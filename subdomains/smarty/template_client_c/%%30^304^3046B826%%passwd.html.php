<?php /* Smarty version 2.6.11, created on 2015-08-14 16:15:21
         compiled from user/passwd.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>

<?php echo '
<script language="JavaScript">
<!--
function check_f_user()
{
  var f = document.f_user;

  if (f.user_pw.value.length == 0) {
    alert(\'Please enter the old password\');
    f.user_pw.focus();
    return false;
  }
  if (f.new_pw1.value.length == 0) {
    alert(\'Please enter the new password\');
    f.new_pw1.focus();
    return false;
  }
  if (f.new_pw2.value.length == 0) {
    alert(\'Please enter the new password again\');
    f.new_pw2.focus();
    return false;
  }
  if (f.new_pw1.value != f.new_pw2.value) {
    alert(\'Password mismatch, please check your input and enter the password again\');
    f.new_pw2.focus();
    return false;
  }
  return true;
}

//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Update Login Password</h2>
  <div id="campaign-search" >
    <strong></strong>
  </div>
  <div class="form-item" >
<table border="0" cellspacing="1" cellpadding="4" align="center">
  <tr>
    <td class="bodyBold" nowrap colspan="2"></td>
    <td align="right" class="requiredHint">* Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="3"><img src="/image/misc/s.gif"></td>
    
  </tr>
   <tr>
    <td  colspan="3"><img src="/image/misc/s.gif"></td>
    
  </tr>
  <form action="#" method="post" name="f_user" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_user()"<?php endif; ?>>
  <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
  <tr>
    <td colspan="2" class="requiredInput">* The Old Password</td>
    <td><input name="user_pw" type="password" id="user_pw" size="20" /></td>
  </tr>
  <tr>
    <td colspan="2" class="requiredInput">* New Password</td>
    <td><input name="new_pw1" type="password" id="new_pw1" /></td>
  </tr>
  <tr>
    <td colspan="2" class="requiredInput" nowrap>* Confirm New Password</td>
    <td><input name="new_pw2" type="password" id="new_pw2" /></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="3"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td  colspan="3"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="Reset" class="button"></td>
  </tr>
  </form>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>