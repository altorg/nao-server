<?php

class APEOption {
    var $ape_index = 0;
    var $autopickup_except = array();

    function APEOption()
    {
	$this->reset();
    }

    function num_options()
    {
	return count($this->autopickup_except);
    }

    function reset()
    {
	$this->ape_index = 0;
	$this->autopickup_except = array();
    }

    function get_post()
    {
	$this->ape_index++;
	$i = $this->ape_index;
	if (isset($_POST['apexcept_'.$i])) {
	    $line = '';
	    $tmpmc = $_POST['apexcept_'.$i];
	    if ($tmpmc != '') {
		$pick = ($_POST['apexcept_type_'.$i] == 'pick' ? '<' : '>');
		$line = '"' . $pick . $tmpmc . '"';
	    }
	    unset($_POST['apexcept_'.$i]);
	    unset($_POST['apexcept_type_'.$i]);
	    if ($line != '')
		return 'AUTOPICKUP_EXCEPTION=' . $line;
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
	return (preg_match("/^AUTOPICKUP_EXCEPTION=/i", $line));
    }

    function parse_option($line, $authoritative)
    {
	$postmc = $this->get_post();
	if ($postmc) return $postmc;

	$ret = $line;
	$line = preg_replace("/^AUTOPICKUP_EXCEPTION=/i", "", $line);
	$this->autopickup_except[] = $line;

	return $ret;
    }

    function print_one($line, $idx)
    {
	global $tablerow;
	echo tr_odd_even($tablerow++);

	$line = preg_replace('/^"/', '', $line);
	$line = preg_replace('/"$/', '', $line);
	$pick = (substr($line, 0, 1) == '<');
	$line = substr($line, 1);

	echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"apexcept_$idx\" SIZE=50 VALUE='".htmlentities($line, ENT_QUOTES)."'></TD>";
	echo "<TD><SELECT NAME=\"apexcept_type_$idx\" style='background-color:".($pick ? '#55ff55' : '#ff5555')."'>";
	echo '<option value="pick"'.($pick ? 'selected' : '').' style="background-color:#55ff55" onClick="pbg(this, \'#55ff55\')">pick up</option>';
	echo '<option value="drop"'.($pick ? '' : 'selected').' style="background-color:#ff5555" onClick="pbg(this, \'#ff5555\')">no pick</option>';
	echo "</SELECT></TD>";
	echo "</TR>\n";
    }

    function print_all()
    {
	echo '<P>';
	echo '<TABLE BORDER=1 WIDTH="100%">';
	echo "<TR><TH colspan=\"7\"><a name='s-ape'></a>Autopickup Exceptions</TH></TR>\n";
	echo "<TR><TD colspan=\"7\">You cannot add exceptions to more general rules: If there are any matching rules, the item will be dropped if any of them specify that.<BR>Uses globbing. (Unless <a href='#apexception_regex'>apexception_regex</a> is set.)</TD></TR>\n";
	$x = 0;
	foreach ($this->autopickup_except as $line) {
		$x++;
		$this->print_one($line, $x);
	}
	$this->print_one('"<"', ++$x);
	$this->print_one('"<"', ++$x);
	$this->print_one('"<"', ++$x);
	$this->print_one('"<"', ++$x);
	$this->print_one('"<"', ++$x);
	echo '</TABLE><P>';

    }


}
