NanJia.Calendar = function (EventHandle, userId, M, Y, MClass, TClass, Title, HClass, DWClass, DClass, Border, ShowDiv) {
	this.GetToday();
	M = parseInt(M);
	Y = parseInt(Y);
	if (M <= 0 || Y <= 0 || isNaN(M) || isNaN(Y))
	{
		this.CM = this.TMonth;
		this.CY = this.TYear;
	} else {
		this.CM = M;
		this.CY = Y;
	}
	this.EventHandle = EventHandle;
	this.Title = NanJia.SetVal(Title, "Mark dates you cannot work");
	this.TClass = NanJia.SetVal(TClass, "calendar_title");
	this.MainClass = NanJia.SetVal(MClass, "calendar_main");
    this.HClass = NanJia.SetVal(HClass, "calendar_month");
	this.DWClass = NanJia.SetVal(DWClass, "calendar_daysofweek");
	this.DClass = NanJia.SetVal(DClass, "calendar_days");
	this.Border = NanJia.SetVal(Border, 0);
	this.ShowDiv = NanJia.SetVal(ShowDiv, 'calendar_showdiv');
    this.user_id = NanJia.SetVal(userId, null);
}
NanJia$Calendar = NanJia.Calendar.prototype;
NanJia$Calendar.Current = function() {
	this.GetDateInfo(this.CY, this.CM);
	dmax = this.DataInfo.daymax;
	new NanJia.Calendar.Ajax(this.CY, this.CM, dmax, this);
}
NanJia$Calendar.Today = function() {
	this.CM = this.TMonth;
	this.CY = this.TYear;
	this.GetDateInfo(this.CY, this.CM);
	dmax = this.DataInfo.daymax;
	new NanJia.Calendar.Ajax(this.CY, this.CM, dmax, this);
}
NanJia$Calendar.GetToday = function() {
	var todayDate=new Date();
	this.TDate = todayDate.getDate() + 1; 
	this.TMonth = todayDate.getMonth() + 1;
	this.TYear = todayDate.getFullYear();
}
NanJia$Calendar.NextYear = function() {
	this.CY++;
	this.GetDateInfo(this.CY, this.CM);
	dmax = this.DataInfo.daymax;
	new NanJia.Calendar.Ajax(this.CY, this.CM, dmax, this);
}
NanJia$Calendar.NextMonth = function() {
	if (this.CM == 12)
	{
		this.CM = 1;
		this.CY++;
	} else {
		this.CM++;
	}
	this.GetDateInfo(this.CY, this.CM);
	dmax = this.DataInfo.daymax;
	new NanJia.Calendar.Ajax(this.CY, this.CM, dmax, this);
}
NanJia$Calendar.PrevMonth = function() {
	if (this.CM == 1)
	{
		this.CM = 12;
		this.CY--;
	} else {
		this.CM--;
	}
	this.GetDateInfo(this.CY, this.CM);
	dmax = this.DataInfo.daymax;
	new NanJia.Calendar.Ajax(this.CY, this.CM, dmax, this);
}
NanJia$Calendar.PrevYear = function() {
	this.CY--;
	this.GetDateInfo(this.CY, this.CM);
	dmax = this.DataInfo.daymax;
	new NanJia.Calendar.Ajax(this.CY, this.CM, dmax, this);
}
NanJia$Calendar.GetDateInfo = function(y, m) {
    var mn=['January','February','March','April','May','June','July','August','September','October','November','December'];
	var dim=[31,0,31,30,31,30,31,31,30,31,30,31];
	var oD = new Date(y, m-1, 1);
	oD.od = oD.getDay() + 1;
	var todaydate = new Date();
	var scanfortoday = ( y == todaydate.getFullYear() && m == todaydate.getMonth()+1 ) ? todaydate.getDate() : 0;
	dim[1] = (((oD.getFullYear()%100!=0)&&(oD.getFullYear()%4==0))||(oD.getFullYear()%400==0))?29:28;
	this.DataInfo = {};
	this.DataInfo.month = mn[m-1];
	this.DataInfo.daymax = dim[m-1];
	this.DataInfo.today = scanfortoday;
	this.DataInfo.od = oD.od;
}
NanJia$Calendar.BuildCal = function () {
    m = this.CM, y = this.CY, cM = this.MainClass, cH = this.HClass, cDW = this.DWClass, cD = this.DClass, brdr = this.Border;
	showDiv = this.ShowDiv, dataInfo = this.DataInfo, cT = this.TClass;
	var t = '<div id="'+cM+'" class="'+cM+'">\n<table class="'+cM+'" cols="7" cellpadding="0" border="'+brdr+'" cellspacing="0">\n';
	t += '<tr align="center"><td colspan="7" align="center" class="'+cT+'">'+this.Title+'</td></tr>\n';
	t += '<tr align="center"><td colspan="7" align="center" class="'+cH+'">'+dataInfo.month+' - '+y+'</td></tr>\n';
	t += '<tr align="center" class="calendar_todaytitle" ><td colspan="1"><div id="cal-prev-year" class="calendar_nextprev"><img src="/js/nanjia/prevy.jpg" width="33" height="27" /></div></td>\n';
	t += '<td colspan="1"  align="left"><div id="cal-prev-month" class="calendar_nextprev"><img src="/js/nanjia/prevm.jpg" width="26" height="27" /></div></td>\n';
	t += '<td colspan="3"><div id="cal-today" class="calendar_todaytitle">Today</td>\n';
	t += '<td colspan="1" align="right" ><div id="cal-next-month" class="calendar_nextprev"><img src="/js/nanjia/nextm.jpg" width="26" height="27" /></div></td>\n';
	t += '<td colspan="1"><div id="cal-next-year" class="calendar_nextprev"><img src="/js/nanjia/nexty.jpg" width="33" height="27" /></div></td>\n';
	t += '</tr>\n';
	t += '<tr align="center">\n';
	for(s=0; s<7; s++) {
		t += '<td class="'+cDW+'">'+"SMTWTFS".substr(s, 1)+'</td>\n';
	}
	t += '</tr>\n<tr align="center">\n';
	for(i = 1; i <= 42; i++) {
		var x = ((i - dataInfo.od >= 0) && (i - dataInfo.od < dataInfo.daymax)) ? i-dataInfo.od + 1 : '&nbsp;';
		if (x == dataInfo.today) {
			mon = this.StandarTime(m);
			day = this.StandarTime(x);
			text = '<span class="calendar_today" id="'+y+'-'+mon+'-'+day+'">'+day+'</span>';
		} else {
			if (x == '&nbsp;'){
				text = x;
			} else {
			    mon = this.StandarTime(m);
			    day = this.StandarTime(x);
				//text = '<div class="calendar_days_td"><span class="calendar_nomal" id="'+y+'-'+mon+'-'+day+'">'+day+'</span></div>';
				text = '<span class="calendar_nomal" id="'+y+'-'+mon+'-'+day+'">'+day+'</span>';
			}
		}
		t += '<td class="'+cD+'">'+text+'</td>\n';
		if(((i)%7==0)&&(i<36)) {
			t += '</tr>\n<tr align="center">\n';
		}
	}
    t += '</tr>\n<tr></tr>\n</table>\n</div>\n';
	NanJia.$(showDiv).innerHTML = t;
	idLists = this.IdLists;
	this.HightLight(idLists);
	this.EventHandle.call(this);
	return t;
}
NanJia$Calendar.StandarTime = function(m){
	if (0<m && m<10)
	{
		m = "0"+m;
	}
	return m;
}
NanJia$Calendar.HightLight = function (idLists) {
	for (i = 0; i < idLists.length ;i++)
	{
        // NanJia.$(idLists[i]).insert({before:new Element('div', {'class': 'calendar_del'})});
		NanJia.$(idLists[i]).className += ' calendar_hightlight';
	}
}
NanJia.Calendar.OAjax = new NanJia.XMLHttp();
NanJia.Calendar.Ajax = function(y, m, d_max, obj) {
	url = "/calendar_get_hightlight.php";
	data = [];
	data['y'] = y;
	data['m'] = m;
	data['d_max'] = d_max;
    data['user_id'] = obj.user_id;
	param = NanJia.Calendar.OAjax.toParam(data);
	method = "POST";
	try
	{
		NanJia.Calendar.OAjax.Request(url, param, method, NanJia.Calendar.Ajax.CallBack, obj);
	}
	catch (e)
	{
		NanJia.Alert(e);
	}
}
NanJia.Calendar.Ajax.CallBack = function(objXMLHttp) {
	args = NanJia.XMLHttp.ObjPool.GetInfo(objXMLHttp);
	obj = args[0];
	if (objXMLHttp.readyState == 4) {
		obj.IdLists = eval("("+objXMLHttp.responseText+")");
		obj.BuildCal();
	}
}