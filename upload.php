<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
require_once('db.inc.php');
#$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
#EVE time is UTC, and that's what EVE Mon sends us. (strtotime doesn't need this, but it stops errors)
date_default_timezone_set('UTC');
$sql='insert into mysales (stationid,regionid,systemid,typeid,bid,price,minvolume,volremain,volenter,issued,duration,rang) values (:stationid,:regionid,:systemid,:typeid,:bid,:price,:minvolume,:volremain,:volenter,:issued,:duration,:rang)';
$histsql="insert ignore into orderhistory (typeid,regionid,historydate,orders,quantity,low,high,average) values (:typeid,:regionid,:date,:orders,:quantity,:low,:high,:average)";
$stmt = $dbh->prepare($sql);
#trigger_error(print_r($stmt, true));
$histstmt = $dbh->prepare($histsql);


$parsed=json_decode($_POST['data'],true);
#trigger_error(print_r($parsed, true));

if ($parsed['resultType']=='orders')
{
    $typeid=$parsed['rowsets'][0]['typeID'];
    $regionid=$parsed['rowsets'][0]['regionID'];
    foreach ($parsed['rowsets'][0]['rows'] as $order)
    {
			#Convert given date/time to unix epoch format
			#To go back to the way it was, use this:
			#	strftime("%Y-%m-%dT%H:%M:%S")
			$issued=strtotime($order[7]);
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
			$date=strtotime($order[0]);
            $histstmt->execute(array(':typeid'=>$typeid,':regionid'=>$regionid,':date'=>$date,':orders'=>$order[1],':quantity'=>$order[2],':low'=>$order[3],':high'=>$order[4],':average'=>$order[5]));
    }
}
?>1
