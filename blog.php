<!DOCTYPE HTML>
<html lang = "zh-CN">
    <head>
        <?php include "php/html_head.php"; ?>
        <link rel = "stylesheet" href = "css/index.css" />
        <link rel = "stylesheet" href = "css/blog.css" />
        <link rel = "stylesheet" href = "css/com.css" />
        <!-- code hight light -->
        <link rel = "stylesheet" href = "/highlight/styles/atom-one-dark.css">
        <script src = "/highlight/highlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </head>
    <body>
        <?php
            include "php/nav.php"
        ?>
        <div class = "container">
            <div id = "blogRightContainer" class = "containerRight">
                <div id = "rightBox">
                    <?php
                        include "php/md_html.php";
                        $path = "md/";
                        $file_list = get_md_list($path);
                        include "php/search_box.php";
                        include "php/rec_box.php";
                        include "php/lc_box2.php";
                        include "php/con_box.php";
                    ?>
                </div>
            </div>
            <div id = "blogContainer" class = "blogPage">
                <?php
                    $blogTitle = mdToHtml($path . $file_list[$order]);
                    echo "<script>document.title = \"MITSUYAMA | " . $blogTitle . "\"</script>";
                ?>
                <div style = "height: 70px;"></div>
                <div class = "hrGray"></div>
                <div style = "height: 20px;"></div>
                <div id = "threeButton">
                    <?php
                        if($order != count($file_list) - 1) {
                            $file = fopen($path . $file_list[$order + 1], "r");
                            $temp = str_replace(PHP_EOL, "", fgets($file));
                            fclose($file);
                            echo "<span id = \"lastBlogButton\" class = \"pageButton\" onclick=\"javascript:window.location.href='blog.php?id=". ($order + 1) ."'\">";
                            echo "<i class=\"rightMargin fa fa-angle-left\" aria-hidden=\"true\"></i>";
                            echo "LAST BLOG</span>";
                            echo "<div id = \"lastBlogT\"><div id = \"lastBlogA\"></div>$temp</div>";
                        }
                        if($order != 0) {
                            $file = fopen($path . $file_list[$order - 1], "r");
                            $temp = str_replace(PHP_EOL, "", fgets($file));
                            fclose($file);
                            echo "<span id = \"nextBlogButton\" class = \"pageButton\" onclick=\"javascript:window.location.href='blog.php?id=". ($order - 1) ."'\">";
                            echo "NEXT BLOG";
                            echo "<i class=\"leftMargin fa fa-angle-right\" aria-hidden=\"true\"></i></span>";
                            echo "<div id = \"nextBlogT\"><div id = \"nextBlogA\"></div>$temp</div>";
                        }
                        echo "<span id = \"rewardButton\" class = \"pageButton\"><i class=\"rightMargin fa fa-coffee\" aria-hidden=\"true\"></i>赏作者一杯咖啡</span>";
                        include "php/reward.php"
                    ?>
                </div>
                <div style = "height: 150px;"></div>
                <comment>
                    <div id = "commentTitleBox">
                        <i class = "rightMargin fa fa-comments" aria-hidden="true"></i>
                        <span id = "commentTitle">
                            <span id = "commentCount">0</span>COMMENTS</span>
                    </div>
                    <!-- <div class = "hrGray"></div> -->
                    <?php
                        include "php/com.php";
                    ?>
                </comment>
            </div>
            <div style = "clear: both;"></div>
        </div>
        <div class = "right-down-fixed">
            <a href = "#">
                <div id = "upToHead">
                    <i class="fa fa-chevron-up" aria-hidden="true"></i>
                </div>
            </a>
            <a href = "#rewardButton">
                <div id = "upToHead">
                    <i class="fa fa-comments" aria-hidden="true"></i>
                </div>
            </a>
        </div>
        <?php
            include "php/page_foot.php";
        ?>
    </body>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src = '<?php
        if($_SERVER['HTTP_HOST'] == "localhost") echo 'MathJax-master/MathJax.js?config=TeX-MML-AM_CHTML';
        else echo 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML';
    ?>' async></script>
    <script type = "text/x-mathjax-config">
        MathJax.Hub.Config({
            tex2jax: {
                skipTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code', 'comment'],
                inlineMath: [['$','$']],
                processEscapes: true
            },
            "HTML-CSS": { linebreaks: { automatic: true } },
            SVG: { linebreaks: { automatic: true } }
        });
    </script>
    <script src = "js/small.js"></script>
    <script src = "js/blog.js"></script>
    <script src = "js/s.js"></script>
    <script src = "js/float.js"></script>
    <script>
        $("#comPost").click(function() {
            $.post("php/create_com.php",
            {
                name: $("#comName").val(),
                mail: $("#comMail").val(),
                text: $("#comText").val(),
                time: "<?php echo date("Y-m-d H:i");?>",
                order: "<?php echo $order?>"
            },
                function(data) {
                    if(data.substring(0, 5) == "error") {
                        var errorInfo = data.substring(6, 7);
                        var errorStr = "<i class=\"rightMargin fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>";
                        switch(errorInfo) {
                            case "1":
                                errorStr += "请输入用户名";
                                break;
                            case "2":
                                errorStr += "请输入邮箱";
                                break;
                            case "3":
                                errorStr += "请输入有效邮箱";
                                break;
                            case "4":
                                errorStr += "请输入评论";
                                break;
                            case "0":
                                errorStr += "此文章评论数已达到最大";
                                break;
                        }
                        $("#boxError").html(errorStr);
                    }
                    else {
                        window.location.reload ()
                    }
                }
            );
        });
    </script>
</html>