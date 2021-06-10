<?php
    class Handler {
        protected $connection;
        public function __construct($connection){
            $this->setConnection($connection);
        }
        public function setConnection(PDO $connection){
            $this->connection=$connection;
        }
        public function getSettings(){
            $sql = "SELECT * FROM settings";
            $result = $this->connection->query($sql);
            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                $settings = array(
                    "creator"  => $response['creator'],
                    "account_price" => $response['account_price']
                );
            }
            return $settings;
        }
        public function doQuery($sql, $return = false) {
            try {
                $result = $this->connection->query($sql);
            } catch (Exception $e) {
                return false;
            }
            if ($return) {
                return $result;
            }else {
                return true;
            }
        }
        public function getRowCount($sql){
            $result = $this->connection->query($sql);
            $amount = $result->rowCount();
            return $amount;
        }
        public function isOperational($username) {
            $result = $this->doQuery("SELECT status FROM users WHERE username LIKE '$username'",true);
            $status = '';
            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                $status = $response['status'];
            }
            return ($status == 1)?true : false;
        }
    }
?>