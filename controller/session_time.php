<?php
    session_start();
    if (!empty($_SESSION["username"])) {
        
        # validating time inactive
        $last_action  = $_SESSION["last_action"];
        $current_time = date("Y-n-j H:i:s");
        $inactive     = (strtotime($current_time)-strtotime($last_action));
       
        //if session inactive time is 30 minutes close session
        if ($inactive>=1800) {
            header("location:./logout");
        }
        
    }
?>