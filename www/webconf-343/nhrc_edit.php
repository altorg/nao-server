<?php
/*
error_reporting(E_ALL);
ini_set('display_errors','On');
*/

$use_basic_auth = (isset($_GET['ba']) ? 1 : 0);

session_start();

if (isset($_GET['nh'])) {
   $nhver = $_GET['nh'];
   if ($nhver == 'nh343' || $nhver == 'nh360') {
      $nh_curr_version = $nhver;
   }
}

include "../nhinclude.php";

if (!$use_basic_auth) {
  requires_login('webconf/'.basename($_SERVER['PHP_SELF']));
}

if (!file_exists($nh_dgl_sqlite_db) || !is_readable($nh_dgl_sqlite_db)) {
  echo 'Password file does not exist, or is unreadable.';
  exit;
}
if (!file_exists($nh_rcfile_default) || !is_readable($nh_rcfile_default)) {
  echo 'Default rcfile does not exist, or is unreadable.';
  exit;
}

/*************************************************/

$toparray['title'] = $nh_title_servername." - NetHack RC Edit";

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username'])) {
   $toparray['need_id'] = 'nethack.alt.org webconfig';
}

html_top($toparray);
//echo 'Back to <a href="http://alt.org/nethack/">http://alt.org/nethack/</a><p>';

if (isset($_SESSION['loggedin']) && isset($_SESSION['username'])) {
   $real_username = $_SESSION['username'];
   $real_password = $_SESSION['passwd'];
} else {
   $real_username = dgl_auth_user_username();
   $real_password = NULL;
}

/*print '<pre>'; print_r($_POST); print '</pre>';*/

$get_def_rc = 0;

if (dgl_auth_user($real_username, $real_password)) {
  echo '<H1>Editing the config file for user <EM>'.$real_username.'</EM></H1>';
  if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Save') {
      update_rcfile();
    } else {
      $get_def_rc = 1;
    }
  }
  edit_form($get_def_rc);
} else {
  echo '&nbsp;<P><B>Auth error: Wrong username or password.</B>';
}
html_bottom();

/*************************************************/


function edit_form($get_def_rc = 0)
{
  global $nh_rcfile_default;
  global $real_username;
  $cansave = 1;

  $rcfname = plr_rcfile($real_username,'nh343');

  if ($get_def_rc) {
    $rcfname = $nh_rcfile_default;
  }

  $fp = @fopen($rcfname, "r");
  if (!$fp) {
      $cansave = 0;
      echo '<P>NOTE: You don\'t seem to have an rcfile. <B>You cannot save these changes.</B> You need to log in to nethack.alt.org first!';
      $fp = @fopen($nh_rcfile_default, "r");
  }
  /*echo '<P><B>NOTE:</B> If you get warnings that the changes could not be written, please wait an hour.';*/

  echo '<P>You can also use the <a href="nethackrc.php">WebConf</a>, which should be easier to use.';

  echo '<P>For more help, see <A HREF="http://nethack.org/v343/Guidebook.html#_TOCentry_43">the Options-section in The Guidebook</A>';
  echo ' or see <a href="https://nethackwiki.com/wiki/Options">this page on the NetHack Wiki</a>.';

  echo ' See <a href="https://alt.org/nethack/default.'.$nh_curr_version.'rc">The default config file</a>';

  echo '<P><form method="POST" action="'.phpself_querystr().'">';
  echo '<textarea name="rcdata" rows="24" cols="80">'; fpassthru($fp); echo '</textarea>';
  echo '<br>';
  if ($cansave)
      echo '<input type="submit" value="Save" name="submit">';
  echo '<input type="reset">';
  if (!$get_def_rc)
    echo '<input type="submit" value="Reset to default" name="submit">';
  echo '</form>';
} //edit_form()

function update_rcfile()
{
    global $real_username;
  if (isset($_POST['submit'])) {
    $rcdata = $_POST['rcdata'];
    $rcdata = str_replace("\r", "", $rcdata);

    $rcfname = plr_rcfile($real_username,'nh343');

    $fp = @fopen($rcfname, "w");
    if (!$fp) {
      echo '<P><em>Could not write rc file. Sorry.</em>';
      $fperms = fileperms($rcfname);
      if (!($fperms & 0x0002)) {
        echo '<P><em>EMail nethack@alt.org about this.</em>';
      }
    } else {
      $rcdata = "\n".$rcdata;  // what eats the first empty line away?
      if (fwrite($fp, $rcdata) === FALSE) {
        echo '<P><em>There was some error when writing the rc file. Sorry.</em>';
      } else {
        echo '<P><em>Your rc file was saved.</em>';
      }
      fclose($fp);
    }
  }
} //update_rcfile()
