<?php
function int($str){
    return intval($str);
}
function sha256($str){
    return hash("sha256",$str);
}
function str($str){
    return strval($str);
}