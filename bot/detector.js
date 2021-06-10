const steem = require("steem");
const mysql = require("mysql");
const moment = require('moment');
const Db    = require('./db.js');
const Steem_utils = require("./steem_utils");

let db          = new Db();
let settings    = [];
let list        = [];
let refreshTime = 5000;
let sutils      = new Steem_utils();
let downvote_percent = 100;
let credits_percent  = 100;

async function main() {
    data();
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
            list     = await db.getFriends(connection);
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
        try {
            let txType = result.operations[0][0]
            let txData = result.operations[0][1]
            if (list.length > 0 && settings[0].account != '' && settings[0].key != '') {
                if(txType == 'comment') checkCommand(txData)
            }
        } catch (error) {
            console.log(error);
            broadcaster();
        }
    });
}

function checkCommand(txData){
    let author   = txData.author;
    let permlink = txData.permlink;
    let body     = txData.body;
    let command1 = body.substring(0, 8);
    let command2 = body.substring(0, 6);
    let command3 = body.substring(0, 8);
    let command4 = body.substring(0, 7);
    let criminal  = txData.parent_author; 
    if (isFriend(author) && criminal != '' && command1 == '!contain') sendAlert(author, permlink, `This command is deprecated. Use <code>!phisher</code>, <code>!spammer</code> or <code>!farmer</code> instead.`);
    else if (isFriend(author) && criminal != '' && command2 == '!clear') remove(criminal,author, permlink);
    else if (isFriend(author) && criminal != '' && command3 == '!phisher') addCriminal(criminal,author, permlink, "phishers");
    else if (isFriend(author) && criminal != '' && command3 == '!spammer') addCriminal(criminal,author, permlink, "spammers");
    else if (isFriend(author) && criminal != '' && command4 == '!farmer') addCriminal(criminal,author, permlink, "farmers");
}
function addCriminal(criminal, author, permlink, crime) {
    try {
        let connection = mysql.createConnection(db.dbConnection);
        connection.connect(async (err) => {
            if (err) throw err;
            let exist = new Array();
            exist['phishers']  = await db.exist(connection, criminal, 'phishers');
            exist['spammers']  = await db.exist(connection, criminal, 'spammers');
            exist['farmers']   = await db.exist(connection, criminal, 'farmers');
            let isFriend = await db.isFriend(connection, criminal);
            if (!isFriend) {
                if (!exist[crime]) {
                    let time = moment.utc().format('YYYY-MM-DD HH:mm:ss');
                    connection.query(`INSERT INTO ${crime} (username, creator, timestamp) VALUES ('${criminal}','${author}', '${time}')`, async (err, results) => {
                        if (err) throw "Can't save new criminal";
                        sendAlert(author, permlink);
                        let index = 0;
                        exist[crime] = true;
                        let message = db.messageParser(settings[0].phisher_message, criminal, exist);
                        switch (crime) {
                            case 'phishers': index = 5; break;
                            case 'spammers': index = 8; break;
                            case 'farmers': index  = 9; break;
                            default:
                                break;
                        }
                        db.saveChange(connection, author, 1,index, time, criminal);

                        if (settings[0].enable_alerts == 1) {
                            let blog     = await sutils.getBlog(criminal, 0, 1);
                            latest_num   = JSON.parse(blog).result[0].entry_id;
                            latest_num   = (parseInt(latest_num) > 50)? 50 : latest_num;
                            blog         = await sutils.getBlog(criminal, 0, latest_num);
                            blog         = JSON.parse(blog).result;
                            let latest   = [];
                            blog.forEach(b => {
                                let current  = b.comment.author;
                                let created  = moment.utc(b.comment.created);
                                let now      = moment.utc();
                                let diff     = now.diff(created, 'days');
                                if (criminal == current && diff < 7) latest.push(b);
                            });
                            if (latest.length > 0) {
                                let i = 0;
                                let interval = setInterval(() => { 
                                    console.log('alerting...');
                                    let b        = latest[i];
                                    let permlink = b.comment.permlink;
                                    let author   = b.comment.author;
                                    console.log(author, permlink);
                                    sendAlert(author, permlink, message);
                                    i++;
                                    if (i >= latest.length) clearInterval(interval);
                                }, 4000);
                                
                            }else console.log('nothing to send');
                        }else console.log('Alerts disabled');
                        setTimeout(() => {
                            connection.end();
                        }, 3000);
                    });
                }else {
                    sendAlert(author, permlink, 'The threat is already contained.');
                    connection.end();
                }
            }else {
                sendAlert(author, permlink, "You can't blacklist Sentinels!");
                connection.end();
            }
            
        })
    } catch (error) {
        console.log(error);
    } 
}
async function remove(criminal, author, permlink) {
    let exist = [false, false, false];
    exist[0] = await removeQuery(criminal, author, 'phishers', 5);
    exist[1] = await removeQuery(criminal, author, 'spammers', 8);
    exist[2] = await removeQuery(criminal, author, 'farmers', 9);
    if (exist[0] || exist[1] || exist[2]) sendAlert(author, permlink, 'Threat cleared.');
    else sendAlert(author, permlink, "The threat doesn't exist.");
}
function removeQuery(criminal,author, table, index) {
    return new Promise ((resolve, reject) => {
        try {
            let connection = mysql.createConnection(db.dbConnection);
            connection.connect(async (err) => {
                if (err) throw err;
                let exist = await db.exist(connection, criminal, table);
                if (exist) { 
                    connection.query(`DELETE FROM ${table} WHERE username LIKE '${criminal}'`, (err, results) => {
                        if (err) throw "Can't delete the threat";
                        let time = moment.utc().format('YYYY-MM-DD HH:mm:ss');
                        db.saveChange(connection, author, 3,index, time, criminal);
                        setTimeout(() => { connection.end(); }, 2000); 
                        resolve(true);
                    });
                }else { connection.end(); resolve(false); }
            })
        } catch (error) { console.log(error); resolve(false); } 
    }).then(r => { return r; })
      .catch(e => {console.log('Fail to get info. ' + e); return false;});
    
}
function isFriend(name){
    return (list.indexOf(name) > -1);
}
function sendAlert (parent_author, parent_permlink, response = 'Threat contained!') {
    let meta     = JSON.parse('{"app":"phishingtool/1.0"}');
    let permlink = Math.random().toString(36).substring(2)+"-contain-alert-"+Math.random().toString(36).substring(2);
    console.log("Current credits " + credits_percent, "Current downvote power " + downvote_percent); 
    steem.broadcast.comment(
        settings[0].key, // posting wif
        parent_author, // author, leave blank for new post
        parent_permlink, // first tag
        settings[0].account, // username
        permlink, // permlink
        '', // Title
        response, // Body of post
        // json metadata (additional tags, app name, etc)
        meta,
        function (err, result) { 
            if (err) console.log('Failure! ' + err);
            else {
                console.log('Alert has been created successfully!');
            }
        }
    );  
}
main();