<div id = "searchBox">
    <div id = "inputBox">
        <div id = "inputImgBox">
            <img class = "normalImg" src = "icon/favicon.ico" />
        </div>
        <input name = "search" type = "text" placeholder = "SEARCH FOR BLOGS" id = "searchInput" value = "<?php echo htmlspecialchars(trim(stripslashes($_GET["q"])))?>">
    </div>
</div>