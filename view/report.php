<?php
    session_start();
    if (!empty($_SESSION['username'])) {
        header("location:./dashboard");
    }
?>
<!DOCTYPE html>
<html lang="en-EN">
<head>
    <?php include("templates/libraries.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="PhishingTool" />
    <meta http-equiv="default-style" content="default-stylesheet" />
    <meta http-equiv="X-UA-Compatible" content="IE=7, chrome=1, firefox=1, opera=1, ie=edge">
    <meta name="title" content="Login Page">
    <meta name="description" content="Find a list of malicious users and links on the steem blockchain">
    <meta name="keywords" content="ecosynthesizer, steem, steemit, symbionts, blockchain">
    <meta name="encoding" charset="utf-8" />
    <meta name="author" content="Symbionts Team">
    <meta name="copyright" content="Symbionts">
    <meta name="robots" content="index, follow"/>
    <title>Steem Sentinels</title>
</head>
<body>
    <header>
        <?php include("templates/navbar.php"); ?> 
    </header>
    <section>
        <div class="container">
            <div class="row mt-2">
                <div class="container">
                    <?php if(isset($_GET['success']) && $_GET['success'] == 'true'){ ?>
                    <div class="alert alert-success alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Your report has been created successfully.</sup>
                    </div>
                    <?php }else if(isset($_GET['success']) && $_GET['success'] != 'true'){ ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>Report was no created.</sup>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 offset-0 col-sm-12 offset-sm-0 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
                    <form action="./auth/report" method="post" class="" name="report" id="report">
                        <div class="content">
                            <label for="subject">Subject</label>
                            <select name="subject" id="subject" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="link">Link</option>
                                <option value="phisher">Phisher</option>
                            </select>
                        </div>
                        <div class="content mt-2" id='input-link' style='display:none;'>
                            <label for="link">Phishing Link</label>
                            <input type="text" class="form-control" name="link" id="link" style="padding: 3px 25px!important;">
                            <div class="info-section ta-r pt-2"><span class="gly-icon glyphicon glyphicon-alert"></span><div class="isValid"></div></div>
                        </div>
                        <div class="content mt-2" id='input-phisher' style='display:none;'>
                            <label for="phisher">Phisher Account</label>
                            <input type="text" class="form-control" name="phisher" id="phisher" style="padding: 3px 25px!important;">
                            <div class="info-section ta-r pt-2"><span class="gly-icon glyphicon glyphicon-alert"></span><div class="isValid"></div></div>
                        </div>
                        <div class="content mt-2">
                            <label for="explanation">Additional Information</label>
                            <textarea class="form-control" name="explanation" id="explanation"></textarea>
                        </div>
                        
                        <div class="content mt-2">
                            <div class='ta-c' id="captcha"></div>
                            <input type="text" class="form-control" name="cpatchaTextBox" id="cpatchaTextBox" placeholder='Fill the captcha'>
                            <div class="info-section ta-r pt-2"><div class="isValidCaptcha"></div></div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-send" name="send" id="send" style="font-size:14px;">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=./noJS">
    </noscript>
    <footer class="footer " style="min-height:2.5rem;"> 
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
            <div class="col-12 ta-c" style='margin-top: 5px;'>&copy; <?php
                        $fromYear = 2020;
                        $thisYear = (int)date('Y');
                        echo $fromYear . (($fromYear != $thisYear) ? '-' . $thisYear : '');?> Symbionts. All Rights Reserved.</div>
            </div>
        </div>
    </footer>
    <script src="./view/js/utils.js"></script>
    <script src="./view/js/validate.js"></script>
    <script src="./view/js/report.js"></script>
</body> 
</html>
