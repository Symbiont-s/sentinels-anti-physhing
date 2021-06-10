<?php
    require_once('config.php');
    require_once('../model/steem.php');
    require_once('../model/phishers.php');

    $steem = new Steem();
    try {
        //set PDO CONNECTION
        $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
        $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $phisher     = new Phisher($connection);
        $result      = $phisher -> doQuery("SELECT * FROM settings", true);
        while($response=$result->fetch(PDO::FETCH_ASSOC)){
            $account = $response['following_account'];
            $enabled = $response['allow_following_account'];
        }
        if ($account != '' && $enabled == 1) {
             //getting phishers added to the db
            $result   = $phisher->doQuery("SELECT * FROM phishers",true);
            $i        = 0;
            $phishers = array();
            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                $phishers[$i] = $response['username'];
                $i++;
            }

            // getting the amount of account followed by the following account
            $json = $steem -> customAPI('follow_api.get_follow_count', array("account"=>$account));
            $followings_count = $json->result->following_count;

            //getting all accounts followed by the following account
            $list = $steem -> customAPI('follow_api.get_following', array("account"=>$account,"start"=>null, "type"=>"blog","limit"=>$followings_count));
            $list = $list->result;

            $new = array();
            $j   = 0;
            for ($i=0; $i < count($list); $i++) {
                $c     = $list[$i]->following; 
                if (!in_array($c, $phishers)) {
                    $new[$j] = $c;
                    $j++;
                }
            }
            if (count($new) > 0) { 
                $sql = "INSERT INTO phishers (username, creator) VALUES ";
                for ($i=0; $i < count($new); $i++) { 
                    $sql .= "('" . $new[$i] . "', '" . $account . "')";
                    $sql .= ($i+1 == count($new))? "":",";
                }
                $added = $phisher->doQuery($sql);
                if ($added) {
                    echo "New Rows Added Successfully.";
                }else{
                    echo "Fail to add new rows.";
                }
            }else {
                echo "Nothing to add.";
            }
        }else {
            echo "Following account disabled.";
        }
           

    } catch (Exception $e) {
        //catch errors
        echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
        die("Error: " . $e->getMessage());
    }

?>