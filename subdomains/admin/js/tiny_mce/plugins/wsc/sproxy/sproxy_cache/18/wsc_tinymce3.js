var sWSCBasePath = '<#sproxy_url#>?cmd=script&doc=';
var sWSCMainSrcPath = sWSCBasePath + 'wsc';
var sWSCBtnPath = 	sWSCBasePath + 'image&img=btn_wsc_tinymce';


(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('wsc');
	tinymce.create('tinymce.plugins.wsc', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;

			// Register commands
			ed.addCommand('mceWSC', function(ui) {
				doSpell({
					lang:ed.getParam('wsc_lang') || "en_US",
					title:ed.getParam('wsc_popup_title') || "WebSpellChecker",
					schemaIdentifier: ed.getParam('wsc_schemaIdentifier'),
					ctrl:ed.id+'_ifr',
					onCancel: ed.getParam('wsc_popup_cancel') || null,
					onClose: ed.getParam('wsc_popup_close') || null,
					onFinish: ed.getParam('wsc_popup_finish') || null
				});
			});
			ed.addButton('wsc', {title : 'wsc.desc', cmd : 'mceWSC',image : sWSCBtnPath});


			ed.onBeforeSetContent.add(function(ed, o) {
				//console.info(ed,o);
				//document.getElementById(ed.id+'_WSC').style.background = 'url('+sWSCBtnPath+')';;
				//ed.dom.addClass(ed.id + '_SCAYT', 'mceButtonDisabled');

				});
			ed.onPostProcess.add(function(ed, o) {
				//console.info(ed,o);
					
			});
			},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @returns Name/value array containing information about the plugin.
		 * @type Array 
		 */
		getInfo : function() {
			return {
				longname : 'WebSpellChecker',
				author : 'www.WebSpellChecker.net',
				authorurl : 'http://www.webspellchecker.net',
				infourl : 'http://www.webspellchecker.net',
				version : ""
			};
		}


	});
	//WSC engine file load entry point*/
	var nHead = document.getElementsByTagName('head')[0];
	var nScript = document.createElement('script');
	nScript.setAttribute('type','text/javascript');
	nScript.setAttribute('src',sWSCMainSrcPath);
	nHead.appendChild(nScript);
	// Register plugin
	tinymce.PluginManager.add('wsc', tinymce.plugins.wsc);
})();
