let user    = $(".users-list");
let link    = $(".links-list");
let phisher = $(".phishers-list");
let report  = $(".reports-list");
let friend  = $(".friends-list");
let changes = $(".changelog-list");
const pagesDisplayed = 5;
const waitingScreen = $(".waitingScreen");
function getTime(time) {
    time      = moment.utc(time.replace(' ', 'T'));
    let now   = moment.utc(); 
    let diff  = now.diff(time, 'seconds');
    let timer = ' sec(s)';
    if (diff >= 60)  {
        //parsing seconds to min
        diff = getHours(diff);
        timer = ' min(s)';
        if (diff >= 60)  {
            //parsing mins to hours
            diff = getHours(diff);
            timer = ' hour(s)';
            if (diff >= 24)  {
                //parsing hours to days
                diff = getDays(diff);
                timer = ' day(s)';
                if (diff >= 365) diff = getYears(diff),timer = ' year(s)';
            }
        }
    }
    return diff + timer;
}
function getHours(time){
    let minutes = 0;
    while(time >= 60) {
        time -= 60;
        minutes++;
    }
    return minutes;
}
function getDays(time){
    let days = 0;
    while(time >= 24) {
        time -= 24;
        days++;
    }
    return days;
}
function getYears(time){
    let years = 0;
    while(time >= 365) {
        time -= 365;
        years++;
    }
    return years;
}
function getPagination(table, field) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'pagination', table:table, field:field},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function fetchData(action = 'users', start=0, limit=50) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:action, start:start,limit:limit},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function existCriminal(username) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'exist', username:username},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function getLogs(start=0, limit=50) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'log', start:start, limit:limit},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function search(table, val) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'search', table:table, val:val},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function clean(table) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'clean', table:table},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function deleteQuery(id, table = 'users') {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'delete',id:id, table:table},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function acceptQuery(id, phishing) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'accept',id:id, phishing:phishing},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function updateUser(id, val) {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'update',id:id, val:val},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function getAccountStatus() {
    return new Promise((resolve) => {
        $.ajax({
            method: "POST",
            url: "./action/fetch",
            data: {action:'account_status'},
            dataType: "json",
            success: function (response) { 
                resolve(response); 
            }
        });
    }).then(data => {return data});
}
function loadUsers () {
    (async () => {
        let users   = await fetchData();
        let friends = await fetchData('friends');
        let pag     = await getPagination('users', 'id');
        let pag2    = await getPagination('friends', 'id');
        let str     = ''; 
        let str2    = ''; 
        if (users.length > 0) str = usersRender(users);
        else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
        if (friends.length > 0) str2 = friendsRender(friends);
        else str2 = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`; 
        user.html(str);
        friend.html(str2);
        paginationRender($('.users-pagination'), pag, 'users', user, 4);
        paginationRender($('.friends-pagination'), pag2, 'friends', friend, 4);
        waitingScreen.fadeOut(300);
        usersTools();
        friendsTools();
    })();
}
function usersRender(users) {
    let i = 1;
    let str = '';
    users.forEach(user => {
        let status = (user.status != 0)? "<div class='c-green'>On</div>" : "<div class='c-red'>Off</div>";
        let action = (user.status != 0)? "Lock" : "Unlock";
        str += `<tr>
        <td>${i}</td>
        <td>${user.username}</td>
        <td>${status}</td>
        <td class="ta-c position-relative">
            <a href="#" class="displayMenu" data-id="${user.id}"><span class="glyphicon glyphicon-option-horizontal"></span></a>
            <ul class="content menu" id="option-list-${user.id}" style="display:none;">
                <li class="remove" data-id="${user.id}">Delete</li>
                <li class="update" data-id="${user.id}" data-action="${action}">${action}</li> 
            </ul>
        </td>
        </tr>`;
        i++;
    });
    return str;
}
function friendsRender(friends) {
    let i = 1;
    let str = '';
    friends.forEach(friend => {
        let time = getTime(friend.timestamp);
        str += `<tr>
        <td>${i}</td>
        <td>${friend.username}</td>
        <td>${time}</td>
        <td class="ta-c position-relative">
            <a href="#" class="displayFriendMenu" data-id="${friend.id}"><span class="glyphicon glyphicon-option-horizontal"></span></a>
            <ul class="content menu" id="option-list-friend-${friend.id}" style="display:none;">
                <li class="removeFriend" data-id="${friend.id}">Delete</li> 
            </ul>
        </td>
        </tr>`;
        i++;
    });
    return str;
}
function usersTools() {
    $(".displayMenu").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(function (e) { 
            e.preventDefault();
            let c = $("#option-list-" + id);
            c.toggle(200);
        });
    });
    $(".remove").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(async function (e) { 
            waitingScreen.fadeIn(300);
            e.preventDefault();
            let remove = await deleteQuery(id);
            console.log(remove);
            if (remove.status == "success") {
                console.log('operation success');
                loadUsers();
            }else if(remove.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else{
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
    $(".update").each(function () {
        let id = $(this).attr("data-id");
        let action = $(this).attr("data-action");
        $(this).click(async function (e) { 
            waitingScreen.fadeIn(300);
            e.preventDefault();
            let update = await updateUser(id, action);
            console.log(update);
            if (update.status == "success") {
                console.log('operation success');
                loadUsers();
            }else if(update.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else {
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
}
function friendsTools(){
    $(".displayFriendMenu").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(function (e) { 
            e.preventDefault();
            let c = $("#option-list-friend-" + id);
            c.toggle(200);
        });
    });
    $(".removeFriend").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(async function (e) { 
            waitingScreen.fadeIn(300);
            e.preventDefault();
            let remove = await deleteQuery(id, 'friends');
            console.log(remove);
            if (remove.status == "success") {
                console.log('operation success');
                loadUsers();
            }else if(remove.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else{
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
}
function loadLinks() {
    (async () => {
        let links = await fetchData('links');
        let pag   = await getPagination('links', 'id');
        let str   = '';
        if (links.length > 0) str += linksRender(links);
        else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`; 
        link.html(str);
        paginationRender($('.links-pagination'), pag, 'links', link, 4);
        waitingScreen.fadeOut(300);
        linksTools();
    })();
}
function linksRender(links) {
    let str = '';
    links.forEach(link => {
        let time = getTime(link.timestamp);
        str += `<tr>
        <td>${link.url}</td>
        <td>${link.creator}</td>
        <td>${time}</td>
        <td class="ta-c position-relative">
            <a href="#" class="displayMenu" data-id="${link.id}"><span class="glyphicon glyphicon-option-horizontal"></span></a>
            <ul class="content menu" id="option-list-link-${link.id}" style="display:none;">
                <li class="remove" data-id="${link.id}">Delete</li> 
            </ul>
        </td>
        </tr>`;
    });
    return str;
}
function linksTools() {
    $(".displayMenu").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(function (e) { 
            e.preventDefault();
            let c = $("#option-list-link-" + id);
            c.toggle(200);
        });
    });
    $(".remove").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(async function (e) { 
            waitingScreen.fadeIn(300);
            e.preventDefault();
            let remove = await deleteQuery(id, 'links');
            console.log(remove);
            if (remove.status == "success") {
                console.log('operation success');
                loadLinks();
            }else if(remove.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else{
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
}
function loadBlacklist(id) {
    (async () => {
        let elm = $(`.${id}-list`);
        let blacklist = await fetchData(id);
        let pag      = await getPagination(id, 'id');
        let settings = await fetchData('settings');
        let str = '';
        if (blacklist.length > 0) str = blacklistRender(blacklist, id);
        else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
        $("#following_account").val(settings[0].following_account);
        elm.html(str);
        paginationRender($(`.${id}-pagination`), pag, id, elm, 4);
        waitingScreen.fadeOut(300);
        blacklistTools(id);
    })();
}
function blacklistRender(blacklist, id) {
    let str = '';
    blacklist.forEach(user => { 
        let time = getTime(user.timestamp);
        str += `<tr>
        <td>${user.username}</td>
        <td>${user.creator}</td>
        <td>${time}</td>
        <td class="ta-c position-relative">
            <a href="#" class="displayMenu" data-id="${user.id}"><span class="glyphicon glyphicon-option-horizontal"></span></a>
            <ul class="content menu" id="option-list-${id}-${user.id}" style="display:none;">
                <li class="remove" data-id="${user.id}">Delete</li>
                <li class="sendAlerts" data-id="${user.id}" data-user="${user.username}">Alerts!</li>
            </ul>
        </td>
        </tr>`;
    });
    return str;
}
function blacklistTools(id) {
    $(".displayMenu").each(function () {
        let id2 = $(this).attr("data-id");
        $(this).click(function (e) { 
            e.preventDefault();
            let c = $(`#option-list-${id}-${id2}`);
            c.toggle(200);
        });
    });
    $('.sendAlerts').each(function () {
        let username = $(this).attr('data-user');
        let feedback = $('.custom-alert');
        $(this).click(async function (e) { 
            e.preventDefault();
            waitingScreen.fadeIn(300);
            let settings = await fetchData('settings');
            let blog     = await getBlog(username, 0, 1);
            let exist    = await existCriminal(username);
            latest_num   = JSON.parse(blog).result[0].entry_id;
            latest_num = (parseInt(latest_num) > 50)? 50 : latest_num;
            blog         = await getBlog(username, 0, latest_num);
            blog         = JSON.parse(blog).result;
            
            let latest   = []; 
            
            blog.forEach(b => {
                let author   = b.comment.author;
                let created  = moment.utc(b.comment.created);
                let now      = moment.utc();
                let diff     = now.diff(created, 'days');
                if (author == username && diff < 7) latest.push(b);
            });
            waitingScreen.fadeOut(300);
            if (latest.length > 0) {
                let i = 0;
                $('custom-text').html('Sending alerts...');
                feedback.toggle(600);
                let message  = messageParser(settings[0].phisher_message, username, exist);
                let interval = setInterval(() => { 
                    console.log('alerting...');
                    let b        = latest[i];
                    let permlink = b.comment.permlink;
                    let author   = b.comment.author;
                    console.log(author, permlink);
                    sendAlert(author, permlink, settings, message);
                    i++;
                    if (i >= latest.length) clearInterval(interval);
                }, 4000);
                
            }else {
                $('custom-text').html('No alerts to send.');
                feedback.toggle(600);
            }
            setTimeout(() => {
                feedback.toggle(600);
            }, 5000);
            
        });
    });
    $(".remove").each(function () {
        let id2 = $(this).attr("data-id");
        $(this).click(async function (e) { 
            e.preventDefault();
            waitingScreen.fadeIn(300);
            let remove = await deleteQuery(id2, id);
            console.log(remove);
            if (remove.status == "success") {
                console.log('operation success');
                loadBlacklist(id);
            }else if(remove.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else{
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
}
function loadReports() {
    (async () => {
        let reports = await fetchData('reports');
        let pag     = await getPagination('report', 'id');
        let str     = ''; 
        if (reports.length > 0) str = reportRender(reports);
        else str = `<tr> <td colspan='5' class='ta-c'>Empty</td> </tr>`; 
        report.html(str);
        paginationRender($('.report-pagination'), pag, 'report', report, 5);
        waitingScreen.fadeOut(300);
        reportTools();
    })();
}
function reportRender(reports) {
    let str = '';
    reports.forEach(report => { 
        let time = getTime(report.timestamp);
        let phisher = (report.phisher == null)?'':report.phisher;
        let link    = (report.link == null)?'':report.link;
        l           = `<a href='${link}' title='${link}'>`;
        l           += (link.length > 21)? `${link.substring(0, 21)}...</a>` : `${link}</a>`; 
        let buttom  = (phisher == '')? `<li class="accept" data-id="${report.id}" data-phishing="links">Accept</li>`:
                                        `<li class="accept" data-id="${report.id}" data-phishing="phishers">Accept</li>`;
        str += `<tr>
        <td>${l}</td>
        <td>${phisher}</td>
        <td>${report.explanation}</td>
        <td>${time}</td>
        <td class="ta-c position-relative">
            <a href="#" class="displayMenu" data-id="${report.id}"><span class="glyphicon glyphicon-option-horizontal"></span></a>
            <ul class="content menu" id="option-list-report-${report.id}" style="display:none; width:180px;">
                <li class="remove" data-id="${report.id}">Delete</li>
                ${buttom}
            </ul>
        </td>
        </tr>`;
    });
    return str;
}
function reportTools() {
    $(".displayMenu").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(function (e) { 
            e.preventDefault();
            let c = $("#option-list-report-" + id);
            c.toggle(200);
        });
    });
    $(".remove").each(function () {
        let id = $(this).attr("data-id");
        $(this).click(async function (e) { 
            e.preventDefault();
            waitingScreen.fadeIn(300);
            let remove = await deleteQuery(id, 'report'); 
            if (remove.status == "success") {
                console.log('operation success');
                loadReports();
            }else if(remove.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else{
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
    $(".accept").each(function () {
        let id = $(this).attr("data-id");
        let p  = $(this).attr("data-phishing");
        $(this).click(async function (e) { 
            e.preventDefault();
            waitingScreen.fadeIn(300);
            let accept = await acceptQuery(id, p); 
            console.log(accept);
            if (accept.status == "success") {
                console.log('operation success');
                loadReports();
            }else if(accept.status == 'forbiden'){
                alert('You cannot do this action now!');
                waitingScreen.fadeOut(300);
            }else{
                console.log('fail to delete');
                alert('Ops! failed to update');
                waitingScreen.fadeOut(300);
            }
        });
    });
}
function loadProfile() {
    (async () => {
        let status = await getAccountStatus();
        let pag    = await getPagination('changelog', 'id');
        let logs   = await getLogs();
        let s      = '';
        let str    = (status[0].status == 1) ? `<div class="c-green">Operational</div>` : `<div class="c-red">Banned</div>`;
        $(".account-status").html(str);
        if (logs.length > 0) s = logsRender(logs);
        else s = `<tr><td colspan='3' class='ta-c'>Empty.</td></tr>`;
        changes.html(s);
        paginationRender($('.logs-pagination'), pag, 'logs', changes, 3);
        waitingScreen.fadeOut(300);
    })();
}
function logsRender(logs) {
    let str = '';
    logs.forEach(log => {
        let time = getTime(log.timestamp);
        let info = (log.information == null)?'':log.information;
        let description = `<b>${log.responsible}</b> ${describe(log.activity, log.description)}`;
        str += `<tr><td>${time}</td><td>${description}</td><td>${info}</td></tr>`;
    });
    return str;
}
function describe(activity, description) {
    let str = '';
    switch (parseInt(activity)) {
        case 1:
            str += 'create a new ';
            break;
        case 2:
            str += 'updated ';
            break;
        case 3:
            str += 'deleted ';
            break;
        case 4:
            str += 'accepted ';
            break;
        default:
            str += 'do an unexpected action.';
            break;
    }
    switch (parseInt(description)) {
        case 1:
            str += 'settings.';
            break;
        case 2:
            str += 'user.';
            break;
        case 3:
            str += 'friend.';
            break;
        case 4:
            str += 'link.';
            break;
        case 5:
            str += 'phisher.';
            break;
        case 6:
            str += 'report.';
            break;
        case 7:
            str += 'logs.';
            break;
        case 8:
            str += 'spammer.';
            break;
        case 9:
            str += 'farmer.';
            break;
        default:
            break;
    }
    return str;
}
// elm - jquery object to render the pagination
// obj - important data about the pages fetch with getPagination()
// id  - identifier for table
// item - object where render the table
function paginationRender(elm, obj, id, item, n) {
    let str = '';
    for (let i = 1; i <= obj.pages; i++) {
        str += `<li class="page-item ${id}-page-item ${id}-page-${i} ${i == 1 ? 'active first':''} ${i == obj.pages ? 'last':''}" data-page="${i}" ${(i > pagesDisplayed && i != obj.pages) ? 'style="display:none;"':''}><a class="page-link ${id}-page-link" data-page="${i}" href="">${i}</a></li>`;
    }
    elm.html(str);
    enablePagination(id, item, n, obj);
}
function enablePagination(id, item, n, obj) {
    $(`.${id}-page-link`).each(function () { 
        let page = $(this).attr('data-page');
        $(this).click(async function (e) { 
            e.preventDefault();
            let start = (obj.limit*page)-obj.limit;
            item.html(`<tr><td colspan='${n}' class='ta-c'>Loading...</td></tr>`);
            switch (id) {
                case 'logs':
                    let logs = await getLogs(start, obj.limit);
                    if (logs.length > 0) var s = logsRender(logs);
                    else var s = `<tr><td colspan='3' class='ta-c'>Empty.</td></tr>`;
                    item.html(s);
                    break;
                case 'users':
                        let users   = await fetchData('users',start, obj.limit);
                        if (users.length > 0) var str = usersRender(users);
                        else var str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
                        item.html(str);
                        usersTools();
                    break;
                case 'friends':
                    let friends   = await fetchData('friends',start, obj.limit);
                    if (friends.length > 0) var str = friendsRender(friends);
                    else var str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
                    item.html(str);
                    friendsTools();
                    break;
                case 'links':
                    let links = await fetchData('links',start, obj.limit);
                    if (links.length > 0) str += linksRender(links);
                    else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
                    item.html(str);
                    linksTools();
                    break;
                case 'phishers':
                    let phishers = await fetchData('phishers',start, obj.limit);
                    if (phishers.length > 0) str += blacklistRender(phishers, id);
                    else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
                    item.html(str);
                    blacklistTools(id);
                    break;
                case 'spammers':
                    let spammers = await fetchData('spammers',start, obj.limit);
                    if (spammers.length > 0) str += blacklistRender(spammers, id);
                    else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
                    item.html(str);
                    blacklistTools(id);
                    break;
                case 'farmers':
                    let farmers = await fetchData('farmers',start, obj.limit);
                    if (farmers.length > 0) str += blacklistRender(farmers, id);
                    else str = `<tr> <td colspan='4' class='ta-c'>Empty</td> </tr>`;
                    item.html(str);
                    blacklistTools(id);
                    break;
                case 'report':
                    let reports = await fetchData('reports',start, obj.limit);
                    if (reports.length > 0) var str = reportRender(reports);
                    else var str = `<tr> <td colspan='5' class='ta-c'>Empty</td> </tr>`; 
                    item.html(str);
                    reportTools();
                    break;
                default:
                    break;
            }
        });
    });
    $(`.${id}-page-item`).each(function () { 
        let current = $(this).attr('data-page');
        $(this).click(function (e) { 
            e.preventDefault();
            if (obj.pages > pagesDisplayed) {
                updateNum(current,id, obj.pages);
            }
            $(`.${id}-page-item`).removeClass('active');
            $(this).addClass('active');
        });
    });
}
function updateNum(current, id) {
    $(`.${id}-page-item:not(.first, .last)`).hide();
    current = parseInt(current);
    if (current < pagesDisplayed) {
        let selector = '';
        for (let i = 1; i <= pagesDisplayed; i++) { selector += `.${id}-page-${i}`; selector += i == pagesDisplayed ? '':','; }
        $(selector).show();
    }else{
        selector = `.${id}-page-${current-2},.${id}-page-${current-1}, .${id}-page-${current}, .${id}-page-${current+1}, .${id}-page-${current+2}`;
        $(selector).show();
    }
}
const checkAccountName = async (username) => {
    if (username == '') return false;
    const [ac] = await steem.api.getAccountsAsync([username]);
    return (ac === undefined) ? false : true;
}
const getBlog = async (account, start, limit) => {
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
async function sendAlert (parent_author, parent_permlink, settings, message) {
    let meta     = JSON.parse('{"app":"phishingtool/1.0"}');
    let permlink = Math.random().toString(36).substring(2)+"-phishing-alert-"+Math.random().toString(36).substring(2); 
    steem.broadcast.comment(
        settings[0].key, // posting wif
        parent_author, // author, leave blank for new post
        parent_permlink, // first tag
        settings[0].account, // username
        permlink, // permlink
        '', // Title
        message, // Body of post
        // json metadata (additional tags, app name, etc)
        meta,
        function (err, result) { 
            if (err) console.log('Failure! ' + err);
            else console.log('Alert has been created successfully!'); 
        }
    );  
}
function messageParser (body, username, ops) {
    let str = '<ul>';
    body = body.replace('[account]', username);
    if (ops['phishers']) str += '<li>Phishing operations.</li>';
    if (ops['spammers']) str += '<li>Spamming operations.</li>';
    if (ops['farmers']) str += '<li>Farming operations.</li>';
    str += '</ul>';
    body = body.replace ('[operations]', str);
    return body;
}
