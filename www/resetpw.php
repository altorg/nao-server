<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

include 'nhinclude.php';

$magic_string = 'SOMETHING';

nh_html_top('Reset password');
print '<H1>Reset your password</H1>';
print '<P>';

$method = 0;

if (isset($_GET['uin']) && isset($_GET['name'])) {
    $nick = trim($_GET['name']);
    $uin = trim($_GET['uin']);
    $method = 1;
}

if (isset($_POST['submit']) && isset($_POST['nick']) && isset($_POST['email'])) {
    $nick = trim($_POST['nick']);
    $email = trim($_POST['email']);
    $method = 2;
}

if (!isset($_GET['debug'])) $_GET['debug'] = 0;

function mk_random_string($len)
{
	$validchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$ret = '';
	for ($i = 0; $i < $len; $i++) {
	    $ret .= substr($validchars, rand(0, strlen($validchars)), 1);
	}
	return $ret;
}

switch ($method) {
default:
case 0: /* just show the page */
    input_form();
    break;

case 1: /* we have uin, check it and change passwd */
     if (check_uin($nick, $uin)) {
        $newpass = mk_random_string(6);
        change_password($nick,$newpass);
	print '<P>Your new password is <b>'.$newpass.'</b>';
     } else {
        print '<P>Wrong username and/or UIN, or UIN has expired.';
     }
    break;

case 2: /* player has asked for reset, create uin and email */
    if (check_nick_email_validity($nick, $email)) {
	$uin = create_uin($nick, $email);
	email_uin_user($email, $nick, $uin);
        print '<P>You will now receive an email telling you what to do.';
    } else {
       print '<p>That is not a valid email address, or it does not match the username.';
       input_form();
    }
    break;

}

nh_html_bottom();



function input_form()
{
    print '<form name="userdata" method="POST" action="'.phpself_querystr().'">';
    print '<TABLE>';
    print '<TR><TD>Username</TD><TD>'.mk_inputfield('text', 'nick').'</TD></TR>';
    print '<TR><TD>EMail</TD><TD>'.mk_inputfield('text', 'email').'</TD></TR>';
    print '<TR><TD>&nbsp;</TD><TD>'.mk_inputfield('submit', 'submit', 'Submit').'</TD></TR>';
    print '</TABLE>';
    print '</form>';
}

function send_command($fp, $out)
{
    fputs($fp, $out."\r\n");
    //    stream_set_timeout($fp, 4);
    $s = fgets($fp, 1024);
    return $s;
}

function validate_email($email)
{
    $mailparts=explode("@",$email);
    $hostname = $mailparts[1];

    // validate email address syntax
    $exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
    $b_valid_syntax = preg_match("/".$exp."/i", $email);

    if ($_GET['debug']) print '1';

    if (!$b_valid_syntax) return 0;

    if ($_GET['debug']) print '1';

    // get mx addresses by getmxrr
    $b_mx_avail=getmxrr( $hostname, $mx_records, $mx_weight );
    $b_server_found=0;

    if ($_GET['debug']) {
	print $b_mx_avail;
	print_r($mx_records);
	print count($mx_records);
    }

    if($b_valid_syntax && $b_mx_avail){
	// copy mx records and weight into array $mxs
	$mxs=array();

	for($i=0;$i<count($mx_records);$i++){
	    while (isset($mxs[$mx_weight[$i]])) $mx_weight[$i]++;
	    $mxs[$mx_weight[$i]]=$mx_records[$i];
	}

	if ($_GET['debug']) print_r($mxs);
	if ($_GET['debug']) print_r($mx_weight);

	// sort array mxs to get servers with highest prio
	ksort ($mxs, SORT_NUMERIC );
	reset ($mxs);

	if ($_GET['debug']) print_r($mxs);

	while (list ($mx_weight, $mx_host) = each ($mxs) ) {
	    if($b_server_found == 0){

		if ($_GET['debug']) print "<br>".$mx_host."<br>";

		//try connection on port 25
		$fp = @fsockopen($mx_host,25, $errno, $errstr, 4);
		if($fp){
		    $ms_resp="";

		    $xtim = time();
		    do {
			$tmp_resp = @fgets($fp, 256);
			$ctim = time();
			if ($_GET['debug']) print '<br>tmp_resp='.$tmp_resp;
		    } while ((substr($tmp_resp, 0, 3) != '220') || ($ctim > ($xtim + 5)));


		    // say HELO to mailserver
		    $ms_resp .= send_command($fp, "HELO nethack.alt.org");

		    // initialize sending mail
		    $ms_resp .= send_command($fp, "MAIL FROM:<nethack@alt.org>");

		    // try receipent address, will return 250 when ok..
		    $rcpt_text = send_command($fp, "RCPT TO:<".$email.">");
		    $ms_resp .= $rcpt_text;

		    if ($_GET['debug']) print '<br>ms_resp='.$ms_resp;

		    if (substr($rcpt_text, 0, 3) == "250")
			$b_server_found = 1;


		    // quit mail server connection
		    $ms_resp .= send_command($fp, "QUIT");

		    fclose($fp);
		}
	    }
	}
    }
    if ($_GET['debug']) print $b_server_found;

    return $b_server_found;
}

function check_nick_email_validity($nick, $email)
{
    global $nh_dgl_sqlite_db;

    $nick = strtolower(trim($nick));
    $email = trim(strtolower($email));

    if (!isset($nick) || strlen($nick) < 1) return 0;
    if (!isset($email) || strlen($email) < 1) return 0;

    if (!preg_match('/^[a-zA-Z0-9]+$/', $nick)) return 0;
    if (preg_match('/[\'" ]/', $email)) return 0;

    $ret = array();

    $db = new PDO("sqlite:".$nh_dgl_sqlite_db);
    $dat = $db->query("select * from dglusers where lower(username)='".$nick."' and email='".$email."'")->fetch();
    $db = null;

    if ($dat !== FALSE) {
	if (validate_email($email))
	    return 1;
    }
    return 0;
}

function create_uin($nick, $email)
{
    global $nh_dgl_sqlite_db;
    global $magic_string;
    $t = floor(time() / 86400); /* 24 * 60 * 60 */

    $nick = strtolower(trim($nick));

    $db = new PDO("sqlite:".$nh_dgl_sqlite_db);
    $dat = $db->query("select * from dglusers where lower(username)='".$nick."' and email='".$email."'")->fetch();
    $db = null;

    return md5($nick . $email . $t . $dat['password'] . $magic_string);
}

function check_uin($nick, $uin)
{
    global $nh_dgl_sqlite_db;
    if (!isset($nick) || strlen($nick) < 1) return 0;
    if (!preg_match('/^[a-zA-Z0-9]+$/', $nick)) return 0;

    $nick = strtolower(trim($nick));

    $db = new PDO("sqlite:".$nh_dgl_sqlite_db);
    $dat = $db->query("select * from dglusers where lower(username)='".$nick."'")->fetch();
    $db = null;

    if ($dat !== FALSE) {
	$realuin = create_uin($nick, $dat['email']);
	if ($realuin == $uin) {
	   return 1;
	}
    }
    return 0;

}

function change_password($nick, $passwd)
{
    global $nh_dgl_sqlite_db;
    if (!isset($nick) || strlen($nick) < 1) return;
    if (!preg_match('/^[a-zA-Z0-9]+$/', $nick)) return;

    $nick = strtolower(trim($nick));

    $db = new PDO("sqlite:".$nh_dgl_sqlite_db);

    $passwd_crypt = crypt($passwd, substr($passwd, 0, 2));
    //$passwd_crypt = $passwd;

    $sql = "update dglusers set password='".$passwd_crypt."' where lower(username)='".$nick."'";
    $db->query($sql);

    /*
    if (isset($_GET['debugx']) && $_GET['debugx'] == '1') {
       print_r($db->errorInfo()); 
    }
    */

    $db = null;
}

function email_uin_user($email, $nick, $uin)
{
    global $NAO_URL_ROOT, $nh_server_fqdn, $nh_server_nickname;
    $subject = $nh_server_nickname . ' Password Recovery';

    $reseturl = $NAO_URL_ROOT.'/resetpw.php?name='.$nick.'&uin='.$uin;
    $server = $nh_server_fqdn;

$text = "Hello, $nick.

You have requested a password change on $server
To reset your password, please visit

$reseturl

";

    $from = 'From: nethack@alt.org';

    mail($email, $subject, $text, $from);
}


