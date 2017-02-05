<?php

class UnknownOption {
    var $unknown_index = 0;
    var $unknown_options = array();

    function UnknownOption()
    {
	$this->reset();
    }

    function num_options()
    {
	return count($this->unknown_options);
    }

    function reset()
    {
	$this->unknown_index = 0;
	$this->unknown_options = array();
    }

    function get_post()
    {
	$this->unknown_index++;
	$i = $this->unknown_index;
	if (isset($_POST['unknownopt_name_'.$i])) {
	    $line = '';
	    $unam = $_POST['unknownopt_name_'.$i];
	    $uval = $_POST['unknownopt_value_'.$i];
	    unset($_POST['unknownopt_name_'.$i]);
	    unset($_POST['unknownopt_value_'.$i]);
	    return $unam . '=' . $uval;
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
	return TRUE;
    }

    function parse_option($line, $authoritative)
    {
	$postmc = $this->get_post();
	if ($postmc) return $postmc;
	$ret = $line;
	$dat = explode('=', $line, 2);
	if (!isset($dat[1])) $dat = explode(':', $line, 2);
	$opt = $dat[0];
	$val = $dat[1];
	if (isset($this->unknown_options[$opt])) {
	    $this->unknown_options[$opt][] = $val;
	} else $this->unknown_options[$opt] = array($val);
	return $ret;
    }

    function print_one($opt)
    {
    }

    function print_all()
    {
	$x = 0;
	echo '<P>';
	echo '<TABLE BORDER=1 WIDTH="100%">';
	echo "<TR><TH colspan=\"7\"><a name='s-unknown'></a>Unknown options</TH></TR>\n";
	echo "<TR><TD colspan=\"7\">This lists options the page cannot handle.</TD></TR>\n";
	foreach ($this->unknown_options as $key => $val) {
	    foreach ($val as $v) {
		$x++;
		$len = strlen($v);
		if ($len < 50) $len = 50;
		print '<tr>';
		print '<td><input type="hidden" name="unknownopt_name_'.$x.'" value="'.htmlentities($key, ENT_QUOTES).'">'.htmlentities($key, ENT_QUOTES).'</td>';
		print '<td><input type="text" name="unknownopt_value_'.$x.'" SIZE="'.$len.'" value="'.htmlentities($v, ENT_QUOTES).'"></td></tr>';
	    }
	}
	echo '</TABLE><P>';
    }


}
