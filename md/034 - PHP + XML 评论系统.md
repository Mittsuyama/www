PHP + XML 构建评论系统
PHP | XML | AJAX | 计算机 | 网页
国内的评论系统插件一个接一个死了，Disqus 需要科学上网，决定自己做一个 PHP 的评论系统。一开始想用 PHP + MySQL，但是由于新版 MySQL 的身份验证一些版本的 PHP 并不支持，于是想用 PHP + XML 硬怼一个评论系统。
2019-7-13

国内的评论系统插件一个接一个死了，Disqus 需要科学上网，决定自己做一个 PHP 的评论系统。一开始想用 PHP + MySQL，但是由于新版 MySQL 的身份验证一些版本的 PHP 并不支持，于是想用 PHP + XML 硬怼一个评论系统。

## 评论发布表单

先上 *HTML* 代码。

```html
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
```

表单样式的 *CSS* 内容就不放出来了，随便怼怼就行。



### 评论区字数监听

评论输入框 *textarea* 限制 500 个字，如何实现字数限制并实施监听的功能？

字数限制在 *HTML* 中修改属性即可，*maxlength = "500"*：

```html
<textarea name = "comment" id = "comText" maxlength = "500" placeholder = "SAY SOMETHING..."></textarea>
```

然后用 *JavaScript* 实现字数监听，并实时修改

```javascript
$("#comText").on('input propertychange', function () {
    var userDesc = $(this).val();
    var len;
    if (userDesc) {
        len = userDesc.str;
    }
    else {
        len = 0
    }
    $("#numLimit").html(len + '/500');
});
```

## POST 内容

### JQuery 的 POST 方法

使用 *jQuery* 的 *POST* 方法来提交内容。

不太清楚当时构建怎么想的，POST 的按钮用的 *div*，而不是 *<input type = "submit" />*，那就能监听 *click* 事件并手动 POST 表单内容：

```javascript
$("#comPost").click(function() {
    $.post("create_com.php",
        {
            name: $("#comName").val(),
            mail: $("#comMail").val(),
            text: $("#comText").val(),
            time: "<?php echo date("Y-m-d H:i");?>",
            order: "<?php echo $order?>"
        },
        function(data) {
            alert(data);
        }
    );
});
```

监听点击事件，使用 POST 方法向 PHP 传输数据。

`注意`：代码第 8 行有一个 *$order* 变量，这个变量保存了本篇文章的序号，方便与其他文章的评论作出区分。

*function(data)* 返回函数暂时先显示 PHP 处理情况，方便 Debug。

### PHP 处理 POST 信息

#### PHP 获取评论信息

```php
$name = test_input($_POST["name"]);
$mail = test_input($_POST["mail"]);
$text = test_input($_POST["text"]);
$myTime = test_input($_POST["time"]);
```

然后对评论信息进行检测，比如用户名、邮箱是否有输入，邮箱是否有效，用 PHP 的正则表达式查找 *preg_match* 进行判断，相关方法：[PHP 正则表达式查找](blog.php?id=33)



在这个 PHP 文件中所有的输出内容都会在上面的 `2.1 JQuery 的 POST 方法` 代码中 POST 方法的返回函数 *funciton(data)* 的 *data* 变量中。

比如 PHP 文件想返回错误信息：*echo "error:1";*，那么返回函数中的 *data* 的值为 *"error:1"*，这样就能根据 PHP 的返回值，修改评论页面的内容。



比如如果邮箱非有效，则返回 *erro:3*：

```php
function correctMail($str) {
    return preg_match("/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/", $str);
}
if(!correctMail($mail)) echo "error:3";
```



#### 修改 XML 文件

加载 XML 文件（没有就先新建文件）：

```php
if(!file_exists("com.xml")) {
    $xmlfile = fopen("com.xml", "w");
    fwrite($xmlfile, "<?xml version=\"1.0\" encoding=\"utf-8\"?><comment></comment>");
    fclose($xmlfile);
}
$xmlDoc = new DOMDocument();
$xmlDoc->load("xml/com.xml");
```

通过 POST 方法获取本片文章序号：

```php
$order = $_POST["order"];
```

获取本片文章评论的 DOM 内容，首先判断是否有评论，没有先在 DOM 中新建根节点。

```php
if($xmlDoc->getElementsByTagName("id" . $order)[0] == false) {
    $root = $xmlDoc->createElement("id" . $order);
    $xmlDoc->getElementsByTagName("comment")[0]->appendChild($root);
}
```

在 DOM 中创建新的评论信息，按照以下格式创建：

```xml
<usr>
    <id>name</id>
    <mail>mail-adress</mail>
    <time>comment time</time>
    <text>comment content</text>
</usr>
```

PHP 文件的内容：

```php
function myCreat($id, $str) {
    global $xmlDoc;
    $node = $xmlDoc->createElement($id);
    $textNode = $xmlDoc->createTextNode($str);
    $node->appendChild($textNode);
    return $node;
}

$usrNode = $xmlDoc->createElement("usr");
$nameNode = myCreat("id", $name);
$mailNode = myCreat("mail", $mail);
$timeNode = myCreat("time", $myTime);
$textNode = myCreat("text", $text);
$usrNode->appendChild($nameNode);
$usrNode->appendChild($mailNode);
$usrNode->appendChild($timeNode);
$usrNode->appendChild($textNode);
$xmlDoc->getElementsByTagName("id" . $order)[0]->appendChild($usrNode);
```

最后保存 XML 文件，返回成功修改：

```php
$xmlDoc->save("../xml/com.xml");
echo "success";
```

### JQuery 处理 POST 返回值

在返回函数 *function(data)* 根据返回值，更新评论页面，比如根据错误代码提供错误信息，成功评论则刷新页面。

```javascript
function(data) {
    if(data.substring(0, 5) == "error") {
        var errorInfo = data.substring(6, 7);
        var errorStr = ""
        switch(errorInfo) {
            case "1":
                errorStr += "请输入用户名";
                break;
            case "2":
                errorStr += "请输入邮箱";
                break;
            case "3":
                errorStr += "请输入有效邮箱";
                break;
            case "4":
                errorStr += "请输入评论";
                break;
            case "0":
                errorStr += "此文章评论数已达到最大";
                break;
        }
        $("#boxError").html(errorStr);
    }
    else {
        window.location.reload ()
    }
}
```

## 加载评论内容

这个就非常简单了，HTML + CSS + PHP，获取评论内容，加载评论，用 CSS 美化一下，搞定！

## 其他问题

在一些服务器中，在 *$xmlDoc = new DOMDocument();* 处无法运行。

打开错误提示，会告诉你 **Fatal error: Uncaught Error: Class 'DOMDocument' not found in….** 之类的信息。那是因为服务器没有 **php-xml** 扩展，安装即可。

假设使用 Ubuntu 作为系统，首先查看 PHP 版本号：

```shell
php --version
```

然后根据**版本号**，安装 php-xml 扩展。

比如我现在的 PHP 版本号是 PHP 7.2.20，那么就安装 7.2 版本的扩展：

```shell
sudo apt-get install php7.2-xml
```

