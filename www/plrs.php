<?php
include 'nhinclude.php';

$whereisdir = '/opt/nethack/nethack.alt.org/nh343/var/whereis/';

$title = 'Currently active NetHack players';

$files = explode("\n", `ls -1 "$whereisdir"`);

array_pop($files);


/*depth=29:dnum=0:hp=134:maxhp=134:turns=46326:score=343234:role=Caveman:race=human:gender=Mal:align=lawful:conduct=0xf88:amulet=0*/

$plrdata = array();

foreach ($files as $f) {
    if (!preg_match('/\.whereis$/', $f)) next;
    $dat = file($whereisdir.$f);
    $log = parse_xlogfile_line($dat[0]);

    if (!$log['role']) next;

    $plrname = trim(preg_replace('/\.whereis$/', '',$f));
    if (preg_match('/^$/', $plrname)) next;

    $log['name'] = $plrname;

    $log['dungeon'] = sprintf("%02d02d", $log['dnum'], $log['depth']);

    array_push($plrdata, $log);
}

function arrayidx($needle, $haystack)
{
    $needle = strtolower($needle);
    for ($h = 0 ; $h < count($haystack) ; $h++) { if ($needle == strtolower($haystack[$h])) return $needle; }
    return strtolower($haystack[0]);
}


$fields = array('Name','Turns','Score', 'Role', 'Race','Gender','Align','HP','Conducts', 'Dungeon','');

if (isset($_GET['s'])) {
    $sort = trim($_GET['s']);
    $sort = arrayidx($sort, $fields);
} else $sort = strtolower($fields[0]);


nh_html_top($title);

print "<h1>$title</h1>";

print '<table>';

$nofollow = array('nofollow'=>1);
print '<tr>';
foreach ($fields as $f) { print '<th>'.mk_url('?s='.$f,$f,$nofollow).'</th>'; }
print '</tr>';

$odd = 0;


function cmpplrs($a, $b) {
    global $sort;
    $num = 1;

    if ($sort == "name" || $sort == 'race' || $sort == 'role' || $sort == 'gender' || $sort == 'align') $num = 0;

    if ($num == 1) return ($b[$sort] - $a[$sort]);
    return strcasecmp($a[$sort], $b[$sort]);
}

usort($plrdata, "cmpplrs");

foreach ($plrdata as $dat) {
    print tr_odd_even($odd++);
    print '<td>'.plr_url($dat['name']).'</td>';
    print '<td>'.$dat['turns'].'</td>';
    print '<td>'.number_format($dat['score']).'</td>';
    print '<td>'.$dat['role'].'</td>';
    print '<td>'.$dat['race'].'</td>';
    print '<td>'.$dat['gender'].'</td>';
    print '<td>'.$dat['align'].'</td>';
    print '<td>'.$dat['hp'].'('.$dat['maxhp'].')</td>';
    print '<td>'.decode_conduct(base_convert($dat['conduct'], 16, 10)).'</td>';
    print '<td>'.dungeonlevel($dat['dnum'], $dat['depth']).'</td>';
    print '<td>'.(intval($dat['amulet']) ? 'Has the Amulet' : '&nbsp;').'</td>';
    print '</tr>'."\n";

}

print '</table>';

nh_html_bottom();
