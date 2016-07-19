NanJia = {};
NanJia.Version = "1.0";
NanJia.Config = [];
//include a js file.
NanJia.GetIEV = function() {
	try {
		if(navigator.appName.toLowerCase() != "microsoft internet explorer") {
			return false;
		}
		$str = navigator.appVersion.toLowerCase();
		if($str.indexOf("msie") == -1) {
			return   false;
		}
        $regx = /msie\s*(\d)+/im;
        $re = $str.match($regx);
        return $re[1];
	} catch(e) {
		return false;
	}
	return false;   
}
NanJia.Defined = function(obj){
	return (obj != undefined);
}
NanJia.SetVal = function(value, defaultValue)
{
	if (!NanJia.Defined(value))
	{
		return defaultValue;
	} else {
		return value;
	}
}
//don't use NanJia.Set(NanJia.*, value) out of the class.
NanJia.Set = function(name, value) {
	try
	{
		NanJia.Config[name] = value;
	}
	catch (e)
	{
		alert(e.description);
		return false;
	}
	return true;
}
NanJia.Get = function(name) {
	try
	{
		value = NanJia.Config[name];
		return value;
	}
	catch (e)
	{
		alert(e.description);
		return false;
	}
}
NanJia.Type = function(obj){
    if (obj === null) {
		return 'null';
	}
	if (!NanJia.Defined(obj)) {
		return 'undefined';
	}
	if (obj.htmlElement) {
		return 'element';
	}
	var type = typeof obj;
	if (type == "string"){
		return "string";
	}
    if (type == "number"){
		return "number";
	}
	if (type == 'object' && obj.nodeName){
		switch(obj.nodeType){
			case 1: return 'element';
			case 3: return (/\S/).test(obj.nodeValue) ? 'textnode' : 'whitespace';
		}
	}
	if (type == 'object' || type == 'function'){
	    if (typeof obj.length == 'number'){
			if (obj.item) return 'collection';
			if (obj.callee) return 'arguments';
		}
		switch(obj.constructor){
			case Array: return 'array';
			case RegExp: return 'regexp';
			default : return 'object';
		}
	}
};
NanJia.Print = function(obj) {
	alert(obj);
	if (NanJia.Type(obj) == 'object')
	{
		str = "";
		for (key in obj)
		{
			str += obj[key].toString();
		}
		return str;

	} else {
		return obj.toString();
	}
}
NanJia.Alert = function (obj) {
	alert(NanJia.Print(obj));
}
NanJia.$ = function(id, idDomain) {
	idDomain = NanJia.SetVal(idDomain, document);
	return idDomain.getElementById(id);
}
NanJia.$$ = function(name, idDomain) {
	idDomain = NanJia.SetVal(idDomain, document);
	return idDomain.getElementsByTagName(name);
}
NanJia.$S = function(id){
	return NanJia.$(id).options[NanJia.$(id).selectedIndex].value;
}
NanJia.Extend = function(ome, oparnet) {
	ome.prototype = oparnet.prototype;
	ome.prototype.constructor = ome;
}
NanJia.EmptyFunction = function() { }
NanJia.ObjPool = function() {
	this.Pool = [];
	this.Args = [];
}
NanJia$ObjPool = NanJia.ObjPool.prototype;
NanJia$ObjPool.GetInstance = function (createH, compareH, args) {
	for (var i = 0; i < this.Pool.length; i++) {
		if (this.Compare(this.Pool[i], compareH)) {
			this.Args[i] = args;
			return this.Pool[i];
		}
	}
	argu = Array.prototype.slice.call(arguments, 3);
    this.Pool[this.Pool.length] = createH.apply(this, argu);
	this.Args[this.Pool.length-1] = args;
    return this.Pool[this.Pool.length - 1];
}
NanJia$ObjPool.Compare = function(obj, handle) {
	return handle.call(this, obj);
}
NanJia$ObjPool.GetInfo = function(obj) {
	for (var i = 0; i < this.Pool.length; i++) {
		if (this.Pool[i] === obj)
		{
			break;
		}
	}
	if (i == this.Pool.length)
	{
		return [];
	} else {
		return this.Args[i];
	}
}
NanJia.ObjSingle = function () {
	this.Single = null;
}
NanJia$ObjSingle = NanJia.ObjSingle.prototype;
NanJia$ObjSingle.GetInstance = function (handle) {
	if (NanJia.Definded(this.Single))
	{
		return this.Single;
	}
	argu = Array.prototype.slice.call(arguments, 1);
	this.Single = handle.apply(this, argu);
    return this.Single;
}
NanJia.EvalScripts = function(str) {
	ScriptFragment = '<script[^>]*>([\\S\\s]*?)<\/script>';
    var matchAll = new RegExp(ScriptFragment, 'img');
	var matchOne = new RegExp(ScriptFragment, 'im');
    str1 = str.match(matchAll);
	re = "";
	if (str1 == null) {
		return (str);
	}
	for (i = 0; i < str1.length; i++) {
	    v = str1[i];
		re += v.match(matchOne)[1]+"\n";
	}
    eval(re);
	return str.replace(matchAll, '');
}