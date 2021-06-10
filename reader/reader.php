<?php
    require_once('../controller/config.php');
    require_once('../model/handler.php');
    $account = 'import';
    try {
        //set PDO CONNECTION
        $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
        $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $handler     = new Handler($connection);
        $limit = 1000;
        $amount      = $handler->doQuery("SELECT COUNT(url) FROM links", true);
        while($response=$amount->fetch(PDO::FETCH_ASSOC)){
            $rows = $response['COUNT(url)'];
        }
        $chunk = round($rows / $limit);
        $from  = 0;
        $to    = $limit;
        $list  = array();
        $k     = 0;
        echo "Fetching [";
        for ($i=0; $i < $chunk; $i++) { 
            $result = $handler->doQuery("SELECT url FROM links LIMIT $from,$to", true);
            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                $list[$k] = $response['url'];
                $k++;
            }
            $from  += $limit;
            echo ($i + 1 == $chunk)?".]": "."; 

        }
        //reading new urls from the file
        $new  = array();
        $i    = 0;
        $fp   = fopen("ALL-phishing-domains.txt", "r");
        while (!feof($fp)){
            $line = fgets($fp);
            if ($line != '-.') {
                if (!in_array($line, $list)) {
                    $new[$i] = $line;
                    $i++;
                }
            }
        }
        fclose($fp);
        
        // //saving new urls on the database
        echo count($new);
        // $items = count($new);
        // $lots  = round($items / $limit);
        // echo $lots;
        // if ($items > 0) { 
        //     for ($j=1; $j <= $lots; $j++) { 
        //         $sql   = "INSERT INTO links (url, creator) VALUES ";
        //         $count = ($j == $lots)? $items : $limit;
        //         for ($i=0; $i < $count; $i++) { 
        //             $sql .= "('" . $new[$i] . "', '" . $account . "')";
        //             $sql .= ($i+1 == $limit)? "":",";
        //         }
        //         // $added = $handler->doQuery($sql);
        //         // if ($added) {
        //         //     echo "New Rows Added Successfully.";
        //         // }else{
        //         //     echo "Fail to add new rows.";
        //         // }
        //         $items -= $limit;
        //     }
            
        // }else {
        //     echo "Nothing to add.";
        // }
    } catch (Exception $e) {
        //catch errors
        echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
        die("Error: " . $e->getMessage());
    }
?>