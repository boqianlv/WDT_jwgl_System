<?php
require '../login/config.php';
$raw = file_get_contents('php://input');
$dat = json_decode($raw,true);
if(!isset($dat['systemState']))
{
    try{
        global $dsn,$user,$pass;
        $dbh=new PDO($dsn,$user,$pass);
        $bol=$dbh->prepare("SELECT status from systemstatus");
        $ans=$bol->execute();
        $b=$bol->fetchAll(PDO::FETCH_ASSOC);
        echo $b[0]['status'];
    }
    catch (PDOException $e)
    {
        die("Error".$e->getMessage());
    }
}
else
{
    try{
        global $dsn,$user,$pass;
        $dbh=new PDO($dsn,$user,$pass);
        $boll=[$dat['systemState']];
        $bol=$dbh->prepare("update systemstatus set status=?");
        $ans=$bol->execute($boll);
        $bol->fetchAll(PDO::FETCH_ASSOC);
        echo $ans;
    }
    catch (PDOException $e)
    {
        die("Error".$e->getMessage());
    }
}