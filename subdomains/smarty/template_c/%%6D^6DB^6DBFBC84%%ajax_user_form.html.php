<?php /* Smarty version 2.6.11, created on 2015-10-20 12:04:37
         compiled from user/ajax_user_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/ajax_user_form.html', 180, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="JavaScript">
<!--
var login_permission = '<?php echo $this->_tpl_vars['login_permission']; ?>
';
<?php echo '
check_f_user = function ()
{ 
  var f = document.f_user;
  if (f.user_name.value.length == 0) {
    alert(\'Please enter user name\');
    f.user_name.focus();
    return false;
  }
  if (login_permission >= 4)
  {
      if (f.user_id.value.length == 0)
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

      if (f.role.value.length == 0) {
        alert(\'Please enter role\');
        f.role.focus();
        return false;
      }
      if (login_permission > 4) {
        if (f.role.value == 1 && f.pay_level.value == 0)
        {
            alert("Please sepficy the pay level");
            f.pay_level.focus();
            return false;
        }
      }
    }

  if (f.first_name.value.length == 0) {
    alert(\'Please enter first name\');
    f.first_name.focus();
    return false;
  }
  if (f.last_name.value.length == 0) {
    alert(\'Please enter last name\');
    f.last_name.focus();
    return false;
  }

  if (!(f.sex[0].checked || f.sex[1].checked)) {
    alert(\'Please enter the gender \');
    return false;
  }

  if (!isEmail(f.email.value)) {
    alert(\'Invalid email address\');
    f.email.focus();
    return false;
  }
  if (f.pay_pref.value == 3)
  {
    if (f.paypal_email.value == \'\')
    {
          alert(\'Please specify the paypal email address\');
          f.paypal_email.focus();
         return false;
    }
  }
  if (f.paypal_email.value != \'\' && !isEmail(f.paypal_email.value)) {
    alert(\'Invalid paypal email address\');
    f.paypal_email.focus();
    return false;
  }

  return true;
}

onChangeRole = function (permisstion)
{
  $(\'user_type_tr\').style.display=\'none\';
  $(\'user_type_tradmin\').style.display=\'none\';
  if (permisstion == 1) {
    $(\'user_type_tr\').style.display=\'\';
  } else {
    $(\'user_type_tr\').style.display=\'none\';
  }
  if (permisstion == 5) {
	$(\'user_type_tradmin\').style.display=\'\';
  } else {
    $(\'user_type_tradmin\').style.display=\'none\';
  }
}
'; ?>

//-->
</script>
<div id="page-box1">
  <h2>User's Information setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the user's required information.</strong>
  </div>
  <div class="form-item" >
<form action="ajax_user_set.php" method="post"  id="f_user" name="f_user" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_user()"<?php endif; ?>>
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <input type="hidden" name="frompage" value="<?php echo $this->_tpl_vars['frompage']; ?>
">
  <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
">
  <?php if ($this->_tpl_vars['login_permission'] == 1): ?>
    <input type="hidden" name="date_join" id="date_join" value="<?php echo $this->_tpl_vars['user_info']['date_join']; ?>
" />
    <input type="hidden" name="role" id="role" value="<?php echo $this->_tpl_vars['user_info']['permission']; ?>
" />
  <?php endif; ?>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['login_permission'] >= 5 && ( $this->_tpl_vars['user_info']['permission'] == 1 || $this->_tpl_vars['user_info']['permission'] == 3 )): ?>
  <tr>
    <td class="requiredInput">Vendor ID</td>    
    <td>
    <?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuit'): ?>
    <input name="vendor_id" type="text" id="vendor_id" value="<?php echo $this->_tpl_vars['user_info']['vendor_id']; ?>
" onchange="javascript:this.value=Trim(this.value)" />
    <?php else: ?>
    <input name="qb_vendor_id" type="text" id="qb_vendor_id" value="<?php echo $this->_tpl_vars['user_info']['qb_vendor_id']; ?>
" onchange="javascript:this.value=Trim(this.value)" />
    <?php endif; ?>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
  <tr>
    <td class="requiredInput">User Name</td>
    <td><input name="user_name" type="text" id="user_name" value="<?php echo $this->_tpl_vars['user_info']['user_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Password</td>
    <td><input name="user_pw" type="password" id="user_pw" value="<?php echo $this->_tpl_vars['user_info']['user_pw']; ?>
"  /></td>
  </tr>
  <tr>
    <td class="requiredInput" nowrap>Confirm Password</td>
    <td><input name="user_pwnew" type="password" id="user_pwnew" value="<?php echo $this->_tpl_vars['user_info']['user_pw']; ?>
"  /></td>
  </tr>
  <?php else: ?>
  <tr>
    <td class="requiredInput">User Name</td>
    <td><input name="user_name" readonly type="text" id="user_name" value="<?php echo $this->_tpl_vars['user_info']['user_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">First Name</td>
    <td><input name="first_name" type="text" id="first_name" value="<?php echo $this->_tpl_vars['user_info']['first_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Last Name</td>
    <td><input name="last_name" type="text" id="last_name" value="<?php echo $this->_tpl_vars['user_info']['last_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Gender</td>
    <td><input type="radio" name="sex" value="male" <?php if ($this->_tpl_vars['user_info']['sex'] == 'male'): ?>checked<?php endif; ?>>male
      <input type="radio" name="sex" value="female" <?php if ($this->_tpl_vars['user_info']['sex'] == 'female'): ?>checked<?php endif; ?>>female</td>
  </tr>
  <tr>
    <td class="requiredInput">Email</td>
    <td><input name="email" type="text" id="email" value="<?php echo $this->_tpl_vars['user_info']['email']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Country</td>
    <td><select name="country" id="country"><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['country'],'output' => $this->_tpl_vars['country'],'selected' => $this->_tpl_vars['user_info']['country']), $this);?>
 </select></td>
  </tr>
  <tr>
    <td class="requiredInput">City</td>
    <td><input name="city" type="text" id="city" value="<?php echo $this->_tpl_vars['user_info']['city']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">State</td>
    <td><input name="state" type="text" id="state" value="<?php echo $this->_tpl_vars['user_info']['state']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">Zip</td>
    <td><input name="zip" type="text" id="zip" value="<?php echo $this->_tpl_vars['user_info']['zip']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="requiredInput">Address1</td>
    <td><textarea name="address" cols="35" rows="4" id="address"><?php echo $this->_tpl_vars['user_info']['address']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Address2</td>
    <td><textarea name="address2" cols="35" rows="4" id="address2"><?php echo $this->_tpl_vars['user_info']['address2']; ?>
</textarea></td>
  </tr>
  <?php if ($this->_tpl_vars['login_permission'] > 4): ?>
  <tr>
    <td class="requiredInput">Pay Level</td>
    <td><select name="pay_level" ><option value="0">[choose]</option><?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['pay_levels'],'values' => $this->_tpl_vars['pay_levels'],'selected' => $this->_tpl_vars['user_info']['pay_level']), $this);?>
</select></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="dataLabel">Your first language</td>
    <td><select name="first_language" id="first_language" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['first_languages'],'selected' => $this->_tpl_vars['user_info']['first_language']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="dataLabel">Pen Name</td>
    <td><input name="pen_name" type="text" id="phone" value="<?php echo $this->_tpl_vars['user_info']['pen_name']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20"></td>
  </tr>
  <tr>
    <td class="dataLabel">Phone Number</td>
    <td><input name="phone" type="text" id="phone" value="<?php echo $this->_tpl_vars['user_info']['phone']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="dataLabel">Mobile Telephone Number</td>
    <td><input name="cell_phone" type="text" id="cell_phone" value="<?php echo $this->_tpl_vars['user_info']['cell_phone']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Bio</td>
    <td><textarea name="bio" cols="35" rows="4" id="bio"><?php echo $this->_tpl_vars['user_info']['bio']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel">Birthday</td>
    <td><input type="text" name="birthday" id="birthday" size="10" maxlength="10" value="<?php if ($this->_tpl_vars['user_info']['birthday'] != ''):  echo $this->_tpl_vars['user_info']['birthday'];  else: ?>1970-01-01<?php endif; ?>" />
        <input type="button" class="button" id="btn_cal_birthday" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "birthday",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_birthday",
            singleClick : true,
            step        : 1,
            range       : [1940, 2030]
        });
        </script></td>
  </tr>
  <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
  <tr>
    <td class="dataLabel">Date Of Hire</td>
    <td><input type="text" name="date_join" id="date_join" size="10" maxlength="10" value="<?php if ($this->_tpl_vars['user_info']['date_join'] != ''):  echo $this->_tpl_vars['user_info']['date_join'];  else: ?>2006-01-01<?php endif; ?>" />
        <input type="button" class="button" id="btn_cal_date_join" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_join",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_date_join",
            singleClick : true,
            step        : 1,
            range       : [1950, 2030]
        });
        </script></td>
  </tr>
  <tr>
    <td class="dataLabel">Social Security Number</td>
    <td><input name="social_security_number" type="text" id="social_security_number" value="<?php echo $this->_tpl_vars['user_info']['social_security_number']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Bank Name</td>
    <td><input name="bank_name" type="text" id="bank_name" value="<?php echo $this->_tpl_vars['user_info']['bank_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Back Rounting Number</td>
    <td><input name="routing_number" type="text" id="routing_number" value="<?php echo $this->_tpl_vars['user_info']['routing_number']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Account Number</td>
    <td><input name="bank_info" type="text" id="bank_info" value="<?php echo $this->_tpl_vars['user_info']['bank_info']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Bank Account Type</td>
    <td><?php echo smarty_function_html_options(array('name' => 'bank_acct_type','options' => $this->_tpl_vars['acct_types'],'selected' => $this->_tpl_vars['user_info']['bank_acct_type']), $this);?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Role</td>
    <td><select name="role" onchange="onChangeRole(this.value)"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_permission'],'selected' => $this->_tpl_vars['user_info']['permission']), $this);?>
</select></td>
  </tr>
  <tr id="user_type_tr" <?php if ($this->_tpl_vars['user_info']['permission'] != 1): ?>style="display:none"<?php endif; ?>>
    <td class="dataLabel">Writer Type</td>
    <td><select name="user_type"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_types'],'selected' => $this->_tpl_vars['user_info']['user_type']), $this);?>
</select></td>
  </tr>
  <tr id="user_type_tradmin" <?php if ($this->_tpl_vars['user_info']['permission'] != 5): ?>style="display:none"<?php endif; ?>>
    <td class="dataLabel">Admin Type</td>
    <td><select name="user_type">
	<option label="Super Admin For Internal" value="1" <?php if ($this->_tpl_vars['user_info']['user_type'] == 1): ?>selected="selected"<?php endif; ?>>Super Admin For Internal User</option>
	<option label="External Admin(Limited)" value="-1" <?php if ($this->_tpl_vars['user_info']['user_type'] == -1): ?>selected="selected"<?php endif; ?>>External Admin(Limited)</option>
	</select></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="dataLabel">Payment Preference</td>
    <td><select name="pay_pref"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['payment_preference'],'selected' => $this->_tpl_vars['user_info']['pay_pref']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="dataLabel">Paypal Email Address</td>
    <td><input name="paypal_email" type="text" id="paypal_email" value="<?php echo $this->_tpl_vars['user_info']['paypal_email']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Google Plus URLs</td>
    <td><input name="googleplus_url" type="text" id="googleplus_url" value="<?php echo $this->_tpl_vars['user_info']['googleplus_url']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" value="Submit" class="button" />&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
</table>
</form>
  </div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>