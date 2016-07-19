<?php /* Smarty version 2.6.11, created on 2013-01-18 09:39:43
         compiled from client/ckey_quick_add.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
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
function check_f_form()
{
  var f = document.f_generatekey;

  if (!isEmail(f.email.value)) {
    alert(\'Invalid email address\');
    f.email.focus();
    return false;
  }

  if (f.domain.value.length == 0) {
    alert(\'Please provide the domain\');
    f.domain.focus();
    return false;
  }


  if (f.apitype.value.length == 0) {
    alert(\'Please specify what\\\'s kind of API\');
    f.apitype.focus();
    return false;
  }
  if (f.email.value.length == 0) {
    alert(\'Please specify email\');
    f.email.focus();
    return false;
  }

  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Generate API Key</h2>
  <div id="campaign-search" >
    <strong>Please enter the domain and email address you would like to generate the API key for.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_generatekey" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_form()"<?php endif; ?>>
  <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['info']['client_id']; ?>
">
  <input type="hidden" name="client_user_id" value="<?php echo $this->_tpl_vars['info']['client_user_id']; ?>
">
  <input type="hidden" name="user" value="<?php echo $this->_tpl_vars['info']['user']; ?>
">
  <input name="email" type="hidden" id="email" value="<?php echo $this->_tpl_vars['info']['email']; ?>
" />
  <?php if ($this->_tpl_vars['login_role'] == 'client'): ?><input type="hidden" name="apitype" id="apitype" value="wordpress" /><?php endif; ?>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Owner</td>
    <td><?php echo $this->_tpl_vars['info']['user']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput" nowrap>Domain</td>
    <td><input name="domain" type="text" id="domain" value="<?php echo $this->_tpl_vars['info']['domain']; ?>
" />(e.g. example.com)</td>
  </tr>
  <tr>
    <td class="dataLabel">Notes</td>
    <td><textarea name="description" cols="50" rows="4" id="description"><?php echo $this->_tpl_vars['info']['description']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
  </form>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>