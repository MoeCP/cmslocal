<?php /* Smarty version 2.6.11, created on 2012-04-19 12:40:12
         compiled from manual_content/add_content_category.html */ ?>
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

<?php echo '
<script language="JavaScript">
<!--
function checkContent()
{
    var f = document.add_content_category;
    if (f.pref_value.value.length == 0) {
        alert("Please enter category title!");
        f.pref_value.focus();
        return false;
    }
    f.submit();
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Manual Content Category Setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the manual content category required information.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="add_content_category" id="add_content_category" >
  <input type="hidden" name="pref_id" value="<?php echo $this->_tpl_vars['content']['pref_id']; ?>
">
  <input type="hidden" name="pref_table" value="<?php echo $this->_tpl_vars['table']; ?>
">
  <input type="hidden" name="pref_field" value="<?php echo $this->_tpl_vars['field']; ?>
">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
    <tr>
    <td class="requiredInput">Category Title*</td>
    <td><input id="pref_value" name="pref_value" type="text" size="60" value="<?php echo $this->_tpl_vars['content']['pref_value']; ?>
" /></td>
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
  </div>
<br>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>