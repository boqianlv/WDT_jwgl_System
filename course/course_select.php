<?php
require '../login/config.php';
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
if(isset($dat['chooseClassId']))
{
    selcourse($dat['userId'],$dat['chooseClassId']);
    //echo 111;
}
else if(isset($dat['cancelClassId']))
{
    Delcourse($dat['userId'],$dat['cancelClassId']);
    //echo 222;
}
else if(!isset($dat['chooseClassId'])&&!isset($dat['cancelClassId']))
{
    Getcourses($dat['userId']);
    //echo 333;
}
//else echo "???";

function selcourse($stu,$cou)
{
    try{
        global $dsn,$user,$pass;
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $tmp=$dbh->prepare("SELECT course_surplus_num,course_student_num,course_state from course where course_id=?");
        $b=$tmp->execute(array($cou));
        //echo $b;
        $ans=$tmp->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($ans);
        $Sel_StuNum=$ans[0]['course_surplus_num'];
        $StuSum=$ans[0]['course_student_num'];
        $status=$ans[0]['course_state'];
        if($Sel_StuNum < $StuSum )
        {
            if($status=='1') {   //1为可选，0为不可选也不可改
                $data = [
                    $stu, $cou
                ];
                $Seltmp = $dbh->prepare("INSERT INTO stu_course(student_id,course_id) VALUES (?,?)");
                $b1 = $Seltmp->execute($data);

                $upd_Sel_StuNum=$Sel_StuNum + 1;
                $upd = [$upd_Sel_StuNum, $cou];
                $updtmp = $dbh->prepare("UPDATE course SET course_surplus_num=? WHERE course_id=?");
                $b2 = $updtmp->execute($upd);
                if ($b1 && $b2) Getcourses($stu);
                else echo 'It seems that SQL do not work.' . '<br />';
            }
            else
            {
                exit('3');
            }
        }
        else
        {
            echo '2';
        }
    }
    catch(PDOException $e) {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}
function Getcourses($stuid)
{
    try{
        global $dsn,$user,$pass;
        $dbh = new PDO($dsn, $user, $pass);
        $set=$dbh->prepare("SET NAMES utf8");
        $set->execute();
        $tempAll=$dbh->prepare("SELECT * from course ");
        $tempAll->execute();
        $SelAll=$tempAll->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($SelAll);
        if(count($SelAll)==0)exit('1');
        $tmpSel=$dbh->prepare("SELECT course_id,course_name,course_week,teacher_name from course where course_id in (SELECT course_id from stu_course where student_id=?)");
        $tmpSel->execute(array($stuid));
        $Selsel=$tmpSel->fetchall(PDO::FETCH_ASSOC);
        //var_dump($Selsel);
        echo json_encode(array($SelAll,$Selsel));
    }catch (PDOException $e) {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}
function Delcourse($stuid,$delid)
{
    try {
        global $dsn, $user, $pass;
        $dbh = new PDO($dsn, $user, $pass);
        $set = $dbh->prepare("SET NAMES utf8");
        $set->execute();
        $DeltmpJudge=$dbh->prepare("select * from stu_course where student_id=? and course_id=?");
        $judge=$DeltmpJudge->execute(array($stuid,$delid));
        $j=$DeltmpJudge->fetchall(PDO::FETCH_ASSOC);
        if($judge&&count($j)!=0)
        {
            $Deltmp=$dbh->prepare("delete from stu_course where student_id=? and course_id=?");
            $Deltmp->execute(array($stuid,$delid));

            $getnum=$dbh->prepare("select course_surplus_num from course where course_id=?");
            $getnum->execute(array($delid));
            $a=$getnum->fetchAll(PDO::FETCH_ASSOC);

            $updTmp=$dbh->prepare("update course set course_surplus_num=? where course_id=?");
            $updTemp=$a[0]['course_surplus_num']-1;
            $updTmp->execute(array($updTemp,$delid));
        }
        Getcourses($stuid);
    }
    catch (PDOException $e) {
        die("Error!" . $e->getMessage() . '<br/>');
    }
}