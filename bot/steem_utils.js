const steem = require("steem");
const fetch = require("node-fetch");
steem.api.setOptions({ url: 'https://api.steemit.com' });
class Steem_utils {
    constructor(){
        this.server = "https://api.steemit.com"; 
    }
    getGlobalProps () {
        return new Promise((resolve, reject) => { 
        steem.api.getDynamicGlobalProperties((err, result) => {
            if (result) {
                var per_mvest        = parseFloat(result.total_vesting_fund_steem)*1000000/parseFloat(result.total_vesting_shares);
                var vesting          = parseFloat(result.total_vesting_shares)*1000000;
                result.per_mvest     = per_mvest;
                result.total_vesting = vesting;
                resolve(result);
            }else if(err) reject(err); 
        });
        }).then(resp => { return resp; })
          .catch(err => { return false; });
    }
    getBlog (account, start, limit) {
        return new Promise((resolve, reject) => {
            fetch("https://api.steemit.com/", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({"jsonrpc":"2.0", 
                                    "method":"condenser_api.get_blog",
                                    "params":[account, start, limit],
                                    "id":1})
            }).then(response => { resolve(response.text());})
              .catch(error => { reject(error); });
        });
    }
    customApi (method, params) {
        return new Promise((resolve, reject) => {
            fetch(this.server, {
            method: "POST",
            headers: { 'Content-Type': 'application/json'},
            body: JSON.stringify({"jsonrpc":"2.0","method":method,"params":params,"id":1})
            }).then(response => { resolve(response.text());})
              .catch(error => { reject(error); });
        });
    }
    getDownvotePower(user) {
        let totalShares   = parseFloat(user.vesting_shares) + parseFloat(user.received_vesting_shares) - parseFloat(user.vesting_withdraw_rate) - parseFloat(user.delegated_vesting_shares);
        let maxVotingMana = totalShares * 1000000;
        let current_time  = (Math.round((new Date).getTime()) / 1e3);
        let maxDown       = maxVotingMana  * 0.25;
        let down_elapsed  = (user.downvote_manabar.last_update_time)? current_time - user.downvote_manabar.last_update_time:0;
        let currentDown   = parseInt(user.downvote_manabar.current_mana) + Math.round(maxDown / 432e3 * down_elapsed);
        if (currentDown > maxDown) currentDown = maxDown;
        let percent = currentDown * 100 / maxDown;
        return parseFloat(percent.toFixed(2));
    }
    getRCPower(rc) {
        let elapsed    = Math.round((new Date).getTime() / 1e3) - rc.rc_manabar.last_update_time;
        let max_rc     = parseInt(rc.max_rc);
        let current_rc = parseInt(rc.rc_manabar.current_mana) + Math.round(max_rc / 432e3 * elapsed);
        let percent;
        if (current_rc < max_rc) {
            percent = current_rc * 100 / max_rc;
        }else {
            percent = 100;
            current_rc = max_rc; 
        }
        return parseFloat(percent.toFixed(2));
    }
    
}

module.exports = Steem_utils;