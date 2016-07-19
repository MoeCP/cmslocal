function serializeElements(elements)
{
	if (typeof(elements) == 'string') {
		elements = [elements];
	}

	var queryComponents = new Array();
	for (var i = 0; i < elements.length; i++) {
		var queryComponent = Form.Element.serialize(elements[i]);
		if (queryComponent)
			queryComponents.push(queryComponent);
    }
    return queryComponents.join('&');
}

function ajaxAction(url)
{
	var div = arguments[1] || '';
	var options = arguments[2] || {};
	var ajaxmethod = arguments[3] || '';
	if (ajaxmethod != 'post')
	{
		ajaxmethod = 'get';
	}
	options = Object.extend({
			method: ajaxmethod,
			onLoading  : loading,
			onComplete : ajaxdone,
			onFailure  : reportError,
			evalScripts:true
		}, options);

	var d = new Date();
	if (url.indexOf('?') == -1) {
		url = url + '?__tmp=' + (d.getTime());
	} else {
		url = url + '&__tmp=' + (d.getTime());
	}

	if (div == '') {
		new Ajax.Request(url, options);
	} else {
		new Ajax.Updater(div, url, options);
	}
        
}

function loading()
{
	$('ajaxloading').style.display = 'block';
}

function ajaxdone(url)
{
	$('ajaxloading').style.display = 'none';
}

function reportError(request)
{
   alert('Sorry. There was an error.');
}

function ajaxSubmit(url, frm, div, ajaxmethod)
{
	if (ajaxmethod != 'post')
	{
		ajaxmethod = 'get';
	}
	var params = Form.serialize(frm);
	var options = arguments[4] || {};
	options.parameters = params;
	ajaxAction(url, div, options, ajaxmethod);
	return false;
}

function closeAjaxWindow() {
    Windows.closeAll();
    return false;
}

function showResponse(origReq) {
	var nav = $(this.nav.contentID);
	var last = nav.lastChild;
	while (last.nodeName != 'DIV') {
		last = last.previousSibling;
	}
	nav.removeChild (last);
	nav.innerHTML += origReq.responseText;

	if (origReq.responseText.indexOf('<div class="endurlbinresult">') == -1) {
		nav.innerHTML += '<div style="background: #AAAAAA;">Fetching ... </div>';
		this.fetching = false;
	} else { //avoid repeat ajax loading when result load completed
		this.fetching = true;
	}
	ajaxdone();
}

function ScrollNav(contentID, url) {
	this.contentID = contentID;
	this.contentObj = $(contentID);
	this.contentObj.nav = this;  // note the current object,it will be used by func checkScroll
	this.fetching = false;
	this.scrollTop = this.contentObj.scrollTop; // note the scrollTop when first start
	this.scrollHeight = this.contentObj.scrollHeight; // note the scrollHeight when first start
	this.url = url;

	this.idx = 0; // page number
	this.checkScroll = function() {

		if(this.nav.scrollTop == this.scrollTop)
			return;
		if (this.fetching)
			return;
		this.nav.scrollTop = this.scrollTop;

		//we add 20 in order to lets user interface flexible.
		if (this.scrollHeight <= parseInt(this.style.height) + this.scrollTop + 20) { //this.scrollHeight == parseInt(this.style.height) + this.scrollTop
			var opts = new Object;
			if (!this.nav.idx)
			{
				this.nav.idx = "2";
			}
			opts.parameters = 'page=' + this.nav.idx;
			opts.onComplete = showResponse.bind(this);

			ajaxAction(this.nav.url, "", opts);
			//var ajax = new Ajax.Request(this.nav.url, opts);
			this.fetching = true;
			this.nav.idx ++;
		}
	}

	this.contentObj.onscroll = this.checkScroll;
}

function changeNav(id, newClass, oldClass) {
	if ($(id).className == oldClass) {
		$(id).className=newClass;
	} else {
		$(id).className=oldClass;
	}
}
