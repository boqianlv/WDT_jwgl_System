<?php
require '../login/config.php';
ini_set("display_errors","On");
error_reporting(E_ALL);
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
if(strlen($dat['id'])==9)
{
    selstudent($raw);
}
else if(strlen($dat['id'])==10)
{
    selteacher($raw);
}
function selstudent($raw)
{
    global $dsn,$user,$pass;
    try {
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $data = json_decode($raw, true);
        $date = [
            $data['id']
        ];
        $sel = $dbh->prepare("SELECT student_id,student_name,student_sex,student_department,student_major,student_political_status,student_from,student_class,student_idcard,student_birthplace,student_address,student_key,student_join_time,usertype FROM student WHERE student_id=(?)");
        $sel->execute($date);
        echo json_encode($sel->fetchall(PDO::FETCH_ASSOC));
        $dbh = null;
    } catch (PDOException $e) {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}
function selteacher($raw)
{
    global $dsn,$user,$pass;
    try{
        $dbh=new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $data = json_decode($raw, true);
        $date = [
            $data['id']
        ];
        $sel = $dbh->prepare("SELECT teacher_id,teacher_name,teacher_department,teacher_major,teacher_key,teacher_join_time,usertype FROM teacher WHERE teacher_id=(?)");
        $sel->execute($date);
        echo json_encode($sel->fetchall(PDO::FETCH_ASSOC));
        $dbh = null;
    }catch (PDOException $e) {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}

