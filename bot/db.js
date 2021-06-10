class Db {
    constructor() {
        this.data = {
            host : 'localhost',
            database : 'phishingtooldb',
            user : 'root',
            password : ''
        }
        this.dbConnection = this.getDataConnection();
    }
    getDataConnection(){
        return this.data;
    }
    getSettings(connection){
        return new Promise((resolve,reject) =>{
            connection.query('SELECT * FROM settings', function(err, results){
                if (err)  reject(err);
                else{
                    let settings = [];
                    results.forEach((r) => { 
                        settings.push({
                            account: r.account,
                            key: r.posting_key,
                            phisher_message: r.bot_phisher_message,
                            link_message: r.bot_link_message,
                            min_downvote_percent: r.min_downvote_percent,
                            min_rc_percent: r.min_rc_percent,
                            enable_alerts: r.allow_auto_alerts
                        });
                    });
                    resolve(settings);
                }
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    getList(connection, table){
        return new Promise((resolve,reject) =>{
            connection.query(`SELECT username FROM ${table}`, function(err, results){
                if (err)  reject(err);
                else{
                    let list = [];
                    results.forEach((r) => { 
                        list.push(r.username);
                    });
                    resolve(list);
                }
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    exist(connection, author, table){
        return new Promise((resolve,reject) =>{
            connection.query(`SELECT username FROM ${table} WHERE username LIKE '${author}'`, function(err, results){
                if (err)  reject(err);
                else{
                    let list = [];
                    results.forEach((r) => { list.push(r.username); });
                    if (list.length > 0) resolve(true);
                    else resolve(false); 
                }
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    isFriend(connection, author){
        return new Promise((resolve,reject) =>{
            connection.query(`SELECT friend FROM friends WHERE friend LIKE '${author}'`, function(err, results){
                if (err)  reject(err);
                else{
                    let list = [];
                    results.forEach((r) => { list.push(r.friend); });
                    if (list.length > 0) resolve(true);
                    else  resolve(false); 
                }
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    getLinksList(connection){
        return new Promise((resolve,reject) =>{
            connection.query('SELECT url FROM links', function(err, results){
                if (err)  reject(err);
                else{
                    let list = [];
                    results.forEach((r) => { 
                        list.push(r.url);
                    });
                    resolve(list);
                }
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    getFriends(connection){
        return new Promise((resolve,reject) =>{
            connection.query('SELECT friend FROM friends', function(err, results){
                if (err)  reject(err);
                else{
                    let list = [];
                    results.forEach((r) => { 
                        list.push(r.friend);
                    });
                    resolve(list);
                }
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    saveChange(connection, creator, activity, description, time, info) {
        return new Promise((resolve,reject) =>{
            connection.query(`INSERT INTO changelog (timestamp, responsible, activity,description, information)
            VALUES ('${time}', '${creator}', ${activity}, ${description}, '${info}')`, function(err, results){
                if (err)  reject(err);
            })
        }).then(r => { return r; })
          .catch(e => {console.log('Fail to get info. ' + e); return false;});
    }
    messageParser (body, username, ops) {
        let str = '<ul>';
        body = body.replace('[account]', username);
        if (ops['phishers']) str += '<li>Phishing operations.</li>';
        if (ops['spammers']) str += '<li>Spamming operations.</li>';
        if (ops['farmers']) str += '<li>Farming operations.</li>';
        str += '</ul>';
        body = body.replace ('[operations]', str);
        return body;
    }
}
module.exports = Db;