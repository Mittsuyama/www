<!DOCTYPE HTML>
<html lang = "zh-CN">
    <head>
        <?php include "php/html_head.php"; ?>
        <link rel = "stylesheet" href = "css/index.css" />
    </head>
    <body>
        <?php
            include "php/nav.php"
        ?>
        <?php
            $qContent = htmlspecialchars($_GET["q"]);
            $webPage = htmlspecialchars($_GET["page"]);
            if($webPage == null) $webPage = 1;
            else $webPage = intval($webPage);
            $nowUrl = "s.php?q=" . $qContent . "&page=";
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
                    $orderList = array();
                    for($i = $mdCount - 1; $i >= 0; $i--) {
                        $text = file_get_contents($path . $file_list[$i]);
                        if($qContent == "" || strstr($text, $qContent) != false) {
                            array_push($orderList, $i);
                        }
                    }
                    $listLen = count($orderList);
                    if($webTag != "|") echo "<div id = \"selectTag\">YOU SEARCH : <span id = \"selectTagC\">". $qContent ."</span></div>";
                    if($listLen == 0) {
                        echo '<img style = "position: relative; width: 50px; float: left;" src = "icon/box.svg">';
                        echo '<span style = "position: relative; top: 5px; left: 20px; font-size: 25px; font-family: gray;">NOTHING FOUND.</span>';
                    }
                    else {
                        $everyPage = 4;
                        $pageCount = intdiv(($listLen - 1), $everyPage) + 1;
                        for($j = ($webPage - 1) * $everyPage; $j < min($listLen, ($webPage - 1) * $everyPage + $everyPage); $j++) {
                            $i = $orderList[$j];
                            include "php/print.php";
                        }
                        echo "<div class = \"hrGray udMargin\"></div>";
                    }
                ?>
                <div id = "pageButtonBox">
                    <?php
                        if($listLen > 0) {
                            if($webPage != 1) echo "<a href = \"". $nowUrl . ($webPage - 1) ."\"><span class = \"pageButton\" style = \"left: 0px;\"><i class=\"rightMargin fa fa-angle-left\" aria-hidden=\"true\"></i>LAST PAGE</span></a>";
                            echo '<div id = "pageButtonCenter">';
                                function printButton($order) {
                                    global $nowUrl;
                                    echo "<a href = \"". $nowUrl . $order ."\"><span class = \"pageButtonC\">$order</span></a>";
                                }
                                if($webPage < 5) {
                                    for($i = 1; $i < $webPage; $i++) printButton($i);
                                }
                                else {
                                    printButton(1);
                                    echo "<span class = \"buttonDots\">...</span>";
                                    for($i = $webPage - 2; $i < $webPage; $i++) printButton($i);
                                }
                                echo "<span class = \"disableButton\">$webPage</span>";
                                if($pageCount - $webPage < 4) {
                                    for($i = $webPage + 1; $i <= $pageCount; $i++) printButton($i);
                                }
                                else {
                                    for($i = $webPage + 1; $i <= $webPage + 2; $i++) printButton($i);
                                    echo "<span class = \"buttonDots\">...</span>";
                                    printButton($pageCount);
                                }
                            echo '</div>';
                            if($webPage != $pageCount && $listLen > 4) echo "<a href = \"". $nowUrl . ($webPage + 1) ."\"><span class = \"pageButton\" style = \"right: 0px;\">NEXT PAGE<i class=\"leftMargin fa fa-angle-right\" aria-hidden=\"true\"></i></span></a>";
                        }
                    ?>
                </div>
            </div>
            <div style = "clear: both;"></div>
        </div>
        <?php
            include "php/page_foot.php";
        ?>
    </body>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/index.js"></script>
    <script src="js/small.js"></script>
    <script src ="js/s.js"></script>
</html>
