<?php
require 'config.php';
ini_set("display_errors","On");
error_reporting(E_ALL);
try{
    $dbh=new PDO($dsn,$user,$pass);
    echo '连接成功！<br/>';
    $dbh=null;
}
catch(PDOException $e){
    die("Error!".$e->getMessage().'<br/>');
}