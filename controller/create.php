<?php
    require_once('config.php');
    require_once('../model/user.php');
    require_once('../model/changelog.php');
    session_start();
    if (!empty($_SESSION['username'])) {
        try {
            //set PDO CONNECTION
            $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
            $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $user        = new User($connection);
            $logs        = new Changelog($connection);
            if (isset($_POST['create'])) { 
                $can         = $user->isOperational($_SESSION['username']);
                if ($can) {
                    $user -> setUsername(htmlentities(addslashes($_POST['username']), ENT_QUOTES));
                    $aux  = new User($connection);
                    $data = $aux->getRowCount("SELECT * FROM users WHERE username LIKE '" . $user->getUsername() . "'");
                    if ($data == 0) {
                        $user->setPassword(htmlentities(addslashes($_POST['password']), ENT_QUOTES));
                        $created = $user->registerNewUser($user, $_SESSION['username']);
                        if ($created) {
                            $_SESSION['last_action'] = date('Y-n-j H:i:s'); 
                            $logs->setChange($_SESSION['username'], 1, 2, $user->getUsername());
                            header('location:.././dashboard?created=true&page=users');
                        }else {
                            header('location:.././dashboard?created=false&page=users');
                        }
                    }else {
                        header('location:.././dashboard?created=inuse&page=users');
                    }
                }else {
                    header('location:.././dashboard?banned=true&page=users');
                } 
            }else if (isset($_POST['addfriend'])) { 
                $can = $user->isOperational($_SESSION['username']);
                if ($can) {
                    $friend = htmlentities(addslashes($_POST['friend']), ENT_QUOTES);
                    $aux  = new User($connection);
                    $data = $aux->getRowCount("SELECT * FROM friends WHERE friend LIKE '" . $friend . "'");
                    if ($data == 0) {
                        date_default_timezone_set('UTC');
                        $timestamp = date('Y-m-d H:i:s');
                        $created = $user->doQuery("INSERT INTO friends (friend, timestamp) VALUES ('$friend','$timestamp')");
                        if ($created) {
                            $_SESSION['last_action'] = date('Y-n-j H:i:s');
                            $logs->setChange($_SESSION['username'], 1, 3, $friend);
                            header('location:.././dashboard?added=true&page=users');
                        }else {
                            header('location:.././dashboard?added=false&page=users');
                        }
                    }else {
                        header('location:.././dashboard?exist=true&page=users');
                    }
                }else {
                    header('location:.././dashboard?banned=true&page=users');
                } 
            }else {
                header('location:.././dashboard');
            }
        } catch (Exception $e) {
            //catch errors
            echo "on line " . $e->getLine() . " " . $e->getFile() . " ";
            die("Error: " . $e->getMessage());
        }
    }else {
        header('location:.././');
    }
    
?>