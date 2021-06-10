<?php
    class Steem {
        private $rpc;
        public function __construct($server = "https://api.steemit.com") {
            $this->setRpc($server);
        }
        public function setRpc($s) {
            $end    = substr($s,-5);
            $endBar = substr($s,-1);
            if ($end == ':2001') { $s = substr($s,0,-5); }
            if ($endBar == '/') { $s = substr($s,0,-1); }
            $this->rpc = $s;
        }
        public function getRpc(){
            return $this->rpc;
        }
        function customAPI($method, $param){
            $ch = curl_init($this->getRpc());
            if ($param == "") { $jsonData = array('id' => 1, 'jsonrpc' => '2.0', 'method' => $method); }
            else{  $jsonData = array( 'id' => 1, 'jsonrpc' => '2.0', 'method' => $method, 'params'=>$param); }
            $jsonDataEncoded = json_encode($jsonData);
            curl_setopt($ch, CURLOPT_POST, 1); # configure to post method
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return usable data
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded); # use the data json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); # set headers
            //ignore HTTPS on localhost
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); #set timeout 
            $result = curl_exec($ch); 
            $result = json_decode($result); 
            curl_close($ch);
            return $result;
        }
    }
?>