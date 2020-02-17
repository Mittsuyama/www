<?php
$rec = array(13, 2, 1, 25, 30, 16, 0);
function get_md_list($path) {
    $myDir = opendir($path);
    $files = array();
    while(($filename = readdir($myDir)) !== false) {
        if(strstr($filename, ".md")) {
            array_push($files, $filename);
        }
    }
    sort($files);
    return $files;
}

function myGetText($path) {
    $file = fopen($path, "r");
    $text = array();
    while(!feof($file)) {
        array_push($text, fgets($file));
    }
    fclose($file);
    return $text;
}

function myReplace($str, $chars, $start, $end) {
    $strLength = strlen($str);
    $charLength = strlen($chars);
    $isLeft = true;
    $pos = 0;
    while($pos + $charLength <= $strLength) {
        if(substr($str, $pos, $charLength) == $chars) {
            if($isLeft) {
                if($str[$pos + 1] == " ") {
                    $pos++;
                    continue;
                }
                $isLeft = false;
                $str = substr($str, 0, $pos) . $start . substr($str, $pos + $charLength);
                $strLength = strlen($str);
            }
            else {
                if($str[$pos - 1] == " ") {
                    $pos++;
                    continue;
                }
                $isLeft = true;
                $str = substr($str, 0, $pos) . $end . substr($str, $pos + $charLength);
                $strLength = strlen($str);
            }
        }
        $pos++;
    }
    return $str;
}

function part_replace($str) {
    $str = myReplace($str, "~~", "<s>", "</s>");
    $str = myReplace($str, "**", "<strong>", "</strong>");
    $str = myReplace($str, "*", "<span class = \"italic\">", "</span>");
    $str = myReplace($str, "`", "<em>", "</em>");
    $str = str_replace("&lt;u&gt;", "<u>", $str);
    $str = str_replace("&lt;/u&gt;", "</u>", $str);
    $str = str_replace("&lt;!--", "<span class = 'small'>", $str);
    $str = str_replace("--&gt;", "</span>", $str);
    return $str;
}

function getContent($str) {
    $str = htmlspecialchars($str);
    $str = str_replace(PHP_EOL, '', $str);
    $pos = 0;
    $dollar_pos = array();
    while(strpos($str, "$", $pos) != false) {
        $pos = strpos($str, "$", $pos) + 1;
        if($str[$pos - 2] == "\\") continue;
        array_push($dollar_pos, $pos - 1);
    }
    array_push($dollar_pos, strlen($str));
    $res = $str;
    $str = "";
    $pos = 0;
    $dollar_include = false;
    foreach($dollar_pos as $i) {
        if(!$dollar_include) {
            // echo $pos . " " . $i . "<br>";
            $dollar_include = true;
            $str .= part_replace(substr($res, $pos, $i - $pos));
            $pos = $i;
        }
        else {
            // echo $pos . " " . ($i + 1) . "<br>";
            $dollar_include = false;
            $str .= substr($res, $pos, $i + 1 - $pos);
            $pos = $i + 1;
        }
    }

    //Picture
    while(preg_match("/\!\[.+\]\(.+\)/", $str, $matches)) {
        preg_match("/\[.+\]/", $matches[0], $alt);
        preg_match("/\(.+\)/", $matches[0], $href);
        $src = substr($href[0], 1, -1);
        if(substr($src, 0, 6) == "../img" || substr($src, 0, 6) == "..\img") {
            if($_SERVER['HTTP_HOST'] != "localhost") $src = "../img" . substr($src, 6);
        } else {
            $src = "../img" . substr($src, 6);
        }
        $str = str_replace($matches[0], "<div class = \"blogImgBox\"><img onclick=\"javascript:window.open(this.src)\" class = \"blogImg\" alt = \"" . substr($alt[0], 1, -1) . "\" src = \"" . $src ."\"/></div>", $str);
    }

    //link
    while(preg_match("/\[.+\]\(.+\)/", $str, $matches)) {
        preg_match("/\[.+\]/", $matches[0], $alt);
        preg_match("/\(.+\)/", $matches[0], $href);
        $str = str_replace($matches[0], "<a href = \"" . substr($href[0], 1, -1) ."\" class = \"myLink\"><i class=\"rightMargin fa fa-paper-plane\"></i>" . substr($alt[0], 1, -1) ."</a>", $str);
    }

    return "<div class = \"normal\">" . $str . "</div>\n";
}

function mdToHtml($path) {
    $text = myGetText($path);
    $textLenght = count($text);

    echo "<div id = \"blogTitle\">\n" . str_replace(PHP_EOL, '', $text[0])."</div>";
    echo "<div id = \"blogTime\"><i class=\"rightMargin fa fa-calendar\" aria-hidden=\"true\"></i>\n" . str_replace(PHP_EOL, '', $text[3]) ."</div>";
    echo "<div class = \"fileTag\">";
    $tags = explode("|", htmlspecialchars(str_replace(PHP_EOL, "", str_replace(" ", "", $text[1]))));
    foreach($tags as $tag) {
        echo "<a href = 'index.php?tag=$tag'><span class = \"subTags\">" . str_replace(" ", "", $tag) . "</span></a>";
    }
    echo "</div>";
    echo "<div class = \"hrGray udMargin\"></div>";

    //Pages start at the 5th line.
    $start = 5;
    $order2 = $order3 = $order4 = $order5 = 0;
    $h2Order = 0;
    $h3Order = 0;
    while($start < $textLenght) {
        $line = $text[$start];
        if(substr($line, 0, 5) == "#####") {
            $order5++;
            echo "<div class = \"head5 heads\"><span class = \"articleOrder\">";
            echo max($order2, 1).".".max($order3, 1).".".max($order4, 1).".".max($order5, 1);
            echo "</span>" . str_replace(PHP_EOL, '', substr($line, 5)) . "</div>\n";
        }
        else if(substr($line, 0, 4) == "####") {
            $order4++;
            $order5 = 0;
            echo "<div class = \"head4 heads\"><span class = \"articleOrder\">";
            echo max($order2, 1).".".max($order3, 1).".".max($order4, 1);
            echo "</span>" . str_replace(PHP_EOL, '', substr($line, 4)) . "</div>\n";
        }
        else if(substr($line, 0, 3) == "###") {
            $order3++;
            $order4 = $order5 = 0;
            echo "<div class = \"head3 heads\" id = \"h3". $h3Order . "\"><span class = \"articleOrder\">";
            $h3Order++;
            echo max($order2, 1).".".max($order3, 1);
            echo "</span>" . str_replace(PHP_EOL, '', substr($line, 3)) . "</div>\n";
        }
        else if(substr($line, 0, 2) == "##") {
            $order2++;
            $order3 = $order4 = $order5 = 0;
            echo "<div class = \"head2 heads\" id = \"h2". $h2Order ."\"><span class = \"articleOrder\">";
            $h2Order++;
            echo max($order2, 1);
            echo "</span>" . str_replace(PHP_EOL, '', substr($line, 2)) . "</div>\n";
        }
        else if($line[0] == ">") {
            $end = $start;
            while($end < $textLenght && $text[$end][0] == ">") $end++;
            echo "<div class = \"quote\">\n";
            for($j = $start; $j < $end; $j++) {
                if(strlen($text[$j]) > 2) {
                    echo "<div class = 'qParagraph'>".str_replace(PHP_EOL, '', substr($text[$j], 1))."</div>\n";
                }
            }
            echo "</div>";
            $start = $end - 1;
        }
        else if(substr($line, 0, 3) == "```") {
            $end = $start + 1;
            while(substr($text[$end], 0, 3) != "```") $end++;
            echo "<pre><code class = \"". substr($line, 3) ."\">";
            for($j = $start + 1; $j < $end - 1; $j++) {
                echo htmlspecialchars($text[$j]);
            }
            echo str_replace(PHP_EOL, "", htmlspecialchars($text[$end - 1]));
            echo "</pre></code>";
            $start = $end;
        }
        else if(substr($line, 0, 3) == "1. ") {
            $spaceClass = 0;
            $tail = 0;
            $end = $start + 1;
            while($end < $textLenght && strlen($text[$end]) > 3) $end++;
            echo "<ol>\n";
            for($j = $start; $j < $end; $j++) {
                $spaceLength = 0;
                while($text[$j][$spaceLength] == " ") $spaceLength++;
                if($spaceLength > $spaceClass) {
                    echo "<ol>\n";
                    $spaceClass = $spaceLength;
                    $tail++;
                }
                else if($spaceLength < $spaceClass) {
                    echo "</ol>\n";
                    $spaceClass = $spaceLength;
                    $tail--;
                }
                echo "<li>" . getContent(substr($text[$j], strpos($text[$j], ".") + 1)) . "</li>";
            }
            while($tail > 0) {
                echo "</ol>\n";
                $tail--;
            }
            echo "</ol>\n";
            $start = $end - 1;
        }
        else if(substr($line, 0, 2) == "- ") {
            $spaceClass = 0;
            $tail = 0;
            $end = $start + 1;
            while($end < $textLenght && strlen($text[$end]) > 3) $end++;
            echo "<ul>\n";
            for($j = $start; $j < $end; $j++) {
                $spaceLength = 0;
                while($text[$j][$spaceLength] == " ") $spaceLength++;
                if($spaceLength > $spaceClass) {
                    echo "<ul>\n";
                    $spaceClass = $spaceLength;
                    $tail++;
                }
                else if($spaceLength < $spaceClass) {
                    echo "</ul>\n";
                    $spaceClass = $spaceLength;
                    $tail--;
                }
                echo "<li>" . getContent(substr($text[$j], strpos($text[$j], "-") + 1)) . "</li>";
            }
            while($tail > 0) {
                echo "</ul>\n";
                $tail--;
            }
            echo "</ul>\n";
            $start = $end - 1;
        }
        else if(strlen($line) < 3) {
            $end = $start + 1;
            while($end < $textLenght && strlen($text[$end]) < 3) $end++;
            if($end - $start > 2) {
                echo "<div class = 'normal'><br /></div>";
            }
            $start = $end - 1;
        }
        else if(substr($line, 0, 2) == "$$") {
            $end = $start + 1;
            while(substr($text[$end], 0, 2) != "$$") $end++;
            for($j = $start; $j < $end + 1; $j++) {
                echo $text[$j];
            }
            $start = $end;
        }
        else if(substr($line, 0, 3) == "---") {
            echo "<div class = \"hrGray\"></div>\n";
        }
        else echo getContent($line);
        $start++;

    }

    return str_replace(PHP_EOL, '', $text[0]);
}
?>