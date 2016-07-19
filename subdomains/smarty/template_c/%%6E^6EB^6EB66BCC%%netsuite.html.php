<?php /* Smarty version 2.6.11, created on 2013-08-27 03:43:16
         compiled from user/netsuite.html */ ?>
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

<script language="JavaScript">
<!--
var login_permission = '<?php echo $this->_tpl_vars['login_permission']; ?>
';
<?php echo '
function check_f_vendor()
{
}
'; ?>

//-->
</script>
<div id="page-box1">
  <h2><?php if ($this->_tpl_vars['user_info']['vendor_id'] > 0): ?>Update<?php else: ?>Create<?php endif; ?> Netsuite Vendor for <?php echo $this->_tpl_vars['user_info']['user_name']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div id="campaign-search" >
    <strong>Please enter the vendor's required information.</strong>
  </div>
  <div class="form-item" >
<form action="" method="post"  name="f_vendor" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_vendor()"<?php endif; ?>>
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
" />
  <input type="hidden" name="vendor_id" value="<?php echo $this->_tpl_vars['user_info']['vendor_id']; ?>
" />
  <input type="hidden" name="vaddresses" value="<?php echo $this->_tpl_vars['user_info']['vaddresses']; ?>
" />
  <input type="hidden" name="pay_pref" value="<?php echo $this->_tpl_vars['user_info']['pay_pref']; ?>
" />
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Name</td>
    <td><input name="firstName" type="text" id="firstName" value="<?php echo $this->_tpl_vars['user_info']['first_name']; ?>
"  /><input name="middleName" type="text" id="middleName" value="<?php echo $this->_tpl_vars['user_info']['middle_name']; ?>
" size="5" /><input name="lastName" type="text" id="lastName" value="<?php echo $this->_tpl_vars['user_info']['last_name']; ?>
"/ ></td>
  </tr>
  <tr>
    <td class="requiredInput">Phone</td>
    <td><input name="phone" type="text" id="phone" value="<?php echo $this->_tpl_vars['user_info']['phone']; ?>
" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Email</td>
    <td><input name="email" type="text" id="email" value="<?php echo $this->_tpl_vars['user_info']['email']; ?>
" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Global Subscription Status </td>
    <td><input name="globalSubscriptionStatus" type="text" id="globalSubscriptionStatus" value="Soft Opt-Out" readonly /></td>
  </tr>
  <tr>
    <td class="requiredInput">Email Preference</td>
    <td><input name="emailPreference" type="text" id="emailPreference" value="Default" readonly /></td>
  </tr>
  <tr>
    <td class="requiredInput">1099 Eligible</td>
    <td><input name="is1099Eligible" type="checkbox" id="is1099Eligible" value="1" checked readonly /></td>
  </tr>
  <tr>
    <td class="requiredInput">Account Number</td>
    <td><input name="accountNumber" type="text" id="accountNumber" value="BlueGlass"  size="50" readonly /></td>
  </tr>
  <tr>
    <td class="requiredInput">Tax ID</td>
    <td><input name="taxIdNum" type="text" id="taxIdNum" value="<?php echo $this->_tpl_vars['user_info']['social_security_number']; ?>
"  size="50" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Addressee</td>
    <td><input name="address[addressee]" type="text" id="addressee" value="<?php echo $this->_tpl_vars['user_info']['fullname']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Address1</td>
    <td><textarea name="address[addr1]" cols="60" rows="4" id="addr1"><?php echo $this->_tpl_vars['user_info']['address']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Address2</td>
    <td><textarea name="address[addr2]" cols="60" rows="4" id="addr2"><?php echo $this->_tpl_vars['user_info']['address2']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">City</td>
    <td><input name="address[city]" type="text" id="city" value="<?php echo $this->_tpl_vars['user_info']['city']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">State</td>
    <td><input name="address[state]" type="text" id="state" value="<?php echo $this->_tpl_vars['user_info']['state']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">Zip</td>
    <td><input name="address[zip]" type="text" id="zip" value="<?php echo $this->_tpl_vars['user_info']['zip']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">Country</td>
    <td><input name="address[country]" type="text" id="country" value="<?php echo $this->_tpl_vars['user_info']['country']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Notes</td>
    <td><input name="comments" type="text" id="comments" value="<?php echo $this->_tpl_vars['user_info']['paypal_email']; ?>
" size="50" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
</table>
</form>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>