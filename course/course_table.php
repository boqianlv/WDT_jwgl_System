<?php
/*$a=json_decode('["1","2","3"]',1);
echo $a[0];*/


require '../login/config.php';
//ini_set("display_errors","On");
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE);
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);

switch($dat['num']/100000%100)
{
    case 1:
        {$depa='理';break;}
    case 2:
        {$depa='guangdian';break;}
    case 3:
        {$depa='jidian';break;}
    case 4:
        {$depa='dianxin';break;}
    case 5:
        {$depa='jisuanji';break;}
    case 6:
        {$depa='cailiao';break;}
    case 7:
        {$depa='huagong';break;}
    case 8:
        {$depa='shengming';break;}
    case 9:
        {$depa='jingguan';break;}
    case 10:
        {$depa='waiyu';break;}
    case 11:
        {$depa='wen';break;}
    case 12:
        {$depa='fa';break;}

}
$num=$dat['num'];
$week=$dat['week'];
selcourse($depa,$num,$week);
function selcourse($d,$n,$w)
{
    global $dsn,$user,$pass,$dat;
    try{
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $dataa = [
            $d,$n
        ];
        $sel=$dbh->prepare('select result from (select * from que where student_department=?) as q where ask =?');
        $sel->execute($dataa);
        //var_dump($sel->fetchall(PDO::FETCH_ASSOC));
        $ress=$sel->fetchall(PDO::FETCH_ASSOC);
        $datares=json_decode($ress[0]['result']);
        //var_dump($ress[0]['result']);
        if($ress[0]==NULL)
        exit('1');
        else if($ress[0]!=NULL && $ress[0]['result']=='[]')
            exit ('2');
        //var_dump($datares);
        //echo count($datares);
        echo '[';
        $datab = [$datares[0],$w];
        $final=$dbh->prepare('select * from inf where course_name=? and weeks=?');
        $final->execute($datab);
        $r1=$final->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($r1);
        for($i=1;$i<=count($datares);$i++){
            $datab = [$datares[$i],$w];
            $final=$dbh->prepare('select * from inf where course_name=? and weeks=?');
            $final->execute($datab);
            $r1=$final->fetchAll(PDO::FETCH_ASSOC);
            if($r1[0]==NULL)break;
            echo ',';
            echo json_encode($r1);
        }
        echo ']';
        //print_r($a);
    }
    catch(PDOException $e){
        die("Error!" . $e->getMessage() . '<br/>');
    }
}