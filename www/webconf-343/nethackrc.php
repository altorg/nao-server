<?php
session_start();
$nh_curr_version = 'nh343';
include "../nhinclude.php";
requires_login('webconf/'.basename($_SERVER['PHP_SELF']));

error_reporting(E_ALL);
ini_set('display_errors','On');


$dgllogin=$nh_dgl_loginfile;
$nhrcpath=$nh_rcfile_dir.'/';

$synonym_opts = array();

$configs=array();
$values=array();
$helptxt=array();




include 'OptionUnknown.php';
$option_unknown = new UnknownOption();

include 'OptionAPE.php';
$option_ape = new APEOption();

include 'OptionMsgtype.php';
$option_msgtype = new MsgtypeOption();

include 'OptionMenucolor.php';
$option_menucolor = new MenucolorOption();

include 'OptionStatuscolor.php';
$option_statuscolor = new StatuscolorOption();




function get_unsaved_options()
{
    global $configs;
    global $option_menucolor;
    global $option_statuscolor;
    global $option_msgtype;
    global $option_ape;
    global $option_unknown;
    $ret = '';
    foreach ($configs as $key => $val) {
	$tmp = 'OPTIONS=';
	if (!isset($val['saved'])) {
	    if (isset($_POST[$key]) && ($_POST[$key]) != $val['defvalue']) {
	    } else {
		continue;
	    }
	} else if ($val['saved'] == 1) continue;

	if ($ret != '') $ret .= "\n";
	if ($val['type'] == 'bool') {
	    $ret .= $tmp . (($_POST[$key] == 'no') ? 'no' : '') . $key;
	} else {
	    if (isset($_POST[$key]) && $_POST[$key] != '') {
		$ret .= $tmp . $key . ':' . $_POST[$key];
	    }
	}
    }

    $ret .= $option_menucolor->get_all_posts();

    $ret .= $option_statuscolor->get_all_posts();

    $ret .= $option_msgtype->get_all_posts();

    $ret .= $option_ape->get_all_posts();

    $ret .= $option_unknown->get_all_posts();

    return $ret;
}

function parse_options($line, $authoritative){/*{{{*/
    global $configs, $synonym_opts;
    $ret = '';
	$items = explode(',', $line);
	// Hack to handle OPTIONS=menu_select_page:,
	if ($authoritative && isset($items[1]) && ($items[1] == '') && substr($line, -1) == ',') {
	    $items[0] = $line;
	    unset($items[1]);
	}
	foreach ($items as $item){
		$type="bool";
		$value="yes";
		$hidden = 0;
		$litem = strtolower($item);
		if ($authoritative == 1 && preg_match('/^~/', $item)) {
		    $hidden = 1;
		    $item = preg_replace('/^~/', '', $item);
		}
		if (preg_match("/^(no|!)/", $item)){
			$value="no";
			$item=preg_replace("/^(no|!)/", "", $item);
			$litem = strtolower($item);
		} elseif (preg_match("/(:|=)(.*)/", $item, $matches)){
			$type="text";
			$value=$matches[2];
			$item=preg_replace("/\s*(:|=).*$/", "", $item);
			$litem = strtolower($item);
		}
		$item=preg_replace("/\n/", "", $item);
		if ($type == 'bool') $item = trim($item);
		if (isset($synonym_opts[$litem])) $item = $synonym_opts[$litem];
		if ($authoritative == 1){
		    $configs[$item] = array('name' => $item, 'type' => $type, 'defvalue' => $value, 'hidden' => $hidden);
		    if (!isset($synonym_opts[$litem])) $synonym_opts[$litem] = $item;
		} else {
		    if (!isset($configs[$item])){
				echo "Unknown option: $item<BR>";
		    } else {
				$configs[$item]["uservalue"]=$value;
				$configs[$item]['saved'] = 0;
		    }
		    if (isset($_POST[$item])) {
			if (isset($configs[$item])) {
			    if ($configs[$item]['type'] == 'bool') {
				if ($ret != '') $ret .= ',';
				$ret .= ($_POST[$item] == 'no' ? 'no' : '') . $item;
			    } else {
				if ((($_POST[$item] != $configs[$item]['defvalue']) ||
				    ($_POST[$item] != $configs[$item]['uservalue'])) && ($_POST[$item] != '')) {
				    if ($ret != '') $ret .= ',';
				    $ret .= $item . ':' . $_POST[$item];
				}
			    }
			} else {
			    /* unknown option */
			    if ($ret != '') $ret .= ',';
			    $ret .= $item;
			}
			$configs[$item]['saved'] = 1;
		    }
		}
	}
	return (($ret != '') ? 'OPTIONS='.$ret : NULL);
}/*}}}*/


function parse_line($line, $authoritative)
{
    global $option_menucolor;
    global $option_statuscolor;
    global $option_msgtype;
    global $option_ape;
    global $option_unknown;
    $ret = NULL;
	if (preg_match("/^OPTIONS=/", $line)){
	    $ret = parse_options(preg_replace("/^OPTIONS=/i", "", $line), $authoritative);
	} elseif (preg_match("/^BOULDER=(.*)/i", $line, $matches)){
	    $ret = parse_options("boulder:".$matches[1],0);
	} elseif (preg_match("/^DOGNAME=(.*)/i", $line, $matches)){
	    $ret = parse_options("dogname:".$matches[1],0);
	} elseif (preg_match("/^CATNAME=(.*)/i", $line, $matches)){
	    $ret = parse_options("catname:".$matches[1],0);
	} elseif (preg_match("/^NAME=(.*)/i", $line, $matches)){
	    $ret = parse_options("name:".$matches[1],0);
	} elseif (preg_match("/^(ROLE|CHARACTER)=(.*)/i", $line, $matches)){
	    $ret = parse_options("role:".$matches[2],0);
	} elseif (preg_match("/^MONSTERS=(.*)/i", $line, $matches)){
	    $ret = parse_options("monsters:".$matches[1],0);
	} elseif (preg_match("/^WARNINGS=(.*)/i", $line, $matches)){
	    $ret = parse_options("warnings:".$matches[1],0);
	} elseif ($option_menucolor->match_line($line)) {
	    $ret = $option_menucolor->parse_option($line, $authoritative);
	} elseif ($option_statuscolor->match_line($line)) {
	    $ret = $option_statuscolor->parse_option($line, $authoritative);
	} elseif ($option_msgtype->match_line($line)) {
	    $ret = $option_msgtype->parse_option($line, $authoritative);
	} elseif ($option_ape->match_line($line)) {
	    $ret = $option_ape->parse_option($line, $authoritative);
	} elseif ($option_unknown->match_line($line)) {
	    $ret = $option_unknown->parse_option($line, $authoritative);
	} else {
	    die('Line parsing error.<br>'); // Should not happen, ever.
	}
	return $ret;
}/*}}}*/

function read_file($filename, $authoritative)
{
    $data = file($filename);
    $ret = '';
    foreach ($data as $line) {
	$l = '';
	$line = preg_replace("/\n/", "", $line);
	$oline = $line;
	if (preg_match('/^#.*$/', $line)) {
	    $comment = $line;
	    $line = '';
	} else if (!$authoritative && preg_match('/^(.*?)(\s*#.*)$/', $line, $matches)) {
	    if (isset($matches[2])) {
		$line = $matches[1];
		$comment = $matches[2];
	    } else {
		$line = '';
		$comment = $matches[1];
	    }
	} else {
	    $comment = '';
	}
	if (preg_match('/^(.*)( *)$/', $line, $matches)) {
	    $line = $matches[1];
	    $comment = $matches[2].$comment;
	}
	$tmp = '';
	if (!preg_match("/^\s*$/", $line))
	    $tmp = parse_line($line, $authoritative);
	if ($tmp != '' && $tmp != NULL)
	    $l .= $tmp;
	if ($comment != '')
	    $l .= $comment;
	if ($l != '' || $oline == '')
	    $l .= "\n";
	$ret .= $l;
    }
    return $ret;
}

function read_values($filename)
{
	global $values;
	$data = file($filename);
	foreach ($data as $line) {
		$line = preg_replace("/#.*$/", "", $line);
		$line = preg_replace("/\n/", "", $line);
		if (!preg_match("/^\s*$/", $line)){
			$items = explode(':', $line);
			$item=strtolower($items[0]);
			$items = explode(';', $items[1]);
			if (isset($items[1])) {
				$x=0;
				$list=array();
				while ($x < sizeof($items)){
					$list[] = explode(',', $items[$x]);
					$x++;
				}
				$values[$item]=$list;
			} else {
				$items = explode(',', $items[0]);
				$values[$item]=$items;
			}
		}
	}
}

function read_helptxt($filename)
{
    $helptxt = array();
    $data = file($filename);
    foreach ($data as $line) {
	$items = explode('|', $line, 2);
	if (!isset($items[1])) continue;
	$helptxt[$items[0]] = $items[1];
    }
    return $helptxt;
}

$tablerow = 0;

function output_field($config){/*{{{*/
	global $values;
	global $helptxt;
	global $tablerow;
	if ($config['hidden']) {
	    echo '<TR style="display:none">';
	} else {
	    echo '<TR>';
	}
	echo "<TD><A NAME='".htmlentities($config["name"], ENT_QUOTES)."'></A>".$config["name"]."</TD>";
	$val = (isset($config['uservalue']) ? $config['uservalue'] : $config['defvalue']);
	if (isset($values[$config["name"]])) {

		if (is_array($values[$config["name"]]) && is_array($values[$config["name"]][0]) && ($values[$config["name"]][0][1])) { // evil check
			echo "<TD COLSPAN=2>";
			$x=0;
			if (is_array($values[$config["name"]][0])) {
			    foreach ($values[$config["name"]] as $conf){
				echo "<SELECT NAME=\"".$config["name"]."_$x\">";
				foreach ($conf as $value){
				    echo "<OPTION VALUE=\"".htmlentities($value, ENT_QUOTES)."\" ".(preg_match("/\\".$value."/", $val) ? " selected" : "").">".htmlentities($value, ENT_QUOTES)."</OPTION>";
				}
				echo "</SELECT>";
				$x++;
			    }
			} else {
			    echo "<SELECT NAME=\"".$config["name"]."_$x\">";
			    foreach ($values[$config["name"]] as $conf){
				echo "<OPTION VALUE=\"".htmlentities($value, ENT_QUOTES)."\" ".(preg_match("/\\".$conf."/", $val) ? " selected" : "").">".htmlentities($conf, ENT_QUOTES)."</OPTION>";
			    }
			    echo "</SELECT>";
			    $x++;
			}
			echo "</TD>";
		} else {
			echo "<TD COLSPAN=2 WIDTH=25%>";
			echo "<SELECT NAME=\"".$config["name"]."\">";
			foreach ($values[$config["name"]] as $value){
			    echo '<OPTION VALUE="'.htmlentities($value, ENT_QUOTES).'"';
			    if (($value != '') && ($val != '') && (substr($value, 0, strlen($val)) == $val)) print ' selected';
			    print '>'.htmlentities($value, ENT_QUOTES).'</OPTION>';
			}
			echo "</SELECT>";
			echo "</TD>";
		}
	} else {
		if ($config["type"]=="bool"){
			echo "<TD><LABEL class='bool yes'><INPUT TYPE=\"radio\" NAME=\"".$config["name"]."\"";
			echo ' ID="'.$config['name'].'_yes"';
			$kd = 'ba();';
			if (isset($config['depends'])) {
			    foreach ($config['depends'] as $tmpd) {
				$kd .= 'chkinput_depend(\''.$tmpd.'\');';
			    }
			}
			echo ' onClick="'.$kd.'" ';
			echo " VALUE=\"yes\"".($val == "yes" ? " checked" : "")."> yes</LABEL></TD>";
			echo "<TD><LABEL class='bool no'><INPUT TYPE=\"radio\" NAME=\"".$config["name"]."\"";
			echo ' ID="'.$config['name'].'_no"';
			echo ' onClick="ba();" ';
			echo " VALUE=\"no\"".($val == "no" ? " checked" : "")."> no</LABEL></TD>";
		} else {
			echo "<TD colspan=\"2\"><INPUT TYPE=\"TEXT\" NAME=\"".$config["name"]."\"";
			echo ' id="'.$config['name'].'_input"';
			$len = strlen($val);
			if (isset($config['maxlen'])) echo ' SIZE="'.$config['maxlen'].'"';
			else if ($len > 0) echo ' SIZE="'.$len.'"';
			$kd = '';
			if (isset($config['minlen']) || isset($config['maxlen']))
			    $kd .= ';chkinput_length(this,'.(isset($config['minlen']) ? $config['minlen'] : -1).','.(isset($config['maxlen']) ? $config['maxlen'] : -1).','.$config['escapebackslash'].')';
			if (isset($config['allowchars']))
			    $kd .= ';chkinput_chars(this,\''.$config['allowchars'].'\')';
			if ($kd != '') print ' onKeydown="'.$kd.'" onKeyup="'.$kd.'"';
			echo " VALUE=\"".htmlentities($val, ENT_QUOTES)."\">";
			if (isset($config['expandcharlist'])) {
			    echo '<span class="expand_charlist" id="expand_charlist_btn_'.$config['name'].'" onclick=\'expand_char_list("'.$config['name'].'","'.$config['expandcharlist'].'")\' title="Expand this list">[+]</span><span id="expand_charlist_'.$config['name'].'" class="expanded_charlist"></span>';
			}
			echo "</TD>";
		}
	}
	echo "<TD>".(isset($helptxt[$config["name"]]) ? $helptxt[$config["name"]] : '')."</TD>";
	echo "</TR>\n";
}/*}}}*/




$real_username = $_SESSION['username'];

$helptxt = read_helptxt('help');
read_values('values');
read_file('defaults',1);
read_extra_settings('extrasettings');

if (isset($_POST['action'])) {
	$udata = read_file(plr_rcfile($real_username,'nh343'), 0);
	$tmpd = get_unsaved_options();
	if ($tmpd) $udata .= $tmpd;

	$location = '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	$headstr = '# Generated by '.$location;
	if (strpos($udata, $headstr) === FALSE) $udata = $headstr . "\n" . $udata;
	if (substr($udata, -1) != "\n") $udata .= "\n";


	$fd=fopen(plr_rcfile($real_username,'nh343'), "w");
	fwrite($fd, $udata);
	fclose($fd);
	header('Location: '.$location);
	exit;
}


html_top(array('title'=>$nh_title_servername." - NetHack WebConf",
	       'xtracss'=>'nethackrc.css'));
print '<H1>NetHack WebConf for nethack.alt.org</H1>';

print '<SCRIPT type="text/javascript" src="nethackrc.js"></SCRIPT>';


function read_extra_settings($file)
{
    global $configs;
    global $synonym_opts;
    $data = file($file);
    foreach ($data as $line) {
	if (preg_match('/^\s*#/', $line)) continue;
	if (preg_match('/^SYNONYM=(.+):(.+)$/', $line, $match)) {
	    $opt = $match[1];
	    $val = $match[2];
	    $synonym_opts[$opt] = $val;
	} else if (preg_match('/^LENGTHS=(.+):([0-9]+),([0-9]+),([01])$/', $line, $match)) {
	    $opt = $match[1];
	    $min = $match[2];
	    $max = $match[3];
	    $backs = $match[4];
	    $configs[$opt]['minlen'] = $min;
	    $configs[$opt]['maxlen'] = $max;
	    $configs[$opt]['escapebackslash'] = $backs;
	} else if (preg_match('/^ALLOWCHARS=(.+):(.*)$/', $line, $match)) {
	    $opt = $match[1];
	    $chr = $match[2];
	    $configs[$opt]['allowchars'] = $chr;
	} else if (preg_match('/^DEPENDS=(.+):(.*)$/', $line, $match)) {
	    $opt = $match[1];
	    $dep = explode(',', $match[2]);
	    $configs[$opt]['depends'] = $dep;
	} else if (preg_match('/^EXPANDCHARLIST=(.+):(.*)$/', $line, $match)) {
	    $opt = $match[1];
	    $dep = $match[2];
	    $configs[$opt]['expandcharlist'] = $dep;
	}
    }
}


if (!isset($_POST['action'])) {

   $user_rc_file = plr_rcfile($real_username,'nh343');

	if (!is_readable($user_rc_file)) {
		die("Cannot read file ".$user_rc_file);
	} else if (!is_writable($user_rc_file)) {
		die("<p>Cannot write file ".$user_rc_file."\n<p>If you created your account within the last hour, please try again later. If not, then something is wrong...");
	} else {
		read_file($user_rc_file,0);
	}
	echo '<FORM METHOD="POST" action="'.phpself_querystr().'">';
	echo '<INPUT TYPE="HIDDEN" NAME="action" VALUE="save">';
	echo '<H3>Editing the config file for user <EM>'.$real_username.'</EM></H3>';
	//echo "<B>WARNING:</B> Pressing the <EM>SAVE</EM>-button will remove all comments and unrecognized lines from the config file.<P>";
	/*echo "<B>NOTE:</B> If you get warnings that the changes could not be written, please wait an hour.<P>";*/

        echo '<P>You can also use the <a href="nhrc_edit.php">RC Edit</a>, which allows better control over the config file contents.';

	echo "<P>Your <A HREF=\"".plr_rcfile_url($real_username,'nh343')."\">current config file</A>.";
	echo ' (The default config file can be <A HREF="https://alt.org/nethack/default.nh343rc">seen here</A>.)';

	echo "<P>For more help, see <A HREF=\"http://nethack.org/v343/Guidebook.html#_TOCentry_43\">the Options-section in The Guidebook</A>";
	echo ' or see <a href="https://nethackwiki.com/wiki/Options">this page on the NetHack Wiki</a>.';

	echo '<p>Go to <a href="#s-menucolors">Menucolors</a>, <a href="#s-msgtype">Messagetypes</a>, <a href="#s-ape">Autopickup exceptions</a>';
	if ($option_statuscolor->num_options()) print ', <a href="#s-statuscolors">Statuscolors</a>';
	if ($option_unknown->num_options()) print ', <a href="#s-unknown">Unknown options</a>';

	echo '<P><TABLE BORDER=1 WIDTH="100%">';
	echo "<TR><TH colspan=\"4\">Common Options</TH></TR>\n";
	foreach ($configs as $config){
		output_field($config);
	}
	echo "</TABLE>\n";

	$option_menucolor->print_all();

	$option_msgtype->print_all();

	$option_ape->print_all();

	if ($option_statuscolor->num_options()) {
	    $option_statuscolor->print_all();
	}

	if ($option_unknown->num_options()) {
	    $option_unknown->print_all();
	}

	echo '<INPUT TYPE="Submit" VALUE="Save!" ID="savebutton">';
	echo '</FORM>';
}

print "\n".'<SCRIPT type="text/javascript">';
print "<!--\n";
print "window.onLoad=win_loaded();\n";
print "// -->\n";
print "</SCRIPT>\n";

html_bottom();
