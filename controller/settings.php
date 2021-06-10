<?php
    require_once('config.php');
    require_once('../model/handler.php');
    try {
        //set PDO CONNECTION
        $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
        $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $handler     = new Handler($connection);
        $result      = $handler->doQuery("SELECT * FROM settings WHERE id=1", true);

        while($response=$result->fetch(PDO::FETCH_ASSOC)){
            $settings = array(
                "enable_following"=> $response['allow_following_account'],
                "enable_alerts"   => $response['allow_auto_alerts'],
                "phisher_message" => $response['bot_phisher_message'],
                "link_message"    => $response['bot_link_message'],
                "account"         => $response['account'],
                "key"             => $response['posting_key'],
                "min_down_percent"=> $response['min_downvote_percent'],
                "min_rc_percent"  => $response['min_rc_percent']
            );
        }
    } catch (Exception $e) {
        //catch errors
        echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
        die("Error: " . $e->getMessage());
    }
    
?>