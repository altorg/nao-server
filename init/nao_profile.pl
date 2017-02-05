#!/usr/bin/perl 

$sourcefile = "/opt/etc/nao_profile.sh"; 
chomp(@newenv = qx ( . $sourcefile; env) );
foreach (@newenv) {
    ($k,$v) = split "=",$_,2;
    $ENV{$k}=$v;
} 
