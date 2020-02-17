<?php
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function correctMail($str) {
        return preg_match("/^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/", $str);
    }
    function myCreat($id, $str) {
        global $xmlDoc;
        $node = $xmlDoc->createElement($id);
        $textNode = $xmlDoc->createTextNode($str);
        $node->appendChild($textNode);
        return $node;
    }

    $order = $_POST["order"];
    $xmlDoc = new DOMDocument();
    if(!file_exists("../xml/com.xml")) {
        $xmlfile = fopen("../xml/com.xml", "w");
        fwrite($xmlfile, "<?xml version=\"1.0\" encoding=\"utf-8\"?><comment></comment>");
        fclose($xmlfile);
    }
    $xmlDoc->load("../xml/com.xml");
    $comCount = 0;
    if($xmlDoc->getElementsByTagName("id" . $order)[0] != false) {
        $com = $xmlDoc->getElementsByTagName("id" . $order)[0]->getElementsByTagName("usr");
        $comCount = $com->length;
    }
    else {
        $comCount = 0;
    }

    if($comCount >= 20) {
        echo "error:0";
    }
    else {
        $name = test_input($_POST["name"]);
        $mail = test_input($_POST["mail"]);
        $text = test_input($_POST["text"]);
        $myTime = test_input($_POST["time"]);
        if($name == "") echo "error:1";
        else if($mail == "") echo "error:2";
        else if(!correctMail($mail)) echo "error:3";
        else if($text == "") echo "error:4";
        else {
            if($comCount == 0) {
                $root = $xmlDoc->createElement("id" . $order);
                $xmlDoc->getElementsByTagName("comment")[0]->appendChild($root);
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
            $xmlDoc->save("../xml/com.xml");
            echo "success";
        }
    }
?>