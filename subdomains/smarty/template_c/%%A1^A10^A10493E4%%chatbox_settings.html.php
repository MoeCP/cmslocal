<?php /* Smarty version 2.6.11, created on 2016-07-11 11:52:24
         compiled from user/chatbox_settings.html */ ?>
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
function esign()
{
  var f = document.f_esign;

  return true;
}
//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Chat Box Settings</h2>

  <div class="form-item" >
<br>
<form action="" method="post"  name="f_chatbox_settings">
<input type="hidden" name="pref_id" id="pref_id" value="<?php echo $this->_tpl_vars['settings']['pref_id']; ?>
" />
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold" nowrap>Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Turn The Chat Box On?</td>
    <td>
      <select name="status" id="status">
        <option value="1" <?php if ($this->_tpl_vars['settings']['pref_value'] == 1): ?>selected<?php endif; ?>>Yes</option>
        <option value="0" <?php if ($this->_tpl_vars['settings']['pref_value'] == 0): ?>selected<?php endif; ?>>No</option>
      </select>
	</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
</table>
</form>
<br>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>