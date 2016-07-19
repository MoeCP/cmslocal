<?php /* Smarty version 2.6.11, created on 2013-01-18 09:42:58
         compiled from article/type_question_add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/type_question_add.html', 53, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>

<?php echo '
<script language="JavaScript">
<!--
function check_f_question()
{
  var f = document.f_question;

  if (f.type_id.value.length == 0) {
    alert(\'Please choose a article type\');
    f.type_id.focus();
    return false;
  }

  if (f.type_id.value.length == 0) {
    alert(\'Please enter questions\');
    f.question.focus();
    return false;
  }

  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Add Type Question</h2>
  <div id="campaign-search" >
    <strong>Please enter the article type question required information.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_question" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_question()"<?php endif; ?>>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Article Type</td>
    <td><select name="type_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $this->_tpl_vars['info']['type_id']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="requiredInput">Questions</td>
    <td><textarea name="question" cols="80" rows="7" id="question"><?php echo $this->_tpl_vars['info']['question']; ?>
</textarea><br />split the quesitons as Enter Key</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button" />&nbsp;<input type="reset" value="reset" class="button" /></td>
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