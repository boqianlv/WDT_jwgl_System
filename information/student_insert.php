<?php
require '../login/config.php';
error_reporting(E_ALL ^ E_NOTICE);
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
student_insert($dat);
function student_insert($d)
{
    global $dsn,$user,$pass;
    try {
        $data = [
            $d['student_id'],
            $d['student_name'],
            $d['student_sex'],
            $d['student_department'],
            $d['student_major'],
            $d['student_political_status'],
            $d['student_from'],
            $d['student_class'],
            $d['student_idcard'],
            $d['student_birthplace'],
            $d['student_address'],
        ];
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $upd = $dbh->prepare("INSERT INTO student(student_id,student_pass,student_name,student_sex,student_department,student_major,student_political_status,student_from,student_class,student_idcard,student_birthplace,student_address,student_key,student_join_time,usertype) VALUES(?,'qwerty',?,?,?,?,?,?,?,?,?,?,UUID(),now(),1);");
        $ibool=$upd->execute($data);
        if($ibool)
        echo 'stu_done';
        else echo 'stu_error';
    }catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }

}