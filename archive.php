<!DOCTYPE HTML>
<html lang = "zh-CN">
    <head>
        <?php include "php/html_head.php"; ?>
        <link rel = "stylesheet" href = "css/index.css" />
        <link rel = "stylesheet" href = "css/archive.css" />
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
            include "php/nav.php";
            $nowUrl = "index.php";
            $webTag = strtolower(htmlspecialchars($_GET["tag"]));
            $webPage = htmlspecialchars($_GET["page"]);
            if($webTag == null) {
                $webTag = "|";
                $nowUrl = $nowUrl . "?page=";
            }
            else {
                $nowUrl = $nowUrl . "?tag=" . $webTag . "&page=";
            }
            if($webPage == null) $webPage = 1;
            else $webPage = intval($webPage);
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
                <?php
                    $time_order = array();
                    $list_count = 0;
                    foreach($files as $file) {
                        $time_order[$list_count] = $file[3];
                        $list_count++;
                    }
                    arsort($time_order);
                    $now_time = "";
                    while($i = key($time_order)) {
                        // echo "i = " . $i . "<br>";
                        if(substr($files[$i][3], 0, 4) != $now_time) {
                            $now_time = substr($files[$i][3], 0, 4);
                            echo "<div class = 'timeHead'>$now_time</div>";
                        }
                        echo "<a href = 'blog.php?id=$i'><div class = 'timeList'>";
                        echo "<span class = 'timeListTime'>" . uniform_time($files[$i][3]) . " </span>";
                        echo "<span class = 'timeListTitle'>" . $files[$i][0] . "</span>";
                        echo "</div ></a>";
                        next($time_order);
                    }
                ?>
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
