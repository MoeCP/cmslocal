NanJia.XMLHttp = function(){
	this.Response = null;
}
NanJia.XMLHttp.ObjPool = new NanJia.ObjPool();
NanJia$XMLHttp = NanJia.XMLHttp.prototype;
NanJia.XMLHttp.Create = function()
{
	if (window.ActiveXObject){
	    var MSXML = ['MSXML2.XMLHTTP.6.0', 'MSXML2.XMLHTTP.5.0', 'MSXML2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP', 'Microsoft.XMLHTTP'];
		for(var n = 0; n < MSXML.length; n ++){
			try {
				var objXMLHttp = new ActiveXObject(MSXML[n]);
				break;
			} 
			catch(e)
			{
				//NanJia.Alert(e);
			}
		}
	} else {
		var objXMLHttp = new XMLHttpRequest();
	}
	return objXMLHttp;
}
NanJia$XMLHttp.Request = function (url, data, method, callBack)
{
	argu = Array.prototype.slice.call(arguments, 4);
	var objXMLHttp = NanJia.XMLHttp.ObjPool.GetInstance(NanJia.XMLHttp.Create, NanJia.XMLHttp.Compare, argu);
	try {
		if (url.indexOf("?") > 0){
			url += "&randnum=" + Math.random();
		} else {
			url += "?randnum=" + Math.random();
		}
		objXMLHttp.onreadystatechange = function () {
			//alert(objXMLHttp.readyState);
			callBack.call(this, objXMLHttp);
		}
		objXMLHttp.open(method, url, true);
		objXMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');
		objXMLHttp.send(data);
	} catch(e) {
		NanJia.Alert(e);
	}
}
NanJia.XMLHttp.Compare = function(objXMLHttp) {
	return (objXMLHttp.readyState == 4) || (objXMLHttp.readyState == 0);
}
NanJia$XMLHttp.GetText = function(uri, param, method) {
	method = NanJia.SetVal(method, 'GET');
	param = NanJia.SetVal(param, null);
	this.Request(uri, param, method, this.GetTextHandle);
}
NanJia$XMLHttp.GetTextHandle = function (objXMLHttp) {
	if (objXMLHttp.readyState == 4)
	{
		this.Response = objXMLHttp.responseText;
	}
}
NanJia$XMLHttp.Update = function(url, id, method, param) {
	  if (method == null || method == 'undefind') {
		  method = "post";
	  }
	  this.Request(url, param, method, this.DivUpdate, id);
}
NanJia$XMLHttp.DivUpdate = function (obj, id) {
	if (objXMLHttp.readyState == 4)
	{
		NanJia.$(id).innerHTML = this.EvalScripts(obj.responseText);
	}
}
NanJia$XMLHttp.toParam = function(arr) {
	str = "";
	for (key in arr)
	{
		type = NanJia.Type(arr[key]);
		if (type == "string" || type == "number")
		{
			str += "&"+key+"="+arr[key];
		}
	}
	str = str.replace(/^&/, '');
	return str;
}