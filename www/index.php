<?php
session_start();
include "nhinclude.php";
nh_html_top("Public NetHack Server");

$moonalt = file_get_contents('moon/pom.txt');
$moonalt = preg_replace("/\n/","", $moonalt);

function total_number_of_games()
{
    $games = parse_xlogfile(null, 'select count(1) as num_games from xlogfile');
    return $games[0]['num_games'];
}


$games = get_maybe_cached('total_number_of_games');

print '<div style="float:right">';
if (isset($_SESSION['loggedin'])) {
  print 'Hello,&nbsp;<b><a href="plr.php" rel="nofollow">'.$_SESSION['username'].'</a></b>.';
} else {
  print '<a href="login.php" rel="nofollow">Login</a>';
}
print '</div>';


print '<H1>Public NetHack server at '.$nh_server_fqdn.' ('.$nh_server_nickname.')</H1>';

?>


<DIV class="leftbar">

<DIV class="newsbar roundborder">
<P align="center"><IMG align="middle" src="moon/pom.jpg" alt="<?php print $moonalt; ?>">
<P class="news" style="color:#929921"><I><?php print $moonalt; ?></I>
<?php print_news(5); ?>
</DIV>

<P>

<DIV class="stats roundborder">
<H2>Stats</H2>
<UL>
 <LI><?php print number_format(get_maybe_cached('n_registered_users')).' registered names.'; ?>

 <LI><?php print number_format($games).' total played games.'; ?>

 <LI><?php print current_players_links(); ?>

</UL>
</DIV>

</DIV>

<!-- <P>
<P style="color:#ff0000"><B>NAO has moved to a new (MUCH faster) server! <A style="color:#ff00ff" href="/nethack/aug2015move.php">[Info on the move and SSH keys]</A></B></P>
</B></P> -->

<DIV class="playnow roundborder">
<P align="center" style="color:#f7ff64"><A href="/nethack/hterm/" target="_blank" style="color:#f7ff64"><B>Play now in browser!</B></A></P>
</DIV>

<DIV class="playnow roundborder">
<P align="center" style="color:#f7ff64"><A href="https://nethackwiki.com/wiki/NetHack" target="_blank" style="color:#f7ff64"><B>Learn about nethack!</B></A> <B>(also <A href="http://www.nethack.org/v360/Guidebook.html" target="_blank" style="color:#f7ff64">here!</A>)</B></P>
</DIV>

<DIV class="index">
<P>To play
<A href="http://www.nethack.org">NetHack</A> on this server, just telnet
to alt.org (on normal port 23 or port 14321) or ssh to nethack@alt.org. 
Consider <A href="http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html">putty</A> on Windows.

<UL>
 <LI>Winners
 <UL>
  <LI><A href="https://alt.org/nethack/top-3.6.0.php"><B>3.6.0 highscores!</B></A>
  <LI><A href="zscore-current.html">Z-scores (3.6.0)</A> - <A href="zscore.html">Z-scores (pre-3.6)</A>
  <LI><A href="mostascensions.html">Most ascensions</A> - <A href="mostascyear.html">(past 365 days)</A> - <A href="ascstreak.html">Streaks</A>
  <LI><A href="lowscoreasc.html">Lowest scoring ascensions</A> - <A href="fastasc.html">Fastest ascensions</A> - <A href="mostconducts.html">Best behaving ascensions</A>
  <LI><A href="fastasc-current.html"><B>Fastest 3.6 ascensions</B></A>
  <LI>High Scores: <A href="top60d.html">Last 60 days</A> - <A href="topyear.html">Last 365 days</A> - <A href="top.php">All time best (all versions)</A>
  <LI><A href="topallclassplayers.html">Top All-around Players</A> by score diversity
  <LI><A href="avg_dlvl.html">Average deepest dungeon level reached</A>
 </UL>
 <LI>Players - <A href="plrlist.php">Player List</A>
 <UL>
  <LI><A href="plr-activity.html">Player activity</A> - <A href="wastedtime.html">Top Timewasters</A>
  <LI><A href="browsettyrec.php">Individual player ttyrec session files</A> (use <A href="trd/">TTYREC decoder</A> to view)
  <LI>Search for <A href="dumplogs.php">Dumplogs</A> or <A href="rcfiles.php">config files</A>
<!--
  <LI>Editing your config file <EM>(Both ask for your login information on nethack.alt.org)</EM>
  <UL>
   <LI><A href="webconf/nethackrc.php">WebConf</A> (Shows options you can change)
   <LI><A href="webconf/nhrc_edit.php">RC Edit</A> (Allows direct editing)
  </UL>
-->
  <LI><A href="webconf/">Edit your config file (current version)</A> - <A href="webconf-343/">(3.4.3)</A>
  <LI><SPAN class="new"><A href="resetpw.php">Reset your password</A></SPAN>
 </UL>
 <LI>Server
 <UL>
  <LI><A href="perday.html">Games finished/high scores per day</A> - <A href="usage/">Usage graph</A>
  <LI><A href="mostrecent.php">Most Recent Games</A>
  <LI><A href="monthlyasc.html">Monthly Ascensions</A>
  <LI>Colored charts of <A href="dailygames_ct.html">all games</A> - <A href="dailyasc_ct.html">ascensions</A>
  <LI><A href="topdeaths.html">Top types of deaths</A> - <A href="topdeaths-w.html">weighted by score</A> - <A href="egregiousdeaths.html">most egregious</A>
  <LI>Score statistics <A href="role-stats.html">by role</A> - <A href="race-stats.html">by race</A>
  <LI><A href="/nethack/petnames.html">Pet- and fruitnames</A> - <A href="/nethack/petnamesplr.html">by player</A>
 </UL>
 <LI>Links/Other Sites
 <UL>
  <LI><A href="http://www.nethack.org/">Official Nethack Site</A>
  <LI><A style="color:#ff00ff" href="https://nethackwiki.com/"><B>NetHack Wiki</B></A>
  <LI><A href="http://www.statslab.cam.ac.uk/~eva/nethack/spoilerlist.html">List of Nethack Spoilers</A> by Eva Myers 
  <LI><A href="http://www.steelypips.org/nethack/">Yet Another Nethack Site</A>
  <LI><A href="https://nethackwiki.com/wiki/Public_server">List of Public Nethack Servers</A> (on the NetHack Wiki)
  <LI><A href="/nethack/dudley/">Dudley's <small>(New, Improved)</small> Dungeon</A> - a NetHack webcomic
<!--  <LI><A href="/nethack/forum/">Discussion forums</A> on this server. -->
  <LI><A href="//nhqdb.alt.org/">Quote database</A>
  <LI>nethack.org download mirror
  <UL>
  <LI><A href="https://s3.amazonaws.com/nethack.org/3.6.0/nethack-360-src.tgz">3.6.0 Source</A>
  <LI><A href="https://s3.amazonaws.com/nethack.org/3.6.0/nethack-360-win-x86.zip">3.6.0 Windows Binary</A>
  <LI><A href="https://s3.amazonaws.com/nethack.org/3.6.0/NetHack-360-mac-Term02.pkg">3.6.0 Mac Binary</A>
  </UL> 
 </UL>
</UL>
</DIV>

<H2>IRC.</H2>
<P>Find many of us in <A href="irc://irc.freenode.org/nethack">#nethack on irc.freenode.org</A>.
(<A href="irc.php">latest chatter</A>)
Our friendly bot <A href="Rodney/">Rodney</A> announces all the embarrassing details
of player deaths (and player achievements) for NetHack 3.6.0 in channel.

<H2>Game Info.</H2>
<P>There are currently two versions of the game you can play on the server:
stock NetHack 3.6.0, and NetHack 3.4.3 <a href="naonh.php">with a few patches</a>.
<a href="https://nethackwiki.com/wiki/Dgamelaunch">dgamelaunch</a>, the game launcher,
will ask you for a login and password, and also allows
registration and editing of custom options files.

<P>Every game is recorded, and available for live viewing by all.
<H2>This Server.</H2>
<P>As of August 2015, this server is a virtual machine existing as an Amazon EC2 c4.large instance in the us-east-1c zone.

<?php
nh_html_bottom();
