<?php
include "svgpie.php";
include "nhinclude.php";

if (isset($_GET['player'])) {
    $plr = trim($_GET['player']);
} else if (isset($_GET['plr'])) {
    $plr = trim($_GET['plr']);
}

if (isset($plr) && !preg_match("/^[0-9a-zA-Z]+$/", $plr)) die();

if (isset($_GET['pie'])) {
    $pie = trim($_GET['pie']);
}

function piemangle1($plr=NULL, $field)
{
    global $nh_xlogfile_db;
    /*$db = new PDO("sqlite:".$nh_xlogfile_db);*/
    $db = new PDO("mysql:host=localhost;dbname=xlogfiledb","rodney","Y3nd0r");
    if ($plr) {
	$sql = "select ".$field.",count(*) from xlogfile where name=\"".$plr."\" group by ".$field;
    } else {
	$sql = "select ".$field.",count(*) from xlogfile group by ".$field;
    }
    $stmt = $db->query($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $data = $stmt->fetchAll();
    $dat = array();
    foreach ($data as $key=>$val) {
	$dat[$val[$field]] = $val['count(*)'];
    }
    $db = null;
    return $dat;
}

header('Content-Type: image/svg+xml');

switch ($pie) {
default:
case 0:
    $dat = array('pie' => 1, 'no pie' => 100);
    break;
case 1:
    $dat = piemangle1($plr, "role");
    break;
case 2:
    $dat = piemangle1($plr, "race");
    break;
case 3:
    $dat = piemangle1($plr, "gender");
    break;
case 4:
    $dat = piemangle1($plr, "align");
    break;
}

//print '<pre>'.$sql."\n"; print_r($dat);

print piechart($dat);
