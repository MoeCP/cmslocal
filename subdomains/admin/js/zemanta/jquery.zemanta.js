(function zemantaStart(b){function d(n){if(b.Zemanta){return
}if(!n.zextend&&n.extend){n.zextend=n.extend;
n.fn.zextend=n.fn.extend
}var h=b.setTimeout,m=b.clearTimeout,f=b.setInterval,r=b.clearInterval,v=b.document,i=b.navigator,B=b.Image,w=b.ActiveXObject,l=b.JSON,q=b.location.protocol==="https:",C=q?"https://s3.amazonaws.com/static.zemanta.com/":"http://fstatic.zemanta.com/",A=q?C:"http://static.zemanta.com/",j=function(){return new Date().getTime()
},g={loghistory:[],log:function(){g.loghistory.push(arguments);
if(g.debug===true){var H="",G=0,F=arguments.length,I="";
for(;G<F;G+=1){H+=I+(typeof arguments[G]==="object"&&arguments[G]?l.stringify(arguments[G]):arguments[G]);
I=" "}if(b.top.console&&typeof b.top.console.log==="function"){b.top.console.log(H)
}else{b.alert(H)
}}},error:(function(){var G=[],H=0;
function F(){H+=1;
return j()+":"+H+":"+Math.floor(Math.random()*10000)
}return function(K){var J=typeof K,I=null,L=null,M="";
if(J==="object"){L=g.control._add_stats(n.zextend({error:K,api_key:g.api_key,rid:g.rid},g.control.preferences.response()));
if(!q){I=new B();
M=F();I.src="http://error.zemanta.com/?error_id="+M+"&api_key="+(L.api_key||"")+"&rid="+(L.rid||"")+"&interface="+(L["interface"]||"")+"&deployment="+(L.deployment||"")+"&error="+(L.error&&L.error.error&&L.error.error.message||"")
}return G.unshift(K)
}else{if(J==="number"){return G[K]
}else{if(J==="string"){K=K.split(":");
if(K.length===2){return G.slice(K[0],K[1])
}else{return[]
}}else{return G
}}}}}())},o=function(){var G=n({}),F={trigger:function(){try{G.trigger.apply(G,arguments)
}catch(H){g.helpers.logGA(null,"widget/error/jquery-trigger-error/"+g.interface_type+"/");
g.log("jQuery trigger error")
}},init:function(H){G=n(H);
(function(I){if(I.constructor===Array){n.map(I,arguments.callee)
}else{H[I]=function(){G[I].apply(G,arguments);
return this
}}})(["bind","one","unbind"]);
return H}};
return F}(),E={},t={},z={},p={},D={},k={},x={},y={},s={};
if(!Array.isArray){Array.isArray=function(F){return Object.prototype.toString.call(F)==="[object Array]"
}}Array.indexOf=function(F,I){if(F.indexOf){return F.indexOf(I)
}var H=0,G=F.length;
for(;H<G;H+=1){if(H in F&&F[H]===I){return H
}}return -1
};function u(){var G=[],H={};
function F(I){return I&&I.length
}G.item=function(I){return G[I]
};G.contains=function(I){if(F(I)){return H[I]
}return false
};G.add=function(I){if(F(I)&&!H[I]){G.push(I);
H[I]=true}return G
};G.remove=function(I){if(F(I)&&H[I]){G.splice(Array.indexOf(G,I),1);
delete H[I]
}return G};
G.toggle=function(I){if(F(I)){G[G.contains(I)&&"remove"||"add"]()
}return G};
G.print=function(J,K){var I=G.join(" ");
return I&&(J||"")+I+(K||"")||""
};G.toString=G.print;
return G}Date.prototype.setISO8601=function(G){var H="([0-9]{4})(-([0-9]{2})(-([0-9]{2})(T([0-9]{2}):([0-9]{2})(:([0-9]{2})(\\.([0-9]+))?)?(Z|(([-+])([0-9]{2}):([0-9]{2})))?)?)?)?",K=G.match(new RegExp(H)),J=0,F=new Date(K[1],0,1),I=0;
if(K[3]){F.setMonth(K[3]-1)
}if(K[5]){F.setDate(K[5])
}if(K[7]){F.setHours(K[7])
}if(K[8]){F.setMinutes(K[8])
}if(K[10]){F.setSeconds(K[10])
}if(K[12]){F.setMilliseconds(Number("0."+K[12])*1000)
}if(K[14]){J=(Number(K[16])*60)+Number(K[17]);
J*=((K[15]==="-")?1:-1)
}J-=F.getTimezoneOffset();
I=(Number(F)+(J*60*1000));
this.setTime(Number(I))
};(function(){var G=60*60*24,F=[{n:"year",s:G*365},{n:"month",s:G*30},{n:"week",s:G*7},{n:"day",s:G},{n:"hour",s:60*60},{n:"minute",s:60},{n:"second",s:1}];
Date.prototype.toTimeSinceString=function(K,I,N,S){var J=K||2,O=I||", ",U=N||" and ",Q=F,T=(j()-this.getTime())/1000,M=0,L=Q.length,H=S?[]:"",P=null,R=0;
for(;M<L;M+=1){P=Q[M];
R=Math.floor(T/P.s);
if(R>0){if(S){H.push({n:P.n,v:R})
}else{if(H!==""){H+=J===1||M+1===L?U:O
}H+=R+" "+P.n+(R>1&&"s"||"")
}if(J===1){break
}T=T-R*P.s;
J-=1}}return H
}})();(function(F){F.zextend(F.easing,{easeInQuart:function(H,I,G,K,J){return K*(I/=J)*I*I*I+G
}})}(n));(function(G){var H=h(function(){},0),F=null;
G.readyOrDone=function(J){var I=v.readyState,K=function(){var L=v.getElementsByTagName("*");
return L[L.length-1]
};if(I==="complete"){G.ready()
}else{if(typeof I==="undefined"){F=K();
m(H);H=h(function(){if(K()===F&&typeof v.readyState==="undefined"){G.ready()
}},1000)}}G(J)
}}(n));(function(K){K=K||b.jQuery;
var J=K.ajax,G=0,H=/^(\w+:)?\/\/([^\/?#]+)/,I=function(M){return function(){var O="",T=(M.type||"").toUpperCase(),S="",V="jQuery.windowName.transport.frame",R=null,Q=null,N=null,W={};
function P(){m(N);
try{delete b.jQueryWindowName[S]
}catch(Y){b.jQueryWindowName[S]=function(){}
}h(function(){K(R).remove();
K(Q).remove()
},100)}function X(){try{var Y=R.contentWindow.name;
if(typeof Y==="string"){if(Y===V){W.status=501;
W.statusText="Not Implemented"
}else{W.status=200;
W.statusText="OK";
W.responseText=Y
}W.readyState=4;
W.onreadystatechange();
P()}}catch(Z){W.status=500;
W.statusText="Internal Server Error";
W.readyState=4;
W.onreadystatechange();
P()}}function U(Z){var Y={},aa=decodeURIComponent;
K.each(Z.split("&"),function(ac,ab){if(ab.length){var ae=ab.split("="),af=aa(ae.shift()),ad=Y[af];
ab=aa(ae.join("="));
if(typeof ad==="undefined"){Y[af]=ab
}else{if(ad.constructor===Array){Y[af].push(ab)
}else{Y[af]=[ad].concat(ab)
}}}});return Y
}W={abort:function(){P()
},getAllResponseHeaders:function(){return""
},getResponseHeader:function(Y){return""
},open:function(Y,Z){O=Z;
this.readyState=1;
this.onreadystatechange()
},send:function(ab){ab=ab||"";
if(ab.indexOf("windowname=")<0){ab+=(ab===""?"":"&")+"windowname="+(M.windowname||"true")
}S="jQueryWindowName"+(""+Math.random()).substr(2,8);
b.jQueryWindowName=b.jQueryWindowName||{};
b.jQueryWindowName[S]=function(){};
var aa=null,ad=null,af=null,ah=null,ae=b.location.href.substr(0,b.location.href.indexOf("/",8)),ac=["/robots.txt","/crossdomain.xml"];
Q=v.createElement("form");
if(K.browser.msie){try{R=v.createElement('<iframe name="'+S+'" onload="jQueryWindowName[\''+S+"']()\">");
K("body")[0].appendChild(R)
}catch(ag){}}if(!R){R=v.createElement("iframe")
}R.style.display="none";
b.jQueryWindowName[S]=R.onload=function(ai){function ak(ao){var an="";
if(ao){G+=1
}an=M.localfile?M.localfile:ac[G]?ae+ac[G]:null;
if(!an){an=b.location.href
}return an}function am(){var ao=false;
try{ao=!!R.contentWindow.location.href
}catch(an){}return ao
}try{if(R.contentWindow.location.href==="about:blank"){return
}}catch(al){}if(W.readyState===3){if(am()){X();
if(W.status===200){K.ajaxSettings.wnjson_supported[M.url]=true
}}else{R.contentWindow.location=ak(true)
}}if(W.readyState===2&&(M.windowname||!am())){W.readyState=3;
W.onreadystatechange();
try{R.contentWindow.location=ak()
}catch(aj){X()
}}};N=h(function(){P()
},120000);R.name=S;
R.id=S;if(!R.parentNode){K("body")[0].appendChild(R)
}if(T==="GET"){R.contentWindow.location.href=O+(O.indexOf("?")>=0?"&":"?")+ab
}else{Q.style.display="none";
K("body")[0].appendChild(Q);
aa=Q.method;
ad=Q.action;
af=Q.target;
ah=Q.submit;
Q.method="POST";
Q.action=O;
Q.target=S;
K.each(U(ab.replace(/\+/g,"%20")),function(aj,ai){function ak(an,am){var al=v.createElement("input");
al.type="hidden";
al.name=an;
al.value=am;
Q.appendChild(al)
}if(ai.constuctor===Array){K.each(ai,function(am,al){ak(aj,al)
})}else{ak(aj,ai)
}});try{aa=Q.method="POST";
ad=Q.action=O;
af=Q.target=S
}catch(Z){}R.contentWindow.location="about:blank";
try{ah()}catch(Y){ah.call(Q)
}}this.readyState=2;
this.onreadystatechange();
if(R.contentWindow){R.contentWindow.name=V
}},setRequestHeader:function(Y,Z){},onreadystatechange:function(){},readyState:0,responseText:"",responseXML:null,status:null,statusText:null};
return W}},F=b.XDomainRequest?function(){var M=new b.XDomainRequest();
M.onreadystatechange=function(){};
M.setRequestHeader=function(N,O){};
M.getAllResponseHeaders=function(){return{"content-type":M.contentType}
};M.getResponseHeader=function(N){if(N==="content-type"){return this.contentType
}};M.onload=function(){K.zextend(M,{readyState:4,status:200,statusText:"OK"});
M.onreadystatechange.call(M,{})
};M.onprogress=function(){K.zextend(M,{readyState:3,status:200,statusText:"OK"});
M.onreadystatechange.call(M,{})
};M.onerror=function(N){K.zextend(M,{readyState:4,status:0,statusText:""});
M.onreadystatechange.call(M,{})
};return M}:K.ajaxSettings.xhr;
K.ajaxSettings.wnjson_supported={};
K.ajaxSettings.cors_supported={};
try{K.support.cors=!!b.XDomainRequest||(function(){var M=K.ajaxSettings.xhr();
M.open("GET",b.location.protocol+"//domain.fake/",true);
M.send();M.abort();
return true
}())}catch(L){K.support.cors=false
}K.zextend({ajax:function(M){var P=K.extend(true,{},K.ajaxSettings,M),T=H.exec(P.url||""),R=T&&(T[1]&&T[1]!==b.location.protocol||T[2]!==b.location.host),O=(P.type||"").toUpperCase(),N=P.success||function(){},S=P.error||function(){},Q=P.complete||function(){};
if(P.windowname){P.xhr=I(P)
}else{if(O==="POST"&&R){if(K.support.cors&&K.ajaxSettings.cors_supported[P.url]!==false){P.xhr=F;
P.success=function(V,U,W){if(W.status===0){U="error";
if(this.error){this.error.call(this,P,W,U,{name:"Unsupported",message:"CORS not supported."})
}}else{K.ajaxSettings.cors_supported[P.url]=true;
return N.apply(this,arguments)
}};P.error=function(V,U){K.ajaxSettings.cors_supported[P.url]=false;
return S.apply(this,arguments)
}}else{if(K.ajaxSettings.wnjson_supported[P.url]!==false){P.xhr=I(P);
P.complete=function(){K.ajaxSettings.wnjson_supported[P.url]=true;
return Q.apply(this,arguments)
}}}}}return J.call(this,P)
}})}(n));n.fn.zattr=function(F,H,G){if(F.constructor===String&&H===null){return this.removeAttr(F)
}else{return this.attr(F,H,G)
}};n.fn.or=function(F){if(this.length){return this.pushStack(this)
}else{return this.add(F)
}};n.fn.findWithSelf=function(F){return this.pushStack(this.find(F).andSelf().filter(F))
};n.fn.fin=function(){return n(this)
};n.fn.getDoc=function(){return this[0]&&this[0].ownerDocument||this.ownerDocument||this||v
};n.fn.realSize=function(F){var I=F||this[0],G=null,H=null;
if(typeof I.naturalHeight==="undefined"){G=n('<img src="'+I.src+'" alt="" />').appendTo("body").css("display","none");
H={w:G.width()||I.width(),h:G.height()||I.height()}
}else{H={w:I.naturalWidth,h:I.naturalHeight}
}return H};
(function(F){if(F.browser.mozilla){F.fn.disableTextSelect=function(){return this.each(function(){F(this).css({MozUserSelect:"none"})
})}}else{if(F.browser.msie){F.fn.disableTextSelect=function(){return this.each(function(){F(this).bind("selectstart",function(){return false
})})}}else{F.fn.disableTextSelect=function(){return this.each(function(){F(this).mousedown(function(){return false
})})}}}})(n);
n.cookie=function(G,O,R){var J="",K=null,Q="",N="",F="",I=null,P=null,H=null,M=0,L=0;
if(typeof O!=="undefined"){R=R||{};
if(O===null){O="";
R.expires=-1
}J="";if(R.expires&&(typeof R.expires==="number"||R.expires.toUTCString)){if(typeof R.expires==="number"){K=new Date();
K.setTime(K.getTime()+(R.expires*24*60*60*1000))
}else{K=R.expires
}J="; expires="+K.toUTCString()
}Q=R.path?"; path="+R.path:"";
N=R.domain?"; domain="+R.domain:"";
F=R.secure?"; secure":"";
v.cookie=[G,"=",encodeURIComponent(O),J,Q,N,F].join("")
}else{I=null;
if(v.cookie&&v.cookie!==""){P=v.cookie.split(";");
for(L=P.length;
M<L;M+=1){H=n.trim(P[M]);
if(H.substring(0,G.length+1)===(G+"=")){I=decodeURIComponent(H.substring(G.length+1));
break}}}return I
}};n.fextend=function(H,F){var G="";
H.original=H.original||{};
for(G in F){if(typeof F[G]==="function"&&typeof H[G]==="function"){H.original[G]=H[G];
F[G].original=H[G];
H[G]=F[G];delete F[G]
}}n.zextend(H,F)
};n.fn.get_element_width=function(I,F){var G=this||{},H=G.width();
H+=I?parseInt(G.css("border-left-width"),10)+parseInt(G.css("border-right-width"),10):0;
H+=F?parseInt(G.css("padding-left"),10)+parseInt(G.css("padding-right"),10):0;
return H};n.fn.get_element_height=function(I,H,J,G,M,L){var K=this||{},F=K.height();
F+=I?parseInt(K.css("border-top-width"),10):0;
F+=H?parseInt(K.css("border-bottom-width"),10):0;
F+=J?parseInt(K.css("padding-top"),10):0;
F+=G?parseInt(K.css("padding-bottom"),10):0;
F+=M?parseInt(K.css("margin-top"),10):0;
F+=L?parseInt(K.css("margin-bottom"),10):0;
return F};E=function(){function G(J){var I=h(function(){},1);
return function H(){var L=this,K=arguments;
m(I);I=h(function(){J.apply(L,K)
},1)}}function F(H){if(!H){H=g.platform.get_editor().win
}if(H&&H.document){H.document.dndattached=true;
if(v.implementation.hasFeature("Events","2.0")){H.document.addEventListener("DOMNodeInserted",G(g.platform.nodesChanged),false);
H.document.addEventListener("DOMAttrModified",G(g.platform.nodesChanged),false);
H.document.addEventListener("DOMNodeRemoved",G(g.platform.nodesRemoved),false)
}else{H.document.onactivate=G(function(){var I=this;
g.platform.nodesChanged.call(I);
h(function(){g.platform.nodesRemoved.call(I)
},1)});H.document.onkeyup=function(I){return function(){var J=I.event;
if(J.keyCode===8||J.keyCode===46){g.platform.nodesRemoved.call(I.document)
}}}(H)}}}return{bind:F,setup:function(){var H=g.platform.get_editor();
if(H.type==="RTE"&&H.win&&H.win.document&&!H.win.document.dndattached&&g.platform.dnd_supported){g.dnd.bind(H.win)
}}}}();l=l||function(){function H(M){return M>=10?M:"0"+M
}var J={date:function(M){return M.getUTCFullYear()+"-"+H(M.getUTCMonth()+1)+"-"+H(M.getUTCDate())+"T"+H(M.getUTCHours())+":"+H(M.getUTCMinutes())+":"+H(M.getUTCSeconds())+"Z"
},other:function(M){return M.valueOf()
}},G=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,L,I={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"};
function F(M){G.lastIndex=0;
return G.test(M)?'"'+M.replace(G,function(N){var O=I[N];
if(typeof O==="string"){return O
}return"\\u"+("0000"+N.charCodeAt(0).toString(16)).slice(-4)
})+'"':'"'+M+'"'
}function K(T,Q){var O,N,U,M,R=L,P,S=Q[T];
if(S&&typeof S==="object"){if(typeof S.toJSON==="function"&&typeof b.Prototype==="undefined"){S=S.toJSON(T)
}else{if(S.constructor===Date){S=J.date(S,T)
}else{if(S.constructor===Number||S.constructor===String||S.constructor===Boolean){S=J.other(S,T)
}}}}switch(typeof S){case"string":return F(S);
case"number":return isFinite(S)?String(S):"null";
case"boolean":case"null":return String(S);
case"object":if(!S){return"null"
}P=[];if(typeof S.length==="number"&&!S.propertyIsEnumerable("length")){M=S.length;
for(O=0;O<M;
O+=1){P[O]=K(O,S)||"null"
}U=P.length===0?"[]":L?"[\n"+L+P.join(",\n"+L)+"\n"+R+"]":"["+P.join(",")+"]";
L=R;return U
}for(N in S){if(Object.hasOwnProperty.call(S,N)){U=K(N,S);
if(U){P.push(F(N)+(L?": ":":")+U)
}}}U=P.length===0?"{}":L?"{\n"+L+P.join(",\n"+L)+"\n"+R+"}":"{"+P.join(",")+"}";
L=R;return U
}}return{stringify:function(M){return K("",{"":M})
},parse:function(M){try{return(new Function("return "+M))()
}catch(N){throw new Error("Parsing JSON failed.")
}}}}();t.array_index=function(F,I,G){var H={};
n.each(F,function(){var J=I?this[I]:G(this);
if(!H[J]){H[J]=this
}});return H
};t.base64=(function(){var G="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",F=function(J){J=J.replace(/\x0d\x0a/g,"\x0a");
var K="",I=0,H=J.length,L=0;
for(;I<H;I+=1){L=J.charCodeAt(I);
if(L<128){K+=String.fromCharCode(L)
}else{if((L>127)&&(L<2048)){K+=String.fromCharCode((L>>6)|192);
K+=String.fromCharCode((L&63)|128)
}else{K+=String.fromCharCode((L>>12)|224);
K+=String.fromCharCode(((L>>6)&63)|128);
K+=String.fromCharCode((L&63)|128)
}}}return K
};return function(R){R=F(R);
var H="",M="",K="",I="",Q=0,P=0,O=0,N=0,L=0,J=R.length;
while(L<J){M=R.charCodeAt(L);
L+=1;K=R.charCodeAt(L);
L+=1;I=R.charCodeAt(L);
L+=1;Q=M>>2;
P=((M&3)<<4)|(K>>4);
O=((K&15)<<2)|(I>>6);
N=I&63;if(isNaN(K)){O=N=64
}else{if(isNaN(I)){N=64
}}H=H+G.charAt(Q)+G.charAt(P)+G.charAt(O)+G.charAt(N)
}return H}}());
t.check_connection=function(H,G){var F=new B(),I=g.helpers.duckie();
F.onerror=G;
F.onload=H;
F.src=I+(I.indexOf("?")>=0?"&":"?")+"t="+j()
};t.check_feature=function(F){try{return b.top.location.hash.indexOf(F)>=0
}catch(G){return b.location.hash.indexOf(F)>=0
}};t.clone=function(H){if(H===null||typeof(H)!=="object"){return H
}var F,G=new H.constructor();
for(F in H){if(g.helpers.ljext[F]!==""){G[F]=t.clone(H[F])
}}return G};
t.simple_clone=function(H){var F,G={};
for(F in H){if(typeof H[F]!=="object"){G[F]=H[F]
}}return G};
t.close_tags=function(K,H){var I,G="",L=["img","br","hr","meta","link","input","param","area","col"],J=["embed"],F=[];
F=H==="1"?L.concat(J):J;
I=arguments.callee["r"+H]=arguments.callee["r"+H]||new RegExp("<("+F.join("|")+")([^>]*)>","gi");
G=K.replace(I,function(O,M,N){while(" /".indexOf(N.slice(-1))>=0&&N.length>0){N=N.substr(0,N.length-1)
}return n.grep(L,function(P){return P===M.toLowerCase()
})[0]?"<"+M+N+" />":"<"+M+N+"></"+M+">"
});return G
};t.copy=(function(){var F=function(G){this.my={props:typeof G==="string"?[G]:G}
};F.prototype={from:function(H){var G=this.my;
G.src=H;return G.src&&G.dest&&this.exec()||this
},to:function(H){var G=this.my;
G.dest=H||{};
return G.src&&G.dest&&this.exec()||this
},exec:function(){var G=this.my;
if(!G.props){G.props=[];
n.each(G.src,function(H,I){G.props.push(H)
})}return t.copy_properties(G.src,G.dest,G.props)
}};return function(G){var H=function(){};
H.prototype=new F(G);
return new H()
}}());t.copy_properties=function(F,H,G){n.each(G,function(){if(typeof F[this]!=="undefined"){H[this]=F[this]
}});return H
};t.create_fragment=function(F,I){I=I&&I.ownerDocument||I||v;
var J=true,H=I.createDocumentFragment(),G=I.createElement("div"),K=null;
if(typeof F==="string"){G.innerHTML="|"+F
}else{G=F;J=false
}while(G.childNodes.length){K=G.childNodes[0];
if(J&&K.nodeType===3){K.nodeValue=K.nodeValue.substr(1);
if(K.nodeValue===""){G.removeChild(K);
continue}}H.appendChild(K);
J=false}return H
};t.drag=(function(){var F=["#zemanta-sidebar #zemanta-gallery-wrap","#zemanta-sidebar #zemanta-articles-wrap","#zemanta-sidebar #zemanta-links-div-ul","#zemanta-sidebar #zemanta-tags-div-ul"];
function H(J,K,M){var L=J;
n.each(K.above,function(N,Q){var O=M.above[N]-L,P=n(Q);
if(!P.length){return true
}if(O<0){O=0;
L-=M.above[N]
}else{L=0}P.height(O);
return L!==0
});if(L>0){J-=L
}n.each(K.below,function(N,Q){var O=M.below[N]+J,P=n(Q);
if(!P.length){return true
}if(O<0){O=0;
J+=M.below[N]
}else{J=0}P.height(O);
return J!==0
})}function G(J){var K={};
n(v).unbind(".drag");
if(n("#zemanta-sidebar").height()!==0&&n("#zemanta-sidebar").css("display")!=="none"&&n("#zemanta-gallery").css("display")!=="none"&&n("#zemanta-articles").css("display")!=="none"){n.each(J.above.concat(J.below),function(L,M){if(n(M).height()!==null){K[M+"-size"]=n(M).height()
}});g.helpers.storage.set(K)
}}function I(K,J,L){if(typeof K==="function"){K.apply(J,L)
}}return function(L,K,J){if(g.platform&&g.platform.disable_draggable_resize){return false
}var M=null;
if(typeof K!=="string"){J=K;
K=null}J=J||{};
if(J.me||K){J.above=F.slice(0,Array.indexOf(F,J.me||K)).reverse();
J.below=F.slice(Array.indexOf(F,J.me||K))
}J.above=J.above||[];
J.below=J.below||[];
return n(L).find(".zemanta-drag-handle").remove().end().append('<span class="zemanta-drag-handle"></span>').attr("title","Drag to resize").mousedown(function(N){if(!n(this).hasClass("draggable")){return
}if(N.which===1){var O={above:[],below:[]},P=N.clientY;
M=this;n.each(J.above,function(Q,R){O.above.push(n(R).height())
});n.each(J.below,function(Q,R){O.below.push(n(R).height())
});n(v).bind({"mousemove.drag":function(Q){H(P-Q.clientY,J,O);
I(J.drag,M,[Q])
},"mouseup.drag":function(Q){G(J);
I(J.stop,M,[Q]);
o.trigger("stopDrag")
}});N.preventDefault();
I(J.start,M,[N]);
o.trigger("startDrag")
}}).fin()}}());
t.duckie=function(){return C+"core/img/user_orange.png"
};t.elf_hash=function(I){var G,H=0,F=0;
for(G=0;G<I.length;
G+=1){H=(H<<4)+I.charCodeAt(G);
if((F=H&4026531840)!==0){H^=(F>>24);
H&=~F}}return""+(H&2147483647)
};t.empty=function(G){var F=true;
n.each(G,function(H){if(t.ljext[H]===""){return
}F=false;return false
});return F
};t.encode_uri=function(F){F=encodeURI(F);
F=F.replace(/\(/g,"%28").replace(/\)/g,"%29");
return F};t.encode_url=function(F){var G=v.createElement("div");
G.innerHTML='<a href="'+F+'">';
return n("a",G).attr("href")
};t.extract_hostname=function(H){var I,F,G;
I=arguments.callee.e=arguments.callee.e||/\/\/((?:www|blog)\d*\.){0,1}(.*?)\//;
F=H.match(I);
if(F){G=F[2].indexOf(".")>=0?F[2]:(F[1]||"")+F[2];
if(G.indexOf("zemanta.com")>-1){if(H.split("?u=").length===2){return arguments.callee(H.split("?u=")[1])
}}return G}else{return""
}};t.html_attr=(function(){var G=/"/g,F=/>/g;
return function(H){return H.replace(G,"&quot;").replace(F,"&gt;")
}}());t.html_value=(function(){var I=/<br \/>/g,J=/\{br\}/g,H=/&/g,F=/</g,G=/>/g;
return function(K){return K.replace(I,"{br}").replace(H,"&amp;").replace(F,"&lt;").replace(G,"&gt;").replace(J,"<br />")
}}());(function(){var I=null,F=[],G=false;
function H(){var L=0,M=F,K=M.length;
F=[];G=true;
m(I);I=null;
for(;L<K;L+=1){M[L].apply(this,arguments)
}G=false;if(F.length){I=h(H,100)
}}function J(K){F.push(K);
if(!G&&!I){m(I);
I=h(H,100)}}t.image_size=function(O,L,U){var T=0,P=0,R=0,S=false,M=false,K="",N=null;
if(typeof O==="string"){K=O;
O=null;U=L;
L=""}else{K=O[L];
L+="_"}if(K&&(!L||!O[L+"_h"]||!O[L+"_w"])){N=new B();
N.onerror=function(){S=true
};N.onload=function(){M=true
};N.src=K;if(typeof U==="function"){(function Q(V){T=N.width;
P=N.height;
if(S){return
}else{if(T===0&&P===0&&!M){J(Q)
}else{O[L+"w"]=T;
O[L+"h"]=P;
if(V!==true){U(O)
}}}})(true)
}else{while(T===0&&P===0&&!M){T=N.width;
P=N.height;
R+=1;if(R>100||S){break
}}O=O||{};O[L+"w"]=T;
O[L+"h"]=P}}return O
}}());t.JSON=l;
t.ljext={init:"",extend:"",override:"",destroy:""};
t.logGA=function(I,G){var H=null,F="Zemanta.Tracking."+(""+Math.random()).substr(2,8);
b.trackingFrames=b.trackingFrames||{};
b.trackingFrames[F]=function(){};
if(n.browser.msie){try{H=v.createElement('<iframe name="'+F+'" onload="trackingFrames[\''+F+"']()\">")
}catch(J){}}if(!H){H=v.createElement("iframe")
}b.trackingFrames[F]=H.onload=function(K){h(function(){n(H).remove()
},100)};H.style.display="none";
H.id=F;if(!H.parentNode){n("body")[0].appendChild(H)
}H.contentWindow.location.href="http://prefs.zemanta.com/tracking"+(G?"/"+G:"")+(I?("/?"+g.helpers.toQueryString(I)):"")
};t.log=function(F){F.properties=n.extend({token:"12ea7650117fc89d3c0f945a59f45cd5",time:Math.floor(j()/1000),distinct_id:g.api_key},F.properties);
n.ajax({url:"http://api.mixpanel.com/track/?data="+t.base64(l.stringify(F)),dataType:"script"})
};t.toQueryString=function(H){var G="",F="";
for(F in H){if(g.helpers.ljext[F]!==""){G+=F+"="+encodeURIComponent(H[F])+"&"
}}return G.slice(0,G.length-1)
};t.load_file=function(H,F){var I=null,G=v.getElementsByTagName("head")[0],J=false;
if(H.indexOf(".js")>=0){I=v.createElement("script");
I.type="text/javascript";
I.src=H}else{if(H.indexOf(".css")>=0){I=v.createElement("link");
I.rel="stylesheet";
I.type="text/css";
I.href=H}else{return I
}}if(F){I.onload=I.onreadystatechange=function(){if(!J&&(!this.readyState||this.readyState==="loaded"||this.readyState==="complete")){J=true;
F.apply(I);
G.removeChild(I)
}}}G.appendChild(I);
return I};t.merge_arrays=function(H){var N=0,L=0,K=0,J=0,R=[],S=[],I=[],P={},U={},G=H.p,O=H.f,M=!!H.p,F,Q,T;
for(N=1,L=arguments.length;
N<L;N+=1){F=arguments[N];
for(K=0,J=F.length;
K<J;K+=1){Q=F[K];
T=M?Q[G]:O(Q);
if(!P[T]){R.push(Q);
P[T]=Q}else{I.push(Q);
U[T]=(U[T]||[]).concat([Q])
}}S.push(R.length-1)
}return H.e?{union:R,unionhash:P,idx:S,duplicate:I,duplicatehash:U}:R
};t.object_search=function(I,F,H){if(typeof H==="undefined"){return null
}for(var G=I.length-1;
G>=0;G-=1){if(I[G][F]===H){return I[G]
}}return null
};t.query_to_object=function(F){var G={};
n.each(F.split("&"),function(){var H=this.split("="),I=H.shift();
G[I]=H.join("=")
});return G
};t.popup=function(G,F){return function(H,S){var I={mode:"default",popup_id:"intent-popup",source_marker:"intent-popup-hover",parent_selector:"body",sensitivity:2,interval:50,timeout:200,init:function(X){},create:function(X){},beforeShow:function(X){},moveShader:function(Y,X){},destroy:function(X){},empty:function(X){},getPopupHeight:function(X){return X?X.outerHeight():n(this).outerHeight()
},position:function(X){n(this).css("left",X.source.position().left)
},element_height:function(X){return n(X).get_element_height(false,false,true,true,true)-1+3
}},U=n.browser.msie&&(n.browser.version.substr(0,1)<8),K=n("#zemanta-sidebar #zemanta-links").length,R=false,N={source:null,popup:null,config:I,links_in_sidebar:K,popupUpsidedown:false},V=null,O=null,J={},Q=function(){},M=function(){};
I=n.zextend(I,S);
I.velocity=I.sensitivity/I.interval;
n.extend(J,{stop:function(X){X.stopPropagation()
},elm_enter:function(Z){if(!t.popup.enabled){return
}var Y=this,ab={x:Z.clientX,y:Z.clientY,t:j()},aa=n(this).data("pos",ab).data("currPos",ab);
m(aa.data("tmer"));
aa.bind("mousemove",function(ac){n(this).data("currPos",{x:ac.clientX,y:ac.clientY,t:j()})
}).data("tmer",h(function X(){var ad=aa.data("pos"),ac=aa.data("currPos");
if(ac&&ac.t!==ad.t&&Math.sqrt(Math.pow(ac.x-ad.x,2)+Math.pow(ac.y-ad.y,2))/(ac.t-ad.t)>I.velocity){aa.data("pos",ac);
aa.data("tmer",h(X,I.interval))
}else{aa.data("state",1).unbind("mousemove");
Q.call(Y)}},I.interval));
if(g.platform.big_article_preview){m(V)
}Z.preventDefault()
},elm_leave:function(Y){var X=this,Z=n(this);
m(Z.data("tmer"));
if(Z.data("state")){Z.data("tmer",h(function(){Z.data("state",0).unbind("mousemove");
M.call(X)},I.timeout))
}},popup_enter:function(){n(this).addClass("active");
m(V)},popup_leave:function(){var X=n(this).removeClass("active");
V=h(function(){X.trigger("out")
},I.timeout)
}});function W(Z){if(I.mode==="gallery"){return -1
}if(I.mode==="links"&&!K){return n(v).height()-(n("#zemanta-links").offset().top+15)
}var X=Z.config.scroll_resilience&&Z.config.scroll_resilience.zero||{},Y=null;
if(X.length===0){return -1
}if(I.mode==="links"){Y=n("#zemanta-sidebar");
return Y.height()-(X.offset().top-Y.offset().top)
}return X.height()
}function T(Y){var X=n("#"+I.popup_id);
if(!X.length){X=n('<div id="'+I.popup_id+'" class="'+Y+'"></div>').prependTo(I.parent_selector)
}else{if(!X.hasClass(Y)){X.trigger("out");
I.empty();X.empty();
X[0].className=Y
}}return X.fin()
}function P(ag,Y,Z,ah){var ae=Z[0],af=ae.scrollTop,ac=I.getPopupHeight.call(Y),aj=(Z.offset()||{}).top-(ah.offset()||{}).top,ad=W(N),ab=aj+(ag.position().top+af),aa=parseInt(ag.css("border-top-width"),10),al=parseInt(ag.css("margin-top"),10),ai=(I.mode==="links")&&!K?ah.height:Z.height(),X=aj+ai-1,ak=ab+I.element_height.call(Y,ag);
if(U&&I.mode==="links"&&!K){ak-=aj
}return function(ao){var ap=0,am=0,an=0;
if(ae.scrollTop!==af||ao===true){af=ae.scrollTop;
ap=ak-af;am=ap+ac;
an=N.popupUpsidedown;
N.popupUpsidedown=false;
if(ap<aj){Y.removeClass("active");
Y.trigger("out");
return}else{if(am>ad&&ad!==-1){ap=((ab-af)<aj?aj-aa:(ab-af))-(ac-aa)-1;
if((ap+ac)>ad){Y.removeClass("active");
Y.trigger("out");
N.popupUpsidedown=true;
return}if((I.mode==="links")&&K){ap+=al-3
}N.popupUpsidedown=true
}else{if(ap>X){ap=X
}}}if(an!==N.popupUpsidedown){I.moveShader.call(Y,N,N.popupUpsidedown)
}if(I.mode==="articles"&&g.platform.big_article_preview){I.moveShader.call(Y,N,N.popupUpsidedown)
}else{if(I.mode==="gallery"&&g.platform.big_gallery_preview){I.moveShader.call(Y,N,N.popupUpsidedown)
}else{Y.css({top:ap})
}}}}}function L(X){return function(){M.call(X[0])
}}Q=function(){m(V);
var ac=n(this).addClass(I.source_marker).unbind("mousemove.popupIntent").bind("mousemove.popupIntent",J.stop),aa=ac.outerHeight(),X=T(this.id),ab=null,Y=0,Z=I.scroll_resilience;
N.source=ac;
N.popup=X;I.position.call(X,N);
if(X.html()===""){X.unbind(".popupIntent").bind("mouseenter.popupIntent",J.popup_enter).bind("mouseleave.popupIntent",J.popup_leave).bind("mousemove.popupIntent",J.stop).unbind("out").bind("out",L(ac))
}if(I.mode==="articles"&&g.platform.big_article_preview){ab=n("#zemanta-articles-wrap");
Y=ab[0].scrollTop;
if((Y<=this.offsetTop||(Y-this.offsetTop)/aa<0.4)&&(aa+this.offsetTop-Y<ab.outerHeight()||(aa+this.offsetTop-Y-ab.outerHeight())/aa<0.4)){I.create.call(X,N)
}else{return
}}else{I.create.call(X,N)
}if(Z){Z=P(ac,X,Z.wrap,Z.zero);
Z(true);r(O);
O=f(Z,23)}n(v).unbind("mousemove.popupIntent-"+I.popup_id).bind("mousemove.popupIntent-"+I.popup_id,function(){ac.trigger("mouseleave")
});I.beforeShow.call(X,N);
X.css("visibility","visible")
};M=function(){var X=this;
n("#"+I.popup_id+"."+X.id+":not(.active)").css("visibility","hidden").each(function(){I.destroy.call(this,N);
n(v).unbind("mousemove.popupIntent-"+I.popup_id);
r(O);n(X).removeClass(I.source_marker)
})};H.live("mouseenter",J.elm_enter).live("mouseleave",J.elm_leave);
return function(){if(!R){I.init.call(this,N);
R=true}}}(G,F)
};t.popup.enabled=true;
t.rdf_link=function(F,G){if(!F.target){return null
}var H=n.grep(F.target,function(J,I){return G?J.type==="rdf"&&J.url.indexOf("freebase.com")>-1:J.type==="rdf"
});if(H&&H.length>0){return H[0].url
}return null
};t.uri_add=function(F,G){var H=F.indexOf("?")>=0&&"&"||"?";
n.each(G,function(J,I){F+=H+encodeURIComponent(J)+"="+encodeURIComponent(I);
H="&"});return F
};t.ZTemplate=function(){function F(H){var G=null;
for(G in H){if(H.hasOwnProperty&&H.hasOwnProperty(G)||!t.ljext[G]){return false
}}return true
}return function(G){G=G||"";
var L=[],M=[],H=false,J=function(){var O=/\{(\w+)\}/g,N;
while((N=O.exec(G))!==null){L.push(N[1]);
M.push(new RegExp("{"+N[1]+"}","g"))
}H=true},K=function(Q){if(!H){J()
}var P=0,N=0,O=G;
if(Q){for(P=0,N=L.length;
P<N;P+=1){O=O.replace(M[P],Q[L[P]])
}}return O},I=null;
if(arguments.length<=1){J();
I=K}else{if(typeof arguments[1]==="object"){I=K(arguments[1])
}}if(F(this)){this.run=K
}else{return I
}}}();t.zTemplate=t.ZTemplate;
t.storage=(function(){var K="",H,F=l.stringify({}),N=v.location.host,J={localStorage:{test:function(){try{return !!b.localStorage
}catch(O){return false
}},init:function(){if(!b.localStorage[K]){b.localStorage[K]=F
}return b.localStorage[K]
},get:function(){return b.localStorage[K]
},set:function(O){b.localStorage[K]=O
},clear:function(){b.localStorage.removeItem(K)
}},globalStorage:{test:function(){try{return b.globalStorage!==H&&b.globalStorage[N]
}catch(O){return false
}},init:function(){if(b.globalStorage[N][K]){b.globalStorage[N][K]=F
}storage=b.globalStorage[N][K]
},get:function(){return b.globalStorage[N][K]
},set:function(O){b.globalStorage[N][K]=O
},clear:function(){b.globalStorage[N].removeItem(K)
}},cookie:{test:function(){try{var O="zemtest"+Math.random(),P="zemtestvalue";
n.cookie(O,P);
return n.cookie(O)===P
}catch(Q){return false
}},init:function(){if(!n.cookie(K)){n.cookie(K,F)
}},get:function(){var O=n.cookie(K);
n.cookie(K,O,{expires:30,path:"/"});
return O},set:function(O){n.cookie(K,O,{expires:30,path:"/"})
},clear:function(){n.cookie(K,F,{expires:30,path:"/"})
}}},I=function(){},G=(function(){var O="";
for(O in J){if(t.ljext[O]!==""){J[O].type=O;
if(J[O].test()){return J[O]
}}}return{init:I,get:I,set:I,clear:I}
}()),L=null;
function M(){var Q=G.get(),P={},O="";
if(Q){O=typeof Q;
if(O==="string"){try{P=l.parse(Q)
}catch(R){g.helpers.logGA(null,"widget/error/storage-data-error/"+g.interface_type+"/");
g.log("[storage] data error")
}}else{if(O==="object"&&!Array.isArray(O)){P=Q
}}}return P
}L={init:function(O){K=O||"defaultZemantaNS";
var R=G.init(),P=false;
if(G!==J.cookie&&(!R||R===F)&&J.cookie.get()){G.set(J.cookie.get())
}try{P=!l.parse(R)
}catch(Q){P=true
}if(P){G.set(F)
}},getAll:function(){return M()
},get:function(O,Q){var P=M();
Q=Q===H?null:Q;
return O in P?P[O]:Q
},set:function(O,Q){var P=M();
if(typeof O==="object"){n.each(O,function(S,R){P[S]=R
})}else{P[O]=Q
}G.set(l.stringify(P))
},clear:function(){G.clear()
},raw:function(){return G
}};return L
}());t.feedback=(function(){var K=16,J=16,G=true,H={animate:function(L,P,O,N,U,S){var V=N&&N.attr("src")||C+"core/img/zem_source_invalph.png",R=null,Q="",T=0,M=0;
J=N&&N.width()||J;
K=N&&N.height()||K;
if(N){T=Math.floor((J-50)/2);
M=Math.floor((K-50)/5);
Q="rect("+M+"px, "+(T+50)+"px, "+(M+50)+"px, "+T+"px)";
L.left-=T;L.top-=M
}R=n('<div style="position:absolute;left:'+L.left+"px;top:"+L.top+"px;width:"+J+"px;height:"+K+"px;z-index:10000;background:url("+V+') no-repeat;overflow:hidden;"></div>');
if(N){R.css("clip",Q)
}R.appendTo("body").animate({left:P.left,top:P.top},O,"easeInQuart",function(){n(this).remove();
if(S==="image-click"&&G){U.css({visibility:"",display:"none"}).fadeIn(300,function(){U.css("display","")
})}})},bounds_calculation:function(L,M){if(L.top<M.top){L.top=M.top;
g.log("[animate] height out of bounds. Too high")
}else{if(L.top>(M.top+M.height)){L.top=M.top+M.height-K;
g.log("[animate] height out of bounds. Too low")
}}if(L.left>(M.left+M.width)){L.left=M.left+M.width-J;
g.log("[animate] width out of bounds. Too right")
}else{if(L.left<M.left){L.left=M.left;
g.log("[animate] width out of bounds. Too left")
}}return L},modes:{"image-click":function(M,L){return{elStart:n(".zemanta-gallery-li.zemanta-gallery-img-clicked").eq(0)||n(".zemanta-gallery-li").eq(0),elEnd:L||M.win&&n(".zemanta-img",M.win.document).not(".zemanta-action-dragged").find("img").eq(0)}
},"article-click":function(M,L){return{elStart:n(".zemanta-article-li.zemanta-selected").eq(0)||n(".zemanta-article-li").eq(0),elEnd:L&&M.win&&n('.zemanta-article-ul-li a[href="'+L.url+'"]',M.win.document).eq(0)}
},"link-click":function(M,L){return{elStart:n(".zemanta-links-li.zemanta-selected").eq(0)||n(".zemanta-links-li").eq(0),elEnd:L&&M.win&&n('.zem_slink[href="'+t.encode_url(decodeURI(L.url))+'"]',M.win.document).or('.zem_slink[href="'+t.encode_uri(decodeURI(L.url))+'"]',M.win.document).eq(0)}
}}},I=function(L){var M=L&&L.offset()||{};
return{width:L&&L.width()||0,height:L&&L.height()||0,left:M.left||0,top:M.top||0}
},F={animate:function(N,S,M){if(!g.platform.animate_enabled){return
}var O=I(S),R={},T=null,P=g.platform.get_editor(false),L=null,Q=null;
if(!P.frame){g.log("[animate] frame element not found. Animation not supported");
return}if(H.modes[N]){L=H.modes[N](P,M);
S=S||L.elStart;
if(P.type==="RTE"){Q=L.elEnd
}}else{return
}T=I(P.frame);
if(P.type==="RTE"){if(Q.length===0){g.log("[animate] can not find target");
return}R=I(Q);
R.width=N==="image-click"?(R.width>50)&&R.width||160:R.width||10;
R.height=N==="image-click"?(R.height>50)&&R.height||160:R.height||10;
if(N==="link-click"){R.left=(R.left+T.left+R.width-20)-P.element.scrollLeft;
R.top=(R.top+T.top)+(R.height/3)-P.element.scrollTop
}else{R.left=(R.left+T.left)+(R.width/2)-P.element.scrollLeft;
R.top=(R.top+T.top)+(R.height/2)-P.element.scrollTop
}R=H.bounds_calculation(R,T)
}else{R.left=T.left+T.width/2;
R.top=T.top+T.height/2
}O.left=O.left+(N==="image-click"?0:O.width/2);
O.top=O.top+(N==="image-click"?0:O.height/2);
H.animate(O,R,500,(N==="image-click"?n("img",S):false),Q,N)
},prepare:function(M,L){if(!g.platform.animate_enabled){return L
}if(M==="image-click"&&G){L.find(".zemanta-img").not(".zemanta-action-dragged").find("img").eq(0).css("visibility","hidden")
}return L}};
return F}());
z=function(){function U(ah,af){var ag=Math.floor(Math.log(500/af)/Math.log(2));
return"http://maps.google.com/maps/api/staticmap?center="+ah+"&zoom="+ag+"&size=300x250&maptype=hybrid&sensor=false"
}function ac(af){return"http://i1.ytimg.com/vi/"+af+"/2.jpg"
}function aa(af){return"http://static.zemanta.com/core/img/lastfm_ro.png?"+af
}function ad(af){return"http://static.zemanta.com/core/img/wikinvest_ro_thumb.gif?"+af
}function J(af){return"http://static.zemanta.com/core/img/5min_ro_thumb.gif?"+af
}function G(af){return"http://assets.hulu.com/shows/show_thumbnail_"+af.replace(/[\s\-]/g,"_").toLowerCase()+".jpg"
}function Z(ah,af,aj){var ag="",ai=null;
ai=/ width=['"](\d+?)['"]/g;
ag=ah.replace(ai,' width="'+af+'"');
ai=/ height=['"](\d+?)['"]/g;
ag=ag.replace(ai,' height="'+aj+'"');
return ag}function N(ag,af,ah){return Z(ag,af,ah)
}function W(ag,af,ah){return Z(ag,af,ah)
}function L(ag,af,ah){return ag
}function H(ag,af,ah){return Z(ag,af,ah)
}function ae(ag,af,ah){return ag
}function T(ag,af,ah){return Z(ag,af,ah)
}function O(ag,af,ah){return ag
}function R(ah,af,ag){ah.url_m_w=af;
ah.url_m_h=ag;
if(ah.type==="Google Maps"){ah.html=N(ah.html,af,ag)
}else{if(ah.type==="YouTube"){ah.html=W(ah.html,af,ag)
}else{if(ah.type==="Last.fm"){ah.html=L(ah.html,af,ag)
}else{if(ah.type==="Slideshare"){ah.html=H(ah.html,af,ag)
}else{if(ah.type==="Wikinvest"){ah.html=ae(ah.html,af,ag)
}else{if(ah.type==="5min"){ah.html=T(ah.html,af,ag)
}else{if(ah.type==="Hulu"){ah.html=O(ah.html,af,ag)
}}}}}}}return ah
}function S(ah){var ag=ah.split(/ width=['"](\d+?)['"]/),aj=ah.split(/ height=['"](\d+?)['"]/),af=0,ai=0;
if(ag.length>2&&aj.length>2){af=parseInt(ag[1],10);
ai=parseInt(aj[1],10)
}return[af,ai]
}function X(af){return S(af)
}function F(af){return S(af)
}function K(af){return[184,140]
}function M(af){return S(af)
}function I(af){return[300,260]
}function ab(af){return S(af)
}function Y(af){return[210,725]
}function V(ai){var ah=ai.match(/src=(["']+)/),ag="\\s",af=null,aj=null;
if(ah){ah=ah[1];
ag=ah}else{ah=""
}af=new RegExp("src="+ah+"([^"+ag+"]+)"+ah);
aj=ai.match(af)||["",""];
return aj[1]
}function Q(af){var ag="",ah="";
if(af.indexOf("youtube.com")>-1){ah=af.split("/v/")[1].split("&")[0];
ag="http://www.youtube.com/watch/?v="+ah
}else{if(af.indexOf("last.fm")>-1){ah=af.split("radioURL=artist%2F")[1].split("%2Fsimilarartist")[0];
ag="http://www.last.fm/music/"+ah+"?ro"
}else{if(af.indexOf("5min.com")>-1){ag=af.split(/src=['"]/)[1].split(/['"]/)[0]
}else{if(af.indexOf("hulu.com")>-1){ah=af.split("show=")[1].split(/['"]/)[0];
ag="http://www.hulu.com/"+ah
}}}}return ag
}var P={link_to_oembed:function(al){var ak="",aj=al.url,am={version:"1.0",type:"rich",thumbnail_width:150,thumbnail_height:100,title:al.title||"",thumbnail_url:"",width:300},ag=aj.substring(aj.indexOf("?")+1,(aj+"#").indexOf("#")),ai="",ah="",af="";
if(aj.indexOf("youtube.com")>-1){ak=t.query_to_object(ag).v;
am.url="http://www.youtube.com/watch?v="+ak;
am.thumbnail_url=ac(ak);
am.html='<object width="300" height="242"><param name="movie" value="http://www.youtube.com/v/'+ak+'&hl=en&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'+ak+'&hl=en&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="242"></embed></object>';
am.height=242;
am.provider_name="YouTube";
am.id=ak}else{if(aj.indexOf("maps.google.com")>-1){ak=t.query_to_object(ag);
am.url=aj;am.thumbnail_url=U(ak.ll||ak.center,ak.spn);
am.html='<iframe width="300" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/?ie=UTF8&amp;ll='+(ak.ll||ak.center)+"&amp;spn="+(ak.spn||"10.0,10.0")+'&amp;sensor=false&amp;output=embed&amp;s=AARTsJqzARj-Z8VnW5pkPMLMmZbqrJcYpw"></iframe><br /><small><a href="http://maps.google.com/?ie=UTF8&amp;ll='+(ak.ll||ak.center)+"&amp;spn="+(ak.spn||"10.0,10.0")+'&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
am.height=250;
am.provider_name="Google Maps";
am.id=ak.ll||ak.center
}else{if(aj.indexOf("last.fm")>-1){ak=aj.split("music/")[1];
ah=ak;af=decodeURIComponent(ah);
while(ah!==af){ah=af;
af=decodeURIComponent(ah)
}ah=ah.replace(/\+/g," ");
ai="lfmMode=radio&amp;radioURL=artist%2F"+ak+"%2Fsimilarartists&amp;title="+encodeURIComponent(ah).replace(/%20/g,"+")+"+Radio&amp;theme=blue&amp;lang=en";
am.url=aj;am.html='<object width="184" height="140"><param name="movie" value="http://cdn.last.fm/widgets/radio/22.swf?'+ai+'"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always" /><embed src="http://cdn.last.fm/widgets/radio/22.swf?'+ai+'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="184" height="140"></embed></object>';
am.thumbnail_url=aa(ak);
am.thumbnail_width=am.width=184;
am.thumbnail_height=am.height=140;
am.provider_name="Last.fm";
am.id=ak}else{if(aj.indexOf("slideshare.net")>-1){ak=t.query_to_object(aj).doc;
am.thumbnail_width=120;
am.thumbnail_height=90;
am.width=425;
am.height=355;
am.html='<a id="__ss_'+t.query_to_object(aj).id+'" href="http://www.slideshare.net/"></a><object style="margin:0px" width="425" height="355"><param name="movie" value="http://static.slidesharecdn.com/swf/ssplayer2.swf?doc='+ak+'" /><param name="allowFullScreen" value="true"/><param name="allowScriptAccess" value="always"/><embed src="http://static.slidesharecdn.com/swf/ssplayer2.swf?doc='+ak+'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="355"></embed></object>';
am.provider_name="Slideshare"
}else{if(aj.indexOf("wikinvest.net")>-1){am.provider_name="Wikinvest";
ak=t.query_to_object(aj).flashvars.split(/ticker['"]:['"]/)[1].split(/['"]/)[0];
am.id=ak;am.html='<iframe src="http://cdn.wikinvest.com/wikinvest/api.php?action=getWikichartIframe&format=text&version=3&flashvars={"ticker":"'+ak+'"}" frameborder="0" width="300" height="260" marginheight="0" marginwidth="0" scrolling="no"></iframe>';
ak=ak.split(":");
am.url="http://finance.yahoo.com/q?s="+(ak.length===1?ak[0]:n.trim(ak[1]));
am.thumbnail_url=ad(ak.join("-"))||"http://static.zemanta.com/core/img/wikinvest_ro_thumb.gif";
am.thumbnail_width=150;
am.thumbnail_height=125;
am.width=300;
am.height=260
}else{if(aj.indexOf("5min.com")>-1){am.provider_name="5min";
am.url=aj.replace("Embeded","Video");
am.thumbnail_url="http://static.zemanta.com/core/img/5min_ro_thumb.gif";
am.thumbnail_width=140;
am.thumbnail_height=105;
am.width=300;
am.height=250;
am.html='<object width="300" height="250" id="FiveminPlayer" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="allowfullscreen" value="true"/><param name="allowScriptAccess" value="always"/><param name="movie" value="'+aj+'"/><embed src="'+aj+'" type="application/x-shockwave-flash" width="300" height="250" allowfullscreen="true" allowScriptAccess="always"></embed></object><br/><a href="'+am.url+'">Video at 5min</a>'
}else{if(aj.indexOf("hulu.com")>-1){am.url=aj;
ak=aj.split("hulu.com/")[1];
am.provider_name="Hulu";
am.thumbnail_width=145;
am.thumbnail_height=80;
am.width=210;
am.height=725;
am.html='<object width="210" height="455"><param name="movie" value="http://www.hulu.com/widget/embed/videopanel"></param><param name="bgcolor" value="0x000000" /><param name="wmode" value="transparent" /><param name="flashVars" value="partner=CSWidget&layout=Vertical2Thumbs&searchEnabled=false&sortEnabled=true&sortDefault=recentlyadded&watchOnHulu=true&show='+ak+'"></param><embed src="http://www.hulu.com/widget/embed/videopanel" type="application/x-shockwave-flash" flashVars="partner=CSWidget&layou=Vertical2Thumbs&searchEnabled=false&sortEnabled=true&sortDefault=recentlyadded&watchOnHulu=true&show='+ak+'" wmode="transparent" bgcolor="0x000000" width="210" height="455"></embed></object>';
am.id=ak}else{am=null
}}}}}}}return am
},get_provider:function(af){if(af.indexOf("youtube.com")>-1){return"YouTube"
}else{if(af.indexOf("maps.google.com")>-1){return"Google Maps"
}else{if(af.indexOf("last.fm")>-1){return"Last.fm"
}else{if(af.indexOf("slideshare.net")>-1){return"Slideshare"
}else{if(af.indexOf("finance.yahoo.com")>-1){return"Wikinvest"
}else{if(af.indexOf("5min.com")>-1){return"5min"
}else{if(af.indexOf("hulu.com")>-1){return"Hulu"
}}}}}}}},oembed_to_gallery_item:function(ai){var ah={active:0,attribution:"",confidence:0.5,description:"",license:"",width:0,height:0},aj="",ag=null,af=0;
ah.url_m=ah.url_s=ah.url_l="";
ah.url_m_w=ah.url_m_h=ah.url_l_w=ah.url_l_h=ah.url_s_w=ah.url_s_h=0;
if(ai&&ai.type==="rich"){ah.description=ai.title;
ah.source_url=ai.url;
ah.type=ai.provider_name;
ah.rich=true;
if(ai.provider_name==="YouTube"){ah.url_s=ah.url_m=ai.thumbnail_url;
ah.url_s_w=128;
ah.url_s_h=96;
ah.url_m_w=300;
ah.url_m_h=242;
ah.html=ai.html
}else{if(ai.provider_name==="Google Maps"){ag=t.query_to_object(ai.url.substr(ai.url.indexOf("?")+1));
af=Math.floor(Math.log(500/spn)/Math.log(2));
ah.url_s="http://maps.google.com/maps/api/staticmap?center="+(ag.center||ag.ll)+"&zoom="+(ag.zoom||af||10)+"&size=75x75&maptype=hybrid&sensor=false";
ah.url_s_w=75;
ah.url_s_h=75;
ah.url_m="http://maps.google.com/maps/api/staticmap?center="+(ag.center||ag.ll)+"&zoom="+(ag.zoom||af||10)+"&size=300x250&maptype=hybrid&sensor=false";
ah.url_m_w=300;
ah.url_m_h=250;
ah.html=ai.html
}else{if(ai.provider_name==="Last.fm"){ah.url_s=ah.url_m=ai.thumbnail_url;
ah.url_s_w=75;
ah.url_s_h=75;
ah.url_m_w=184;
ah.url_m_h=140;
ah.html=ai.html;
ah.attribution='<a href="http://www.last.fm/widgets/?url=artist%2F'+ai.title+'%2Fsimilarartists&amp;colour=blue&amp;size=regular&amp;autostart=0&amp;from=code&amp;widget=radio">Get your own widget</a> at <a href="http://www.last.fm">Last.fm</a>';
ah.source_url=ah.source_url+"?ro"
}else{if(ai.provider_name==="Slideshare"){aj=ai.html.split(/.*id="__ss_(\d*)".*/)[1];
ah.url_s=ah.url_m=ai.thumbnail_url;
ah.url_s_w=120;
ah.url_s_h=90;
ah.url_m_w=425;
ah.url_m_h=355;
ah.html=ai.html.replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/ style=".*?"/g,"").replace(/<div(.*?)(<div>.*?<\/div>)<\/div>/,"<div$1</div>").replace(/<div.*?>|<\/div>/g,"");
ah.html=ah.html.replace("<a",'<a id="ss_'+aj+'"')
}else{if(ai.provider_name==="Wikinvest"){ag=t.query_to_object(V(ai.html)).flashvars.split(/ticker['"]:['"]/)[1].split(/['"]/)[0];
ah.url_s=ah.url_m=ad(ag.replace(":","-"));
ah.url_s_w=75;
ah.url_s_h=75;
ah.url_m_w=300;
ah.url_m_h=260;
ah.html=ai.html.replace(/"/g,"%22").replace(/'/g,'"')
}else{if(ai.provider_name==="5min"){ah.url_s=ah.url_m=ai.thumbnail_url;
ah.url_s_w=140;
ah.url_s_h=105;
ah.url_m_w=300;
ah.url_m_h=250;
ah.html=ai.html.replace(/"/g,"%22").replace(/'/g,'"').replace(/ style=".*?"/g,"").replace(/<div.*?>|<\/div>/g,"");
ah.html=ah.html.replace("<object",'<object thumbnail_id="'+ai.thumbnail_url.split("5min.com/")[1]+'"');
ah.html=T(ah.html,ah.url_m_w,ah.url_m_h)
}else{if(ai.provider_name==="Hulu"){ah.url_s=ah.url_m=ai.thumbnail_url;
ah.url_s_w=145;
ah.url_s_h=80;
ah.url_m_w=210;
ah.url_m_h=725;
ah.html=ai.html
}}}}}}}}else{ah=null
}return ah},rich_to_object:function(ag){var aj="",al=null,ap=null,af=0,an=0,ah="",am=n(ag).find(".zemanta-img-attribution"),ai={},aq=[],ao=null;
try{aj=decodeURIComponent(ag.innerHTML)
}catch(ak){al=ag.getElementsByTagName("embed")[0]||{src:ag.getElementsByTagName("object")[0].innerHTML};
ap=g.rich.link_to_oembed({url:Q(al.src)});
af=parseInt(al.width,10)||0;
an=parseInt(al.height,10)||0;
ap.type=ap.provider_name;
aj=af?R(ap,af,an).html:ap.html
}ai={html:aj,license:"",active:0,attribution:am.length?am.html():"",confidence:0.5,description:"",rich:true};
if(aj.indexOf("maps.google.com")>-1){ai.type="Google Maps";
ah=(aj.indexOf("ll=")>0&&aj.split("ll=")||aj.split("center="))[1].replace(/&amp;/g,"&").split("&")[0];
if(ah){aq=X(aj);
ai.element=ah;
ai.url_s_w=75;
ai.url_s_h=75;
ai.url_m_w=aq[0]||300;
ai.url_m_h=aq[1]||250;
ai.url_m=U(ah);
ai.url_s=ai.url_m.replace("size="+aq[0]+"x"+aq[1],"size=75x75");
ai.source_url="http://maps.google.com/maps?ll="+ah
}}else{if(aj.indexOf("youtube.com")>-1){ai.type="YouTube";
ah=aj.split("youtube.com/v/")[1].replace(/&amp/g,"&").split("&")[0];
if(ah){aq=F(aj);
ai.element=ah;
ai.url_s_w=128;
ai.url_s_h=96;
ai.url_m_w=aq[0]||300;
ai.url_m_h=aq[1]||242;
ai.url_m=ac(ah);
ai.url_s=ai.url_m;
ai.source_url="http://www.youtube.com/watch?v="+ah
}}else{if(aj.indexOf("last.fm")>-1){ai.type="Last.fm";
ah=aj.split(/radioURL=artist[%2F|\/]{1}/)[1].split(/[%2F|\/]{1}/)[0];
if(ah){aq=K(aj);
ai.description=decodeURIComponent(ah.replace(/\+/g,"%20"));
ai.element=ah;
ai.url_s_w=75;
ai.url_s_h=75;
ai.url_m_w=aq[0];
ai.url_m_h=aq[1];
ai.url_m=aa(encodeURIComponent(decodeURIComponent(ah)));
ai.url_s=ai.url_m;
ai.source_url="http://www.last.fm/music/"+ah+"?ro"
}}else{if(aj.indexOf("slideshare.net")>-1){ai.type="Slideshare";
ah=aj.split("?doc=")[1].replace(/&amp;/g,"&").split("&")[0];
if(ah){aq=M(aj);
ai.element=ah;
ai.url_s_w=120;
ai.url_s_h=90;
ai.url_m_w=425;
ai.url_m_h=355;
ai.source_url="";
ai.url_s="http://cdn.slidesharecdn.com/"+ah+"-thumbnail-2?"+ah.split("-")[1];
ai.url_m=ai.url_s
}}else{if(aj.indexOf("wikinvest.com")>-1){ai.type="Wikinvest";
ah=aj.split(/ticker['"]:['"]/)[1].split(/['"]/)[0];
if(ah){aq=I(aj);
ai.element=ah;
ai.title=ah;
ah=ah.split(":");
ai.source_url="http://finance.yahoo.com/q?s="+(ah.length===1?ah[0]:ah[1]);
ai.url_s_w=75;
ai.url_s_h=75;
ai.url_m_w=300;
ai.url_m_h=260;
ai.url_s=ad(ah.join("-"))||"http://static.zemanta.com/core/img/wikinvest_ro_thumb.gif";
ai.url_m=ai.url_s
}}else{if(aj.indexOf("5min.com")>-1){ai.type="5min";
ah=aj.split("Embeded/")[1].split("/")[0];
if(ah){aq=ab(aj);
ao=aj.search(/a href=['"]http:\/\/www.5min.com\/Video\//);
if(ao>-1){ai.source_url="http://www.5min.com/Video/"+aj.slice(ao+34).split(/['"]/)[0]
}else{ai.source_url="http://www.5min.com/Video/"+ah
}ai.url_s_w=140;
ai.url_s_h=105;
ai.url_m_w=aq[0]||300;
ai.url_m_h=aq[1]||250;
ao=aj.search(/thumbnail_id=['"]/);
if(ao>-1){ai.url_s="http://pthumbnails.5min.com/"+aj.slice(ao+14).split(/['"]/)[0]
}else{ai.url_s=J(ah)
}ai.url_m=ai.url_s
}}else{if(aj.indexOf("hulu.com")>-1){ai.type="Hulu";
ah=aj.split("show=")[1].split(/['"]/)[0];
if(ah){aq=Y(aj);
ai.url_s_w=145;
ai.url_s_h=80;
ai.url_m_w=210;
ai.url_m_h=725;
ai.url_s=G(ah);
ai.url_m=ai.url_s
}}else{ai={}
}}}}}}}return ai
},rich_to_images:(function(){var ag=null,af=function(ah){ah=typeof ah==="object"&&ah||g.control.sync.dom();
if(typeof ah==="function"){return ah.simple(af,this,[])
}ag=ag||j();
if(n.trim(ah.text())===""&&ag+5000>j()){return h(af,500)
}if(g.platform.ro_rich_to_object(ah)){g.platform.set_html(ah.html())
}};return af
}()),set_rich:function(ag){var af=false;
n(".zemanta-img",ag).each(function(){var ai=n("img",this),aj="",ah=0,ak=0,al=null;
if(ai.length){ai=ai[0];
aj=ai.src;ah=ai.width||parseInt(n(ai).css("width").split("px")[0],10);
ak=ai.height||parseInt(n(ai).css("height").split("px")[0],10);
al=g.helpers.object_search(g.gallery._images,"url_m",aj);
if(al&&al.rich){if((ah!==al.url_m_w||ak!==al.url_m_h)&&ah*ak){al=R(al,ah,ak)
}af=g.platform.ro_image_to_rich(this,al)
}}});return af
},_success:function(ai,ag,am){am=am||g.control.sync.dom();
if(typeof am==="function"){return am.simple(P._success,this,arguments)
}var al=null,af=[],ak=0,ah=null,aj="";
g.log("rich success");
g.rich._objects=ai.rich_objects;
for(;ak<ai.rich_objects.length;
ak+=1){ah=ai.rich_objects[ak];
aj=g.rich.get_provider(ah.url);
if(g.platform.rich_platforms[aj]){ah.type="rich";
ah.provider_name=aj;
al=g.rich.oembed_to_gallery_item(ah);
if(al){af.push(al)
}}}if(af.length){g.gallery._old=g.gallery._images;
g.gallery._images=af;
g.gallery.process(true,am)
}g.log("rich done");
ag(am)}};return P
}();p=function(){var F={initialize:function(){if(!n("#zemanta-head").length){if(n("#zemanta-control").length){n("#zemanta-control").attr("id","zemanta-head").removeClass("zemanta").html("<h1></h1>")
}else{n("#zemanta-sidebar").prepend('<div id="zemanta-head"><h1></h1></div>')
}if(n("#zemanta-source").length){n("#zemanta-source").attr("id","zemanta-control").removeClass("zemanta")
}else{n("#zemanta-head").after('<div id="zemanta-control"></div>')
}n("#zemanta-filter").remove();
n("#zemanta-message, #zemanta-articles, #zemanta-gallery").removeClass("zemanta");
if(n("#zemanta-preferences").length){n("#zemanta-preferences").attr("id","zemanta-tools").removeClass("zemanta")
}else{if(!n("#zemanta-tools").length){n("#zemanta-sidebar").append('<div id="zemanta-tools"></div>')
}}if(!n("#zemanta-sidebar").parents(".zemanta-wrap").length){n("#zemanta-sidebar").parent().addClass("zemanta-wrap")
}}n("#zemanta-head h1").html("<div>Zemanta</div>").addClass("zemanta-source-global").addClass("zemanta-source").addClass("zemanta-source-on");
n("#zemanta-control").hide();
if(!n("#zemanta-message").prev("#zemanta-control").length){n("#zemanta-message").insertAfter("#zemanta-control")
}g._set_status(0);
n("#zemanta-control-update").disableTextSelect().unbind("click").bind("click",function(){if(g._enabled){g.control.update_manual=true;
g.control.update(n("#zemanta-keyword-input").val(),false);
g.control.update_manual=false
}});function G(){n("#zemanta-sidebar").removeClass("zemanta-disabled");
h(g.control.preferences.update,1);
n("#zemanta-message").html(g.microcopy.loading_preferences).show()
}if(g.widget_enabled===false){o.trigger("initDisable");
g.one("editorRich",G);
n("#zemanta-sidebar").addClass("zemanta-disabled");
n("#zemanta-message").hide()
}else{G()}h(g.control.initial.load,1);
g.control.refine();
if(g.platform.rich_supported){g.rich.rich_to_images()
}o.trigger("controlInitialized")
},initial:(function(){var H=null,G={load:function(){g.helpers.load_file(A+"initial/zemanta-initial.js",G.parse)
},parse:function(){H=g.initial;
try{if(H.messages&&H.messages.type==="zemanta"){g.control.messaging.init(H.messages.data)
}}catch(I){g.helpers.logGA(null,"widget/log/initial-failed/"+g.interface_type+"/");
g.log("Initial failed: "+l.stringify(I))
}if(!g.helpers.empty(g._preferences)){g.initialize_parts(0)
}},inspire:function(){var J=[],I=[];
try{if(H.images&&H.images.type&&g.gallery.sources[H.images.type]){J=g.gallery.sources[H.images.type](H.images.data);
if(J.length&&n("#zemanta-gallery-thumbnails").children().length===0){g.gallery._images=J.slice(0,g.gallery_width*3)
}}if(H.articles&&H.articles.type&&g.articles.sources[H.articles.type]){g.latest_articles="Interesting Articles";
I=g.articles.sources[H.articles.type](H.articles.data);
if(I.length&&n("#zemanta-articles-list").children().length===0){g.articles._articles=I
}}}catch(K){g.helpers.logGA(null,"widget/log/inspiration-failed/"+g.interface_type+"/");
g.log("Inspiration failed: "+l.stringify(K))
}}};return G
}()),isFirstVisit:function(){var H=10,I=Math.min(H,Math.max(0,g.helpers.storage.get("hasBeenHere",0))),G=I<H;
if(G){g.helpers.storage.set("hasBeenHere",I+1)
}if(t.check_feature("ZemantaFirstVisit")){return true
}return G},recordVisit:(function(){var G=0,H=function(I){return function(){h(function(){n("#zemanta-visitrecorder"+I).remove()
},100)}};if(q){return function(I){}
}else{return function(J){if(g._preferences.account_type==="pro"){return
}var I=H(G);
n('<div id="zemanta-visitrecorder'+G+'" class="zemanta-visitrecorder"><iframe src="http://www.zemanta.com/widgetstats/'+J+'/" width="1" height="1"></iframe></div>').find("iframe").load(I).end().appendTo("#zemanta-sidebar");
h(I,5000);G+=1
}}}()),messaging:(function(){var G={};
G={init:function(I){var H=G.storage.init();
if(H!==false){G.parse(I||b.zemantaLatestMessage,H)
}else{G.hide()
}},helpers:{checkValidity:function(J,K){var I=false,H=null;
if((typeof J==="object"&&J[0]==="*")||J==="*"){I=true
}else{if(typeof J==="string"||(typeof J==="object"&&typeof J.test==="function")){try{H=typeof J==="object"?J:new RegExp(J);
I=H.test(K)
}catch(L){g.log("Tried regex but failed miserably")
}}else{n.each(J,function(N,M){if(M===K){I=true
}})}}return I
},browser:(function(){var H="";
n.each(n.browser,function(J,I){if(I===true){H=J
}});return H
}()),icons:{notice:"core/img/lightbulb.png",warning:"core/img/bullet_warn.png"}},parse:function(I,H){if(!I||typeof I.sort!=="function"){return
}I.sort(function(K,J){return(K.id>J.id)?1:-1
});n.each(I,function(){if(!g.platform.disable_messages&&G.helpers.checkValidity(this.browsers,G.helpers.browser)&&G.helpers.checkValidity(this.browsers,G.helpers.browser+n.browser.version)&&G.helpers.checkValidity(this.platforms,g.interface_type)&&Date.parse(this.date_begin)<j()&&Date.parse(this.date_end)>j()&&(H.id===undefined||this.id>=H.id)&&!(this.id===H.id&&H.read)){G.queue.append(this)
}})},hide:function(){n("#zemanta-message").hide();
n("#zemanta-sidebar").hide().show().css("display","")
},storage:(function(){var H={init:function(){var I=n.cookie("zemantaLatestSeenCookie"),J=null;
if(I){H.set(I)
}if(g.helpers.storage.get("zemantaLatestSeenCookie")===null){H.set({id:0,first:null,last:null,times:0,read:false})
}J=H.get();
if(g.control.message===null){return false
}return J},get:function(){return g.helpers.storage.get("zemantaLatestSeenCookie")
},set:function(I){g.helpers.storage.set("zemantaLatestSeenCookie",I)
}};return H
}()),clearError:function(){var H=n("#zemanta-message div.zemanta-message-wrap");
if(H.hasClass("warning")||H.hasClass("error")){G.queue.next()
}},queue:(function(){var H=[],J={},I={render:function(){var P=H[0],Q=-1,O=P.title||H.length===1&&"New Message!"||"New Messages!",K=G.helpers.icons[P.type||"notice"],N=P.type==="warning"&&'If you see this often, please contact us at <a href="mailto:support@zemanta.com?subject=Problems%20with%20Zemanta&body=My%20api%20key%20is%20'+g.api_key+"%20and%20I%20get%20this%20error%20a%20lot%3A%20"+encodeURIComponent(P.text)+'.">support@zemanta.com</a>.'||"",L=P.url?'<a href="'+P.url+'" target="_blank" class="ext">Read more</a>':"",M=P.text.indexOf("<")===0?P.text+(N&&" <p>"+N+"</p>"||""):"<p>"+P.text+L+(N&&" "+N||"")+"</p>";
if(M.indexOf("{")>-1){M=t.zTemplate(M,g)
}if(M.indexOf("zemanta-message-openanddismiss")>-1){o.trigger("special_message_shown")
}if((Q=M.indexOf("</p>"))>-1){M=M.substring(0,Q)+L+M.substring(Q)
}else{M+=L}n('<div class="zemanta-message-wrap '+(P.type||"notice")+'"><div id="zemanta-message-title"><span>'+H.length+"</span><h5>"+O+"</h5></div>"+M+(P.nodismiss?"":'<div class="zemanta-message-buttons"><a href="" id="zemanta-message-ok"><span>Got it!</span></a></div>')+"</div>").appendTo(n("#zemanta-message").empty().show().addClass("zemanta-wrapped-message")).find("#zemanta-message-ok").disableTextSelect().click(function(R){R.preventDefault();
J.next()}).end().find(".zemanta-message-openanddismiss").click(function(R){R.preventDefault();
b.open(n(this).attr("href"));
o.trigger("special_message_clicked");
J.next()});
if(P.id){I.markAsSeen(P)
}},markAsSeen:function(M){var L=new Date().toGMTString(),K=G.storage.get();
if(typeof K==="string"){K=l.parse(K)
}if(M.id!==K.id){K.id=M.id;
K.times=0;K.first=L;
K.read=false
}K.last=L;K.times+=1;
G.storage.set(K)
},markAsRead:function(L){var K=G.storage.get();
if(K.id!==L.id){g.log("SHOULD NEVER HAPPEN!");
K.id=L.id}K.read=true;
G.storage.set(K)
}};J={render:function(){if(H.length>0){I.render()
}else{G.hide()
}},prepend:function(K){if(!H[0]||!H[0].errortype||K.errortype!==H[0].errortype){H.unshift(K)
}I.render()
},append:function(K){H.push(K);
if(H.length===1){I.render()
}},next:function(){var K=H.shift();
if(K&&K.id){I.markAsRead(K)
}J.render()
}};return J
}())};return G
}()),refine:function(){var H="",G="zemanta-refine-on",L=null;
if(n("#zemanta-refine").length>0){return
}function K(){n("#zemanta-refine-box").hide();
n("#zemanta-refine-toggle").removeClass(G);
H=n("#zemanta-refine-input").val();
n("#zemanta-refine-input").val("");
g.helpers.logGA(null,"widget/stat/jqz/refine/close/"+g.interface_type+"/")
}function J(){n("#zemanta-refine-input").val(H);
n("#zemanta-refine-box").show();
n("#zemanta-refine-input").css("width",n("#zemanta-refine-box").width()-n("#zemanta-refine-close").width()-26-n("#zemanta-refine-button").width()-2-7-8-10).focus();
n("#zemanta-refine-toggle").addClass(G);
g.helpers.logGA(null,"widget/stat/jqz/refine/open/"+g.interface_type+"/")
}function I(){if(n("#zemanta-refine-input").val().length>0){g.control.update_manual=true;
g.control.update(n("#zemanta-refine-input").val(),false);
g.control.update_manual=false;
K()}}function M(O){var N=O&&O.jquery?O:n(v);
N.find("#zemanta-refine-toggle, #zemanta-refine-close").click(function(P){if(n("#zemanta-refine-toggle."+G).length){K()
}else{if(n("#zemanta-refine-toggle.zemanta-refine-toggle-enabled").length){J()
}}}).end().find("#zemanta-refine-input").keypress(function(P){var Q="",R=this;
if(P.which===13){P.preventDefault();
Q=n(this).val();
h(function(){if(Q===n(R).val()){I()
}},1)}}).end().find("#zemanta-refine-button").click(function(P){P.preventDefault();
I()}).keypress(function(P){if(P.which===13){P.preventDefault();
I()}}).end();
return !!L.find("#zemanta-refine-toggle").length
}L=n('<div id="zemanta-refine"><div id="zemanta-refine-toggle" class="zemanta-refine-toggle-disabled" title="Nothing to search from...">Search</div><div id="zemanta-refine-box"><div id="zemanta-refine-close" title="Close Search">Search</div><div id="zemanta-refine-field"><input type="text" name="refine" id="zemanta-refine-input" value="" /><input type="button" id="zemanta-refine-button" value="GO" /></div></div></div>');
if(!M(L)){h(M,1000)
}L.appendTo("#zemanta-control");
g.bind("recommendationsProcessed.enableRefine",function(){n("#zemanta-refine-toggle.zemanta-refine-toggle-disabled").removeClass("zemanta-refine-toggle-disabled").addClass("zemanta-refine-toggle-enabled").attr("title","Open Search").each(function(){g.unbind("recommendationsProcessed.enableRefine")
})})},sources:function(G){var H=n("#zemanta-head div.zemanta-source-mine");
if(!H.length){H=n('<div class="zemanta-source-mine zemanta-source"><div class="zemanta-disabled" title="&quot;My sources&quot; is available after you have some recommendations">My Sources</div></div>').disableTextSelect().insertAfter("#zemanta-head h1").fin()
}if(G){H.find("div").attr("title","Click to switch to your selection").removeClass("zemanta-disabled").end().parent().find(".zemanta-source:not(#zemanta-preferences)").unbind("click").click(function(){var J=n(this),I="zemanta-source-on";
if(!J.hasClass(I)){J.parent().find(".zemanta-source").removeClass(I).end().end().addClass(I);
if(t.check_feature("ZemantaNoSelection")){delete g.preferences.sourcefeed_ids;
delete g.preferences.flickr_user_id;
delete g.preferences.social_timestamp
}if((g.preferences.sourcefeed_ids===undefined||g.preferences.sourcefeed_ids.length===0)&&g.preferences.flickr_user_id===undefined&&g.preferences.social_timestamp===undefined){if(J.hasClass("zemanta-source-mine")){n("#zemanta-sidebar").find("#zemanta-gallery, #zemanta-articles, #zemanta-links, #zemanta-tools").hide();
if(!n("#zemanta-noselection").show().length){n('<div id="zemanta-noselection"><h2><span class="zemanta-noselection-title">Let\'s get personal!</span></h2><div id="zemanta-noselection-wrap"><p>Get tailored recommendations by using your very own sources.</p><ul><li>Tell us your blog URL and we\'ll recommend <em>your old posts</em>. <a href="'+g._preferences.config_url+'" class="prefs">Open preferences</a></li><li>Add RSS feeds to get recommendations from <em>your favorite blogs</em>. <a href="'+g._preferences.config_url+'" class="prefs">Open preferences</a></li><li>Get images from <em>your Flickr account</em> recommended to you. <a href="'+g._preferences.config_url+'" class="prefs">Open preferences</a></li><li>Enable only parts of Zemanta <em>you</em> use. Make inserted links open in a <em>new window</em>. <a href="'+g._preferences.config_url+'" class="prefs">Open preferences</a></li><li>Add your Amazon Affiliate ID to cater to your marketing needs. <a href="'+g._preferences.config_url+'" class="prefs">Open preferences</a></li></ul><p class="notfinding">Not finding exactly what you need? <a href="http://getsatisfaction.com/zemanta/" class="contact">Give us a shout</a> and we\'ll be more than happy to help you find what you are looking for!</p></div></div>').find("a.prefs").click(g.control.preferences.open).end().insertAfter("#zemanta-articles");
g.control.recordVisit("noselection")
}g.helpers.logGA(null,"widget/stat/jqz/my_sources/"+g.interface_type+"/")
}else{n("#zemanta-noselection").hide();
n("#zemanta-sidebar").find("#zemanta-gallery, #zemanta-articles, #zemanta-links, #zemanta-tools").show()
}}else{g.control.update(n("#zemanta-keyword-input").val(),false)
}}}).end().end()
}},_apikey_isvalid:function(I){var G=null,H=false;
G=arguments.callee.rgx=arguments.callee.rgx||new RegExp("^[0-9a-z]{24}$");
H=G.test(I);
return H},_add_stats:function(){var G="",H={};
n.each(["mozilla","safari","opera","msie"],function(){if(n.browser[this]){H.browser=this.toString()
}});try{G=b.top.ZemantaGetReleaseId()
}catch(I){g.log(I)
}if(G){H.releaseid=G
}return function(L){var J=0,K=0,M=null;
L=n.zextend(L,H,{"interface":g.interface_type,deployment:g.deployment});
if(b.innerWidth){J=b.innerWidth;
K=b.innerHeight
}else{M=(v.documentElement&&v.documentElement.clientWidth)?v.documentElement:v.body;
J=M.clientWidth;
K=M.clientHeight
}if(J){L.viewportWidth=J
}if(K){L.viewportHeight=K
}return L}}(),_readside_samefeatures:function(H,I){var G=I.split(" "),J=H.filter(".zem-script").attr("class")!==null&&H.attr("class").split(" ");
return G.length===J.length-1&&g.helpers.merge_arrays({e:true,f:function(K){return K
}},J,G).duplicate.length===G.length
},preferences:(function(){var H={busy:false,process:function(K){K=K||g.control.sync.dom();
if(typeof K==="function"){n("#zemanta-message").html(g.microcopy.fetching_content);
return K.simple(H.process,this,arguments)
}var Q=g._preferences,P=null,I=null,M={change:false,nosig:true},O=false;
o.trigger("preferencesReceived",Q);
if(Q.api_key&&g.control._apikey_isvalid(Q.api_key)){g.api_key=Q.api_key;
o.trigger("apikey_change")
}if(Q.status==="ok"){Q.config_url=t.uri_add(Q.config_url||"",{platform:g.interface_type});
G.response(Q);
if(Q.link_target&&Q.link_target!==g.link_target){if(Q.link_target==="1"||Q.link_target==="_blank"){g.link_target=true
}else{g.link_target=false
}}if(Q.doctype&&Q.doctype!==g.doctype){O=g.doctype!=="-1";
if(Q.doctype==="True"||Q.doctype==="1"||Q.doctype==="raw"){g.doctype="1"
}else{g.doctype="0"
}H.update_templates();
if(O){M.change=H.update_doctype(K)||M.change
}}if(Q.image_position&&Q.image_position!==g.image_position.index){if(Q.image_position==="1"||Q.image_position==="left"){g.image_position={index:"1",title:"left"}
}else{g.image_position={index:"0",title:"right"}
}H.update_templates()
}if(Q.return_rdf_links&&Q.return_rdf_links!==g.return_rdf_links&&g.platform.rdf_supported&&g.widget_version>1){g.return_rdf_links=Q.return_rdf_links;
if(!g.return_rdf_links){if(arguments.callee.links_template_html){g.platform.links_template_html=arguments.callee.links_template_html;
delete arguments.callee.links_template_html
}}else{P=arguments.callee.links_template_html=g.platform.links_template_html;
g.platform.links_template_html=function(R){var S=P(R),T=n("<div>"+S+"</div>");
if(R.rdf){T.find("a").each(function(){n(this).attr({rel:"ctag:means"+(n(this).attr("rel")?" "+n(this).attr("rel"):""),"xmlns:ctag":"http://commontag.org/ns#","typeof":"ctag:Tag",resource:R.rdf,property:"ctag:label"}).addClass("rdfa")
}).end();return T.html()
}else{return S
}}}M.change=H.update_rdf_links(K)||M.change
}if(Q.nofollow&&Q.nofollow!==g.nofollow){if(Q.nofollow==="1"||Q.nofollow===true){g.nofollow=true
}else{g.nofollow=false
}H.update_templates()
}if(Q.readside_javascript!==g.readside_javascript&&g.platform.rjs_supported&&g.widget_version>1){g.readside_javascript=Q.readside_javascript;
if(!g.readside_javascript){if(arguments.callee.signature_modify){g.platform.signature_modify=arguments.callee.signature_modify;
delete arguments.callee.signature_modify
}}else{I=arguments.callee.signature_modify=g.platform.signature_modify;
g.platform.signature_modify=function(T,S){var R=I.call(this,T,S);
return g.readside_javascript?n("<div>"+R+"</div>").find(".zemanta-pixie").find(".zem-script").remove().end().append("SCRIPTPLACEHOLDER").end().html().replace("SCRIPTPLACEHOLDER",'<span class="zem-script '+g.readside_javascript+'"><script type="text/javascript" src="http://static.zemanta.com/readside/loader.js" defer="defer"><\/script></span>'):R
}}g.pixie=g.platform.signature_modify(g.pixie);
if(K.find(".zemanta-pixie-img").length!==0){if((K.find(".zem-script").length===0&&arguments.callee.signature_modify)||(K.find(".zem-script").length!==0&&(!arguments.callee.signature_modify||!g.control._readside_samefeatures(K.find(".zem-script"),g.readside_javascript)))){M={change:true,nosig:false}
}}}if(n("#zemanta-preferences").length===0){n('<div id="zemanta-preferences" class="zemanta-source"><div><a href="'+Q.config_url+'" title="Open Zemanta preferences">Preferences</a></div></div>').find("a").click(g.control.preferences.open).end().appendTo("#zemanta-head")
}g.platform.gallery_template_item=Q.image_layout_item||g.platform.gallery_template_item;
g.platform.articles_template_wrapper=Q.article_layout_wrapper||g.platform.articles_template_wrapper;
g.platform.articles_template_item=Q.article_layout_item||g.platform.articles_template_item;
g.platform.links_template_item=Q.link_layout_item||g.platform.links_template_item;
G.hide_parts();
M.change=g.platform.ro_rerender_rich(K)||M.change;
for(var N=0,L=["gallery","articles","links","tags"],J="";
N<L.length;
N++){g.control.widget[L[N]]=!Q["disable_"+L[N]]
}if(parseInt(Q.autoupdate,10)===0){g.control.watcher={start:function(){},stop:function(){},resume:function(){},is_on:function(){return false
}}}if(M.change){g.platform.set_html(K.html(),M.nosig)
}}o.trigger("preferencesProcessed",K);
g.control.update(n("#zemanta-keyword-input").val(),true,K)
},update_doctype:function(I){if(I){I.find(".zemanta-img").zattr("style",g.doctype_image1.substring(g.doctype_image1.indexOf('"')+1,g.doctype_image1.lastIndexOf('"'))||null).find("img").zattr("style",g.doctype_image2.substring(g.doctype_image2.indexOf('"')+1,g.doctype_image2.lastIndexOf('"'))||null).end().find(".zemanta-img-attribution").zattr("style",g.doctype_image3.substring(g.doctype_image3.indexOf('"')+1,g.doctype_image3.lastIndexOf('"'))||null).end().end().find(".zemanta-related").each(function(){var J=n(this);
if(g.widget_version>2){J.replaceWith(n(g.platform.articles_template_wrapper).findWithSelf(".zemanta-article-ul").append(J.find(".zemanta-article-ul-li")).end())
}else{J.replaceWith(g.doctype_related1+n(this).nextAll(".zemanta-article-ul").html()+g.doctype_related2)
}}).end().find(".zemanta-related-title").each(function(){var K=n(this),J=null;
if(K.parents(".zemanta-related").length>0||K.hasClass("zemanta-related")){return
}J=K.nextAll(".zemanta-article-ul");
K.remove();
if(g.widget_version>2){J.replaceWith(n(g.platform.articles_template_wrapper).findWithSelf(".zemanta-article-ul").append(J.find(".zemanta-article-ul-li")).end())
}else{J.replaceWith(g.doctype_related1+J.html()+g.doctype_related2)
}}).end().find(".zemanta-pixie").zattr("style",g.doctype_pixie1.substring(g.doctype_pixie1.indexOf('"')+1,g.doctype_pixie1.lastIndexOf('"'))||null).end().find(".zemanta-pixie-img").zattr("style",g.doctype_pixie2.substring(g.doctype_pixie2.indexOf('"')+1,g.doctype_pixie2.lastIndexOf('"'))||null).end();
return true
}return false
},update_templates:function(){g.helpers.copy(["doctype_image1","doctype_image2","doctype_image3","doctype_related1","doctype_related2","doctype_pixie1","doctype_pixie2"]).from(g.platform.control_setHTML(g.doctype,g.image_position.title,g.nofollow,g.link_target)).to(g);
return false
},update_rdf_links:function(I){I.find(".zem_slink").each(function(){if(g.return_rdf_links==="0"){n(this).zattr("rel",n.grep(n(this).attr("rel").split(" "),function(K,J){return K!=="ctag:means"
}).join(" ")).removeAttr("xmlns:ctag").removeAttr("typeof").removeAttr("resource").removeAttr("property").removeClass("rdfa")
}else{g.log("Converting exiting links to RDF doesn't work yet.")
}}).end();return true
},success:function(I){H.busy=false;
g._preferences=I;
H.process()
},error:function(J,I){H.busy=false;
if(I){g._set_status(6,I)
}},prefs_window:null,watch:function(){var J=false;
try{J=(H.prefs_window&&!H.prefs_window.closed)
}catch(I){g.log("Error while watching prefs:",I)
}if(J){h(H.watch,2000)
}else{g._set_status(0,{text:"Reload this page for new preferences to take effect.",type:"info"});
h(function(){g._set_status(2);
G.update();
if(H.prefs_window){n("#zemanta-control-update").disableTextSelect().unbind("focus")
}},3000)}}},G={open:function(I){if(I&&I.preventDefault){I.preventDefault()
}if(typeof b.ZemantaWindowOpen!=="undefined"){H.prefs_window=b.ZemantaWindowOpen(g._preferences.config_url,600,900)
}else{H.prefs_window=b.open(g._preferences.config_url,null,"height=600, width=900, scrollbars=1, menubar=no, toolbar=no, location=no, status=no")
}if(H.prefs_window){H.watch()
}else{n("#zemanta-control-update").disableTextSelect().unbind("focus").one("focus",function(J){H.watch();
n(b).unbind("focus")
});n(b).one("focus",H.watch)
}g.helpers.logGA(null,"widget/stat/jqz/preferences/"+g.interface_type+"/");
return false
},update:function(){if(H.busy){return
}var I={method:"zemanta.preferences",api_key:g.api_key,format:"json"};
if(g._lastplatformerror){I.lasterror=g._lastplatformerror
}I=g.control._add_stats(I);
H.busy=true;
g.post(g.proxy?g.proxy:g.api_url,I,H.success,H.error)
},response:function(I){if(I){g.preferences=g.helpers.copy(["pixie","amazon_id","flickr_user_id","daylife_id","social_timestamp","target_types","image_types","flickr_license","autofeed_ids","sourcefeed_ids","image_max_h","image_max_w","return_rdf_links","careful_pc","no_log","personal_scope","account_type"]).from(I).to();
if(g.preferences.amazon_id===""){delete g.preferences.amazon_id
}}else{return g.preferences?g.preferences:{}
}},hide_parts:function(){var I=g._preferences;
if(I.target_types===""){n("#zemanta-links").hide()
}if(I.image_types===""){n("#zemanta-gallery").hide()
}if(I.personal_scope&&!I.sourcefeed_ids){n("#zemanta-articles").hide()
}if(I.return_tags==="0"){n("#zemanta-tags").hide()
}if(I.account_type&&I.account_type==="pro"){n("#zemanta-source, #zemanta-preferences, #zemanta-head .zemanta-source-mine").hide();
n("#zemanta-sidebar").addClass("zemanta-pro-sidebar").find("#zemanta-message").hide()
}}};return G
}()),cancel_update:function(G){o.trigger("updateCanceled");
g.control.watcher.start(G)
},update:function(G,S,K,O){if(!g._enabled){return
}if(g.helpers.empty(g._preferences)){g.control.preferences.update();
return}g.control.watcher.stop();
K=K||g.control.sync.dom();
if(typeof K==="function"){return K.simple(g.control.update,this,[G,S])
}O=O||g.control.sync.title();
if(typeof O==="function"){return O.simple(g.control.update,this,[G,S,K])
}g._enabled=false;
var Q=g.rid||(g.rid=g.platform.control_getRID(K)),M=K.html(),P=n.trim(K.text()),N=n.trim(O),H=n.zextend({method:"zemanta.suggest",articles_highlight:1,pc:1,text_title:N,manual:g.control.update_manual||false,format:"json",text:M,return_rich_objects:g.platform.rich_supported&&1||0,api_key:g.api_key},g.control.preferences.response());
if(S&&P.length<g.constants.min_text_for_update&&!G){g.control.watcher.start();
g._enabled=true;
return}if(Q){H.post_rid=Q
}if(n("#zemanta-head div.zemanta-source-mine").hasClass("zemanta-source-on")&&!n("#zemanta-noselection").length){H.personal_scope=1
}if(g._preferences.channel){H.channel=g._preferences.channel
}if(g.readside_javascript&&n.inArray("more-info",g.readside_javascript.split(" "))>-1){H.return_rdf_links="1"
}if(G){H.emphasis=G
}if((!P||P==="Write text here...")&&!H.emphasis){if(!S){g._set_status(5)
}else{g._enabled=true
}g.log("No update - no content or emphasis.");
return g.control.cancel_update(g.platform.filter_zemanta(K))
}var J=["gallery","articles","links","tags"],R={gallery:["images","rich_objects"],articles:["articles"],links:["markup","rdf_links"],tags:["keywords"]};
for(var L=0;
L<J.length;
L++){if(!g.control.widget[J[L]]){for(var I=0;
I<R[J[L]].length;
I++){H["return_"+R[J[L]][I]]=0
}}}if(g.platform.big_article_preview){H.articles_preview=1
}H=g.control._add_stats(H);
g.post(g.proxy?g.proxy:g.api_url,H,g.control._success,g.control._error);
o.trigger("updateRecommendations",H);
g._set_status(4);
if(g.control.update_manual){g.helpers.logGA(null,"widget/stat/jqz/"+(G?"refine":"update")+"/"+g.interface_type+"/")
}},_error:function(H,G){g.control.cancel_update();
if(G){g._set_status(6,G)
}},_success:function(H){g._set_status(2);
var G=null,I=null;
if(H.status==="fail"){g.helpers.logGA(null,"widget/error/api/response-status-is-fail/"+g.interface_type+"/");
g.log("Data is fail.");
return"error"
}H.images=H.images||[];
H.articles=H.articles||[];
H.markup=H.markup||{};
H.markup.links=H.markup.links||[];
H.keywords=H.keywords||[];
H.rich_objects=H.rich_objects||[];
g.control.messaging.clearError();
o.trigger("recommendationsReceived",g._lastresponse["zemanta.suggest"]);
if(H.status==="ok"){G=[g.articles._success,function(K,J,L){if(K.rich_objects.length){K.images=g.gallery.sources.oembed(n.grep(K.rich_objects,function(M){M.provider_name=g.rich.get_provider(M.url);
M.type="rich";
return false
})).concat(K.images)
}g.gallery._success(K,J,L)
},g.links._success,g.tags._success];
G.push(function(K,J,L){g.control._pixiechange(K,L);
o.trigger("recommendationsProcessed",g._lastresponse["zemanta.suggest"]);
g.control.watcher.start(g.platform.filter_zemanta(L))
});I=g.control.sync.dom();
if(typeof I==="function"){I(function(J){g.control.process(G,H,J)
})}else{g.control.process(G,H,I)
}}},process:function(G,I,J){var H=G.shift();
H(I,function(K){if(G.length){if(J){g.control.process(G,I,J)
}else{g.control.process(G,I,J)
}}},J)},_pixiechange:function(I,K){K=K||g.control.sync.dom();
if(typeof K==="function"){return K.simple(g.control._pixiechange,this,arguments)
}var G="",H="",J=null;
G=g.control._pixiegetimage(n("<div>"+g.pixie+"</div>").find("img").attr("src"));
H=g.control._pixiegetimage(n("<div>"+I.signature+"</div>").find("img").attr("src"));
g.rid=g.rid||g.platform.control_getRID(n("<div>"+I.signature+"</div>"));
if(G!==H||g.pixie.indexOf(g.rid)<0){g.pixie=g.platform.signature_modify(I.signature,g.doctype)
}J=K.find(".zemanta-pixie-img");
if(J.length>0&&(g.control._pixiegetimage(J.attr("src"))!==H||J.attr("src").indexOf(g.rid)<0)){g.platform.set_html(g._pixie(K.html(),true),true)
}},_pixiegetimage:function(H){if(!H){return
}if(H.indexOf("img.zemanta.com")>=0){H=H.split("?")[0];
return H.substr(H.lastIndexOf("/")+1)
}else{var G=H.split("/");
G.splice(0,4);
return G.join("/")
}},isEnabled:function(G){if(typeof G==="string"&&G.length>2048){return(/<[^>]*id="zemanta-disable"[^<]*>/g).exec(G)===null
}return n("<div>"+G+"</div>").find("#zemanta-disable").length===0
},sync:(function(){var H=0,I={data:{},createId:function(){H+=1;
return"async-"+j()+"-"+H
},execute:function(L,J,K){g.log("executing "+L);
I.data[L]=null;
delete I.data[L];
J.apply(b,K)
},set_data:function(K,J){g.log("setting data: "+K+" ("+I.data[K]+"):",J);
if(typeof I.data[K]==="function"){I.execute(K,I.data[K],J)
}else{I.data[K]=J
}},set_callback:function(K,J){g.log("setting callback: "+K+" ("+I.data[K]+")",J);
if(typeof I.data[K]!=="undefined"){I.execute(K,J,I.data[K])
}else{I.data[K]=J
}},create_setcallback:function(K){var J=function(L){I.set_callback(K,L)
};J.simple=function(N,M,L){g.log("simple callback:",N);
J(function(O){N.apply(M,Array.prototype.concat.apply([],L).concat(O))
})};return J
}},G={dom:function(K){var M=I.createId(),J=function(){I.set_data(M,arguments)
},L=g.platform.get_dom(K,J);
return typeof L!=="undefined"&&L||I.create_setcallback(M)
},title:function(K){var M=I.createId(),J=function(){I.set_data(M,arguments)
},L=g.platform.get_title(K,J)||" ";
return typeof L!=="undefined"&&L||I.create_setcallback(M)
}};return G
}()),dom:function(){return{get:function(G){return g.platform.get_dom(G)
},set:function(){}}
}(),watcher:function(){var J=null,H=null,O=15000,K=0,G={},N=function(P,Q){Q=Q||g.control.sync.dom(true);
if(typeof Q==="function"){return Q.simple(N,this,[P])
}P(n.trim(Q.text()).length)
},L=function(){},I=function(P){var R=j(),Q=Math.min(O,Math.max(O/2,O-(R-K)));
if(Math.abs(P-J)>300){J=P;
g.control.update(n("#zemanta-keyword-input").val(),false)
}else{m(H);
H=h(L,Q)}},M=function(){m(H);
H=null};L=function(){K=j();
N(I)};G={start:function(P){M();
K=j();N(function(Q){J=Q;
I(Q)},P)},resume:function(){if(!H){M();
L()}},stop:function(){M()
},is_on:function(){return !!H
}};return G
}(),published_ping:function(){var G={url:"",rid:""};
return function(H){if(H&&(G.url!==H||G.rid!==g.rid)){G={url:H,rid:g.rid};
g.post(g.proxy?g.proxy:g.api_url,g.control._add_stats({method:"zemanta.post_published_ping",format:"json",current_url:b&&b.location?b.location.href:"",post_rid:G.rid,post_url:G.url,api_key:g.api_key}),function(I){g.log(I)
})}return G
}}(),widget_reset:function(H){var G=n("#zemanta-links"),I=n("#zemanta-tags");
g._lastrequest={};
g._lastresponse={};
g._lasttransport={};
n("#zemanta-gallery-thumbnails").empty();
g.gallery._images=[];
n("#zemanta-articles-list").empty();
g.articles._articles=[];
if(!G.parent("#zemanta-sidebar").length){n("#zemanta-links").remove()
}else{n("#zemanta-links-div-ul li:not(.zemanta-title)").remove()
}g.links._links=[];
if(!I.parent("#zemanta-sidebar").length){n("#zemanta-tags").remove()
}else{n("#zemanta-tags-div-ul li:not(.zemanta-title)").remove()
}g.tags._tags=[];
g.rich._objects=[];
g.control.watcher.stop();
if(H){g.initialize_parts(0);
g.control.watcher.resume()
}},widget_open:function(){g.control.watcher.resume();
n("#zemanta-tags, #zemanta-links, #zemanta-sidebar").show();
g.widget_opened=true;
o.trigger("widgetOpen")
},widget_close:function(){g.control.watcher.stop();
n("#zemanta-tags, #zemanta-links, #zemanta-sidebar").hide();
g.widget_opened=false;
o.trigger("widgetClose")
},widget_enable:function(){var G=n("#zemanta-disabled");
g.links_not_in_sidebar=g.links_not_in_sidebar||!(n("#zemanta-sidebar #zemanta-links").length);
g.tags_not_in_sidebar=g.tags_not_in_sidebar||!(n("#zemanta-sidebar #zemanta-tags").length);
G.css({height:"100%",display:"block"}).animate({height:"0"},300,function(){n(this).css({height:"",display:"none"})
});if(g.links_not_in_sidebar){n("#zemanta-links").show(300,function(){g.links.redraw()
})}if(g.tags_not_in_sidebar){n("#zemanta-tags").show(300,function(){g.tags.redraw()
})}g.widget_enabled=true;
g.control.watcher.resume();
o.trigger("widgetEnable")
},widget_disable:function(H){H=H||g.platform.widget_nonrte_screen;
var G=H();g.links_not_in_sidebar=g.links_not_in_sidebar||!(n("#zemanta-sidebar #zemanta-links").length);
g.tags_not_in_sidebar=g.tags_not_in_sidebar||!(n("#zemanta-sidebar #zemanta-tags").length);
if(G.parent().length){G.css({height:0,display:"block"}).animate({height:"100%"},300,function(){n(this).css({height:""})
})}else{G.appendTo("#zemanta-sidebar").show()
}if(g.links_not_in_sidebar){n("#zemanta-links").hide(300)
}if(g.tags_not_in_sidebar){n("#zemanta-tags").hide(300)
}g.widget_enabled=false;
g.control.watcher.stop();
o.trigger("widgetDisable")
},widget:{gallery:true,articles:true,links:true,tags:true,change_state:function(H,J,I){if(typeof H==="string"){g[H].change_state(J,I)
}else{if(typeof H==="object"){for(var G in H){if(g.hasOwnProperty(G)){g[G].change_state(H[G],I)
}}}else{return
}}}}};return F
}();D=function(){var I="zemanta-gallery-li",F="zemanta-gallery-img",H={initialized:false,sources:{flickr_license:{1:"CreativeCommons NonCommercial ShareAlike",2:"CreativeCommons NonCommercial",3:"CreativeCommons NonCommercial NoDerivs",4:"CreativeCommons Attribution only",5:"CreativeCommons ShareAlike",6:"CreativeCommons NoDerivs"}},icons:["",'<img src="'+t.duckie()+'" class="zemanta-source-icon" />','<img src="'+C+'core/img/type_video.png" class="zemanta-source-icon" />','<img src="'+C+'core/img/type_map.png" class="zemanta-source-icon" />','<img src="'+C+'core/img/type_5min.png" class="zemanta-source-icon" />','<img src="'+C+'core/img/type_slideshare.png" class="zemanta-source-icon" />'],activeTypes:["","zemanta-gallery-img-clicked","zemanta-gallery-img-dragged"],template:t.zTemplate('<li id="{elid}" class="'+I+'{selection}" alt="Add/remove &quot;{desc}&quot;"><img id="img_{id}" class="'+F+'" src="{src}" style="{style}" />{icon}<div class="zemanta-selector"></div>{promoted}</li>'),handlers:{click:function(L){L.stopPropagation();
var K=n(this),J=n("."+F,this).attr("src"),M=g.helpers.object_search(g.gallery._images,"url_s",J);
n("#zemanta-gallery-popup").css("visibility","hidden");
if(J&&M){if(L.metaKey||L.ctrlKey){return b.open(M.source_url)
}if(n.grep(H.activeTypes,function(O,N){return O&&K.hasClass(O)
}).length){g.gallery._select(M,false)
}else{g.gallery._select(M,true)
}}},error:function(J){if(this.src==="#"||this.src.charAt(-1)==="#"||this.src.indexOf("http://api.zemanta.com")===0||this.src.indexOf("http://maps.google.com")===0){return
}n(this).parent().remove()
},boxhover:function(J){H.helpers.hidemessage(true)
}},helpers:{createResizeHandler:function(J){return function(O){var M=J.img,N=0,L=0,K="";
if(M){N=Math.floor((O.url_s_w-50)/2);
L=Math.floor((O.url_s_h-50)/5);
K="rect("+L+"px, "+(N+50)+"px, "+(L+50)+"px, "+N+"px)";
M.find("."+F).css({clip:K,position:"absolute",marginTop:(-L)+"px",marginLeft:(-N)+"px"})
}}},showmessage:function(M,J){var K=n("#zemanta-gallery"),L=K.find("div.zemanta-message");
if(!L.length){n('<div class="zemanta-message'+(J&&" zemanta-autohide"||"")+'"><div class="zemanta-message-wrap">'+M+"</div></div>").insertAfter(n("h2",K))
}else{L[J?"removeClass":"addClass"]("zemanta-autohide").find(".zemanta-message-wrap").html(M)
}},hidemessage:function(J){var K=n("#zemanta-gallery div.zemanta-message.zemanta-autohide");
if(J){K.animate({opacity:0,height:0},function(){n(this).remove()
})}else{K.remove()
}},prepDesc:function(N,M){N=(N.length>100)?N.substring(0,100)+"...":N;
M=M||30;var L="<br />",K=L.length,J=M;
for(;J<N.length;
J+=M){if(N.substring(J-M,J-K).indexOf(" ")===-1){N=N.substring(0,J-K)+L+N.substring(J-K)
}}return N},prepSource:function(K){if(K.source){return K.source
}var J="",L=K.source_url;
if(L.indexOf("wikipedia.org")!==-1||L.indexOf("wikimedia.org")!==-1){J="wikipedia"
}else{if(L.indexOf("crunchbase.com")!==-1){J="crunchbase"
}else{if(L.indexOf("last.fm")!==-1){J="lastfm"
}else{if(L.indexOf("ytimg.com")!==-1||L.indexOf("youtube.com")!==-1){J="youtube"
}else{if(L.indexOf("maps.google.com")!==-1||L.indexOf("api.zemanta.com")!==-1){J="googlemaps"
}else{if(L.indexOf("finance.yahoo.com")!==-1){J="yahoofinance"
}else{J=g.helpers.extract_hostname(L).split(".")[0]
}}}}}}K.source=J;
return J},prepMore:(function(){var J={wikipedia:"Wikipedia",crunchbase:"CrunchBase",lastfm:"Last.fm",youtube:"YouTube",googlemaps:"Google Maps",yahoofinance:"Yahoo! Finance"};
return function(M){if(M.more_link){return M.more_link
}var L=M.source||H.helpers.prepSource(M),K=J[L];
if(!K){K=L.substring(0,1).toUpperCase()+L.substring(1)
}if(g.platform.big_article_preview){K='<a class="ext zemanta-big-preview-morelink" href="'+M.source_url+'">'+K+"</a>"
}else{K='<a class="ext" href="'+M.source_url+'">More info at '+K+"</a>"
}M.more_link=K;
return K}}())},eliminateDuplicates:function(J){g.gallery._images=g.helpers.merge_arrays({p:"url_m"},n.each(g.gallery._images,function(){this.first=true
}),(J?g.gallery._old:n.each(g.gallery._old,function(){delete this.first
})))},syncData:function(J){g.gallery._images=g.platform.gallery_active(g.gallery._images,J);
g.gallery._images.sort(function(L,K){return(!L.active&&!K.active)?0:L.active?!K.active?-1:1:1
})},prepData:function(){n.each(g.gallery._images,function(J,K){if(!K.hash){K.hash=t.elf_hash(K.source_url+K.url_s);
if(g.link_target){K.attribution=K.attribution.replace(/<a href=/g,'<a target="_blank" href=')
}}})},centerImage:function(K,R){var N={width:"100%",height:"100%",position:"absolute",left:0,top:0},P=n(K).parent().css(N).end().fin(),S=P.realSize(),Q=S.w,M=S.h,J=0,O=P.parents("#zemanta-media-content").css({left:0,top:0,position:"relative"}),L=O.parents("#zemanta-gallery-popup-media");
R=R||1;if(Q===0&&M===0){return
}P.css(N);J=Q/M;
if(J>R){L.css("height",O.width()/J);
R=J;O.css({height:0,paddingTop:(R/J*100/R)+"%",top:(((1-R/J)/2)*100/R)+"%"})
}else{O.css({width:0,height:"100%",paddingLeft:(J/R*100)+"%",marginLeft:(((1-J/R)/2)*100)+"%"})
}L.find("#zemanta-gallery-popup-preloader").css("visibility","hidden");
P.css("visibility","")
}},G={_images:[],_old:[],pages:0,imageConfig:{},exposed:{helpers:{prepSource:H.helpers.prepSource}},sources:{flickr:function(L){var J=H.sources.flickr_license,K=[];
if(L.stat==="ok"){n.each(L.photos.photo,function(N,O){if(O.title.length===0){O.title="Description unavailable"
}var M="http://farm"+O.farm+".static.flickr.com/"+O.server+"/"+O.id+"_"+O.secret;
K.push({url_l:M+"_l.jpg",url_m:M+"_m.jpg",url_s:M+"_s.jpg",url_s_w:75,url_s_h:75,url_m_w:0,url_m_h:0,source_url:"http://www.flickr.com/photos/"+O.owner+"/"+O.id+"/",height:1111,width:1111,license:J[O.license],description:O.title,confidence:0.5,poc:true,attribution:'Image by <a href="http://www.flickr.com/photos/'+O.owner+"/"+O.id+'/" >'+O.ownername+"</a> via Flickr"})
})}return K
},oembed:function(K){var J=[];
n.each(K,function(M,N){var L=g.rich.oembed_to_gallery_item(N);
if(L){delete L.active;
delete L.height;
delete L.html;
delete L.rich;
delete L.type;
delete L.width;
J.push(L)}});
return J}},initialize:function(){var L=n("#zemanta-gallery"),K={};
if(!L.length){g.helpers.logGA(null,"widget/warn/jqz/gallery-initialize-failed/"+g.interface_type+"/");
return g.log("Not initializing gallery - no wrapper")
}n('<h2><span class="zemanta-gallery-title">Media Gallery</span> <a class="zemanta-help" title="Need help with Media Gallery?" href="http://www.zemanta.com/faq/quickhelp/?gallery#faqid-56">?</a></h2><div id="zemanta-gallery-wrap"><ul id="zemanta-gallery-thumbnails"></ul></div>').appendTo(L.empty().mouseover(H.helpers.hidemessage));
n("#zemanta-sidebar #zemanta-gallery-wrap").height(g.helpers.storage.get("#zemanta-sidebar #zemanta-gallery-wrap-size")===0?0:g.helpers.storage.get("#zemanta-sidebar #zemanta-gallery-wrap-size")||g.platform.gallery_height);
G.imageConfig=(function(){var N={isInitialized:false,currentObj:null,currentConfig:{},exactImageUrl:"",imageSize:0,initialize:function(){var O=n('<div id="zemanta-gallery-config"><div class="zemanta-gallery-config-head"><h2><span class="zemanta-gallery-config-title">Image Settings</span><a href="" id="zemanta-gallery-config-close">Close</a></h2></div><div class="zemanta-gallery-config-main"><ol><li class="zemanta-gallery-config-alignbox"><form><fieldset><legend>Position</legend><a class="zemanta-help" title="Need help with Image Settings?" href="http://www.zemanta.com/faq/#faqid-95">?</a><ul><li><label for="zemanta-gallery-config-align-left"><span class="zemanta-gallery-config-alignicon left"></span><input type="radio" name="zemanta-gallery-config-align" id="zemanta-gallery-config-align-left" value="left" />Left</label></li><li><label for="zemanta-gallery-config-align-center"><span class="zemanta-gallery-config-alignicon center"></span><input type="radio" name="zemanta-gallery-config-align" id="zemanta-gallery-config-align-center" value="center" />Center</label></li><li><label for="zemanta-gallery-config-align-right"><span class="zemanta-gallery-config-alignicon right"></span><input type="radio" name="zemanta-gallery-config-align" id="zemanta-gallery-config-align-right" value="right" />Right</label></li></ul></fieldset></form></li><li class="zemanta-gallery-config-captionbox"><form><fieldset><legend><label for="zemanta-gallery-config-caption">Caption<input type="checkbox" name="zemanta-gallery-config-caption" id="zemanta-gallery-config-caption" value="override" /></label></legend><p><textarea id="zemanta-gallery-config-caption-text" name="zemanta-gallery-config-caption-text" rows="2" cols="26" disabled="disabled"></textarea></p></fieldset></form></li><li class="zemanta-gallery-config-sizebox"><form><fieldset><legend>Size</legend><ul class="zemanta-gallery-config-sizebox-column left"><li><label for="zemanta-gallery-config-size-small"><input type="radio" name="zemanta-gallery-config-size" id="zemanta-gallery-config-size-small" value="small" />Small</label></li><li><label for="zemanta-gallery-config-size-medium"><input type="radio" name="zemanta-gallery-config-size" id="zemanta-gallery-config-size-medium" value="medium" />Medium</label></li><li><label for="zemanta-gallery-config-size-large"><input type="radio" name="zemanta-gallery-config-size" id="zemanta-gallery-config-size-large" value="large" />Large</label></li></ul><ul class="zemanta-gallery-config-sizebox-column right"><li><label for="zemanta-gallery-config-size-custom"><input type="radio" name="zemanta-gallery-config-size" id="zemanta-gallery-config-size-custom" value="custom" />Custom (width)</label></li><li><input type="text" name="zemanta-gallery-config-size-custom-value" id="zemanta-gallery-config-size-custom-value" value="100" /></li></ul></fieldset></form></li><li class="zemanta-gallery-config-sourcebox"><p><span>Source:</span> <a id="zemanta-gallery-config-source" href="http://www.zemanta.com" alt="http://www.zemanta.com" target="_blank">http://www.zemanta.com</a></p></li></ol><div class="zemanta-gallery-config-actions"><a href="" id="zemanta-gallery-config-done"><span>Done</span></a><a href="" id="zemanta-gallery-config-remove"><span>Remove</span></a></div></div></div>');
n("#zemanta-gallery-config").remove();
if(!N.isInitialized){n("#zemanta-sidebar").append(O);
if(g.platform.hide_align_image_config){n("#zemanta-gallery-config .zemanta-gallery-config-alignbox").remove()
}O.css("display","none");
O.find("#zemanta-gallery-config-close").click(function(P){M.done(P);
M.close(P)}).end().find("#zemanta-gallery-config-done").click(function(P){M.done(P);
M.close(P)}).end().find("#zemanta-gallery-config-remove").click(function(P){M.remove(P);
M.close(P)});
O.find("#zemanta-gallery-config-align-left").click(function(P){M.alignImage(P,"left")
}).end().find("#zemanta-gallery-config-align-center").click(function(P){M.alignImage(P,"center")
}).end().find("#zemanta-gallery-config-align-right").click(function(P){M.alignImage(P,"right")
}).end();O.find("#zemanta-gallery-config-caption-text").bind("keyup",function(P){M.changeCaption(P)
});O.find("#zemanta-gallery-config-caption").change(function(P){if(n(this).attr("checked")==="checked"){M.changeCaption(P);
O.find("#zemanta-gallery-config-caption-text").attr("disabled",false)
}else{M.changeCaption(P,true);
O.find("#zemanta-gallery-config-caption-text").attr("disabled",true)
}});O.find("#zemanta-gallery-config-size-small").click(function(P){M.resizeImage(P,"url_s");
O.find("#zemanta-gallery-config-size-custom-value").attr("disabled",true)
});O.find("#zemanta-gallery-config-size-medium").click(function(P){M.resizeImage(P,"url_m");
O.find("#zemanta-gallery-config-size-custom-value").attr("disabled",true)
});O.find("#zemanta-gallery-config-size-large").click(function(P){M.resizeImage(P,"url_l");
O.find("#zemanta-gallery-config-size-custom-value").attr("disabled",true)
});O.find("#zemanta-gallery-config-size-custom").click(function(P){if(n("#zemanta-gallery-config-size-custom-value").val()){M.resizeImage(P,"custom")
}O.find("#zemanta-gallery-config-size-custom-value").attr("disabled",false)
});O.find("#zemanta-gallery-config-size-custom-value").bind("keyup",function(P){M.resizeImage(P,"custom")
});O.find("form").bind("submit",function(P){P.preventDefault()
});N.isInitialized=true
}}},M={isOpen:false,alignImage:function(O,Q){var P=g.platform.get_image_align_element(n('a[href="'+N.currentObj.source_url+'"]',g.platform.get_editor().element));
g.platform.change_image_alignment(P,Q)
},resizeImage:function(S,Q){var P=g.platform.get_editor(),R=0,O=0,U=n('a[href="'+N.currentObj.source_url+'"]',P.element).closest(".zemanta-img"),T=function(W,V){if(typeof V==="string"){R=N.currentObj[V+"_w"]||null;
O=N.currentObj[V+"_h"]||null;
if(R){W.find("img").attr("width",R).attr("height",O).attr("src",N.currentObj[V]);
g.platform.set_image_wrapper_size(W,R)
}}else{if(typeof V==="number"){W.find("img").attr("width",V).attr("height",null);
g.platform.set_image_wrapper_size(W,V)
}}};customSize=parseInt(n("#zemanta-gallery-config-size-custom-value").val(),10);
P.ignoreDOMevents=true;
if(Q==="custom"&&customSize&&customSize>=50){T(U,customSize)
}else{if(Q!=="custom"){T(U,Q)
}}P.ignoreDOMevents=false
},changeCaption:function(R,Q){var P=g.platform.get_editor(),S=n('a[href="'+N.currentObj.source_url+'"]',P.element).closest(".zemanta-img"),O=g.platform.get_image_caption_elm(S);
if(Q){g.platform.remove_image_caption_elm(O)
}else{if(O.length){g.platform.update_image_caption_text(O,n("#zemanta-gallery-config-caption-text").val())
}else{g.platform.add_image_caption_elm(S,n("#zemanta-gallery-config-caption-text").val())
}}},open:function(U){var Q=function(W,V){if(!n(W.target).closest(V).length){M.close(W)
}},T=null,S=null,R=false,P="none",O=false;
if(M.isOpen&&N.currentObj&&N.currentObj.source_url===U.source_url){return
}S=g.helpers.object_search(g.gallery._images,"source_url",U.source_url);
N.currentObj=S||U;
N.currentObj.source_url_bare=N.currentObj.source_url.replace(/https?:\/\//ig,"");
T=n('a[href="'+N.currentObj.source_url+'"]',g.platform.get_editor().element).eq(0).closest(".zemanta-img");
T.find("img").addClass("zemanta-img-configured");
P=g.platform.get_image_alignment(T);
n("#zemanta-gallery-config .zemanta-gallery-config-alignbox input:checked").removeAttr("checked");
n("#zemanta-gallery-config-align-"+P).attr("checked","checked");
if(g.platform.get_image_caption_text(T)){n("#zemanta-gallery-config-caption").attr("checked",true);
n("#zemanta-gallery-config-caption-text").attr("disabled",false);
n("#zemanta-gallery-config-caption-text").val(g.platform.get_image_caption_text(T).replace(/\n/gi,""))
}n("#zemanta-gallery-config-source").attr("alt",N.currentObj.source_url).attr("href",N.currentObj.source_url).text((N.currentObj.source_url_bare.length>20)?N.currentObj.source_url_bare.substring(0,20)+"...":N.currentObj.source_url_bare);
N.exactImageUrl=T.find("img").attr("src");
N.imageSize=parseInt(T.find("img").attr("width")||T.find("img").width(),10);
n.each({url_s:"small",url_m:"medium",url_l:"large"},function(V,W){O=!(N.currentObj&&N.currentObj[V]&&(N.currentObj[V+"_w"]||N.currentObj[V+"_h"]));
n("#zemanta-gallery-config-size-"+W).attr("disabled",O)
});if(!N.currentObj.url_s_h){n("#zemanta-gallery-config-size-custom").attr("checked",true);
n("#zemanta-gallery-config-size-custom-value").val(!isNaN(N.imageSize)&&N.imageSize||"")
}else{n.each({url_s:"small",url_m:"medium",url_l:"large"},function(V,W){if(N.currentObj[V+"_w"]===N.imageSize&&N.currentObj[V]===N.exactImageUrl&&!R){n("#zemanta-gallery-config-size-"+W).attr("checked",true);
R=true}});if(!R){n("#zemanta-gallery-config-size-custom").attr("checked",true);
n("#zemanta-gallery-config-size-custom-value").val(N.imageSize)
}else{n("#zemanta-gallery-config-size-custom-value").attr("disabled",true)
}}if(!M.isOpen){n("#zemanta-gallery-config").slideToggle("slow")
}n("body").bind("click.image_config",function(V){if(M.isOpen){Q(V,"#zemanta-gallery-config")
}});n(g.platform.get_editor().element).parent().bind("click.image_config",function(V){if(M.isOpen){Q(V,n(V.target).closest(".zemanta-img"))
}});M.isOpen=true
},close:function(O){if(O&&O.preventDefault){O.preventDefault()
}if(M.isOpen){n("#zemanta-gallery-config").slideUp("slow",function(){n(this).css("display","none")
})}n.each(["small","medium","large","custom"],function(P,Q){n("#zemanta-gallery-config-size-"+Q).attr("disabled",false);
n("#zemanta-gallery-config-size-"+Q).attr("checked",false)
});n("#zemanta-gallery-config-size-custom-value").val("");
n("#zemanta-gallery-config-caption-text").val("");
n("#zemanta-gallery-config-caption").attr("checked",false);
N.currentObj=null;
N.currentConfig={};
n(g.platform.get_editor().element).unbind("click.image_config");
n("body").unbind("click.image_config");
M.isOpen=false
},done:function(O){},remove:function(O){n('a[href="'+N.currentObj.source_url+'"]',g.platform.get_editor().element).closest(".zemanta-img").remove()
}};N.initialize();
return M}());
if(g.platform.image_config){g.bind("image_selected",function(M,N){G.imageConfig.open(N);
M.preventDefault()
});g.bind("image_remove",function(M,N){G.imageConfig.close(M);
M.preventDefault()
});g.bind("selection_updated",function(M,N){G.imageConfig.close(M);
M.preventDefault()
})}K={popup_id:"zemanta-gallery-popup",source_marker:"zemanta-gallery-img-hover",mode:"gallery",parent_selector:g.platform.gallery_popup_parent,init:function J(O,M){g.dnd.setup();
var P={},N=0,R=0,Q=n("#zemanta-gallery-thumbnails .zemanta-gallery-li");
Q.each(function(T,U){var S=(n(U).position()||{}).left;
if(N<S){R+=1;
P[S]=T;N=S}else{return false
}});if(!M&&R>g.gallery_width){g.platform.widget_resize();
J(O,true)}else{O.last_pos=N;
O.full_width=Math.max(O.last_pos+50-12,g.gallery_width*62-12);
if(Q.length>g.gallery_width){g.gallery_width=R
}}},element_height:function(M){return n(M).get_element_height(false,true,true,true,true)-1
},position:function(M){g.platform.gallery_popup_position(M,this)
},create:function(R){var U=R.source,M=this,N=g.platform.big_gallery_preview&&(M.width()-20)||M.width(),P=null,W=1,O=null,Q=g.helpers.object_search(g.gallery._images,"url_s",U.find("."+F).attr("src")),V=false,T=null,S=M;
if(g.platform.big_gallery_preview){n("#zemanta-gallery-popup").addClass("zemanta-big-preview big-gallery-preview")
}if(!Q){M.trigger("out");
return}if(!n("#zemanta-gallery-popup-desc").length){if(Q.pc&2){V=true
}if(!g.platform.big_gallery_preview){n('<div class="zemanta-gallery-popup-shader"></div>').css({left:U.position().left-M.position().left}).appendTo(M)
}if(g.platform.big_gallery_preview){S=n('<div class="zemanta-big-preview-content"></div>');
P=n('<p id="zemanta-gallery-popup-desc" title="'+g.helpers.html_attr(Q.description||"")+'"><a href="'+Q.source_url+'" class="zemanta-big-preview-title">'+H.helpers.prepDesc(g.helpers.html_value(Q.description||""),30)+"</a></p>").find("a").click(function(X){b.open(n(this).attr("href"));
X.preventDefault()
}).end();S.append(P).appendTo(M)
}else{P=n('<p id="zemanta-gallery-popup-desc" title="'+g.helpers.html_attr(Q.description||"")+'">'+H.helpers.prepDesc(g.helpers.html_value(Q.description||""),30)+"</p>").appendTo(S)
}if(!Q.license){Q.license="Unknown license"
}if(g.platform.big_gallery_preview&&Q.description_long){n('<p id="zemanta-gallery-popup-image-description">'+H.helpers.prepDesc(Q.description_long)+"</p>").appendTo(S)
}n('<p id="zemanta-gallery-popup-license">'+H.helpers.prepDesc(Q.license,30)+"</p>").appendTo(S);
if(g.platform.big_gallery_preview){if(Q.url_m_w&&Q.url_m_h){n('<p id="zemanta-gallery-popup-size">Size: '+Q.url_m_w+"x"+Q.url_m_h+"px</p>").appendTo(S)
}else{if(Q.width&&Q.height){n('<p id="zemanta-gallery-popup-size">Original size: '+Q.width+"x"+Q.height+"px</p>").appendTo(S)
}}}T=n('<div id="zemanta-gallery-popup-more">'+(Q.more_link||H.helpers.prepMore(Q))+"</div>");
T.find("a.ext").click(function(X){b.open(n(this).find("a").attr("href"));
X.preventDefault()
});T.appendTo(S);
if(V){n('<div class="zemanta-promoted-content"><a href="http://www.zemanta.com/faq/?promoted#faqid-79" class="zemanta-promoted" target="_blank">promoted</a></div>').appendTo(T)
}}else{if(n("#zemanta-gallery-popup-media").height()===0){n("#zemanta-gallery-popup-media").find("img").unbind("error").end().remove()
}P=n("#zemanta-gallery-popup-desc")
}if(!n("#zemanta-gallery-popup-media").length){O=n('<div id="zemanta-gallery-popup-media"><div id="zemanta-gallery-popup-preloader"><span>Almost there!</span></div><div id="zemanta-media-content"><div id="zemanta-media-wrapper"><img'+(Q.url_m_w&&Q.url_m_h?' width="'+Q.url_m_w+'" height="'+Q.url_m_h+'"':"")+' alt="" src="#'+Q.hash+"_"+j()+'_m" title="'+g.helpers.html_attr(Q.description)+'" /></div></div></div>').css({width:"100%",height:150}).each(function(){var X=n("img",this);
W=N/150;X.data("timer",h(function(){X.trigger("load")
},1000))}).find("#zemanta-gallery-popup-preloader").css("visibility","").end().find("img").css("visibility","hidden").bind("load",function(){m(n(this).data("timer"));
n(this).css("display","block");
H.centerImage(this,W)
}).bind("error",function(){var X=this,Y="";
if(X.src.indexOf("wikimedia")>=0){if(X.src.indexOf("thumb/")>=0&&Q.url_l_w<500&&Q.url_l_h<500){Y=X.src.replace(/\/thumb/,"");
Y=Y.substr(0,Y.lastIndexOf("/"));
X.src=Y;Q.url_m=Y;
delete Q.url_m_w;
delete Q.url_m_h
}else{}}}).attr("src",Q.url_m).end().fin();
if(Q.type&&Q.type==="YouTube"){O.html(Q.html.replace(/300/g,N).replace(/242/g,150))
}else{if(Q.type&&Q.type==="Google Maps"){O.html(Q.html.replace('width="300','width="'+N).replace('height="250','height="150'))
}}O.insertAfter(P)
}},destroy:function(M){n("#zemanta-gallery-popup-media").each(function(){if(n("#zemanta-media-content").length===0){n(this).empty().css({height:0})
}})},empty:function(M){n("#zemanta-media-content img").unbind("error")
},scroll_resilience:{zero:n("#zemanta-sidebar"),wrap:n("#zemanta-gallery-wrap")}};
H.popupInit=t.popup(n("#zemanta-gallery li",n("#zemanta-sidebar")[0]),K);
if(g.platform.image_config){n(g.platform.get_editor().win.document).delegate(".zemanta-img","click",function(M){o.trigger("image_selected",{source_url:n("a",this).attr("href")})
})}H.initialized=true;
if(g.gallery._images.length){H.prepData();
G._render()
}},change_state:function(K,J){var L=g.control.sync.dom();
J=J||0;if(K){H.syncData(L);
if(!H.initialized){G.initialize()
}else{H.prepData();
G._render()
}n("#zemanta-gallery").show(J);
if(!g.platform.disable_draggable_resize){n("#zemanta-articles h2").addClass("draggable").attr("title","Drag to resize")
}}else{n("#zemanta-gallery").hide(J);
n("#zemanta-articles h2").removeClass("draggable").attr("title","")
}g.control.widget.gallery=K
},_success:function(K,J,L){if(!H.initialized){return J(L)
}L=L||g.control.sync.dom();
if(typeof L==="function"){return L.simple(G._success,this,arguments)
}g.log("gallery success");
g._set_status(2);
g.gallery._old=g.gallery._images;
g.gallery._images=K.images;
if(!K.images.length){H.helpers.showmessage("Sorry, we didn't find any new visual media for the text you're writing...",true)
}G.process(false,L);
J(L)},process:function(J,K){K=K||g.control.sync.dom();
if(typeof K==="function"){return K.simple(G.process,this,arguments)
}if(G._images.length){H.helpers.hidemessage()
}H.eliminateDuplicates(J);
H.syncData(K);
if(g.control.widget.gallery){H.prepData();
G._render()
}},_render:function(){var L=H.template,M=H.icons,N=n("#zemanta-gallery-thumbnails"),K=v.createDocumentFragment(),J=[];
n.each(g.gallery._images,function(O,S){var R=n("#zemimg-"+S.hash),Q={},P="";
P=u().add(H.activeTypes[S.active||0]).add(S.pc&2&&"zemanta-gallery-li-promoted").print(" ");
if(!R.length){if(S.url_s_h===0||S.url_s_w===0){g.helpers.image_size(S,"url_s",H.helpers.createResizeHandler(Q))
}R=n(L({elid:"zemimg-"+S.hash,id:O,src:C+"core/img/spacer.gif#"+S.hash,desc:S.description.replace(/"/g,"&quot;"),style:"",selection:P,icon:M[S.personal_scope===1&&1||S.rich&&S.source_url.indexOf("youtube.com")>=0&&2||S.rich&&S.source_url.indexOf("maps.google.com")>=0&&3||S.rich&&S.source_url.indexOf("5min.com")>=0&&4||S.rich&&S.source_url.indexOf("slideshare.com")>=0&&5||0],promoted:S.pc&2&&'<div class="zemanta-gallery-img-promoted">Promoted</div>'||""})).click(H.handlers.click);
h(function(){R.css("background","#fff").find("img."+F).one("load",function(){var V=S.url_s_w?Math.floor((S.url_s_w-50)/2):0,U=S.url_s_h?Math.floor((S.url_s_h-50)/5):0,T="rect("+U+"px, "+(V+50)+"px, "+(U+50)+"px, "+V+"px)";
n(this).css({background:"#fff",clip:T,position:"absolute","margin-top":-U+"px","margin-left":-V+"px"}).unbind("error")
}).one("error",H.handlers.error).attr("src",S.url_s)
},1);J.push(R[0]);
Q.img=R}else{R.find("img").unbind("error").end()[0].className=I+P
}K.appendChild(R.get(0))
});J=n(J);N.empty();
N.append(K);
H.popupInit()
},_mark:function(L,K){var J=n("#zemanta-gallery ."+I+"#zemimg-"+L.hash);
delete L.active;
n.each(H.activeTypes,function(M,N){if(N){J.removeClass(N)
}});if(K){L.active=K;
J.addClass(H.activeTypes[K])
}},_select:function(M,L,N){N=N||g.control.sync.dom();
if(typeof N==="function"){return N.simple(g.gallery._select,this,arguments)
}var K=null,J=H.activeTypes[1];
M.description=M.description.replace(/"/g,"&quot;");
if(L===true){o.trigger("image_insert",M);
N=g.force_one_paragraph(N);
if(g.platform.image_insert_at_cursor){N=g.platform.insert_zemanta_image(N,g.platform.gallery_insert(M,n("<div></div>")).html())
}else{N=g.platform.gallery_insert(M,N)
}N=g.helpers.feedback.prepare("image-click",N);
G._scroll(M);
G._mark(M,1);
if(g.platform.image_config){h(function(){o.trigger("image_selected",M)
},1)}}else{o.trigger("image_remove",M);
G._scroll(M);
N=g.platform.gallery_remove(M,N);
G._mark(M)}g.platform.set_html(N.html(),M.poc)
},_scroll:function(K){if(!K){return
}var J=g.platform.get_editor();
if(J.win){h(function(){var L=n('.zemanta-img a[href="'+K.source_url+'"] img',J.element);
if(L.length){g.platform.scroll(L)
}g.helpers.feedback.animate("image-click",n("ul#zemanta-gallery-thumbnails li#zemimg-"+K.hash))
},1)}}};return G
}();k=function(){var H="zemanta-article-li",G={initialized:false,template:t.ZTemplate('<li id="zemart-{hash}" class="'+H+'{selection}">{promoted}<p class="zemanta-article-title"><span>{title}</span></p><p class="zemanta-article-date-source"><span class="zemanta-article-date">{date}</span><span class="zemanta-article-source ext"><a class="zemanta-read-more" target="_blank" title="Open {article_url} in New Window" href="{article_url}">Visit</a>{hostname}</span></p>{source_icon}{personal_icon}<div class="zemanta-selector"></div></li>'),rgx_nonascii_start:/^[^a-zA-Z0-9<]*/g,rgx_nonascii_end:/[^a-zA-Z0-9>]*$/g,helpers:{convertDate:function(J){if(typeof J==="string"){var I=new Date();
I.setISO8601(J);
return I}return J
},showmessage:function(L,I){var J=n("#zemanta-articles"),K=J.find("div.zemanta-message");
if(!K.length){n('<div class="zemanta-message'+(I&&" zemanta-autohide"||"")+'"><div class="zemanta-message-wrap">'+L+"</div></div>").insertAfter(n("h2",J))
}else{K[I?"removeClass":"addClass"]("zemanta-autohide").find(".zemanta-message-wrap").html(L)
}},hidemessage:function(I){var J=n("#zemanta-articles div.zemanta-message.zemanta-autohide");
if(I){J.animate({opacity:0,height:0},function(){n(this).remove()
})}else{J.remove()
}},humanize:function(I){I=(I||"").replace(G.rgx_nonascii_start,"").replace(G.rgx_nonascii_end,"");
I="&#x2026;"+I+"&#x2026;";
return I}},handlers:{lastevent:{},stop:function(I){I.stopPropagation()
},click:function(J){if(G.handlers.lastevent==J){return
}G.handlers.lastevent=J;
if(n(J.target).hasClass("zemanta-read-more")){return
}var M=n(this),L=(M.attr("id")||"").replace("zemart-",""),I=M.find("a").attr("href"),K=g.helpers.object_search(g.articles._articles,"hash",L)||g.helpers.object_search(g.articles._articles,"url",I);
if(J.metaKey||J.ctrlKey){return b.open(I)
}if(K){if(M.is(".zemanta-selected")){g.articles._select(K,false,M)
}else{g.articles._select(K,true,M)
}}},markhover:function(I){n(this).parent().toggleClass("hover")
}},eliminateDuplicates:function(){var I=g.helpers.merge_arrays({p:"url",e:true},g.articles._articles,n.each(g.articles._old,function(){delete this.active
}));g.articles._articles=I.union;
if(g.articles._articles[0]){g.articles._articles[0].first=true
}if(g.articles._articles[I.idx[0]+1]){g.articles._articles[I.idx[0]+1].last=true
}},createHighlightText:function(J){var I=n("<span />").html(J.text_preview||J.text_highlight||J.title_highlight||"Excerpt is not available at this time.").text().replace(/\n/g,"<br />"),K=J.title.replace(" and related posts","");
J.highlight_text='<div class="zemanta-snippet-header"><a href="'+J.url+'" title="Open link in new window" target="_blank" class="zemanta-big-preview-title">'+K+'</a><div class="zemanta-snippet-postmeta"><ul class="zemanta-snippet-social"><li class="fb"><span>0</span></li><li class="twitter"><span>0</span></li></ul><span class="zemanta-snippet-postdate">'+J.outdate+'</span></div></div><div class="zemanta-snippet-body">'+(J.article_id?'<img src="http://thumbs.zemanta.com/'+J.article_id+'.jpg" class="zemanta-article-preview-img" />':"")+I+'</div><div class="zemanta-snippet-footer"><a href="http://'+J.hostname.toLowerCase()+'" target="_blank" title="Open link in new window" class="zemanta-big-preview-morelink">'+J.hostname.toLowerCase()+"</a>"+(J.pc&2&&'<div class="zemanta-promoted-content"><a href="http://www.zemanta.com/faq/?promoted#faqid-79" class="zemanta-promoted" target="_blank">promoted</a></div>'||"")+"</div>";
return J},checkImage:(function(){var J={},I={};
return function(L){var K="http://thumbs.zemanta.com/"+L.article_id+".jpg";
if(!L.article_id){G.createHighlightText(L);
return}if(!J[K]){h(function(){var M=n("<img />");
M.one("load",function(){J[K]=true;
G.createHighlightText(L)
}).one("error",function(){J[K]=true;
I[K]=true;L.article_id=null;
G.createHighlightText(L)
}).attr("src",K)
},1)}else{if(I[K]){L.article_id=null
}G.createHighlightText(L)
}}})(),syncData:function(I){g.articles._articles=g.platform.articles_active(g.articles._articles,I,g.helpers.array_index(g.articles._articles,false,function(J){return g.helpers.encode_url(J.url)
}));g.articles._articles.sort(function(K,J){return(!K.active&&!J.active)?0:K.active?!J.active?-1:1:1
})},prepData:function(){var J=j(),I=86400*1000;
n.each(g.articles._articles,function(K,L){var M=null;
if(!L.hash){if(t.check_feature("ZemantaFakePC")){L.pc=Math.floor(Math.random()*4)
}M=G.helpers.convertDate(L.published_datetime);
L.published_datetime=M;
L.title=n.trim(L.title).replace(/"/g,"&quot;");
L.hostname=g.helpers.extract_hostname(L.url);
L.hash=t.elf_hash(L.url);
L.outdate=M.getTime()<=I*3650?"":M.toTimeSinceString(1)+" ago "
}if(g.platform.big_article_preview){G.checkImage(L)
}else{L.highlight_text='<span class="zemanta-snippet-title">From the article:</span><span class="zemanta-snippet">'+G.helpers.humanize(L.text_highlight||L.title_highlight||"<i>Excerpt not available at this time.</i>")+' <a href="'+t.html_attr(L.url)+'" target="_blank">read more</a></span>'+(L.pc&2&&'<p class="zemanta-promoted-content">This is a <a href="http://www.zemanta.com/faq/?promoted#faqid-79" class="zemanta-promoted" target="_blank">promoted</a> article. <a href="http://www.zemanta.com/faq/?promoted#faqid-79" class="zemanta-more" target="_blank">Read more</a></p>'||"")
}})},sharesData:{},setSharesCount:function(K,I,J){if(typeof(this.sharesData[K])==="undefined"){this.sharesData[K]={}
}this.sharesData[K][I]=J
},getSharesCount:function(J,I){if(typeof(this.sharesData[J])!=="undefined"&&typeof(this.sharesData[J][I])!=="undefined"){return this.sharesData[J][I]
}return false
},checkSharesCount:function(J,M){var L=J,K=0,I=typeof(M)==="function"?M:function(){};
if(this.getSharesCount("twitter",J)===false){this.setSharesCount("twitter",L,0);
n.ajax({url:"http://urls.api.twitter.com/1/urls/count.json",data:{url:J},dataType:"jsonp",crossDomain:true,success:n.proxy(function(N){K=N.count||0;
this.setSharesCount("twitter",L,K);
I("twitter",L,K)
},this)})}if(this.getSharesCount("fb",J)===false){this.setSharesCount("fb",L,0);
n.ajax({url:"https://graph.facebook.com/",data:{id:J},dataType:"jsonp",crossDomain:true,success:n.proxy(function(N){K=N.shares||0;
this.setSharesCount("fb",L,K);
I("fb",L,K)
},this)})}},updateSharesUI:function(K){var I=n("#zemanta-articles-popup .zemanta-snippet-social"),N=n("#zemanta-articles-popup .zemanta-snippet-header"),J=n(".zemanta-big-preview-title",N),L=this.getSharesCount("twitter",K)||0,M=this.getSharesCount("fb",K)||0;
if(L===0&&M===0){I.stop(true,true).css("display","none")
}else{I.slideDown("fast");
J.css("margin-right",N.width()-(I.position()&&I.position().left||0))
}I.find(".twitter span").text(L);
I.find(".fb span").text(M)
}},F={_articles:[],_old:[],sources:{popular:function(L){var J=[],M=j(),K=86400*1000,I=0;
n.each(L.popular,function(N){var P=G.helpers.convertDate(this.published),O={url:g.helpers.encode_url(this.url),title:n.trim(this.title).replace(/\u0093|\u0094/g,'"').replace(/"/g,"&quot;"),published_datetime:G.helpers.convertDate(this.published),confidence:0.5,zemified:this.rid===null?0:1,text_highlight:this.description||"",poc:true,hostname:g.helpers.extract_hostname(this.url),outdate:P.getTime()<=K*3650?"":P.toTimeSinceString(1)+" ago",hash:t.elf_hash(this.url),article_id:this.article_id};
if(N===0){O.first=true
}J.push(O)});
J=J.slice(0,5);
return J}},initialize:function(){var J=n("#zemanta-articles"),I={};
if(!J.length){g.helpers.logGA(null,"widget/warn/jqz/articles-initiallize-failed/"+g.interface_type+"/");
return g.log("Not initializing articles - no wrapper")
}n("#zemanta-articles-wrap li",n("#zemanta-sidebar")[0]).live("click",G.handlers.click).find("a").live("click",G.handlers.stop).live("mousedown",G.handlers.stop).live("mouseup",G.handlers.stop).live("hover",G.handlers.markhover).end();
n('<h2><span class="zemanta-articles-title">Related Articles</span> <a class="zemanta-help" title="Need help with Related Articles?" href="http://www.zemanta.com/faq/quickhelp/?articles#faqid-58">?</a></h2><div id="zemanta-articles-wrap"><ul id="zemanta-articles-list"></ul></div>').appendTo(n("#zemanta-articles").empty().mouseover(G.helpers.hidemessage)).disableTextSelect();
n("#zemanta-sidebar #zemanta-articles-wrap").height(g.helpers.storage.get("#zemanta-sidebar #zemanta-articles-wrap-size")===0?0:g.helpers.storage.get("#zemanta-sidebar #zemanta-articles-wrap-size")||g.platform.articles_height);
if(!g.platform.disable_draggable_resize){n("#zemanta-articles h2").addClass("draggable");
g.helpers.drag("#zemanta-articles h2.draggable","#zemanta-sidebar #zemanta-articles-wrap",{stop:function(){if(n.browser.msie){n("#zemanta-links-div-ul").hide().show()
}}})}I={popup_id:"zemanta-articles-popup",mode:"articles",source_marker:"zemanta-articles-li-hover",parent_selector:g.platform.article_popup_parent,markArticleInsert:function(K){n("#zemanta-articles-popup .zemanta-big-preview-content, #zemanta-articles-popup .zemanta-article-shader").addClass("zemanta-selected");
n("#zemanta-add-article").html("Remove from post")
},markArticleRemove:function(K){n("#zemanta-articles-popup .zemanta-big-preview-content, #zemanta-articles-popup .zemanta-article-shader").removeClass("zemanta-selected");
n("#zemanta-add-article").html("&#65122; Add to post")
},init:function(){g.bind("articles_insert",I.markArticleInsert);
g.bind("articles_remove",I.markArticleRemove)
},position:function(K){g.platform.article_popup_position(K)
},calcShaderPosition:function(L,N,M){function Q(U){return parseInt(U,10)||0
}var R=0,P=null,O=null,K=null,S=null,T=parseInt(N.source.css("border-bottom-width"),10);
if(g.platform.big_article_preview){P=n("#"+L[0].className);
O=n("#zemanta-articles-popup");
K=n("#zemanta-articles-wrap");
S=n(".zemanta-big-preview-content",this);
R=O.outerHeight(true)-(K.outerHeight(true)+n("#zemanta-tools").outerHeight(true)+n("#zemanta-plugin-help").outerHeight(true))+(P[0].offsetTop-K[0].scrollTop)+8-(Q(O.css("margin-top"))+Q(O.css("border-top-width"))-Q(O.css("padding-top")))
}else{R=M?L.outerHeight()-T:0
}return R},beforeShow:function(K){if(!g.platform.big_article_preview&&K.popupUpsidedown){n(".zemanta-article-shader",this).css("top",I.calcShaderPosition(this,K,true))
}},moveShader:function(L,K){if(!g.platform.big_article_preview){n(".zemanta-article-shader",this).css("top",I.calcShaderPosition(this,L,K))
}},element_height:function(K){return n(K).get_element_height(true,false,true,true)-1
},create:function(P){function N(X){var V=n(this).parents("#zemanta-articles-popup")[0].className,W=n.trim(V.replace("active","")),Y=n("#"+W);
if(X.target.nodeName.toLowerCase()==="a"&&(n(X.target).parents("div.zemanta-snippet-title").length||n(X.target).parents("p.zemanta-snippet-metadata").length)){return
}Y.click();
X.stopPropagation();
X.preventDefault()
}var R=P.source,K=this,O=g.helpers.object_search(g.articles._articles,"hash",(R.attr("id")||"").replace("zemart-","")),U=0,S=29,M=0,L=0,Q=0,T=0;
if(!O){return
}if(!g.platform.big_article_preview){if(!n(".zemanta-article-desc",K).length){n('<div class="zemanta-article-desc">'+O.highlight_text+"</div>").appendTo(K);
n(".zemanta-article-desc",K).unbind(N).click(N)
}U=n("#zemanta-articles").height()+n("zemanta-tools").height();
S=29;M=Math.floor(R.width()*(S/100));
L=Math.max(P.source.get_element_width(false,false),100)-M;
Q=Math.max(P.source.get_element_width(false,true),100)-M;
T=parseInt(this.css("left"),10)+M;
if(!n(".zemanta-article-shader",K).length){n('<div class="zemanta-article-shader"></div>').css("width",Q).appendTo(K)
}K.css({width:L,left:T});
n(".zemanta-article-shader",P.source).show()
}else{if(!n(".zemanta-big-preview-content",K).length){n('<div class="zemanta-big-preview-content">'+O.highlight_text+"</div>").appendTo(K)
}K.addClass("zemanta-big-preview big-article-preview");
g.platform.article_big_popup_position(P,K,this);
G.checkSharesCount(O.url,n.proxy(function(){var V=/zemart-(\d+)/i.exec(n(this).attr("class")).pop();
if(V==O.hash){G.updateSharesUI(O.url)
}},this));G.updateSharesUI(O.url)
}o.trigger("articles_popup",O)
},destroy:function(K){},scroll_resilience:{zero:n("#zemanta-sidebar"),wrap:n("#zemanta-articles-wrap")}};
if(g.platform.big_article_preview){I.interval=150;
I.timeout=650
}t.popup(n("#zemanta-articles-list li",n("#zemanta-sidebar")[0]),I)();
G.initialized=true;
if(g.articles._articles.length){G.prepData();
g.articles._render()
}n("#zemanta-articles-list li a",n("#zemanta-sidebar")[0]).live("click",function(){o.trigger("articles_readmore",g.helpers.object_search(g.articles._articles,"url",n(this).attr("href")))
});n(".zemanta-snippet a",n("#zemanta-sidebar")[0]).live("click",function(){o.trigger("articles_readmore",g.helpers.object_search(g.articles._articles,"url",n(this).attr("href")))
})},change_state:function(J,I){var K=g.control.sync.dom();
I=I||0;if(J){G.syncData(K);
if(!G.initialized){F.initialize()
}else{G.prepData();
F._render()
}n("#zemanta-articles").show(I)
}else{n("#zemanta-articles").hide(I)
}g.control.widget.articles=J
},_success:function(J,I,K){if(!G.initialized){return I(K)
}K=K||g.control.sync.dom();
if(typeof K==="function"){return K.simple(F._success,this,arguments)
}g.log("articles success");
g._set_status(2);
g.articles._old=g.articles._articles;
g.articles._articles=J.articles;
if(!J.articles.length){G.helpers.showmessage("Sorry, we didn't find any new articles for the text you're writing...",true)
}F.process(K);
I(K)},process:function(I){I=I||g.control.sync.dom();
if(typeof I==="function"){return I.simple(F.process,this,arguments)
}if(F._articles.length){G.helpers.hidemessage()
}G.eliminateDuplicates();
G.syncData(I);
if(g.control.widget.articles){G.prepData();
F._render()
}},_render:function(){var L=n("#zemanta-articles-list"),K=G.template,J=v.createDocumentFragment(),I=[];
if(g.platform.big_article_preview){L.addClass("big-article-preview")
}n.each(g.articles._articles,function(N,Q){var P=n("#zemart-"+Q.hash,L),M="",O=u().add(Q.active&&"zemanta-selected").add(Q.pc&2&&"zemanta-article-li-promoted").print(" ");
if(!P.length){M=typeof Q.zemified!=="undefined"&&Q.zemified===1&&'<img src="'+C+'core/img/zem_source.png" class="zemanta-source-icon-1">'||"";
P=n(K({hash:Q.hash,fulltitle:Q.title,hostname:Q.hostname,article_url:Q.url,date:Q.outdate,title:Q.title,source_icon:M,personal_icon:typeof Q.personal_scope!=="undefined"&&Q.personal_scope===1&&'<img src="'+C+'core/img/user_orange.png" class="zemanta-source-icon-'+(M===""&&"1"||"2")+'">'||"",selection:O,promoted:Q.pc&2&&'<div class="zemanta-article-promoted">Promoted</div>'||""})).disableTextSelect();
if(g.platform.big_article_preview){P.append('<div class="zemanta-insert">'+(P.hasClass("zemanta-selected")?"click to remove":"click to insert")+"</div>")
}I.push(P[0])
}else{P[0].className=H+O
}J.appendChild(P[0])
});L.empty().append(J).show()
},_select:function(J,I,L,K){K=K||g.control.sync.dom();
if(typeof K==="function"){return K.simple(g.articles._select,this,arguments)
}if(I===true){o.trigger("articles_insert",J);
L.addClass("zemanta-selected");
L.find(".zemanta-insert").text("click to remove");
K=g.platform.articles_insert(J,g.force_one_paragraph(K))
}else{o.trigger("articles_remove",J);
L.removeClass("zemanta-selected");
L.find(".zemanta-insert").text("click to insert");
K=g.platform.articles_remove(J,K)
}g.platform.set_html(K.html(),J.poc);
g.platform.scroll("bottom");
if(L){g.helpers.feedback.animate("article-click",L,J)
}}};return F
}();x=function(){var G={initialized:false,helpers:{findIndexByType:function(K,J){var I=0,H=K.target.length;
for(;I<H;I+=1){if(K.target[I].type===J){return I
}}},deselectCurrent:function(J){var I=0,H=J.target.length;
if(J.selectedtype){g.links._select(g.links._prepObject(J,G.helpers.findIndexByType(J,J.selectedtype)))
}else{for(;
I<H;I+=1){g.links._select(g.links._prepObject(J,I))
}}},extractType:function(H){if(typeof(H)==="string"){n.each(H.split(" "),function(I,J){if(J.indexOf(":")<0&&J!=="nofollow"){H=J
}})}return H
},closeSublist:function(){n("#zemanta-links-popup").css("visibility","hidden")
}},handlers:{lastevent:{},listclick:function(J){if(G.handlers.lastevent==J){return
}G.handlers.lastevent=J;
var H=n("span.zemanta-link-anchor",this).text().replace(/\xA0/g," "),L=n(this),K=g.helpers.object_search(g.links._links,"hash",(L.attr("id")||"").replace("zemlink-","")),I=null;
if(H&&K){if(J.metaKey||J.ctrlKey){return b.open(K.target[0].url)
}if(L.is(".zemanta-selected")){G.helpers.deselectCurrent(K);
L.removeClass("zemanta-selected");
delete K.selectedtype
}else{K.selectedtype=K.renderData.reltype;
I=g.links._prepObject(K,0);
g.links._select(I,true);
L.addClass("zemanta-selected");
g.helpers.feedback.animate("link-click",L,I)
}L.find(".zemanta-link-promoted")[K.renderData.promoted&&"addClass"||"removeClass"]("visible").end().find(".zemanta-link-icon").removeClass().addClass("zemanta-link-icon").addClass("zemanta-link-"+K.renderData.reltype).end()
}G.helpers.closeSublist()
},createitemclick:function(H){return function(L){if(G.handlers.lastevent==L){return
}G.handlers.lastevent=L;
var N=n(this),M=n("#zemlink-"+H.hash),J=N.prevAll("li").length,K=H.target[J],O=M.find(".zemanta-link-icon").hasClass("zemanta-link-"+K.type),I=null;
if(L.metaKey||L.ctrlKey){L.stopPropagation();
return b.open(K.url)
}if(M.is(".zemanta-selected")){G.helpers.deselectCurrent(H)
}M.find(".zemanta-link-icon").removeClass().addClass("zemanta-link-icon");
if(M.is(".zemanta-selected")&&O){M.find(".zemanta-link-icon").addClass("zemanta-link-"+H.renderData.reltype).end().find(".zemanta-link-promoted")[H.renderData.promoted&&"addClass"||"removeClass"]("visible").end().removeClass("zemanta-selected");
H.selectedtype=null
}else{M.find(".zemanta-link-icon").addClass("zemanta-link-"+K.type).end().find(".zemanta-link-promoted")[K.pc&2&&"addClass"||"removeClass"]("visible").end().addClass("zemanta-selected");
I=g.links._prepObject(H,J);
g.links._select(I,true);
g.helpers.feedback.animate("link-click",N,I);
H.selectedtype=H.target[J].type
}G.helpers.closeSublist();
L.stopPropagation()
}},createitemover:function(H){return function(I){b.status=H
}},itemout:function(H){b.status=""
},linkclick:function(H){H.stopPropagation();
G.helpers.closeSublist()
}},listtemplate:t.ZTemplate('<li id="zemlink-{elid}" class="zemanta-links-li{addclass}" style="z-index:{index};"><span class="zemanta-link-icon zemanta-link-{reltype}"></span><span class="zemanta-link-anchor">{anchor}</span>{promoted}</li>'),itemtemplate:t.ZTemplate('<li title="{fulltitle}"><span class="zemanta-link-icon zemanta-link-{type}"></span><span class="zemanta-link-anchor">{title}</span> <a class="ext" rel="{type}" title="Open {url} in new window" href="{url}" target="_blank">Visit</a>{promoted}</li>'),eliminateDuplicates:function(){g.links._links=g.helpers.merge_arrays({p:"anchor"},g.links._links,n.grep(g.links._old,function(I,H){return !!I.selectedtype
}))},syncData:function(H){g.links._links=g.platform.links_active(g.links._links,H)
},prepData:function(){n.each(g.links._links,function(I,K){var J={},H=false;
K.hash=t.elf_hash(K.anchor);
if(!K.renderData){n.each(K.target,function(L,M){if(t.check_feature("ZemantaFakePC")){M.pc=Math.floor(Math.random()*4)
}if(M.pc&2){H=true
}J[M.type]=M
});K.renderData={fulltitle:"Add/remove link to "+K.anchor,anchor:K.anchor.replace(/ /g,"&nbsp;"),reltype:K.target[0].type,promoted:H?'<span class="zemanta-link-promoted">Promoted</span>':"",addclass:n.grep(K.target,function(L){return L.type!=="rdf"
}).length===1?" zemanta-links-li-single":"",bytype:J}
}})}},F={_links:[],_old:[],_temp:[],getType:function(H){return G.helpers.extractType(H)
},initialize:function(){if(!n("#zemanta-links-div-ul").length){g.platform.links_initialize()
}if(g.widget_enabled===false){n("#zemanta-links").not("#zemanta-sidebar #zemanta-links").hide()
}var J=n("#zemanta-links"),I={};
if(!J.length){g.helpers.logGA(null,"widget/warn/jqz/links-initialize-failed/"+g.interface_type+"/");
return g.log("Not initializing links - no wrapper")
}n("#zemanta-sidebar #zemanta-links-div-ul").height(g.helpers.storage.get("#zemanta-sidebar #zemanta-links-div-ul-size")===0?0:g.helpers.storage.get("#zemanta-sidebar #zemanta-links-div-ul-size")||120);
if(!g.platform.disable_draggable_resize){n("#zemanta-sidebar #zemanta-links h2").addClass("draggable").each(function(){g.helpers.drag("#zemanta-sidebar #zemanta-links h2.draggable","#zemanta-sidebar #zemanta-links-div-ul",{stop:function(){if(n.browser.msie){n("#zemanta-links-div-ul").hide().show()
}}})})}n("#zemanta-links h2 a.zemanta-help").attr("title","Need help with In-text Links?").attr("href","http://www.zemanta.com/faq/quickhelp/?links#faqid-60");
I={popup_id:"zemanta-links-popup",mode:"links",source_marker:"zemanta-links-link-hover",parent_selector:n("#zemanta-links h2").or("#zemanta-links"),init:function H(L,K){L.zemClearHeight=(n("#zemanta-links .zem-clear").offset()||{}).top-(n("#zemanta-articles").offset()||{}).top
},position:function(N){var M=this,L=Math.max(N.source.get_element_width(false,true),175),K=Math.round((N.source.position()||{}).left+5);
M.width(L);
if(isNaN(K)){K=3
}K+=(N.links_in_sidebar||n("#zemanta-links h2.horizontal").length)?1:0;
M.css({left:K})
},getPopupHeight:function(K){var L=K?K:n(this);
return n(L).get_element_height(true,false,true,true,false)
},beforeShow:function(L){var K=this;
if(L.popupUpsidedown){n(".zemanta-link-shader",K).css("top",K.height())
}},moveShader:function(L,K){n(".zemanta-link-shader",this).css("top",(K?this.height():-3))
},create:function(N){var P=N.source,L=this,O=null,M=G.itemtemplate,K=false;
O=g.helpers.object_search(g.links._links,"hash",(P.attr("id")||"").replace("zemlink-",""));
if(!O||!O.target){return
}n(".zemanta-link-shader",L).remove();
n('<div class="zemanta-link-shader"></div>').width(N.source.get_element_width(false,true)).appendTo(L);
if(!n(".zemanta-links-desc",L).length){n('<ul class="zemanta-links-desc"></ul>').appendTo(L);
n.each(O.target,function(Q,R){if(R.type==="rdf"){return
}var S=R.title.length>22?R.title.substring(0,21)+"...":R.title;
K=K||R.pc&2;
n(M({fulltitle:"Add link to "+R.title,type:R.type,title:S,url:R.url,promoted:(R.pc&2)&&'<div class="zemanta-link-promoted">Promoted</div>'||""})).disableTextSelect().mouseover(G.handlers.createitemover(R.url)).mouseout(G.handlers.itemout).click(G.handlers.createitemclick(O)).find("a").click(G.handlers.linkclick).end().appendTo(n("ul.zemanta-links-desc",L))
});L.css("min-width",K&&200||175);
if(K){n('<p class="zemanta-promoted-content">What are <a href="http://www.zemanta.com/faq/?promoted#faqid-79" class="zemanta-promoted" target="_blank">promoted</a> links? <a href="http://www.zemanta.com/faq/?promoted#faqid-79" class="zemanta-more" target="_blank">Read more</a></p>').appendTo(L)
}}n(".zemanta-link-shader",N.source).show()
},destroy:function(K){},scroll_resilience:{zero:n("#zemanta-links h2.vertical").or("#zemanta-sidebar #zemanta-links h2").or("#zemanta-links-div-ul"),wrap:n("#zemanta-links-div-ul"),zeroHeader:n("#zemanta-links h2.vertical").or("#zemanta-sidebar #zemanta-links h2").or("#zemanta-links-div-ul")}};
t.popup(n(".zemanta-links-li",n("#zemanta-links")[0]),I);
n("#zemanta-links-div-ul li",n("#zemanta-links")[0]).live("click",G.handlers.listclick);
G.initialized=true;
if(g.links._links.length){g.links._render()
}},change_state:function(I,H){var J=g.control.sync.dom();
H=H||0;if(I){G.syncData(J);
if(!G.initialized){F.initialize()
}else{G.prepData();
F._render()
}n("#zemanta-links").show(H)
}else{n("#zemanta-links").hide(H)
}g.control.widget.links=I
},_success:function(L,K,N){if(!G.initialized){return K(N)
}N=N||g.control.sync.dom();
if(typeof N==="function"){return N.simple(F._success,this,arguments)
}var P=0,O=0,M=0,J=0,Q="",H=[],I=[];
g.log("links success");
g._set_status(2);
g.links._old=g.links._links;
g.links._links=L.markup.links;
H=g.links._links.slice();
H.sort(function(S,R){return S.anchor.split(" ").length-R.anchor.split(" ").length
});for(P=0,M=H.length;
P<M;P+=1){for(O=0,J=I.length;
O<J;O+=1){if(H[P].anchor.toLowerCase().indexOf(I[O])>-1){if(!H[O].collidesWith){H[O].collidesWith=[]
}H[O].collidesWith.push(H[P])
}}I.push(H[P].anchor.toLowerCase())
}for(P=0,M=g.links._links.length;
P<M;P+=1){for(O=0,J=g.links._links[P].target.length;
O<J;O+=1){Q=g.links._links[P].target[O];
Q.url=g.helpers.encode_url(Q.url);
Q.type=Q.type.indexOf("_")>=0?Q.type.substr(0,Q.type.indexOf("_")):Q.type
}}F.process(N);
K(N)},process:function(H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(F.process,this,arguments)
}G.eliminateDuplicates();
G.syncData(H);
if(g.control.widget.links){G.prepData();
F._render(H)
}},_render:function(){var K=G.listtemplate,L=n("#zemanta-links-div-ul"),H=g.links._links.length,J=v.createDocumentFragment(),I=[];
n.each(g.links._links,function(M,P){var N=null,O=n("#zemlink-"+P.hash);
if(!O.length){N=t.simple_clone(P.renderData);
if(P.active){N.addclass+=" zemanta-selected";
N.reltype=P.selectedtype
}N.elid=P.hash;
N.index=H-M+1;
O=n(K(N)).disableTextSelect().appendTo("#zemanta-links-div-ul");
if(P.renderData.bytype[N.reltype].pc&2){O.find(".zemanta-link-promoted").addClass("visible")
}I.push(O[0])
}J.appendChild(O[0])
});L.empty();
L.append(J);
F.redraw();
g.platform.links_all()
},redraw:function(){var H=n.browser.msie&&parseFloat(n.browser.version)<8;
n("#zemanta-links h2.vertical").each(function(){var M=n(this),J=M.parent(),K=J.find("> ul"),L=J.find("p.zem-clear").length,I=38;
K.css("overflow","auto");
M.height(I);
J.height("");
I=J.height()+(H&&6||0);
M.height(I);
if(!L){J.height(I+2)
}K.css("overflow","")
});if(H){n("#zemanta-links h2").css("width","100px");
h(function(){n("#zemanta-links h2").css("width","")
},1)}},_prepObject:function(I,H){var J=g.helpers.simple_clone(I);
n.zextend(J,I.target[H]);
J.rdf={freebase:g.helpers.rdf_link(I,true),any:g.helpers.rdf_link(I)};
J.collidesWith=I.collidesWith;
return J},_select:function(I,H,J){J=J||g.control.sync.dom();
if(typeof J==="function"){return J.simple(g.links._select,this,arguments)
}I.title=I.title.replace(/"/g,"&quot;");
if(H===true){J=g.platform.links_insert(I,J)
}else{J=g.platform.links_remove(I,J)
}g.platform.set_html(J.html())
}};return F
}();y=function(){var G={initialized:false,handlers:{lastevent:{},click:function(H){if(G.handlers.lastevent==H){return
}G.handlers.lastevent=H;
if(G.enabled()){var J=n(this).text().replace(/\xA0/g," "),I=g.helpers.object_search(g.tags._tags,"name",J);
if(J&&I){if(n(this).is(".zemanta-selected")){g.tags._select(I,false)
}else{g.tags._select(I,true)
}n(this).toggleClass("zemanta-selected")
}}}},enabled:function(){return !!(g.tags_target_id&&v.getElementById(g.tags_target_id))
},template:t.ZTemplate('<li class="zemanta-tags-li">{name}</li>'),eliminateDuplicates:function(){g.tags._tags=g.helpers.merge_arrays({p:"name"},g.tags._tags,n.grep(g.tags._old,function(I,H){return !!I.active
}))},syncData:function(H){g.tags._tags=g.platform.tags_active(g.tags._tags,H)
}},F={_tags:[],_old:[],initialize:function(){if(!n("#zemanta-tags-div-ul").length&&G.enabled()){g.platform.tags_initialize()
}var H=n("#zemanta-tags");
if(!H.length){g.helpers.logGA(null,"widget/warn/jqz/tags-initialize-failed/"+g.interface_type+"/");
return g.log("Not initializing tags - no wrapper")
}else{if(g.widget_enabled===false||!G.enabled()){n("#zemanta-tags").not("#zemanta-sidebar #zemanta-tags").hide()
}}n("#zemanta-sidebar #zemanta-tags-div-ul").height(g.helpers.storage.get("#zemanta-sidebar #zemanta-tags-div-ul-size")===0?0:g.helpers.storage.get("#zemanta-sidebar #zemanta-tags-div-ul-size")||120);
if(!g.platform.disable_draggable_resize){n("#zemanta-sidebar #zemanta-tags h2").addClass("draggable").each(function(){g.helpers.drag("#zemanta-sidebar #zemanta-tags h2.draggable","#zemanta-sidebar #zemanta-tags-div-ul",{stop:function(){if(n.browser.msie){n("#zemanta-tags-div-ul").hide().show()
}}})})}n("#zemanta-tags h2 a.zemanta-help").attr("title","Need help with Tags?").attr("href","http://www.zemanta.com/faq/quickhelp/?tags#faqid-62");
if(g._preferences.return_tags==="0"){n("#zemanta-tags").hide()
}n("#zemanta-tags-div-ul li",n("#zemanta-tags")[0]).live("click",G.handlers.click);
G.initialized=true;
if(g.tags._tags.length){g.tags._render()
}},change_state:function(I,H){var J=g.control.sync.dom();
H=H||0;if(I){G.syncData(J);
if(!G.initialized){F.initialize()
}else{F._render()
}n("#zemanta-tags").show(H)
}else{n("#zemanta-tags").hide(H)
}g.control.widget.tags=I
},_success:function(I,H,J){if(!G.initialized||!G.enabled()){return H(J)
}J=J||g.control.sync.dom();
if(typeof J==="function"){return J.simple(F._success,this,arguments)
}g.log("tags success");
g.tags._old=g.tags._tags;
g.tags._tags=I.keywords;
F.process(J);
H(J)},process:function(H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(F.process,this,arguments)
}G.eliminateDuplicates();
if(g.control.widget.tags){G.syncData(H);
F._render()
}},_render:function(){n("#zemanta-tags-div-ul").empty();
var I=G.template,J=0,H=g.tags._tags.length,K=null;
for(;J<H;J+=1){K=n(I({name:g.tags._tags[J].name.replace(/ /g,"&nbsp;")})).appendTo("#zemanta-tags-div-ul").disableTextSelect();
if(g.tags._tags[J].active===true){K.addClass("zemanta-selected")
}}F.redraw();
g.platform.tags_all()
},redraw:function(){var H=n.browser.msie&&parseFloat(n.browser.version)<8;
n("#zemanta-tags h2.vertical").each(function(){var M=n(this),J=M.parent(),K=J.find("> ul"),L=J.find("p.zem-clear").length,I=38;
K.css("overflow","auto");
M.height(I);
J.height("");
I=J.height()+(H&&6||0);
M.height(I);
if(!L){J.height(I+2)
}K.css("overflow","")
});if(H){n("#zemanta-tags h2").css("width","100px");
h(function(){n("#zemanta-tags h2").css("width","")
},1)}},adjustHeight:function(){F.redraw()
},_getTagArray:function(){var H=n.map(n(v.getElementById(g.tags_target_id)).val().split(","),function(I){I=n.trim(I);
return I===""?null:I
});return H
},_setTagsFromArray:function(H){n(v.getElementById(g.tags_target_id)).val(H.join(", "))
},_select:function(I,H,J){J=J||g.control.sync.dom();
if(typeof J==="function"){return J.simple(g.tags._select,this,arguments)
}if(H===true){o.trigger("tags_insert",I);
g.platform.tags_insert(I);
I.active=true
}else{o.trigger("tags_remove",I);
g.platform.tags_remove(I);
I.active=false
}g.platform.set_html(J.html())
}};return F
}();s=function(){var F={animate_enabled:false,big_article_preview:false,inactive_sidebar:false,article_popup_parent:"#zemanta-sidebar",image_insert_at_cursor:false,image_config:false,big_gallery_preview:false,gallery_popup_parent:"#zemanta-sidebar",disable_draggable_resize:false,disable_messages:false,dnd_supported:false,rdf_supported:false,rjs_supported:false,rich_supported:false,rich_platforms:{YouTube:true,"Google Maps":true,"Last.fm":true,Slideshare:true,Wikinvest:true,"5min":true,Hulu:true},allow_get:true,dnd_top_node:function(G){return((n(G).parent().attr("nodeName")||"").toLowerCase()==="a")?n(G).parent():null
},dnd_lost_attribution:function(H,I){var J=(g.doctype!=="1")?' style="font-size:0.8em;"':"",G=n("<span class='zemanta-img-attribution'"+J+">"+I.attribution+"</span>");
G.insertAfter(n(H).parents(".zemanta-img").find("a").eq(0))
},gallery_height:192,articles_height:240,gallery_active:function(G,L){var K=[],J=[],I=null,H=null;
L.find(".zemanta-img a img").each(function(){var P=n(this),N=P.attr("src").replace(/\\,/g,","),M=P.parents("a").attr("href"),O=null;
if((H=H||g.helpers.array_index(G,"url_m"))[N]){O=n(this).parents(".zemanta-img");
H[N].active=O.hasClass("zemanta-action-dragged")?2:1
}else{if((I=I||g.helpers.array_index(G,"source_url"))[M]){O=n(this).parents(".zemanta-img");
I[M].active=O.hasClass("zemanta-action-dragged")?2:1;
I[M].url_m=N
}else{K.push(P)
}}}).end();
if(K.length>0){n.each(K,function(){var N=this.attr("src"),M={url_l:N,url_m:N,url_s:N,source_url:this.parent().attr("href")||N,height:0,width:0,url_m_w:this[0].width?this[0].width:0,url_m_h:this[0].height?this[0].height:0,url_s_w:this[0].width?this[0].width:0,url_s_h:this[0].height?this[0].height:0,confidence:0.5,license:"Licence unknown",description:this.attr("alt"),attribution:this.parents(".zemanta-img").find(".zemanta-img-attribution").html(),active:this.parents(".zemanta-img").hasClass("zemanta-action-dragged")?2:1};
t.image_size(M,"url_s");
if(!M.attribution){M.attribution=this.parent(".zemanta-img").text()
}J.push(M)});
G=G.concat(J)
}return G},gallery_insert:function(I,J){J=J||g.control.sync.dom();
if(typeof J==="function"){return J.simple(g.platform.gallery_insert,this,arguments)
}var H=g.platform.get_editor(),G=0;
J.prepend(g.platform.gallery_create(J.getDoc(),I,true));
if((!I.url_m_w||!I.url_m_h)&&g.doctype!=="1"&&H.win){b.setTimeout(function K(){G+=1;
var L=false;
n(".zemanta-img",H.element).each(function(){var P=this,M=this,O=n("img",this),N=O.length&&O[0].width;
if(N){while(P===M){M=n(P).children()[0];
if(n("img",M).length&&n(".zemanta-img-attribution",M).length){P=M
}}/*n(P).css("width",N+10)*/
}else{if(O.length){L=true
}}});if(L&&G<10){b.setTimeout(K,500)
}},500)}return J
},gallery_remove:function(G,H){if(G===undefined){return
}H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(g.platform.gallery_remove,this,arguments)
}if(typeof G==="string"){G={url_m:G}
}return H.find(".zemanta-img").each(function(){if(n(this).find("img").attr("src")===G.url_m){n(this).remove()
}}).end()},gallery_create:function(J,I,G){var L=I.description.length>50?I.description.substring(0,46)+"...":I.description,H={source_url:I.source_url,src:I.url_m,alt:L,attribution:I.attribution,box_width:I.url_m_w+10,width:I.url_m_w,height:I.url_m_h},K=null;
K=n((g.widget_version>2?g.platform.gallery_template_html(H):g.platform.gallery_template(G,H.width).run(H)),J);
if(!G){K.findWithSelf(".zemanta-img").addClass("zemanta-action-dragged").end()
}if((g.doctype!=="1"||I.rich)&&H.width&&H.height){/*K.find("img").attr({width:H.width,height:H.height}).parents(".zemanta-img").css("width",H.box_width).end().end()*/K.find("img").attr({width:H.width,height:H.height}).end()
}return K[0]
},gallery_template:function(J,K){var L=J?"":" zemanta-action-dragged",I="",H="",G=g.doctype_image1,M="";
if(g.doctype!=="1"&&K){G=G.slice(0,G.length-1)+';width:{box_width};"';
M='width="{width}" height="{height}" '
}if(g.rte_type==="rte"||g.rte_type==="textarea"){H=g.doctype_image3===""?"":g.doctype_image3.substr(0,g.doctype_image3.length-1)+'margin:1em 0 0;display:block;"';
I='<span class="zemanta-img'+L+'" '+G+'><a href="{source_url}"><img src="{src}" '+M+'alt="{alt}" '+g.doctype_image2+' /></a><span class="zemanta-img-attribution" '+H+">{attribution}</span></span>"
}else{I='<div class="zemanta-img'+L+'" '+G+'><a href="{source_url}"><img src="{src}" '+M+'alt="{alt}" '+g.doctype_image2+' /></a><p class="zemanta-img-attribution" '+g.doctype_image3+">{attribution}</p></div>"
}return new g.helpers.ZTemplate(I)
},gallery_template_item:"",gallery_template_html:function(G){var H=new g.helpers.ZTemplate(g.platform.gallery_template_item);
return H.run(G)
},gallery_popup_position:function(J,I){var H=Math.min(J.full_width-(g.gallery_width-1)*10,206),G=0;
if(g.platform.big_gallery_preview){I.width(260);
G=-I.width()+6;
I.css({top:n("#zemanta-gallery").position().top,left:G})
}else{I.width(H);
G=Math.floor(((J.source.position()||{}).left-6)/(J.last_pos-6)*(J.full_width-H))+6;
if(isNaN(G)){G=6
}I.css({left:G})
}},articles_active:function(G,K,J){var I=[],H=[];
K.find(".zemanta-article-ul-li a").each(function(){var L=(n(this).attr("href")||"").replace(/\\,/g,",");
if(J[L]){J[L].active=true
}else{I.push(n(this))
}});if(I.length>0){n.each(I,function(){var M=this.attr("href"),L={url:g.helpers.encode_url(M),confidence:0.5,published_datetime:"1970-01-01T00:00:01Z",zemified:0,title:this.text(),active:true};
H.push(L)});
H[0].last=true;
G=G.concat(H)
}return G},articles_insert:function(G,H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(g.platform.articles_insert,this,arguments)
}if(H.find(".zemanta-related-title").length===0){H.append(g.widget_version>2?g.platform.articles_template_wrapper:g.doctype_related1+g.doctype_related2)
}return H.find(".zemanta-article-ul").each(function(){var J={hash:"",article_url:G.url,title:G.title,host:g.helpers.extract_hostname(G.url)},I=g.widget_version>2?g.platform.articles_template_html(J):g.platform.articles_template().run(J);
n(this).append(I)
}).end()},article_popup_position:function(I){if(!g.platform.big_article_preview){var H=this,G=Math.round((I.source.position()||{}).left+parseInt(I.source.css("margin-left"),10));
if(isNaN(G)){G=6
}H.css({left:G})
}},article_big_popup_position:function(K,G,H){var O=null,P=null,Q=0,I=0,J=0,N=0,L=0,M=0;
O=K.source.position();
P=n("#zemanta-articles").position();
Q=n(H).outerHeight();
I=K.source.outerHeight();
J=P.top;N=J+n("#zemanta-articles").outerHeight();
L=O.top+P.top+I-Q*0.5;
M=-G.width()-4;
if(L<J){L=J
}else{if(L+Q>N){L=Math.max(J,N-Q)
}}G.css({top:L,left:M})
},articles_remove:function(H,I){I=I||g.control.sync.dom();
if(typeof I==="function"){return I.simple(g.platform.articles_remove,this,arguments)
}var G=n.fn.jquery==="1.1.4"?'[@href="'+H.url.replace(/~/g,"%7E")+'"]':'[href="'+H.url.replace(/~/g,"%7E")+'"]';
I.find(".zemanta-article-ul "+G).parent().remove();
if(I.find("li.zemanta-article-ul-li").length===0){I.find(".zemanta-related, .zemanta-related-title, .zemanta-article-ul").remove()
}return I},articles_template:function(){return new g.helpers.ZTemplate('<li class="zemanta-article-ul-li{hash}"><a href="{article_url}">{title}</a> ({host})</li>')
},articles_template_wrapper:"",articles_template_item:"",articles_template_html:function(G){var H=new g.helpers.ZTemplate(g.platform.articles_template_item);
return H.run(G)
},links_initialize:function(){n("#zemanta-links").append('<h2 class="vertical"><span class="zemanta-links-title">In-text Links</span><a class="zemanta-help" href="http://www.zemanta.com/faq/quickhelp/?links#faqid-60">?</a></h2><ul id="zemanta-links-div-ul"><li class="zemanta-title"><span>Link recommendations will appear here</span></li></ul><p class="zem-clear">&nbsp;</p>')
},links_active:function(G,J){var I=g.helpers.array_index(G,"anchor"),K=[],H=[];
J.find("a.zem_slink").each(function(){var O=n(this),N=O.text(),L="",M=this.href;
if(I[N]){I[N].active=true;
L=n.grep(I[N].target,function(Q,P){return Q.url===M
});I[N].selectedtype=L[0]?L[0].type:g.links.getType(this.rel)
}else{if(n.trim(N).length){H.push(O.get(0))
}}});if(H.length>0){n.each(H,function(){K.push({active:true,selectedtype:g.links.getType(this.rel),anchor:n(this).text(),confidence:0.5,target:[{url:this.href,type:g.links.getType(this.rel),title:this.title}]})
});G=G.concat(K)
}return G},links_all:function(){if(g.links._links.length===0){n("#zemanta-links-div-ul").append('<li class="zemanta-title"><span>No links found</span></li>')
}else{if(!n("#zemanta-links-apply").length){n('<span id="zemanta-links-apply" title="Apply all in-text links">Apply all</span>').appendTo("#zemanta-links h2").disableTextSelect().click(function(){n(".zemanta-links-li:not(.zemanta-selected)").click()
})}}},links_insert:function(I,H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(g.platform.links_insert,this,arguments)
}var K="",M=/([?()+\.])/g,P=false,O="",J={};
function L(R){if(n(R).parents("a, .zemanta-img-attribution").length){return true
}return false
}function G(V,U,R){var T=0,S=V.collidesWith.length;
for(;T<S;T+=1){U=U.replace(V.collidesWith[T].anchor,"")
}return U.length>=V.anchor.length&&R.test(U)
}function N(){this.parentNode.replaceChild(g.helpers.create_fragment(n(this).text(),this.ownerDocument),this)
}function Q(U,ab,S){var Z=0,X=U.childNodes.length,W=0,V=0,R=false,T=null,af=null,ae=null,aa=null,ad=null,ac="",Y=[];
for(;Z<X;Z+=1){T=U.childNodes[Z];
if(T.nodeType===1){R=Q(T,ab,S);
if(R){return true
}}else{if(T.nodeType===3){af=T.nodeValue;
if(af.length<ab.anchor.length){continue
}if(!ad){ac=ab.anchor.replace(/&/g,"&amp;");
ac=ab.anchor.replace(M,"\\$1");
ad=new RegExp("([-!\"#$%&\u2018\u2019\u201C\u201D\u2014\u2013'()*+,./:;<=>\\?@\\[\\]_`{|}~\\s\\\\]+|^)("+ac+")([-!\"#$%&\u2018\u2019\u201C\u201D\u2014\u2013'()*+,./:;<=>\\?@\\[\\]_`{|}~\\s\\\\]+|$)")
}if(ad.test(af)&&!L(T)){if(!ab.collidesWith){T.parentNode.replaceChild(g.helpers.create_fragment(af.replace(ad,"$1"+S+"$3"),T.ownerDocument),T);
return true
}else{if(!!G(ab,af.slice(),ad)){ae=T.parentNode;
for(W=0,V=ab.collidesWith.length;
W<V;W+=1){aa=ab.collidesWith[W];
Q(ae,aa,'<a class="collision">'+aa.anchor+"</a>")
}Y=ab.collidesWith;
ab.collidesWith=null;
R=Q(ae,ab,S);
ab.collidesWith=Y;
n("a.collision",ae).each(N);
if(R){return true
}}}}}}}return false
}if(g.readside_javascript&&n.inArray("more-info",g.readside_javascript.split(" "))>-1){O=I.rdf.freebase;
if(I.rdf.freebase){O=" freebase/"+O.split("/ns/")[1]
}}J={href:I.url,title:I.title,rel:I.type,freebase:O||"",anchor:I.anchor,rdf:I.rdf.any};
K=g.widget_version>1?g.platform.links_template_html(J):g.platform.links_template().run(J);
P=Q(H.get(0),I,K);
o.trigger("links_insert");
return H},links_remove:function(G,H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(g.platform.links_remove,this,arguments)
}H.find("a.zem_slink").each(function(){var I=n(this);
if(I.text()===G.anchor){I.replaceWith(g.helpers.create_fragment(I.text(),I.getDoc()))
}}).end();return H
},links_template:function(){return new g.helpers.ZTemplate('<a href="{href}" title="{title}" rel="{rel}" class="zem_slink">{anchor}</a>')
},links_template_item:"",links_template_html:function(G){var H=new g.helpers.ZTemplate(g.platform.links_template_item);
return H.run(G)
},tags_initialize:function(){n("#zemanta-tags").append('<h2 class="vertical"><span class="zemanta-tags-title">Tags</span><a class="zemanta-help" href="http://www.zemanta.com/faq/quickhelp/?tags#faqid-62">?</a></h2><ul id="zemanta-tags-div-ul"><li class="zemanta-title"><span>Tag recommendations will appear here</span></li></ul><p class="zem-clear">&nbsp;</p>')
},tags_active:function(G,J){var I=g.helpers.array_index(G,"name"),H=[];
n.each(g.tags._getTagArray(),function(){var K=this.toString();
if(I[K]){I[K].active=true
}else{if(I[K.replace(/"/g,"")]){I[K.replace(/"/g,"")].active=true;
I[K.replace(/"/g,"")].name=K.replace(/"/g,"")
}else{if(g.tags._old.length===0){H.push({name:K,confidence:0.5,active:true})
}}}});return g.helpers.merge_arrays({p:"name"},G,H)
},tags_all:function(){if(g.tags._tags.length===0){n("#zemanta-tags-div-ul").append('<li class="zemanta-title"><span>No tags found</span></li>')
}else{if(!n("#zemanta-tags-apply").length){n('<span id="zemanta-tags-apply" title="Apply all tags">Apply all</span>').appendTo("#zemanta-tags h2").disableTextSelect().click(function(){n(".zemanta-tags-li:not(.zemanta-selected)").click()
})}}},tags_insert:function(G){g.tags._setTagsFromArray(g.tags._getTagArray().concat(G.name))
},tags_remove:function(G){g.tags._setTagsFromArray(n.grep(g.tags._getTagArray(),function(H){return H!==G.name
}))},object_align:function(G,H){if(H){if(H==="right"||H==="left"){n(G).css("float",H)
}}else{return n(G).css("float")||""
}},ro_rich_to_object:function(H){var G=false;
n(".zemanta-rich",H).each(function(J){var N=null,I=null,L=null,M=null,K=n("<div></div>")[0],O=g.platform.object_align(this)||"";
if(this.getElementsByTagName("object").length||this.getElementsByTagName("iframe").length){N=g.rich.rich_to_object(this)
}else{L=g.platform.get_editor(true);
if(L.all.plain.element){K.innerHTML=L.all.plain.element.value;
M=n(".zemanta-rich",K).get(J);
N=g.rich.rich_to_object(M)
}}if(N){I=g.platform.gallery_create(this.ownerDocument,N,!n(this).hasClass("zemanta-action-dragged"));
if(O){g.platform.object_align(I,O)
}g.gallery._old.splice(0,0,N);
g.gallery._images.splice(0,0,N);
this.parentNode.replaceChild(I,this);
G=true}});return G
},ro_image_to_rich:function(H,I){var G=n(H).find(".zemanta-img-attribution");
n(H).html(I.html).removeClass("zemanta-img").addClass("zemanta-rich");
if(G.html()!==""){n(H).append(G)
}return true
},ro_rerender_rich:function(H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(g.platform.ro_rerender_rich,this,arguments)
}var G=false;
n(".zemanta-img",H).each(function(){var J=n("img",this)[0],L=J&&J.src||"",K=g.helpers.object_search(g.gallery._images,"url_m",L),M=g.platform.object_align(this),I=null;
if(K){K.url_s=K.url_s.replace(/api_key=([a-z0-9]*)/,"api_key="+g.api_key);
K.url_m=K.url_m.replace(/api_key=([a-z0-9]*)/,"api_key="+g.api_key);
if(K.rich){I=g.platform.gallery_create(H.getDoc(),K,!n(this).hasClass("zemanta-action-dragged"));
if(I.innerHTML!==this.innerHTML){if(M){g.platform.object_align(I,M)
}this.parentNode.replaceChild(I,this);
G=true}}}});
return G},widget_nonrte_screen:function(){if(g.platform.widget_enabled_screen){n("#zemanta-disabled").remove();
return g.platform.widget_enabled_screen.apply(this,arguments)
}var G=n("#zemanta-disabled");
if(G.hasClass("zemanta-nonrte")){return G
}else{G.remove();
return n('<div id="zemanta-disabled" class="zemanta-nonrte"></div>').html('<div class="zemanta-disabled-back"></div><div id="zemanta-disabled-wrap"><div id="zemanta-disabled-content" class="zemanta-tip"><h3>Zemanta is inactive!</h3><p>You need to compose your blog post in <strong>'+g.control.interface_compose_text+"</strong> mode in order for Zemanta to work!</p></div></div>").fin()
}},widget_firstvisit_screen:function(J,L){var K=null,G=null,I=function(M){var N=0,P=Math.max(140-M,0),Q="",O=(P.toString(10)).split(""),R=O.length;
if(P<=0){r(K);
H(true);g.initialize_parts(3)
}for(N=R-3;
N<R;N+=1){Q+="<span>"+(N>=0?O[N]:0)+"</span>"
}return Q},H=function(M){n(".zemanta-source-mine, #zemanta-preferences").css("display",M?"block":"none")
};J=J||n("#zemanta-initial");
if(g._preferences&&g._preferences.account_type!=="pro"){H(false);
J.html("").append(n('<div id="zemanta-first-wrap"><div class="counter-wrap"><p class="start">Start writing!</p><p class="counter-msg-top">Zemanta needs about</p><div id="firstscreen-counter">'+I(L)+'</div><p class="counter-msg-bottom">letters to do its magic.</p><p class="button-msg">Not so sure what<br />to write about?</p><div class="button"><a class="init" href="#"><span>Get inspired!</span></a></div></div><div class="foot"><p class="notfinding">Not finding exactly what you need? <a href="http://getsatisfaction.com/zemanta/" class="contact external">Give us a shout</a> and we\'ll be more than happy to help you find what you are looking for!</p></div></div>').find("a.prefs").click(g.control.preferences.open).end().find("a.external").click(function(M){M.preventDefault();
b.open(this.href)
}).end().find("a.init, a.first-init").click(function(M){M.preventDefault();
H(true);r(K);
g.initialize_parts(3)
}).end()).show();
K=f(function(){var O=null,M=0,N=g.control.sync.dom()[0];
if(!G||!N||G.innerHTML.length!==N.innerHTML.length){G=N;
O=g.platform.filter_zemanta(n(G));
M=n.trim(O.text()).length;
n("div#firstscreen-counter").html(I(M))
}},100)}else{J.html('<div id="zemanta-initial-wrap"><p>No recommendations yet, please write more text.</p></div>');
if(L>0){g.one("recommendationsReceived",function(){g.initialize_parts(4)
})}}},widget_returnvisit_screen:function(G,J){G=G||n("#zemanta-initial");
var H=new Date(),I=g.tips[Math.ceil((H-new Date(H.getFullYear(),0,1))/86400000)%g.tips.length];
if(g._preferences&&g._preferences.account_type!=="pro"){if(I.content.indexOf("{")>-1){I.content=t.zTemplate(I.content,g._preferences)
}G.html("").append(n('<h2><span class="zemanta-initial-title">Hi '+(g._preferences.name||"there").split(" ")[0]+'!</span></h2><div id="zemanta-initial-wrap"><p>It\'s good to see you again! Looking for a topic?</p><div class="button"><a class="init" href="#"><span>Get inspired</span></a></div><p class="action">or just <strong>start writing!</strong></p><div id="zemanta-tip"><h3>Zemanta tip of the Day!</h3><h4>'+I.title+"</h4>"+I.content+(I.more?'<p>Learn more <a href="'+I.more+'">here</a>.</p>':"")+'</div><p class="notfinding">Not finding exactly what you need? <a href="http://getsatisfaction.com/zemanta/" class="contact">Give us a shout</a> and we\'ll be more than happy to help you find what you are looking for!</p></div>').find("a.prefs").attr("href",g._preferences.config_url).click(g.control.preferences.open).end().find("a.external").click(function(K){K.preventDefault();
b.open(this.href)
}).end().find("a.init").click(function(K){K.preventDefault();
g.initialize_parts(3);
g.helpers.logGA(null,"widget/stat/jqz/get_inspired/"+g.interface_type+"/")
}).end()).show()
}else{G.html('<div id="zemanta-initial-wrap"><p>No recommendations yet'+(J<300?", please write more text":"")+".</p></div>")
}},signature_modify:function(I,G){var H=n("<div>"+I+"</div>").find(".zem-script").remove().end().find("script").remove().end();
if(G==="0"||G==="-1"){H.find(".zemanta-pixie").attr("style","margin-top:10px;height:15px").end().find(".zemanta-pixie-img").attr("style","border:none;float:right").end()
}I=H.html();
if(G==="1"){I=g.helpers.close_tags(I,G)
}g.log("signature_modify:"+I);
return I},control_setHTML:function(I,M,L,H){var J={},K=L&&' rel="nofollow"'||"",G=H&&' target="_blank"'||"";
if(I==="1"){J.doctype_image1="";
J.doctype_image2="";
J.doctype_image3="";
J.doctype_related1='<div class="zemanta-related"><h6 class="zemanta-related-title">'+(g._preferences.article_layout_title||"Related articles")+'</h6><ul class="zemanta-article-ul">';
J.doctype_related2="</ul></div>";
J.doctype_pixie1="";
J.doctype_pixie2="";
g.platform.articles_template_wrapper='<div class="zemanta-related"><h6 class="zemanta-related-title">'+(g._preferences.article_layout_title||"Related articles")+'</h6><ul class="zemanta-article-ul"></ul></div>';
g.platform.gallery_template_item='<div class="zemanta-img"><a href="{source_url}"'+K+G+'><img src="{src}" alt="{alt}" /></a><p class="zemanta-img-attribution">{attribution}</p></div>'
}else{J.doctype_image1='style="margin:1em;display:block"';
J.doctype_image2='style="border:none;display:block"';
J.doctype_image3='style="font-size:0.8em;"';
J.doctype_related1='<fieldset class="zemanta-related"><legend class="zemanta-related-title">'+(g._preferences.article_layout_title||"Related articles")+'</legend><ul class="zemanta-article-ul">';
J.doctype_related2="</ul></fieldset>";
J.doctype_pixie1='style="margin-top:10px;height:15px;"';
J.doctype_pixie2='style="border:none;float:right;"';
g.platform.articles_template_wrapper='<fieldset class="zemanta-related"><legend class="zemanta-related-title">'+(g._preferences.article_layout_title||"Related articles")+'</legend><ul class="zemanta-article-ul"></ul></fieldset>';
g.platform.gallery_template_item='<div class="zemanta-img" '+J.doctype_image1+'><a href="{source_url}"'+K+G+'><img src="{src}" alt="{alt}" '+J.doctype_image2+' /></a><p class="zemanta-img-attribution" '+J.doctype_image3+">{attribution}</p></div>"
}g.platform.articles_template_item='<li class="zemanta-article-ul-li{hash}"><a href="{article_url}"'+K+G+">{title}</a> ({host})</li>";
g.platform.links_template_item='<a class="zem_slink{freebase}" href="{href}" title="{title}" rel="{rel}'+(L&&" nofollow"||"")+'"'+G+">{anchor}</a>";
return J},control_getRID:function(I){I=I||g.control.sync.dom();
if(typeof I==="function"){return I.simple(g.platform.control_getRID,this,arguments)
}var H=I.find(".zemanta-pixie-img").attr("src"),G="";
try{if(H&&H.indexOf("x-id=")>=0){G=H.split("x-id=")[1]
}else{I.find(".zemanta-article-ul-li a, .zem-slink").filter("[href^=http://r.zemanta.com]").each(function(){H=this.href;
if(H.indexOf("rid=")>=0){G=G||H.split("rid=")[1].split("&")[0]
}})}}catch(J){}return G
},set_image_wrapper_size:function(H,G){return H.css("width",G)
},insert_zemanta_image:function(H,G){return{dom:H,cursorInsert:false}
},hide_align_image_config:false,change_image_alignment:function(G,H){return G
},get_image_alignment:function(H){var G=H.css("float");
if(G=="left"){return"left"
}else{if(G=="right"){return"right"
}else{if(H.css("display")=="block"&&H.css("margin-left")=="auto"&&H.css("margin-right")=="auto"){return"center"
}}}return"none"
},get_image_align_element:function(G){return G.closest(".zemanta-img")
},get_image_caption_text:function(G){return g.platform.get_image_caption_elm(G).text()
},get_image_caption_elm:function(G){return G.find(".zemanta-img-attribution")
},remove_image_caption_elm:function(G){return G.remove()
},update_image_caption_text:function(G,H){return G.text(H)
},add_image_caption_elm:function(G,I){var H=(g.doctype!=="1")?' style="font-size:0.8em;"':"";
return G.append(n('<p class="zemanta-img-attribution"'+H+"></p>").text(I))
},get_editor:function(G){var L=null,J=null,H={element:null,property:null,type:null,win:null},I=null;
try{J=n("#"+g.rte_id).prop("contentWindow");
L=n("#"+g.rte_id+":first").get(0)||n("#"+g.rte_id).get(0);
I={rte:{element:J&&J.document.body,property:"innerHTML",type:"RTE",win:J},plain:{element:L,property:"value",type:L&&L.tagName.toLowerCase(),win:null}};
if(g.rte_type==="rte"||g.rte_type==="archetype"){H=I.rte
}else{if(g.rte_type==="textarea"){H=I.plain
}else{H=I.plain
}}}catch(K){g.helpers.logGA(null,"widget/warn/editor/"+g.interface_type+"/");
g.log(K)}return G?{active:H,all:I}:H
},get_dom:function(I,L){var G=null,K=null,J=null,H=g.platform.get_editor();
K=(H.type==="RTE"&&H.element)?n(H.element.cloneNode(true)):null;
if(!K){J=v.createElement("div");
if(H.element!==null){G=H.element[H.property]
}if(G!==undefined&&G!==null&&g.saveNewlines){G=G.replace(g.nlRegex,g.nlrep)
}J.innerHTML=G||"";
K=n(J)}K=g.platform.filter_dom(K,H.type,I);
if(I&&K){K=g.platform.filter_zemanta(K)
}return K.fin()
},get_title:function(){if(b.external&&typeof b.external.GetContentHtml!=="undefined"){return""
}return n("#title").or("#post_title").val()||""
},filter_zemanta:function(G){return G.find(".zemanta-img, .zemanta-action-click, .zemanta-img-attribution, .zemanta-related, .zemanta-related-title, .zemanta-article-ul, .zemanta-article-ul-li, .zemanta-pixie, .zemanta-pixie-a, .zemanta-pixie-img, #zemanta-pixie, .zemanta-reblog-cite, #zemanta-pixie-img").remove().end().find("a.zem_slink, a.zem_olink").each(function(){n(this).replaceWith(g.helpers.create_fragment(n(this).html(),G.getDoc()))
}).end().fin()
},filter_dom:function(H,G){return H
},get_html:function(H,I){I=I||g.control.sync.dom();
if(typeof I==="function"){return I.simple(g.platform.get_html,this,arguments)
}var G=I?n.trim(I.html()):"";
g.log("get_html:"+G);
return G},set_html:function(H,J,L){if(H===null||typeof H==="undefined"||H===""){return""
}if(!J){H=g._pixie(H)
}if(H&&g.saveNewlines){H=H.replace(g.nlRegex,"").replace(g.nlrepRegex,g.nl)
}H=g.helpers.close_tags(H,g.doctype);
var I=g.platform.get_editor(),K=null,G=null;
if(L){return H
}if(I.element){if(I.type==="RTE"){G=I.element;
K=g.helpers.create_fragment(H,G.ownerDocument);
if(!g.control.isEnabled(K)){g.helpers.logGA(null,"widget/error/editor/DOM-change-from-rte-not-enabled/"+g.interface_type+"/");
return g._set_status(6,{text:"Something was wrong when fetching content from the editor - stopping before we lose you any content.",type:"warning",errortype:4})
}if(I.win){I.win.document.ignoreDOMevents=true
}while(G.firstChild){G.removeChild(G.firstChild)
}G.appendChild(K);
if(I.win){I.win.document.ignoreDOMevents=false
}}else{if(!g.control.isEnabled(H)){g.helpers.logGA(null,"widget/error/editor/DOM-change-from-plaintext-not-enabled/"+g.interface_type+"/");
return g._set_status(6,{text:"Something was wrong when fetching content from the editor - stopping before we lose you any content.",type:"warning",errortype:4})
}I.element[I.property]=H
}}},fallback_deployment:function(G){g.timeout={"zemanta.preferences":10000,"zemanta.suggest":40000,"zemanta.post_published_ping":5000};
if(g.deployment.indexOf("wnjson")===-1){g.deployment+=" (fallback:wnjson)";
g.load=function(H){H.url=g.api_url;
return g.default_load(H)
};if(G){G.deployment=g.deployment
}g.log("new fallback deployment: "+g.deployment);
o.trigger("deployment_changed");
return true
}else{return false
}},scroll:function(H){switch(typeof H){case"object":H=H.length?H.offset().top:0;
break;case"number":break;
case"string":H=H==="bottom"?5000:0;
break;default:H=0;
break}var G=g.platform.get_editor();
if(G.element){G.element.scrollTop=H-5;
G.element.scrollLeft=0
}},move_to_start:function(I){var G=n(I).prevAll("p, br"),H=null;
if(G.length>0&&n(I).nextAll().length!==0){H=G[0];
if(H!==n(I).prev().get(0)){n(I).insertAfter(H)
}}else{G=n(I).parents("p");
if(G.length&&n(I)[0].nextSibling!==null){H=G[0];
if(H.firstChild!==I){n(I).insertBefore(H.firstChild)
}}}},rewrap_image:function(K,J,N,G){var L=g.platform.object_align(N)||"",P=null,I=n("img",N).attr("width"),O=n("img",N).attr("height"),M=n(N).hasClass("zemanta-img")&&!n(N).hasClass("zemanta-action-dragged")?"zemanta-gallery-img-clicked":"zemanta-gallery-img-dragged",H=g.platform.get_editor();
g.log("nC: rewrapping "+N.tagName+" / "+J.url_m_w);
if(!K.ignoreDOMevents){K.ignoreDOMevents=true;
h(function(){K.ignoreDOMevents=false
},1)}n(N).parents("a, .zemanta-pixie, .zemanta-article-ul, .zemanta-related-title").each(function(){var Q=n(this);
if(Q.parent().hasClass("zemanta-related")){Q=Q.parent()
}else{if(Q.hasClass("zemanta-article-ul")&&Q.prev().hasClass("zemanta-related-title")){Q=Q.prev()
}}Q.before(N)
});P=g.platform.gallery_create(K,J,M==="zemanta-gallery-img-clicked");
if(L){g.platform.object_align(P,L)
}if(G&&I>0&&O>0&&(I!==J.url_m_w||O!==J.url_m_h)&&!(J.rich&&(I===J.url_s_w||!I)&&(O===J.url_s_h||!O))){n("img",P).attr("width",I);
n("img",P).attr("height",O)
}n(N).find("img").each(function(){g.platform.mark_gallery(g.helpers.object_search(g.gallery._images,"url_m",this.src),false)
}).end().replaceWith(P);
g.platform.mark_gallery(J,M);
if(H&&H.element&&H.element.nodeType&&!J.poc){g.platform.set_html(H.element[H.property])
}},mark_gallery:function(H,G){if(!H){return
}var I=n('li.zemanta-gallery-li img[src="'+H.url_s+'"]').parent();
if(G){I.addClass(G)
}else{I.removeClass("zemanta-gallery-img-dragged").removeClass("zemanta-gallery-img-clicked")
}},nodesChanged:function(I,K){var H=this,G=false,J={},L={};
g.log("nodesChanged: "+H.ignoreDOMevents);
if(H.ignoreDOMevents){return
}o.trigger("nodesChanged",this);
n("img:not(.zemanta-img-configured)",this).each(function(){g.log("nC: looking for image: "+this.src);
var M=g.helpers.object_search(g.gallery._images,"url_m",this.src),O=this,N=null;
g.log("nC: img: "+(M&&M.url_s)+"/"+(M&&M.url_m));
if(M){g.log("nC: medium image");
if(n(this).parents(".zemanta-img").length>0){g.log("nC: found our image with wrapper: "+n(this).parents(".zemanta-img").html());
if(n(this).parents(".zemanta-img").find(".zemanta-img-attribution").length===0&&g.platform.gallery_template_item.indexOf("zemanta-img-attribution")>=0&&g._preferences.account_type!=="pro"){O=n(this).parents(".zemanta-img").get(0);
g.log("nC: found our image with wraper but no attribution, "+n(this).parents("a").attr("href")+" === "+M.source_url+"/"+M.attribution);
g.platform.rewrap_image(H,M,O,true)
}else{if(!isNaN(parseInt((n(this).parents(".zemanta-img").get(0)||{style:{}}).style.width,10))&&n(this).parents(".zemanta-img").css("width")!==n(this).width()+10&&n(this).width()!==0){/*n(this).parents(".zemanta-img").css("width",n(this).width()+10)*/
}if(M.url_s===M.url_m){G=true
}}if(L[this.src]||J[this.src]){n(this).parents(".zemanta-img").remove()
}else{J[this.src]=n(this).parents(".zemanta-img")
}}else{if(n(this).parents("a").attr("href")===M.source_url){g.log("nC: image has stuff around it - "+n.map(n(this).parents(),function(Q,P){return Q.tagName
}).join(" > "));
O=n(this).parents("a").get(0);
N=g.platform.dnd_top_node(this);
if(N){O=N.get(0)
}}g.log("nC: found our image without wraper, "+n(this).parents("a").attr("href")+" === "+M.source_url+"/"+M.attribution);
O=O||this;if(J[this.src]){J[this.src].remove()
}L[this.src]=function(){g.platform.move_to_start(O);
g.platform.rewrap_image(H,M,O,true)
}}}else{g.log("nC: not medium image");
M=g.helpers.object_search(g.gallery._images,"url_s",this.src);
g.log("nC: 2nd img: "+(M&&M.url_s)+" / "+(M&&M.url_m));
if(M&&!g.gallery.imageConfig.isOpen){g.log("nC: found a small image, converting to big with wrapper "+M.url_m);
if(n(this).parents(".zemanta-img").length>0){O=n(this).parents(".zemanta-img").get(0);
g.log("nC: dropped in existing wrapper "+M.url_m)
}g.platform.move_to_start(O||this);
g.platform.rewrap_image(H,M,O)
}else{g.log("nC: found a foreign image")
}}});H.ignoreDOMevents=true;
n.each(L,function(N,M){M()
});if(G&&!K){g.platform.nodesRemoved.call(H)
}H.ignoreDOMevents=false
},nodesRemoved:function(K,G){var J=this;
g.log("nodesRemoved: "+J.ignoreDOMevents);
if(J.ignoreDOMevents){return
}function H(){var L=[],O={},M=null,N,P;
n("img",J).each(function(){if(n(this).parents(".zemanta-img").length>0){g.log("nR: found our image: "+this.src);
L.push({indoc:true,url_m:this.src,source_url:n(this).parents("a").attr("href"),dragged:n(this).parents(".zemanta-img").hasClass("zemanta-action-dragged")})
}else{g.log("nR: found foreign image: "+this.src)
}});N=n("#zemanta-gallery-thumbnails .zemanta-gallery-img-dragged, #zemanta-gallery-thumbnails .zemanta-gallery-img-clicked").length;
M=n("#zemanta-gallery-thumbnails").find("li").removeClass("zemanta-gallery-img-dragged").removeClass("zemanta-gallery-img-clicked").end();
if(L.length>0){g.log("nR: found "+L.length+" images, making selections");
O=g.helpers.merge_arrays({p:"url_m",e:true},L,g.gallery._images);
n.each(O.duplicate,function(){if(O.unionhash[this.url_m]&&O.unionhash[this.url_m].indoc){M.find('img[src="'+this.url_s+'"]').parents("li").eq(0).addClass(O.unionhash[this.url_m].dragged?"zemanta-gallery-img-dragged":"zemanta-gallery-img-clicked")
}});O=g.helpers.merge_arrays({p:"source_url",e:true},L,g.gallery._images);
n.each(O.duplicate,function(){if(O.unionhash[this.source_url]&&O.unionhash[this.source_url].indoc){M.find('img[src="'+this.url_s+'"]').parents("li").eq(0).addClass(O.unionhash[this.source_url].dragged?"zemanta-gallery-img-dragged":"zemanta-gallery-img-clicked")
}})}else{g.log("nR: no images, no selections")
}P=n("#zemanta-gallery-thumbnails .zemanta-gallery-img-dragged, #zemanta-gallery-thumbnails .zemanta-gallery-img-clicked").length;
if(N!==P){o.trigger("selection_updated")
}}function I(){n(".zemanta-img-attribution",J).each(function(){var L=n(this);
if(!L.parents(".zemanta-img").length){L.remove()
}});n(".zemanta-img",J).each(function(){var M=n(this).find("img"),L=0;
g.log("nR: found our wrapper with "+M.length+" image(s) - "+n(this).find("img").attr("src"));
if(M.length===0){g.log("nR: removing");
if(!J.ignoreDOMevents){J.ignoreDOMevents=true;
h(function(){J.ignoreDOMevents=false
},1)}n(this).remove()
}else{M.each(function(){if(g.helpers.object_search(g.gallery._images,"url_m",this.src)){L+=1
}});if(L>1){g.platform.rewrap_image(J,g.helpers.object_search(g.gallery._images,"url_m",M.eq(1).src)||g.helpers.object_search(g.gallery._images,"url_s",M.eq(1).src),this,true)
}}});H()}h(function(){I();
if(G){G(J)}},50)
},widget_resize:function(){},get_post_url:function(){return""
},update_button_initialize:function(){n("#zemanta-control").append('<div id="zemanta-control-update"><span class="zemanta-update-text">Update</span></div>')
}};return F
}();n.extend(g,function(){var F={debug_data:[],session_id:Math.floor(Math.random()*1000000),constants:{min_text_for_update:50},microcopy:{retry:"Retry",loading_preferences:"Loading preferences...",loading_preferences_failed:"Preferences failed to load.",fetching_content:"Fetching content..."},tips:[{title:"Use your own Flickr photos!",content:'<p>Want to give your blog post a more personal touch? Tell us your Flickr screen name and we will find the perfect picture, from your personal photostream.</p><p>To link your Flickr account, simply head over to your Zemanta <a href="#" class="prefs">preferences&nbsp;page</a>, locate the My Sources section and enter your Flickr screen name.</p>',more:""},{title:"Content from your own blogs",content:'<p>Share your personal blog sources and Zemanta will suggest related articles from your own content!</p><p>To link your personal RSS feeds, simply head over to your Zemanta <a href="#" class="prefs">preferences&nbsp;page</a>, locate the My Sources section and enter in your RSS feed URL.</p>',more:""},{title:"Refine - Got something specific in mind?",content:"<p>Blogging about puppies but want to add a picture of a kitten? No problem - use the <strong>Refine</strong> button to fine-tune the recommendations with additional keywords not found in your post.</p>",more:""},{title:"Amazon Affiliate links",content:'<p>For you bloggers out there making money with your Amazon Affiliate account, Zemanta has something for you!</p><p>Activate your Amazon Affiliate account by simply heading over to your Zemanta <a href="#" class="prefs">preferences&nbsp;page</a>, locate the Affiliate Marketing section and list your Amazon Affiliate ID (amazon.com only!).</p>',more:""},{title:"In-text links",content:"<p>Adding hyperlinks is easy peasy lemon squeezy with Zemanta's single-click link suggestions!</p><p>As you write your blog post, Zemanta picks out which people and terms are important in the text and recommends links to them. Inserting a link is a one click operation, but if you really want control, you can pick out a different destination from the drop-down (not available on all links).</p>",more:""},{title:"Tags",content:"<p>Adding tags to your post not only makes it easier for your readers to navigate through your blog archives, but it also helps you boost your SEO ranking by giving search engines more ways to find your content. Adding relevant tags is now only one click away.</p>",more:""},{title:"Adding images is a piece of cake!",content:"<p>Add photos to your blog post by simply choosing the image you wish to use from the recommendations and drag & drop it to where you want it.</p><p>To remove the image, simply click on it again in the sidebar. You can also select the image in the editor and press the delete button.</p>",more:""},{title:"Personalize your Zemanta Widget",content:'<p>You\'re missing out on a bunch of things by not being registered with Preferences!</p><p>You could:</p><ol class="zemanta-tip-list"><li>add your own and other blogs as sources of related articles</li><li>use your own Flickr images</li><li>set styling preferences and image positioning</li><li>disable the parts you don\'t use</li><li>use your Amazon Affiliate ID to earn money</li></ol><p>We\'re really not sure how can you blog without this.<div class="button zemanta-tip-button"><a class="prefs" href="{config_url}"><span>Register</span></a></div></p>',more:""}],get_test_version:function(K,J,H){J=J>0&&J<1&&J||0;
var G,I=0,L=0,O=0,N=g.api_key.charAt(0).toLowerCase(),M="0123456789abcdefghijklmnopqrstuvwxyz";
M=(M+M).substr(Math.floor(J*M.length)).substr(0,M.length);
K=typeof K==="number"&&[{p:K,r:true},{p:1-K,r:false}]||n.map(K,function(Q,P){if(!Q.p){Q={p:1/K.length,r:Q}
}I+=parseFloat(Q.p);
return Q});
L=I?1/I:1;if(H){G=[];
N="."}n.each(K,function(Q,S){var R=Math.round(M.length*O*L),P=Math.round(M.length*(O+S.p)*L);
if(M.substring(R,P).indexOf(N)>=0){G=S.r;
return false
}else{if(H){G.push(M.substring(R,P))
}}O+=S.p;return true
});return G!==undefined?G:K[K.length-1].r
},trigger_editorChange:function(){},initialize:function(G){if(g.widget_opened===undefined){g.widget_opened=true
}if(g.widget_enabled===undefined){g.widget_enabled=true
}if(!n("#zemanta-sidebar").length&&!G.platform.inactive_sidebar){return false
}G.platform.big_article_preview=G.platform.big_article_preview&&!(n.browser.msie&&(n.browser.version.substr(0,1)<8));
G.platform.big_gallery_preview=G.platform.big_gallery_preview&&!(n.browser.msie&&(n.browser.version.substr(0,1)<8));
if(G&&G.widget_version>=1){n.fextend(g.platform,G.platform)
}g.platform.animate_enabled=false;
g.rte_id=G.textarea_id;
g.rte_type=G.textarea_type;
if(!g.platform.get_editor().element){n("#zemanta-message").html("Finding editor...");
return null
}g.control.interface_compose_text="Rich Text";
if(!g.initialized&&typeof G.init==="function"){G.init(o)
}g.debug=t.check_feature("zemanta-debug")||t.check_feature("ZemantaDebug");
if(g.debug){g.bind("apikey_change",function(H){g.debug_data.push({time:new Date(),event:H.type,data:g.api_key})
})}g.setNewline=function(I){if(!I){return
}var H=I.indexOf("\r\n")>=0&&"\r\n"||I.indexOf("\n")>=0&&"\n"||I.indexOf("\r")>=0&&"\r";
if(H&&H!==g.nl){g.nl=H;
g.nlRegex=new RegExp(g.nl,"g")
}};g.nl=n.browser.msie?"\r\n":"\n";
g.nlRegex=new RegExp(g.nl,"g");
g.nlrep=n('<div><br class="zemanta-bogus" /></div>').html();
g.nlrepRegex=new RegExp(g.nlrep,"g");
g.saveNewlines=n.browser.msie;
g.prepixie=g.saveNewlines?g.nlrep+g.nlrep:g.nl+g.nl;
g.widget_version=G.widget_version||0;
g.rid=null;
g.api_url=(G.secure||q)?"https://sapi.zemanta.com/services/rest/0.0/":"http://api.zemanta.com/services/rest/0.0/";
g.proxy=G.proxy_url?G.proxy_url:null;
g.api_key=G.api_key&&g.control._apikey_isvalid(G.api_key)?G.api_key:g._zemanta_api_key();
o.trigger("apikey_change");
g.interface_type=G.interface_type;
g.tags_target_id=G.tags_target_id;
g.gallery_width=G.gallery_width?G.gallery_width:3;
g.latest_articles="Latest Update";
g._settings=G;
g._preferences={};
g.preferences={};
g.doctype="-1";
g.image_position={index:"0",title:"right"};
g.nofollow=G.nofollow||false;
g.link_target=G.link_target||false;
g.helpers.copy(["doctype_image1","doctype_image2","doctype_image3","doctype_related1","doctype_related2","doctype_pixie1","doctype_pixie2"]).from(g.platform.control_setHTML(g.doctype,g.image_position.title,g.nofollow,g.link_target)).to(g);
g.pixie='<div class="zemanta-pixie"><img class="zemanta-pixie-img" src="http://img.zemanta.com/pixy.gif" alt="" /></div>';
g._lastrequest={};
g._lastresponse={};
g._lasttransport={};
g.initial={};
g.unbind("editor").bind("editor",function(H,I){if(g.widget_opened){if(g.widget_enabled!==I){o.trigger("editorChange",I);
o.trigger(I?"editorRich":"editorPlain")
}}else{g.unbind("widgetOpen.delayedEditor").one("widgetOpen.delayedEditor",function(){o.trigger("editor",I)
})}});g.unbind("editorChange").bind("editorChange",function(H,I){if(I){g.control.widget_enable()
}else{g.control.widget_disable(g.platform.widget_nonrte_screen)
}});g.platform.get_editor();
g.load=this.set_deployment(g.proxy);
g.one("preferencesProcessed",function(H,I){if(I.nodeType){I=n(I)
}if(g._preferences.account_type!=="pro"){g.control.sources()
}if(!g.helpers.empty(g.initial)){g.initialize_parts(0,I)
}if(n("#zemanta-sidebar").width()>=278){n("#zemanta-preferences").addClass("zemanta-preferences-wide")
}g.initialize_logging()
});g.one("recommendationsProcessed.published_ping",function(H,I){g.platform.get_post_url(g.control.published_ping)
});g.one("widgetPartsProcessed",function(I){var H=null;
if(!g.platform.disable_messages&&n("#zemanta-links").length&&(g.platform.inactive_sidebar||n(".zemanta-wrap").length&&n("#zemanta-links").offset().top<n(".zemanta-wrap").offset().top)){H=n("#zemanta-message");
H.clone(true,true).insertBefore(n("#zemanta-links")).addClass("horizontal");
H.remove()}});
g.control.initialize();
g.dnd.setup();
g.initialized=true;
return this
},initialize_logging:function(){var J={pnn7x9qzeac9rqk9k92nhhut:1,"6hhd39hp5dx2kxfgee33vmgk":1,h33vag9v7epdakq3crdax84s:1,grw29kgdwfkr74zfcnne9ryy:1};
if(g.interface_type==="demo"||J[g.api_key]){return
}function M(N,O){}function K(N,O){}function I(N,O){}function H(N,O){}function L(N,O){if(g.load===g.default_load){g.helpers.logGA(null,"widget/log/deployment_changed/"+g.interface_type+"/"+(n.support.cors?"CORS":"wnjson")+"/")
}}function G(N,O){if(O.update_manual){}}g.bind("special_message_shown",M);
g.bind("special_message_clicked",M);
if(g.get_test_version(0.05,0.9723987107054278)){g.bind("image_insert",K);
g.bind("image_remove",K)
}if(g.get_test_version(0.05,0.8149768015473438)){g.bind("tags_insert",I);
g.bind("tags_remove",I)
}if(g.get_test_version(0.1,0.1024821782332026)){g.bind("articles_insert",H);
g.bind("articles_remove",H);
g.bind("articles_popup",H);
g.bind("articles_readmore",H)
}if(g.get_test_version(0.05,0.4876014397589565)){g.bind("editorChange",function(N,O){})
}if(g.get_test_version(0.1,0.030306524131447077)){g.bind("updateRecommendations",G)
}g.bind("deployment_changed",L)
},initialize_parts:function(K,M){if(g._preferences.account_type!=="pro"){try{g.control.messaging.queue.render()
}catch(N){n("#zemanta-message").hide()
}}M=M||g.control.sync.dom();
if(typeof M==="function"){return M.simple(g.initialize_parts,this,[K])
}var G=g.platform.filter_zemanta(n(M[0].cloneNode(true))),J=n.trim(G.text()).length,H=null,L=["gallery","articles","links","tags"],I=[];
if(!K){o.trigger("initialScreen");
K=g.platform.inactive_sidebar&&3||g.control.isFirstVisit()&&1||J===0&&2||3
}if(K===1){H=n("#zemanta-initial");
if(!H.length){H=n('<div id="zemanta-initial"></div>').insertBefore("#zemanta-gallery")
}g.platform.widget_firstvisit_screen(H,J);
n("#zemanta-gallery, #zemanta-articles, #zemanta-control").hide();
g.control.recordVisit("firsttimeuser");
if(J===0){g.one("recommendationsReceived",function(){g.initialize_parts(4)
})}o.trigger("firstvisitScreen")
}else{if(K===2){H=n("#zemanta-initial");
if(!H.length){H=n('<div id="zemanta-initial"></div>').insertBefore("#zemanta-gallery")
}g.platform.widget_returnvisit_screen(H,J);
n("#zemanta-gallery, #zemanta-articles").hide();
n("#zemanta-control").show();
g.control.recordVisit("repeatuser");
g.one("recommendationsReceived.firstUpdate",function(){g.initialize_parts(4)
});o.trigger("inspiremeScreen")
}else{if(K===3||K===4){g.unbind("recommendationsReceived.firstUpdate");
if(g._preferences.account_type!=="pro"){if(K===3){if(J<g.constants.min_text_for_update){g.control.initial.inspire();
o.trigger("inspireFinished")
}else{g.control.update(n("#zemanta-keyword-input").val(),false,M)
}}g.control.sources(true);
n("#zemanta-head h1").attr("Switch to Zemanta selection")
}n("#zemanta-initial, #zemanta-noselection").hide();
n("#zemanta-initial-wrap div.button, #zemanta-initial-wrap div.button a.init").hide();
n("#zemanta-gallery, #zemanta-articles, #zemanta-control").show();
g.load=g.set_deployment(g.proxy);
if(!g.platform.disable_draggable_resize){n("#zemanta-tools").addClass("draggable");
g.helpers.drag("#zemanta-tools.draggable",{above:["#zemanta-sidebar #zemanta-tags-div-ul","#zemanta-sidebar #zemanta-links-div-ul","#zemanta-sidebar #zemanta-articles-wrap","#zemanta-sidebar #zemanta-gallery-wrap"],stop:function(){if(n.browser.msie){n("#zemanta-tools").hide().show()
}}})}I=n.map(L,function(O,P){if(g.control.widget[O]){g[O].initialize();
return null
}else{return O
}});n("#zemanta-links a.zemanta-help, #zemanta-tags a.zemanta-help, #zemanta-sidebar a.zemanta-help").click(function(O){O.preventDefault();
b.open(this.href)
});n.map(I,function(O){g[O].change_state(false,0)
});if(!n("#zemanta-sidebar #zemanta-gallery:visible, #zemanta-sidebar #zemanta-articles:visible, #zemanta-sidebar #zemanta-links:visible, #zemanta-sidebar #zemanta-tags:visible").length){n("#zemanta-tools").removeClass("draggable")
}g.control.preferences.hide_parts();
o.trigger("widgetPartsProcessed")
}}}o.trigger("widgetReady")
},set_localfile:function(){var H="",G=b.location,J=G.protocol+"//"+G.host,I=10000;
n('img[src^="'+J+'"], img:not([src^="http://"])').each(function(){var L=this,K=parseInt(L.height,10)*parseInt(L.width,10);
if(K<I){I=K;
H=L.src}});
I=200;if(!H&&!(n.browser.safari&&i.userAgent.toLowerCase().indexOf("safari")>=0)){n('link[href^="'+J+'"][rel="stylesheet"]').each(function(){var M=this,K=M.sheet||M.styleSheet,L=K&&(K.rules||K.cssRules)||[];
if(L.length<I){I=L.length;
H=M.href}})
}return H.substr(J.length)
},default_load:function(G){g.log("default load "+t.JSON.stringify(G));
n.ajaxSetup({localfile:g.platform.localfile||g.set_localfile()});
var H={type:G.method,url:G.url,data:G.data};
if(G.method==="POST"){H.complete=G.onload
}else{if(G.method==="GET"){H.jsonp="jsoncallback";
H.dataType="jsonp";
H.success=function(I){G.onload({readyState:4,status:200,responseObject:I,responseText:"[jsonp request, object transfered]"},"success")
}}}return n.ajax(H)
},set_deployment:function(N){var I=null,K=this.deployment&&this.deployment.indexOf("fallback:")>=0,Q=function(T){g.log("load "+t.JSON.stringify(T));
var S=I(T);
return S},G=function(){return g._preferences&&g._preferences.account_type==="pro"
},L=function(){try{if(typeof b.external.GetContentHtml!=="undefined"){return true
}}catch(S){}return false
}(),M=function(){var S=null;
if(typeof w==="undefined"){return false
}try{S=new w("ZemantaBHO.ZemantaXMLHttpRequest");
if(S!==null){S=null;
return true
}}catch(T){}return false
}(),R=function(){return n('script[src^="http://labs.zemanta.com/bookmarklet"]').length
}(),P=function(){return b.ZemantaGetAPIKey&&!b.ZemantaGetReleaseId&&!b.ZemantaLoaded&&!b.ZemantaPluginVersion&&!N&&/chrome\//.test(i.userAgent.toLowerCase())
}(),H=function(){return b.ZemantaGetAPIKey&&!b.ZemantaGetReleaseId&&!b.ZemantaLoaded&&!b.ZemantaPluginVersion&&!N&&/safari\//.test(i.userAgent.toLowerCase())&&!/chrome\//.test(i.userAgent.toLowerCase())
}(),O=function(){return b.google&&b.google.Blog&&b.google.Blog.Editor
}();h(function(){o.trigger("deployment_changed")
},1);function J(S,U){try{var T=0;
while(S){if(typeof S[U]!=="undefined"){return S[U]
}T+=1;S=b!==b.parent?b.parent:null;
if(T>10){break
}}return null
}catch(V){}}this.deployment="unknown";
if(!G()&&(I=J(b,"GM_xmlhttprequest"))){this.deployment="greasemonkey";
return Q}if(!G()&&(I=J(b,"ZemantaXMLHttpRequest"))){this.deployment="firefox extension";
if(!N){return Q
}else{this.deployment+=" and "+g.interface_type+" "+(g._preferences&&g._preferences.account_type||"")+"plugin"
}}if(!G()&&L){this.deployment="microsoft desktop plugin "+g.interface_type;
return function(U){g.log("ms_plg load "+t.JSON.stringify(U));
var T=Math.random().toString(),S="";
b.zemantaStoreRequest(U,T);
if(b.external){S=null;
try{S=b.external.getPluginVersion();
if(S){b.external.makeXMLHttpRequest(U.method,U.url,U.data,T)
}}catch(V){}}return{abort:function(){}}
}}else{if(!G()&&M){this.deployment="ie extension";
if(!N){return function(U){g.log("ie_ext load "+t.JSON.stringify(U));
var W=null,V="";
W=new w("ZemantaBHO.ZemantaXMLHttpRequest");
W.open(U.method,U.url,true);
for(V in U.headers){if(U.headers[V]!==""&&typeof U.headers[V]==="string"){W.setRequestHeader(V,U.headers[V])
}}h(function T(){if(parseInt(W.readyState,10)===4){return U.onload(W,"success")
}h(T,42)},42);
try{W.send(U.data)
}catch(S){g.helpers.logGA(null,"widget/error/plugin/ie-send-error/"+g.interface_type+"/");
g.log("Error loading "+U.url+"/"+U.method,S)
}return W}}else{this.deployment+=" and "+g.interface_type+" "+(g._preferences&&g._preferences.account_type||"")+"plugin"
}}else{if(!G()&&R){this.deployment="bookmarklet (wnjson)";
return g.default_load
}else{if(!G()&&O){this.deployment="google gadget (wnjson)";
return g.default_load
}else{if(!G()&&P){this.deployment="chrome extension (wnjson)";
if(!N){return g.default_load
}else{this.deployment+=" and "+g.interface_type+" "+(g._preferences&&g._preferences.account_type||"")+"plugin"
}}else{if(!G()&&H){this.deployment="safari extension (wnjson)";
if(!N){return g.default_load
}else{this.deployment+=" and "+g.interface_type+" "+(g._preferences&&g._preferences.account_type||"")+"plugin"
}}}}}}}if(this.deployment==="unknown"&&g.proxy){this.deployment=g.interface_type+" "+(g._preferences&&g._preferences.account_type||"")+"plugin"
}if(this.deployment.indexOf("plugin")===this.deployment.length-6){if(b.ZemantaPluginVersion&&typeof b.ZemantaPluginVersion==="function"){this.deployment+=" "+b.ZemantaPluginVersion()
}}if(K){g.platform.fallback_deployment();
return g.load
}else{g.log("new deployment: "+this.deployment);
return g.default_load
}},rte_id:"",rte_type:"",proxy:null,timeout:{"zemanta.preferences":5000,"zemanta.suggest":20000,"zemanta.post_published_ping":5000},markerClassName:"hasZemanta",force_one_paragraph:function(H){H=H||g.control.sync.dom();
if(typeof H==="function"){return H.simple(g.platform.force_one_paragraph,this,arguments)
}var G=null;
if(n.trim(H.text()).length===0){G=H.find("p:first");
if(G.length===0||G.children().length>0){try{G=n("<p></p>",H.get(0)&&H.get(0).ownerDocument||g.platform.get_editor().element.ownerDocument).appendTo(H)
}catch(I){g.helpers.logGA(null,"widget/warn/jqz/force-one-paragraph-failed/"+g.interface_type+"/");
g.log("Inserting paragraph failed: "+I.message)
}}G.text("Write text here...")
}return H},_get:function(G){return(this._settings[G]!==null?this._settings[G]:g._defaults[G])
},_zemanta_api_key:function(){var H="",J="h33vag9v7epdakq3crdax84s",G=null;
try{if(typeof b.top.ZemantaGetPluginAPIKey!=="undefined"){H=b.top.ZemantaGetPluginAPIKey()
}else{if(typeof b.top.ZemantaGetAPIKey!=="undefined"){H=b.top.ZemantaGetAPIKey()
}else{if(n.browser.msie){try{G=new w("ZemantaBHO.ZemantaXMLHttpRequest");
H=G.ZemantaGetAPIKey()
}catch(I){g.helpers.logGA(null,"widget/error/jqz/ie-activex-object-failed/"+g.interface_type+"/");
g.log(I);H=J
}}}}}catch(K){g.helpers.logGA(null,"widget/warn/jqz/api-key-error/"+g.interface_type+"/");
g.log(K)}if(!H||!g.control._apikey_isvalid(H)){g.helpers.logGA(null,"widget/warn/jqz/api-key-using-hardcoded/"+g.interface_type+"/");
g.log("GetAPIKey did not return a key. Inserting hardcoded key.");
H=J}return H
},_pixie:function(M,H){M=M||"";
var L="",I="",J=g.pixie,K=null,N=null,G=null;
if(g.saveNewlines){M=M.replace(g.nlRegex,"")
}L=g.prepixie+n("<div>"+g.pixie+"</div>").html().substr(0,30);
I=M.lastIndexOf(L);
M=I>0?M.substr(0,I)+M.substr(I+g.prepixie.length):M;
K=n("<div></div>");
K[0].innerHTML=M;
if(!H&&n(".zemanta-pixie",K).length===1&&n(".zemanta-pixie",K).find('img[src^="http://img.zemanta.com"]').length>0&&(!g.rid||n(".zemanta-pixie",K).html().indexOf(g.rid)>0)){try{N=K[0].ownerDocument.createElement("div");
N.appendChild(n(".zemanta-pixie",K).find(".zem-script").attr("class","zem-script "+g.readside_javascript).end()[0]);
J=N.innerHTML.replace(" defer ",' defer="defer" ').replace(" type=text/javascript",' type="text/javascript"')
}catch(O){}}G=K.find('.zemanta-pixie, #zemanta-pixie, .zemanta-pixie-a, #zemanta-pixie-a, .zemanta-pixie-img, #zemanta-pixie-img, img[src*="img.zemanta.com"], .zem-script');
if(G.length>0){G.remove()
}else{o.trigger("trackEmails",g.rid)
}return K.html()+g.prepixie+J
},_rte_filterHTML:function(H,G){if(G&1){H.find("br[mce_bogus]").each(function(){var I=n(this).parent();
n(this).remove();
if(I.html()===""){I.remove()
}})}if(G&2){H.find("p").each(function(){if(n(this).html()===""){n(this).remove()
}})}return H
},_set_status:function(G,H){if(G===0){if(!n("#zemanta-control-update").length){g.platform.update_button_initialize();
g._enabled=true;
this.nf_status_code=0
}}else{if(G===1){n("#zemanta-control-update").find("span").text("Analyzing text").end().addClass("updating").removeClass("warning").removeClass("info");
this.nf_status_code=1
}else{if(G===2){n("#zemanta-control-update").find("span").text("Update").end().removeClass("updating").removeClass("warning").removeClass("info");
g._enabled=true;
this.nf_status_code=2
}else{if(G===4){g._enabled=false;
if(typeof this.nf_status_code!=="undefined"){g._set_status(this.nf_status_code)
}}else{if(G===5){n("#zemanta-control-update").find("span").text("Keep writing").end().addClass("info").removeClass("updating").removeClass("warning");
h(function(){g._set_status(2)
},5000)}else{if(G===6){n("#zemanta-control-update").find("span").text("Error").end().addClass("warning").removeClass("updating").removeClass("info");
h(function(){g._set_status(2)
},5000)}else{if(G===7){n("#zemanta-control-update").find("span").text("Updating in 3").end().addClass("updating").removeClass("warning").removeClass("info")
}else{if(G===8){n("#zemanta-control-update").find("span").text("Updating in 2").end().addClass("updating").removeClass("warning").removeClass("info")
}else{if(G===9){n("#zemanta-control-update").find("span").text("Updating in 1").end().addClass("updating").removeClass("warning").removeClass("info")
}else{if(G===10){n("#zemanta-control-update").find("span").text("No prefs").end().addClass("warning").removeClass("updating").removeClass("info");
h(function(){g._set_status(2)
},5000)}}}}}}}}}}if(H){g.control.messaging.queue.prepend(H)
}g.status=G
},post:function(K,W,L,T,P){var Q=null,O=2,H={},J=W.method==="zemanta.preferences"||W.method==="zemanta.suggest",S=W.method==="zemanta.preferences"&&"preferences"||W.method==="zemanta.suggest"&&"suggestions",G="",R="",M=null;
if(typeof T==="number"){P=T;
T=null}T=T||function(){};
P=P||1;function U(Y){h(function(){g.post(K,W,L,T,P+1)
},100+Math.floor(Math.random()*100))
}function V(Y,Z){if(J){g._set_status(6,Z)
}if(!Z&&W.method==="zemanta.preferences"){n("#zemanta-message").html(g.microcopy.loading_preferences_failed+" ").append(n('<a href="#">'+g.microcopy.retry+"</a>").css("cursor","pointer").click(function(){n("#zemanta-message").html(g.microcopy.loading_preferences);
g.post(K,W,L,T)
}))}T(Y)}function X(){if(P<=O){g.log("Silent retry "+P);
U("silentError, less than 2 tries")
}else{V("error")
}}function N(Z,aa,Y){if(P<=O){g.log(Z+" - retry "+P,aa);
U("handleError, less than 2 tries")
}else{if(g.platform.fallback_deployment(W)){P=0;
g.log(Z+" - fallback deployment activated",aa);
if(aa&&J){g.helpers.logGA(aa,"widget/warn/transport/fallback-activated/"+g.interface_type+"/")
}U("handleError, fallback deployment")
}else{g.log(Z+" - fallback deployment already active",aa);
if(aa&&J){g.helpers.logGA(aa,"widget/error/transport/fallback-active-giving-up/"+g.interface_type+"/")
}T("error",Y)
}}}function I(ah,ae){m(Q);
var af,aa,ab,Z,ac=false;
try{af=ah&&parseInt(ah.readyState,10);
aa=ah&&parseInt(ah.status,10);
ac=ah.processed
}catch(ag){g.log("This sometimes fails for no apparent reason - could be that the object exists but the properties are not accessible from JavaScript...");
g.helpers.logGA({status:ae,readystate:af,httpstatus:aa},"widget/error/transport/problem-accessing-fetched-content/"+g.interface_type+"/");
return X("xhr access error")
}if(ah.processed){return
}else{try{ah.processed=true
}catch(ad){}}if(ae==="timeout"){g.log("timeout happened");
g.helpers.logGA({status:ae,readystate:af,httpstatus:aa},"widget/error/transport/connection-timed-out/"+g.interface_type+"/");
return V("timeout",{text:"The connection timed out - sure you're online?",type:"warning",errortype:2})
}if(!ah||ah.aborted){g.log("not processing doCallback "+ae+" / "+(ah&&(ah.aborted||ah.readyState)));
g.helpers.logGA({status:ae,readystate:af,httpstatus:aa},"widget/error/transport/no-XHR-failed/"+g.interface_type+"/");
return X("no xhr, xhr aborted or error")
}if(af!==4){ae="error"
}else{if(ah.responseText==="<h1>403 Developer Over Rate</h1>"){ae="error";
aa=403}}g.log("doCallback "+ae+" / "+ah.responseText);
if(ae==="error"){if(aa===403&&ah.responseText==="<h1>403 Developer Over Rate</h1>"){g.helpers.logGA(null,"widget/error/api/developer-over-rate/"+g.interface_type+"/"+g.api_key+"/");
g.log("Developer Over Rate",ah);
n("#zemanta-control-update, #zemanta-refine").hide();
return V("error",{text:"It seems that you've gone over quota.",nodismiss:true,type:"warning",errortype:5})
}else{g.helpers.logGA(null,"widget/error/transport/"+ae+"/"+g.interface_type+"/");
ab=(/<body>(.*)<\/body>/g).exec(ah.responseText||"");
ab=ab&&ab[1]||(ah.responseText||"").substr(0,100);
return N("Transport "+ae+" "+aa+" on "+W.method+": "+ab,{xhr:ah,status:ae,readystate:af,httpstatus:aa},{text:"We had a problem fetching the "+S+" - you might want to try again later.",type:"warning",errortype:1})
}}else{if(aa===200){try{g._lasttransport[W.method]=ah.responseText;
Z=ah.responseObject||g.helpers.JSON.parse(ah.responseText);
g._lastresponse[W.method]=n.extend(true,{},Z);
if(!Z){return N("Empty content",null,{text:"The "+S+" we got back were empty - you might want to try again later.",type:"warning",errortype:3})
}}catch(Y){g.helpers.logGA({status:ae,readystate:af,httpstatus:aa},"widget/error/transport/content-mangled/"+g.interface_type+"/");
return N("Mangled content",null,{text:"The "+S+" we got back were mangled - you might want to try again later.",type:"warning",errortype:3})
}ae=L(Z);if(ae==="error"){return N("Illegal content",null,{text:"There was an error getting "+S+" - you might want to try again later.",type:"warning",errortype:3})
}}else{return N("Invalid response on "+W.method+": "+aa,{xhr:ah,status:ae,readystate:af,httpstatus:aa},{text:"Unknown error happened while fetching "+S+" - you might want to try again later.",type:"warning",errortype:6})
}}}if(W.method==="zemanta.suggest"){g._set_status(1)
}G=g.helpers.toQueryString(W);
R=!g.platform.allow_get||g.deployment.indexOf("wnjson")===-1||(K+"?"+G).length>2000?"POST":"GET";
H=n.zextend({method:R,headers:n.zextend({"Content-Type":"application/x-www-form-urlencoded; charset=utf-8"},g.helpers.ljext),url:K,data:G,onload:function(Z,Y){I(Z,Y)
}},g.helpers.ljext);
g.log(H);g._lastrequest[W.method]=n.extend(true,{},{data:W,send:H});
Q=h(function(){g.log("timeout:"+M);
if(M&&M.abort){try{M.abort();
M.aborted=true
}catch(Y){}g.log("aborted");
g.control.cancel_update()
}g.log("status is "+g.status);
I(M,"timeout")
},g.timeout&&g.timeout[W.method]||30000);
M=g.load(H)
},control:p,gallery:D,articles:k,links:x,tags:y,platform:s,helpers:t,rich:z,dnd:E,$:n};
return F}());
b.Zemanta=g;
n.zemanta=g;
n.fn.zemanta=function(F){g.initialize(F);
return this
};o.init(g);
g.helpers.storage.init()
}try{function a(g){var f=typeof g==="function"&&g();
return f&&f.jquery&&f.zemanta
}function c(){var g=b.jQuery,f=null;
if(!g){throw"No jQuery!"
}if(!a(g)){do{b.jQuery.noConflict(true);
if(b.jQuery&&a(b.jQuery)){f=b.jQuery;
break}}while(b.jQuery);
b.jQuery=b.$=g
}else{f=g.noConflict(true)
}if(zemantaStart.jQuery){return true
}else{if(f){b.jQuery=b.jQuery||f;
b.$=b.$||f;
if(!b.zQuery){zemantaStart.jQuery=b.zQuery=f;
return true
}else{return false
}}}throw ("Waiting for Zemanta jQuery...")
}if(c()){d(b.zQuery)
}}catch(e){zemantaStart.tries=zemantaStart.tries+1||1;
zemantaStart.timeout=zemantaStart.timeout*2||100;
if(zemantaStart.tries<=10){b.setTimeout(function(){zemantaStart(b)
},zemantaStart.timeout)
}return}}(window));