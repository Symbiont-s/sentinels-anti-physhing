<?php
    require_once('handler.php');
    class Changelog extends Handler{
        /**
         * create new changelog
         * method receive the , the , and  by default is user
         * @param current session name to register who make te action
         * @param activity number, to register what activity type to do
         * @param description, to register from who is the action
         */
        public function setChange($currentSessionName, $activity,$description=1, $information = null){
            date_default_timezone_set('UTC');
            $timestamp = date('Y-m-d H:i:s');
            $field = ($information != null)? ',information':'';
            $sql = "
                INSERT INTO changelog (timestamp, responsible, activity,description $field)
                VALUES ('$timestamp', '$currentSessionName', $activity, $description";
            $sql .= ($information != null)? ",'$information')": ")";
            $logged = $this->doQuery($sql);
            return $logged;
        }
        /**
         * METHOD USE A SYSTEM BASED ON INTEGER NUMBERS TO BUILD THE DESCRIPTION
         * index:
         * for activity:
         * 1 - CREATE a new thing
         * 2 - UPDATE a  thing
         * 3 - DELETE a thing
         * 4 - ACCEPT a thing
         * 5 - REJECT a thing
         * for description:
         * 1 - action make to a settings
         * 2 - action make to an user
         * 3 - action make to a friend
         * 4 - action make to a link
         * 5 - action make to a phisher
         * 6 - action make to a report
         * 7 - action make to a log
         * the method will show the latest 50 entries
         */
        public function getChangelog($start=0, $limit=50){
            $sql = "SELECT * FROM changelog ORDER BY timestamp DESC limit $start,$limit";
            $result=$this->connection->query($sql);
            $row  = []; 
            while($response=$result->fetch(PDO::FETCH_ASSOC)){
                $c = array(
                    "id"          => $response['id'],
                    "timestamp"   => $response['timestamp'],
                    "responsible" => $response['responsible'],
                    'activity'    => $response['activity'],
                    'description' => $response['description'],
                    'information' => $response['information']
                ); 
                array_push($row, $c); 
            } 
            return $row;
        }
    }

?>