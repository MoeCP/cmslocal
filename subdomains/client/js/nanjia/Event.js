//require nanjia Array, Dom0 level 
NanJia.Event = function(el, eventType, handle) {
	this.lsnrs = [];
	this.El = el;
	this.EventType = eventType;
	this.EventType = 'on' + this.EventType;
	el[this.EventType] = this.CallBack;
	el.event = this;
	if (NanJia.Defined(handle))
	{
		this.AddListener(handle);
	}
}
NanJia$Event = NanJia.Event.prototype;
NanJia$Event.AddListener = function(lsnr) {
    this.lsnrs.append(lsnr, true);
}
NanJia$Event.RemoveListener = function(lsnr){
    this.lsnrs.remove(lsnr);
}
NanJia$Event.Notify = function(e, obj){
	var lsnrs = this.lsnrs;
	for(var i=0;i<lsnrs.length;i++){
		var lsnr=lsnrs[i];
		lsnr.call(this, e, obj);
	}
}
NanJia$Event.CallBack = function(event){
	var e = event || window.event;
	//i have store the event object in el.event;
	//in this place , this is el object.
	this.event.Notify(e, this);
}
//////////////////////////////////////////
//Dom2 event function
NanJia.D2Event = function(el, eventType, handle) {
	this.El = el;
	this.EventType = eventType;
    if (NanJia.Defined(handle))
	{
		this.AddListener(handle);
	}
}
NanJia$D2Event = NanJia.D2Event.prototype;
NanJia$D2Event.AddListener = function(handle) {
	if(window.addEventListener) {
		this.El.addEventListener(this.EventType, handle, false);
	} else { // IE
		this.El.attachEvent('on'+this.EventType, handle);
	}
}
NanJia$D2Event.RemoveListener = function(handle) {
	if (window.removeEventListener)
	{
		this.El.removeEventListener(this.EventType, handle, false);
	} else { //IE
		this.El.detachEvent('on'+this.EventType, handle);
	}
}
NanJia.Events = function(divDomain) {
	this.DivDomain = NanJia.SetVal(divDomain, document);
}
NanJia$Events = NanJia.Events.prototype;
NanJia$Events.AddListenersByTagName = function(name, eventType, handle) {
	objs = NanJia.$$(name, this.DivDomain);
	for (i = 0;i < objs.length ;i++ )
	{
		new NanJia.Event(objs[i], eventType, handle);
	}
}
NanJia$Events.AddListenersByIdNames = function(arr, eventType, handle) {
	for (i = 0;i < arr.length;i++ )
	{
		new NanJia.Event(NanJia.$(arr[i]), eventType, handle);
	}
}
NanJia$Events.AddListenersByIdName = function(idName, eventType, handle) {
	new NanJia.Event(NanJia.$(idName), eventType, handle);
}
NanJia.D2Events = function(divDomain) {
	this.DivDomain = NanJia.SetVal(divDomain, document);
}
NanJia$D2Events = NanJia.Events.prototype;
NanJia$D2Events.AddListenersByTagName = function(name, eventType, handle) {
	objs = NanJia.$$(name, this.DivDomain);
	for (i = 0;i < objs.length ;i++ )
	{
		new NanJia.Event(objs[i], eventType, handle);
	}
}