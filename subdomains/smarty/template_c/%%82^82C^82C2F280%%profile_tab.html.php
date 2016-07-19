<?php /* Smarty version 2.6.11, created on 2013-10-18 10:47:14
         compiled from user/profile_tab.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'user/profile_tab.html', 32, false),array('modifier', 'default', 'user/profile_tab.html', 36, false),array('modifier', 'nl2br', 'user/profile_tab.html', 46, false),)), $this); ?>
<div class="page-box1-class">
<table width="100%"  cellspacing="1" cellpadding="4"><tr><td align="left">
<h2>User's Information&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_tpl_vars['login_permission'] == 5): ?><input type="button" class="button" value="Edit" onclick="showUserDialog()" /><?php endif;  if ($this->_tpl_vars['user_info']['role'] == 'copy writer' && ( $this->_tpl_vars['login_role'] == 'admin' || $this->_tpl_vars['login_role'] == 'copy writer' ) && $this->_tpl_vars['login_permission'] == 5): ?>&nbsp;&nbsp<input type="button" class="button" value="Upload Photo" onclick="window.location.href='/user/profile_photo.php?user_id=<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
'" /><?php endif; ?></h2></td><td align="right" ><?php if ($this->_tpl_vars['user_info']['photo'] != ''): ?><img alt="Thumbnail Image" src="<?php echo $this->_tpl_vars['user_info']['photo']; ?>
"><?php endif; ?></td></table>
</div>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
<?php if ($this->_tpl_vars['candidate']['candidate_id'] > 0): ?>
  <tr>
	<td colspan="2" ><?php if ($this->_tpl_vars['candidate']['work_in_us'] == 1): ?>You are authorized to work in the United States<?php else: ?>You are not authorized to work in the United States<?php endif; ?></td>
  </tr>
<?php endif; ?>
  <tr>
    <td valign="top" >
    <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="sortableTable" >
      <tr class="odd">
        <th>User Name</th>
        <td><?php echo $this->_tpl_vars['user_info']['user_name']; ?>
</td>
      </tr>
      <tr class="even" >
        <th>First Name</th>
        <td><?php echo $this->_tpl_vars['user_info']['first_name']; ?>
</td>
      </tr>
      <tr class="odd">
        <th>Last Name</th>
        <td><?php echo $this->_tpl_vars['user_info']['last_name']; ?>
</td>
      </tr>
      <tr class="even">
        <th>E-mail Address</th>
        <td><?php echo $this->_tpl_vars['user_info']['email']; ?>
</td>
      </tr>
      <tr class="odd">
        <th>Birthday</th>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['birthday'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
      </tr>
      <tr class="even">
        <th>Main Number</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['phone'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <tr class="odd">
        <th>Cell Number</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['cell_phone'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
            <tr class="even">
        <th>Address</th>
        <td colspan="3" >
          Address1: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_info']['address'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<br />
          Address2: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_info']['address2'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<br />
          City: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_info']['city'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<br />
          State/Province: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_info']['state'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<br />
          Zipcode: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['user_info']['zip'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<br />
        </td>
      </tr>
      <tr class="odd">
        <th>Country</th>
        <td ><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['country'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <?php if ($this->_tpl_vars['user_info']['first_language']): ?>
      <tr class="even">
        <th>Your first language</th>
        <td><?php echo $this->_tpl_vars['user_info']['first_language']; ?>
</td>
      </tr>
      <?php endif; ?>
                </table>
    </td>
    <td valign="top">
    <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="sortableTable" >
      <?php if ($this->_tpl_vars['login_permission'] == 5 || $this->_tpl_vars['login_permission'] == 4): ?>
      <?php if ($this->_tpl_vars['login_permission'] == 5): ?>
      <tr class="even">
        <th>Pay Level</th>
        <td><?php if ($this->_tpl_vars['user_info']['pay_level'] > 0):  echo $this->_tpl_vars['user_info']['pay_level'];  else: ?>n/a<?php endif; ?></td>
      </tr>
      <?php endif; ?>
      <tr class="odd">
        <th>Password</th>
        <td><?php echo $this->_tpl_vars['user_info']['user_pw']; ?>
</td>
      </tr>
      <tr class="even">
        <th>Role</th>
        <td><?php echo $this->_tpl_vars['user_info']['role'];  if ($this->_tpl_vars['user_info']['role'] == 'copy writer'): ?>(<?php echo $this->_tpl_vars['user_types'][$this->_tpl_vars['user_info']['user_type']]; ?>
)<?php endif; ?></td>
      </tr>
      <?php endif; ?>
      <tr class="odd" >
        <th>Gender</th>
        <td><?php echo $this->_tpl_vars['user_info']['sex']; ?>
</td>
      </tr>
      <tr class="even">
        <th>Hire Date</th>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['date_join'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
      </tr>
      <tr class="odd">
        <th>SSN</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['hide_ssn'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <tr class="even">
        <th>Payment Preference</th>
        <td><?php echo $this->_tpl_vars['payment_preference'][$this->_tpl_vars['user_info']['pay_pref']]; ?>
</td>
      </tr>
      <?php if ($this->_tpl_vars['user_info']['pay_pref'] == 2): ?>
      <tr class="odd">
        <th>Bank Name</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['bank_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <tr class="even">
        <th>Bank Rounting Number</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['hide_routing_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <tr class="odd">
        <th>Account Number</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['hide_bank_info'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <tr class="even">
        <th>Bank Account Type</th>
        <td><?php echo $this->_tpl_vars['acct_types'][$this->_tpl_vars['user_info']['bank_acct_type']]; ?>
</td>
      </tr>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['user_info']['pay_pref'] == 3): ?>
      <tr class="odd">
        <th>Paypal Email Address</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['paypal_email'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
      </tr>
      <?php endif; ?>
          </table>
    </td>
  </tr>
  </table>