<?php
    require_once('config.php');
    require_once('../model/user.php');
    require_once('../model/changelog.php');
    session_start();
    if ($_SESSION['username'] == 'root') { 
        try {
            //set PDO CONNECTION
            $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
            $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $user        = new User($connection);
            $logs        = new Changelog($connection);
            if (isset($_POST['save'])) {
                $enable_following = (isset($_POST['allow_following_account']))?1:0;
                $enable_alerts = (isset($_POST['allow_auto_alerts']))?1:0;
                $result = $user->doQuery("UPDATE settings SET allow_following_account=$enable_following, allow_auto_alerts=$enable_alerts WHERE id=1");
                if ($result) {
                    $_SESSION['last_action'] = date('Y-n-j H:i:s');
                    $logs->setChange($_SESSION['username'], 2, 1);
                    header('location:.././dashboard?added=true&page=advanced');
                }else {
                    header('location:.././dashboard?added=false&page=advanced');
                }
            }else if (isset($_POST['bot'])) {
                $message = $_POST['phisher_message'];
                $link    = $_POST['link_message'];
                $account = htmlentities(addslashes(strtolower($_POST['account'])), ENT_QUOTES);
                $key     = htmlentities(addslashes($_POST['key']), ENT_QUOTES);
                $min_downvote_percent = htmlentities(addslashes($_POST['min_downvote_percent']), ENT_QUOTES);
                $min_rc_percent = htmlentities(addslashes($_POST['min_rc_percent']), ENT_QUOTES);
                if ($min_downvote_percent > 100) { $min_downvote_percent = 100; }
                if ($min_downvote_percent < -1) { $min_downvote_percent = -1; }
                if ($min_rc_percent > 100) { $min_rc_percent = 100; }
                if ($min_rc_percent < -1) { $min_rc_percent = -1; }
                $result  = $user->doQuery("UPDATE settings SET bot_phisher_message='$message', bot_link_message='$link', account='$account', posting_key='$key', min_downvote_percent=$min_downvote_percent, min_rc_percent=$min_rc_percent WHERE id=1");
                if ($result) {
                    $_SESSION['last_action'] = date('Y-n-j H:i:s');
                    $logs->setChange($_SESSION['username'], 2, 1);
                    header('location:.././dashboard?added=true&page=advanced');
                }else {
                    header('location:.././dashboard?added=false&page=advanced');
                }
            }
        } catch (Exception $e) {
            //catch errors
            echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
            die("Error: " . $e->getMessage());
        }
    }
    
?>