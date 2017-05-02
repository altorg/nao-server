<?php
session_start();
include 'nhinclude.php';
requires_login();

$basedir = '/tmp/getplrdata/';

$plr = $_SESSION['username'];

if (!isset($plr)) exit;

if (!preg_match('/^[0-9a-zA-Z]+$/', $plr)) exit;

if (!file_exists($basedir)) {
    if (!mkdir($basedir)) {
	print "Error creating temp dir.";
	exit;
    }
}

if (!chdir(plr_userdata_dir($plr).'/../')) exit;


function create_xlogfile($basedir, $plr)
{
    $topdata = parse_xlogfile(null, "select * from xlogfile where name = '".$plr."'");

    function join_func(&$a, $b) { return $a = $b.'='.$a; }

    if (!mkdir($basedir . $plr)) {
	print "Error creating temp dir 2.";
	exit;
    }

    $fp = fopen($basedir . $plr . '/xlogfile', 'w');

    foreach ($topdata as $line) {
	array_walk($line, 'join_func');
	fwrite($fp, implode($line, ':') . "\n");
    }
    fclose($fp);
}

set_time_limit(0);

create_xlogfile($basedir, $plr);


$fname = $basedir . 'nao-' . $plr . '-' . date('Ymd');

exec('/bin/tar cf '.$fname.'.tar '.$plr.'/*');
chdir($basedir);
exec('/bin/tar --append --file='.$fname.'.tar '.$plr.'/xlogfile');
exec('/bin/gzip '.$fname.'.tar');

downloadfile($fname.'.tar.gz');
@unlink($fname.'.tar.gz');


@unlink($basedir . $plr . '/xlogfile');
@rmdir($basedir . $plr);
