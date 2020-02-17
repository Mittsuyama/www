<?php
    $blogOrder = htmlspecialchars($_GET["id"]);
?>
<div id = "commentBox">
    <form>
        <div id = "sayBox">
            <input name = "name" id = "comName" maxlength = "50" type = "text" placeholder = "ID" />
            <input name = "email" id = "comMail" maxlength = "50" type = "text" placeholder = "MAIL" />
            <textarea name = "comment" id = "comText" maxlength = "500" placeholder = "SAY SOMETHING..."></textarea>
            <div style = "height: 40px;"></div>
            <span id = "comPost">POST</span>
            <span id = "numLimit">0/500</span>
            <span id = "boxError"></span>
        </div>
    </form>
</div>
<div id = "commentContainer">
    <?php
        ini_set('display_errors',1);            //错误信息
        ini_set('display_startup_errors',1);    //php启动错误信息
        error_reporting(-1);
        if(!is_dir("xml/")) {
            mkdir("xml/");
        }
        if(!file_exists("xml/com.xml")) {
            $xmlfile = fopen("xml/com.xml", "w");
            fwrite($xmlfile, "<?xml version=\"1.0\" encoding=\"utf-8\"?><comment></comment>");
            fclose($xmlfile);
        }
        $comCount = 0;
        $xmlDoc = new DOMDocument();
        $xmlDoc->load("xml/com.xml");
        if($xmlDoc->getElementsByTagName("id" . $blogOrder)[0] != false) {
            $com = $xmlDoc->getElementsByTagName("id" . $blogOrder)[0];
            foreach($com->getElementsByTagName("usr") as $usr) {
                echo "<div class = \"usrBox\">";
                echo "<i class=\"usrImg fa fa-user-circle\" aria-hidden=\"true\"></i>";
                echo "<div class = \"usrName\">" . $usr->getElementsByTagName("id")[0]->nodeValue;
                echo "<span class = \"usrMail\">" . $usr->getElementsByTagName("mail")[0]->nodeValue . "</span></div>";
                echo "<div class = \"usrTime\">" . $usr->getElementsByTagName("time")[0]->nodeValue . "</div>";
                echo "<div class = \"usrText\">" . $usr->getElementsByTagName("text")[0]->nodeValue . "</div>";
                echo "</div>";
                $comCount++;
            }
        }
        else {
            echo "<div id = \"noComment\"><i class=\"rightMargin fa fa-frown-o\" aria-hidden=\"true\"></i>暂无评论，说点什么再走吧。</div>";
        }
    ?>
    <script>
        document.getElementById("commentCount").innerHTML = "<?php echo $comCount;?>";
    </script>
</div>