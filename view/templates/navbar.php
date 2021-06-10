<nav class="navbar navbar-dark bg-primary navbar-expand-lg"> 
    <div class="container pd-l-5 pd-r-5">   
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="./" rel="nofollow" title='Go to main page' class="nav-item nav-link router-link-active">
                    <img src="./view/img/SMLOGO.png" width="40px">
                </a>
            </li>
        </ul>
        <ul class="navbar-nav nav-link ml-auto d-lg-none">
            <!--add here navigation to mobile screen-->
            
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navbar" aria-expanded="false" aria-label="toggle">
            <img src="./view/img/menu.png" class="steem-icon">
        </button>
        <div id="navigation" class="navbar-collapse collapse">
            <ul class="navbar-nav w-100">
                <?php if(empty($_SESSION['username'])) {?>
                <li class="nav-item"><a href="./signin" title='Login' class="nav-link">Login</a></li>
                <li class="nav-item"><a href="./report" title='Send an anonymous report' class="nav-link">Report</a></li>
                <?php }else{ ?>
                <li class="nav-item"><a href="./dashboard" title='Manage the tool' class="nav-link"><div class="extra-padding">Dashboard</div></a></li>
                <li class="nav-item"><a href="./logout" title='' class="nav-link">Log Out</a></li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-lg-block d-none"></li>
            </ul>
        </div>
    </div>
</nav>