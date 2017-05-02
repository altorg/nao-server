<?php
session_start();
include 'nhinclude.php';
requires_login();
nh_html_top('All games by player', array('norobots'=>1));

$plr = $_SESSION['username'];

if (isset($plr)) {

    print '<H1>All games for player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).', raw logfile format</H1>';
    print '<BR>&nbsp;';

    if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

	$topdata = parse_xlogfile(null, "select * from xlogfile where name = '".$plr."'");
	print '<PRE>';

	function join_func(&$a, $b) { return $a = $b.'='.$a; }

	foreach ($topdata as $line) {
	    array_walk($line, 'join_func');
	    print implode($line, ':');
	    print "\n";
	}
	print '</PRE>';

    } else print '<P>That is not a valid player name.';

}

nh_html_bottom();
