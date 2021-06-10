<?php
    require_once('handler.php');
    class Link extends Handler {
        private $id;
        private $url;
        private $creator;
        //getters and setters
        public function setId($id){
            $this->id=$id;
        } 
        public function getId(){
            return $this->id;
        } 
        public function setUrl($url){
            $this->url=$url;
        } 
        public function getUrl(){
            return $this->url;
        }
        public function setCreator($creator){
            $this->creator=$creator;
        } 
        public function getCreator(){
            return $this->creator;
        }
        public function saveLink() {
            date_default_timezone_set('UTC');
            $timestamp = date('Y-m-d H:i:s');
            $sql = "INSERT INTO links (url, creator, timestamp) VALUES 
                    ('" . $this->getUrl() . "','" .  $this->getCreator(). "', '$timestamp')";
            $result = $this->doQuery($sql);
            return ($result)? true:false;
        }
    }
?>