<?php
require '../login/config.php';
error_reporting(E_ALL ^ E_NOTICE);
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
teacher_insert($dat);
function teacher_insert($d)
{
    global $dsn,$user,$pass;
    try {
        $data = [
            $d['teacher_id'],
            $d['teacher_name'],
            $d['teacher_department'],
            $d['teacher_major'],
        ];
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $upd = $dbh->prepare("INSERT INTO teacher(teacher_id,teacher_pass,teacher_name,teacher_department,teacher_major,teacher_key,teacher_join_time,usertype) VALUES(?,'qwerty',?,?,?,UUID(),now(),2)");
        $tbool=$upd->execute($data);
        if($tbool)echo 'tea_done';
        else echo 'tea_error!';
    }catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }

}