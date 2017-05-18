<?php

function piechart($data, $wid=null, $hei=null)
{
    $showkey = 1;
    $show_values = 1; /* only if showkey. 0=values, 1=percentage */

    $datacount = count($data);

    if (!isset($wid)) $wid = 600;
    if (!isset($hei)) {
      $hei = $wid;
      if ($showkey) $hei += (10*$datacount);
    }

    $total = 0;
    $radius = (min($wid,$hei) / 2) * 0.6;
    $startx = (min($wid,$hei) / 2);
    $starty = (min($wid,$hei) / 2);
    $lastx = $radius;
    $lasty = 0;
    $txtlen = 0;

    $total = array_sum($data);
    arsort($data);

    $ret = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'."\n";
    $ret .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"'."\n";
    $ret .= '"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";

    $ret .= '<svg width="'.$wid.'" height="'.$hei.'" ';
    $ret .= 'xmlns="http://www.w3.org/2000/svg" version="1.1">'."\n";
    /*$ret .= 'xmlns="http://www.w3.org/2000/svg" version="1.1" style="background-color:green;">'."\n";*/

    /*
	function togglehilitedata(evt) {
	  var c = evt.target;
	  var s = c.getAttribute("stroke");
	  if (s == "#e0e0e0")
	    c.setAttribute("stroke", "#000000");
          else
	    c.setAttribute("stroke", "#e0e0e0");
	}

    */

    if ($showkey) {
	$ret .= '
  <script type="text/ecmascript"> <![CDATA[
	function hilitedata(i) {
          var g = document.getElementById("g"+i);
          g.setAttribute("style", "stroke:black");
	}
	function dehilitedata(i) {
          var g = document.getElementById("g"+i);
          g.setAttribute("style", "stroke:none");
	}
  ]]> </script>
';
    }


#    $colors = array('#333399','#339933','#993333','#339966', '#336699', '#663399', '#669933', '#993366', '#996633', '#66CC99', '#6699CC', '#9966CC', '#99CC66', '#CC6699', '#CC9966');

    $colors = array("#aa0000","#00aa00","#aa5500","#0000aa","#aa00aa","#00aaaa","#aaaaaa","#555555",
		    "#ff5555","#55ff55","#ffff55","#5555ff","#ff55ff","#55ffff","#ffffff");
#    $colors = array('red','blue','yellow','magenta','orange','slateblue','slategrey','greenyellow','wheat');
    $bordercolor = '#e0e0e0';


    $seg = 0;
    $i = 0;

    if ($datacount < 2) {
	$ret .= '<circle id="p'.$i.'" cx="'.$startx.'" cy="'.$starty.'" r="'. $radius . '" ';

      $ret .= 'fill="'.$colors[$i].'"';
      $ret .= ' stroke="' . $bordercolor . '" stroke-width="1" stroke-linejoin="round"';

      if ($showkey) {
	  $ret .= ' onmouseover="hilitedata('.$i.')" onmouseout="dehilitedata('.$i.')"';
      }

      $ret .= '/>'."\n";
    } else {


    foreach ($data as $key=>$val) {
      $arc = "0";

      if (strlen($key) > $txtlen) $txtlen = strlen($key);

      $seg = $val/$total * 360 + $seg;

      if (($val/$total * 360) > 180) $arc = "1";

      $radseg = deg2rad($seg);

      $nextx = (cos($radseg) * $radius);
      $nexty = (sin($radseg) * $radius);

      $ret .= '<path id="p'.$i.'" d="M '.$startx.','.$starty.' l '.$lastx.','.(-($lasty)).' a'. $radius . ',' . $radius . ' 0 ' . $arc . ',0 ' .($nextx - $lastx).','.(-($nexty - $lasty)) . ' z" ';

      $ret .= 'fill="'.$colors[$i].'"';
      $ret .= ' stroke="' . $bordercolor . '" stroke-width="1" stroke-linejoin="round"';

      if ($showkey) {
	  $ret .= ' onmouseover="hilitedata('.$i.')" onmouseout="dehilitedata('.$i.')"';
      }

      $ret .= '/>'."\n";

      $lastx = $nextx;
      $lasty = $nexty;
      $i++;
    }

    }

    $seg = 0;
    $i = 0;
    foreach ($data as $key=>$val) {
      $lseg = (($val / 2)/$total) * 360 + $seg;
      $seg = (($val)/$total) * 360 + $seg;
      $radseg = deg2rad($lseg);
      $labelx = round((cos($radseg) * ($radius*1.1)));
      $labely = round((sin($radseg) * ($radius*1.1)));

      $ret .= '<text x="'.($startx + $labelx).'" y="'.($starty - $labely).'" fill="black"';
      if ($startx+$labelx < $startx) $ret .= ' style="text-anchor:end;font-family:monospace"';
      else $ret .= ' style="text-anchor:start;font-family:monospace"';
      $ret .= '>'.$key.'</text>'."\n";
    }

    if ($showkey) {
      $i = 0;
      foreach ($data as $key=>$val) {
	  if ($show_values) $v = number_format($val);
	  else $v = sprintf("%3.2f", round(($val/$total)*100));

	$ret .= '<g id="g'.$i.'">';

	$ret .= '<rect x="10" y="'.($starty+$radius+15*$i).'" height="10" width="10" style="stroke:#000000;fill:'.$colors[$i].';stroke-width:0;"/>';
	$ret .= '<text x="25" y="'.($starty+$radius+15*$i+9).'" fill="black" style="font-family:monospace">';
	$ret .= sprintf("%s (%3.2f%%) %s", $v, ($val/$total)*100, $key);
        $ret .= '</text>';

	$ret .= '</g>';

        $ret .= "\n";

	$i++;
      }
    }

    $ret .= '</svg>';

    return $ret;
}

/*
$dat = array('foo' => 1, 'bar' => 2, 'baz' => 3 , 'qux' => 4, 'zap'=>3, 'mappo'=>2, 'kala'=>1, 'fishhhy'=>3,
	     'moop'=>4, 'asda'=>3, 'ertty'=>3, 'asds'=>1);

header('Content-Type: image/svg+xml');

print piechart($dat);
*/

function bake_pie($dat)
{
    header('Content-Type: image/svg+xml');
    print piechart($dat);
    exit;
}

