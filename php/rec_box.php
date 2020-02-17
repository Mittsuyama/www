<div id = "recBox" class = "rightHead">
    <i class="rightMargin fa fa-heart" aria-hidden="true"></i>
    <span class = "tabBoxTitle">RECOMMEND</span>
    <div class = "hrGray"></div>
    <?php
        $rec = array(13, 2, 1, 5, 25, 30, 16, 0);
        foreach($rec as $i) {
            $file = fopen($path . $file_list[$i], "r");
            $temp = fgets($file);
            fclose($file);
            echo "<a href = \"blog.php?id=$i\"><div class = \"recTitle\"><i class=\"rightMargin fa fa-angle-right\" aria-hidden=\"true\"></i>$temp</div></a>";
        }
    ?>
</div>