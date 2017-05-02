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
            // $url = 'trd/?file='.plr_ttyrec_url($nick).$rcs[$x];
            $desc = preg_replace('/^dumplog\/.*\//', '', $rcs[$x]);
            // $lnk = preg_replace('/\.ttyrec\.bz2$/', '', $lnk2);
            // $lnk = preg_replace('/.ttyrec$/', '', $rcs[$x]);
            $rcs[$x] = mk_url($s3url,$desc, $nofollow);
                /*'&nbsp;('.mk_url('naotv.php?fname='.plr_ttyrec_url($nick).$rcs[$x],'tv').')';*/
        }
        print_tabled_array($rcs, 4);
    }
}

function input_form($nick="")
{
    echo '<form name="search" method="POST" action="'.phpself_querystr().'">';
    echo mk_inputfield('text', 'player', $nick);
    echo mk_inputfield('submit', 'submit', 'Search');
    echo '</form>';
}



function player_dumpfile_listing($plr)
{
    global $s3;
    global $bucket;

    print '<H2>Player '.mk_url('plr.php?player='.$plr, $plr, array('nofollow'=>1)).'</H2>';
    print '<P>';

    $s3list = array();

    $iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => 'dumplog/'.$plr.'/'));

    foreach ($iterator as $object) {
    //      echo $object['Key'] . "\n";
            $s3list[] = $object['Key'];
    }

    if ($s3list) {
        show_recs($plr, $s3list);
    } else print '<P>No Dumplogs found in S3.';

}


html_top(array('title'=>$nh_title_servername." - Find NetHack game dumps",
	       'focus'=>'search.player',
	       'norobots'=>1));

if ($_POST['submit'] && isset($_POST['player'])) {
    $nick = trim($_POST['player']);
} else if (isset($_GET['player'])) {
    $nick = trim($_GET['player']);
} else if (isset($_SESSION['loggedin']) && !isset($plr)) {
  $nick = $_SESSION['username'];
}


print '<H1>Search NetHack game dump logs by Nick</H1>';

$nick = show_with_valid_username($nick, 'player_dumpfile_listing', 'dumplogs.php?player=%s');

print '<P>&nbsp;';
input_form($nick);

print '<BR>&nbsp;<P>'.current_players_links();

html_bottom();
