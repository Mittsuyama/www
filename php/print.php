<?php
echo "<div class = \"fileList\">";
echo "<a href = 'blog.php?id=$i'><div class = \"fileTitle\">". $files[$i][0] . "</div></a>";
echo "<div class = \"fileTime\"><i class=\"rightMargin fa fa-calendar\" aria-hidden=\"true\"></i>". $files[$i][3] . "</div>";
//BLOG TAG
echo "<div class = \"fileTag\">";
$tags = explode("|", $files[$i][1]);
foreach($tags as $tag) {
    echo "<a href = 'index.php?tag=$tag'><span class = \"subTags\">" . str_replace(" ", "", $tag) . "</span></a>";
}
echo "</div>";
//BLOG Brief
echo "<div class = \"fileBrief\">". $files[$i][2] . "</div>";
echo "<a href = \"blog.php?id=$i\"><span class = \"readmore\"><i class=\"rightMargin fa fa-book\" aria-hidden=\"true\"></i>READ MORE</span></a>";
echo "</div>";