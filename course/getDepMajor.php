<?php
require '../login/config.php';
try{
    global $dsn,$user,$pass;
    $dbh=new PDO($dsn,$user,$pass);
    $zm=$dbh->prepare("SET NAMES utf8");
    $zm->execute();
    $sel=$dbh->prepare("select * from department");
    $sel->execute();
    $data=array(array());
    $DT=$sel->fetchAll(PDO::FETCH_ASSOC);
    $cnt=count($DT);
    for($i=0;$i<$cnt;$i++)
    {
        array_push($data[0],$DT[$i]['dep']);
        $selzy=$dbh->prepare("select major from dep_major where dep=?");
        $selzy->execute(array($DT[$i]['dep']));
        //echo $DT[$i]['dep'];
        $tmpzy=$selzy->fetchAll(PDO::FETCH_ASSOC);
        $cntzy=count($tmpzy);
        $datazy=array();
        for($j=0;$j<$cntzy;$j++)
        {
            array_push($datazy,$tmpzy[$j]['major']);
        }
        //var_dump($tmpzy);
        array_push($data,$datazy);
    }
    echo json_encode($data);
}
catch(PDOException $e)
{
    die('Error'.$e->getMessage());
}