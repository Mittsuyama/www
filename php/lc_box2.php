<comment>
    <div id = "lcBox" class = "rightHead">
        <i class="rightMargin fa fa-comments" aria-hidden="true"></i>
        <span class = "tabBoxTitle">LATEST COMMENTS</span>
        <div class = "hrGray"></div>
        <?php
            $xmlDoc = new DOMDocument();
            $xmlDoc->load("xml/com.xml");
            if($xmlDoc->getElementsByTagName("time")[0] != false) {
                $time_list = $xmlDoc->getElementsByTagName("time");
                $list_count = 0;
                $temp = array();
                foreach($time_list as $node) {
                    $time = $node->nodeValue;
                    $temp[$time] = $list_count;
                    $list_count++;
                }
                arsort($temp);
                $list_count = 0;
                foreach($temp as $tempOrder) {
                    $node = $xmlDoc->getElementsByTagName("time")[$tempOrder];
                    $id = substr($node->parentNode->parentNode->nodeName, 2);
                    $rootNode = $node->parentNode;
                    echo "<a class = 'rightComment' href = \"blog.php?id=$id#commentTitle\">";
                    echo "<div class = \"limitBox\">";
                    echo $rootNode->getElementsByTagName("text")[0]->nodeValue;
                    echo "</div>";
                    echo "<span class = \"limitBox2\">";
                    $file = fopen($path . $file_list[$id], "r");
                    $temp = fgets($file);
                    fclose($file);
                    echo "—— " . $temp;
                    echo "</span></a>";
                    $list_count++;
                    if($list_count > 10) break;
                }
            }
            else {
                echo "NOTHING.";
            }
        ?>
    </div>
</comment>