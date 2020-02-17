<!DOCTYPE HTML>
<html lang = "zh-CN">
    <head>
        <?php include "php/html_head.php"; ?>
        <link rel = "stylesheet" href = "css/index.css" />
        <link rel = "stylesheet" href = "css/blog.css" />
        <style>
            .info {
                font-size: 18px;
                margin: 10px 0px;
                color: #555;
            }
        </style>
    </head>
    <body>
        <?php
            include "php/nav.php"
        ?>
        <div class = "container">
            <div class = "containerRight">
                <div id = "rightBox">
                    <?php
                        include "php/search_box.php";
                        include "php/tag_box.php";
                        include "php/rec_box.php";
                        include "php/lc_box.php";
                    ?>
                </div>
            </div>
            <div id = "blogContainer">
                <h1>ABOUT</h1>
                <div class = "info"><i class="rightMargin fa fa-user" aria-hidden="true"></i>ID: MITSUYAMA</div>
                <div class = "info"><i class="rightMargin fa fa-address-card" aria-hidden="true"></i>NAME: XSS</div>
                <div class = "info"><i class="rightMargin fa fa-envelope" aria-hidden="true"></i>E-MAIL: MITSUYAMA@163.COM</div>
                <div class = "info"><i class="rightMargin fa fa-briefcase" aria-hidden="true"></i>CAREER: STUDENT</div>
                <div class = "info"><i class="rightMargin fa fa-github" aria-hidden="true"></i>GITHUB: <a href = "https://github.com/Mittsuyama">Mittsuyama</a></div>
                <div style = "margin-top: 70px;" id = "threeButton">
                    <span style = "left: 0px; transform: translate(0%, 0%);" id = "rewardButton" class = "pageButton">
                        <i class="rightMargin fa fa-coffee" aria-hidden="true"></i>
                        赏作者一杯咖啡
                    </span>
                </div>
                <?php include "php/reward.php" ?>
            </div>
            <div style = "clear: both;"></div>
        </div>
        <?php
            include "php/page_foot.php";
        ?>
    </body>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src = "js/blog.js"></script>
</html>
