#!/usr/bin/perl 

$sourcefile = "/etc/unsafe_profile.sh"; # or whatever
chomp(@newenv = qx ( . $sourcefile; env) );
foreach (@newenv) {
    ($k,$v) = split "=",$_,2;
    $ENV{$k}=$v;
} 
