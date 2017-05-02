<?php

if (isset($_GET['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
}

session_start();

include 'nhinclude.php';

$title = "Login to $nh_server_nickname's website";

if (!isset($_POST['goto']) && isset($_GET['goto']) && preg_match('/^[.a-z]+$/', $_GET['goto'])) $_POST['goto'] = $_GET['goto'];


if (($_SERVER['REQUEST_METHOD'] == "POST") && $_POST['submit']) {
   $usrname = dgl_auth_user_username($_POST['nao_username'], $_POST['nao_password']);
   if (isset($_GET['debug'])) {
       print "<pre>



       print $usrname;
       print ".$_POST['nao_username'].";
       print ".$_POST['nao_password'].";
</pre>";
   }
   if ($usrname) {
      $_SESSION['loggedin'] = 1;
      $_SESSION['username'] = $usrname;
      $_SESSION['passwd'] = $_POST['nao_password']; /* UGH! */
      if (isset($_POST['goto'])) {
	  $s = $_POST['goto'];
	  if (!preg_match('/^http:\/\/alt\.org\//', $_POST['goto'])) $s = 'http://alt.org/nethack/'.$s;
	  simple_redirect($s);
      } else {
	  simple_redirect();
      }
      exit;
   }

} else if (isset($_SESSION['loggedin']) && isset($_POST['goto'])) {
    $s = $_POST['goto'];
    if (!preg_match('/^http:\/\/alt\.org\//', $_POST['goto'])) $s = 'http://alt.org/nethack/'.$s;
    simple_redirect($s);
    exit;
}

if (isset($_SESSION['loggedin'])) {
  $title = "Logged in to $nh_server_nickname's website";
}

nh_html_top($title);

if (isset($_GET['debug'])) {
    print '<pre>'; print_r($_SESSION); print '</pre>';
}
print '<h1>'.$title.'</h1>';

if (isset($_SESSION['loggedin'])) {
   print '<p>Welcome, '.$_SESSION['username'];
   print '<p>';
   print '<a href="logout.php">Log out</a>';
} else {
  print '<form name="login" action="'.phpself_querystr().'" method="POST">';
  print table_caption();
  print mk_tablerow('Username', mk_inputfield('text', 'nao_username'));
  print mk_tablerow('Password', mk_inputfield('password', 'nao_password'));
  print '</table>';
  print mk_inputfield('submit', 'submit', 'Login');
  if (isset($_POST['goto'])) $_GET['goto'] = $_POST['goto'];
  if (isset($_GET['goto'])) {
      print '<input type="hidden" name="goto" value="'.urldecode($_GET['goto']).'">';
  }
  print '</form>';
}

nh_html_bottom();
