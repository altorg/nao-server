<?php
include 'nhinclude.php';

if (($_SERVER['REQUEST_METHOD'] == "POST") && $_POST['submit']) {
    $cookiestr = make_cookie_str($_POST);
    setcookie('nhdatatableview', $cookiestr, time() + 60*60*24*31*365, '/nethack/', '.alt.org', 0);
    $COOKIE['nhdatatableview'] = $cookiestr;
} else if (isset($_COOKIE['nhdatatableview'])) {
    $cookiestr = $_COOKIE['nhdatatableview'];
} else {
    $cookiestr = 'Score Name Dlvl MaxDlvl HP MaxHP Role Race Gender Align Reason EndDate';
}

$pagetitle='Set your preferences for '.$nh_server_nickname.' website';
nh_html_top($pagetitle);

print '<H1>'.$pagetitle.'</H1>';
print '<BR>&nbsp;';

function make_cookie_str($dat)
{
    global $nh_datsorttype;
    $ret = '';
    foreach ($nh_datsorttype as $key => $val) {
	if ($dat[$key]) $ret .= ' '.$key;
    }
    return trim($ret);
}

function decode_cookie_str($str)
{
    $dat = preg_split("/ /", preg_replace('/ +/', ' ', trim($str)));
    return $dat;
}

function input_form($dat = null)
{
    global $nh_datsorttype;
    echo '<form name="search" method="POST" action="'.phpself_querystr().'">';
    echo '<TABLE>';
    foreach ($nh_datsorttype as $key => $val) {
	print mk_tablerow($key, mk_inputfield('checkbox', $key, in_array($key, $dat)));
    }
    print '</TABLE>';
    print '<br>'.mk_inputfield('submit', 'submit', 'Set');
    echo '</form>';
}


print '<P>Select which fields you want to see when viewing tables that show data directly from the logfile or the record-file.';

input_form(decode_cookie_str($cookiestr));

nh_html_bottom();





