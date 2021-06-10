<?php
    require_once('handler.php');
    class Report extends Handler {
        private $id;
        private $phishing;
        private $creator;
        private $explanation;
        private $field;
        //getters and setters
        public function setId($id){
            $this->id=$id;
        } 
        public function getId(){
            return $this->id;
        } 
        public function setPhishing($phishing){
            $this->phishing=$phishing;
        } 
        public function getPhishing(){
            return $this->phishing;
        }
        public function setCreator($creator){
            $this->creator=$creator;
        } 
        public function getCreator(){
            return $this->creator;
        }
        public function setExplanation($explanation){
            $this->explanation=$explanation;
        } 
        public function getExplanation(){
            return $this->explanation;
        }
        public function setField($field){
            $this->field=$field;
        } 
        public function getField(){
            return $this->field;
        }
        public function saveReport() {
            date_default_timezone_set('UTC');
            $timestamp = date('Y-m-d H:i:s');
            $sql = "INSERT INTO report (" . $this->getField() . ", explanation, timestamp) VALUES 
                ('" .  $this->getPhishing(). "', '" .  $this->getExplanation() . "', '$timestamp')";
            $result = $this->doQuery($sql);
            return ($result)? true:false;
        }
    }
?>