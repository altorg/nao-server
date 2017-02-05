function pbg(t,c){t.parentNode.style.backgroundColor=c;}

function b1(t){
    t.parentNode.parentNode.className = 'bool_' + t.value + ' ' + (t.checked ? 'checked' : '');
}

function ba(elem){
  if (elem) {
     b1(elem);
     return;
  }
  e = document.forms[0].elements;
  for (i = 0; i < e.length; i++) {
    if (e[i].type == "radio") {
        b1(e[i]);
    }
  }
}

function swap_textinputs(aid,bid)
{
  var a = document.getElementById(aid);
  var b = document.getElementById(bid);
  if (!a || !b) return 0;
  var c = a.value;
  a.value = b.value;
  b.value = c;
  return 1;
}

function swap_selects(aid,bid,bg)
{
  var a = document.getElementById(aid);
  var b = document.getElementById(bid);
  if (!a || !b) return;
  var c = a.selectedIndex;
  a.selectedIndex = b.selectedIndex;
  b.selectedIndex = c;

  if (bg) {
    a.style.backgroundColor = a.options[a.selectedIndex].style.backgroundColor;
    b.style.backgroundColor = b.options[b.selectedIndex].style.backgroundColor;
  }
}

function menucolor_move(idx,dir)
{
  var a = (idx + dir);
  if (!swap_textinputs("menucolortext_" + a, "menucolortext_" + idx)) return;
  swap_selects("menucolor_" + a, "menucolor_" + idx, 1);
  swap_selects("menucolor_" + a + "_mod", "menucolor_" + idx + "_mod");
}

function msgtype_move(idx,dir)
{
  var a = (idx + dir);
  if (!swap_textinputs("msgtypetext_" + a, "msgtypetext_" + idx)) return;
  swap_selects("msgtype_" + a, "msgtype_" + idx, 1);
}

function statuscolor_move(idx,dir)
{
  var a = (idx + dir);
  if (!swap_textinputs("statuscolortext_" + a, "statuscolortext_" + idx)) return;
  swap_selects("statuscolor_" + a, "statuscolor_" + idx, 1);
  swap_selects("statuscolor_" + a + "_mod", "statuscolor_" + idx + "_mod");
}

function menucolor_rm(idx)
{
  var a = document.getElementById("menucolor_rm_" + idx);
  if (!a) return;
  a.value = 1;
  a.parentNode.parentNode.style.display="none";
}

function msgtype_rm(idx)
{
  var a = document.getElementById("msgtype_rm_" + idx);
  if (!a) return;
  a.value = 1;
  a.parentNode.parentNode.style.display="none";
}

function statuscolor_rm(idx)
{
  var a = document.getElementById("statuscolor_rm_" + idx);
  if (!a) return;
  a.value = 1;
  a.parentNode.parentNode.style.display="none";
}

function display_js_tools()
{
  var tmp = document.getElementsByTagName("span");
  for (var i = 0; i < tmp.length; i++) {
     if (tmp[i].className == "webconf_js_tools") {
        tmp[i].style.display="inline";
     }
  }
}


function win_loaded()
{
  ba();
  display_js_tools();
}

function save_button(i)
{
  var b = document.getElementById("savebutton");
  if (!b) return;
  b.disabled = (i == false);
}

function chkinput_length(i,min,max, backslash)
{
    if (!i) return;
    var v = i.value;
    if (backslash == 1)
	v = v.replace(/\\./g, '.'); /* backslashes are escaped */
    if ((min > -1 && (v.length < min)) || (max > -1 && (v.length > max))) {
	i.parentNode.style.backgroundColor = "red";
	save_button(false);
    } else {
	i.parentNode.style.backgroundColor = "transparent";
	save_button(true);
    }
}

function chkinput_chars(i,chr)
{
  if (!i) return;

  r = new RegExp(chr, "");

  if (i.value.match(r)) {
    i.parentNode.style.backgroundColor = "transparent";
    save_button(true);
  } else {
    i.parentNode.style.backgroundColor = "red";
    save_button(false);
  }
}

function chkinput_depend(opt)
{
  var yn = "yes";
  var ny = "no";
  if (opt.substr(0,1) == "!") {
     yn = "no";
     ny = "yes";
     opt = opt.substr(1);
  }
  var d = document.getElementById(opt + "_" + yn);
  if (!d) return;
  d.checked = true;
  ba(d);
  var d = document.getElementById(opt + "_" + ny);
  if (!d) return;
  d.checked = false;
  ba(d);
}

String.prototype.replaceAt=function(index, character) {
    return this.substr(0, index) + character + this.substr(index+character.length);
}

function deslash(s)
{
    return s.replace(/\\\\/g, '\\');
}
function enslash(s)
{
    return s.replace(/\\/g, '\\\\');
}


function update_textinput_singlechar(i, pos, val)
{
    var v = document.getElementById(i+'_input');
    if (!v) return;
    if (val.length != 1) return;

    var dev = deslash(v.value);
    dev = dev.replaceAt(pos, val);
    v.value = enslash(dev);
}

function shrink_char_list(elem)
{
    var e = document.getElementById('expand_charlist_'+elem);
    var v = document.getElementById(elem+'_input');
    if (!e || !v) return;

    e.innerHTML = '';
    v.readOnly = false;

    var btn = document.getElementById('expand_charlist_btn_'+elem);
    if (!btn) return;
    btn.style.display='inline';
}

function expand_char_list(elem,values)
{
    var e = document.getElementById('expand_charlist_'+elem);
    var v = document.getElementById(elem+'_input');
    if (!e || !v) return;

    var s = '';

    s += '<span class="expand_charlist" onclick=\'shrink_char_list("'+elem+'")\'>[-]</span>';

    s += '<table>';

    var valuesarr = values.split(",");

    var dev = deslash(v.value);

    for (var i = 0; i < valuesarr.length; i++) {

        var kd = 'update_textinput_singlechar(\''+elem+'\','+i+',this.value)';
	var iv = dev.substr(i, 1);
	if (iv == '"') iv = '&quot;';

	s+= '<tr>';
	s+= '<td>' + valuesarr[i]+'</td>';
	s+= '<td>';
	s+= '<input type="text" size="1" maxlength="1" ';
        s+= 'onchange="'+kd+'" onkeyup="'+kd+'" ';
        s+= 'value="' + iv + '"></input>';
        s+= '</td>';
	s+= '</tr>';
    }

    s += '</table>';


    e.innerHTML = s;

    v.readOnly = true;

    var btn = document.getElementById('expand_charlist_btn_'+elem);
    if (!btn) return;
    btn.style.display='none';
}