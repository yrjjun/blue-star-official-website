<?php
require("../function.php");

if($bluestar->bad_words("../file/json/bad_words.json", $_REQUEST["text"])){
    die(json_encode(array("code"=>200)));
}else{
    die(json_encode(array("code"=>404)));
}
?>