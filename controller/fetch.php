<?php
    // if (isset($_POST['fetch'])) {
        require_once('config.php');
        require_once('../model/user.php');
        require_once('../model/links.php');
        require_once('../model/phishers.php');
        require_once('../model/report.php');
        require_once('../model/changelog.php');
        session_start();
        if (!empty($_SESSION['username'])) {
            try {
                //set PDO CONNECTION
                $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
                $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $user        = new User($connection);
                $link        = new Link($connection);
                $phisher     = new Phisher($connection);
                $report      = new Report($connection);
                $logs        = new Changelog($connection);
                $limit       = 25;
                switch ($_POST['action']) {
                    case 'users':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $user->doQuery("SELECT id,username,status FROM users WHERE username!='root' AND username!='" . $_SESSION['username'] . "' LIMIT $start,$limit", true);
                        $row    = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "username" => $response['username'],
                                "status"   => $response['status']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'log':
                        $start = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $logs->getChangelog($start, $limit);
                        echo json_encode($result);
                        break;
                    case 'friends':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $user->doQuery("SELECT * FROM friends LIMIT $start,$limit", true);
                        $row    = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "username" => $response['friend'],
                                "timestamp"   => $response['timestamp']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;    
                    case 'links':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $link->doQuery("SELECT * FROM links ORDER BY timestamp DESC LIMIT $start,$limit", true);
                        $row    = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "url" => $response['url'],
                                "creator"   => $response['creator'],
                                'timestamp' => $response['timestamp']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'phishers':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $phisher->doQuery("SELECT * FROM phishers LIMIT $start,$limit", true);
                        $row  = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "username" => $response['username'],
                                "creator"   => $response['creator'],
                                'timestamp' => $response['timestamp']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'spammers':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $phisher->doQuery("SELECT * FROM spammers LIMIT $start,$limit", true);
                        $row  = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "username" => $response['username'],
                                "creator"   => $response['creator'],
                                'timestamp' => $response['timestamp']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'farmers':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $phisher->doQuery("SELECT * FROM farmers LIMIT $start,$limit", true);
                        $row  = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "username" => $response['username'],
                                "creator"   => $response['creator'],
                                'timestamp' => $response['timestamp']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'reports':
                        $start  = htmlentities(addslashes($_POST['start']), ENT_QUOTES);
                        $result = $report->doQuery("SELECT * FROM report LIMIT $start,$limit", true);
                        $row    = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "link"  => $response['link'],
                                "phisher"  => $response['phisher'],
                                "explanation"  => $response['explanation'],
                                "timestamp"  => $response['timestamp']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'settings':
                        $result = $phisher->doQuery("SELECT * FROM settings", true);
                        $row  = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "id"       => $response['id'],
                                "following_account" => $response['following_account'],
                                "account"=>$response['account'],
                                "key" => $response['posting_key'],
                                "phisher_message"=>$response['bot_phisher_message']
                            ); 
                            array_push($row, $c); 
                        } 
                        echo json_encode($row);
                        break;
                    case 'delete':
                        $can    = $user->isOperational($_SESSION['username']);
                        if ($can) { 
                            $id     = htmlentities(addslashes($_POST['id']), ENT_QUOTES);
                            $table  = htmlentities(addslashes($_POST['table']), ENT_QUOTES);
                            
                            if ($table == 'users') { $index  = 2; $field = 'username'; }
                            else if ($table == 'friends') { $index  = 3; $field = 'friend'; }
                            else if ($table == 'links') { $index  = 4; $field = 'url'; }
                            else if ($table == 'phishers') { $index  = 5; $field = 'username'; }
                            else if ($table == 'report') { $index  = 6; $field = '*';}
                            else if ($table == 'spammers') { $index  = 8; $field = 'username';}
                            else if ($table == 'farmers') { $index  = 9; $field = 'username';}
                            $res    = $user->doQuery("SELECT $field FROM $table WHERE id=" . $id, true);
                            while($response=$res->fetch(PDO::FETCH_ASSOC)){
                                if ($index != 6) {
                                    $info = $response[$field];
                                }else {
                                    $info = null;
                                } 
                            } 
                            if ($table == 'users') {
                                if ($_SESSION['username'] != 'root') {
                                    echo json_encode(array("status"=>"forbiden"));
                                }else {
                                    $result = $user->doQuery("DELETE FROM $table WHERE id=" . $id); 
                                    if ($result) {
                                        $_SESSION['last_action'] = date('Y-n-j H:i:s');
                                        $logs->setChange($_SESSION['username'], 3, $index, $info);
                                        echo json_encode(array("status"=>"success"));
                                    }else {
                                        echo json_encode(array("status"=>"failed"));
                                    }
                                }  
                            }else {
                                $result = $user->doQuery("DELETE FROM $table WHERE id=" . $id); 
                                if ($result) {
                                    $_SESSION['last_action'] = date('Y-n-j H:i:s');
                                    $logs->setChange($_SESSION['username'], 3, $index, $info);
                                    echo json_encode(array("status"=>"success"));
                                }else {
                                    echo json_encode(array("status"=>"failed"));
                                }
                            }
                        }else {
                            echo json_encode(array("status"=>"forbiden"));
                        }
                        break;
                    case 'accept':
                        $can    = $user->isOperational($_SESSION['username']);
                        if ($can) {
                            $id       = htmlentities(addslashes($_POST['id']), ENT_QUOTES);
                            $table    = htmlentities(addslashes($_POST['phishing']), ENT_QUOTES);
                            $result   = $report->doQuery("SELECT * FROM report WHERE id=$id", true);
                            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                                $identifier = ($table == 'links')?'link':'phisher';
                                $phishing = $response[$identifier];
                                $info    = $phishing; 
                            }
                            
                            if ($table == "phishers") {
                                $index   = 5; 
                                $phisher -> setUsername($phishing);
                                $phisher -> setCreator($_SESSION['username']);
                                $res     = $phisher->savePhisher();
                            }else if($table == "links") {
                                $index = 4; 
                                $link -> setUrl($phishing);
                                $link -> setCreator($_SESSION['username']);
                                $res  = $link->saveLink();
                            }
                            if ($res) {
                                $del = $report->doQuery("DELETE FROM report WHERE id=$id");
                                if ($del) {
                                    $_SESSION['last_action'] = date('Y-n-j H:i:s');
                                    $logs->setChange($_SESSION['username'], 4, $index, $info);
                                    echo json_encode(array("status"=>"success"));
                                }else {
                                    echo json_encode(array("status"=>"failed", "info"=>$info));
                                }
                            }else {
                                echo json_encode(array("status"=>"failed"));
                            }
                        }else {
                            echo json_encode(array("status"=>"forbiden"));
                        }
                        break;
                    case 'update':
                        $can        = $user->isOperational($_SESSION['username']);
                        if ($can && $_SESSION['username'] == 'root') {
                            $id     = htmlentities(addslashes($_POST['id']), ENT_QUOTES);
                            $status = ($_POST['val'] == "Lock")? 0: 1;
                            $result = $user->doQuery("UPDATE users SET status=" . $status . " WHERE id=" . $id);
                            if ($result) {
                                $_SESSION['last_action'] = date('Y-n-j H:i:s');
                                $logs->setChange($_SESSION['username'], 2, 2);
                                echo json_encode(array("status"=>"success"));
                            }else {
                                echo json_encode(array("status"=>"failed"));
                            }
                        }else {
                            echo json_encode(array("status"=>"forbiden"));
                        }
                        break;
                    case 'clean':
                        $can        = $user->isOperational($_SESSION['username']);
                        if ($can && $_SESSION['username'] == 'root') {
                            $table  = htmlentities(addslashes($_POST['table']), ENT_QUOTES); 
                            if ($table == 'users') { $index  = 2; }
                            else if ($table == 'friends') { $index  = 3; }
                            else if ($table == 'links') { $index  = 4; }
                            else if ($table == 'phishers') { $index  = 5; }
                            else if ($table == 'spammers') { $index  = 8; }
                            else if ($table == 'farmers') { $index  = 9; }
                            else if ($table == 'report') { $index  = 6;}
                            else { $index = 7; }
                            $result = $user->doQuery("DELETE FROM $table WHERE 1");
                            if ($result) {
                                $_SESSION['last_action'] = date('Y-n-j H:i:s');
                                $logs->setChange($_SESSION['username'], 3, $index);
                                echo json_encode(array("status"=>"success"));
                            }else {
                                echo json_encode(array("status"=>"failed"));
                            }
                        }else {
                            echo json_encode(array("status"=>"forbiden"));
                        }
                        break;
                    case 'pagination':
                        $field  = htmlentities(addslashes($_POST['field']), ENT_QUOTES);
                        $table  = htmlentities(addslashes($_POST['table']), ENT_QUOTES);
                        $result = $user->doQuery("SELECT COUNT($field) FROM $table", true); 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $key  = "COUNT($field)";
                            $rows = $response[$key];
                        }
                        if ($table == 'users') {
                            $rows -= 1;
                        }
                        $pages = ceil($rows / $limit);
                        echo json_encode(array(
                            "count"=>$rows,
                            "pages"=>$pages,
                            "limit"=>$limit
                        ));
                        break;
                    case 'account_status':
                        $result = $user->doQuery("SELECT status FROM users WHERE username LIKE '" . $_SESSION['username'] . "'", true);
                        $row    = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            $c = array(
                                "status"   => $response['status']
                            ); 
                            array_push($row, $c); 
                        }
                        echo json_encode($row);
                        break;
                    case 'exist':
                        $username  = htmlentities(addslashes($_POST['username']), ENT_QUOTES);
                        $result1   = $user->doQuery("SELECT username FROM phishers WHERE username LIKE '$username'", true);
                        $result2   = $user->doQuery("SELECT username FROM spammers WHERE username LIKE '$username'", true);
                        $result3   = $user->doQuery("SELECT username FROM farmers WHERE username LIKE '$username'", true);
                        $i1 = 0;
                        $i2 = 0;
                        $i3 = 0;
                        $exist1 = false;
                        $exist2 = false;
                        $exist3 = false;
                        while($response=$result1->fetch(PDO::FETCH_ASSOC)){ $i1++; }
                        while($response=$result2->fetch(PDO::FETCH_ASSOC)){ $i2++; }
                        while($response=$result3->fetch(PDO::FETCH_ASSOC)){ $i3++; }

                        if ($i1 > 0) { $exist1 = true; }
                        if ($i2 > 0) { $exist2 = true; }
                        if ($i3 > 0) { $exist3 = true; }

                        echo json_encode(array(
                            "phishers" => $exist1,
                            "spammers" => $exist2,
                            "farmers"  => $exist3
                        ));
                        break;
                    case 'search':
                        $table  = htmlentities(addslashes($_POST['table']), ENT_QUOTES);
                        $value  = htmlentities(addslashes($_POST['val']), ENT_QUOTES);
                        $field  = (($table == 'links')? 'url' : ($table == 'changelog'))? 'information' : 'username';  
                        $result = $user->doQuery("SELECT * FROM $table WHERE $field LIKE '%$value%' LIMIT 0,25", true);
                        $row    = []; 
                        while($response=$result->fetch(PDO::FETCH_ASSOC)){
                            if ($table != 'changelog') {
                                $c = array(
                                    "id"        => $response['id'],
                                    $field      => $response[$field],
                                    "creator"   => $response['creator'],
                                    'timestamp' => $response['timestamp']
                                ); 
                            }else {
                                $c = array(
                                    "id"          => $response['id'],
                                    "timestamp"   => $response['timestamp'],
                                    "responsible" => $response['responsible'],
                                    'activity'    => $response['activity'],
                                    'description' => $response['description'],
                                    'information' => $response['information']
                                ); 
                            }
                            
                            array_push($row, $c); 
                        }
                        echo json_encode($row);
                        break;
                    default:
                        # code...
                        break;
                }
            } catch (Exception $e) {
                //catch errors
                echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
                die("Error: " . $e->getMessage());
            }  
        }
    
?>