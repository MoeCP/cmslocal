<?php /* Smarty version 2.6.11, created on 2014-06-26 07:11:29
         compiled from article/article_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/article_form.html', 320, false),array('modifier', 'default', 'article/article_form.html', 373, false),array('modifier', 'nl2br', 'article/article_form.html', 405, false),array('modifier', 'date_format', 'article/article_form.html', 531, false),array('modifier', 'html_entity_decode', 'article/article_form.html', 547, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<link rel="stylesheet" type="text/css" href="spell_checker/spell_checker/css/spell_checker.css">
<script src="spell_checker/spell_checker/cpaint/cpaint2.inc.compressed.js" type="text/javascript"></script>
<!-- You can use either one of the files below, but the compressed one
     will be faster and a lot smaller to download -->
<script src="spell_checker/spell_checker/js/spell_checker_compressed.js" type="text/javascript"></script>
<!--<script src="js/spell_checker.js" type="text/javascript"></script>-->

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>

<?php echo '
<style type="text/css">
.disabled_input { border-right: #000033 0px solid; border-top: #000033 0px solid; font-size: 12px; border-left: #000033 0px solid; border-bottom: #000033 0px solid; background:#ffffff}
</style>
'; ?>


<script language="JavaScript">
<!--
<?php echo '
//var WSCCorePath = "/js/tiny_mce/plugins/wsc/sproxy/sproxy.php?cmd=script&doc=wsc&plugin=tinymce3";
tinyMCE.init({
mode : "exact",
theme : "advanced",
elements : "richtext_body",
extended_valid_elements : "iframe[src|width|height|name|align]",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
atd_button_url: "/js/tiny_mce/plugins/AtD/atdbuttontr.gif",
atd_rpc_url: "/js/tiny_mce/plugins/AtD/server/proxy.php?url=",
atd_rpc_id: '; ?>
"<?php echo $this->_tpl_vars['atd_key']; ?>
"<?php echo ',
atd_css_url: "/js/tiny_mce/plugins/AtD/css/content.css",
atd_show_types: "Bias Language,Cliches,Complex Expression,Diacritical Marks,Double Negatives,Hidden Verbs,Jargon Language,Passive voice,Phrases to Avoid,Redundant Expression",
atd_ignore_strings: "AtD,rsmudge",
atd_ignore_enable: "false",
//wsc_popup_title :"Custom Popup Title",
//wsc_lang :"en_US",
//plugins : "spellchecker,searchreplace,charcount,paste,charcount,advimage,AtD,media,wsc,inlinepopups",
plugins : "spellchecker,searchreplace,charcount,paste,charcount,advimage,AtD,media",
//theme_advanced_buttons1 : "bold,italic,underline, separator,forecolor ,separator,search,replace,code, separator,wsc,separator, AtD,charcount",
theme_advanced_buttons1 : "bold,italic,underline, separator,forecolor ,separator,search,replace,code, separator, AtD,charcount",
theme_advanced_buttons1_add_before: "media,image,undo,redo,paste,pastetext,pasteword,selectall,separator,hr,link,unlink,separator, formatselect, bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
theme_advanced_buttons2 :"",
theme_advanced_buttons3 : "",
paste_auto_cleanup_on_paste : true,
relative_urls : false,
remove_script_host : false,
paste_preprocess : function(pl, o) {
    // Content string containing the HTML from the clipboard
    o.content =  o.content;
},
paste_postprocess : function(pl, o) {
    // Content DOM node containing the DOM structure of the clipboard
    o.node.innerHTML = o.node.innerHTML;
},
setup: function(ed) {
  var text = "";
  var wordcount = false;
  var max_word = $(\'max_word\').value;
  var check_word = parseInt(max_word);
  if (isNaN(check_word)) {
    check_word = 0;
  }
  ed.onKeyUp.add(function(ed, e) {
    if (!wordcount) {
      wordcount = addWordCount(ed);
    }
    total_word = getEditorCount(ed);
    if (total_word < max_word && check_word > max_word || total_word + 1< check_word) {
       check_word = max_word;
    }

    if (check_word > 0 && total_word > 0&& (total_word - check_word) >= 50) {
        //alert("You have exceeded the word limit");
        check_word = total_word + 1;
    }
  });
  ed.onLoadContent.add(function(ed,e) {
    if (!wordcount) {
            wordcount = addWordCount(ed);
    }
      total_word = getEditorCount(ed);
    });
}
});
var is_spell_check = false;
function save_f_article(action)
{
  if (action == \'submit\' && !isObjectOrNot($(\'myConfirmDialog\')))  {
    Dialog.confirm("Confirm that no changes were made or needed after the Final Proofread Check.", {width:300, okLabel: "Yes", cancelLabel:\'No\', id:"myConfirmDialog", buttonClass: "myConfirmButton", className: "mac_os_x", cancel:function(win) { tinyMCE.activeEditor.execCommand(\'mceWritingImprovementTool\');return false;}, ok:function(win) {check_f_article(action); return true;} });
    return false;
  } else {
    return check_f_article(action);
  }

}
function check_f_article(action)
{
  var f = document.f_article;
  if (f.language.value.length == 0 && action != \'autotemp\') {
    alert(\'Please choose language of the article\');
    f.language.focus();
    return false;
  }
  if (f.title.value.length == 0 && action != \'autotemp\') {
    alert(\'Please provide title of the article\');
    f.title.focus();
    return false;
  }
  if (action == \'submit\')
  {
'; ?>

<?php echo $this->_tpl_vars['jsCode'][0]; ?>

<?php echo '
  }
  tinyMCE.triggerSave(false,false);
  if (f.richtext_body.value.length == 0) {
    if (action == \'autotemp\') {
      return false;
    }
    alert(\'Please enter the content of the article\');
    f.richtext_body.focus();
    return false;
  }
  if (action == \'submit\')
  {
 '; ?>

 <?php if ($this->_tpl_vars['jsCode'][1]): ?>
  content = getTextFromHtml(f.richtext_body.value);
  var len  = content.length;
 <?php echo '
  if (len) {
'; ?>

  <?php echo $this->_tpl_vars['jsCode'][1]; ?>

<?php echo '
  }
 '; ?>

 <?php endif; ?>
 <?php echo '
 }
  //alert(f.body.value);
  f.temp_body.value = f.richtext_body.value;//firefox;
  return true;
}

function submitArticle()
{
  var f = document.f_article;	
  f.action.value = \'submit\';
  f.article_status.value = \'1\';
  if (check_f_article(f.action.value))
  {
    /*if (isObjectOrNot($(\'myConfirmDialog\')) {
      $(\'myConfirmDialog\').remove();
    }*/
    if (!isObjectOrNot($(\'myConfirmDialog\')))  {
      Dialog.confirm("Confirm that no changes were made or needed after the Final Proofread Check?", {width:300, okLabel: "Yes", cancelLabel:\'No\', id:"myConfirmDialog", buttonClass: "myConfirmButton", className: "mac_os_x", cancel:function(win) {tinyMCE.activeEditor.execCommand(\'mceWritingImprovementTool\');return false;}, ok:function(win) {f.submit(); return true;} });
    } else {
      f.submit();
    }
  }

}

function saveTimmer()
{
  setTimeout("doAction(\'autotemp\',\'';  echo $this->_tpl_vars['url'];  echo '\')", 300000);//1000 = 1 second
}
'; ?>

<?php if ($this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6' && $this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
saveTimmer();
<?php endif;  echo '
function doAction(action, url)
{
    var f = document.f_article;
    
    tinyMCE.triggerSave(false,false);
    var post_string = "";
    if (action != \'temp\' && action != \'save\' && action != \'autotemp\') {
       alert(\'Please sign in this system\');
	  
       return false;
    }
    if (!check_f_article(action)) {
    //tinyMCE.updateContent(f_article);
      saveTimmer();
	    return false;
    }
    
    var f = document.f_article;

    f.action.value = action;
    f.temp_body.value = f.richtext_body.value;//firefox;
	//form = document.getElementById("f_article");
	//addEvent(form, "submit", submit);
	//document.forms["f_article"].submit();
    /*START: Added AT 14:59 2006-8-10*/
    if (action == \'save\') {
        f.action.value = \'temp\';
        Element.show(\'show_result\');
    }
     new Ajax.Updater
    (
        \'show_status\',
         url, 
         {
             method:\'post\',  
             parameters: Form.serialize(\'f_article\'),
             onComplete:showResult
         }
    );

	saveTimmer();
	//tinyMCE.updateContent(\'f_article\');
     /*END ADDED*/
//	document.getElementById(\'f_article\').submit();
}
function showResult()
{
	Element.hide(\'show_result\');
	Element.show(\'show_shape_end\');
}

function CountWords (this_field, show_word_count, show_char_count) 
{

  if (show_word_count == null) {
    show_word_count = true;
  }

  if (show_char_count == null) {
    show_char_count = false;
  }
  
  var fullStr = this_field.value;
  
  fullStr = fullStr.replace(new RegExp(\'</p>\', \'gi\'), \'</p>\\n\');
	fullStr = fullStr.replace(new RegExp(\'</div>\', \'gi\'), \'</div>\\n\');
	fullStr = fullStr.replace(new RegExp(\'<br[ ]?[/]?>\', \'gi\'), \'<br />\\n\');
  

  fullStr = fullStr.stripTags();
  fullStr = fullStr.unescapeHTML();
  fullStr = fullStr.replace("\\r\\n", "\\n", \'gi\');

  var char_count = fullStr.length - 1;
  

  fullStr += " ";
  fullStr = fullStr.replace("\\n", " ", \'gi\');
  fullStr = fullStr.replace(/[ ]+/gi, \' \');

  fullStr = fullStr.replace(/^[^A-Za-z0-9]+/gi, "");
  var splitString = fullStr.split(" ");
  var word_count = splitString.length - 1;
  
  if (fullStr.length < 2) {
    word_count = 0;
  }

  if (word_count == 1) {
    wordOrWords = " word";
  } else {
    wordOrWords = " words";
  }

  if (char_count == 1) {
    charOrChars = " character";
  } else {
    charOrChars = " characters";
  }

  if (show_word_count & show_char_count) {
       $(\'word_count\').value=word_count + wordOrWords  + " " + char_count + charOrChars;
  } else {

    if (show_word_count) {
        $(\'word_count\').value=word_count + wordOrWords;
    } else {
      if (show_char_count) {
        $(\'word_count\').value=char_count + charOrChars;
      }
    }
  }
  //return word_count;
}

'; ?>

//-->
</script>
<div id="div_test" ></div>
<div class="loadingajax" style="display:none;z-index:1000" id="show_result"  align="center" valign="center" >Loading...</div>
<div id="page-box1">
  <h2>Add a new article according to keyword</h2>
  <div id="campaign-search" >
    <strong>Please remember to spell-check your article before submitting</strong>
  </div>
  <div class="pageposition" >
  <table width="100%"  class="formClass" ><tr><td valign="top">
<form action="#" method="post" name="f_article" id="f_article" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_article('submit');"<?php endif; ?>>
<input type="hidden" name="max_word" id="max_word" value="<?php echo $this->_tpl_vars['keyword_info']['max_word']; ?>
" />
<input type="hidden" name="pay_type" id="pay_type" value="<?php echo $this->_tpl_vars['keyword_info']['pay_type']; ?>
" />
<input type="hidden" name="client_id" id="max_word" value="<?php echo $this->_tpl_vars['keyword_info']['client_id']; ?>
" />
<input type="hidden" name="keyword_id" id="keyword_id" value="<?php echo $this->_tpl_vars['keyword_info']['keyword_id']; ?>
">
<input type="hidden" name="article_id" id="article_id"  value="<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
">
<input type="hidden" name="article_status" id="article_status" value="<?php echo $this->_tpl_vars['keyword_info']['article_status']; ?>
">
<input type="hidden" name="action" id="action" value="submit" />  
  <div class="form-item">
    <table cellspacing="0" cellpadding="5" border="0" width="80%" valign="top" >
      <tbody><tr>
      <td align="right" class="form-label"><strong>Language</strong></td>
      <td align="left" ><select name="language">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['languages'],'selected' => $this->_tpl_vars['keyword_info']['language']), $this);?>

    </select></td>
      </tr>
    </tbody></table> 
  </div>
  <?php if ($this->_tpl_vars['keyword_info']['title_param'] == '1'): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Article Title</strong></td>
      <td align="left"><input type="text" name="title" id="title" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['title']; ?>
" /></td>
      </tr>
    </tbody></table> 
  </div>   
  <input type="hidden" name="html_title" id="html_title" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['html_title']; ?>
" />
    <?php else: ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Article Title</strong></td>
      <td align="left"><input type="text" name="title" id="title" size="100" value="<?php if ($this->_tpl_vars['keyword_info']['title'] != ''):  echo $this->_tpl_vars['keyword_info']['title'];  else:  echo $this->_tpl_vars['keyword_info']['keyword'];  endif; ?>" readonly/></td>
      </tr>
    </tbody></table> 
  </div>  
   <input type="hidden" name="html_title" id="html_title" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['html_title']; ?>
" /> 
    <?php endif; ?>
  <?php if ($this->_tpl_vars['custom_fields']): ?>
  <?php $_from = $this->_tpl_vars['custom_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" class="form-label"><strong><?php echo $this->_tpl_vars['item']['label']; ?>
:&nbsp;</strong></td>
      <td align="left">
       <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
       <input type="text" name="<?php echo $this->_tpl_vars['key']; ?>
" id="<?php echo $this->_tpl_vars['key']; ?>
" size="100" value="<?php echo $this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']]; ?>
" />
       <?php else: ?>
       <input type="hidden" name="<?php echo $this->_tpl_vars['key']; ?>
" id="<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']]; ?>
" />
       <?php echo ((is_array($_tmp=@$this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>

       <?php endif; ?>
      </td>
      </tr>
    </tbody></table> 
  </div>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['keyword_info']['meta_param'] == '1'): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Meta Keywords</strong></td>
      <td align="left"><input type="text" name="keyword_meta" id="keyword_meta" value="<?php echo $this->_tpl_vars['keyword_info']['keyword_meta']; ?>
" size="100"/></td>
      </tr>
    </tbody></table> 
  </div> 
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Meta Description</strong></td>
      <td align="left"><textarea name="description_meta" id="description_meta" style="width: 550px; height: 60px;" id="comment" id="comment"><?php echo $this->_tpl_vars['keyword_info']['description_meta']; ?>
</textarea></td>
      </tr>
    </tbody></table> 
  </div> 
  <?php endif; ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Article Content</strong></td>
      <td align="left">
      <div id="contentdiv" >
    <textarea name="richtext_body" id="richtext_body" style="width: 700px; height: 400px;" ><?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['keyword_info']['richtext_body'];  endif; ?></textarea>
        <input type="hidden" name="temp_body" id="temp_body">	</div></td>
      </tr>
    </tbody></table> 
  </div> 
  <?php if ($this->_tpl_vars['keyword_info']['template'] == '2'): ?>
  <div class="form-item" >
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" valign="top" class="form-label"><strong>Small Image</strong></td>
      <td align="left"><input type="text" size="100"  name="small_image" id="small_image" 
        value="<?php echo $this->_tpl_vars['keyword_info']['small_image']; ?>
" /></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Large Image</strong></td>
      <td align="left"><input type="text" size="100" name="large_image" id="large_image" 
        value="<?php echo $this->_tpl_vars['keyword_info']['large_image']; ?>
" /></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Image Credit</strong></td>
      <td align="left"><input type="text" size="100"  name="image_credit" id="image_credit" 
        value="<?php echo $this->_tpl_vars['keyword_info']['image_credit']; ?>
" /></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Image Caption</strong></td>
      <td align="left"><input type="text" size="100"  name="image_caption" id="image_caption" 
        value="<?php echo $this->_tpl_vars['keyword_info']['image_caption']; ?>
" /></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Blurb</strong></td>
      <td align="left"><textarea name="blurb" style="width: 700px; height: 160px;"  id="blurb"><?php echo $this->_tpl_vars['keyword_info']['blurb']; ?>
</textarea></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Meta description</strong></td>
      <td align="left"><input type="text" size="100" name="meta_description" id="meta_description" 
        value="<?php echo $this->_tpl_vars['keyword_info']['meta_description']; ?>
" /></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Category</strong></td>
      <td align="left"><select id="category_id" name="category_id" ><option value="" >[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_categories'],'selected' => $this->_tpl_vars['keyword_info']['category_id']), $this);?>
</select></td>
      </tr>
    </tbody></table> 
  </div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['keyword_info']['show_cp_bio'] == '1'): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" class="form-label" valign="top"><strong>Author Bio</strong></td>
      <td align="left"><textarea name="cp_bio" style="width: 700px; height: 160px;" id="cp_bio" id="cp_bio"><?php echo $this->_tpl_vars['keyword_info']['cp_bio']; ?>
</textarea></td>
      </tr>
    </tbody></table> 
  </div>
  <script language="JavaScript">tinyMCEInit('cp_bio')</script>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['keyword_info']['template'] == '3' && $this->_tpl_vars['login_role'] != 'client'): ?>
  <div class="form-item" >
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody>
	        <tr>
      <td align="right" valign="top" class="form-label"><strong>Blurb</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><textarea name="blurb" style="width: 700px; height: 160px;"  id="blurb"><?php echo $this->_tpl_vars['keyword_info']['blurb']; ?>
</textarea><?php else:  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['blurb'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  endif; ?></td>
      </tr>
    </tbody></table> 
  </div>
  <?php endif; ?>

 <?php if ($this->_tpl_vars['tags']): ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "article/article_tags.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php endif; ?>
  <?php if ($this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6'): ?>
  <div id="form-buttons">
  <table cellspacing="0" cellpadding="5" border="0" width="100%">
    <tr><td colspan="2" ><span style="font-size: 12px;" ><strong>Important Reminder:&nbsp;</strong>Continue to Proofread Check the content until no changes are made or required. Any changes made after a Proofread Check will not be reflected by the initial check. </span></td></tr>
    <tr>
      <td align="left" >
      <?php if ($this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
        <input type="button"  class="button" value="Save" onclick="doAction('save', '<?php echo $this->_tpl_vars['url']; ?>
');" />&nbsp;<input type="button" value="Submit" class="button" onclick="submitArticle()" />&nbsp;<input type="reset" value="reset" class="button" />
      <?php endif; ?>
      </td>
      <td align="right" >
      <?php if ($this->_tpl_vars['custom_fields']['custom_field1']): ?>
        <?php if ($this->_tpl_vars['keyword_info']['article_status'] != '99' && $this->_tpl_vars['keyword_info']['article_status'] != '4'): ?>
        <input type="button" value="Not Available" class="button" onclick="InAvailableArticle('f_article')" />
        <?php else: ?>
        <input type="button" value="Back On" class="button" onclick="AvailableArticle('f_article')" />
        <?php endif; ?>
      <?php endif; ?>
      </td>
    </tr>
  </table>
  </div>
  <?php endif; ?>
<div id="show_shape_end" class="corner" style="display:none;width:310px;z-index:1000;height: 30px;" >
			<div class="ricohint" style="width:310px;z-index:1000;" id="show_status"  align="center" >saving...</div>
		</div>
</form>
  </td><td valign="top" >
    <div class="small-note" >
       <a href="javascript:void(0);" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Content Production Style Guide</a><br />
    </div>
<table cellspacing="0" cellpadding="0" border="0" width="283" class="info-table" valign="top" >
  <tbody><tr>
    <td height="41" background="/images/info-table-top.gif" align="center">Article Information </td>
  </tr>
  <tr>
    <td><table class="article-info">
      
      <tbody><tr class="even" >
        <td >Campaign Name</td>
        <td><?php echo $this->_tpl_vars['keyword_info']['campaign_name']; ?>
</td>
      </tr>
      <tr class="odd">
        <td >Campaign Keywords</td>
        <td ><?php echo $this->_tpl_vars['keyword_info']['keyword']; ?>
</td>
      </tr>
      <tr class="even">
        <td>Start Date</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
      </tr>
      <tr class="odd">
        <td >Due Date</td>
        <td ><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
      </tr>
      <tr class="even">
        <td>No. of Words</td>
        <td><?php if ($this->_tpl_vars['keyword_info']['max_word'] == '0'): ?>No limit<?php else:  echo $this->_tpl_vars['keyword_info']['max_word'];  endif; ?></td>
      </tr>
      <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <tr ><td colspan="2" align="center"><a href="/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
" target="_blank" >View Full Style Guide</a></td></tr>
      <?php endif; ?>
      <?php $_from = $this->_tpl_vars['optional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
      <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
        <td><?php echo $this->_tpl_vars['item']['label']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
      <tr class="odd">
        <td>Article Type</td>
        <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['keyword_info']['article_type']]; ?>
</td>
      </tr>
      <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <tr class="even">
        <td >Editor</td>
        <td ><?php echo $this->_tpl_vars['keyword_info']['ue_name']; ?>
</td>
      </tr>
      <tr class="odd">
        <td>Copywriter</td>
        <td><?php echo $this->_tpl_vars['keyword_info']['uc_name']; ?>
</td>
      </tr>
        <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
      <tr class="even">
        <td >Mapping-ID</td>
        <td ><?php echo $this->_tpl_vars['keyword_info']['mapping_id']; ?>
</td>
      </tr>
        <?php endif; ?>
      <?php endif; ?>
    </tbody></table>
    </td>
  </tr>
  <tr>
    <td><img alt="" src="/images/info-table-bottom.gif"></td>
  </tr>
</tbody></table>
    <div class="small-note" >
      <div>Keyword Instructions:</div>
      <div>
      <?php if ($this->_tpl_vars['keyword_info']['keyword_description'] != ''): ?>
      <div class="divContent" >
      <b>Note From <?php echo $this->_tpl_vars['keyword_info']['pm_name']; ?>
:</b><br />
      <?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['keyword_description'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>

      </div>
      <?php endif; ?>
       <a href="javascript:void(0);" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Content Production Style Guide</a><br />
        <a href="javascript:void(0);" onclick="openWindow('/client_campaign/campaign_notes.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=400,width=550,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Editorial notes</a></div>
      <?php if ($this->_tpl_vars['notes']['notes'] != ''): ?>
       <div class="requiredInput2" nowrap>Editor Notes</div>
       <div style="height:80px;width:450px;overflow:auto;text-align:left;">
        <b>Note From <?php echo $this->_tpl_vars['notes']['user_name']; ?>
:</b><br /><?php echo $this->_tpl_vars['notes']['notes']; ?>

       </div>
      <?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6' && $this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "article/zemantawidget.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </div>
  </td></tr></table>
  </div>
<div id="article-coments" >
<?php if ($this->_tpl_vars['comment_count'] != 0): ?>
<table border="0" cellspacing="0" cellpadding="0" width="85%" class="comments-info" >
  <tr class="comments-head" >
    <td class="comments-head-left">&nbsp;</td>
    <td  colspan="8" ><span class="comments-header">Current Articles Comments Information</span></td>
    <td class="comments-head-right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" >
  <?php $_from = $this->_tpl_vars['keyword_info']['comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
     <table cellspacing="0" cellpadding="10" bordercolor="#999999" border="1" width="100%">
   <tr>
    <td>
    <table cellspacing="0" width="100%">
  <tr>
    <td></td>
    <td align="right" class="comments-label">Role: &nbsp;</td>
    <td><?php echo $this->_tpl_vars['item']['creation_role']; ?>
</td>
    <td align="right" class="comments-label">Creator: &nbsp;</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client' || $this->_tpl_vars['login_role'] == 'client' && $this->_tpl_vars['item']['creation_role'] != 'editor' && $this->_tpl_vars['item']['creation_role'] != 'copy writer'): ?>
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
    <?php else: ?>
    <td><?php echo $this->_tpl_vars['item']['creation_role']; ?>
</td>
    <?php endif; ?>
    <td align="right" class="comments-label">Comment Date: &nbsp; </td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
</td>
    <td align="right" class="comments-label">Version: &nbsp;</td>
    <td><?php echo $this->_tpl_vars['item']['version_number']; ?>
</td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="10" ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['comment'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td></td>
  </tr>
    </table>
     </td>
   </tr>
    </table>
  <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
</table>
<?php endif; ?>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>