<?php

class MenucolorOption {
    var $menucolor_index = 0;
    var $menucolor = array();
    var $menucolor_values = array('black', 'red', 'green', 'brown', 'blue', 'magenta', 'cyan', 'gray', 'orange', 'lightgreen', 'yellow', 'lightblue', 'lightmagenta', 'lightcyan', 'white');
    var $menucolor_httpcolors = array('black' => '#000000',
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
    var $menucolor_mods = array('none', 'bold', 'dim', 'underline', 'blink', 'inverse');

    function MenucolorOption()
    {
	$this->reset();
    }

    function num_options()
    {
	return count($this->menucolor);
    }

    function reset()
    {
	$this->menucolor_index = 0;
	$this->menucolor = array();
    }

    function get_post()
    {
	$this->menucolor_index++;
	$i = $this->menucolor_index;
	if (isset($_POST['menucolortext_'.$i])) {
	    $removed = $_POST['menucolor_rm_'.$i];
	    $line = '';
	    $tmpmc = $_POST['menucolortext_'.$i];
	    if ($tmpmc != '') {
		$line = '"'.$tmpmc.'"='.$_POST['menucolor_'.$i];
		if (isset($_POST['menucolor_'.$i.'_mod']) && ($_POST['menucolor_'.$i.'_mod'] != ''))
		    $line .= '&'.$_POST['menucolor_'.$i.'_mod'];
	    }
	    unset($_POST['menucolortext_'.$i]);
	    unset($_POST['menucolor_'.$i]);
	    unset($_POST['menucolor_rm_'.$i]);
	    unset($_POST['menucolor_'.$i.'_mod']);
	    if ($line != '')
		return ($removed ? '#' : '').'MENUCOLOR=' . $line;
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
	return (preg_match("/^MENUCOLOR=/i", $line));
    }

    function parse_option($line, $authoritative)
    {
	if ($this->match_line($line)) {
	    $line = preg_replace("/^MENUCOLOR=/i", "", $line);
	} else return '#'.$line;

	$postmc = $this->get_post();
	if ($postmc) return $postmc;

	$items = explode('=', $line);
	$mod = explode('&', isset($items[1]) ? $items[1] : '');
	$modi = (isset($mod[1]) ? $mod[1] : null);
	$ret = 'MENUCOLOR=';

	$item=array();
	$item["text"]=$items[0];
	$item["color"]=trim($mod[0]);
	if (!in_array($item['color'], $this->menucolor_values)) {
	    $item['color'] = 'gray';
	}
	$item["modifier"]=$modi;
	$this->menucolor[]=$item;

	return $ret . $line;
    }

    function print_one($line, $idx)
    {
	global $tablerow;

	$line["text"] = preg_replace("/^\"/", "", $line["text"]);
	$line["text"] = preg_replace("/\"$/", "", $line["text"]);
	echo tr_odd_even($tablerow++);
	echo "<TD>";
	echo '<span class="webconf_js_tools" style="display:none">';
	echo '<a href="javascript:menucolor_move('.$idx.', -1)" style="text-decoration:none;">&#x21e7;</a>';
	echo '&nbsp;';
	echo '<a href="javascript:menucolor_move('.$idx.', 1)" style="text-decoration:none;">&#x21e9;</a>';
	echo '&nbsp;';
	echo '<a href="javascript:menucolor_rm('.$idx.')" style="text-decoration:none;">X</a>';
	echo '&nbsp;';
	echo '</span>';
	echo '<input type="hidden" name="menucolor_rm_'.$idx.'" id="menucolor_rm_'.$idx.'" value="0">';
	echo "<INPUT TYPE=\"TEXT\" NAME=\"menucolortext_".$idx."\" ID=\"menucolortext_".$idx."\" SIZE=50 VALUE='".htmlentities($line["text"], ENT_QUOTES)."'></TD>";
	echo "<TD><SELECT STYLE=\"background-color:".$this->menucolor_httpcolors[$line['color']].";\" NAME=\"menucolor_".$idx."\" ID=\"menucolor_".$idx."\">";
	foreach ($this->menucolor_values as $color){
	    echo "<OPTION STYLE=\"background-color:".$this->menucolor_httpcolors[$color].";\" VALUE=\"$color\" onClick=\"pbg(this,'".$this->menucolor_httpcolors[$color]."');\"";
	    if ($color == $line['color']) print ' selected';
	    print ">$color</OPTION>";
	}
	echo "</SELECT></TD>";
	echo '<TD>';
	echo '<SELECT NAME="menucolor_'.$idx.'_mod" ID="menucolor_'.$idx.'_mod">';
	echo '<OPTION VALUE=""';
	if (!(in_array($line["modifier"], $this->menucolor_mods))) echo ' selected';
	echo '>&nbsp;</OPTION>';
	foreach ($this->menucolor_mods as $mod){
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
	echo "<TR><TH colspan=\"7\"><a name='s-menucolors'></a>Menucolors</TH></TR>\n";
	echo "<TR><TD colspan=\"7\">Last matching rule will be used, so place more general
rule first and exceptions to that rule under it.<BR>Regular expressions are allowed.</TD></TR>\n";
	foreach ($this->menucolor as $color){
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
