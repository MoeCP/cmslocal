var sSCAYTRequestedFile=SCAYTCorePath;var oSCAYTPluginLoader=null;var sSCAYTQueryStr=null;if(window.ActiveXObject){oSCAYTPluginLoader=new ActiveXObject('Microsoft.XMLHTTP');sSCAYTQueryStr='';}else if(window.XMLHttpRequest){oSCAYTPluginLoader=new XMLHttpRequest();sSCAYTQueryStr=null;}
if(oSCAYTPluginLoader){oSCAYTPluginLoader.open('GET',sSCAYTRequestedFile,false)
oSCAYTPluginLoader.send(sSCAYTQueryStr);eval(oSCAYTPluginLoader.responseText);}
