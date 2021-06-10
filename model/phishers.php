<?php
    require_once('handler.php');
    class Phisher extends Handler {
        private $id;
        private $username;
        private $creator;
        //getters and setters
        public function setId($id){
            $this->id=$id;
        } 
        public function getId(){
            return $this->id;
        } 
        public function setUsername($username){
            $this->username=$username;
        } 
        public function getUsername(){
            return $this->username;
        }
        public function setCreator($creator){
            $this->creator=$creator;
        } 
        public function getCreator(){
            return $this->creator;
        }
        public function savePhisher() {
            date_default_timezone_set('UTC');
            $timestamp = date('Y-m-d H:i:s');
            $sql = "INSERT INTO phishers (username, creator, timestamp) VALUES 
            ('" . $this->getUsername() . "','" .  $this->getCreator(). "', '$timestamp')";
            $result = $this->doQuery($sql);
            return ($result)? true:false;
        }
        public function exist($username) {
            $result = $this->doQuery("SELECT username FROM phishers WHERE username LIKE '$username'",true);
            return ($result->rowCount() > 0)? true : false;
        }
    }
?>