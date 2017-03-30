#!/usr/bin/perl
#
# This script reads the NetHack logfile, and outputs all the entries in scorefile order.
# Used to reconstruct the record-file from logfile, if record-file is corrupted.
#
# Run as: cat logfile | ../../scripts/makerecord2.pl | head -n 2000 | tee record
#
#

while(<STDIN>) {
	$line = $_;
	chomp($line);
	$line =~ /(\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+) (\S+),(\S+)/;
	$linearray[++$num] = $line;
	$namearray[$num] = $16;
	$typearray[$num] = $12;
	$score[$num] = $2 . ' ' . $num;
}

@sorted = sort {$a <=> $b} @score;

while($top = pop sorted) {
	$top =~ /(\S+) (\S+)/;
	$v = $1;
	$k = $2;
	$used{$namearray[$k].$typearray[$k]}++;	
	if (($used{$namearray[$k].$typearray[$k]} <= 10) && ($v > 0)) {
		print $linearray[$k] . "\n";
	}
}
