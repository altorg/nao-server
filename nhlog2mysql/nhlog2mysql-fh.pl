#!/usr/bin/perl
$|++;

use DBI;
use DBD::mysql;
use File::Tail;
use strict;
do "/opt/nhlog2mysql/XLogfile-fh.pm";

my $xlogfile = "/opt/nethack/hardfought.org/fiqhackdir/data/xlogfile";
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
	$xlogline->{dumplog} =~ s/_/:/g;
	return if ($xlogline->{name} eq "paxedtest" && $xlogline->{death} eq "ascended");
	$dbh->do("insert into xlogfile (version,points,deathdnum,deathlev,maxlvl,hp,maxhp,deaths,deathdate,birthdate,"
		."uid,role,race,gender,align,name,death,dumplog,conduct,turns,starttime,endtime,gender0,align0) values ("
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
		.$dbh->quote($xlogline->{dumplog}).','
		.$dbh->quote($xlogline->{conduct}).','
		.$dbh->quote($xlogline->{turns}).','
		.$dbh->quote($xlogline->{starttime}).','
		.$dbh->quote($xlogline->{endtime}).','
		.$dbh->quote($xlogline->{gender0}).','
		.$dbh->quote($xlogline->{align0}).')');

	if ($dbh->err()) { die "$DBI::errstr\n"; }
}

#first go through existing xlogfile
$dbh = DBI->connect("dbi:mysql:xlogfile_fh:localhost","nhdb","123456",$dbargs) or die("Unable to connect: $DBI::errstr\n");
print "Deleting all rows from current xlogfile_fh.db\n";
$dbh->do("delete from xlogfile");
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
$fh=File::Tail->new(name=>$xlogfile,reset_tail=>0);

print "Entries all imported, now tailing file...\n";
while (defined($line=$fh->read))
{
    $dbh = DBI->connect("dbi:mysql:xlogfile_fh:localhost","nhdb","123456",$dbargs) or die("Unable to connect: $DBI::errstr\n");
    insertxlogline($line);
    while (!$dbh->commit) { print "{retrying}"; sleep 5; }
    $dbh->disconnect();
    if(!(++$counter%100)) { print ".$counter\n"; } else { print "."; }
}
