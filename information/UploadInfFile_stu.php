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
    // possible CSV file
    // could also check for file content at this point
    if ($_FILES["file"]["error"] > 0) {
        echo "ERROR:" . $_FILES["file"]["error"] . "<br />";

    }
    else {
        /*
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
        */
        BatchStudent($_FILES["file"]["tmp_name"]);
        /*if (file_exists("/var/www/html/inf_all/" . $_FILES["file"]["name"])) {
            exit($_FILES["file"]["name"] . " already exists. ");
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "/var/www/html/inf_all/" . $_FILES["file"]["name"]);
            //echo "Stored in: " . "/var/www/html/inf_all/" . $_FILES["file"]["name"];
        }*/
    }
}
else {
     exit("Invalid file");
}
//BatchStudent('../inf_all/'.$_FILES["file"]["name"]);
//echo  unlink('../inf_all/'.$_FILES["file"]["name"]);
function BatchStudent($filename)
{

    $file = fopen($filename,'r');
    $i=0;
    $a=array();
    while ($data = fgetcsv($file))
    {
        if($i)
        {
            if(!isset($data[5]))exit("Not student!");
            $tmp=[1,2,3,4,5,6,9,10];
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
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
