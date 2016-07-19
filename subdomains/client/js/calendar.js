Content = {};
Content.Cal = {};
Content.Cal.Event = function() {
	events = new NanJia.Events(NanJia.$('calendar_main'));
	events.AddListenersByTagName('span', 'click', Content.Cal.Event.Save);
	events.AddListenersByIdName('cal-prev-year', 'click', Content.Cal.PrevYear);
	events.AddListenersByIdName('cal-prev-month', 'click', Content.Cal.PrevMonth);
    events.AddListenersByIdName('cal-next-year', 'click', Content.Cal.NextYear);
	events.AddListenersByIdName('cal-next-month', 'click', Content.Cal.NextMonth);
	events.AddListenersByIdName('cal-today', 'click', Content.Cal.Today);
}
Content.Cal.Event.Save = function(e, obj) {
	new Content.Cal.Ajax(obj);
}
Content.Cal.PrevYear = function(e, obj) {
	ca.PrevYear();
}
Content.Cal.PrevMonth = function(e, obj) {
	ca.PrevMonth();
}
Content.Cal.NextYear = function(e, obj) {
	ca.NextYear();
}
Content.Cal.NextMonth = function(e, obj) {
	ca.NextMonth();
}
Content.Cal.Today = function(e, obj) {
	ca.Today();
}
ajax = new NanJia.XMLHttp();
Content.Cal.Ajax = function(obj) {
	url = "/calendar_save.php";
	data = [];
	data['date'] = obj.id;
	data['user_id'] = ca.user_id;
	param = ajax.toParam(data);
	method = "POST";
	try
	{
		ajax.Request(url, param, method, Content.Cal.Ajax.CallBack, obj);
	}
	catch (e)
	{
		NanJia.Alert(e);
	}
}
Content.Cal.Ajax.CallBack = function(objXMLHttp) {
	//alert('Content.Cal.Ajax.CallBack');
	args = NanJia.XMLHttp.ObjPool.GetInfo(objXMLHttp);
	obj = args[0];
	if (objXMLHttp.readyState == 4){
		cssClassName = obj.className;
		cssClassName = cssClassName.trim();
		if (cssClassName.indexOf('calendar_hightlight') != -1)
		{
			obj.className = cssClassName.replace(/(\s)*calendar_hightlight/, '');
		} else {
			obj.className += ' calendar_hightlight';
		}
	}
}