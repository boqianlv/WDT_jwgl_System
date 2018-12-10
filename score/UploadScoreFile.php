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
        BatchScore($_FILES["file"]["tmp_name"]);
    }
}
else {
    exit("Invalid file");
}
function BatchScore($filename)
{
    $file = fopen($filename,'r');
    $i=0;
    $a=array();
    //while($data=fgetcsv($file))echo '???';
    while ($data = fgetcsv($file))
    {
        //var_dump($data);
        if($i)
        {

            //for($j=0;$j<7;$j++)
                //array_push($a,$data[$j]);
            $tmp=[0,1,2,3,4,5,6];
            foreach($tmp as $_i)
                $data[$_i] = iconv('GBK', 'utf-8//IGNORE', $data[$_i]);
            try{
                global $dsn,$user,$pass;
                $dbh = new PDO($dsn, $user, $pass);
                //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $set=$dbh->prepare("SET NAMES utf8");
                $set->execute();

                $upd = $dbh->prepare("Insert into stu_score(student_id) values (?)");
                $ib_bool=$upd->execute(array($data[0]));

                $sel=$dbh->prepare("SELECT student_score from stu_score where student_id=?");
                $sel->execute(array($data[0]));
                $ans=$sel->fetchAll(PDO::FETCH_ASSOC);
                $exp=json_decode($ans[0]['student_score'],1);
                if($exp==NULL)$exp=array();
                array_push($exp,array('class_hour'=>$data[2],'course_name'=>$data[1],'course_score'=>$data[3],'credit'=>$data[4],'status'=>$data[5],'type'=>$data[6]));
                $tmp=json_encode($exp);
                $data=[$tmp,$data[0]];
                $updscore=$dbh->prepare("update stu_score set student_score=? where student_id=?");
                $is_bool=$updscore->execute($data);
                if($is_bool)
                    echo 'score_batch_done';
                else
                    echo 'score_batch_error';

                //json_decode();
            }
            catch (PDOException $e)
            {
                die("Error!" . $e->getMessage() . '<br/>');
            }
        }
        $i++;
        //echo $i;
    }


}