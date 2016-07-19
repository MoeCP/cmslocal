<?php /* Smarty version 2.6.11, created on 2012-03-09 16:48:48
         compiled from user/qb_vendor.html */ ?>
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
  <h2><?php if ($this->_tpl_vars['user_info']['vendor_id'] > 0): ?>Update<?php else: ?>Create<?php endif; ?> QuickBook Vendor for <?php echo $this->_tpl_vars['user_info']['user_name']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div id="campaign-search" >
    <strong>Please enter the vendor's required information.</strong>
  </div>
  <div class="form-item" >
<form action="" method="post"  name="f_vendor" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_vendor()"<?php endif; ?>>
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
" />
  <input type="hidden" name="ListID" value="<?php echo $this->_tpl_vars['user_info']['qb_vendor_id']; ?>
" />
  <input type="hidden" name="EditSequence" value="<?php echo $this->_tpl_vars['user_info']['qb_sequence']; ?>
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
    <td><input name="FirstName" type="text" id="FirstName" value="<?php echo $this->_tpl_vars['user_info']['first_name']; ?>
"  /><input name="MiddleName" type="text" id="MiddleName" value="<?php echo $this->_tpl_vars['user_info']['middle_name']; ?>
" size="5" /><input name="LastName" type="text" id="LastName" value="<?php echo $this->_tpl_vars['user_info']['last_name']; ?>
"/ ></td>
  </tr>
  <tr>
    <td class="requiredInput">Address1</td>
    <td><textarea name="VendorAddress[Addr1]" cols="60" rows="4" id="Addr1"><?php echo $this->_tpl_vars['user_info']['address']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Address2</td>
    <td><textarea name="VendorAddress[Addr2]" cols="60" rows="4" id="Addr2"><?php echo $this->_tpl_vars['user_info']['address2']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">City</td>
    <td><input name="VendorAddress[City]" type="text" id="City" value="<?php echo $this->_tpl_vars['user_info']['city']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">State</td>
    <td><input name="VendorAddress[State]" type="text" id="State" value="<?php echo $this->_tpl_vars['user_info']['state']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">Postal Code</td>
    <td><input name="VendorAddress[PostalCode]" type="text" id="PostalCode" value="<?php echo $this->_tpl_vars['user_info']['zip']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">Country</td>
    <td><input name="VendorAddress[Country]" type="text" id="Country" value="<?php echo $this->_tpl_vars['user_info']['country']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"><input type="hidden" name="Phone" value="<?php echo $this->_tpl_vars['user_info']['pay_pref']; ?>
" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Phone</td>
    <td><input name="Mobile" type="text" id="Mobile" value="<?php echo $this->_tpl_vars['user_info']['phone']; ?>
" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Bank Routing Number</td>
    <td><input name="Pager" type="text" id="Pager" value="<?php echo $this->_tpl_vars['user_info']['routing_number']; ?>
"  size="50" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Back Account Number</td>
    <td><input name="AltPhone" type="text" id="AltPhone" value="<?php echo $this->_tpl_vars['user_info']['bank_info']; ?>
"  size="50" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Email</td>
    <td><input name="Email" type="text" id="Email" value="<?php echo $this->_tpl_vars['user_info']['email']; ?>
" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Tax ID</td>
    <td><input name="VendorTaxIdent" type="text" id="VendorTaxIdent" value="<?php echo $this->_tpl_vars['user_info']['social_security_number']; ?>
"  size="50" /></td>
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