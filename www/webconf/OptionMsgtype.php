<?php

class MsgtypeOption {
    var $msgtype_index = 0;
    var $msgtype = array();
    var $msgtype_types = array('hide', 'stop', 'norep', 'show');
    var $msgtype_colors = array('hide' => '#aaaaaa', 'stop' => '#ff5555', 'norep' => '#5555ff', 'show' => '#ffffff');

    function APEOption()
    {
	$this->reset();
    }

    function num_options()
    {
	return count($this->msgtype);
    }

    function reset()
    {
	$this->msgtype_index = 0;
	$this->msgtype = array();
    }

    function get_post()
    {
	$this->msgtype_index++;
	$i = $this->msgtype_index;
	if (isset($_POST['msgtypetext_'.$i])) {
	    $removed = $_POST['msgtype_rm_'.$i];
	    $line = '';
	    $tmpmc = $_POST['msgtypetext_'.$i];
	    if ($tmpmc != '') {
		$line = $_POST['msgtype_'.$i].' "'.$tmpmc.'"';
	    }
	    unset($_POST['msgtypetext_'.($this->msgtype_index)]);
	    unset($_POST['msgtype_'.($this->msgtype_index)]);
	    if ($line != '')
		return ($removed ? '#' : '').'MSGTYPE=' . $line;
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
	return (preg_match("/^MSGTYPE=/i", $line));
    }

    function parse_option($line, $authoritative)
    {
	$postmc = $this->get_post();
	if ($postmc) return $postmc;

	$ret = 'MSGTYPE=';

	if (preg_match('/^MSGTYPE=([a-z]+) "(.*)"$/', $line, $matches)) {
	   if (in_array($matches[1], $this->msgtype_types)) {
	      $this->msgtype[] = array('type'=>$matches[1], 'str'=>$matches[2]);
	      return $ret . $line;
	   }
	}
	return '# ' . $ret . $line;
    }

    function print_one($line, $idx)
    {
	global $tablerow;
	echo tr_odd_even($tablerow++);

	echo "<TD>";
	echo '<span class="webconf_js_tools" style="display:none">';
	echo '<a href="javascript:msgtype_move('.$idx.', -1)" style="text-decoration:none;">&#x21e7;</a>';
	echo '&nbsp;';
	echo '<a href="javascript:msgtype_move('.$idx.', 1)" style="text-decoration:none;">&#x21e9;</a>';
	echo '&nbsp;';
	echo '<a href="javascript:msgtype_rm('.$idx.')" style="text-decoration:none;">X</a>';
	echo '&nbsp;';
	echo '</span>';
	echo '<input type="hidden" name="msgtype_rm_'.$idx.'" id="msgtype_rm_'.$idx.'" value="0">';
	echo "<INPUT TYPE=\"TEXT\" NAME=\"msgtypetext_$idx\" ID=\"msgtypetext_$idx\" SIZE=50 VALUE='".htmlentities($line['str'], ENT_QUOTES)."'></TD>";
	echo "<TD><SELECT NAME=\"msgtype_$idx\" ID=\"msgtype_$idx\" STYLE='background-color:".$this->msgtype_colors[$line['type']].";' >";
	foreach ($this->msgtype_types as $t) {
	    echo "<OPTION STYLE='background-color:".$this->msgtype_colors[$t].";' VALUE=\"$t\"".($t==$line['type'] ? ' selected' : '');
	    print ' onClick="pbg(this, \''.$this->msgtype_colors[$t].'\')"';
	    print ">$t</OPTION>";
	}
	echo "</SELECT></TD>";
	echo "</TR>\n";
    }

    function print_all()
    {
	echo '<P>';
	echo '<TABLE BORDER=1 WIDTH="100%">';
	echo "<TR><TH colspan=\"7\"><a name='s-msgtype'></a>Messagetypes</TH></TR>\n";
	echo "<TR><TD colspan=\"7\">Uses globbing. (Unless <a href='#msgtype_regex'>msgtype_regex</a> is set.)</TD></TR>\n";
	$x = 0;
	foreach ($this->msgtype as $line) {
		$x++;
		$this->print_one($line, $x);
	}
	$this->print_one(array('str'=>'', 'type'=>'show'), ++$x);
	$this->print_one(array('str'=>'', 'type'=>'show'), ++$x);
	$this->print_one(array('str'=>'', 'type'=>'show'), ++$x);
	$this->print_one(array('str'=>'', 'type'=>'show'), ++$x);
	$this->print_one(array('str'=>'', 'type'=>'show'), ++$x);
	echo '</TABLE><P>';
    }


}
