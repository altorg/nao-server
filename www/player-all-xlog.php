<?php
session_start();
include 'nhinclude.php';

$useraw = (isset($_GET['raw']) ? 0 : 1);

if ($useraw == 1) {
   nh_html_top("All games by player");
}


$plr = $_GET['player'];
if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $plr = $_SESSION['username'];
}

if (isset($plr)) {

    if ($useraw == 1) {
      print '<H1>All games for player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).'</H1>';
      print '<BR>&nbsp;';
    }
    if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

    $topdata = parse_xlogfile(null, "select * from xlogfile where name='".$plr."' order by endtime asc");

    if (count($topdata) >= 1) {
        if ($useraw == 1) print '<PRE>';
	foreach ($topdata as $line) {
	   $l = array();
	   foreach ($line as $k=>$v)
	   	   $l[] = $k.'='.$v;
	   print implode(':', $l)."\n";
	}
	if ($useraw == 1) print '</PRE>';
    } else print '<P>Player '.$plr.' has not played any games.';

    } else print '<P>That is not a valid player name.';

}

if ($useraw == 1) {
   nh_html_bottom();
}
