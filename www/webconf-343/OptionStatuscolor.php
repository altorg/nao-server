<?php

class StatuscolorOption {
    var $statuscolor_index = 0;
    var $statuscolor = array();
    var $statuscolor_values = array('black', 'red', 'green', 'brown', 'blue', 'magenta', 'cyan', 'gray', 'orange', 'lightgreen', 'yellow', 'lightblue', 'lightmagenta', 'lightcyan', 'white');
    var $statuscolor_httpcolors = array('black' => '#000000',
				      'red' => '#aa0000',
				      'green' => '#00aa00',
				      'brown' => '#aa5500',
				      'blue' => '#0000aa',
				      'magenta' => '#aa00aa',
				      'cyan' => '#00aaaa',
				      'gray' => '#aaaaaa',
				      'orange' => '#ff5555',
				      'lightgreen' => '#55ff55',
				      'yellow' => '#ffff55',
				      'lightblue' => '#5555ff',
				      'lightmagenta' => '#ff55ff',
				      'lightcyan' => '#55ffff',
				      'white' => '#ffffff');
    var $statuscolor_mods = array('none', 'bold', 'dim', 'underline', 'blink', 'inverse');

    function StatuscolorOption()
    {
	$this->reset();
    }

    function num_options()
    {
	return count($this->statuscolor);
    }

    function reset()
    {
	$this->statuscolor_index = 0;
	$this->statuscolor = array();
    }

    function get_post()
    {
	$this->statuscolor_index++;
	$i = $this->statuscolor_index;
	if (isset($_POST['statuscolortext_'.$i])) {
	    $removed = $_POST['statuscolor_rm_'.$i];
	    $line = '';
	    $tmpmc = $_POST['statuscolortext_'.$i];
	    if ($tmpmc != '') {
		$line = ''.$tmpmc.':'.$_POST['statuscolor_'.$i];
		if (isset($_POST['statuscolor_'.$i.'_mod']) && ($_POST['statuscolor_'.$i.'_mod'] != ''))
		    $line .= '&'.$_POST['statuscolor_'.$i.'_mod'];
	    }
	    unset($_POST['statuscolortext_'.$i]);
	    unset($_POST['statuscolor_'.$i]);
	    unset($_POST['statuscolor_rm_'.$i]);
	    unset($_POST['statuscolor_'.$i.'_mod']);
	    if ($line != '')
		return ($removed ? '#' : '').'STATUSCOLOR=' . $line;
	}
	return NULL;
    }

    function get_all_posts()
    {
	$postmc = '';
	do {
	    $tmpmc = $this->get_post();
	    if ($tmpmc)
		$postmc .= "\n".$tmpmc;
	} while ($tmpmc);
	if ($postmc != '')
	    $postmc .= "\n";
	return $postmc;
    }

    function match_line($line)
    {
	return (preg_match("/^STATUSCOLOR=/i", $line));
    }

    function parse_option($line, $authoritative)
    {
	if ($this->match_line($line)) {
	    $line = preg_replace("/^STATUSCOLOR=/i", "", $line);
	} else return '#'.$line;

	$postmc = $this->get_post();
	if ($postmc) return $postmc;

	$parts = explode(',', $line);

	foreach ($parts as $part) {
	    $arr = preg_split('/[=:]/', $part, 2);
	    if (count($arr) == 2) {
		$oper = $arr[0];
		$mod = explode('&', $arr[1]);
		$color = $mod[0];
		$modi = (isset($mod[1]) ? $mod[1] : null);

		if (!in_array($color, $this->statuscolor_values)) {
		    $color = 'gray';
		}

		$item=array();
		$item['text'] = $oper; /* TODO */
		$item['color'] = $color;
		$item['modifier'] = $modi;
		$this->statuscolor[]=$item;
	    }
	}

	$ret = 'STATUSCOLOR=';

	return $ret . $line;
    }

    function print_one($line, $idx)
    {
	global $tablerow;

	echo tr_odd_even($tablerow++);
	echo "<TD>";
	echo '<span class="webconf_js_tools" style="display:none">';
	echo '<a href="javascript:statuscolor_move('.$idx.', -1)" style="text-decoration:none;">&#x21e7;</a>';
	echo '&nbsp;';
	echo '<a href="javascript:statuscolor_move('.$idx.', 1)" style="text-decoration:none;">&#x21e9;</a>';
	echo '&nbsp;';
	echo '<a href="javascript:statuscolor_rm('.$idx.')" style="text-decoration:none;">X</a>';
	echo '&nbsp;';
	echo '</span>';
	echo '<input type="hidden" name="statuscolor_rm_'.$idx.'" id="statuscolor_rm_'.$idx.'" value="0">';
	echo "<INPUT TYPE=\"TEXT\" NAME=\"statuscolortext_".$idx."\" ID=\"statuscolortext_".$idx."\" SIZE=50 VALUE='".htmlentities($line["text"], ENT_QUOTES)."'></TD>";
	echo "<TD><SELECT STYLE=\"background-color:".$this->statuscolor_httpcolors[$line['color']].";\" NAME=\"statuscolor_".$idx."\" ID=\"statuscolor_".$idx."\">";
	foreach ($this->statuscolor_values as $color){
	    echo "<OPTION STYLE=\"background-color:".$this->statuscolor_httpcolors[$color].";\" VALUE=\"$color\" onClick=\"pbg(this,'".$this->statuscolor_httpcolors[$color]."');\"";
	    if ($color == $line['color']) print ' selected';
	    print ">$color</OPTION>";
	}
	echo "</SELECT></TD>";
	echo '<TD>';
	echo '<SELECT NAME="statuscolor_'.$idx.'_mod" ID="statuscolor_'.$idx.'_mod">';
	echo '<OPTION VALUE=""';
	if (!(in_array($line["modifier"], $this->statuscolor_mods))) echo ' selected';
	echo '>&nbsp;</OPTION>';
	foreach ($this->statuscolor_mods as $mod){
	    echo '<OPTION VALUE="'.$mod.'"';
	    if ($line["modifier"] == $mod) {
		echo " selected";
	    }
	    echo '>'.$mod.'</OPTION>';
	}
	echo '</SELECT>';
	echo '</TD>';
	echo "</TR>\n";
    }

    function print_all()
    {
	$x=0;
	echo '<P>';
	echo '<TABLE BORDER=1 WIDTH="100%">';
	echo "<TR><TH colspan=\"7\"><a name='s-statuscolors'></a>Statuscolors</TH></TR>\n";
	/*
	echo "<TR><TD colspan=\"7\">Last matching rule will be used, so place more general
rule first and exceptions to that rule under it.<BR>Regular expressions are allowed.</TD></TR>\n";
	*/
	foreach ($this->statuscolor as $color){
		$x++;
		$this->print_one($color,$x);
	}
	$this->print_one(array('text'=>"", 'color'=>"gray", 'modifier'=>""), ++$x);
	$this->print_one(array('text'=>"", 'color'=>"gray", 'modifier'=>""), ++$x);
	$this->print_one(array('text'=>"", 'color'=>"gray", 'modifier'=>""), ++$x);
	$this->print_one(array('text'=>"", 'color'=>"gray", 'modifier'=>""), ++$x);
	$this->print_one(array('text'=>"", 'color'=>"gray", 'modifier'=>""), ++$x);
	echo '</TABLE>';
    }


}
