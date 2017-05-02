<?php
session_start();
include 'nhinclude.php';

$plr = $_GET['player'];
if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $plr = $_SESSION['username'];
}

nh_html_top("All games by player, grouped by ending reason");

if (isset($plr)) {

    if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

	$games = parse_xlogfile(null, "select death,count(*) as cnt from xlogfile where name='".$plr."' group by death order by cnt desc");

	if (count($games)) {
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
            foreach ($games as $l) {
                $rank++;
		print tr_odd_even($rank);
		print '<TD align="right">'.$rank.'.</TD>';
		print '<TD align="right">'.number_format($l['cnt']).'</TD>';
		print '<TD>'.$l['death'].'</TD>';
		print '</TR>';
            }
	    print '</TBODY>';
	    print '</TABLE>';
 	} else print '<P>Player '.$plr.' has not played any games.';

    } else print '<P>That is not a valid player name.';

}

nh_html_bottom();
