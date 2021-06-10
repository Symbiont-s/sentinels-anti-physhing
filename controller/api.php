<?php
    include("config.php");
    require_once("../model/user.php");
    try {
        //set PDO CONNECTION
        $connection     = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
		$connection     -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $user = new User($connection);
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                # CATCH METHOD GET
                http_response_code(200); 
                # looking for url request
                try{
                    if (isset($_GET['method'])) {
                        $method = htmlentities(addslashes($_GET['method']),ENT_QUOTES);
                        if ($method == 'phishers') {
                            $result = $user->doQuery("SELECT * FROM phishers", true);
                            $list = [];
                            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                                array_push($list, $response['username']);
                            }
                            echo json_encode(array(
                                "status"=>"success",
                                "result"=>array(
                                    "phishers" => $list
                                )
                            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        }else if ($method == 'spammers') {
                            $result = $user->doQuery("SELECT * FROM spammers", true);
                            $list = [];
                            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                                array_push($list, $response['username']);
                            }
                            echo json_encode(array(
                                "status"=>"success",
                                "result"=>array(
                                    "spammers" => $list
                                )
                            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        }else if ($method == 'farmers') {
                            $result = $user->doQuery("SELECT * FROM farmers", true);
                            $list = [];
                            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                                array_push($list, $response['username']);
                            }
                            echo json_encode(array(
                                "status"=>"success",
                                "result"=>array(
                                    "farmers" => $list
                                )
                            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        }else if ($method == 'global') {
                            $result   = $user->doQuery("SELECT * FROM phishers", true);
                            $result2  = $user->doQuery("SELECT * FROM spammers", true);
                            $result3  = $user->doQuery("SELECT * FROM farmers", true);
                            $phishers = [];
                            $spammers = [];
                            $farmers  = [];
                            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                                array_push($phishers, $response['username']);
                            }
                            while($response=$result2->fetch(PDO::FETCH_ASSOC)){
                                array_push($spammers, $response['username']);
                            }
                            while($response=$result3->fetch(PDO::FETCH_ASSOC)){
                                array_push($farmers, $response['username']);
                            }
                            echo json_encode(array(
                                "status"=>"success",
                                "result"=>array(
                                    "phishers" => $phishers,
                                    "spammers" => $spammers,
                                    "farmers"  => $farmers
                                )
                            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        }else {
                            throw new Exception("Please set a valid method. global, phishers, farmers and spammers is currently supported.");
                        }
                    }else {
                        throw new Exception("Invalid Request.");
                    }
                }catch (Exception $e) {
                    echo json_encode(array(
                        "status"=>"rejected",
                        "message"=> $e->getMessage()
                    ));
                }
    
            }else{
                http_response_code(405);//invalid request
            }  
    } catch (Exception $e) {
        echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
        die("Error: " . $e->getMessage());    
    }
?>