#!/usr/bin/perl
$|++;

use DBI;
use DBD::mysql;
use File::Tail;
use strict;
do "XLogfile-360.pm";

my $xlogfile = "/opt/nethack/nethack.alt.org/nh360/var/xlogfile";
my $fh;
my $line;
my $xlogline;
my $oneline;
my $dbargs = {AutoCommit => 0, PrintError => 1};
my $dbh;
my $counter;

sub get_nbits {
    my $x = shift;
    return unpack '%32b*', pack 'I', $x;
}


sub insertxlogline
{
	$oneline = shift;
	$xlogline = parse_xlogline($oneline);
	return if ($xlogline->{name} eq "paxedtest" && $xlogline->{death} eq "ascended");
	$dbh->do("insert into xlogfile (version,points,deathdnum,deathlev,maxlvl,hp,maxhp,deaths,deathdate,birthdate,uid,"
		."role,race,gender,align,name,death,conduct,turns,achieve,realtime,starttime,endtime,gamedelta,gender0,align0,nconducts,nachieves,flags) values ("
		.$dbh->quote($xlogline->{version}).','
		.$dbh->quote($xlogline->{points}).','
		.$dbh->quote($xlogline->{deathdnum}).','
		.$dbh->quote($xlogline->{deathlev}).','
		.$dbh->quote($xlogline->{maxlvl}).','
		.$dbh->quote($xlogline->{hp}).','
		.$dbh->quote($xlogline->{maxhp}).','
		.$dbh->quote($xlogline->{deaths}).','
		.$dbh->quote($xlogline->{deathdate}).','
		.$dbh->quote($xlogline->{birthdate}).','
		.$dbh->quote($xlogline->{uid}).','
		.$dbh->quote($xlogline->{role}).','
		.$dbh->quote($xlogline->{race}).','
		.$dbh->quote($xlogline->{gender}).','
		.$dbh->quote($xlogline->{align}).','
		.$dbh->quote($xlogline->{name}).','
		.$dbh->quote($xlogline->{death}).','
		.$dbh->quote(hex($xlogline->{conduct})).','
		.$dbh->quote($xlogline->{turns}).','
		.$dbh->quote(hex($xlogline->{achieve})).','
		.$dbh->quote($xlogline->{realtime}).','
		.$dbh->quote($xlogline->{starttime}).','
		.$dbh->quote($xlogline->{endtime}).','
		.$dbh->quote($xlogline->{endtime} - $xlogline->{starttime}).','
		.$dbh->quote($xlogline->{gender0}).','
		.$dbh->quote($xlogline->{align0}).','
		.$dbh->quote(get_nbits(hex($xlogline->{conduct}))).','
		.$dbh->quote(get_nbits(hex($xlogline->{achieve}))).','
		.$dbh->quote(hex($xlogline->{flags})).')');

	if ($dbh->err()) { die "$DBI::errstr\n"; }
}

#first go through existing xlogfile
$dbh = DBI->connect("dbi:mysql:xlogfiledb:localhost","root","",$dbargs) or die("Unable to connect: $DBI::errstr\n");
#print "Deleting all rows from current xlogfile.db\n";
# $dbh->do("delete from xlogfile_360");
# sleep 5 seconds to give time for other script to delete rows from table before these get inserted
sleep(5);
open(INPUT, "<$xlogfile");
while(<INPUT>)
{
	$line = $_;
	insertxlogline($line);
	if(!(++$counter%10000)) { $dbh->commit; print "Inserted $counter entries.\n"; }
}
$dbh->commit();
$dbh->disconnect();

#then tail it for new entries
$fh=File::Tail->new($xlogfile);

print "Entries all imported, now tailing file...\n";
while (defined($line=$fh->read))
{
    $dbh = DBI->connect("dbi:mysql:xlogfiledb:localhost","root","",$dbargs) or die("Unable to connect: $DBI::errstr\n");
    insertxlogline($line);
    while (!$dbh->commit) { print "{retrying}"; sleep 5; }
    $dbh->disconnect();
    if(!(++$counter%100)) { print ".$counter\n"; } else { print "."; }
}
