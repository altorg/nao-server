<?php
include 'nhinclude.php';

$toplimit = 2000;

$topdata = parse_xlogfile(null, "select * from xlogfile where points > 1000000 order by points desc, endtime asc limit ".$toplimit);

$headerdata = array('tablesort' => array('topscores'));

nh_html_top('NetHack top scores');

$settings = array(
		  'caption' => 'Top '.$toplimit.' scores',
		  'fields' => array(2, 5, 1, 11, 12, 8, 13, 10),
		  'max_limit' => $toplimit,
		  'table_id' => 'topscores'
		  );
print xlog_table($topdata, $settings);

nh_html_bottom($headerdata);

