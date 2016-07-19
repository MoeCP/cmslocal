<?php /* Smarty version 2.6.11, created on 2012-08-27 09:24:40
         compiled from manual_content/add_manual_content.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'manual_content/add_manual_content.html', 97, false),array('modifier', 'nl2br', 'manual_content/add_manual_content.html', 104, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link rel="stylesheet" type="text/css" href="spell_checker/spell_checker/css/spell_checker.css">
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>

<script language="JavaScript">
<?php echo '
<!--

tinyMCE.init({
mode : "exact",
theme : "advanced",
elements : "full_text",
relative_urls : false,
remove_script_host : false,
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
plugins : "spellchecker,searchreplace,charcount",
theme_advanced_buttons1 : "bold,italic,underline, separator,forecolor ,separator,search,replace,separator, code, separator,spellchecker,charcount",
theme_advanced_buttons1_add_before: "undo,redo,separator,hr,link,unlink,separator, formatselect, bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
theme_advanced_buttons2 :"",
theme_advanced_buttons3 : "",
directionality: "ltr",
force_br_newlines : "false",
force_p_newlines : "true",
debug : false,
cleanup : true,
cleanup_on_startup : false,
safari_warning : false
});
//-->
'; ?>

</script>

<?php echo '
<script language="JavaScript">
<!--
function checkContent()
{
    var f = document.add_manual_content;
    tinyMCE.triggerSave(false,false);
    if (f.title.value.length == 0) {
        alert("Please enter content\'s title!");
        f.title.focus();
        return false;
    } else if (f.category.value.length == 0) {
        alert("Please select a category!");
        f.body.focus();
        return false;
    } else if (f.full_text.value.length == 0 ) {
        alert("please enter main text!");
        f.full_text.focus();
        return false;    
    }
    var state = document.getElementById("state");
    if (state.checked == true) {
        state.value = 1;
    } else state.value = 0;
    //tinyMCE.updateContent(\'add_manual_content\');
    f.submit();
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Manual Content Setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the manual content required information.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="add_manual_content" id="add_manual_content" >
  <input type="hidden" name="content_id" value="<?php echo $this->_tpl_vars['content']['content_id']; ?>
">
  <input type="hidden" name="created_by" value="<?php echo $this->_tpl_vars['content']['created_by']; ?>
">
  <input type="hidden" name="created" value="<?php echo $this->_tpl_vars['content']['created']; ?>
">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Title *</td>
    <td><input id="title" name="title" type="text" size="60" value="<?php echo $this->_tpl_vars['content']['title']; ?>
" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Category *</td>
    <td>
      <select name="category" id="category">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['categories'],'selected' => $this->_tpl_vars['content']['category']), $this);?>

      </select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput" valign="top">Main Text *</td>
    <td>
    <textarea name="full_text" id="full_text" style="width: 700px; height: 400px;" ><?php if ($this->_tpl_vars['content']['full_text'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['content']['full_text'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['content']['full_text'];  endif; ?></textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Publish</td>
    <td><input name="state" type="checkbox" id="state" <?php echo $this->_tpl_vars['checked']; ?>
/></td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Submit" class="button" onclick="checkContent()">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
  </form>
</table>
<br>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>