<?php

include 'nhinclude.php';

goto_cronjob_script();

$headerdata = array('tablesort' => array('zscore'));

nh_html_top('Player Z-scores (3.6.0)', $headerdata);

print "


<H1>Players and their Z-scores</H1>
<P>
<P>This list is compiled by looking at each player's ascensions. Your Z Score for a role is the number of times you've ascended that role, with a twist. The first ascension in a role is worth 1 point, the next 1/2 points, the next 1/3 points, and so on. This means it's worthwhile to spread your ascensions out evenly (since your eighteenth wizard ascension is worth .0556 points while your first caveman is worth 1 full point).</P>
<P>In other words, this is meant to be an alternative to the &quot;<A HREF='topallclassplayers.html'>best all around players</A>&quot; which is (in some eyes) flawed because it considers score, and score is not a very good estimator of ability.</P>
<P>
";

print table_caption(NULL, 'zscore');
$heads = array('Rank', 'Player Name', 'Total Z-score');

$heads = array_merge($heads, array_values($nh_role));

print table_head($heads);

function mostascfunc($dat, $line)
{
    global $player, $ascention;
    $player{$dat['name']} += $dat['cnt'];
    $ascention{$dat['name']}{$dat['role']} += $dat['cnt'];
}

parse_xlogfile('mostascfunc', "select name,role,count(*) as cnt from xlogfile where death='ascended' and version='3.6.0' group by name,role");


foreach ($player as $key => $val) {

	$total_zscore{$key} = 0;
	foreach ($nh_role as $rkey => $rval) {
		if ($ascention{$key}{$rval} > $hiasc{$rval}) {
		   $hiasc{$rval} = $ascention{$key}{$rval};
		}

		$tmp = 0.0;
		if ($ascention{$key}{$rval} > 0) {
		    $tmp = 1.0;
		    if ($ascention{$key}{$rval} > 1) {
			for ($i = 2; $i <= $ascention{$key}{$rval}; $i++) {
			    $tmp += (1.0 / $i);
			}
		    }
		}

		$zscore{$key}{$rval} = $tmp;
		$total_zscore{$key} += $tmp;
	}
}

arsort($total_zscore);

$ranking = ranking_array($total_zscore);

print '<TBODY>';

foreach ($total_zscore as $key => $val) {
	print tr_odd_even($odd++);
	print '<TD>'.ordnum($ranking{$key}).'</TD>';
	print '<TD>'.mk_url('plr.php?player='.$key, $key, array('nofollow' => 1)).'</TD>';
	print '<TD><SPAN title="'.$player{$key}.'">'.sprintf("%.6f", $val).'</SPAN></TD>';
	foreach ($nh_role as $rkey => $rval) {
		if ($ascention{$key}{$rval} > 0) {
		   if ($ascention{$key}{$rval} == $hiasc{$rval}) {
		       print '<TD class="mostasc">';
		   } else {
		      print '<TD>';
		   }
		   print '<SPAN title="'.$ascention{$key}{$rval}.'x'.$rval.'">';
		   print sprintf("%.3f", $zscore{$key}{$rval});
		   print '</SPAN>';
		   print '</TD>';
		} else {
		   print '<TD>&nbsp;</TD>';
		}
	}
	print '</TR>';

	print "\n";
}

tablefooter();

print '<P><SMALL>Thanks to <A href="http://sartak.org/">Sartak</A> for the idea.</SMALL>';

nh_html_bottom($headerdata);
