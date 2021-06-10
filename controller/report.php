<?php
    require_once('config.php');
    require_once('../model/report.php');
    require_once('../model/changelog.php');
    if (isset($_POST['send'])) {
        try {
            //set PDO CONNECTION
            $connection  = new PDO($dbhost, $dbuser, $dbpass);//data connection with PDO
            $connection  -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $report      = new Report($connection);
            $logs        = new Changelog($connection);
            $subject     = htmlentities(addslashes($_POST['subject']), ENT_QUOTES);
            $data        = ($subject == 'link')? $_POST['link']: $_POST['phisher'];
            $phishing    = htmlentities(addslashes($data), ENT_QUOTES);
            $explanation = htmlentities(addslashes($_POST['explanation']), ENT_QUOTES);
            $report -> setPhishing($phishing);
            $report -> setExplanation($explanation);
            $report -> setField($subject);
            echo $report->getField() . $report->getPhishing() . $report->getExplanation();
            $res    = $report->saveReport();
            if ($res) {
                $logs->setChange('anon', 1, 6);
                header('location:../report?success=true');
            }else {
                header('location:../report?success=false');
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