<?php
session_start();
include 'nhinclude.php';

$plr = $_GET['player'];
if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $plr = $_SESSION['username'];
}

nh_html_top('All games by player, grouped by ending reason', array('norobots'=>1));

if (isset($plr)) {

    $games = array();

    if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

	function plrendreduce($dat, $line) {
	    global $games;
	    $r = simple_death_reason($dat['death']);
	    $games[$r] += $dat['cnt'];
	}

	parse_xlogfile('plrendreduce', "select death,count(*) as cnt from xlogfile where name='".$plr."' group by death");

	if (count($games)) {

	    arsort($games);
            print '<TABLE width="100%">';
	    print '<CAPTION>All games for player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).', grouped by ending reason</CAPTION>';
	    print '<THEAD>';
	    print '<TR>';
	    print '<TH align="right">Rank</TH>';
	    print '<TH align="right">Amount</TH>';
	    print '<TH>Death</TH>';
	    print '</TR>';
	    print '</THEAD>';
	    print '<TBODY>';
            foreach ($games as $key=>$val) {
                $rank++;
		print tr_odd_even($rank);
		print '<TD align="right">'.$rank.'.</TD>';
		print '<TD align="right">'.number_format($val).'</TD>';
		print '<TD>'.$key.'</TD>';
		print '</TR>';
            }
	    print '</TBODY>';
	    print '</TABLE>';
 	} else print '<P>Player '.$plr.' has not played any games.';

    } else print '<P>That is not a valid player name.';

}

nh_html_bottom();
