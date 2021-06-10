<?php
    session_start();
    if (!empty($_SESSION['username'])) {
        require_once('config.php');
        require_once('../model/links.php');
        require_once('../model/phishers.php');
        require_once('../model/changelog.php');
        try {
            //set PDO CONNECTION
            $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
            $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $link        = new Link($connection);
            $phisher     = new Phisher($connection);
            $logs        = new Changelog($connection);
            if (isset($_POST['addlink'])) { 
                $can   = $link->isOperational($_SESSION['username']);
                if ($can) {
                    $result   = $link->doQuery("SELECT * FROM links",true);
                    $links = array();
                    $i        = 0;
                    while($response=$result->fetch(PDO::FETCH_ASSOC)){
                        $links[$i] = $response['url'];
                        $i++;
                    }
                    $url      = $_POST['url']; 
                    $list     = explode(' ', $url);
                    $j        = 0;
                    $new      = array();
                    $regx     = "%^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\x{00a1}-\x{ffff}][a-z0-9\x{00a1}-\x{ffff}_-]{0,62})?[a-z0-9\x{00a1}-\x{ffff}]\.)+(?:[a-z\x{00a1}-\x{ffff}]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$%iuS";
                    for ($i=0; $i < count($list); $i++) { 
                        $list[$i] = trim($list[$i]);
                        if (!in_array($list[$i], $links) && preg_match($regx, $list[$i]) > 0) { 
                            $new[$j] = $list[$i];
                            $j++; 
                        }
                    }
                    if (count($new) > 0) {
                        date_default_timezone_set('UTC');
                        $timestamp = date('Y-m-d H:i:s');
                        $sql  = "INSERT INTO links (url, creator, timestamp) VALUES ";
                        $info = '';
                        for ($i=0; $i < count($new); $i++) { 
                            $sql  .= "('" . $new[$i] . "', '" . $_SESSION['username'] . "', '$timestamp')";
                            $sql  .= ($i+1 == count($new))? "":",";
                            $info .= $new[$i] . " ";
                        }
                        $added = $link->doQuery($sql);
                        if ($added) {
                            if (strlen($info) >= 255) {
                                $info = substr($info, 0, 250) . "...";
                            }
                            $logs->setChange($_SESSION['username'], 1, 4, $info);
                            header('location:.././dashboard?added=true&page=links');
                        }else {
                            header('location:.././dashboard?added=false&page=links');
                        }
                    }else {
                        header('location:.././dashboard?exist=true&page=links');
                    } 
                }else {
                    header('location:.././dashboard?banned=true&page=links');
                }
                
            }else if (isset($_POST['addphisher'])) { 
                $can = $phisher->isOperational($_SESSION['username']);
                if ($can) {
                    $result   = $phisher->doQuery("SELECT * FROM phishers",true);
                    $phishers = array();
                    $i        = 0;
                    while($response=$result->fetch(PDO::FETCH_ASSOC)){
                        $phishers[$i] = $response['username'];
                        $i++;
                    }
                    $username = htmlentities(addslashes($_POST['phisher']), ENT_QUOTES); 
                    $list     = explode(' ', $username);
                    $j        = 0;
                    $new      = array();
                    for ($i=0; $i < count($list); $i++) { 
                        $list[$i] = trim($list[$i]);
                        if (!in_array($list[$i], $phishers) && $list[$i] != '') { 
                            $new[$j] = $list[$i];
                            $j++; 
                        }
                    }
                    if (count($new) > 0) {
                        date_default_timezone_set('UTC');
                        $timestamp = date('Y-m-d H:i:s');
                        $sql  = "INSERT INTO phishers (username, creator, timestamp) VALUES ";
                        $info = '';
                        for ($i=0; $i < count($new); $i++) { 
                            $sql  .= "('" . $new[$i] . "', '" . $_SESSION['username'] . "', '$timestamp')";
                            $sql  .= ($i+1 == count($new))? "":",";
                            $info .= $new[$i] . " ";
                        }
                        $added = $phisher->doQuery($sql);
                        if ($added) {
                            if (strlen($info) >= 255) {
                                $info = substr($info, 0, 250) . "...";
                            }
                            $logs->setChange($_SESSION['username'], 1, 5, $info);
                            header('location:.././dashboard?added=true&page=phishers');
                        }else {
                            header('location:.././dashboard?added=false&page=phishers');
                        }
                    }else {
                        header('location:.././dashboard?exist=true&page=phishers');
                    }
                }else{
                    header('location:.././dashboard?banned=true&page=phishers');
                }
            }else if (isset($_POST['addspammer'])) { 
                $can = $phisher->isOperational($_SESSION['username']);
                if ($can) {
                    $result   = $phisher->doQuery("SELECT * FROM spammers",true);
                    $spammers = array();
                    $i        = 0;
                    while($response=$result->fetch(PDO::FETCH_ASSOC)){
                        $spammers[$i] = $response['username'];
                        $i++;
                    }
                    $username = htmlentities(addslashes($_POST['spammer']), ENT_QUOTES); 
                    $list     = explode(' ', $username);
                    $j        = 0;
                    $new      = array();
                    for ($i=0; $i < count($list); $i++) { 
                        $list[$i] = trim($list[$i]);
                        if (!in_array($list[$i], $spammers) && $list[$i] != '') { 
                            $new[$j] = $list[$i];
                            $j++; 
                        }
                    }
                    if (count($new) > 0) {
                        date_default_timezone_set('UTC');
                        $timestamp = date('Y-m-d H:i:s');
                        $sql  = "INSERT INTO spammers (username, creator, timestamp) VALUES ";
                        $info = '';
                        for ($i=0; $i < count($new); $i++) { 
                            $sql  .= "('" . $new[$i] . "', '" . $_SESSION['username'] . "', '$timestamp')";
                            $sql  .= ($i+1 == count($new))? "":",";
                            $info .= $new[$i] . " ";
                        }
                        $added = $phisher->doQuery($sql);
                        if ($added) {
                            if (strlen($info) >= 255) {
                                $info = substr($info, 0, 250) . "...";
                            }
                            $logs->setChange($_SESSION['username'], 1, 8, $info);
                            header('location:.././dashboard?added=true&page=spammers');
                        }else {
                            header('location:.././dashboard?added=false&page=spammers');
                        }
                    }else {
                        header('location:.././dashboard?exist=true&page=spammers');
                    }
                }else{
                    header('location:.././dashboard?banned=true&page=spammers');
                }
            }else if (isset($_POST['addfarmer'])) { 
                $can = $phisher->isOperational($_SESSION['username']);
                if ($can) {
                    $result   = $phisher->doQuery("SELECT * FROM farmers",true);
                    $farmers = array();
                    $i        = 0;
                    while($response=$result->fetch(PDO::FETCH_ASSOC)){
                        $farmers[$i] = $response['username'];
                        $i++;
                    }
                    $username = htmlentities(addslashes($_POST['farmer']), ENT_QUOTES); 
                    $list     = explode(' ', $username);
                    $j        = 0;
                    $new      = array();
                    for ($i=0; $i < count($list); $i++) { 
                        $list[$i] = trim($list[$i]);
                        if (!in_array($list[$i], $farmers) && $list[$i] != '') { 
                            $new[$j] = $list[$i];
                            $j++; 
                        }
                    }
                    if (count($new) > 0) {
                        date_default_timezone_set('UTC');
                        $timestamp = date('Y-m-d H:i:s');
                        $sql  = "INSERT INTO farmers (username, creator, timestamp) VALUES ";
                        $info = '';
                        for ($i=0; $i < count($new); $i++) { 
                            $sql  .= "('" . $new[$i] . "', '" . $_SESSION['username'] . "', '$timestamp')";
                            $sql  .= ($i+1 == count($new))? "":",";
                            $info .= $new[$i] . " ";
                        }
                        $added = $phisher->doQuery($sql);
                        if ($added) {
                            if (strlen($info) >= 255) {
                                $info = substr($info, 0, 250) . "...";
                            }
                            $logs->setChange($_SESSION['username'], 1, 9, $info);
                            header('location:.././dashboard?added=true&page=farmers');
                        }else {
                            header('location:.././dashboard?added=false&page=farmers');
                        }
                    }else {
                        header('location:.././dashboard?exist=true&page=farmers');
                    }
                }else{
                    header('location:.././dashboard?banned=true&page=farmers');
                }
            }else if (isset($_POST['edit_following_account'])){
                $can = $phisher->isOperational($_SESSION['username']);
                if ($can) {
                    $following = htmlentities(addslashes($_POST['following_account']), ENT_QUOTES);
                    $valid = $phisher->doQuery("UPDATE settings SET following_account = '$following' WHERE id=1");
                    if ($valid) { 
                        $logs->setChange($_SESSION['username'], 2, 1);
                        header('location:.././dashboard?added=true&page=phishers');
                    }else {
                        header('location:.././dashboard?added=false&page=phishers');
                    } 
                }else{
                    header('location:.././dashboard?banned=true&page=phishers');
                }   
            }
        } catch (Exception $e) {
            //catch errors
            echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
            die("Error: " . $e->getMessage());
        }
    }
    
?>