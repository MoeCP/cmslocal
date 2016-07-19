<?php /* Smarty version 2.6.11, created on 2012-03-15 14:07:15
         compiled from client/client_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client/client_form.html', 114, false),array('function', 'html_options', 'client/client_form.html', 147, false),)), $this); ?>
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
function check_f_client()
{
  var f = document.f_client;

  if (f.user_name.value.length == 0) {
    alert(\'Please enter user name\');
    f.user_name.focus();
    return false;
  }

  if (f.client_id.value.length == 0)
  {
	  if (f.user_pw.value.length == 0) {
		alert(\'Please enter user password\');
		f.user_pw.focus();
		return false;
	  }
	  if (f.user_pwnew.value.length == 0) {
		alert(\'Please enter confirm password\');
		f.user_pwnew.focus();
		return false;
	  }
  }

  if (f.user_pw.value != f.user_pwnew.value)
  {
	alert(\'Password mismatch, Please check your input and enter the password again\');
    f.user_pw.focus();
    return false;
  }

  if (f.company_name.value.length == 0) {
    alert(\'Please enter client company name\');
    f.company_name.focus();
    return false;
  }
  if (f.city.value.length == 0) {
    alert(\'Please enter City\');
    f.city.focus();
    return false;
  }
  if (f.state.value.length == 0) {
    alert(\'Please enter state\');
    f.state.focus();
    return false;
  }
  if (f.zip.value.length == 0) {
    alert(\'Please enter zip\');
    f.zip.focus();
    return false;
  }

  if (!isEmail(f.email.value)) {
    alert(\'Invalid email address\');
    f.email.focus();
    return false;
  }

  if (f.project_manager_id.value.length == 0) {
    alert(\'Please provide a project manager\');
    f.project_manager_id.focus();
    return false;
  }
'; ?>

<?php if ($this->_tpl_vars['user_permission_int'] <> 2):  echo '
  if (f.referrer_type.value == \'\')
  {
      alert(\'Please specify the referrer type\');
      f.referrer_type.focus();
      return false;
  }

  if (f.referrer_type.value == 2 && f.referrer_name.value == \'\')
  {
      alert(\'Please specify the referrer name\');
      f.referrer_name.focus();
      return false;
  }
'; ?>

<?php endif;  echo '
  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Client's Information setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the client's required information.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_client" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_client()"<?php endif; ?>>
  <input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['client_info']['client_id']; ?>
">
  <input type="hidden" name="creation_user" value="<?php echo $this->_tpl_vars['client_info']['creation_user']; ?>
">
  <input type="hidden" name="creation_role" value="<?php echo $this->_tpl_vars['client_info']['creation_role']; ?>
">
  <?php if ($this->_tpl_vars['user_permission_int'] == 2): ?>
  <input type="hidden" name="referrer_type" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['client_info']['referrer_type'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
">
  <input type="hidden" name="referrer_name" value="<?php echo $this->_tpl_vars['client_info']['referrer_name']; ?>
">
  <input type="hidden" name="referrer_tracking" value="<?php echo $this->_tpl_vars['client_info']['referrer_tracking']; ?>
">
  <?php endif; ?>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">User Name</td>
    <td><input name="user_name" type="text" id="user_name" value="<?php echo $this->_tpl_vars['client_info']['user_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Password</td>
    <td><input name="user_pw" type="password" id="user_pw" value="<?php echo $this->_tpl_vars['client_info']['user_pw']; ?>
" /></td>
  </tr>
  <tr>
    <td class="requiredInput" nowrap>Confirm Password</td>
    <td><input name="user_pwnew" type="password" id="user_pwnew" value="<?php echo $this->_tpl_vars['client_info']['user_pw']; ?>
" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Company Name</td>
    <td><input name="company_name" type="text" id="company_name" value="<?php echo $this->_tpl_vars['client_info']['company_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Company Address</td>
    <td><textarea name="company_address" cols="50" rows="4" id="company_address"><?php echo $this->_tpl_vars['client_info']['company_address']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Country</td>
    <td><select name="country"><option value="United States of America">United States of America</option><?php if ($this->_tpl_vars['client_info']['country'] != ''):  echo smarty_function_html_options(array('values' => $this->_tpl_vars['country'],'output' => $this->_tpl_vars['country'],'selected' => $this->_tpl_vars['client_info']['country']), $this); else:  echo smarty_function_html_options(array('values' => $this->_tpl_vars['country'],'output' => $this->_tpl_vars['country'],'selected' => 'United States of America'), $this); endif; ?></select></td>
  </tr>
  <tr>
    <td class="requiredInput">City</td>
    <td><input name="city" type="text" id="city" value="<?php echo $this->_tpl_vars['client_info']['city']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">State</td>
    <td><input name="state" type="text" id="state" value="<?php echo $this->_tpl_vars['client_info']['state']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Zip</td>
    <td><input name="zip" type="text" id="zip" value="<?php echo $this->_tpl_vars['client_info']['zip']; ?>
" onchange="javascript:this.value=Trim(this.value)"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Email</td>
    <td><input name="email" type="text" id="email" value="<?php echo $this->_tpl_vars['client_info']['email']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
  </tr>
  <tr>
    <td class="dataLabel">Company Url</td>
    <td><input name="company_url" type="text" id="company_url" value="<?php echo $this->_tpl_vars['client_info']['company_url']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
  </tr>
  <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
  <tr>
    <td class="dataLabel">Contact Name</td>
    <td><input name="contact_name" type="text" id="contact_name" value="<?php echo $this->_tpl_vars['client_info']['contact_name']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="dataLabel">Company Phone Number</td>
    <td><input name="company_phone" type="text" id="company_phone" value="<?php echo $this->_tpl_vars['client_info']['company_phone']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Billing Email</td>
    <td><input name="bill_email" type="text" id="bill_email" value="<?php echo $this->_tpl_vars['client_info']['bill_email']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="dataLabel">Billing Phone</td>
    <td><input name="bill_office_phone" type="text" id="bill_office_phone" value="<?php echo $this->_tpl_vars['client_info']['bill_office_phone']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Technical Email</td>
    <td><input type="text" name="technical_email" id="technical_email" size="20" value="<?php echo $this->_tpl_vars['client_info']['technical_email']; ?>
" /></td>
  </tr>
  <tr>
    <td class="dataLabel">Technical Phone</td>
    <td><input name="technical_office_phone" type="text" id="technical_office_phone" value="<?php echo $this->_tpl_vars['client_info']['technical_office_phone']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <?php if ($this->_tpl_vars['user_permission_int'] >= 5 || $this->_tpl_vars['user_permission_int'] == 2): ?>
  <tr>
    <td class="requiredInput">Project Manager</td>
    <td>
    <select name="project_manager_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_pm'],'selected' => $this->_tpl_vars['client_info']['project_manager_id']), $this);?>
</select>
    </td>
  </tr>
  <?php if ($this->_tpl_vars['user_permission_int'] >= 5): ?>
  <tr>
    <td class="dataLabel">Agency</td>
    <td>
    <select name="agency_id"><option value="0">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['agencies'],'selected' => $this->_tpl_vars['client_info']['agency_id']), $this);?>
</select>
    </td>
  </tr>
  <?php endif; ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['user_permission_int'] <> 2): ?>
  <tr>
    <td class="requiredInput">Referrer Type</td>
    <td><select name="referrer_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['referrer_types'],'selected' => $this->_tpl_vars['client_info']['referrer_type']), $this);?>
</select></td>
  </tr>
    <tr>
    <td class="requiredInput">Referrer Name</td>
    <td><input name="referrer_name" type="text" id="referrer_name" value="<?php echo $this->_tpl_vars['client_info']['referrer_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
    <tr>
    <td class="dataLabel">Referrer Tracking</td>
    <td><input name="referrer_tracking" type="text" id="referrer_tracking" value="<?php echo $this->_tpl_vars['client_info']['referrer_tracking']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <?php endif; ?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>