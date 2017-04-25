<?php
include realpath(dirname(__FILE__) . '/html.php');

// Used on page titles and headings
$nh_title_servername = 'alt.org';

if ( isset($_SERVER['HTTPS'] ) || (getenv('NAO_SCRIPT_CRONJOB_HTTPS') == '1')) {
        $NAO_URL_ROOT = 'https://alt.org/nethack';
} else {
        $NAO_URL_ROOT = 'http://alt.org/nethack';
}

$NAO_URL_ROOT_REGEX = '/^https?:\/\/alt\.org\/nethack\//';


$nh_server_fqdn = 'alt.org';
$nh_server_nickname = 'NAO';

if (!isset($nh_curr_version)) {
  $nh_curr_version = 'nh360';
}

$nh_dgl_sqlite_db = '/opt/nethack/nethack.alt.org/dgldir/dgamelaunch.db';

$nh_livelog = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/livelog';

$nh_UID = '5';  // UID NetHack runs as on the server.
$nh_playground_var_dir = '/opt/nethack/nethack.dtype.org/var/';

$nh_savedir = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/save/';

$nh_xlogfile = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/xlogfile';
$nh_xlogfile_db = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/xlogfile.db';


$nh_logfile = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/logfile';
$nh_scorefile = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/record';
$nh_inprogress_dir = '/opt/nethack/nethack.alt.org/dgldir/inprogress-'.$nh_curr_version;

$nh_dgl_loginfile = '/opt/nethack/nethack.dtype.org/dgl-loginNONEXISTENT';

$nh_rcfile_default = '/opt/nethack/nethack.alt.org/dgl-default-rcfile.'.$nh_curr_version;
$nh_rcfile_dir = '/opt/nethack/nethack.alt.org/dgldir/userdata';
$nh_rcfile_url = $NAO_URL_ROOT.'/userdata/';

$nh_dumplog_dir = '/opt/nethack/nethack.alt.org/dgldir/userdata';
$nh_dumplog_url = 'https://s3.amazonaws.com/altorg/dumplog/';

$nh_chardump_dir = '/opt/nethack/nethack.dtype.org/chardumps/chardump';
$nh_chardump_url = $NAO_URL_ROOT.'/chardump/';

$nh_ttyrec_dir = '/opt/nethack/nethack.alt.org/dgldir/userdata';
$nh_ttyrec_url = $NAO_URL_ROOT.'/userdata/';

$nh_whereis_dir = '/opt/nethack/nethack.alt.org/'.$nh_curr_version.'/var/whereis';

#$nh_archived_ttyrec_dir = '/mnt/offline3/nethack/ttyrec';
#$nh_archived_ttyrec_url = $NAO_URL_ROOT.'/oldttyrec/';

// Max. # of games mostrecent.php shows
$nh_mostrecent_games = 1000;

// Min. limits for perplayer.php
$nh_perplayer_games = 10;
$nh_perplayer_score = 1000000;

// Variables for topallclassplayers.php
$nh_topallclassplayers_base = 1.2;
$nh_topallclassplayers_sub = 15;
$nh_topallclassplayers_pow = 0.4;
$nh_topallclassplayers_asc1 = 800; // bonus for 1. ascension
$nh_topallclassplayers_ascn = 400; // bonus for other ascensions

// Cache file template. '%s' gets replaced with 'logfile' or 'record'
$nh_cachefile = "/tmp/nao-%s.cache";

$nh_race = array('Dwa', 'Elf', 'Gno', 'Hum', 'Orc');
//$nh_gender = array("Fem", "Mal", "Neu");
$nh_gender = array('Fem', 'Mal');
$nh_align = array('Law', 'Neu', 'Cha');
$nh_role = array('Arc', 'Bar', 'Cav', 'Hea', 'Kni', 'Mon', 'Pri', 'Rog', 'Ran', 'Sam', 'Tou', 'Val', 'Wiz');

// nethack versions that have entries in logfile and record
$nh_version = array('3.3.1', '3.4.0', '3.4.1', '3.4.2', '3.4.3','3.6.0');

// The year when the server was first online (or the year of the oldest entry in logfile/record)
$nh_server_startyear = 2001;

$month_names = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

// Is array key returned by parse_logfile_line() numeric or not?
$nh_datsorttype = array(
		'Version' => 0,
		   'Score' => 1,
		   'Name' => 0,
		   'Dnum' => 1,
		   'Dlvl' => 1,
		   'MaxDlvl' => 1,
		   'HP' => 1,
		   'MaxHP' => 1,
		   'Deaths' => 1,
		//   'UID' => 1,
		   'Role' => 0,
		   'Race' => 0,
		   'Gender' => 0,
		   'Align' => 0,
		   'Reason' => 0,
		   'StartDate' => 1,
		   'EndDate' => 1
);

$NAO_helplesses = array(
		    'being frightened to death',
		    'being scared by rattling',
		    'being scared stiff',
		    'being terrified of a demon',
		    'digesting something',
		    'disrobing',
		    'dragging an iron ball',
		    'dressing up',
		    'fainted from lack of food',
		    'frozen by a monster',
		    'frozen by a monster\'s gaze',
		    'frozen by a potion',
		    'frozen by a trap',
		    'fumbling',
		    'gazing into a crystal ball',
		    'gazing into a mirror',
		    'getting stoned',
		    'hiding from thunderstorm',
		    'jumping around',
		    'moving through the air',
		    'opening a container',
		    'paralyzed by a monster',
		    'praying',
		    'pretending to be a pile of gold',
		    'reading a book',
		    'ringing a bell',
		    'sleeping',
		    'sleeping off a magical draught',
		    'stuck in a spider web',
		    'taking off clothes',
		    'trying to turn the monsters',
		    'unconscious from rotten food',
		    'vomiting'
		    );


$regexp_replace_http = array('from' => '/((https?|ftp):\/\/([\w\d\-]+)(\.[\w\d\-]+){1,})([\/\?\w\d\.,=&+%~_\-#;:@!\|]+)?/',
			     'to' => array(
					   'full'  => '<A rel="nofollow" href="\1\5">\1\5</A>',
					   'short' => '[<A rel="nofollow" href="\\1\\5">\\1</A>]'
					   )
			     );


function enable_debug()
{
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
}

function simple_redirect($url=NULL)
{
    global $NAO_URL_ROOT;
    header('Location: '.($url ? $url : $NAO_URL_ROOT));
    exit;
}

function downloadfile($file)
{
    if(file_exists($file)) {
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($file));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
    }
}



function goto_cronjob_script()
{
    if (getenv('NAO_SCRIPT_CRONJOB') != '1') {
	$s = basename($_SERVER['PHP_SELF'], '.php').'.html';
	header('Location: '.$s);
	exit;
    }
}

function requires_login($page=NULL)
{
    global $NAO_URL_ROOT;
    if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username'])) {
	$s = isset($page) ? $page : basename($_SERVER['PHP_SELF']);
	simple_redirect($NAO_URL_ROOT.'/login.php?goto=' . urlencode($s));
    }
}

/* return the # of registered users */
function n_registered_users()
{
  global $nh_dgl_sqlite_db;

  $db = new PDO("sqlite:".$nh_dgl_sqlite_db);
  $dat = $db->query("select count(*) from dglusers")->fetch();
  $db = null;
  return $dat[0];
}

function get_maybe_cached($func)
{
    $fname = '/tmp/nao-'.$func.'.cache';
    if (file_exists($fname) && (filemtime($fname) > time()-(60*10))) {
	$ret = file_get_contents($fname);
    } else {
	$ret = call_user_func($func);
	file_put_contents($fname, $ret);
    }
    return $ret;
}

function plr_savefile($plr)
{
  global $nh_savedir, $nh_UID;

  $savefile = $nh_savedir . $nh_UID . $plr.'.gz';

  if (file_exists($savefile)) {
    return filemtime($savefile);
  }
  return 0;
}

function plr_userdata_dir($plr)
{
    return '/opt/nethack/nethack.alt.org/dgldir/userdata/'.$plr[0].'/'.$plr;
}

/* return the public URL of $plr's rcfile */
function plr_rcfile_url($plr,$ver=0)
{
 global $nh_rcfile_url,$nh_curr_version;
 if ($ver) {	
 return $nh_rcfile_url.$plr[0]."/".$plr."/".$plr.'.'.$ver.'rc';
 } else {
 return $nh_rcfile_url.$plr[0]."/".$plr."/".$plr.'.'.$nh_curr_version.'rc';
 }
}

/* return the directory & filename of $plr's rcfile */
function plr_rcfile($plr,$ver=0)
{
 global $nh_rcfile_dir,$nh_curr_version;
 if ($ver) {	
 return $nh_rcfile_dir."/".$plr[0]."/".$plr."/".$plr.'.'.$ver.'rc';
 } else {
 return $nh_rcfile_dir."/".$plr[0]."/".$plr."/".$plr.'.'.$nh_curr_version.'rc';
 }
}

/* return the public URL of $plr's dumplogs */
function plr_dumplog_url($plr)
{
 global $nh_dumplog_url;
 return $nh_dumplog_url.$plr."/";
}
function plr_dumplog_dir($plr)
{
 /* not used anymore, now that in S3 */
 return '';
 /* global $nh_dumplog_dir;
 return $nh_dumplog_dir.'/'.$plr[0].'/'.$plr.'/dumplog/'; */
}

/* return the public URL of $plr's TTYRECs */
function plr_ttyrec_url($plr)
{
 global $nh_ttyrec_url;
 return $nh_ttyrec_url.$plr[0].'/'.$plr."/ttyrec/";
}
function plr_ttyrec_dir($plr)
{
 global $nh_ttyrec_dir;
 return $nh_ttyrec_dir.'/'.$plr[0].'/'.$plr.'/ttyrec/';
}

function paging_controls($npage, $pagesize, $nentries, $qvar='n')
{
  parse_str($_SERVER['QUERY_STRING'], $query);
  print '<div class="pagecontrols">';
  if ($npage > 0) {
    unset($query[$qvar]);
    print '<a class="first" href="'.phpself_querystr($query).'">First</a> ';
    $query[$qvar] = ($npage - 1);
    print '<a class="prev" href="'.phpself_querystr($query).'">Prev</a> ';
  } else {
    print '<span class="first">First</span> <span class="prev">Prev</span> ';
  }
  print '<span class="pagenum">'.($npage+1).'/'.((int)ceil($nentries/$pagesize)).'</span> ';
  if ((($npage+1) * $pagesize) < $nentries) {
    $query[$qvar] = ($npage + 1);
    print '<a class="next" href="'.phpself_querystr($query).'">Next</a> ';
    $query[$qvar] = (int)(($nentries-1) / $pagesize);
    print '<a class="last" href="'.phpself_querystr($query).'">Last</a>';
  } else {
    print '<span class="next">Next</span> <span class="last">Last</span>';
  }
  print '</div>';
}

function urlizestr($str, $type=0)
{
    global $regexp_replace_http;
    switch ($type) {
    default:
    case 0:
	$r = $regexp_replace_http['to']['full']; break;
    case 1:
	$r = $regexp_replace_http['to']['short']; break;
    }
    return preg_replace($regexp_replace_http['from'],$r,$str);
}

function mangle_definition_entry($val)
{
    $val = preg_replace('/\x02/','',$val);
    $val = preg_replace('/\x1f/','',$val);
    $val = htmlentities(preg_replace('/\n/','',$val));
    $val = preg_replace_callback('/\b[sS]ee {([^}]+)}$/',
				 function ($m) { return 'see {<A href="#'.strtolower($m[1]).'">'.$m[1].'</A>}'; },$val);
    $val = urlizestr($val, 1);
    return $val;
}

function definition_entry($key, $val)
{
    $word = $key;
    if ($word) {
	$sword = preg_replace('/(\w)_/','\\1 ',$word);
	print '<dt>';
	print '<a name="'.strtolower(htmlentities($word)).'"></a>';
	print '<a name="'.htmlentities($sword).'"></a>';
	print htmlentities($sword);
	print '</dt>'."\n";

	print '<dd>';
	if (is_array($val)) {
	    if (count($val) > 1) {
		print '<ol>';
		for ($i = 0; $i < count($val); $i++) {
		    print '<li><span>'.mangle_definition_entry($val[$i]).'</span>'."\n";
		}
		print '</ol>';
	    } else {
		print mangle_definition_entry($val[0]);
	    }
	} else {
	    print mangle_definition_entry($val);
	}
	print '</dd>'."\n";
    }
}



function table_caption($txt=NULL, $id=NULL)
{
    $html = '<TABLE'.($id ? " id='$id'" : '').'>'."\n";
    if ($txt) $html .= '<CAPTION>'.$txt.'</CAPTION>'."\n";
    return $html;
}

function table_head($arr)
{
    $html = '<THEAD>'."\n";
    $html .= "<TR>\n";
    foreach ($arr as $s) {
	$html .= '<TH>'.$s."</TH>\n";
    }
    $html .= "</TR>\n";
    $html .= "</THEAD>\n";
    return $html;
}

function plr_url($plr)
{
    global $NAO_URL_ROOT;
    return '<a href="'.$NAO_URL_ROOT.'/plr.php?player='.$plr.'" rel="nofollow">'.$plr.'</a>';
}

function plr_dump($plr, $starttime, $ver=NULL)
{
    global $NAO_URL_ROOT, $nh_curr_version;
    if ($ver == NULL) {
       $ver = $nh_curr_version;
    } else {
       $ver = 'nh' . preg_replace('/[.]/', '', $ver);
    }
    return $nh_dumplog_url.$plr.'/'.$starttime.'.'.$ver.'.txt';
}


/*
+       if(!u.uconduct.food)            e |= 0x001L;
+       if(!u.uconduct.unvegan)         e |= 0x002L;
+       if(!u.uconduct.unvegetarian)    e |= 0x004L;
+       if(!u.uconduct.gnostic)         e |= 0x008L;
+       if(!u.uconduct.weaphit)         e |= 0x010L;
+       if(!u.uconduct.killer)          e |= 0x020L;
+       if(!u.uconduct.literate)        e |= 0x040L;
+       if(!u.uconduct.polypiles)       e |= 0x080L;
+       if(!u.uconduct.polyselfs)       e |= 0x100L;
+       if(!u.uconduct.wishes)          e |= 0x200L;
+       if(!u.uconduct.wisharti)        e |= 0x400L;
+       if(!num_genocides())            e |= 0x800L;
*/

function num_conducts($c)
{
	return  (($c & 0x001) >> 0) +
		(($c & 0x002) >> 1) +
		(($c & 0x004) >> 2) +
		(($c & 0x008) >> 3) +
		(($c & 0x010) >> 4) +
		(($c & 0x020) >> 5) +
		(($c & 0x040) >> 6) +
		(($c & 0x080) >> 7) +
		(($c & 0x100) >> 8) +
		(($c & 0x200) >> 9) +
		(($c & 0x400) >> 10) +
		(($c & 0x800) >> 11);
}

$conduct_names = array(
	'Foodless',
	'Vegan',
	'Vegetarian',
	'Atheist',
	'Weaponless',
	'Pacifist',
	'Illiterate',
	'Polypiles',
	'Polyself',
	'Wishing',
	'Artiwishing',
	'Genocide'
);

$conduct_colors = array(
		'brown',
		'lightgreen',
		'yellow',
		'lightblue',
		'pink',
		'goldenrod',
		'lightsteelblue',
		'plum',
		'aquamarine',
		'ivory',
		'lightyellow',
		'cyan'
);

function decode_conduct($c)
{
	global $conduct_names;
	$txt = array();
	for ($x = 0; $x < count($conduct_names); $x++) {
		if ($c & (0x1 << $x)) {
			$txt[] = $conduct_names[$x];
		}
	}
	return join(", ", $txt);
}

function decode_conduct_short($c)
{
	global $conduct_names, $conduct_colors;
	$txt = "";
	for ($x = 0; $x < count($conduct_names); $x++) {
		if ($c & (0x1 << $x)) {
			$txt .= '<span style="font-family:monospace;background-color:'.$conduct_colors[$x].'" title="'.$conduct_names[$x].'">'.substr($conduct_names[$x],0,1).'</span>';
		}
	}
	return $txt;
}


function xlog_table($dat, $settings = array())
{
    $odd = 0;
    $sortfunc = ((isset($settings['sortfunc'])) ? $settings['sortfunc'] : NULL);
    $caption = ((isset($settings['caption'])) ? $settings['caption'] : '');
    $fields = ((isset($settings['fields'])) ? $settings['fields'] : array());
    $max_limit = ((isset($settings['max_limit'])) ? $settings['max_limit'] : 100);
    $need_parsing = ((isset($settings['need_parsing'])) ? $settings['need_parsing'] : 0);
    $direct_output = ((isset($settings['direct_output'])) ? $settings['direct_output'] : 0);
    $rank = ((isset($settings['rank'])) ? $settings['rank'] : 0);
    $table_id = $settings['table_id'];

    if (count($dat) < 1) return '';

    if ($max_limit < 1) $max_limit = 65535;

    if (!in_array(9, $fields)) {
	$dumped = 0;
	$tmpfields = array();
	for ($x = 0; $x < count($fields); $x++) {
	    array_push($tmpfields, $fields[$x]);
	    if (($fields[$x] == 1 || $fields[$x] == 14) && !$dumped) {
		array_push($tmpfields, 9);
		$dumped = 1;
	    }
	}
	$fields = $tmpfields;
    }

    if (function_exists($sortfunc)) usort($dat, $sortfunc);

    $ret = table_caption($caption, $table_id);

    $field_heads = array('Rank',	/* #0, ordinal number */
			 'Name',	/* #1, player name */
			 'Rank',	/* #2, like #0, but number. */
			 'Date',	/* #3, full endtime in "Sun, 02 Nov 2008 23:26:57 +0000" */
			 'Conducts',	/* #4 */
			 'Score',	/* #5 */
			 'Turns',	/* #6 */
			 'Time',	/* #7 */
			 array('','','',''),	/* #8 race role gender align */
			 '',		/* #9 */
			 'Date',	/* #10, short endtime in "2008-10-26" */
			 'Lev/Max',	/* #11, deathlev/maxlvl */
			 'HP/Max',	/* #12, hp/maxhp */
			 'Death',	/* #13, death reason*/
			 'Name',	/* #14, player name, like #1, but not an url link to plr.php */
			 'Start Time',  /* #15, */
			 'End Time'     /* #16, */
			 );

    $head = array();
    foreach ($fields as $f) {
	if (is_array($field_heads[$f])) $head = array_merge($head, $field_heads[$f]);
	else array_push($head, $field_heads[$f]);
    }
    $ret .= table_head($head);
    $ret .= '<TBODY>'."\n";

    if ($direct_output) { print $ret; $ret = ''; }

    $colstr = '';
    foreach ($fields as $f) {
	switch ($f) {
	default: $colstr .= '$ret .= "<TD>&nbsp;</TD>";'; break;
	case 0: $colstr .= '$ret .= "<TD>".ordnum(++$rank)."</TD>";'; break;
	case 1: $colstr .= '$ret .= "<TD>".plr_url($s[\'name\'])."</TD>";'; break;
	case 2: $colstr .= '$ret .= "<TD>".(++$rank).".</TD>";'; break;
	case 3: $colstr .= 'if ($s[\'endtime\']) { $ret .= "<TD>".date("r", $s[\'endtime\'])."</TD>"; } else { $ret .= "<TD>&nbsp;</TD>"; }'; break;
	case 4:	$colstr .= 'if ($s[\'conduct\']) {
                              /*$conds = base_convert($s[\'conduct\'], 16, 10);*/
			      $conds = $s[\'conduct\'];
			      $nconds = num_conducts($conds);
			      if ($nconds)
			        $ret .= \'<TD><B>\'.$nconds.\'</B> <em>(\'.decode_conduct_short($conds).\')</em></TD>\';
			      else
                                $ret .= \'<TD>\'.decode_conduct_short($conds).\'</TD>\';
                            } else { $ret .= "<TD>&nbsp;</TD>"; }';
	    break;
	case 5: $colstr .= '$ret .= \'<TD style="text-align:right">\'.number_format($s[\'points\']).\'</TD>\';'; break;
	case 6: $colstr .= 'if ($s[\'turns\']) { $ret .= \'<TD style="text-align:right">\'.$s[\'turns\'].\'</TD>\'; } else { $ret .= "<TD>&nbsp;</TD>"; }'; break;
	case 7: $colstr .= 'if ($s[\'realtime\']) { $val = $s[\'realtime\'];
			    $hour = intval($val / (60*60));
			    $mins = ($val / 60) % 60;
			    $secs = $val % 60;
			    $ret .= \'<TD style="text-align:right">\'.sprintf(\'%02u:%02u:%02u\', $hour, $mins, $secs).\'</TD>\'; } else { $ret .= "<TD>&nbsp;</TD>"; }';
	    break;
	case 8: $colstr .= '$ret .= \'<TD>\'.$s[\'role\'].\'</TD><TD>\'.$s[\'race\'].\'</TD><TD>\'.$s[\'gender\'].\'</TD><TD>\'.$s[\'align\'].\'</TD>\';';
	    break;
	case 9: $colstr .= 'if ($s[\'starttime\']) { $ret .= \'<TD>(<a href="\'.plr_dump($s[\'name\'], $s[\'starttime\'], $s[\'version\']).\'" rel="nofollow">d</a>)</TD>\'; } else { $ret .= "<TD>&nbsp;</TD>"; }';
	    break;
	case 10: $colstr .= '$ret .= \'<TD style="text-align:center">\'.dayformat($s[\'deathdate\']).\'</TD>\';';
	    break;
	case 11: $colstr .= '$ret .= \'<TD style="text-align:right">\'.$s[\'deathlev\'].\'/\'.$s[\'maxlvl\'].\'</TD>\';';
	    break;
	case 12: $colstr .= '$ret .= \'<TD style="text-align:right">\'.$s[\'hp\'].\'/\'.$s[\'maxhp\'].\'</TD>\';';
	    break;
	case 13: $colstr .= '$ret .= \'<TD style="text-align:right">\'.htmlentities($s[\'death\']).\'</TD>\';'; break;
	case 14: $colstr .= '$ret .= "<TD>".$s[\'name\']."</TD>";'; break;
	case 15: $colstr .= 'if ($s[\'starttime\']) { $ret .= "<TD>".dayformat(strftime("%Y%m%d", $s[\'starttime\']))."&nbsp;".strftime("%H:%M:%S", $s[\'starttime\'])."</TD>"; } else { $ret .= "<TD>&nbsp;</TD>"; };'; break;
	case 16: $colstr .= 'if ($s[\'endtime\']) { $ret .= "<TD>".dayformat(strftime("%Y%m%d", $s[\'endtime\']))."&nbsp;".strftime("%H:%M:%S", $s[\'endtime\'])."</TD>"; } else { $ret .= "<TD>&nbsp;</TD>"; };'; break;
	}
    }

    foreach ($dat as $s) {
	$ret .= tr_odd_even($odd++);
	if ($need_parsing) $s = parse_xlogfile_line($s);
	eval($colstr);
	$ret .= '</TR>'."\n";
	if ($direct_output) { print $ret; $ret = ''; }
	if ($odd >= $max_limit) break;
    }
    $ret .= '</TBODY>';
    $ret .= '</TABLE>'."\n";
    if ($direct_output) { print $ret; $ret = ''; }
    return $ret;
}


function datsort_urls($script, $cursort)
{
    global $nh_datsorttype;
    foreach ($nh_datsorttype as $key => $val) {
	if ($key == $cursort) { /*print '['.$key.']';*/ }
	else { print ' '.mk_url($script.$key, $key).' '; }
    }
}


function remove_null_keys($arr)
{
    foreach (array_keys($arr) as $k)
        if ($arr[$k] == null) unset($arr[$k]);
    return $arr;
}


function ordnum($i)
{
  $ends = array('th', 'st', 'nd', 'rd');
  $m = $i % 10;
  return $i.($ends[(!((($m) > 3) || (floor($i % 100 / 10) == 1)))*$m]);
}


function ranking_array($arr)
{
    $rank = 1;
    $x = 0;
    $plrnames = array_keys($arr);
    while ($x < count($plrnames)) {
        $start = $x;
	$end = $x;
	$ps = $arr{$plrnames[$start]};
	while ($arr{$plrnames[$end]} == $ps) { $end++; }
	for ($d = $start; $d < $end; $d++) {
	    $ranking{$plrnames[$d]} = $rank;
	}
	$rank = $rank + ($end - $start);
	$x = $end;
    }
    return $ranking;
}

function mk_dateboxes($name, $defval = null)
{
    global $nh_server_startyear, $month_names;
    $nao_years = array('');
    for ($x = $nh_server_startyear; $x <= intval(date("Y")); $x++) { $nao_years[] = $x; };
    $nao_months = $month_names;
    $nao_days = array('');
    for ($x = 1; $x < 32; $x++) { $nao_days[] = $x; }

    return mk_dropdown($name.'_year', $nao_years, $defval[$name.'_year'])
	.mk_dropdown($name.'_month', $nao_months, $defval[$name.'_month'])
	.mk_dropdown($name.'_day', $nao_days, $defval[$name.'_day']);
}


function dgl_auth_user($username = NULL, $passwd = NULL)
{
  global $nh_dgl_sqlite_db;
  if (!isset($username))
      $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
  if (!isset($passwd))
      $passwd = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

  if (!isset($username) || !isset($passwd) ||
      ($username == "") || ($passwd == "")) return 0;

  if (!preg_match("/^[0-9a-zA-Z]+$/", $username)) return 0;

  $passwd_crypt = crypt($passwd, substr($passwd, 0, 2));

  $username = strtolower($username);

  $db = new PDO("sqlite:".$nh_dgl_sqlite_db);
  $dat = $db->query("select * from dglusers where lower(username)='".$username."'")->fetch();
  $db = null;

  if (strtolower($dat['username']) == $username && $dat['password'] == $passwd_crypt) return 1;

  return 0;
} //dgl_auth_user()

function dgl_auth_user_username($username = NULL, $passwd = NULL)
{
  global $nh_dgl_sqlite_db;
  if (!isset($username))
      $username = $_SERVER['PHP_AUTH_USER'];
  if (!isset($passwd))
      $passwd = $_SERVER['PHP_AUTH_PW'];

  if (!isset($username) || !isset($passwd) ||
      ($username == "") || ($passwd == "")) return 0;

  if (!preg_match("/^[0-9a-zA-Z]+$/", $username)) return 0;

  $passwd_crypt = crypt($passwd, substr($passwd, 0, 2));

  $username = strtolower($username);

  $db = new PDO("sqlite:".$nh_dgl_sqlite_db);
  $dat = $db->query("select * from dglusers where lower(username)='".$username."'")->fetch();
  $db = null;

  if (strtolower($dat['username']) == $username && $dat['password'] == $passwd_crypt) return $dat['username'];

  return NULL;
} //dgl_auth_user_username()


function dgl_match_username($fragment, $exact=0)
{
  global $nh_dgl_sqlite_db;

  $ret = array();

  if (!preg_match("/^[0-9a-zA-Z]+$/", $fragment)) return $ret;

  $db = new PDO("sqlite:".$nh_dgl_sqlite_db);

  if ($exact) {
    $sqlx = "select * from dglusers where username='".$fragment."'";
  } else {
    $sqlx = "select * from dglusers where username like '%".$fragment."%'";
  }

  foreach ($db->query($sqlx) as $row) {
	$tmp = $row['username'];
	if ($tmp) $ret[] = $tmp;
  }

  $db = null;

  return $ret;
}

function get_hexcolor_gradient($c1, $c2, $curr,$max)
{
    $r = ($curr*$c2[0]+($max-$curr)*$c1[0])/$max;
    $g = ($curr*$c2[1]+($max-$curr)*$c1[1])/$max;
    $b = ($curr*$c2[2]+($max-$curr)*$c1[2])/$max;
    return sprintf("%02x%02x%02x", $r,$g,$b);
}

/*
 * Print a colored table of days for one year.
 *  $year is the table heading
 *  $data is array[month][day] of ints
 *  $maxval is the biggest int in $data
 *  $montotal is array[12] of int, the totals for each month
 *  $maxmon is biggest int in $montotal
 *  $flags has several misc flags:
 *   $flags['monthcolor1'] = array(255,255,255)
 *   $flags['monthcolor2'] = array(255,128,0)
 *   $flags['daycolor1'] = array(255,255,255)
 *   $flags['daycolor2'] = array(255,255,0)
 *   $flags['dayurl'] = NULL  (you could put eg. 'gamesday.php?date=', and it would get
 *     the date appended to it, in yyyymmdd format)
 */
function print_yeargraph_table($year, $data, $maxval, $montotal, $maxmon=-1, $flags=NULL)
{
    global $month_names;

    if (!isset($flags['monthcolor1'])) $flags['monthcolor1'] = array(255,255,255);
    if (!isset($flags['monthcolor2'])) $flags['monthcolor2'] = array(255,128,0);
    if (!isset($flags['daycolor1'])) $flags['daycolor1'] = array(255,255,255);
    if (!isset($flags['daycolor2'])) $flags['daycolor2'] = array(255,255,0);

    print "\n".'<table width="95%">';
    print '<tr><th colspan="33">'.$year.'</th></tr>'."\n";

    print '<tr><td colspan="2">&nbsp;</td>';
    for ($day = 1; $day <= 31; $day++) {
        print '<th>&nbsp;'.$day.'</th>';
    }
    print '</tr>'."\n";

    for ($mon = 1; $mon <= 12; $mon++) {

        print '<tr><th align="right">'.$month_names[$mon].'</th>';
	if (($maxmon > 0) && ($montotal[$mon] == $maxmon)) { print '<td class="mostasc">'; }
	else {
	    $bgcolor = get_hexcolor_gradient($flags['monthcolor1'], $flags['monthcolor2'], $montotal[$mon],$maxmon);
    	    print '<td style="background-color:#'.$bgcolor.';">';
	}
        print $montotal[$mon].'</td>';

        for ($day = 1; $day <= 31; $day++) {
            $a = $data[$mon][$day];
            if ($a == $maxval) {
                print '<td class="mostasc">';
    	    } else if ($a) {
		$bgcolor = get_hexcolor_gradient($flags['daycolor1'], $flags['daycolor2'], $a,$maxval);
    	        print '<td style="background-color:#'.$bgcolor.';">';
    	    } else print '<td>';
	    if ($a) {
	        if (isset($flags['dayurl'])) {
		    print '<A href="'.$flags['dayurl'].sprintf("%04s%02s%02s", $year,$mon,$day).'" rel="nofollow">'.$a.'</A>';
                } else print $a;
	    } else print ' &nbsp; ';
       	    print '</td>';
        }
        print '</tr>'."\n";
    }
    print '</table>'."\n";
}

function get_rating_table($percent, $showpercent = NULL)
{
  $ret = '<span class="ratingbar">';
  $ret .= '<span class="rating" style="width:'.$percent.'%;">&nbsp;</span>';
  if ($showpercent) { $ret .= '<span class="ratingtext">'.$percent.'%</span>'; }
  $ret .= '</span>';
  return $ret;
}

function bargraph_xcmp($a, $b)
{
	if ($a['v'] == $b['v']) {
	   return strcmp($a['k'], $b['k']);
	}
	return ($a['v'] > $b['v']) ? -1 : 1;
}

function bargraph($header, $ar, $maxval, $shownum=1, $showtotal=0)
{
	$odd = 0;
	$ret = '<TABLE width="75%">';
	$ret .= '<CAPTION>'.$header.'</CAPTION>';

	$total = 0;

	/* Ugh, but apparently there's no other way to sort first by value then by key */
	$tmpar = array();
	while (list($key, $val) = each($ar)) {
	      array_push($tmpar, array('k'=>$key,'v'=>$val));
	}
	usort($tmpar, 'bargraph_xcmp');
	foreach ($tmpar as $r) {
		$key = $r['k'];
		$val = $r['v'];
		$total += $val;
		$ret .= tr_odd_even($odd++);
		$ret .= '<TD>'.$key.'</TD>';
		if ($shownum) $ret .= '<TD width="5%">'.$val.'</TD>';
		$ret .= '<TD width="100px">'.get_rating_table(round($val*(100/$maxval), 2), 1).'</TD>';
		$ret .= '</TR>';
	}

	if ($showtotal) {
	    $ret .= tr_odd_even($odd++);
	    $ret .= '<TD style="text-align:right">Total</TD>';
	    $ret .= '<TD>'.$total.'</TD>';
	    if ($shownum) $ret .= '<TD>&nbsp;</TD>';
	}

	$ret .= '</TABLE>';

	return $ret;
}


function mk_prefs_link()
{
    return '<DIV class="prefs">'.mk_url('setprefs.php','Prefs', array('nofollow'=>1)).'</DIV>';
}

function mk_inputfield($type, $name, $val='')
{
    switch ($type) {
    case 'text':
    case 'submit':
    case 'password':
        return '<input type="'.$type.'" name="'.$name.'" value="'.$val.'">';
    case 'checkbox':
        return '<input type="'.$type.'" name="'.$name.'"'.($val ? ' checked' : '').'>';
    }
}

function mk_url($url,$name=NULL,$data=NULL)
{
	if (!isset($name)) { $name = $url; }
	return '<A href="'.$url.'"'.(isset($data['nofollow'])?' rel="nofollow"':'').'>'.$name.'</A>';
}

function odd_even($odd)
{
	return ($odd % 2) ? 'odd' : 'even';
}

function tr_odd_even($odd)
{
	return '<TR class="'.odd_even($odd).'">';
}

$today = date("Ymd");

function dayformat($day)
{
    global $today;
    if ($day == $today) {
	return 'today';
    } else {
	return substr($day,0,4).'-'.substr($day,4,2).'-'.substr($day,6);
    }
}

function show_with_valid_username($plr, $func, $format='plr.php?player=%s')
{
    if (isset($plr)) {
	if (!is_callable($func)) return;

	if (preg_match("/^[0-9a-zA-Z]+$/", $plr)) {

	    $plr_matches = dgl_match_username($plr);

	    $plr_count = count($plr_matches);

	    if ($plr_count > 1) {
		$plr_matches_exact = dgl_match_username($plr, 1);
		if (count($plr_matches_exact) == 1) $plr_matches = $plr_matches_exact;
		$plr_count = count($plr_matches);
	    }

	    if ($plr_count > 200) {
		print '<P>"<EM>'.$plr.'</EM>" matches '.count($plr_matches).' usernames, please narrow your search.';
	    } else if ($plr_count > 1) {
		print '<P>"<EM>'.$plr.'</EM>" matches several usernames:';
		print '<P>';
		$tmparr = array();
		function valid_username_icmp($a,$b) { return strcasecmp($a,$b); }
		usort($plr_matches, 'valid_username_icmp');
		foreach ($plr_matches as $tmp) {
		    if ($tmp)
		        $tmparr[] = mk_url(sprintf($format, $tmp), $tmp, array('nofollow'=>1));
		}
		print_tabled_array($tmparr, 5);
	    } else if ($plr_count < 1) {
		print '<P>"<EM>'.$plr.'</EM>" does not match any username.';
	    } else {
		$plr = $plr_matches[0];
		call_user_func($func, $plr);
	    }
	} else print '<P>"<EM>'.$plr.'</EM>" is not a valid name.';
    }
    return $plr;
}

function parse_file($func, $nfile=0)
{
	global $nh_logfile, $nh_scorefile;

	if (!is_callable($func)) return;

	switch ($nfile) {
		case 1:  $file = $nh_logfile; break;
		default: $file = $nh_scorefile; break;
	}

	$fh = @fopen($file, 'r');
	while (!feof($fh)) {
	    $line = fgets($fh, 256); /* FIXME: might need to increase if logfile/record format changes */
	    if (strlen($line) < 2) continue;
	    $dat = parse_logfile_line($line);
	    call_user_func($func, $dat, ++$rank);
	}
	fclose($fh);
}

function parse_xlogfile($func=null, $sqlquery=null)
{

	global $nh_xlogfile_db;
	$rank = 0;

	if (($func != null) && !is_callable($func)) return;
	if (!$sqlquery) $sqlquery = "select * from xlogfile";

	/*$db = new PDO("sqlite:".$nh_xlogfile_db);*/
	$db = new PDO("mysql:host=localhost;dbname=xlogfiledb","rodney","INSERTPASSWORD");
	$sqlx = $sqlquery;
	$stmt = $db->query($sqlx);
	if (!$stmt) { print_r($db->errorInfo()); die("parse_xlogfile()"); }
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	if ($func != null) {
	    while ($dat = $stmt->fetch()) {
	        call_user_func($func, $dat, ++$rank);
	    }
	} else {
	    $data = $stmt->fetchAll();
	    $db = null;
	    return $data;
	}
	$db = null;
}

function n_xlogfile_entries($sqlquery=null)
{

	global $nh_xlogfile_db;

	if (!$sqlquery) $sqlquery = "select * from xlogfile";

	/*$db = new PDO("sqlite:".$nh_xlogfile_db);*/
	$db = new PDO("mysql:host=localhost;dbname=xlogfiledb","rodney","INSERTPASSWORD");
	$dat = $db->query($sqlquery)->fetch();
  	$db = null;
  	return $dat[0];
}


function mk_dropdown($name, $dat, $defval = 0)
{
    $str = '<select name="'.$name.'">';
    for ($x = 0; $x < count($dat); $x++) {
	$str .= '<option value="'.$x.'"';
	if (($x == $defval) || ($dat[$x] == $defval)) { $str .= ' selected'; }
	$str .= '>'.$dat[$x].'</option>';
    }
    $str .= '</select>';
    return $str;
}

function print_tabled_array($arr, $cols = 3)
{
    if (is_array($arr) && (count($arr) > 0)) {

 	if ($cols < 1) { $cols = 1; }
	while (($cols > 0) && ((count($arr) / $cols) < $cols)) { $cols = $cols - 1; }

	$divd = intval((count($arr)-1+$cols) / $cols);

	print '<TABLE style="width:100%">';
	for ($x = 0; $x < $divd; $x++) {
	    print '<TR>';
	    for ($y = 0; $y < $cols; $y++) {
	        $idx = intval($y * $divd + $x);
		$d = $arr[$idx];
		if ($d) {
		    print '<TD align="right">'.($idx+1).'.</TD><TD>'.$d.'</TD>';
		} else {
		    print '<TD colspan="2">&nbsp;</TD>';
		}
	    }
	    print '</TR>';
	}
	print '</TABLE>';
    }
}

/* FIXME: this global $key, $numeric bothers me.
 */
function array_sortkey($arr, $key, $reverse = 0, $numeric = 1)
{
    function array_sortkey_icmp($a, $b)
    {
	global $key, $numeric;
	if ($numeric) {	if ($b[$key] > $a[$key]) return 1; else if ($b[$key] < $a[$key]) return -1; else return 0; } /* $b[$key]-$a[$key]; }*/
	return strcasecmp($a[$key], $b[$key]);
    }
    function array_sortkey_icmpr($a, $b)
    {
	global $key, $numeric;
	if ($numeric) {	if ($b[$key] > $a[$key]) return -1; else if ($b[$key] < $a[$key]) return 1; else return 0; } /* $a[$key]-$b[$key]; }*/
	if ($numeric) {	return $a[$key]-$b[$key]; }
	return strcasecmp($a[$key], $b[$key]);
    }
    if ($reverse) {
	usort($arr, 'array_sortkey_icmpr');
    } else {
	usort($arr, 'array_sortkey_icmp');
    }
    return $arr;
}

function nh_html_top($title, $data=NULL)
{
	global $nh_title_servername;
	$data['title'] = $nh_title_servername.' - '.$title;
	html_top($data);
}

function nh_html_bottom($data=NULL)
{
	html_bottom($data);
}

function print_news($limit = 3)
{
    if (!is_readable('news')) {
	print '<P class="news">No news.';
	return;
    }
    $fh = fopen('news', 'r');
    while (!feof($fh) && ($limit--)) {
	$line = fgets($fh, 4096);
	if (strlen($line) < 2) continue;
	print '<P class="news">'.$line;
    }
    if (!feof($fh)) {
	print '<P class="more">'.mk_url('news.php','More');
    }
    fclose($fh);
}

function dungeon_to_dname($dungeon)
{
    switch ($dungeon) {
      case 0:   return 'DoD';
      case 1:   return 'Gehennom';
      case 2:   return 'Mines';
      case 3:   return 'Quest';
      case 4:   return 'Sokoban';
      case 5:   return 'Ludios';
      case 6:   return 'Vlad\'s';
      case 7:   return 'Planes';
      case -5:  return 'Astral';
      case -4:  return 'Water';
      case -3:  return 'Fire';
      case -2:  return 'Air';
      case -1:  return 'Earth';
      default:  return 'Unknown';
    }
}

function dungeonlevel($dnum, $dlvl)
{
    $dungeon = dungeon_to_dname($dnum);
    if ($dungeon == 'Planes') {
	$lvl = dungeon_to_dname($dlvl).' Plane';
	return $lvl;
    } else {
        $lvl = $dlvl;
    }
    return $dungeon.' / '.$lvl;
}

function get_cookied_tablefields()
{
    $str = $_COOKIE['nhdatatableview'];
    if (!isset($str)) { $str = 'Score Name Dlvl MaxDlvl HP MaxHP Role Race Gender Align Reason EndDate'; }
    $arr = preg_split("/ /", preg_replace('/ +/', ' ', trim($str)));
    foreach ($arr as $line) { $dat[$line] = 1; }
    return $dat;
}

function tableheader($tabletype)
{
    $dat = get_cookied_tablefields();

    print '<THEAD>';

    print '<TR>';
    print '<TH align="right">Rank</TH>';

    if ($dat['Version']) {
	print '<TH align="right">Version</TH>';
    }

    if ($dat['Score']) {
	print '<TH align="right">Score</TH>';
    }
    if ($dat['Name']) {
	print '<TH align="right">Name</TH>';
    }

    if ($dat['Dnum']) {
	print '<TH align="right">Dungeon</TH>';
    }

    if ($dat['Dlvl'] && $dat['MaxDlvl']) {
	print '<TH align="right">Lev/Max</TH>';
    } else if ($dat['Dlvl']) {
	print '<TH align="right">Lev</TH>';
    } else if ($dat['MaxDlvl']) {
	print '<TH align="right">MaxLvl</TH>';
    }

    if ($dat['HP'] && $dat['MaxHP']) {
	print '<TH align="right">HP/Max</TH>';
    } else if ($dat['HP']) {
	print '<TH align="right">HP</TH>';
    } else if ($dat['MaxHP']) {
	print '<TH align="right">MaxHP</TH>';
    }

    if (isset($dat['Role']) || isset($dat['Race']) || isset($dat['Gender']) || isset($dat['Align'])) {
	$span = $dat['Role'] + $dat['Race'] + $dat['Gender'] + $dat['Align'];
	print str_repeat('<TH></TH>', ($span - 1));
	print '<TH>Type</TH>';
    }

    if ($dat['Reason']) {
	print '<TH align="right">Death</TH>';
    }

    if ($dat['StartDate'] || $dat['EndDate']) {
	$span = $dat['StartDate'] + $dat['EndDate'];
	print str_repeat('<TH></TH>', ($span - 1));
	print '<TH>Date</TH>';
    }

    print '</TR>';

    print '</THEAD>';
    print '<TBODY>';
}

function tablefooter()
{
    print '</TBODY>';
    print '</TABLE>';
}

function mk_tablerow($name, $dat)
{
    $str = '<TR><TD>'.$name.'</TD>';
    if (is_array($dat)) {
	foreach ($dat as $a) { $str .= '<TD>'.$a.'</TD>'; }
    } else {
	$str .= '<TD>'.$dat.'</TD>';
    }
    $str .= '</TR>';
    return $str;
}

$nh_tablerow = 0;

function tablerow($tabletype, $dat, $rank)
{
    global $nh_tablerow;
    print tr_odd_even($nh_tablerow++)
	.'<TD align="right">'.$rank.'</TD>';

    $sdat = get_cookied_tablefields();

    if ($sdat['Version']) {
	print '<TD align="right">'.$dat['Version'].'</TD>';
    }

    if ($sdat['Score']) {
	print '<TD align="right">'.number_format($dat['Score']).'</TD>';
    }
    if ($sdat['Name']) {
	if ($tabletype == 1) {
	    $str = $dat['Name'];
	} else {
	    $str = mk_url('plr.php?player='.$dat['Name'], $dat['Name'], array('nofollow'=>1));
	}
	print '<TD align="right">'.$str.'</TD>';
    }

    if ($sdat['Dnum']) {
	print '<TD align="right">'.dungeon_to_dname($dat['Dnum']).'</TD>';
    }

    if ($sdat['Dlvl'] && $sdat['MaxDlvl']) {
	print '<TD align="right">'.$dat['Dlvl'].'/'.$dat['MaxDlvl'].'</TD>';
    } else if ($sdat['Dlvl']) {
	print '<TD align="right">'.$dat['Dlvl'].'</TD>';
    } else if ($sdat['MaxDlvl']) {
	print '<TD align="right">'.$dat['MaxDlvl'].'</TD>';
    }

    if ($sdat['HP'] && $sdat['MaxHP']) {
	print '<TD align="right">'.$dat['HP'].'/'.$dat['MaxHP'].'</TD>';
    } else if ($sdat['HP']) {
	print '<TD align="right">'.$dat['HP'].'</TD>';
    } else if ($sdat['MaxHP']) {
	print '<TD align="right">'.$dat['MaxHP'].'</TD>';
    }

    if ($sdat['Role']) {
	print '<TD align="right">'.mk_url('character.php?role='.$dat['Role'], $dat['Role'], array('nofollow'=>1)).'</TD>';
    }
    if ($sdat['Race']) {
	print '<TD>'.$dat['Race'].'</TD>';
    }
    if ($sdat['Gender']) {
	print '<TD>'.$dat['Gender'].'</TD>';
    }
    if ($sdat['Align']) {
	print '<TD>'.$dat['Align'].'</TD>';
    }

    if ($sdat['Reason']) {
	print '<TD align="right">'.$dat['Reason'].'</TD>';
    }

    if ($sdat['StartDate']) {
	print '<TD align="right">'.dayformat($dat['StartDate']).'</TD>';
    }
    if ($sdat['EndDate']) {
	print '<TD align="right">'.dayformat($dat['EndDate']).'</TD>';
    }

    print '</TR>'."\n";

}

function is_scum_dat($dat)
{
	return (($dat['points'] < 10) && ($dat['deathlev'] == 1) && preg_match('/^(quit|escaped)/',$dat['death']));
}

function whereis_pretty($plr)
{
    global $nh_whereis_dir;
    $notin = 'is not currently in the dungeon.';

    $dungeons = array('the Dungeons of Doom', 'Gehennom', 'Mines', 'Quest', 'Sokoban', 'Ludios', 'Vlad\'s', 'Plane');
    $planes = array('dummy', 'Earth', 'Air', 'Fire', 'Water', 'Astral');
    $aligns = array('Neu' => 'neutral', 'Law' => 'lawful', 'Cha'=>'chaotic');
    $rolemap = array('Arc'=>'archeologist', 'Bar'=>'barbarian', 'Cav'=>'caveman', 'Hea'=>'healer', 'Kni'=>'knight', 'Mon'=>'monk', 'Pri'=>'priest', 'Rog'=>'rogue', 'Ran'=>'ranger', 'Sam'=>'samurai', 'Tou'=>'tourist', 'Val'=>'valkyrie', 'Wiz'=>'wizard');
    $racemap = array('Dwa'=>'dwarven', 'Elf'=>'elven', 'Gno'=>'gnomish', 'Hum'=>'human', 'Orc'=>'orcish');

    $file = $nh_whereis_dir . '/' . $plr . '.whereis';

    $fh = @fopen($file, 'r');
    if (!$fh) return $notin;
    $line = fgets($fh, 256);
    fclose($fh);
    if (strlen($line) < 2) return $notin;
    $dat = parse_xlogfile_line($line);

    if (!$dat['race']) return '';

    /*depth=1:dnum=0:hp=16:maxhp=16:turns=2:score=0:role=Valkyrie:race=human:gender=Mal:align=neutral:conduct=0xfff:amulet=0*/

    if ($dat['gender'] == 'Mal') $gender = 'male';
    else $gender = 'female';

    $str = $plr . ' the '.$aligns[$dat['align']].' '.$gender.' '.$racemap[$dat['race']].' '.$rolemap[$dat['role']];
    $str .= ' was last seen in '.$dungeons[$dat['dnum']];
    $str .= ' on turn '.$dat['turns'].' and with '.$dat['score'].' points';
    if (intval($dat['amulet']))
	$str .= ', carrying the amulet!';

    return $str;
}

function parse_xlogfile_line($line)
{
    // Parses one line from extended logfile.
    $spl = explode(":", $line);
    $final = array();
    foreach ($spl as $l) {
	$tmp = explode("=", $l);
	$final[$tmp[0]] = $tmp[1];
    }
    return ($final);
}

function parse_logfile_line($line)
{
	// Parses Single line from log file
	$spacesplit = explode(" ", $line);
	$final = array();
	$final['Version'] = array_shift($spacesplit); //0
	$final['Score'] = array_shift($spacesplit); // 1
	$final['Dnum'] = array_shift($spacesplit); // 2
	$final['Dlvl'] = array_shift($spacesplit); // 3
	$final['MaxDlvl'] = array_shift($spacesplit); // 4
	$final['HP'] = array_shift($spacesplit); // 5
	$final['MaxHP'] = array_shift($spacesplit); // 6
	$final['Deaths'] = array_shift($spacesplit); // 7
	$final['EndDate'] = array_shift($spacesplit); // 8
	$final['StartDate'] = array_shift($spacesplit); // 9
	$final['UID'] = array_shift($spacesplit); // 10
	$final['Role'] = array_shift($spacesplit); // 11
	$final['Race'] = array_shift($spacesplit); // 12
	$final['Gender'] = array_shift($spacesplit); // 13
	$final['Align'] = array_shift($spacesplit); // 14
	$spacesplit = explode(",", implode(" ", $spacesplit));
	$final['Name'] = array_shift($spacesplit);
	$final['Reason'] = htmlentities(implode(",", $spacesplit));
	return ($final);
}

function simple_death_reason($reason)
{
    global $NAO_helplesses;
	$by = trim($reason);
	$by = preg_replace("/^(.+) called .*$/","\\1",$by);
	$by = preg_replace("/^(.+) named .*$/","\\1",$by);

	$by = preg_replace("/^(killed by kicking) .+$/","\\1 (something)",$by);
//	$by = preg_replace("/^(killed by kicking an?) ((uncursed)|(cursed)|(blessed)) /","\\1 ",$by);
//	$by = preg_replace("/^(killed by kicking( an?)?) [-+0-9]+ /","\\1 ",$by);

	// wand charges
	$by = preg_replace("/ \([0-9]+:[0-9]+\)$/","",$by);

	$by = preg_replace("/( mounting a saddled) .+$/","\\1 (pet)",$by);

	$by = preg_replace("/ a worthless piece of .+ glass/"," (a worthless piece of glass)",$by);
	$by = preg_replace("/ an? ((agate)|(amber)|(aquamarine)|(chrysoberyl)|(citrine)|(fluorite)|(garnet)|(jacinth)|(jade)|(jasper)|(jet)|(obsidian)|(topaz)|(turquoise)) stone/"," (a gem stone)",$by);
	$by = preg_replace("/^killed by an? (ruby|diamond|dilithium crystal|sapphire|black opal|opal|emerald)$/", "killed by (a gem stone)", $by);

	$by = preg_replace("/((the)?) invisible/","\\1",$by);
	$by = preg_replace("/((the)?) hallucinogen-distorted/","\\1",$by);
	$by = preg_replace("/((moat)|(pool of water))/","(moat|pool of water)",$by);
	$by = preg_replace("/((himself)|(herself))/","(him|her)self",$by);
	$by = preg_replace("/((a giant eel)|(an electric eel)|(a kraken))/","an (*eel|kraken)",$by);
	$by = preg_replace("/a priest(ess)?( of .*)?$/","a priest(ess)",$by);

	$by = preg_replace("/ the high priest(ess)? of .*$/"," the high priest(ess) of (a god)",$by);

	$by = preg_replace("/, while helpless/","",$by);

	$by = preg_replace("/ \(with the Amulet\)/","",$by);

	$by = preg_replace('/^(.+), while ('.join('|', $NAO_helplesses).')$/', "\\1", $by);

	$by = preg_replace("/^killed by (Mr|Ms)\. .* the shopkeeper/","killed by a shopkeeper",$by);
	$by = preg_replace("/ the wrath of (.*)?$/"," the wrath of (a god)",$by);
	$by = preg_replace("/ the ghost of (.*)$/"," the ghost of (a player)",$by);

	$by = preg_replace("/^(killed by touching) ((The Staff of Aesculapius)|(Sting)|(Grimtooth)|(Magicbane)|(The Eyes of the Overworld)|(The Mitre of Holiness)|(Cleaver)|(The Master Key of Thievery)|(The Orb of Detection)|(Giantslayer)|(Grayswandir)|(Demonbane)|(Sunsword)|(Snickersnee)|(Vorpal Blade)|(Orcrist)|(The Orb of Fate)|(The Eye of the Aethiopica)|(Stormbringer)|(Excalibur))$/","\\1 (an artifact)",$by);

	$by = preg_replace("/ (an? .+ of) ((Moloch)|(Quetzalcoatl)|(Camaxtli)|(Huhetotl)|(Mitra)|(Crom)|(Set)|(Anu)|(Ishtar)|(Anshar)|(Athena)|(Hermes)|(Poseidon)|(Lugh)|(Brigit)|(Manannan Mac Lir)|(Shan Lai Ching)|(Chih Sung-tzu)|(Huan Ti)|(Issek)|(Mog)|(Kos)|(Mercury)|(Venus)|(Mars)|(Amaterasu Omikami)|(Raijin)|(Susanowo)|(Blind Io)|(The Lady)|(Offler)|(Tyr)|(Odin)|(Loki)|(Ptah)|(Thoth)|(Anhur))$/", " \\1 (a god)", $by);

	$by = preg_replace("/rotted (.*) corpse/","rotted corpse",$by);
	$by = preg_replace("/choked(.*)$/","choked",$by);
	$by = preg_replace("/ (cockatrice|chickatrice)/"," (cockatrice|chickatrice)",$by);
	$by = preg_replace("/ ((a)|(an)) /"," a(n) ",$by);
	$by = preg_replace("/ ((his)|(her)) /"," (his|her) ",$by);
	return $by;
}

function get_files_in_dir($dir)
{
    $ret = array();
    if ($dh = @opendir($dir)) {
	while (($file = readdir($dh)) !== false)
	    if (($file != '.') && ($file != '..'))
	        array_push($ret, $file);
	closedir($dh);
    }
    return $ret;
}

function plr_is_online($nick)
{
    global $nh_inprogress_dir;
    if (!isset($nick)) return 0;
    $nick = strtolower($nick);
    if ($dh = @opendir($nh_inprogress_dir)) {
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
		$pname = preg_split("/:/", $file, 2);
		if (strtolower($pname[0]) == $nick) {
		    closedir($dh);
		    return 1;
		}
            }
        }
        closedir($dh);
    }
    return 0;
}


function current_players()
{
    global $nh_inprogress_dir;
    function current_players_icmp($a,$b)
	{
	    return strcasecmp($a,$b);
	}
    $ret = array();
    if ($dh = @opendir($nh_inprogress_dir)) {
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
		$pname = preg_split("/:/", $file, 2);
		array_push($ret, trim($pname[0]));
            }
        }
        closedir($dh);
    }
	// hack for now! hack! hack
	// this ONLY here because I want nao site to show combined 343/360 players for now -drew
	// sorry for the ugliness pasi. :)
    if ($dh = @opendir("/opt/nethack/nethack.alt.org/dgldir/inprogress-nh343")) {
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
		$pname = preg_split("/:/", $file, 2);
		array_push($ret, trim($pname[0]));
            }
        }
        closedir($dh);
    }
    usort($ret, 'current_players_icmp');
    return $ret;
}

function current_players_links()
{
    global $NAO_URL_ROOT;
    $currplr = current_players();
    $cnt = count($currplr);
    $curr = $cnt.' <A href="'.$NAO_URL_ROOT.'/plrs.php" rel="nofollow">players currently online</A>';
    if ($cnt > 0) {
	$curr .= ': ';
    }
    $idx = 0;
    foreach ($currplr as $cp) {
	$idx++;
	$curr .= mk_url('plr.php?player='.$cp, $cp, array('nofollow'=>1));
	if ($idx < $cnt) $curr .= ",\n ";
    }
    return $curr;
}

function number_of_players()
{
    global $nh_inprogress_dir;
    $ret = 0;
    if ($dh = @opendir($nh_inprogress_dir)) {
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
		$ret++;
            }
        }
        closedir($dh);
    }
    return $ret;
}


/* Needs php5
function parse_logfile($logfile)
{
	global $nh_cachefile;
	$cachefile = str_replace("%s", basename($logfile), $nh_cachefile);
        // logfile cache
        // check mod time of cache
        if ((!file_exists($cachefile)) || (filemtime($cachefile) < filemtime($logfile)))
        {
                // If the cache file is older than the logfile, make new cache
                // Parses log file, returns as a multidimensional array
                $fh = fopen($logfile,'r');
                while (!feof($fh))
                {
                        // Read file line by line
                        $line = fgets($fh);
                        if (!$line) continue;
                        $final = parse_logfile_line($line);
                        $array[] = $final;
                }
                // write cache
                file_put_contents($cachefile,serialize($array));
                return ($array);
        }
        else
        {
                // return the array from the cache file
                $file = file_get_contents($cachefile);
                $array = unserialize($file);
                return ($array);
        }
} 
*/

function query_str($params)
{
  $str = '';
  foreach ($params as $key => $value) {
    $str .= (strlen($str) < 1) ? '' : '&';
    $str .= $key . '=' . rawurlencode($value);
  }
  return ($str);
}

function phpself_querystr($querystr = null)
{
  $ret = $_SERVER['PHP_SELF'];
  $ret = preg_replace('/\/index.php$/', '/', $ret);
  if (!isset($querystr)) parse_str($_SERVER['QUERY_STRING'], $querystr);
  if (count($querystr)) $ret .= '?'.htmlentities(query_str($querystr));

  return $ret;
}

