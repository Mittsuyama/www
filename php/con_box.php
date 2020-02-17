<div style = "width: 100%; height: 50px;"></div>
<div id = "contentsBox" class = "rightHead">
    <i class="fa fa-bookmark rightMargin" aria-hidden="true"></i>
    <span id = "tabBoxTitle">CONTENTS</span>
    <div class = "hrGray"></div>
    <?php
        $order = htmlspecialchars($_GET["id"]);
        $file = fopen($path . $file_list[$order], "r");
        $head_list = array();
        while(!feof($file)) {
            $line = fgets($file);
            if($line[3] != "#" && substr($line, 0, 3) == "###") {
                array_push($head_list, array(str_replace(PHP_EOL, '', substr($line, 3)), 2));
            }
            if($line[2] != "#" && substr($line, 0, 2) == "##") {
                array_push($head_list, array(str_replace(PHP_EOL, '', substr($line, 3)), 1));
            }
        }
        fclose($file);
        $h2Order = 0;
        $h3Order = 0;
        $head_first = true;
        echo "<ol class = \"h2ListOl\">";
        foreach($head_list as $head) {
            if($head[1] == 1) {
                if(!$head_first) echo "</ol>";
                else $head_first = false;
                echo "<li><a href = \"#h2$h2Order\"><div id = \"h2text$h2Order\" class = \"h2List\">" . $head[0] . "</div></a></li>";
                echo '<ol class = "h2Box" id = "h2Box' . $h2Order . '">';
                $h2Order++;
            }
            else {
                echo '<li><a href = "#h3' . $h3Order .'"><div class = "h3Text">' . $head[0] . '</div></a></li>';
                $h3Order++;
            }
        }
        if(!$h2Order) {
            echo "<li><a href = \"#\"><div class = \"h2List\">无</div></a></li>";
        }
        echo "</ol>";
    ?>
</div>