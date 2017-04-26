<?php
session_start();
include 'nhinclude.php';
html_top(array('title'=>$nh_title_servername." - Player Info",
	       'focus'=>'search.player',
	       'norobots'=>1));

if ($_POST['submit'] && isset($_POST['player'])) {
    $plr = trim($_POST['player']);
    if (($plr == "") && $_SESSION['loggedin']) {
      $plr = $_SESSION['username'];
    }
} else if (isset($_GET['player'])) {
    $plr = trim($_GET['player']);
} else if ($_SESSION['loggedin']) {
    $plr = $_SESSION['username'];
}

print '<H1>Player Info</H1>';

function player_home($plr)
{
    print '<H2>Player '.$plr.'</H2>';

    print '<UL>';
    if ($_SESSION['loggedin'] && ($plr == $_SESSION['username'])) {
        print "<LI><b>This is you.</b> You are logged in to the alt.org website. <a href='logout.php'>Log out here</a>";
    }

    $online = plr_is_online($plr);
    if ($online) {
        print '<LI>'.whereis_pretty($plr);
	/*print '<LI class="plronline">is currently playing.';*/
    } else {
	print '<LI class="plroffline">is not playing.';
    }

    if ($_SESSION['loggedin'] && ($plr == $_SESSION['username'])) {
        print "<LI><span class='new'><a href='getplrdata.php'>Download your files</a> (config file, game dumps and ttyrecs)</span>";
    }

    $savefilemtime = plr_savefile($plr);
    if ($savefilemtime) {
       print '<LI>has a save file, dated '.date('D, d M Y, H:i:s', $savefilemtime);
    }
    print '</UL>';


    print '<UL>';

    print '<LI>'.mk_url(plr_rcfile_url($plr), 'Config file', array('nofollow'=>1));
    if ($_SESSION['loggedin'] && ($plr == $_SESSION['username'])) {
       print ' - '.mk_url('webconf/', 'Edit your config file', array('nofollow'=>1));
       $plrparam = '';
    } else {
       $plrparam = '?player='.substr($plr,0,20);
    }

    print '<LI>'.mk_url('player-stats.php'.$plrparam, 'Game statistics', array('nofollow'=>1));
    print '<LI>'.mk_url('player.php'.$plrparam, 'Scores in hiscore list', array('nofollow'=>1));
    print '<LI>'.mk_url('player-all.php'.$plrparam, 'All games', array('nofollow'=>1));
    /*print ' - ' .mk_url('player-top.php'.$plrparam, 'Sorted by score', array('nofollow'=>1));*/
    /*print ' - ' .mk_url('player-all-sort.php'.$plrparam, 'Sorted by end reason', array('nofollow'=>1));*/
    print ' - ' .mk_url('player-all-raw.php'.$plrparam, 'In raw logfile format', array('nofollow'=>1));
    print ' - ' .mk_url('player-all-xlog.php'.$plrparam, 'Raw Xlogfile format', array('nofollow'=>1));
    /*print ' - ' .mk_url('player-all-xlog.php'.$plrparam, 'All games from extended logfile', array('nofollow'=>1));*/
    print '<LI>'.mk_url('player-endings.php'.$plrparam, 'Grouped ending reasons', array('nofollow'=>1));
    print ' - '. mk_url('player-endings-reduced.php'.$plrparam, 'Reduced version', array('nofollow'=>1));
    print '<LI>Game dump logs: '.mk_url('dumplogs.php'.$plrparam, 'Browse', array('nofollow'=>1));
    // print ' - '.mk_url(plr_dumplog_url($plr), 'Directory listing', array('nofollow'=>1));
    print '<LI>TTYRECs: '.mk_url('browsettyrec.php'.$plrparam, 'Browse', array('nofollow'=>1));
    print ' -  '.mk_url(plr_ttyrec_url($plr), 'Directory listing', array('nofollow'=>1));
    print '</UL>';
/*
    if (!file_exists(plr_userdata_dir($plr)))
	print '<P>This player has not logged in since The Big Hard Drive Crash of 2008-03-19.';

    print '<P>You can also <a href="getoldttyrec.php?player='.$plr.'">download TTYRECs from before The Crash</a>.';
    print ' Note that only about 2/3 of the TTYRECs were salvageable.';
*/
}

$plr = show_with_valid_username($plr, 'player_home');


print '<BR>&nbsp;<P>';
input_form($plr);

print '<BR>&nbsp;<P>'.current_players_links();

nh_html_bottom();

function input_form($nick="")
{
    echo '<form name="search" method="POST" action="'.phpself_querystr().'">';
    echo mk_inputfield('text', 'player', $nick);
    echo mk_inputfield('submit', 'submit', 'Search');
    echo "</form>";
}

