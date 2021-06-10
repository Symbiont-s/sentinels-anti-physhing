let page = $('.currentPage').attr('data-page');

load(page);

$(".second-nav").each(function () {
    let id = $(this).attr("data-id");
    $(this).click(function (e) {
        // waitingScreen.fadeIn(300);
        e.preventDefault();
        hideAll(); 
        $("#" + id).fadeIn(300);
        $(this).addClass('item-active');
        load(id);
    });
});
function load(id) {
    switch (id) {
        case 'profile':
            loadProfile();
            break;
        case 'users':
            loadUsers();
            break;
        case 'links':
            loadLinks();
            break;
        case 'phishers':
            loadBlacklist('phishers');
            break;
        case 'spammers':
            loadBlacklist('spammers');
            break;
        case 'farmers':
            loadBlacklist('farmers');
            break;
        case 'reports':
            loadReports();
            break;
        default:
            $('.clean').each(function () {
                let table = $(this).attr('data-table');
                $(this).click(async function (e) { 
                    e.preventDefault();
                    waitingScreen.fadeIn(300);
                    let r = await clean(table);
                    console.log(r);
                    waitingScreen.fadeOut(300);
                });
            });
            break;
    }
}

function hideAll() {
    $("#users").hide(); 
    $("#links").hide();
    $("#phishers").hide();
    $("#profile").hide();
    $("#advanced").hide();
    $("#reports").hide();
    $("#spammers").hide();
    $("#farmers").hide();
    $(".second-nav").removeClass('item-active');
}


$("#search-link, #search-phisher, #search-logs, #search-spammer, #search-farmer").keyup(async function () {
    let table   = $(this).attr('data-table');
    let html    = $("." + table + "-list");
    let val     = $(this).val();
    html.html(`<tr><td colspan='${table != 'changelog' ? 4 : 3}' class='ta-c'>Loading...</td></tr>`);
    if (val != '') {
        let results = await search(table, val);
        console.log(results, table);
        if (results.length > 0) str = (table == 'links')? linksRender(results):(table == 'changelog')?logsRender(results):blacklistRender(results, table);
        else str = `<tr><td colspan='${table != 'changelog' ? 4 : 3}' class='ta-c'>No results.</td></tr>`; 
        html.html(str);
        (table == 'links')? linksTools(): blacklistTools(table);
    }else (table == 'links')? loadLinks():(table == 'changelog')?loadProfile(): loadBlacklist(table);
});
$("#min_downvote_percent, #min_rc_percent").keyup(function (e) { 
    let val = parseInt($(this).val());
    if (val > 100) $(this).val(100);
    else if (val < -1) $(this).val(-1);
});