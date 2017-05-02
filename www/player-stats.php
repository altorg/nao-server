<?php
session_start();
include 'nhinclude.php';
nh_html_top('Statistics of all games by player', array('norobots'=>1));

$plr = $_GET['player'];
if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $plr = $_SESSION['username'];
}

$ascs = 0;
$roles = array();
$races = array();
$genders = array();
$aligns = array();

$lines = 0;
$asc_roles = array();
$asc_races = array();
$asc_genders = array();
$asc_aligns = array();

if (isset($plr)) {

    if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

	function plrstatsfunc($dat, $line) {
	    global $lines, $roles, $races, $genders, $aligns;
	    global $ascs, $asc_roles, $asc_races, $asc_genders, $asc_aligns;
	    $lines++;
	    $roles[$dat['role']]++;
	    $races[$dat['race']]++;
	    $genders[$dat['gender']]++;
	    $aligns[$dat['align']]++;
	    if (preg_match('/^ascended$/', $dat['death'])) {
		$ascs++;
		$asc_roles[$dat['role']]++;
		$asc_races[$dat['race']]++;
		$asc_genders[$dat['gender']]++;
		$asc_aligns[$dat['align']]++;
	    }
	}

	parse_xlogfile('plrstatsfunc', "select * from xlogfile where name='".$plr."'");

	if ($lines > 0) {

	    print '<p>';
	    print '<table width="100%">'."\n";
	    print '<CAPTION>Statistics for all games for player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).'</CAPTION>'."\n";
	    print '<tr>'."\n";
	    print '<td width="50%" valign=top>'."\n";

	    if ($ascs) {
	       $ascper = round(($ascs * (100/$lines)),2);
	       if ($ascs < 2) {
	         print '<p><b>1 ascension: ';
		 print current(array_keys($asc_roles));
		 print '-'.current(array_keys($asc_races));
		 print '-'.current(array_keys($asc_genders));
		 print '-'.current(array_keys($asc_aligns));
		 print '</b> ('.$ascper.'% of all games by '.$plr.')'."\n";
	         print '<p>';
	       } else {
		 $al1 = '<b>Ascensions:</b> '.$ascs.' ('.$ascper.'% of all games by '.$plr.')';
		 $al2 = bargraph('Ascended roles', $asc_roles, $ascs);
		 $al3 = bargraph('Ascended races', $asc_races, $ascs);
		 $al4 = bargraph('Ascended genders', $asc_genders, $ascs);
		 $al5 = bargraph('Ascended alignments', $asc_aligns, $ascs);
	       }
	    } else {
	       print '<p><b>No ascensions.</b>';
	       print '<p>';
	    }

	    if ($al1) {
	       print $al1;
	       print '</td><td valign=top>';
	    }
	    print '<b>Games played:</b> '. $lines.'</td>';
	    print '</tr><tr><td valign=top>';
	    if ($al2) {
	       print $al2;
	       print '</td><td valign=top>';
	    }
	    $nofollow = array('nofollow'=>1);
	    print bargraph('Roles ('.mk_url('bake.php?plr='.$plr.'&pie=1', 'svg pie', $nofollow).')', $roles, $lines).'</td>';
	    print '</tr><tr><td valign=top>';
	    if ($al3) {
	       print $al3;
	       print '</td><td valign=top>';
	    }
	    print bargraph('Races ('.mk_url('bake.php?plr='.$plr.'&pie=2', 'svg pie', $nofollow).')', $races, $lines).'</td>';
	    print '</tr><tr><td valign=top>';
	    if ($al4) {
	       print $al4;
	       print '</td><td valign=top>';
	    }
	    print bargraph('Genders ('.mk_url('bake.php?plr='.$plr.'&pie=3', 'svg pie', $nofollow).')', $genders, $lines).'</td>';
	    print '</tr><tr><td valign=top>';
	    if ($al5) {
	       print $al5;
	       print '</td><td valign=top>';
	    }
	    print bargraph('Alignments ('.mk_url('bake.php?plr='.$plr.'&pie=4', 'svg pie', $nofollow).')', $aligns, $lines).'</td>';

	    print '</tr>';
	    print '</table>';

 	} else print '<P>Player '.$plr.' has not played any games.';

    } else print '<P>That is not a valid player name.';

}

nh_html_bottom();
