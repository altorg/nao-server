<?php

/*
 * print out the html page header
 * data['foo'] can be:
 *  need_id = "domain"
 *  charset = "utf-8" or whatever. "ISO-8859-1" by default.
 *  title   = "alt.org" by default
 *  css     = "css_file_url"
 *  rss     = "rss_file_url"
 *  xtracss = array of extra css files to use. none by default.
 *  focus   = "form.component" gives focus to the form's component
 *  notop   = don't print the top banner
 *  lastmod = output Last-Modified header. lastmod=>timestamp
 *  norobots = output robots meta tag
 *  javascript = output javascript tags
 *  tablesort = output sortable tables javascript tag
 *  bodyclass = output extra classes for body div
 */
function html_top($data = null)
{
  // Ask password if we need it.
  if (isset($data['need_id']) && !isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="'.$data['need_id'].'"');
  }

  $chrset = isset($data['charset']) ? $data['charset'] : 'ISO-8859-1';

  header('Content-type: text/html; charset='.$chrset);

  if (isset($data['lastmod'])) {
      $modtimestr = gmdate("D, d M Y H:i:s", $data['lastmod']) . ' GMT';
      header('Last-Modified: '.$modtimestr);
  }

  print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"';
  print ' "http://www.w3.org/TR/html4/loose.dtd">'."\n";

  print '<HTML>'."\n";
  print '<HEAD>'."\n";
  print '<TITLE>'.(isset($data['title']) ? $data['title'] : "alt.org").'</TITLE>'."\n";

  if (isset($data['norobots'])) {
      print '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">'."\n";
  }

  print '<META http-equiv="Content-Type" content="text/html;charset='.$chrset.'">'."\n";

  print '<link rel="icon" href="/nethack/alt_favicon.png">'."\n";
  print '<link rel="shortcut icon" href="/nethack/alt_favicon.png">'."\n";

  print '<link rel="stylesheet" type="text/css" href="'
	.(isset($data['css']) ? $data['css'] : '/nethack/altorg.css').'">'."\n";

  if (isset($data['xtracss'])) {
    if (!is_array($data['xtracss'])) $data['xtracss'] = array($data['xtracss']);
    foreach ($data['xtracss'] as $css)
      print '<link rel="stylesheet" type="text/css" href="'.$css.'">'."\n";
  }

  if (isset($data['rss'])) {
      print '<link href="'.$data['rss'].'" type="application/rss+xml" rel="alternate" title="Sitewide RSS Feed">'."\n";
  }

  if (isset($data['javascript'])) {
      if (!is_array($data['javascript']))
	  $data['javascript'] = array($data['javascript']);

      foreach ($data['javascript'] as $js) {
	  print '<SCRIPT src="'.$js.'"></SCRIPT>'."\n";
      }
  }

  if (isset($data['tablesort'])) {
      print '<link rel="stylesheet" type="text/css" href="/nethack/tablesort.css">'."\n";
  }

  if (isset($data['focus'])) {
    print '<SCRIPT type="text/javascript">';
    print "<!--\n";
    print "function sf(){document.".$data['focus'].".focus();}\n";
    print "// -->\n";
    print "</SCRIPT>\n";
    print "</HEAD>\n";
    print '<BODY onLoad="sf()">'."\n";
  } else {
    print "</HEAD>\n";
    print "<BODY>\n";
  }

  if (!isset($data['notop'])) {
      print '<DIV class="headerbar">'."\n";
      print '<A class="header" href="/">';
      print '<IMG src="/images/altorgheader.png" class="header" alt="alt.org">';
      print '</A>'."\n";
      print "</DIV>\n";
  }

  if (!isset($data['bodyclass']))
      $data['bodyclass'] = array();
  if (!is_array($data['bodyclass']))
      $data['bodyclass'] = array($data['bodyclass']);
  $data['bodyclass'][] = 'body';

  print '<DIV class="'.join(' ', $data['bodyclass']).'">'."\n";
} // html_top()



function html_bottom($data = null) {
    if (getenv('NAO_SCRIPT_CRONJOB') == '1') {
	print '<P class="lastupdate">This page last updated on '.date('Y-m-d H:i:s').'</P>';
    }
    print "</DIV>\n";
    if (!isset($data['notop'])) {
	print '<DIV id="pagefooter">';
	print '<DIV class="footertxt">All original content on this site is licensed under a <a href="https://creativecommons.org/licenses/by/4.0/">Creative Commons Attribution 4.0 International License</a>.</DIV>'."\n";
	print '<DIV class="footerbar"></DIV>'."\n";
	print '</DIV>';
    }

    if (isset($data['tablesort'])) {
	print '<script src="/nethack/tablesort.uncompressed.js"></script>'."\n";
	print "<script>\n";
	foreach ($data['tablesort'] as $tid) {
	    print 'new Tablesort(document.getElementById("'.$tid.'"));'."\n";
	}
	print "</script>\n";
    }

    print "</BODY>\n</HTML>";
}
