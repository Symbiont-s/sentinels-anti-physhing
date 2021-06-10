<?php
    require_once('../controller/session_time.php'); 
    if (empty($_SESSION['username'])) {
        header("location:./signin");
    }
    include("../controller/settings.php");
?>
<!DOCTYPE html>
<html lang="en-EN">
<head>
    <?php include("templates/libraries.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="Steem Sentinels" />
    <meta http-equiv="default-style" content="default-stylesheet" />
    <meta http-equiv="X-UA-Compatible" content="IE=7, chrome=1, firefox=1, opera=1, ie=edge">
    <meta name="title" content="Main Page">
    <meta name="description" content="Cybercrime Awareness & Prevention | Steem Blockchain">
    <meta name="keywords" content="ecosynthesizer, steem, steemit, symbionts, blockchain, anti-phishing">
    <meta name="encoding" charset="utf-8" />
    <meta name="author" content="Symbionts Team">
    <meta name="copyright" content="Symbionts">
    <meta name="robots" content="index, follow"/>
    <title>Steem Sentinels</title>
</head>
<body>
    <header>
        <?php 
            include("templates/navbar.php");
            $profile = ['','style="display:none;"'];
            $advance = ['','style="display:none;"'];
            $users   = ['','style="display:none;"'];
            $links   = ['','style="display:none;"'];
            $phish   = ['','style="display:none;"'];
            $spam    = ['','style="display:none;"'];
            $farm    = ['','style="display:none;"'];
            $report  = ['','style="display:none;"'];
            $page    = '';
            if (isset($_GET['page'])) {
                $param   = $_GET['page'];
                if ($param == 'links') { $links[0] = 'item-active'; $links[1] = ''; $page = 'links'; }
                else if ($param == 'phishers') { $phish[0] = 'item-active'; $phish[1] = ''; $page = 'phishers'; }
                else if ($param == 'spammers') { $spam[0] = 'item-active'; $spam[1] = ''; $page = 'spammers'; }
                else if ($param == 'farmers') { $farm[0] = 'item-active'; $farm[1] = ''; $page = 'farmers'; }
                else if ($param == 'reports') { $report[0] = 'item-active'; $report[1] = ''; $page = 'reports'; }
                else if ($param == 'profile') { $profile[0] = 'item-active'; $profile[1] = ''; $page = 'profile'; }
                else if ($param == 'users') { $users[0] = 'item-active'; $users[1] = ''; $page = 'users'; }
                else if ($param == 'advanced') {
                    if ($_SESSION['username'] == 'root') { $advance[0] = 'item-active'; $advance[1] = ''; $page = 'advanced'; }
                    else { $profile[0] = 'item-active'; $profile[1] = ''; $page = 'profile'; }
                }
            }else {
                $profile[0] = 'item-active'; $profile[1] = ''; $page = 'profile'; 
            }
        ?> 
    </header>
    <section>
        <div class="currentPage" data-page='<?php echo $page; ?>'></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="container">
                            <div class="col-12">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="margin-top:7px; margin-bottom:7px;">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarNav">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $profile[0]; ?>" href="#" data-id="profile">Profile</a>
                                        </li>
                                        <?php 
                                            if ($_SESSION['username'] == 'root') {
                                                echo '<li class="nav-item">
                                                        <a class="nav-link second-nav ' . $advance[0] . '" href="#" data-id="advanced">Advanced</a>
                                                    </li>
                                                    ';
                                            }
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $users[0]; ?>" href="#" data-id="users">Managers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $links[0]; ?>" href="#" data-id="links">Links</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $phish[0]; ?>" href="#" data-id="phishers">Phishers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $spam[0]; ?>" href="#" data-id="spammers">Spammers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $farm[0]; ?>" href="#" data-id="farmers">Farmers</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link second-nav <?php echo $report[0]; ?>" href="#" data-id="reports">Reports</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- ALERTS -->
        <div class="container">
            <div class="row mt-2">
                <div class="col-12 apialert">
                    <?php if(isset($_GET['created']) && $_GET['created'] == 'true'){ ?>
                    <div class="alert alert-success alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Manager has been created successfully.</sup>
                    </div>
                    <?php }else if(isset($_GET['created']) && $_GET['created'] != 'true'){ ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Manager was no created.</sup>
                    </div>
                    <?php } ?>
                    <?php if(isset($_GET['banned']) && $_GET['banned'] == 'true'){ ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>WARNING: Your account has been locked.</sup>
                    </div>
                    <?php } ?>
                    <?php if(isset($_GET['changed']) && $_GET['changed'] == 'true'){ ?>
                    <div class="alert alert-success alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Your password has been changed successfully.</sup>
                    </div>
                    <?php }else if(isset($_GET['changed']) && $_GET['changed'] != 'true'){ ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Your password was no changed.</sup>
                    </div>
                    <?php } ?>
                    <?php if(isset($_GET['exist']) && $_GET['exist'] == 'true'){ ?>
                    <div class="alert alert-info alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>This entry already exist.</sup>
                    </div>
                    <?php } ?>
                    <?php if(isset($_GET['added']) && $_GET['added'] == 'true'){ ?>
                    <div class="alert alert-success alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Your entry was added successfully.</sup>
                    </div>
                    <?php }else if(isset($_GET['added']) && $_GET['added'] != 'true'){ ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Your entry was no added.</sup>
                    </div>
                    <?php } ?>
                </div> 
            </div>
        </div>
        
        <div class="waitingScreen" style="display:none;"></div>

        <!-- SECTIONS -->
        <div class="container" style="margin-top:8px;">
            <?php if ($_SESSION['username'] == 'root') {  ?>
                <div class="row" id="advanced" <?php echo $advance[1]; ?>>
                    <div class="col-12 col-sm-12 col-md-6">
                        <form action="./auth/edit" method="post" class="content">
                            <h2 class="ta-c">Generals</h2>
                            <div class="form-group">
                                <label for="allow_following_account" style="display:inline-block; width:60%;">Enable Following Account</label>
                                <input type="checkbox" class="form-control" name='allow_following_account' id='allow_following_account' style="display:inline-block; width:25%; height:15px;" <?php echo ($settings['enable_following'] == 1)?"checked":""; ?>>
                            </div>
                            <div class="form-group">
                                <label for="allow_auto_alerts" style="display:inline-block; width:60%;">Enable Auto Alerts</label>
                                <input type="checkbox" class="form-control" name='allow_auto_alerts' id='allow_auto_alerts' style="display:inline-block; width:25%; height:15px;" <?php echo ($settings['enable_alerts'] == 1)?"checked":""; ?>>
                            </div>
                            <button type="submit" class="btn btn-filter" name="save" id="save">Save</button>
                        </form>
                        <div class="content mt-2">
                            <h2>Maintenance</h2>
                            <button type="submit" class="btn btn-filter clean mt-2" name="clean-logs" data-table='changelog'>Clean logs</button>
                            <button type="submit" class="btn btn-filter clean mt-2" name="clean-phishers" data-table='phishers'>Clean Phishers</button>
                            <button type="submit" class="btn btn-filter clean mt-2" name="clean-spammers" data-table='spammers'>Clean Spammers</button>
                            <button type="submit" class="btn btn-filter clean mt-2" name="clean-farmers" data-table='farmers'>Clean Farmers</button>
                            <button type="submit" class="btn btn-filter clean mt-2" name="clean-links" data-table='links'>Clean Links</button>
                            <button type="submit" class="btn btn-filter clean mt-2" name="clean-reports" data-table='report'>Clean Reports</button>
                        </div>
                    </div> 
                    <div class="col-12 col-sm-12 col-md-6">
                        <form action="./auth/edit" method="post" class="content">
                            <h2 class="ta-c">Warning Bot</h2>
                            <div class="form-group">
                                <label for="phisher_message">The message used to target users in the blacklists</label><br>
                                <span>Use <code>[account]</code>, <code>[operations]</code> as variables. </span>
                                <textarea class="form-control" name='phisher_message' id='phisher_message'><?php echo $settings['phisher_message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="link_message">The message used to target phishing URLs</label>
                                <textarea class="form-control" name='link_message' id='link_message'><?php echo $settings['link_message']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="account">Account to send alerts with</label>
                                <input type="text" class="form-control" name='account' id='account' value="<?php echo $settings['account']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="key">Posting Key</label>
                                <input type="password" class="form-control" name='key' id='key' value="<?php echo $settings['key']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="min_downvote_percent">Disable downvotes when the power reach </label>
                                <input type="number" class="form-control" name='min_downvote_percent' id='min_downvote_percent' value="<?php echo $settings['min_down_percent']; ?>" min='-1' max='100'>
                            </div>
                            <div class="form-group">
                                <label for="min_rc_percent">Disable Alerts when the resources reach </label>
                                <input type="number" class="form-control" name='min_rc_percent' id='min_rc_percent' value="<?php echo $settings['min_rc_percent']; ?>" min='-1' max='100'>
                            </div>
                            <span>*Set as -1 to disable downvotes or alerts.</span>
                            <button type="submit" class="btn btn-filter" name="bot" id="bot">Save</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="row" id="users" <?php echo $users[1]; ?>>
                <div class="col-12 col-sm-12 col-md-6">
                    <form action="./auth/create" method="post" class="content mt-2" name="new" id="new">
                        <h2 class='ta-c'>Create a new manager</h2>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="password2">Repeat Password</label>
                            <input type="password" class="form-control" name="password2" id="password2">
                        </div>
                        <button type="submit" class="btn btn-filter" name="create" id="create">Create</button>
                    </form>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <form action="./auth/create" method="post" class="content mt-2" name="newfriend" id="newfriend">
                        <h2 class='ta-c'>Add one or several Sentinels</h2>
                        <span>
                        Note: Sentinels will be able to do add and remove users from the blacklist by leaving the following commands under a post or a comment.<br>
                        <p></p>
                        <code>!contain</code> - Add the target author of the post/comment to the blacklist. <br>
                        <code>!clear</code> - Remove the target author of the post/comment from the blacklist.
                        <p></p>
                        </span>
                        <div class="form-group">
                            <label for="friend">Add a steem account (sentinel) without '@'</label>
                            <input type="text" class="form-control" name="friend" id="friend">
                            <span class='isValid'></span>
                        </div>
                        <button type="submit" class="btn btn-filter" name="addfriend" id="addfriend">Add</button>
                    </form>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content mt-2">
                        <h3>List of managers</h3>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm users-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:10%;'>#</th>
                                <th style='width:40%;'>Username</th>
                                <th style='width:30%;'>Status</th>
                                <th style='width:20%;'></th>
                                </tr>
                            </thead>
                            <tbody class="users-list">
                                <tr>
                                    <td colspan='4' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content mt-2">
                        <h3>List of Sentinels</h3>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm friends-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:20%;'>#</th>
                                <th style='width:40%;'>Username</th>
                                <th style='width:30%;'>Timestamp</th>
                                <th style='width:10%;'></th>
                                </tr>
                            </thead>
                            <tbody class="friends-list">
                                <tr>
                                    <td colspan='4' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="links" <?php echo $links[1]; ?>>
                <div class="col-12 col-sm-12 col-md-6">
                    <form action="./auth/add" method="post" class="content mt-2" name="newlink" id="newlink">
                        <h2 class='ta-c'>Add one or several URLs</h2> 
                        <span>
                        - Add several URLs separated by a break. <br>
                        - Valid URLs only, body text regex conversion not yet implemented. <br> 
                        </span>
                        <div class="form-group">
                            <input type="text" name="url" id="url" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-filter" name="addlink" id="addlink">Add</button>
                    </form>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content profile-content mt-2">
                        <h3 style='width:60%; display:inline-block;'>URLs List</h3>
                        <div class="bar" style='width:38%; display:inline-block; margin-bottom:4px;'><input type="text" name='search-link' data-table='links' id='search-link' class="form-control" placeholder='Search'></div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm links-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:40%;'>URL</th>
                                <th style='width:30%;'>Creator</th>
                                <th style='width:20%;'>Timestamp</th>
                                <th style='width:10%;'></th>
                                </tr>
                            </thead>
                            <tbody class="links-list">
                                <tr>
                                    <td colspan='4' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="phishers" <?php echo $phish[1]; ?>>
                <div class="col-12">
                    <div class="custom-alert alert alert-info alert-dismissible" style='display:none;'>
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup class='custom-text'>Sending alerts...</sup>
                    </div>
                </div> 
                <div class="col-12 col-sm-12 col-md-6">
                    <form action="./auth/add" method="post" class="content mt-2" name="newphisher" id="newphisher">
                        <h2 class='ta-c'>Add one or several phishers</h2>
                        <span>
                        - Add several accounts separated by a break, and without the "@". <br>
                        </span>
                        <div class="form-group">
                            <input type="text" name="phisher" id="phisher" class="form-control">
                            <span class="account-exist"></span>
                        </div>
                        <button type="submit" class="btn btn-filter" name="addphisher" id="addphisher">Add</button>
                    </form>
                    <?php if($settings['enable_following'] == 1){ ?>
                    <form action="./auth/add" method="post" class="content mt-2" name="followaccount" id="followaccount">
                        <h2 class='ta-c'>Add Users Followed By</h2>
                        <div class="form-group">
                            <input type="text" name="following_account" id="following_account" class="form-control">
                            <span>Leave blank to don't use it.</span>
                        </div>
                        <button type="submit" class="btn btn-filter" name="edit_following_account" id="edit_following_account">Save</button>
                    </form>
                    <?php } ?>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content profile-content mt-2">
                        <h3 style='width:60%; display:inline-block;'>Phishers List</h3>
                        <div class="bar" style='width:38%; display:inline-block; margin-bottom:4px;'><input type="text" name='search-phisher'  id='search-phisher' data-table='phishers' class="form-control" placeholder='Search'></div>
                        <span>
                        Note: The <code>Alerts</code> button will send warnings under what X target has posted in the last 7 days. Adding accounts in bulk will not trigger the alerts but they can be sent individually by clicking on the account. When sentinels add a phisher by using the <code>!phisher</code> command, the warning will automatically be left in his last 7 days' posts if there are any.
                        </span>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm phishers-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:40%;'>Phisher</th>
                                <th style='width:30%;'>Creator</th>
                                <th style='width:20%;'>Timestamp</th>
                                <th style='width:10%;'></th>
                                </tr>
                            </thead>
                            <tbody class="phishers-list">
                                <tr>
                                    <td colspan='4' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="spammers" <?php echo $spam[1]; ?>>
                <div class="col-12">
                    <div class="custom-alert alert alert-info alert-dismissible" style='display:none;'>
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup class='custom-text'>Sending alerts...</sup>
                    </div>
                </div> 
                <div class="col-12 col-sm-12 col-md-6">
                    <form action="./auth/add" method="post" class="content mt-2" name="newspammer" id="newspammer">
                        <h2 class='ta-c'>Add one or several spammers</h2>
                        <span>
                        - Add several accounts separated by a break, and without the "@". <br>
                        </span>
                        <div class="form-group">
                            <input type="text" name="spammer" id="spammer" class="form-control">
                            <span class="account-exist"></span>
                        </div>
                        <button type="submit" class="btn btn-filter" name="addspammer" id="addspammer">Add</button>
                    </form>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content profile-content mt-2">
                        <h3 style='width:60%; display:inline-block;'>Spammers List</h3>
                        <div class="bar" style='width:38%; display:inline-block; margin-bottom:4px;'><input type="text" name='search-spammer'  id='search-spammer' data-table='spammers' class="form-control" placeholder='Search'></div>
                        <span>
                        Note: The <code>Alerts</code> button will send warnings under what X target has posted in the last 7 days. Adding accounts in bulk will not trigger the alerts but they can be sent individually by clicking on the account. When sentinels add a spammer by using the <code>!spammer</code> command, the warning will automatically be left in his last 7 days' posts if there are any.
                        </span>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm spammers-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:40%;'>Spammer</th>
                                <th style='width:30%;'>Creator</th>
                                <th style='width:20%;'>Timestamp</th>
                                <th style='width:10%;'></th>
                                </tr>
                            </thead>
                            <tbody class="spammers-list">
                                <tr>
                                    <td colspan='4' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="farmers" <?php echo $farm[1]; ?>>
                <div class="col-12">
                    <div class="custom-alert alert alert-info alert-dismissible" style='display:none;'>
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup class='custom-text'>Sending alerts...</sup>
                    </div>
                </div> 
                <div class="col-12 col-sm-12 col-md-6">
                    <form action="./auth/add" method="post" class="content mt-2" name="newfarmer" id="newfarmer">
                        <h2 class='ta-c'>Add one or several farmers</h2>
                        <span>
                        - Add several accounts separated by a break, and without the "@". <br>
                        </span>
                        <div class="form-group">
                            <input type="text" name="farmer" id="farmer" class="form-control">
                            <span class="account-exist"></span>
                        </div>
                        <button type="submit" class="btn btn-filter" name="addfarmer" id="addfarmer">Add</button>
                    </form>
                    <?php if($settings['enable_following'] == 1){ ?>
                    <form action="./auth/add" method="post" class="content mt-2" name="followaccount" id="followaccount">
                        <h2 class='ta-c'>Add Users Followed By</h2>
                        <div class="form-group">
                            <input type="text" name="following_account" id="following_account" class="form-control">
                            <span>Leave blank to don't use it.</span>
                        </div>
                        <button type="submit" class="btn btn-filter" name="edit_following_account" id="edit_following_account">Save</button>
                    </form>
                    <?php } ?>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content profile-content mt-2">
                        <h3 style='width:60%; display:inline-block;'>Farmers List</h3>
                        <div class="bar" style='width:38%; display:inline-block; margin-bottom:4px;'><input type="text" name='search-farmer'  id='search-farmer' data-table='farmers' class="form-control" placeholder='Search'></div>
                        <span>
                        Note: The <code>Alerts</code> button will send warnings under what X target has posted in the last 7 days. Adding accounts in bulk will not trigger the alerts but they can be sent individually by clicking on the account. When sentinels add a farmer by using the <code>!farmer</code> command, the warning will automatically be left in his last 7 days' posts if there are any.
                        </span>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm farmers-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:40%;'>Farmer</th>
                                <th style='width:30%;'>Creator</th>
                                <th style='width:20%;'>Timestamp</th>
                                <th style='width:10%;'></th>
                                </tr>
                            </thead>
                            <tbody class="farmers-list">
                                <tr>
                                    <td colspan='4' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="profile" <?php echo $profile[1]; ?>> 
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="content profile-content mt-2">
                        <img src="https://robohash.org/<?php echo $_SESSION['username']; ?>.png" class="icon">
                        <div class="profile-data">
                            <h2>Welcome back, <?php echo $_SESSION['username']; ?></h2>
                            You logged at <?php echo $_SESSION['last_action']; ?><br>
                            Account Status: <span class="account-status"></span><br>
                            <a href="./logout" class='btn btn-danger'>Log Out</a>
                        </div>
                    </div>
                    <form action="./auth/newpassword" method="post" class="content mt-2" name="change" id="change">
                        <h2 class='ta-c'>Change Password</h2>
                        <div class="form-group">
                            <label for="old">Old Password</label>
                            <input type="password" name="old" id="old" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="old">New Password</label>
                            <input type="password" name="newpsw" id="newpsw" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="old">Repeat New Password</label>
                            <input type="password" name="newpsw2" id="newpsw2" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-filter" name="changepsw" id="changepsw">Change Password</button>
                    </form>
                </div>
                <div class="col-12 col-sm-12 col-md-6"> 
                    <div class="content mt-2">
                        <h3 style='width:60%; display:inline-block;'>Logs</h3>
                        <div class="bar" style='width:38%; display:inline-block; margin-bottom:4px;'><input type="text" name='search-logs'  id='search-logs' data-table='changelog' class="form-control" placeholder='Search'></div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm logs-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:20%;'>Timestamp</th>
                                <th style='width:60%;'>Description</th>
                                <th style='width:20%;'>Modified</th>
                                </tr>
                            </thead>
                            <tbody class="changelog-list">
                                <tr>
                                    <td colspan='3' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" id="reports" <?php echo $report[1]; ?>> 
                <div class="col-12 offset-0 col-sm-12 offset-sm-0 col-md-8 offset-md-2">
                    <div class="content profile-content mt-2">
                        <h3>Reports List</h3>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center pagination-sm report-pagination">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li> 
                            </ul>
                        </nav>
                        <table class="table table-striped user-table">
                            <thead>
                                <tr>
                                <th style='width:20%;'>Link</th>
                                <th style='width:20%;'>Phisher</th>
                                <th style='width:30%;'>Explanation</th>
                                <th style='width:20%;'>Timestamp</th>
                                <th style='width:10%;'></th>
                                </tr>
                            </thead>
                            <tbody class="reports-list">
                                <tr>
                                    <td colspan='5' class='ta-c'>Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=./noJS">
    </noscript>
    <footer class="footer " style="min-height:2.5rem;"> 
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
            <div class="col-12 ta-c" style='margin-top: 5px;'>&copy; <?php
                        $fromYear = 2020;
                        $thisYear = (int)date('Y');
                        echo $fromYear . (($fromYear != $thisYear) ? '-' . $thisYear : '');?> Symbionts. All Rights Reserved.</div>
            </div>
        </div>
    </footer>
    <script src="./view/js/utils.js"></script>
    <script src="./view/js/dashboard.js"></script> 
    <script src="./view/js/validate.js"></script> 
</body> 
</html>
