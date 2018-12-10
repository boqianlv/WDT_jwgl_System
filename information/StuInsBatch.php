<?php
//require 'student_insert.php';
require '../login/config.php';
require 'UploadInfFile_stu.php';
BatchStudent('../inf_all/'.$_FILES["file"]["name"]);
/*
$i='hello';$j='world';
$data=[
    $i,$j
];
var_dump($data);
*/

function BatchStudent($filename)
{
    $file = fopen($filename,'r');
    $i=0;
    $a=array();
    while ($data = fgetcsv($file))
    {
        if($i)
        {
            $tmp=[2,3,4,5,6,9,10];
            foreach($tmp as $_i)
                $data[$_i] = iconv('GBK', 'utf-8//IGNORE', $data[$_i]);
            //var_dump($data);

            for($j=0;$j<=10;$j++)
                array_push($a,$data[$j]);
        }
        $i++;
    }
    //echo $i;
    //var_dump($a);
    $sqlqueryBatch="INSERT INTO student(student_id,student_pass,student_name,student_sex,student_department,student_major,student_political_status,student_from,student_class,student_idcard,student_birthplace,student_address,student_key,student_join_time,usertype) VALUES(?,'qwerty',?,?,?,?,?,?,?,?,?,?,UUID(),now(),1)";
    for($k=0;$k<$i-2;$k++)
    {
        $sqlqueryBatch.=",(?,'qwerty',?,?,?,?,?,?,?,?,?,?,UUID(),now(),1)";
    }
    //echo $sqlqueryBatch;

    try{
        global $dsn,$user,$pass;
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();

        $upd = $dbh->prepare($sqlqueryBatch);
        $ib_bool=$upd->execute($a);
        if($ib_bool)
            echo 'stu_batch_done';
        else
            echo 'stu_batch_error';
    }
    catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}
