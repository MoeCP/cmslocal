function setRecord(url, return_url, id, params)
{
  var isSet = new Array(false,id);
  var isTrue=showModalDialog(url,window,params);
  if (isTrue)
  {
    if (id == '')
    {
      window.location = return_url;
    } else {
      isSet = isTrue;
      if (isSet[0])
      {
        window.location = return_url + isTrue[1];
      }
    }
  }
}

function openRecord(url,params){
  var isTrue=showModalDialog(url,window,params);
}

function doSubmit(form_name, id_name, id_value, form_refresh_value)
{
  eval("document." + form_name + "." + id_name + ".value = " + id_value);
  form_refresh_value.toString();
  eval("document." + form_name + ".form_refresh.value = '" + form_refresh_value + "'");
  eval("document." + form_name + ".submit()");
}

function deleteSubmit(form_name, id_name, id_value, form_refresh_value, msg)
{
  if (confirm("Do You Delete " + msg + "?"))
  {
    //eval("document." + form_name + "." + id_name + ".value = " + id_value);
    eval("document." + form_name + "." + id_name + ".value = '" + id_value + "'");
    if (form_refresh_value != '')
    {
      form_refresh_value.toString();
      eval("document." + form_name + ".form_refresh.value = '" + form_refresh_value + "'");
    }
    eval("document." + form_name + ".submit()");
  } else {
    return false;
  }
}

function changeUserStatus(form_name, id_name, id_value, form_refresh_value, action, msg)
{
    if (form_refresh_value == 'D')
    {
        msg = "Do You disable " + msg + "?";
    }
    else if (form_refresh_value == 'A')
    {
        msg = "Do You enable " + msg + "?";
    }
  if (confirm(msg))
  {
    eval("document." + form_name + "." + id_name + ".value = '" + id_value + "'");
    if (form_refresh_value != '')
    {
      form_refresh_value.toString();
      eval("document." + form_name + ".form_refresh.value = '" + form_refresh_value + "'");
    }
    if (action != '')
    {
      action.toString();
      eval("document." + form_name + ".operation.value = '" + action + "'");
    }
    eval("document." + form_name + ".submit()");
  } else {
    return false;
  }
}

function sendEmail( form_name, id_name, id_value, operation_name, operation_value )
{

	eval("document." + form_name + "." + id_name + ".value = '" + id_value + "'");
	eval("document." + form_name + "." + operation_name + ".value = '" + operation_value + "'");
	if( id_value > 0)
		eval("document." + form_name + ".submit()");
	else
		return false;
}

/*
 * 此函数是用来实现刷新排列列表
 * function orderBy(url, sort){
 *   sort = (sort == 'ASC')?'DESC':'ASC';
 *   window.location = 'search.php?'+url+'&sort='+sort;
 * }
 */

function changeReturn(url, id)
{
	window.location = url + id;
}

function openWindow(url, params)
{
	var d = new Date();
	window.open(url, 'newwindow' +  (d.getTime()), params);
}

function openLink(url)
{
    window.location.href=url;
}

function addClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var l = p.length;
	for (var i = 0; i < l; i++) {
		if (p[i] == sClassName)
			return;
	}
	p[p.length] = sClassName;
	el.className = p.join(" ").replace( /(^\s+)|(\s+$)/g, "" );
}

function removeClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var np = [];
	var l = p.length;
	var j = 0;
	for (var i = 0; i < l; i++) {
		if (p[i] != sClassName)
			np[j++] = p[i];
	}
	el.className = np.join(" ").replace( /(^\s+)|(\s+$)/g, "" );
}




/////////////////////////////////////////////////////////////////////////


function isEmail(string)
{
  if (string.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]{2,4}$/) != -1) {
    return true;
  } else {
    return false;
  }
}

// isBlank(value) Returns true if value only contains spaces
function isBlank(val)
{
 if(val==null) return true;
 for(var i = 0; i < val.length; i ++) {
  if ((val.charAt(i)!=' ') && (val.charAt(i)!="\t") && (val.charAt(i)!="\n") && (val.charAt(i)!="\r")) {
      return false;
    }
 }
 return true;
}
 
// isInteger(value) Returns true if value contains all digits
function isInteger(val)
{
 if (isBlank(val)) return false;
 
 for(var i = 0; i < val.length; i ++) {
  if(!isDigit(val.charAt(i))) return false;
 }
 return true;
}
 
// isDigit(value) Returns true if value is a 1-character digit
function isDigit(num) {
 if (num.length > 1) return false;
 var string = "1234567890";
 if (string.indexOf(num) != -1) return true;
 return false;
}

function isNumeric(val){
  return(parseFloat(val,10)==(val*1));
}

/* check one object is undefined or not */
function isObjectOrNot(obj)
{
    try {
		if (obj === undefined) return false;
		if (obj === null) return false;
		if (obj == "undefined") return false;
        return true;
    } catch (e) {
		return false;
    }
}
 
function LTrim(str)
{
 if (str==null){return str;}
 for (var i=0; str.charAt(i)==" " || str.charAt(i)=="\n" || str.charAt(i)=="\t" || str.charAt(i)=="\r"; i++);
 return str.substring(i,str.length);
}
 
function RTrim(str)
{
 if (str==null){return str;}
 for (var i=str.length-1; str.charAt(i)==" " || str.charAt(i)=="\n" || str.charAt(i)=="\t" || str.charAt(i)=="\r"; i--);
 return str.substring(0,i+1);
}
 
function Trim(str)
{
 return LTrim(RTrim(str));
}
 
// Replace all '\t' and '\r' in a string
function TRReplace(str)
{
  var re = new RegExp('\t|\r', 'gi');
  var newstr = str.replace(re, '');
 
  return newstr;
}

//是否全选
function checkAll(str, evt)
{
  var a = document.getElementsByName(str);
  var n = a.length;
  //evt = evt ? evt : (window.event ? window.event : null);
  //var e = evt ? evt.srcelement : (window.event ? window.event.srcElement : null);
  var e = document.getElementsByName('Select_All');
  for (var i=0; i<n; i++) {
      //a[i].checked = evt.srcElement.checked;
      if (!a[i].disabled)
      {
          a[i].checked = e[0].checked;
      } 
  }
}

function checkItem(str, f_str, evt)
{
//  //为了能够在firfox上运行；
//  //evt = evt ? evt : (window.event ? window.event : null);
//  var e = evt ? evt.srcelement : (window.event ? window.event.srcElement : null);
//  //var e = evt.srcElement;
//  var all = eval(f_str + str);
//  if (e.checked)
//  {
//    var a = document.getElementsByName(e.name);
//    all.checked = true;
//    for (var i=0; i<a.length; i++)
//    {
//      if (!a[i].checked){ all.checked = false; break;}
//    }
//  }
//  else all.checked = false;
	return false;
}

function checkPostItem(str, post_checkbox_array, checkbox_name, f_str)
{
  var all = eval(f_str + str);
  var checkbox_array = new Array();
  checkbox_array = post_checkbox_array.split(",");
  if (post_checkbox_array != '')
  {
    var a = document.getElementsByName(checkbox_name);
    for (var ca=0; ca<checkbox_array.length; ca++)
    {
      var checkboxid = checkbox_array[ca] - 1;
      a[checkboxid].checked = true;
      all.checked = true;
      for (var i=0; i<a.length; i++)
      {
        if (!a[i].checked){ all.checked = false; break;}
      }
    }
  } else {
    all.checked = false;
  }
}

function getCssValue(node_id, attr_name) {
    return Try.these (
                function() {return eval("document.getElementById('"+node_id+"').currentStyle."+attr_name);},
                function() {return eval("document.defaultView.getComputedStyle(document.getElementById('"+node_id+"'), null).getPropertyValue('"+attr_name+"')");}
               );
}

function removeNotification(row, url)
{
    total = parseInt($('total_notifications').innerHTML);
    $('total_notifications').innerHTML = --total;
    row.remove();
    ajaxAction(url);
}

function changeTemplate(url, tpl_id)
{ 
  if (tpl_id > 0)
  {
      ajaxAction(url + '?tpl_id=' + tpl_id, 'show_result_status');
  }
}

function appendRsToObj(obj,currenobj,total_row,url)
{
	var div = arguments[4] || '';
    
	var options = {asynchronous:false,evalScripts:false,onComplete:getResponse};
    var oajax = ajaxAction(url, div, options);
    responseText = responseText.replace(/&nbsp;<tr>/gi, '<tr>');
    responseText = responseText.replace(/\s+/gi,' ');
    obj.insert({after:responseText});
    currenobj.innerHTML = 'Hide Campaigns';
    // currenobj.setAttribute('onclick', "hideCampaigns(this,$('" + obj.identify() +"'),"+total_row+",'" + url +"','" +div+"');return false;"); only for firefox
    currenobj.onclick = function() {hideCampaigns(this,$(obj.identify()),total_row, url, div);return false;}; // firefox and ie
}

function hideCampaigns(currenobj, obj, total_row, url, div)
{
    var sibilings = obj.nextSiblings();
    
    for (i=0;i< total_row;i++)
    {
        sibilings[i].remove();
    }
    currenobj.innerHTML = 'View Campaigns';
    // currenobj.setAttribute('onclick', 'appendRsToObj($(\'' + obj.identify() +'\'), this,'+total_row+',\'' + url +'\', \''+ div +'\');return false;'); only for firefox
    currenobj.onclick = function(){appendRsToObj($(obj.identify()), this, total_row, url,div);return false;}; // firefox and ie
}

function resetElement() {
    $('report_result').innerHTML = 'I am gonna get updated';
}

function showWindowDialog(url)
{
	var awidth  = arguments[1] || '600';
	var aheight = arguments[2] || '300';
	var atitle	= arguments[3] || 'Add/Edit';

    /*
	var i = parseInt(100*Math.random());
	var mywin = new Window('window_id' + i, {title: atitle, className: "alphacube", width: awidth, height: aheight});
    id = parseInt(id);
	if (id > 0)
	{
		url +=  '&id=' + id;
	}
    */

    var mywin = new Window({className: "mac_os_x", title: atitle, width:awidth, height:aheight,zIndex:9999, recenterAuto:false});
	mywin.setDestroyOnClose();
	mywin.setAjaxContent(url, {method:'get', evalJS:true});
	mywin.showCenter();
}



function addWordCount(ed) {
  if (typeof(ed.id) == "undefined") ed = tinyMCE.activeEditor;
  $('contentdiv').insert({before:'<div id="' + ed.id + '_wordcount" class="wordcount">0 words, 0 characters</div>'});
  return true;
}
