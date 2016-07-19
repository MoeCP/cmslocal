// <!-- (c) 1999-2012 SpellChecker.net, Inc.  All rights reserved.  www.WebSpellChecker.net -->
 var WSC = (function(){
var spchver="";
var sp_sh="<#service_host#>";
var schema_url="";
var schema="";
var spp_langs="";
var url="<#sproxy_url#>";
var service_host=sp_sh;
var customerid="1:DDC7r-TByxp-9syVh1-I8t034-mStOd4-XKNY22-0vS2q3-qHCo52-XmOSw3-S8CA72-6rOo5-7B1";
var cust_dic_ids="";
var udn="";
/*~var hide="";*/
var doc_blank="/lf/blank.html";
var doc_initialize = url+"?cmd=script&doc=wsc_core&name=initialize";
var doc_blankform="/lf/blankform.html";
var doc_process="/script/ssrv.cgi";
var is_window_opened=false; 
var formNum=-1;
var ctrlName='';
var isMSIE=(navigator.appName=='Microsoft Internet Explorer');
var isNN=navigator.appName=='Netscape';
var isProxyVer=false;
var botf,cmd,spellWin,ssrv_host,port,ssrv,ssrvname,lang,ctrl,url,force_reload,intlang,ctrlObj,sp_i,sp_doc,sp_time,sp_old_doc_unload,sp_spw_name,sp_spw_params,sp_wurl,sp_asce,sp_iswopn,autoClose;
var sRelaxedDomain = "";
var onCancel = null;
var onFinish = null;
var onClose = null;
var windowTitle = "www.WebSpellChecker.net";

var isCallbackInvokedInSession = false;

/**
* API Function 
* @param {Object} oParams  
* object params:
*	oParams.lang {String} one of allowed lang names
*   oParams.ctrl {Mixed} control id or array of ids
*   oParams.cmd {String} 'spell' or 'grammar' or 'thes'
*	oParams.forceReload
*   oParams.intLang {String} - interface language @see oParams.lang
*   oParams.userDictionaryName {String} - fixed dictionary name
*   oParams.customDictionaryName {Array} - additional custom (customer) dictionary identifiers array
*   oParams.schemaIdentifier {int} - schema identifier
*   oParams.isProxyVer {Boolean} - 
*   oParams.width {int} - window width
*   oParams.height {int} - window height
*   oParams.top {int} - window initial y coortinate
*   oParams.left {int} - window initial x coortinate
*   oParams.title {String} - window title

*   oParams.autoClose {String} - no|yes|nomisspellings
*   oParams.domainName {String} - common domain name (relaxed script solution)
*   oParams.schemaURI{String} - custom CSS url ("")
*   oParams.onCancel {Function} - Callback function
*   oParams.onFinish {Function} - Callback function
*   oParams.onClose {Function} - Callback function
*/

function doSpell(oParams){
	if(typeof(oParams) != 'object' || !oParams.ctrl){
		alert('Wrong initial data');
		return;
	}
	//relaxed scripting solution
	if(typeof(oParams.domainName) == "string" && (/^\s*$/).test(oParams.domainName) == false){
		sRelaxedDomain = oParams.domainName
		document.domain = sRelaxedDomain;
	}	
	mSourceCtrlsNames = oParams.ctrl;
	
	if (oParams.ctrl && typeof oParams.ctrl == "object" && oParams.ctrl.length){
		//spellCheckMultipleInstances(oParams.ctrl, oParams.lang, oParams.schemaIdentifier);
		spellCheckMultipleInstances(oParams);
	}else{
		spellCheckSingleInstance(oParams);
	}
}

/**
* 
*/
function spellCheckSingleInstance(oParams){
	if((isMSIE&&(parseInt (navigator.appVersion)< 4)) || (isNN&&isAppVer('3.0') && !(/safari/i).test(navigator.appVersion))){
		alert('Sorry, currently we do not support your browser.');
		return;
	}
	lang = oParams.lang || "en";
	cmd = oParams.cmd || "spell";
	ctrl = oParams.ctrl || null;
	force_reload = (oParams.forceReload != null)?oParams.forceReload:(true);  
	intlang = oParams.intLang || "";
	/*~hide = oParams.hide || "";	*/
	isProxyVer = (oParams.isProxyVer != null)?(oParams.isProxyVer):isProxyVer;
	udn = oParams.userDictionaryName || udn;
	cust_dic_ids = oParams.customDictionaryName || cust_dic_ids;
	if(cust_dic_ids.constructor &&  cust_dic_ids.constructor.toString().toLowerCase().indexOf("array") != -1){
		cust_dic_ids = cust_dic_ids.join(',');
	}
	
	schema = oParams.schemaIdentifier || schema;
	schema_url = oParams.schemaURI || schema_url;
	autoClose = oParams.autoClose || 'no';

	onCancel = oParams.onCancel || onCancel;
	onFinish = oParams.onFinish || onFinish;
	onClose = oParams.onClose || onClose;

	var wWidth = (parseInt(oParams.width)>0)?(oParams.width):(492);
	var wHeight = (parseInt(oParams.height)>0)?(oParams.height):(440);
	
	var wTop = (parseInt(oParams.top)>=0)?(oParams.top):(null);
	var wLeft = (parseInt(oParams.left)>=0)?(oParams.left):(null);
	windowTitle = oParams.title || windowTitle;
	

	if(ctrl.id=="MTBDummy"){
		ctrlName = ctrl.id;
		ctrlObj = ctrl;
	}else{
		var nObj = document.getElementById(ctrl);
	
		if(nObj == null){
			alert('Source element "' + ctrl + '" not found');
			return;
		}else{
			ctrlName = ctrl;
			ctrlObj = nObj;
		}
	}
	
	if(/^\s*$/.test(getSourceObjectContent(ctrlObj,true))){
		alert('Nothing to check.');
		return;
	}
	
	sp_time = "";

	if(isProxyVer){
		if(service_host==sp_sh){

			if((/:\/\/(.*?)\//).test(service_host)){
				ssrv_host = RegExp.$1
			}else{
				ssrv_host = '';
			}
			/*~}*/
			(/(^.*\/)(.*)/).test(url);	
			service_host = RegExp.$1;
			ssrvname = RegExp.$2;
		}
		doc_blank=ssrvname+"?cmd=script&doc=wsc_core&name=blank&ssrv_host="+ssrv_host;
		doc_blankform=ssrvname+"?cmd=script&doc=wsc_core&name=blankform&ssrv_host="+ssrv_host;
		doc_process=ssrvname;
	} else {
		ssrv_host='';
		port='';
		ssrv='';
		ssrvname='';
	}
	
	if(is_window_opened){
		is_window_opened = false;
	}
	

	botf=167;
		
	if(window.name==''){
		window.name='sp_ch_opener_window';
	}
	
	wndname=window.name;
			
	if(!is_window_opened){
		sp_spw_name = createIdentifier("spch"+document.location.host+sp_time);
		sp_spw_params = "width="+wWidth+",height="+wHeight+",toolbar=no,resizable=no";
		if(wTop!= null){
			sp_spw_params += ",top=" + wTop;
		}
		if(wLeft!= null){
			sp_spw_params += ",left=" + wLeft;
		}


		sp_wurl=getWindowURL();
		isCallbackInvokedInSession = false;
		spellWin = window.open(sp_wurl,sp_spw_name,sp_spw_params);
		spellWin.opener=window;
	}
	spellWin.focus();
	
	return;
}

function initWSCFrames(){
	spellWin.location.replace(getSPWU());
	return;
}

function getSPWU(){
	var sBlankDocUrl = escape(addUrlParam((service_host+doc_blank),"relaxedDomain",sRelaxedDomain));
	var sBlankFormDocUrl = escape(addUrlParam((service_host+doc_blankform),"relaxedDomain",sRelaxedDomain));
	/*~return url+'?cmd=script&doc=wsc_core&name=frm&qw=&ctrlname=&firstframeh=90&thirdframeh='+botf+'&fifthframe=&docblank='+sBlankDocUrl+'&docblankform='+sBlankFormDocUrl+'&hide='+hide + '&relaxedDomain=' + (sRelaxedDomain?sRelaxedDomain:'');*/
	return url+'?cmd=script&doc=wsc_core&name=frm&qw=&ctrlname=&firstframeh=90&thirdframeh='+botf+'&fifthframe=&docblank='+sBlankDocUrl+'&docblankform='+sBlankFormDocUrl+'&relaxedDomain=' + (sRelaxedDomain?sRelaxedDomain:'');
}

function ChkFrm() {
	

	sp_doc=spellWin.frames[1].document;
	
	if(sp_doc) { 
		var f_dst;

		f_dst=sp_doc.forms[0];
		
		f_dst.action=service_host+doc_process;
		if(sp_asce){
			f_dst.sp_asce.value=lang;
		}
		f_dst.cmd.value=cmd;
		f_dst.slang.value=lang;
		f_dst.intlang.value=intlang;
		f_dst.text.value=getSourceObjectContent(ctrlObj,false);
		f_dst.trg_url.value=url;
		f_dst.trg_wnd.value=wndname;
		f_dst.txt_ctrl.value=force_reload?ctrlName:'';
		f_dst.svc_time.value=(new Date()).getTime();
		f_dst.customerid.value=customerid?customerid:'';
		f_dst.cust_dic_ids.value=cust_dic_ids?cust_dic_ids:'';
		f_dst.udn.value=udn?udn:'';

		f_dst.schema_url.value=schema_url;
		f_dst.schema.value=schema;
		f_dst.spp_langs.value=spp_langs;
		
		f_dst.hide.value=autoClose;
		f_dst.settings.value=(isProxyVer)?("sproxy"):("sproxy=0");
		if(f_dst.ssrv_host){
			f_dst.ssrv_host.value=ssrv_host;
		}
		f_dst.relaxedDomain.value = sRelaxedDomain;
		f_dst.submit();
	}
	is_window_opened=true;
}

function getWindowURL(){
	var sUrl = doc_initialize;
	sUrl = addUrlParam(sUrl,"relaxedDomain",sRelaxedDomain);
	return sUrl;
}

function addUrlParam(sUrl,sParam,sValue){
	return sUrl + ((/\?/).test(sUrl)?("&"):("?")) + sParam+ "=" + sValue; 
}

function isAppVer(s){
	return navigator.appVersion.indexOf(s)!=-1;
}


//# callback method invoker
function endSpellCheckCallback(sExitType,sCtrlId,sCheckedContent){
	var mCtrl = null;
	if(isCallbackInvokedInSession == true){
		return;
	}
	isCallbackInvokedInSession = true;
	
	if(sCtrlId == "MTBDummy"){
		mCtrl = pChecker.getCtrls();
	}else{
		mCtrl = document.getElementById(sCtrlId);
	}
	switch(sExitType){
		case "cancel":
			if(typeof(onCancel) == 'function'){
				onCancel(mCtrl);
			}
		break;
		case "close":
			
			if(typeof(onClose) == 'function'){
				onClose(mCtrl);
			}
		break;		
		case "finish":
			if(sCtrlId == "MTBDummy"){
				pChecker.restore(sCheckedContent);
			}else{
				setSourceObjectContent(mCtrl,sCheckedContent);
			}
			if(typeof(tinyMCE) == 'object'){
				if(tinyMCE.activeEditor.plugins.scayt && tinyMCE.activeEditor.plugins.scayt._s._SCAYT_control){
					tinyMCE.activeEditor.plugins.scayt._s._SCAYT_control.refresh();
				}
			}
			if(typeof(onFinish) == 'function'){
				onFinish(mCtrl);
			}
		break;		
	}
}


//#get node content
function getSourceObjectContent(nObject,isText){
	var sContent = '';
	var nCtrl = null;
	
	if(nObject.value)
	{
		sContent = nObject.value;
	} else if(nObject.contentWindow)
	{
		nCtrl = nObject.contentWindow.document.body;
	} else if (nObject.contentEditable &&
		(nObject.tagName == "SPAN" || 
		nObject.tagName == "DIV"))
	{
		nCtrl = nObject; 
	}
	if (nCtrl) {
		if(isText){
			sContent = nCtrl.innerText ||
			nCtrl.textContent ||
			"";
		}else{
			sContent = nCtrl.innerHTML;
		}
	}
	return sContent;
}
//# set Source object content
function setSourceObjectContent(nObject,sContent){
	if(nObject.value){
		nObject.value = sContent;
	}else if(nObject.contentWindow){
		nObject.contentWindow.document.body.innerHTML = sContent;
	} else if (nObject.contentEditable &&
		(nObject.tagName == "SPAN" || 
		nObject.tagName == "DIV"))
	{
		nObject.innerHTML = sContent;
	}
	return;
}

//#convert incoming string to identifier valid format: knife.domain.com:8080 => knife_domain_com_8080
function createIdentifier(sValue){
	return sValue.replace(/(\.|\-|\:)/img,'_');
}
//multiple instances  addon

/////////////////////////////////////////////////////////////////////////////////////////
var pChecker = null;
function MTB_I_Make_RegExp(strName)
{
	return new RegExp(strName.replace(/%/g, '\\x').replace(/\+/g, ' ').replace(/\*/g, '\\*').replace(/\[/g, '\\[').replace(/\]/g, '\\]'), '');
}

function MTB_I_EscapeText(strSrc)
{
	return strSrc.replace(/&/g,'&amp;').replace(/</g,'&lt;');
}

function MTB_I_UnescapeText(strSrc)
{
	return strSrc.replace(/&lt;/g, '<').replace(/&amp;/g, '&');
}
/////////////////////////////////////////////////////////////////////////////////////////
function MTB_I_StdAction(strText)
{
	this.obj.restore(strText);
}
function MTB_I_DummyObj()
{
	this.value	= '';
	this.name	= 'MTB_Dummy.action(this.document.forms[0].msg_body.value), parent.opener.MTB_Dummy';
	this.obj	= null;
	this.id = 'MTBDummy';
}
MTB_I_DummyObj.prototype.action	= MTB_I_StdAction;
var MTB_Dummy	= new MTB_I_DummyObj();
/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////
function MTB_I_Compose_Text(bSimple){

	var	strResult;
	var i,j;
	var count	= 0;

	if (bSimple){
		strResult	= '';
	}else{
		strResult	= this.m_strBegin;
	}
    //var items = this.m_strNames.split(',');
   // var s = ''; s1 = '';
    var i, j;
	var items = this.m_strNames;
    
    for (i = 0; i < items.length; i++)
    {
    	//var ctrl = eval(items[i]);
    	var s_ctrl = items[i];
		var nObj = document.getElementById(items[i]);
		if(!nObj){
			continue;
		}
    	var s_val = getSourceObjectContent(nObj,false);

		if (!bSimple)
			strResult	+= this.m_strRBegin + '_x_path="' + s_ctrl + '"' + this.m_strRMiddle;
		strResult		+= s_val;
		if (!bSimple)	
			strResult	+= this.m_strREnd;
		else
			strResult	+= this.m_strSDiv;
		count++;
    	
    	//s += items[i] + "\n";
    }

	if (!bSimple){
		strResult	+= this.m_strEnd;
	}

	return strResult;
}
/////////////////////////////////////////////////////////////////////////////////////////
function MTB_I_Restore(strValue)
{
	var aE	= MTB_I_UnescapeText(strValue).split(this.m_strSplitter);
	var i;
	var sPath;
	var nBeg,nEnd;
	
	for (i = 0; i < aE.length - 1; i++)
	{
		nBeg				= aE[i].indexOf('_x_path="') + '_x_path="'.length;
		nEnd				= aE[i].indexOf('"' + this.m_strRMiddle, nBeg);
		sPath 				= aE[i].substring(nBeg, nEnd);

		setSourceObjectContent(document.getElementById(sPath),aE[i].substring(nEnd + this.m_strRMiddle.length + 1, aE[i].length));

	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function MTB_I_Check()
{
	MTB_Dummy.value		= this.compose(false);
	MTB_Dummy.obj    	= this;

	if (MTB_Dummy.value == (this.m_strBegin + this.m_strEnd)){
		alert('Nothing to check');
	}else{
	
	 // make copy of oparams,
		this.oParams.ctrl = MTB_Dummy;
//		spellCheckSingleInstance({ctrl:MTB_Dummy,lang:this.m_strLang,schemaIdentifier:this.m_strSch});
		spellCheckSingleInstance(this.oParams);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
function MTBChecker(oParams)
{
	this.m_strNames	= oParams.ctrl;
//	this.m_strNames	= strNames; oParams.ctrls;
//	this.m_strLang	= strLang;
//	this.m_strSch	= schemaIdentifier;
	this.oParams = oParams;
}

MTBChecker.prototype.check		= MTB_I_Check;
MTBChecker.prototype.compose	= MTB_I_Compose_Text;
MTBChecker.prototype.restore	= MTB_I_Restore;
MTBChecker.prototype.getCtrls = function(){
	var ctrls = new Array();
	for(var i=0; i<this.m_strNames.length;i++){
		ctrls.push(document.getElementById(this.m_strNames[i]));
	}
	return ctrls;
}

MTBChecker.prototype.m_strBegin		= '<table border=1 _bordercolor=silver width=100%>';
MTBChecker.prototype.m_strEnd		= '</table>';
MTBChecker.prototype.m_strRBegin	= '<TR tr_x_tr><TD ';
MTBChecker.prototype.m_strRMiddle	= '>';
MTBChecker.prototype.m_strREnd		= '</TD tr_x_tr></TR tr_x_tr>';
MTBChecker.prototype.m_strSplitter	= "</TD tr_x_tr></TR tr_x_tr>";
MTBChecker.prototype.m_strSDiv		= '\n\n';

MTBChecker.prototype.m_strDoc			= 'document';
MTBChecker.prototype.m_strCollection	= 'forms';
MTBChecker.prototype.m_strSubCollection	= 'elements';
MTBChecker.prototype.m_strTypes			= 'text,textarea';

MTBChecker.prototype.m_objSubmitForm = "";

function spellCheckMultipleInstances(oParams){
	pChecker	= new MTBChecker(oParams);
	pChecker.check();
}
function getCtrlName(){
	return ctrlName;
}
	return { 
		doSpell : doSpell,
		initWSCFrames: initWSCFrames,
		ChkFrm: ChkFrm,
		endSpellCheckCallback: endSpellCheckCallback,
		getCtrlName: getCtrlName
	};
})();
doSpell = WSC.doSpell;