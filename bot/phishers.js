const steem = require("steem");
const mysql = require("mysql");
const Db    = require('./db.js');
const Steem_utils = require("./steem_utils");

let db          = new Db();
let settings    = [];
let phish_list  = [];
let spam_list   = [];
let farm_list   = [];
let refreshTime = 5000;
let sutils      = new Steem_utils();
let downvote_percent = 100;
let credits_percent  = 100;
let message_queue = [];

async function main() {
    data()
    setInterval(() => {
        data();
    }, refreshTime);
    
    broadcaster();

    while (true) {
        console.log('detecting..');
        await queue_handler(message_queue);
        await wait(4)
    }
}
function wait(time){
    return new Promise(resolve => {
        setTimeout(() => resolve('☕'), time*1000); // miliseconds to seconds
    });
}
function data() {
    try {
        let connection = mysql.createConnection(db.dbConnection);
        connection.connect(async (err) => {
            if (err) throw err;
            settings     = await db.getSettings(connection);
            phish_list   = await db.getList(connection, 'phishers');
            spam_list    = await db.getList(connection, 'spammers');
            farm_list    = await db.getList(connection, 'farmers');
            if (settings[0].account != '') {
                try {
                    let user = await sutils.customApi("condenser_api.get_accounts", [[settings[0].account]]);
                    let rc   = await sutils.customApi("rc_api.find_rc_accounts", {"accounts":[settings[0].account]});
                    user     = JSON.parse(user).result[0];
                    rc       = JSON.parse(rc).result.rc_accounts[0];
                    downvote_percent = sutils.getDownvotePower(user);
                    credits_percent  = sutils.getRCPower(rc);
                } catch (error) {
                    downvote_percent = 0;
                    credits_percent = 0;
                }
            }
            setTimeout(() => {
                connection.end();
            }, refreshTime - 500);
        });
    } catch (error) {
        console.log("Error: " + error);
        data();
    }
}
function broadcaster() {
    try {
        steem.api.setOptions({ url: 'https://api.steemit.com' });
        steem.api.streamTransactions('head', (err, result) => {
            if (err) throw err; 
            let txType = result.operations[0][0]
            let txData = result.operations[0][1]
            if (settings[0].account != '' && settings[0].key != '') {
                if(txType == 'comment') checkAuthor(txData)
            }else console.log('Set a valid key or username');
            
        });
    } catch (error) {
        broadcaster();
    }
    
}

function checkAuthor(txData){
    let author   = txData.author;
    let permlink = txData.permlink;
    let exist = new Array();
    exist['phishers'] = (phish_list.indexOf(author) > -1) ? true : false;
    exist['spammers'] = (spam_list.indexOf(author) > -1)  ? true : false;
    exist['farmers']  = (farm_list.indexOf(author) > -1)  ? true : false;
    let message = db.messageParser(settings[0].phisher_message, author, exist);
    if (exist['phishers'] || exist['spammers'] || exist['farmers']) {
        phishingAlert(author, permlink, message);
    }
}

 function queue_handler(message_queue) {
    return new Promise(async (resolve) => {
        if (message_queue.length > 0) {
            for (let i = 0; i < message_queue.length; i++) {
                let c = message_queue[i];
                console.log('commenting at...', c.author, c.permlink);
                let result = await err_alert_handle(c.author,c.permlink, c.message);
                if (result == "") message_queue.splice(i, 1), console.log('success');
                else console.log("failed"), resolve(false);
            }
        }
        resolve(true)
    }).then(r => {return r;}); 
    
}

async function phishingAlert (parent_author, parent_permlink, body) {
    console.log("Current credits " + credits_percent, "Current downvote power " + downvote_percent);
    if (settings[0].min_rc_percent > 0) {
        if (credits_percent >= settings[0].min_rc_percent) {
            message_queue.push({
                message:body,
                author:parent_author,
                permlink:parent_permlink
            });
            console.log("Post queued!");
        }else console.log('Not enough credits');
    }else console.log('Alerts are disabled.');
    
    if (settings[0].min_downvote_percent > 0) {
        if (downvote_percent >= settings[0].min_downvote_percent) {
            steem.broadcast.vote(settings[0].key, settings[0].account, parent_author, parent_permlink, -10000, function(err, result) {
                if (err) console.log('Failure! ' + err);
                console.log("downvote was send");
            });
        }else console.log('Not enough downvoting power');
    }else console.log('Downvotes are disabled.');
}
function err_alert_handle(parent_author, parent_permlink, body) {
    return new Promise(async (resolve) => {
        let meta     = JSON.parse('{"app":"phishingtool/1.0"}');
        let permlink = Math.random().toString(36).substring(2)+"-phishing-alert-"+Math.random().toString(36).substring(2);
        let result = await sendAlert(parent_author, parent_permlink, body,meta, permlink);
        if (result == "") console.log('Alert has been created successfully!');
        else {
            for (let k = 0; k < 10; k++) {
                result = await sendAlert(parent_author, parent_permlink, body, meta, permlink);
                if (result === "") {
                    console.log(`Alert has been created successfully!`); 
                }
            }
            resolve("failed")
        }
        resolve("");
    }).then(r => {return r;});    
}
function sendAlert(parent_author, parent_permlink, body, meta, permlink){
    return new Promise(async (resolve) => {
        steem.broadcast.comment(
            settings[0].key, // posting wif
            parent_author, // author, leave blank for new post
            parent_permlink, // first tag
            settings[0].account, // username
            permlink, // permlink
            '', // Title
            body, // Body of post
            // json metadata (additional tags, app name, etc)
            meta,
            async function (error, result) { 
                if (error) {
                    // console.log('Failure! ' + err);
                    if (error.message.indexOf("Can only vote once every 3 seconds") !== -1)
                    console.error("Can only vote once every 3 seconds");
                    else if (error.message === "HTTP 504: Gateway Time-out" || error.message === "HTTP 502: Bad Gateway" || error.message.indexOf("request to https://api.steemit.com failed, reason: connect ETIMEDOUT") !== -1 || error.message.indexOf("transaction tapos exception") !== -1)
                        console.error("Error 504/502");
                    else
                        console.error(error);
                    await wait(5);
                    resolve(error);
                }
                else resolve("");
            }
        );
    }).then(r => {return r;});
    
}
main();