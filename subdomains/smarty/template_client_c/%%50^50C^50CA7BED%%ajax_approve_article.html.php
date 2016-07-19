<?php /* Smarty version 2.6.11, created on 2014-09-02 14:12:21
         compiled from article/ajax_approve_article.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'article/ajax_approve_article.html', 401, false),array('modifier', 'nl2br', 'article/ajax_approve_article.html', 444, false),array('modifier', 'escape', 'article/ajax_approve_article.html', 476, false),array('modifier', 'date_format', 'article/ajax_approve_article.html', 576, false),array('modifier', 'html_entity_decode', 'article/ajax_approve_article.html', 589, false),array('function', 'html_options', 'article/ajax_approve_article.html', 501, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
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
.disabled_input { border-right: #000033 1px solid; border-top: #000033 1px solid; font-size: 14px; background-color:#ffffee; border-left: #000033 1px solid; border-bottom: #000033 1px solid }
/*
.disabled_input { border-right: #000033 0px solid; border-top: #000033 0px solid; font-size: 12px; border-left: #000033 0px solid; border-bottom: #000033 0px solid; background:background-color}
*/
</style>
<script language="JavaScript">
'; ?>

<?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
<?php echo '
<!--
tinyMCE.init({
mode : "exact",
theme : "advanced",
elements : "richtext_body",
extended_valid_elements : "iframe[src|width|height|name|align]",
relative_urls : false,
remove_script_host : false,
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
plugins : "spellchecker,searchreplace,charcount,paste,wordcount,AtD,media",
theme_advanced_buttons1 : "bold,italic,underline, separator,forecolor ,separator,search,replace,separator, code, separator,AtD,charcount",
theme_advanced_buttons1_add_before: "media,image,undo,redo,paste,pastetext,pasteword,selectall,separator,hr,link,unlink,separator, formatselect, bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
theme_advanced_buttons2 :"",
theme_advanced_buttons3 : "",
paste_auto_cleanup_on_paste : true,
directionality: "ltr",
force_br_newlines : "false",
force_p_newlines : "true",
debug : false,
cleanup : true,
cleanup_on_startup : false,
safari_warning : false,
paste_preprocess : function(pl, o) {
    // Content string containing the HTML from the clipboard
    o.content = o.content;
},
paste_postprocess : function(pl, o) {
    // Content DOM node containing the DOM structure of the clipboard
    o.node.innerHTML = o.node.innerHTML;
},
setup: function(ed) {
  var text = "";
  var wordcount = false;
  var total_word = 0;
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
        alert("You have exceeded the word limit");
        check_word = total_word + 1;
    }
  });

  ed.onLoadContent.add(function(ed,e) {
    if (!wordcount) {
            wordcount = addWordCount(ed);
    }
    getEditorCount(ed);});
}
});

'; ?>

<?php endif; ?>
<?php echo '
var login_role = "';  echo $this->_tpl_vars['login_role'];  echo '";
check_f_article =  function()
{
    var f = document.f_article;
    if (login_role != \'client\')
    {
        if (f.title.value.length == 0) {
        alert(\'Please provides title of the article\');
        f.title.focus();
        return false;
        }
	    tinyMCE.triggerSave(false,false);
	    if (f.richtext_body.value.length == 0) {
            alert(\'Please enter the content of the article\');
	        f.richtext_body.focus();
	        return false;
	    }
	    f.temp_body.value = f.richtext_body.value;//firefox;
	    //tinyMCE.updateContent(\'f_article\');
    }
    else
    {
	    if (f.body.value.length == 0) {
	    alert(\'Please enter the content of the article\');
	    f.comment.focus();
	    return false;
	    }
	    f.temp_body.value = f.body.value;//firefox;
    }
    return true;
}

doAction = function(action, url)
{
    if (action != \'reject\' && action != \'temp\' && action != \'autotemp\' && action != \'approval\' && action != \'submit\' && action != \'save\' && action != \'force\' && action != \'forcec\'&& action != \'force reject\' && action != \'publish\' && action != \'1gc\' && action != \'1gd\')
    {
       alert(\'Please sign in this system\');
       return false;
    }
    $("approve_action").value = action;
    if (!check_f_article()) 
    {
	    return false;
    }
    var f = document.f_article;
    if (login_role != \'client\')
    {
        tinyMCE.triggerSave(false,false);
        f.temp_body.value = f.richtext_body.value;//firefox;
        //tinyMCE.updateContent(\'f_article\');
    }
    else
    {
        f.temp_body.value = f.body.value;//firefox;
    }
	//document.forms[\'f_article\'].submit();
//    document.getElementById(\'f_article\').submit();
    if (action == \'force\')
    {
        if (f.article_status.value == 0 && confirm(\'Will approve even if copywriter has not submitted article. Continue?\'))
        {
            $(\'f_article\').submit();
        }
        else if (f.article_status.value == 1 && confirm(\'Article has not been google-checked. Continue?\')) 
        {
            $(\'f_article\').submit();
        }
        else if (f.article_status.value != 1 && f.article_status.value != 0 && confirm("Do you want to force approve this article?"))
        {
            $(\'f_article\').submit();
        }
        else
        {
           $(\'comment\').focus();
           return false;
        }
        return true;
    }
    if (action == \'publish\')
    {
        f.article_status.value = 6;
        $(\'f_article\').submit();
    }
    if( action == \'approval\' )
    {
        if (f.article_status.value == \'1gd\')
        {
            if (!confirm("This article wasn\'t passed google checking.\\nAre you sure this article is  not duplicated?"))
            {
               $(\'comment\').focus();
               return false;
            }
        }
    }
    else
    {
        if (f.article_status.value == \'1\' && (action == \'1gc\' || action == \'1gd\')) 
        {
               $("approve_action").value = action;
              Element.show(\'show_result\');
        }
        else
        {
          if (action == \'save\')
          {
              $("approve_action").value = \'temp\';
              Element.show(\'show_result\');
          }
          if (action == \'reject\')
          {
              if (login_role != \'client\')
              {
                  f.article_status.value = 2;
              }
              else 
              {
                  f.article_status.value = 3;
              }
              Element.show(\'show_result\');
          }
        }
     }
      new Ajax.Updater
      (
          \'show_status\',
           url, 
           {
               method:\'post\',  
               parameters: Form.serialize(\'f_article\'),
               evalScripts:true,
               onComplete:showResult
           }
      );
     return true;
}

CountWords=function(this_field, show_word_count, show_char_count) 
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
  fullStr = fullStr.replace(new RegExp(\'&nbsp;\', \'g\'), \' \');//replace &nbsp; with space
  fullStr = fullStr.unescapeHTML();
  fullStr = fullStr.replace(new RegExp("\\r\\n", "gi"), "\\n");

  var char_count = fullStr.length - 1;

  fullStr += " ";
  fullStr = fullStr.replace(new RegExp("\\n", "gi"), " ");
  fullStr = fullStr.replace(/[ ]+/gi, \' \');

   fullStr = fullStr.replace(/^[^A-Za-z0-9]+/gi, "");
  var splitString   = fullStr.split(" ");
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

showResult = function()
{
	Element.hide(\'show_result\');
	Element.show(\'show_shape_end\');
}

addGeneralNote=function(note_id) {
    if (note_id != 0)
    {
        //var div_id = eval("general_note_"+note_id);
        var div_id = "general_note_"+note_id;
        var note_body = htmlSpecialCharsDecode($(div_id).innerHTML);
        var old_comment = $("comment").value;
        if (old_comment == \'\') {
            $("comment").value = note_body;
        } else {
            $("comment").value = $("comment").value+"\\n"+note_body;
        }
    }
}
htmlSpecialCharsDecode=function(str) {
    var r = new RegExp("<br[ ]*[/]?>", "ig");
    str = str.replace(new RegExp("&amp;", "gi"), "&");
    str = str.replace(new RegExp("&quot;", "gi"), "\\"");
    str = str.replace(new RegExp("&#039;", "gi"), "\\\'");
    str = str.replace(new RegExp("&lt;", "gi"), "<");
    str = str.replace(new RegExp("&gt;", "gi"), ">");

    str = str.replace("\\r\\n", "\\n");
    str = str.replace(r, "\\n");
    str = str.replace(new RegExp("\\n+", "g"), "\\n");
    return str;
}
//-->
</script>
'; ?>

<div class="loadingajax" style="display:none;z-index:1000" id="show_result"  align="center" valign="center" >Loading...</div>
<div id="page-box1" >
  <strong>Once you've reviewed the article and made comments click the Reject or the approve button to notify your editorial team.</strong> <br /><br />
  <div class="pageposition" >
  <table width="100%"  class="formClass" ><tr><td valign="top">
  <div id="page-left">
  <form action="#" method="post"  name="f_article" id="f_article" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_article()"<?php endif; ?>>
    <input type="hidden" name="refererby" value="<?php echo $this->_tpl_vars['refererby']; ?>
" />
    <input type="hidden" name="max_word" id="max_word" value="<?php echo $this->_tpl_vars['keyword_info']['max_word']; ?>
" />
    <input type="hidden" name="keyword_id" value="<?php echo $this->_tpl_vars['keyword_info']['keyword_id']; ?>
" />
    <input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
" />
    <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['keyword_info']['client_id']; ?>
" />
    <input type="hidden" name="article_id" value="<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
" />
    <input type="hidden" name="article_status" id="article_status" value="<?php echo $this->_tpl_vars['keyword_info']['article_status']; ?>
" />
    <input type="hidden" name="approve_action" id="approve_action" value="temp" />
    <input type="hidden" name="is_edit" id="is_edit" value="cancle_edit" />
    <input type="hidden" name="is_paintext" id="is_paintext" value="0" />
    <input type="hidden" name="action_status" id="action_status" value="<?php echo $this->_tpl_vars['keyword_info']['action_status']; ?>
" />
    <input type="hidden" name="language" id="language" value="<?php echo $this->_tpl_vars['keyword_info']['language']; ?>
" />
    <input type="hidden" name="temp_body" id="temp_body">
    <?php if ($this->_tpl_vars['login_role'] == 'client'): ?>
    <input type="hidden" name="opt" id="opt" value="<?php echo $this->_tpl_vars['opt']; ?>
" />
    <?php endif; ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Article Title</strong></td>
      <td align="left">    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <?php if ($this->_tpl_vars['keyword_info']['title_param'] == '1'): ?>
    <input type="text" name="title" id="title" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['title']; ?>
" />
      <?php else: ?>
    <input type="text" name="title" id="title" size="100" value="<?php if ($this->_tpl_vars['keyword_info']['title'] != ''):  echo $this->_tpl_vars['keyword_info']['title'];  else:  echo $this->_tpl_vars['keyword_info']['keyword'];  endif; ?>"/>
      <?php endif; ?>
    <?php else: ?>
    <?php echo $this->_tpl_vars['keyword_info']['title']; ?>

    <input type="hidden" name="title" id="title" value="<?php echo $this->_tpl_vars['keyword_info']['title']; ?>
" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['is_show_extra_info']): ?>
      <a href="javascript:void(0)" onclick="openWindow('/article/article_extra_info.php?cid=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
&article_id=<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
', 'height=700,width=720,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" >Add Additional Meta Info</a>
    <?php endif; ?></td>
      </tr>
    </tbody></table> 
  </div>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Html Title Tag</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['keyword_info']['title_param'] == '1'): ?>
      <input type="text" name="html_title" id="html_title" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['html_title']; ?>
" />
      <?php else: ?>
      <input type="text" name="html_title" id="html_title" size="100" value="<?php if ($this->_tpl_vars['keyword_info']['html_title'] != ''):  echo $this->_tpl_vars['keyword_info']['html_title'];  else:  echo $this->_tpl_vars['keyword_info']['keyword'];  endif; ?>" readonly />
      <?php endif; ?></td>
      </tr>
    </tbody></table> 
  </div>
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
  <?php if ($this->_tpl_vars['is_show_url_category']): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Url Category</strong></td>
      <td align="left"><input type="text" name="url_category" id="url_category" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['url_category']; ?>
" /></td>
      </tr>
    </tbody></table> 
  </div>
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
      <td align="left"><textarea name="description_meta" id="description_meta" style="width: 550px; height: 60px;" ><?php echo $this->_tpl_vars['keyword_info']['description_meta']; ?>
</textarea></td>
      </tr>
    </tbody></table> 
  </div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Article Content</strong></td>
      <td align="left">
      <div id="contentdiv" >
      <textarea name="richtext_body" id="richtext_body" style="width: 700px; height: 400px;" ><?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['keyword_info']['richtext_body'];  endif; ?></textarea>
      </div>
      </td>
      </tr>
    </tbody></table> 
  </div>
  <?php else: ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Word Count</strong></td>
      <td align="left"><input type="text" class="disabled_input" name="word_count" id="word_count" value="0" size="90"   onkeyup="CountWords($('body'), true, true);" readOnly  class="disabled_input"/></td>
      </tr>
    </tbody></table> 
  </div>
  <?php if ($this->_tpl_vars['keyword_info']['tags']): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"  style="width:50px" ><strong>Tags</strong></td>
      <td align="left"><?php echo $this->_tpl_vars['keyword_info']['tags']; ?>
</td>
      </tr>
    </tbody></table> 
  </div>
  <?php endif; ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Article Content</strong></td>
      <td align="left">
        <div id="div_body_test" ></div>
        <input type="hidden" name="richtext_body" id="richtext_body"
        value="<?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['richtext_body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>" />
        <input type="hidden" name="body" id="body" value="<?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['richtext_body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>" />
        <div style="width: 700px; height: 400px;overflow:auto;color:#000000;background:#ffffff;"><?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['keyword_info']['richtext_body'];  endif; ?></div></td>
      </tr>
    </tbody></table> 
  </div>
  <script>
  <!--
  CountWords($('body'), true, true);
  -->
  </script>
  <?php endif; ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Comments</strong></td>
      <td align="left"><textarea name="comment" style="width: 700px; height: 160px;" id="comment" id="comment"><?php echo $_POST['comment']; ?>
</textarea></td>
      </tr>
    </tbody></table> 
  </div>
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="80%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Select General Editorial Notes</strong></td>
      <td align="left"><select id="general_note_subject" name="general_note_subject" onchange="addGeneralNote(this.value)"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['general_note_subjects']), $this);?>
</select></td>
      </tr>
    </tbody></table> 
  </div>
    <?php if ($this->_tpl_vars['general_note_bodies'] != ''): ?>
      <?php $_from = $this->_tpl_vars['general_note_bodies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['general_notes'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['general_notes']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['note_id'] => $this->_tpl_vars['note_body']):
        $this->_foreach['general_notes']['iteration']++;
?>
        <div style="display:none" id="general_note_<?php echo $this->_tpl_vars['note_id']; ?>
" name="general_note_<?php echo $this->_tpl_vars['note_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['note_body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
      <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['keyword_info']['body'] != ''): ?>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
    <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
    <div id="form-buttons">
      <div id="div_button" >
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <?php if ($this->_tpl_vars['login_role'] == 'copy writer'): ?>
        <input type="button" value="Submit" class="button" onclick="doAction('submit' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '1'): ?>
        <input type="button" value="Google Clean" class="button" onclick="doAction('1gc' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <input type="button" value="Google Duplicated" class="button" onclick="doAction('1gd' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <?php if ($this->_tpl_vars['login_role'] == 'admin' && $this->_tpl_vars['keyword_info']['richtext_body'] != ''): ?>
        <input type="button" value="Force Approve" class="button" onclick="doAction('force' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <?php endif; ?>
      <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '4'): ?>
        <input type="button" value="Force Request Edit" class="button" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
        <input type="button" value="Force Client Approve" class="button" onclick="doAction('forcec' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <?php endif; ?>
      <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '2' || $this->_tpl_vars['keyword_info']['article_status'] == '1gd'): ?>
        <input type="button" value="Force Approve" class="button" onclick="doAction('force' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <input type="button" value="Request Edit" class="button" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <?php else: ?>
        <input type="button" value="Request Edit" class="button" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <input type="button" value="Approve" class="button" onclick="doAction('approval' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <?php endif; ?>
    <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '4' && $this->_tpl_vars['login_role'] == 'client'): ?>
      <input type="button" value="Reject" class="button" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <input type="button" value="Approve" class="button" onclick="doAction('approval' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
    <?php endif; ?>
    <input type="reset" value="Reset" class="button" />
      </div>
    </div>
    <div id="show_shape_end" class="corner" style="display:none;width:310px;z-index:1000;height: 30px;" >
      <div class="ricohint" style="width:310px;z-index:1000;" id="show_status"  align="center" >saving...</div>
    </div>
    </form>
  </div>
<?php if ($this->_tpl_vars['tags']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "article/article_tags.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
  </td><td valign="top" >
  <div id="page-right" >
    <div class="small-note" >
       <a href="javascript:void(0);" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Content Production Style Guide</a><br />
    </div>
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
<table cellspacing="0" cellpadding="0" border="0" width="283" class="info-table">
  <tbody><tr>
    <td height="41" background="/images/info-table-top.gif" align="center">Article Information </td>
  </tr>
  <tr>
    <td><table class="article-info" >
      
      <tbody><tr class="even">
        <td>Campaign Name</td>
        <td><?php echo $this->_tpl_vars['keyword_info']['campaign_name']; ?>
</td>
      </tr>
      <tr class="odd" >
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
        <td><div id="max-word-td" ><?php if ($this->_tpl_vars['keyword_info']['max_word'] == 0): ?>No limit<?php else:  echo $this->_tpl_vars['keyword_info']['max_word'];  endif; ?></div></td>
      </tr>
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
        <td ><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['mapping_id'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
      </tr>
        <?php endif; ?>
      <?php endif; ?>
    </tbody></table></td>
  </tr>
  <tr>
    <td><img alt="" src="/images/info-table-bottom.gif"></td>
  </tr>
</tbody></table>
<?php endif; ?>
    <div class="small-note" >
      Keyword Instructions: <br />
       <a href="javascript:void(0);" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Content Production Style Guide</a><br />
        <?php if ($this->_tpl_vars['login_role'] != 'client'): ?><a href="javascript:void(0);" onclick="openWindow('/client_campaign/campaign_notes.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=400,width=550,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Editorial notes</a><?php endif; ?><br />
      <?php if ($this->_tpl_vars['keyword_info']['keyword_description'] != ''): ?>
      <b>Note From <?php echo $this->_tpl_vars['keyword_info']['pm_name']; ?>
:</b><br />
      <div  style="height:150px;width:280px;overflow:auto;text-align:left;" >
      <?php echo $this->_tpl_vars['keyword_info']['keyword_description']; ?>

      </div>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['notes']['notes'] != ''): ?>
       Editor Notes <br />
       <div style="height:150px;width:280px;overflow:auto;text-align:left;">
        <b>Note From <?php echo $this->_tpl_vars['notes']['user_name']; ?>
:</b><br /><?php echo $this->_tpl_vars['notes']['notes']; ?>

       </div>
      <?php endif; ?>
    </div>
  </div>
  </td></tr></table>
  <div style="clear: both;"></div>
  </div>
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
    <td><?php if ($this->_tpl_vars['item']['creation_role'] == 'client'):  echo $this->_tpl_vars['item']['ccreator'];  else:  echo $this->_tpl_vars['item']['creator'];  endif; ?></td>
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
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>