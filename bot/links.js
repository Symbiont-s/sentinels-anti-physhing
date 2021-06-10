const steem  = require("steem");
const mysql  = require("mysql");
const moment = require('moment');
const Db     = require('./db.js');
const Steem_utils = require("./steem_utils");

let db          = new Db();
let settings    = [];
let list        = [];
let refreshTime = 5000;
let sutils      = new Steem_utils();
let downvote_percent = 100;
let credits_percent  = 100;

async function main() {
    setInterval(() => {
        data();
    }, refreshTime);
    
    broadcaster();
}
function data() {
    try {
        let connection = mysql.createConnection(db.dbConnection);
        connection.connect(async (err) => {
            if (err) throw err;
            settings = await db.getSettings(connection);
            list     = await db.getLinksList(connection);
            if (settings[0].account != '') {
                let user = await sutils.customApi("condenser_api.get_accounts", [[settings[0].account]]);
                let rc   = await sutils.customApi("rc_api.find_rc_accounts", {"accounts":[settings[0].account]});
                user     = JSON.parse(user).result[0];
                rc       = JSON.parse(rc).result.rc_accounts[0];
                downvote_percent = sutils.getDownvotePower(user);
                credits_percent  = sutils.getRCPower(rc);
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
    steem.api.setOptions({ url: 'https://api.steemit.com' });
    steem.api.streamTransactions('head', (err, result) => { 
        let txType = result.operations[0][0]
        let txData = result.operations[0][1]
        if (list.length > 0 && settings[0].account != '' && settings[0].key != '') {
            if(txType == 'comment') checkLinks(txData)
            else if (txType == 'transfer') checkTransfer(txData);
        }
        
    });
}
function createNewPhisher(author, creator) {
    try {
        let connection = mysql.createConnection(db.dbConnection);
        connection.connect(async (err) => {
            if (err) throw err;
            let exist = await db.phisherExist(connection, author);
            if (!exist) {
                let time = moment.utc().format('YYYY-MM-DD HH:mm:ss');
                connection.query(`INSERT INTO phishers (username, creator, timestamp) VALUES ('${author}','${settings[0].account}', '${time}')`, (err, results) => {
                    if (err) throw "Can't save new phisher";
                    db.saveChange(connection, creator, 1,5, time, author);
                    setTimeout(() => {
                        connection.end();
                    }, 3000);
                });
            }else {
                connection.end();
            }
        })
    } catch (error) {
        console.log(error);
    } 
}
function checkTransfer(txData) {
    if (txData.memo != '') {
        if (isPhishing(txData.memo)) createNewPhisher(txData.from, settings[0].account);
    }
}
function checkLinks(txData){
    let author   = txData.author;
    let permlink = txData.permlink;
    if (isPhishing(txData.json_metadata)){ 
        phishingAlert(author, permlink);
        createNewPhisher(author, settings[0].account);
    }
}

function isPhishing(json){
    let res = false;
    list.forEach(url => {
        if (json.indexOf(url) > -1) res = true;
    });
    return res;
}
function phishingAlert (parent_author, parent_permlink) {
    let meta     = JSON.parse('{"app":"phishingtool/1.0"}');
    let permlink = Math.random().toString(36).substring(2)+"-phishing-alert-"+Math.random().toString(36).substring(2);
    console.log("Current credits " + credits_percent, "Current downvote power " + downvote_percent);
    if (settings[0].min_rc_percent > 0) {
        if (credits_percent >= settings[0].min_rc_percent) {
            steem.broadcast.comment(
                settings[0].key, // posting wif
                parent_author, // author, leave blank for new post
                parent_permlink, // first tag
                settings[0].account, // username
                permlink, // permlink
                '', // Title
                settings[0].link_message, // Body of post
                // json metadata (additional tags, app name, etc)
                meta,
                function (err, result) { 
                    if (err) console.log('Failure! ' + err);
                    else {
                        console.log('Alert has been created successfully!');
                    }
                }
            );
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
main();