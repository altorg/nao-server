<?php
session_start();
include 'nhinclude.php';


$plr = $_GET['player'];
$style = $_GET['style'];
$sort = $_GET['sort'];

if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $plr = $_SESSION['username'];
}

$pagesize = 100;
$npage = $_GET['n'];
if (!isset($npage) || ($npage < 0)) $npage = 0;

$paging = " limit ".$pagesize." offset ".($npage*$pagesize);

switch ($style) {
default:
case 0: $fields = array(2,5,14,7,6,11,12,8,13,10); $style=0; break;
case 1: $fields = array(2,5,14,8,4,10); break;
}

switch ($sort) {
default:
case 0: $sorts = ''; break;
case 1: $sorts = ' order by points desc'; break;
case 2: $sorts = ' order by death asc'; break;
case 3: $sorts = ' order by realtime desc'; break;
case 4: $sorts = ' order by turns desc'; break;
}

$headerdata = array('norobots' => 1,
		    'tablesort' => array('plrall')
		    );

nh_html_top('All games by player', $headerdata);

if (isset($plr)) {


    if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

      $nentries = n_xlogfile_entries("select count(1) from xlogfile where name='".$plr."'");

      if ($nentries < 1) {
          print '<P>Player '.$plr.' has not played any games.';
      } else {

        if ($nentries <= $pagesize) {
          $paging = "";
        } else {
	  paging_controls($npage, $pagesize, $nentries);
	}

        $data = parse_xlogfile(null, "select * from xlogfile where name='".$plr."'".$sorts.$paging);
	$nofollow = array('nofollow'=>1);
        if (count($data) > 0) {

          print '<div style="width:100%;text-align:right;">';
	  print 'See also '.mk_url('?player='.$plr.'&style='.($style==0?'1':'0'), 'alternative view', $nofollow).'.';
	  print '<br>';
	  print 'Sort by: ';
	  parse_str($_SERVER['QUERY_STRING'], $query);
	  unset($query['sort']);
	  print mk_url(phpself_querystr($query), 'Date', $nofollow).' ';
	  $query['sort'] = 1;
	  print mk_url(phpself_querystr($query), 'Score', $nofollow).' ';
	  $query['sort'] = 2;
	  print mk_url(phpself_querystr($query), 'Death', $nofollow).' ';
	  $query['sort'] = 3;
	  print mk_url(phpself_querystr($query), 'Time', $nofollow).' ';
	  $query['sort'] = 4;
	  print mk_url(phpself_querystr($query), 'Turns', $nofollow);
          print '</div>';

          print '<div class="fullwide">';
	  $settings = array(
			    'caption' => 'All games for player '.mk_url('plr.php?player='.$plr, $plr, $nofollow),
			    'fields' => $fields,
			    'rank' => ($npage*$pagesize),
			    'table_id' => 'plrall'
			    );
	  print xlog_table($data, $settings);
 	  print '</div>';

	  if ($nentries > $pagesize) {
	    paging_controls($npage, $pagesize, $nentries);
	  }

        }

      }

    } else print '<P>That is not a valid player name.';

}

nh_html_bottom($headerdata);
