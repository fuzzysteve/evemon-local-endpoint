<?php
require_once('db.inc.php');
#$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql='insert into evesupport.mysales (stationid,regionid,systemid,typeid,bid,price,minvolume,volremain,volenter,issued,duration,rang) values (:stationid,:regionid,:systemid,:typeid,:bid,:price,:minvolume,:volremain,:volenter,str_to_date(:issued,"%Y-%m-%dT%H:%i:%s"),:duration,:rang) on duplicate key update volremain=:volremain,issued=str_to_date(:issued,"%Y-%m-%dT%H:%i:%s")';
$histsql="insert ignore into evesupport.orderhistory (typeid,regionid,historydate,orders,quantity,low,high,average) values (:typeid,:regionid,:date,:orders,:quantity,:low,:high,:average)";
$stmt = $dbh->prepare($sql);
$histstmt = $dbh->prepare($histsql);


$parsed=json_decode($_POST['data'],true);

if ($parsed['resultType']=='orders')
{
    $typeid=$parsed['rowsets'][0]['typeID'];
    $regionid=$parsed['rowsets'][0]['regionID'];
    foreach ($parsed['rowsets'][0]['rows'] as $order)
    {
            list($issued,$temp)=explode("+",$order[7]);
            $bid=0;
            if($order[6]){
                $bid=1;
            }
            $stmt->execute(array(':regionid'=>$regionid,':typeid'=>$typeid,':minvolume'=>$order[5],':stationid'=>$order[9],':volenter'=>$order[4],':rang'=>$order[2],':issued'=>$issued,':price'=>$order[0],':duration'=>$order[8],':bid'=>$bid,':volremain'=>$order[1],':systemid'=>$order[10]));
    }
}
else
{
    $typeid=$parsed['rowsets'][0]['typeID'];
    $regionid=$parsed['rowsets'][0]['regionID'];
    foreach ($parsed['rowsets'][0]['rows'] as $order)
    {
            list($date,$temp)=explode("T",$order[0]);
            $histstmt->execute(array(':typeid'=>$typeid,':regionid'=>$regionid,':date'=>$date,':orders'=>$order[1],':quantity'=>$order[2],':low'=>$order[3],':high'=>$order[4],':average'=>$order[5]));
    }
}
?>1
