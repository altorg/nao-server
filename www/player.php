<?php
session_start();
include 'nhinclude.php';

$plr = $_GET['player'];
if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $plr = $_SESSION['username'];
}

$scores = array();

function playerfunc($dat, $line)
{
    global $plr, $scores;
    if (!strcasecmp($dat['Name'],$plr)) {
	array_push($scores, $dat);
    }
}


nh_html_top('NetHack top scores by player', array('norobots'=>1));

if (isset($plr)) {

    parse_file('playerfunc');

    if (count($scores) > 0) {
	print mk_prefs_link();
 	print '<TABLE width="100%">';
	print '<CAPTION>Top scores for player '.$plr.'</CAPTION>';
 	tableheader(1);
 	foreach ($scores as $dat) {
	    $rank++;
	    tablerow(1, $dat, $rank);
 	}
	tablefooter();

    } else print '<P>Player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).' does not have any scores in the top 2000.';

}

nh_html_bottom();
