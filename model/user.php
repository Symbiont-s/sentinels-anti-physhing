<?php
    require_once('handler.php');
    class User extends Handler {
        private $username;
        private $password;
        private $id;
        private $status;

        //  getters and setters
        public function setId($id){
            $this->id=$id;
        } 
        public function getId(){
            return $this->id;
        } 
        public function setUsername($usr){
            $this->username=$usr;
        } 
        public function getUsername(){
            return $this->username;
        } 
        public function setPassword($psw){
            $this->password=$psw;
        } 
        public function getPassword(){
            return $this->password;
        } 
        public function setStatus($stt){
            $this->status=$stt;
        } 
        public function getStatus(){
            return $this->status;
        }
        public function exist($username) {
            $sql    = "SELECT * FROM users WHERE username LIKE '" . $username . "'";
            $amount = $this->getRowCount($sql);
            return ($amount != 0)? true:false;
        }
        public function getDataUser($username){
            $sql    = "SELECT * FROM users WHERE username LIKE '" . $username . "'";
            $amount = $this->getRowCount($sql);
            if ($amount != 0) {
                $result = $this->doQuery($sql, true);
                $row    = array();
                $i      = 0;
                while($response=$result->fetch(PDO::FETCH_ASSOC)){
                    $this    -> setId($response['id']);
                    $this    -> setUsername($response['username']);
                    $this    -> setPassword($response['password']);
                    $this    -> setStatus($response['status']);
                    $row[$i] = $this;
                    $i++;
                }
                return $row;
            }else {
                return false;
            }
        } 
        public function validateUser(){
            $usr    = $this -> getUsername();
            $psw    = $this -> getPassword();
            $result = $this -> getDataUser($usr);
            if ($result) {
                foreach($result as $a){
                    if ($a->getStatus() == 0) {
                        return false;
                    }else {
                        $message = ($a->getUsername() == $usr && password_verify($psw,$a->getPassword()))? true : false;
                    } 
                }
                return $message;
            }else { return false; }
        }
        public function userAreLocked($username) {
            $aux = new User($this->connection);
            $data = $aux->getDataUser($username);
            $type = $data[0]->getStatus();
            $response = ($type == 1) ? true : false;
            return $response;
        }
        public function registerNewUser(User $user, $creator){ 
            $allowed  = $this->userAreLocked($creator);
            if (!$allowed) { return false; }
			$username = strtolower($user->getUsername()); 
			$pass     = $user->getPassword();
            $encrypt  = password_hash($pass, PASSWORD_DEFAULT);
            $type     = 1;
			$sql      = "INSERT INTO users (username, password) VALUES ('" . $username . "','" . $encrypt . "')";
            $result   = $this->doQuery($sql, true);
            // if ($result) {
                $new = $this->getDataUser($user->getUsername());
                if (count($new) > 0) {
                    return true;
                }else {
                    return false;
                }
                // $saveNewActivity = new Changelog($this->connection);
                // $saveNewActivity->setChange($currentSessionName, 1,1);
            // }
        }
        public function validatePassword($psw, $user){
            $result = $this->doQuery("SELECT password FROM users WHERE username LIKE '" . $user . "'", true);
            $row    = []; 
            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                $pass = $response['password']; 
            }
            return (password_verify($psw, $pass))?true:false;
        }
        public function updatePassword($old, $newPass, $username){
            $valid   = $this->validatePassword($old, $username);
            if ($valid) {
                $encrypt = password_hash($newPass, PASSWORD_DEFAULT);
                $this->doQuery("UPDATE users SET password='$encrypt' WHERE username LIKE '$username'");
                $changed = $this->validatePassword($newPass, $username);
                return ($changed)?true:false;
            }else{
                return false;
            }
            
        }
    }
?>