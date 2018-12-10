<?php
require '../login/config.php';
error_reporting(E_ALL ^ E_NOTICE);
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
//var_dump($dat);
if($dat['usertype']==1)
    student_update($dat);
else if ($dat['usertype']==2)
    teacher_update($dat);

function student_update($d)
{
    global $dsn,$user,$pass;
    try {
        $data = [
            $d['_student_id'],
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
            $d['student_id']
        ];
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $upd = $dbh->prepare('UPDATE student SET student_id = ?,student_name=? , student_sex=? , student_department=?,student_major=?,student_political_status=?,student_from=?,student_class=?,student_idcard=?,student_birthplace=?,student_address=? where student_id=?');
        $ubool=$upd->execute($data);
        if($ubool)echo 'stu_done';
        else echo 'stu_error';
    }catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }

}
function teacher_update($d)
{
    global $dsn,$user,$pass;
    try {
        $data = [
            $d['_teacher_id'],
            $d['teacher_name'],
            $d['teacher_department'],
            $d['teacher_major'],
            $d['teacher_id']
        ];
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $upd = $dbh->prepare("UPDATE teacher SET teacher_id=?,teacher_name=?,teacher_department=?,teacher_major=? where teacher_id=?");
        $sbool=$upd->execute($data);
        //echo $upd;
        if($sbool)
            echo 'tea_done';
        else
            echo 'tea_error';
    }catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }

}