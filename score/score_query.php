<?php
require '../login/config.php';
error_reporting(E_ALL ^ E_NOTICE);
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
$key=$dat['key'];
selscore($key);
function selscore($k)
{

    global $dsn,$user,$pass,$key;
    try {
        $dbh = new PDO($dsn, $user, $pass);
        $sel=$dbh->prepare('SELECT student_id FROM student WHERE student_key=?');
        $sel->execute(array($key));
        $h=json_encode($sel->fetchall(PDO::FETCH_ASSOC));
        $que=json_decode($h,1);
        $sel_=$dbh->prepare('SELECT student_score from stu_score where student_id=?');
        //var_dump($que);
        //echo $h;
        $sel_->execute(array($que[0]['student_id']));
        $p=$sel_->fetchall(PDO::FETCH_ASSOC);
        print_r($p[0]['student_score']);
    }
    catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}