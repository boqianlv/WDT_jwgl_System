<?php
require '../login/config.php';
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
addcourses($dat);
function addcourses($data)
{
    try{
        global $dsn,$user,$pass;
        $dbh=new PDO($dsn,$user,$pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dat=[
            $data['course_name'],
            $data['course_week'],
            $data['teacher_name'],
            $data['course_student_num'],
            $data['course_range_college'],
            $data['course_range_major']
        ];
        $add=$dbh->prepare("INSERT INTO course(course_name, course_week, teacher_name, course_surplus_num, course_student_num, course_state, course_range_college, course_range_major) values (?,?,?,0,?,1,?,?)");
        $vool=$add->execute($dat);
        echo $vool;
    }catch(PDOException $e)
    {
        die('Error'.$e->getMessage());
    }

}