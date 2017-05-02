<?php
require ('/usr/share/php5/aws.phar');
session_start();
include "nhinclude.php";
use Aws\S3\S3Client;

// Instantiate the client.
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$bucket = 'altorg';



function icmp($a,$b)
{
    return strcasecmp($a,$b);
}

function show_recs($nick,$rcs)
{
    if (is_array($rcs)) {
        usort($rcs, 'icmp');
	$nofollow = array('nofollow'=>1);
	for ($x = 0; $x < count($rcs); $x++) {
            $s3url = 'https://s3.amazonaws.com/altorg/'.$rcs[$x];
	    $url = 'trd/?file='.$s3url;
	    // $url = 'trd/?file='.plr_ttyrec_url($nick).$rcs[$x];
	    $lnk2 = preg_replace('/^ttyrec\/.*\//', '', $rcs[$x]);
	    $lnk = preg_replace('/\.ttyrec\.bz2$/', '', $lnk2);
	    // $lnk = preg_replace('/.ttyrec$/', '', $rcs[$x]);
	    $rcs[$x] = mk_url($url, $lnk, $nofollow).'&nbsp;('.mk_url($s3url,'dl', $nofollow).')';
		/*'&nbsp;('.mk_url('naotv.php?fname='.plr_ttyrec_url($nick).$rcs[$x],'tv').')';*/
	}
	print_tabled_array($rcs, 4);
    }
}

function input_form($nick="")
{
    echo '<form name="search" method="POST" action="'.phpself_querystr().'">';
    echo 'Player: '.mk_inputfield('text', 'player', $nick);
    echo mk_inputfield('submit', 'submit', 'Search');
    echo "</form>";
}

function player_ttyrec_listing($plr)
{
	global $s3;
	global $bucket;

	print '<H2>Player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).'</H2>';

	print '<P><B>Recent/Unarchived ttyrecs</B>';
	$ret = get_files_in_dir(plr_ttyrec_dir($plr));
	if ($ret) {
	show_recs($plr, $ret);
	} else print '<P>No unarchived ttyrecs.';
	
	print '<P><B>Archived in S3</B>';
	print '<P>';
	$s3list = array();

	$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => 'ttyrec/'.$plr.'/'));

	foreach ($iterator as $object) {
    	//	echo $object['Key'] . "\n";
		$s3list[] = $object['Key'];
	}

    if ($s3list) {
	show_recs($plr, $s3list);
    } else print '<P>No TTYRECs.';


}

nh_html_top('Browse TTYRECs', array('norobots'=>1));

if ($_POST['submit'] && isset($_POST['player'])) {
    $plr = trim($_POST['player']);
} else if (isset($_GET['player'])) {
    $plr = trim($_GET['player']);
} else if (isset($_SESSION['loggedin']) && !isset($plr)) {
    $plr = $_SESSION['username'];
}


print '<H1>Browse player ttyrecs</H1>';

$plr = show_with_valid_username($plr, 'player_ttyrec_listing', 'browsettyrec.php?player=%s');

print '<BR>&nbsp;<P>';
input_form($plr);

nh_html_bottom();
