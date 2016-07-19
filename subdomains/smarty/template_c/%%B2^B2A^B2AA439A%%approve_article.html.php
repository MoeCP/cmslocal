<?php /* Smarty version 2.6.11, created on 2015-12-09 13:09:57
         compiled from article/approve_article.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'article/approve_article.html', 503, false),array('modifier', 'nl2br', 'article/approve_article.html', 546, false),array('modifier', 'escape', 'article/approve_article.html', 580, false),array('modifier', 'date_format', 'article/approve_article.html', 825, false),array('modifier', 'html_entity_decode', 'article/approve_article.html', 969, false),array('function', 'html_options', 'article/approve_article.html', 710, false),)), $this); ?>
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
<script language="JavaScript" type="text/javascript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif;  echo '
<style type="text/css">
.disabled_input { border-right: #000033 1px solid; border-top: #000033 1px solid; font-size: 14px; background-color:#ffffee; border-left: #000033 1px solid; border-bottom: #000033 1px solid }
/*
.disabled_input { border-right: #000033 0px solid; border-top: #000033 0px solid; font-size: 12px; border-left: #000033 0px solid; border-bottom: #000033 0px solid; background:background-color}
*/
</style>
<script language="JavaScript"  type="text/javascript">
'; ?>

<?php if ($this->_tpl_vars['login_role'] != 'client' || 1):  echo '
<!--
// var WSCCorePath = "/js/tiny_mce/plugins/wsc/sproxy/sproxy.php?cmd=script&doc=wsc&plugin=tinymce3";
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
//wsc_popup_title :"Custom Popup Title",
//wsc_lang :"en_US",
//plugins : "spellchecker,searchreplace,charcount,paste,wordcount,advimage,AtD,media,wsc,inlinepopups",
//theme_advanced_buttons1 : "bold,italic,underline, separator,forecolor ,separator,search,replace,separator, code, separator, wsc, separator,AtD,charcount",
plugins : "spellchecker,searchreplace,charcount,paste,wordcount,advimage,AtD,media",
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
        //alert("You have exceeded the word limit");
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

<?php endif;  echo '
var login_role = "';  echo $this->_tpl_vars['login_role'];  echo '";
function check_f_article()
{
    var f = document.f_article;
    if (login_role != \'client\')
    {
        if (f.title.value.length == 0 && f.approve_action.value != \'autotemp\') {
          alert(\'Please provide title of the article\');
          f.title.focus();
          return false;
        }
        if (f.approve_action.value != \'autotemp\')
        {
        '; ?>

        <?php echo $this->_tpl_vars['jsCode'][0]; ?>

        <?php echo '
        }
	    tinyMCE.triggerSave(false,false);
	    if (f.richtext_body.value.length == 0 && f.approve_action.value != \'autotemp\') {
            alert(\'Please enter the content of the article\');
	        f.richtext_body.focus();
	        return false;
	    }
      '; ?>

      <?php if ($this->_tpl_vars['jsCode'][1]): ?>
      <?php echo '
      content = getTextFromHtml(f.richtext_body.value);
      var len  = content.length;
      if (len && f.approve_action.value != \'autotemp\') {
      '; ?>

        <?php echo $this->_tpl_vars['jsCode'][1]; ?>

      <?php echo '
      }
      '; ?>

      <?php endif; ?>
      <?php echo '
	    f.temp_body.value = f.richtext_body.value;//firefox;
	    //tinyMCE.updateContent(\'f_article\');
    }
    else
    {
		tinyMCE.triggerSave(false,false);
	    if (f.richtext_body.value.length == 0) {
	    alert(\'Please enter the content of the article\');
	    f.comment.focus();
	    return false;
	    }
	    //##f.temp_body.value = f.body.value;//firefox;
	    f.temp_body.value = f.body.value = f.richtext_body.value;//firefox;
    }
    return true;
}

function editArticle()
{
   if ($("edit_or_cancle").value == \'Edit Article\')
   {
     //document.getElementById("body2").disabled=false;
     $("richtext_body").readOnly=false;
     $("richtext_body").className="";
     $("edit_or_cancle").value="Cancel";
     $("is_edit").value="do_edit";
   } else {
     //document.getElementById("body2").disabled=true;
     $("richtext_body").readOnly=true;
     $("richtext_body").className="disabled_input";
     $("edit_or_cancle").value="Edit Article";
     $("is_edit").value="cancle_edit";
   }
}

function doAction(action, url)
{
    if (action == \'reject\' && login_role == \'client\') {
        if ($(\'rejectedButton\').innerHTML == \'Request Revision\'){
          var str = \'Do you wish to have this piece revised? \\n\\nNot a problem! Before confirming, please assure the following:\\n\\n-  Have you thoroughly read the entire piece and provided detailed feedback within the commentary section? \\n\\nWe have found clients who provide writers with thorough, specific feedback receive delivery faster and are 90% more satisfied with the end result than those who do not.\\n\\n-  Are the changes being requested in addition or deviates from the information provided to us in the pre-production processes? i.e. client questionnaire, samples, outlines, etc. If so, additional charges may accrue.\\n\\nThank you!\';
          if (!confirm(str, \'YES\', \'NO\')) {
              $(\'rejectedButton\').disabled = true;
              $(\'rejectedButton\').addClassName(\'disabledbutton\');
              return false;
          } else {
            $(\'rejectedButton\').innerHTML = \'Send Request\';
          }
        }
    }
    if (action!=\'qa\' && action != \'reject\' && action != \'temp\' && action != \'autotemp\' && action != \'approval\' && action != \'submit\' && action != \'save\' && action != \'force\' && action != \'forcec\' && action != \'forcecr\' && action != \'force reject\' && action != \'publish\' && action != \'1gc\' && action != \'1gd\') {
       alert(\'Please sign in this system\');
       return false;
    }


    $("approve_action").value = action;
    if (!check_f_article())  {
      saveTimmer();
	    return false;
    }
    var f = document.f_article;

    if (login_role == \'editor\' && action!=\'qa\' && action != \'temp\' && action != \'autotemp\') {

      var structure = jQuery(\'#\' + ratingDivId + " input[name=\'structure\']:checked").val() || 0;
      
    }
    if (login_role != \'client\') {
        tinyMCE.triggerSave(false,false);
        f.temp_body.value = f.richtext_body.value;//firefox;
        //tinyMCE.updateContent(\'f_article\');
        if (login_role == \'editor\') {
            if (f.article_status.value == \'1gc\' && (action==\'reject\' || action==\'approval\') || action == \'force\' && (f.article_status.value==\'2\' || f.article_status.value==\'1gd\') || action == \'reject\' && (f.article_status.value==\'1gd\' || f.article_status.value==\'4\')) {
                if (action==\'reject\' && (f.article_status.value == \'1gc\' || f.article_status.value==\'4\' || f.article_status.value==\'1gd\') && f.comment.value == \'\') {
                    alert(\'Please comment on article, before rejecting it\');
                    f.comment.focus();
                    return false;                
                }
                if (action == \'force\' && f.article_status.value==\'1gd\' && f.comment.value == \'\') {
                    alert(\'Please comment on article, before approving it\');
                    f.comment.focus();
                    return false;       
                }

                if ((f.article_status.value == \'1gc\' && action==\'approval\' || action == \'force\' && (f.article_status.value==\'2\' || f.article_status.value==\'1gd\'))) {
                  var rankings = f.ranking;
                  var is_ranked = false;
                  for(var i=0;i<rankings.length;i++) {
                    if (rankings[i].checked){
                      is_ranked = true;break;
                    }
                  }
                  if (!is_ranked){
                    alert(\'Please add the article rating\');return false;
                  }
                }
                /*if ((f.article_status.value == \'1gc\' && action==\'approval\' || action == \'force\' && (f.article_status.value==\'2\' || f.article_status.value==\'1gd\')) && !isObjectOrNot(f.ranking_id)) {
                    //alert(\'Please rate the article before approving or requesting edit of this article\');
                    //alert(\'Please rate the article before approving of this article\');
                    '; ?>

                    //showWindowDialog('/client_campaign/article_ranking_pop.php?user_id='+<?php echo $this->_tpl_vars['keyword_info']['copy_writer_id']; ?>
+'&campaign_id=' + <?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
 + '&article_id=' + <?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
+ '&keyword_id=' + <?php echo $this->_tpl_vars['keyword_info']['keyword_id']; ?>
);
                    $('post_action').value = action;
                    showWindowRatingDialog('article-rating-div');
                    <?php echo '
                    f.add_ranking.focus();
                    return false;
                }*/
            }
        }
    } else {
	    tinyMCE.triggerSave(false,false);
        //f.temp_body.value = f.body.value;//firefox;
		f.temp_body.value = f.body.value = f.richtext_body.value;//firefox;
        if (action == \'reject\' && f.comment.value == \'\') {
            alert(\'Please comment on article, before rejecting it\');
            f.comment.focus();
            return false;
        }

    }
	//document.forms[\'f_article\'].submit();
//    document.getElementById(\'f_article\').submit();
    if (action == \'force\') {
        if (f.article_status.value == 0 && confirm(\'Will approve even if copywriter has not submitted article. Continue?\')) {
            $(\'f_article\').submit();
        } else if (f.article_status.value == 1 && confirm(\'Article has not been google-checked. Continue?\')) {
            $(\'f_article\').submit();
        } else if (f.article_status.value != 1 && f.article_status.value != 0 && confirm("Do you want to force approve this article?")) {
            $(\'f_article\').submit();
        } else {
           $(\'comment\').focus();
           return false;
        }
        return true;
    }
    if (action == \'publish\') {
        f.article_status.value = 6;
        $(\'f_article\').submit();
    }
    if( action == \'approval\' ) {
        if (f.article_status.value == \'1gd\') {
            if (confirm("This article wasn\'t passed google checking.\\nAre you sure this article is  not duplicated?")) {
                $(\'f_article\').submit();
            } else {
               $(\'comment\').focus();
               return false;
            }
        } else {
            $(\'f_article\').submit();
        }
    } else {
        if (f.article_status.value == \'1\' && (action == \'1gc\' || action == \'1gd\'))  {
               $("approve_action").value = action;
              Element.show(\'show_result\');
        } else {
          if (action == \'save\') {
              $("approve_action").value = \'temp\';
              Element.show(\'show_result\');
          }
          if (action == \'reject\') {
              if (login_role != \'client\') {
                  f.article_status.value = 2;
              } else  {
                  f.article_status.value = 3;
              }
              Element.show(\'show_result\');
          }
        }

        new Ajax.Updater (
            \'show_status\',
             url, 
             {
                 method:\'post\',  
                 parameters: Form.serialize(\'f_article\'),
                 evalScripts:true,
                 asynchronous: false,
                 onComplete:showResult
             }
        );
        saveTimmer();
        return true;
     }
     return true;
}

function showResult()
{
	Element.hide(\'show_result\');
	Element.show(\'show_shape_end\');
}

function saveTimmer()
{
  setTimeout("doAction(\'autotemp\',\'';  echo $this->_tpl_vars['url'];  echo '\')", 300000);//1000 = 1 second
}

'; ?>

<?php if ($this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6' && $this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
saveTimmer();
<?php endif;  echo '
function doRating(url, user_id, keyword_id, article_id, campaing_id)
{
    if (user_id > 0 && article_id > 0 && keyword_id > 0 && campaing_id > 0) {
        if (url != \'\') {
          if (doAction(\'autotemp\', url)) {
            window.location.href=\'/client_campaign/article_ranking.php?user_id=\'+user_id+\'&campaign_id=\'+campaing_id+\'&article_id=\'+article_id+\'&keyword_id=\'+keyword_id;
          }
        } else {
            window.location.href=\'/client_campaign/article_ranking.php?user_id=\'+user_id+\'&campaign_id=\'+campaing_id+\'&article_id=\'+article_id+\'&keyword_id=\'+keyword_id;
        }
    } else if (user_id == 0 || user_id == \'\') {
        alert(\'Please assign the article to one copy writer first.\');
        return false;
    } else if (article_id == 0 || article_id == \'\') {
        alert(\'Please specify the article to rate\');
        return false;
    }
}
function checkRated(obj)
{
    var form = document.f_article;
    if (typeof obj == \'string\') 
        obj = form.elements[obj];
    for (var i=0; i < obj.length; i++)
    {
        obj[i].disabled = !(obj[i].disabled);
    }
}
function addGeneralNote(note_id) {
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
function htmlSpecialCharsDecode(str) {
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
<script>
function stop() {
    alert("The ranking quotiety is EMPTY! Please set it in System Setting!");
   // $("lsf").href = "#";
}
function jump() {
$("lsf").href = "/client_campaign/article_campaign_ranking.php?copywriter_id={$keyword_info.copy_writer_id}&campaign_id={$keyword_info.campaign_id}&article_id={$keyword_info.article_id}&keyword_id={$keyword_info.keyword_id}";
}

function changeNoflowStatus(aid, status)
{
    ajaxAction(\'/article/change_noflow_status.php?aid=\'+aid + \'&status=\' + status, \'show_result\');
}

</script>
'; ?>

<?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
<link href='/js/rating/jquery.rating.css' type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="/js/rating/jquery.rating.js"></script>
<script type="text/javascript" src="/js/rating/jquery.rating.function.js"></script>
<?php endif; ?>
<div class="loadingajax" style="display:none;z-index:1000" id="show_result"  align="center" valign="center" >Loading...</div>
<div id="page-box1" >
  <h2>Approve article</h2>
  <strong><?php if ($this->_tpl_vars['login_role'] == 'client'): ?>
<p>Are we ready to review this piece?</p>
<p style="background-color:#fceebf; padding:10px; line-height:25px; width:960px;-moz-border-radius: 5px;border-radius: 5px;">Great! Be sure to review the piece thoroughly, making note of all your feedback in the space provided below. Upon completion, please click the "Save" button to assure all of your feedback has been recorded.
If you're pleased with the content here, simply click "Approve!"
Does the piece need a bit of tweaking? Not a problem! We've got you covered! Just click the "Revise" button below and remember to record your feedback in the space provided!
Thank you.</p>
 <?php else:  endif; ?></strong> <br />
  <div class="pageposition" >
  <table width="100%"  class="formClass" ><tr><td valign="top">
  <div id="page-left">
  <form action="#" method="post"  name="f_article" id="f_article" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_article()"<?php endif; ?>>
    <input type="hidden" name="max_word" id="max_word" value="<?php echo $this->_tpl_vars['keyword_info']['max_word']; ?>
" />
    <input type="hidden" name="pay_type" id="pay_type" value="<?php echo $this->_tpl_vars['keyword_info']['pay_type']; ?>
" />
    <input type="hidden" name="refererby" value="<?php echo $this->_tpl_vars['refererby']; ?>
" />
    <input type="hidden" name="keyword_id" value="<?php echo $this->_tpl_vars['keyword_info']['keyword_id']; ?>
" />
    <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['keyword_info']['client_id']; ?>
" />
    <input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
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
  <table cellspacing="0" cellpadding="5" border="0" width="60%" border="1">
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
	  <?php if ($this->_tpl_vars['login_role'] == 'client'): ?>
      <tr>
        <td align="right" class="form-label"><strong>Word Count</strong></td>
        <td align="left"><?php echo $this->_tpl_vars['keyword_info']['real_words']; ?>
</td>
      </tr>
	  <?php endif; ?>
    </tbody></table> 
  </div>
  <input type="hidden" name="html_title" id="html_title" size="100" value="<?php echo $this->_tpl_vars['keyword_info']['html_title']; ?>
" />
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
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
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
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
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
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" valign="top" class="form-label"><strong>Article Content</strong></td>
      <td align="left">
      <div id="contentdiv" >
      <textarea name="richtext_body" id="richtext_body" style="width: 860px; height: 400px;" ><?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['keyword_info']['richtext_body'];  endif; ?></textarea>
      </div>
      </td>
      </tr>
    </tbody></table> 
  </div>
  <?php else: ?>
    <?php if ($this->_tpl_vars['keyword_info']['tags']): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" class="form-label"  style="width:50px" ><strong>Tags</strong></td>
      <td align="left"><?php echo $this->_tpl_vars['keyword_info']['tags']; ?>
</td>
      </tr>
    </tbody></table> 
  </div>
  <?php endif; ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" valign="top" class="form-label"><strong>Article Content</strong></td>
      <td align="left">
        <div id="div_body_test" ></div>
		  <div id="contentdiv" >
		  <textarea name="richtext_body" id="richtext_body" style="width: 860px; height: 400px;" ><?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['keyword_info']['richtext_body'];  endif; ?></textarea>
		  </div>
        <input type="hidden" name="body" id="body" value="<?php if ($this->_tpl_vars['keyword_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['richtext_body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>" />
				
		</td>
      </tr>
    </tbody></table> 
  </div>
    <?php endif; ?>
  <?php if ($this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
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
  <?php if ($this->_tpl_vars['keyword_info']['template'] == '2'): ?>
  <div class="form-item" >
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" valign="top" class="form-label"><strong>Small Image</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><input type="text" size="100"  name="small_image" id="small_image" 
        value="<?php echo $this->_tpl_vars['keyword_info']['small_image']; ?>
" /><?php else:  if ($this->_tpl_vars['keyword_info']['small_image']): ?><img src="<?php echo $this->_tpl_vars['keyword_info']['small_image']; ?>
" /><?php endif;  endif; ?></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Large Image</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><input type="text" size="100" name="large_image" id="large_image" 
        value="<?php echo $this->_tpl_vars['keyword_info']['large_image']; ?>
" /><?php else:  if ($this->_tpl_vars['keyword_info']['large_image']): ?><img src="<?php echo $this->_tpl_vars['keyword_info']['large_image']; ?>
" /><?php endif;  endif; ?></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Image Credit</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><input type="text" size="100"  name="image_credit" id="image_credit" 
        value="<?php echo $this->_tpl_vars['keyword_info']['image_credit']; ?>
" /><?php else:  echo $this->_tpl_vars['keyword_info']['image_credit'];  endif; ?></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Image Caption</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><input type="text" size="100"  name="image_caption" id="image_caption" 
        value="<?php echo $this->_tpl_vars['keyword_info']['image_caption']; ?>
" /><?php else:  echo $this->_tpl_vars['keyword_info']['image_caption'];  endif; ?></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Blurb</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><textarea name="blurb" style="width: 700px; height: 160px;"  id="blurb"><?php echo $this->_tpl_vars['keyword_info']['blurb']; ?>
</textarea><?php else:  echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['blurb'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  endif; ?></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Meta description</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><input type="text" size="100" name="meta_description" id="meta_description" 
        value="<?php echo $this->_tpl_vars['keyword_info']['meta_description']; ?>
" /><?php else:  echo $this->_tpl_vars['keyword_info']['meta_description'];  endif; ?></td>
      </tr>
      <tr>
      <td align="right" valign="top" class="form-label"><strong>Category</strong></td>
      <td align="left"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?><select id="category_id" name="category_id" ><option value="" >[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_categories'],'selected' => $this->_tpl_vars['keyword_info']['category_id']), $this);?>
</select><?php else:  if ($this->_tpl_vars['keyword_info']['category_id'] > 0):  echo $this->_tpl_vars['image_categories'][$this->_tpl_vars['keyword_info']['category_id']];  endif;  endif; ?></td>
      </tr>
    </tbody></table> 
  </div>
  <?php endif; ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody>
	  <tr>
      <td align="right" class="form-label" valign="top"><strong><?php if ($this->_tpl_vars['login_role'] == 'client'): ?>Tell us what you think<?php else: ?>Comments<?php endif; ?></strong></td>
      <td align="left"><textarea name="comment" style="width: 700px; height: 160px;" id="comment" id="comment"><?php echo ((is_array($_tmp=@$_POST['comment'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['autocomment']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['autocomment'])); ?>
</textarea></td>
      </tr>
      <?php if ($this->_tpl_vars['login_role'] == 'client' && $this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6'): ?>
      <tr><td></td><td align="right"><input type="button"  class="button" value="Save" onclick="doAction('save', '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;</td></tr>
      <?php endif; ?>
    </tbody></table> 
  </div>
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

  <?php if ($this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
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
    <?php if ($this->_tpl_vars['login_role'] == 'admin' || $this->_tpl_vars['login_role'] == 'editor' && ( $this->_tpl_vars['keyword_info']['article_status'] != '0' && $this->_tpl_vars['keyword_info']['article_status'] != '1' )): ?>
  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Rate the Article</strong></td>
      <td align="left"> 
        <div id="articleRatingButtonDiv" >
               <input type="hidden"  name="ranking_id" id="ranking_id" value="<?php echo $this->_tpl_vars['ranking_id']; ?>
" />
       <input type="hidden"  name="user_id" id="user_id" value="<?php echo $this->_tpl_vars['keyword_info']['copy_writer_id']; ?>
" />
      <?php $_from = $this->_tpl_vars['rankings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
      <input type="radio" name="ranking" class="star" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
      <?php endforeach; endif; unset($_from); ?> 
      &nbsp;&nbsp;&nbsp;&nbsp;<a href="http://community.copypress.com/copypress-writers-guide/for-editors-how-to-complete-an-assignment/" target="_blank">What is this?</a>
       </div>
       </td>
      </tr>
    </tbody></table>    
  </div>
    <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['login_role'] == 'admin' && $this->_tpl_vars['keyword_info']['article_status'] > '2'): ?>

  <script type='text/javascript'>
  <?php echo '
	updateInvoiceDate = function() {
		jQuery.ajax({
			\'type\': \'GET\',
			\'dataType\': \'json\',
  '; ?>

			'url': "/article/update_invoice_date.php?article_id="+<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
,
  <?php echo '
			\'data\': \'invoice_date=\'+jQuery("#invoice_date").val(),
			\'success\':function(data){
				if (data.success){
					jQuery("#invoice_date").css("background-color","yellow");
				} else {
					jQuery("#invoice_date").css("background-color","red");
				}
				//alert(data.msg);
				jQuery("#update_invoice_date").text(data.msg);
			},
			\'complete\':function(XHR,TS){XHR = null;}
		});
	}
  '; ?>

  </script>

  <div class="form-item">
  <table cellspacing="0" cellpadding="5" border="0" width="60%">
      <tbody><tr>
      <td align="right" class="form-label"><strong>Invoice Date</strong></td>
      <td align="left"> 
        <div id="articleRatingButtonDiv" >
		<input type="text" name="invoice_date" id="invoice_date" size="10" maxlength="10" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['invoice_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%m/%d/%Y') : smarty_modifier_date_format($_tmp, '%m/%d/%Y')); ?>
" readonly/>
        <input type="button" class="button" id="btn_cal_invoice_date" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "invoice_date",
            ifFormat    : "%m/%d/%Y",
            showsTime   : false,
            button      : "btn_cal_invoice_date",
            singleClick : true,
            step        : 1,
            onUpdate    : updateInvoiceDate,
            range       : [2000, 2038]
        });
        </script>
       </div>
       </td>
       <td align="left">
	   <div id="update_invoice_date" style="font-weight:bold;color:red;"></div>
       </td>
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
    <div id="form-buttons">
      <div id="div_button" >
      <table cellspacing="0" cellpadding="5" border="0" width="100%">
        <tr>
          <td align="left" >
  <?php if ($this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6' && $this->_tpl_vars['keyword_info']['article_status'] != '99'): ?>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <input type="button"  class="button" value="Save" onclick="doAction('save', '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
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
		<div style="border: 2px dashed red; display: inline; padding: 10px 5px; margin: 0px 5px 0px 0px;font: italic bold 12px/20px arial,sans-serif;">
        <input type="button" value="Force Client Approve" class="button" onclick="doAction('forcec' , '<?php echo $this->_tpl_vars['url']; ?>
' );"> With Pay Cycle:
		<select name="forcec_pay_month"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['onemonthlater']), $this);?>
</select>
		</div>
        <input type="button" value="Force Client Reject" class="button" onclick="doAction('forcecr' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <?php endif; ?>
      <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '2' || $this->_tpl_vars['keyword_info']['article_status'] == '1gd'): ?>
        <input type="button" value="Force Approve" class="button" onclick="doAction('force' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <input type="button" value="Request Edit" class="button" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <?php else: ?>
        <input type="button" value="Editor Approve" class="button" onclick="doAction('approval' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
        <input type="button" value="Request Edit" class="button" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <?php endif; ?>
      <?php if (( $this->_tpl_vars['login_role'] == 'admin' ) && $this->_tpl_vars['keyword_info']['qa_complete'] != '1' && ( $this->_tpl_vars['keyword_info']['article_status'] == '4' || $this->_tpl_vars['keyword_info']['article_status'] == '1gc' )): ?>
      <input type="button" id="qa_complete_btn" value="QA Complete" class="button" onclick="doAction('qa' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;
      <?php endif; ?>
    <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '4' && $this->_tpl_vars['login_role'] == 'client'): ?>
      <?php if ($this->_tpl_vars['keyword_info']['client_id'] != '478'): ?> <input type="button" value="Approve" class="button" onclick="doAction('approval' , '<?php echo $this->_tpl_vars['url']; ?>
' );">&nbsp;<?php endif; ?>
      <a id="rejectedButton" href="#" onclick="doAction('reject' , '<?php echo $this->_tpl_vars['url']; ?>
' );" title="Request Revision" >Request Revision</a>&nbsp;
          <?php endif; ?>

  <?php endif; ?>
  <div style="border: 2px dashed red; display: inline; padding: 10px 5px; margin: 0px 5px 0px 0px;font: italic bold 12px/20px arial,sans-serif;">
        Change Status<select name="noflow_status<?php echo $this->_tpl_vars['item']['article_id']; ?>
" onchange='changeNoflowStatus(<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
, this.value)'><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['noflow_statuses'],'selected' => $this->_tpl_vars['keyword_info']['noflow_status']), $this);?>
</select>
  </div>
        </td>
        <td align="right" >
        <?php if ($this->_tpl_vars['custom_fields']['custom_field1']): ?>
        <?php if ($this->_tpl_vars['keyword_info']['article_status'] != '5' && $this->_tpl_vars['keyword_info']['article_status'] != '6' && $this->_tpl_vars['keyword_info']['article_status'] != '99' && $this->_tpl_vars['keyword_info']['article_status'] != '4'): ?>
        <input type="button" value="Not Available" class="button" onclick="InAvailableArticle('f_article')" />
        <?php elseif ($this->_tpl_vars['keyword_info']['article_status'] == '99'): ?>
        <input type="button" value="Back On" class="button" onclick="AvailableArticle('f_article')" />
        <?php endif; ?>
        <?php endif; ?>
        </td>
      </tr>
    </table>
          </div>
    </div>
    <div id="show_shape_end" class="corner" style="display:none;width:310px;z-index:1000;height: 30px;" >
      <div class="ricohint" style="width:310px;z-index:1000;" id="show_status"  align="center" >saving...</div>
    </div>
    </form>
  </div>
  </td><td valign="top" >
  <div id="page-right" >
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
      <tr class="odd">
        <td>Pay Period</td>
        <td><div id="max-word-td" ><?php echo $this->_tpl_vars['cppayperiod']; ?>
</div></td>
      </tr>
      <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <tr ><td colspan="2" align="center">
	  <?php if ($this->_tpl_vars['keyword_info']['style_guide_url'] != ''): ?>
	  <a href="<?php echo $this->_tpl_vars['keyword_info']['style_guide_url']; ?>
" target="_blank" >Style Guide PDF</a><br /><div style="height: 10px;"></div>
	  <?php endif; ?>
	  <a href="/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
" target="_blank" >Assignment Details</a>
	  </td></tr>
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
	
      <?php if ($this->_tpl_vars['keyword_info']['keyword_description'] != ''): ?>
      <b>Note From <?php echo $this->_tpl_vars['keyword_info']['pm_name']; ?>
:</b><br />
      <div  style="height:150px;width:280px;overflow:auto;text-align:left;" >
      <?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['keyword_description'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>

      </div>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['notes']['notes'] != ''): ?>
      <!-- Editor Notes <br />-->
       <div style="height:150px;width:280px;overflow:auto;text-align:left;font-weight: normal;">
        <b>Note From <?php echo $this->_tpl_vars['notes']['user_name']; ?>
:</b><br /><?php echo $this->_tpl_vars['notes']['notes']; ?>

       </div>
      <?php endif; ?>
    </div>
	    <div ><a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
" target="_blank" style="color:red;">View previous versions</a></div>
  </div>
  </td></tr>
  </table>
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
    <td><?php if ($this->_tpl_vars['item']['creation_role'] == 'client'):  if ($this->_tpl_vars['item']['creation_user_id']):  echo $this->_tpl_vars['item']['ccreator'];  else: ?>n/a<?php endif;  else:  echo $this->_tpl_vars['item']['creator'];  endif; ?></td>
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
<?php if ($this->_tpl_vars['login_role'] == 'editor'): ?>
<div>
<?php $_from = $this->_tpl_vars['quotiety']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n'] => $this->_tpl_vars['v']):
?>
<input type="hidden" name="quotiety[<?php echo $this->_tpl_vars['n']; ?>
]" id="quotiety<?php echo $this->_tpl_vars['n']; ?>
" value="<?php echo $this->_tpl_vars['v']; ?>
" />
<?php endforeach; endif; unset($_from); ?>
</div>
<div id="afterRatedDiv" style="display:none;"></div>
<div id="article-rating-div" style="display:none;">
  <div class="form-item" style="margin:10px;" >
<form method="post" name="ranking_info" id="ranking_info" >
  <input type="hidden" name="opt" id="operation" value="rating" />
  <input type="hidden" name="post_url" id="post_url" value="<?php echo $this->_tpl_vars['url']; ?>
" />
  <input type="hidden" name="post_action" id="post_action" value="temp" />
  <input type="hidden" name="ranking_id" value="<?php echo $this->_tpl_vars['ranking_info']['ranking_id']; ?>
"/>
  <input type="hidden" name="ranking" value="<?php echo $this->_tpl_vars['ranking_info']['ranking']; ?>
"/>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="ratingtable" >
  <tr>
    <td class="bodyBold">Please rate this article</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="requiredInput" >Keyword </td>
    <td colspan="4">
      <?php echo $this->_tpl_vars['ranking_info']['keyword']; ?>

      <input type="hidden" name="keyword_id" id="keyword_id" value="<?php echo $this->_tpl_vars['ranking_info']['keyword_id']; ?>
" />
      <input type="hidden" name="article_id"   value="<?php echo $this->_tpl_vars['keyword_info']['article_id']; ?>
" />
    </td>
  </tr>
  <tr>
    <td class="requiredInput" >Campaign </td>
    <td colspan="4">
      <?php echo $this->_tpl_vars['ranking_info']['campaign_name']; ?>

      <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['ranking_info']['campaign_id']; ?>
" />
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Copywriter Name</td>
    <td colspan="4">
      <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->_tpl_vars['ranking_info']['user_id']; ?>
" />
      <?php echo $this->_tpl_vars['ranking_info']['user_name']; ?>

    </td>
  </tr>
  <tr>
    <td class="requiredInput">Punctuation</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="punctuation" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['punctuation'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Grammar</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="grammar" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['grammar'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Structure</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="structure" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['structure'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">AP Style</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="ap_style" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['ap_style'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Style Guide</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="style_guide" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['style_guide'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Overall Content Quality</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="quality" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['quality'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Communication with Editor</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="communication" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['communication'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>    
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Cooperativeness</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="cooperativeness" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['cooperativeness'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>   
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Timeliness</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="timeliness" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['timeliness'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>   
    </td>
  </tr>
  <tr><td colspan="12" align="center" ><div id="totaldiv" >Total:<?php echo ((is_array($_tmp=@$this->_tpl_vars['ranking_info']['ranking'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</div></td></tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" class="button" name="save" value="Save" onclick="checkRanking()"/></td>
  </tr>    
</table>
</form>
  </div>
</div>
<?php echo '
'; ?>

<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>