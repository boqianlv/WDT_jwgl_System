<?php
$csv_mimetypes = array(
    'text/csv',
    'text/plain',
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    'text/anytext',
    'application/octet-stream',
    'application/txt',
);
require '../login/config.php';
if (in_array($_FILES['file']['type'], $csv_mimetypes)&&$_FILES["file"]["size"]<256000) {
    if ($_FILES["file"]["error"] > 0) {
        echo "ERROR:" . $_FILES["file"]["error"] . "<br />";

    }
    else {
        BatchTeacher($_FILES["file"]["tmp_name"]);
    }
}
else {
    exit("Invalid file");
}
function BatchTeacher($filename)
{
    $file = fopen($filename,'r');
    $i=0;
    $a=array();
    while ($data = fgetcsv($file))
    {
        if($i)
        {
            if(isset($data[5]))exit("Not teacher!");
            $tmp=[2,3,4];
            foreach($tmp as $_i)
                $data[$_i] = iconv('GBK', 'utf-8//IGNORE', $data[$_i]);
            for($j=0;$j<4;$j++)
                array_push($a,$data[$j]);
        }
        $i++;
    }


    $sqlqueryBatch="INSERT INTO teacher(teacher_id,teacher_pass,teacher_name,teacher_department,teacher_major,teacher_key,teacher_join_time,usertype) VALUES(?,'qwerty',?,?,?,UUID(),now(),2)";
    for($k=0;$k<$i-2;$k++)
    {
        $sqlqueryBatch.=",(?,'qwerty',?,?,?,UUID(),now(),2)";
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
            echo 'tea_batch_done';
        else
            echo 'tea_batch_error';
    }
    catch (PDOException $e)
    {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}

