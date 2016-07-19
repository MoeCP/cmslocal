/**
 * $Id: editor_plugin_src.js 201 1/20/2010 15:56:56Z spocke $
 *
 * @author Leo.liuxl@gmail.com
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('charcount');

	tinymce.create('tinymce.plugins.CharcountPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
            var t = this;
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mceCharcount', function() {
                var content = tinyMCE.activeEditor.getContent();
                var words = t._countWords( content );
                //var chars = this._countChars( content, false );
                var chars_spaces = t._countChars( content, true );
                var chars = chars_spaces - words + 1;

                var count_text = ed.getLang('charcount.desc')+'\n';
                    count_text += ed.getLang('charcount.words')+': '+words+'\n';
                    count_text += ed.getLang('charcount.chars')+': '+chars+'\n';
                    count_text += ed.getLang('charcount.chars_with_spaces')+': '+chars_spaces+'\n';

                alert( count_text );
                //ed.windowManager.alert(count_text);
   
                //return true;
                //alert(tinyMCE.activeEditor.getContent());
			});

			// Register example button
			ed.addButton('charcount', {
				title : 'charcount.desc',
				cmd : 'mceCharcount',
				image : url + '/img/charcount.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('charcount', n.nodeName == 'IMG');
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

        _countWords : function( content ) {
            content = this._clean( content );
            content = content.replace(/[ ]+/g, ' ');
            var arr = content.split(' ');
            
            var total = 0;
            for(var i=0; i<arr.length; i++)
            {
                if( arr[i].length){
                  total ++;
                }
            }
            return total;
        },

        _countChars : function( content, spaces ) {
            content = this._clean( content );

            var total = 0;
            if(!spaces)
            {
                content = content.replace(/[ ]+/g, '');
            }
            total = content.length;

            return total;
        },

        _clean : function( content ) {

            content = content.replace(new RegExp('</p>', 'g'), '</p>\n');
            content = content.replace(new RegExp('</div>', 'g'), '</div>\n');
            content = content.replace(new RegExp('<br[ ]?[/]?>', 'g'), '<br />\n');
            //content = content.replace(new RegExp('(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)', 'img'), '');//strip Scripts
            //content = content.replace(/<\/?[^>]+>/gi, ''); //remove html
            content = this._stripScripts(content); //strip Scripts and the cmd of scripts
            content = this._stripTags(content); //remove html tags
            content = content.replace(new RegExp('&nbsp;', 'g'), ' ');//replace &nbsp; with space
            content = this._unescapeHTML(content); // change special html chars to chars
            // change enter char to  blank
            content = content.replace(new RegExp("\r\n", "g"), "\n");
            content = content.replace(new RegExp("\n", "g"), " ");
            // content = content.replace(/&([a-zA-Z0-9#]+);/g, '1');//convert entities to single character
            return content;
        },

        /**
         * Strips a string of any HTML Scripts tag.
         * input: {'a <a href="#">link</a><script>alert("hello world!")</script>'}
         * output will be:{'a link'}
         *
         * @param {String} the tinymce maintainer content which you wanna to deal
         * @return {String} the return value will without the html tags and without the content of script.
         */
        _stripScripts : function( content ) {
            return content.replace(new RegExp('(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)', 'img'), '');
        },

        /**
         * Strips a string of any HTML tag.
         * input: {'a <a href="#">link</a><script>alert("hello world!")</script>'}
         * output will be:{'a linkalert("hello world!")'}
         *
         * @param {String} the tinymce maintainer content which you wanna to deal
         * @return {String} the return value will without the html tags
         */
        _stripTags : function( content ) {
            return content.replace(/<\/?[^>]+>/gi, '');
        },

        /**
         * Strips tags and converts the entity forms of special HTML characters to their normal form.
         * input: {Pride &amp; Prejudice}
         * output will be:{'Pride & Prejudice'}
         *
         * @param {String} the tinymce maintainer content which you wanna to deal
         * @return {String} the same with the prototype.js function unescapeHTML
         */
        _unescapeHTML : function( content ) {
            var div = document.createElement('div');
            div.innerHTML = this._stripTags(content);
            //alert(this._inject(this._$A(div.childNodes), '',function(memo,node){ return memo+node.nodeValue }));
            return div.childNodes[0] ? (div.childNodes.length > 1 ?
                this._inject(this._$A(div.childNodes), '',function(memo,node){ return memo+node.nodeValue }) :
                div.childNodes[0].nodeValue) : '';
        },

        _inject : function(tANodes, memo, iterator) {
            tANodes.each(function(value, index) {
              memo = iterator(memo, value, index);
            });
            return memo;
        },

        _$A : function(iterable) {
            if (!iterable) return [];
            if (iterable.toArray) {
                return iterable.toArray();
            } else {
                var results = [];
                for (var i = 0, length = iterable.length; i < length; i++)
                    results.push(iterable[i]);
                return results;
            }
        },

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Count Words And Characters',
				author : 'leo.liuxl@gmail.com',
				authorurl : 'http://www.infinitenine.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('charcount', tinymce.plugins.CharcountPlugin);
})();