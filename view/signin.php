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
            <div class="row mt-4">
                <div class="col-12">
                <?php if(isset($_GET['logged']) && $_GET['logged'] == 'false'){ ?>
                <div class="alert alert-danger alert-dismissible">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <sup>Wrong password or username.</sup>
                </div>
                <?php } ?>
                <?php if(isset($_GET['banned']) && $_GET['banned'] == 'true'){ ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <sup>WARNING: Your account has been locked.</sup>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 offset-0 col-sm-12 offset-sm-0 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
                    <form action="./auth/login" method="post" class="content" name="login" id="login">
                        <h2 class='ta-c'>Login</h2>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <button type="submit" class="btn btn-filter" name="join" id="join">Login</button>
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
    <script src="./view/js/validate.js"></script> 
    <!-- 
    <script src="./view/js/actions.js"></script>
    <script src="./view/js/app.js"></script>-->
</body> 
</html>
