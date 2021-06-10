<?php
    require_once('../controller/session_time.php'); 
?>
<!DOCTYPE html>
<html lang="en-EN">
<head>
    <?php include("templates/libraries.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="PhishingTool" />
    <meta http-equiv="default-style" content="default-stylesheet" />
    <meta http-equiv="X-UA-Compatible" content="IE=7, chrome=1, firefox=1, opera=1, ie=edge">
    <meta name="title" content="Main Page">
    <meta name="description" content="Find a list of malicious users and links on the steem blockchain">
    <meta name="keywords" content="ecosynthesizer, steem, steemit, symbionts, blockchain">
    <meta name="encoding" charset="utf-8" />
    <meta name="author" content="Symbionts Team">
    <meta name="copyright" content="Symbionts">
    <meta name="robots" content="index, follow"/>
    <title>Steem Sentinels</title>
</head>
<body id="main">
    <div class="container ta-c">
        <div id="row mt-8" style="margin-top:90px;">
            <div class="col-12 offset-0 col-sm-12 offset-sm-0 col-md-6 offset-md-3">
                <div class="content ta-c" style='box-shadow:0.5px 1px 5px black;'>
                    <div class="fadeIn first">
                        <img src="./view/img/Sentinels_Symbol_Gradient.png" alt="steem icon" style='width:150px;'/>
                    </div>
                    <center>
                        <h3 style='font-size:14px;'>Cybercrime Awareness & Prevention</h3>
                        <button type="button" class="btn btn-primary " id="report" style="
                            display:block;
                            font-size:16px;
                            padding:6px 12px;
                            background-color: white;
                            color: #999999;
                            width: 235px;
                            margin-top:20px;
                            border:1px solid #999999;
                            border-radius:0;">Send an Anonymous Report
                        </button>
                        
                        <button type="button" class="btn btn-primary " id="dashboard" style="
                            display:block;
                            font-size:16px;
                            padding:6px 12px;
                            background-color: white;
                            color: #999999;
                            width: 235px;
                            margin-top:20px;
                            border:1px solid #999999;
                            border-radius:0;">Dashboard & Management
                        </button>
                    </center>
                    <p style="padding-top:30;font-size:13;"><div class="row pt-2 pb-2">
                    <div class="col-12 ta-c" style='margin-top: 5px;'>&copy; <?php
                                $fromYear = 2020;
                                $thisYear = (int)date('Y');
                                echo $fromYear . (($fromYear != $thisYear) ? '-' . $thisYear : '');?> Symbionts. All Rights Reserved.</div>
                    </div></p>
                </div>
                
            </div>
            
        </div>
    </div>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=./noJS">
    </noscript>
    <script src="./view/js/index.js"></script>
    <!-- <footer class="footer " style="min-height:2.5rem;"> 
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
            <div class="col-12 ta-c" style='margin-top: 5px;'>&copy; <?php
                        $fromYear = 2020;
                        $thisYear = (int)date('Y');
                        echo $fromYear . (($fromYear != $thisYear) ? '-' . $thisYear : '');?> Symbionts. All Rights Reserved.</div>
            </div>
        </div>
    </footer> -->
    <!-- 
    <script src="./view/js/actions.js"></script>
    <script src="./view/js/app.js"></script>-->
</body> 
</html>
