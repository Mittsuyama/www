<div id = "tagBox" class = "rightHead">
    <i class="rightMargin fa fa-tags" aria-hidden="true"></i>
    <span class = "tabBoxTitle">TAGS</span>
    <div class = "hrGray"></div>
    <div id = "tagBoxContent">
        <?php
            function utf8_array_asort(&$array) {
                if(!isset($array) || !is_array($array)) {
                return false;
                }
                foreach($array as $k=>$v) {
                $array[$k] = iconv('UTF-8', 'GBK//IGNORE',$v);
                }
                asort($array);
                foreach($array as $k=>$v) {
                $array[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
                }
                return true;
            }
            function uniform_time($str) {
                $str = str_replace(PHP_EOL, "", $str);
                $str = str_replace(" ", "", $str);
                $str = str_replace("/", "-", $str);
                $res = "";
                $res = substr($str, 0, 5);
                if($str[6] == '-') $res .= "0" . $str[5];
                else $res .= $str[5] . $str[6];
                $res .= "-";
                if($str[-2] == '-') $res .= "0" . $str[-1];
                else $res .= $str[-2] . $str[-1];
                return $res;
            }
            include_once "php/md_html.php";
            $path = "md/";
            $file_list = get_md_list($path);
            $mdCount = count($file_list);
            $files = array();
            for($i = 0; $i < $mdCount; $i++) {
                $file = fopen($path . $file_list[$i], "r");
                $text = array();
                for($j = 0; $j < 4; $j++) array_push($text, fgets($file));
                fclose($file);
                array_push($files, $text);
            }
            $temp = array();
            for($i = 0; $i < $mdCount; $i++) {
                $files[$i][3] = uniform_time($files[$i][3]);
                $files[$i][1] = htmlspecialchars(str_replace(PHP_EOL, "", str_replace(" ", "", $files[$i][1])));
                $tags = explode("|", $files[$i][1]);
                foreach($tags as $tag) {
                    $tag = preg_replace("/\s*/", "", $tag);
                    array_push($temp, $tag);
                }
            }
            $temp = array_unique($temp);
            utf8_array_asort($temp);
            echo "<a href = 'index.php'><div class = \"rightTagHead\">所有文章</div></a>";
            foreach($temp as $tag) {
                echo "<a href = 'index.php?tag=$tag'>";
                if(strtolower($tag) == $webTag) echo "<div class = \"rightTag selected\">";
                else echo "<div class = \"rightTag\">";
                echo $tag;
                echo "</div></a>";
            }
            echo "<div style = \"clear:both;\"></div>";
        ?>
    </div>
</div>