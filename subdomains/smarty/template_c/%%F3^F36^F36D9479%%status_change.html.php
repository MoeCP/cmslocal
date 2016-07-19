<?php /* Smarty version 2.6.11, created on 2012-03-08 11:43:12
         compiled from client_campaign/status_change.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'client_campaign/status_change.html', 23, false),array('modifier', 'strip', 'client_campaign/status_change.html', 23, false),array('modifier', 'escape', 'client_campaign/status_change.html', 23, false),)), $this); ?>
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
</script>
<?php endif;  if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid'): ?>
<script language="JavaScript">
$('status_' + <?php echo $this->_tpl_vars['item']['user_id']; ?>
).innerHTML = 'paid';
</script>
<?php endif;  if ($this->_tpl_vars['item']['payment_flow_status'] == ''): ?>
<input type="button" class="button" value="Confirm First" onclick="doAcctFlow('Confirm with <?php echo $this->_tpl_vars['role']; ?>
 first?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap', '<?php echo $this->_tpl_vars['item']['month']; ?>
', '<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<input type="button" class="button" value="Approve Now" onclick="doAcctFlow('Approve without <?php echo $this->_tpl_vars['role']; ?>
 confirmation?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cpc', '<?php echo $this->_tpl_vars['item']['month']; ?>
', '<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'ap'): ?>
<font color="red">[Awaiting <?php if ($this->_tpl_vars['role'] == 'editor'): ?>editor<?php else: ?>copywriter<?php endif; ?> confirmation]</font>
<input type="button" class="button" value="Confirm again" onclick="doAcctFlow('Send confirmation email to <?php echo $this->_tpl_vars['role']; ?>
 again?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap',  '<?php echo $this->_tpl_vars['item']['month']; ?>
','<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<input type="button" class="button" value="Force Approve" onclick="doAcctFlow('Approve without <?php echo $this->_tpl_vars['role']; ?>
 confirmation?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cpc', '<?php echo $this->_tpl_vars['item']['month']; ?>
', '<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'cpc' && $this->_tpl_vars['item']['vendor_id'] > 0 && ( $this->_tpl_vars['pay_plugin'] == 'NetSuite' || $this->_tpl_vars['pay_plugin'] == 'QuickBook' )): ?>
<input type="button" class="button" value="create bill" onclick="doAcctFlow('create bill <?php echo $this->_tpl_vars['pay_plugin']; ?>
?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cbill', '<?php echo $this->_tpl_vars['item']['month']; ?>
', '<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'cpc' || $this->_tpl_vars['item']['payment_flow_status'] == 'cbill'): ?>
<input type="button" class="button" value="mark as paid" onclick="doAcctFlow('paid?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'paid', '<?php echo $this->_tpl_vars['item']['month']; ?>
', '<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<?php elseif ($this->_tpl_vars['item']['payment_flow_status'] != 'paid'): ?>
<a href="#" target="_self" onMouseOver="return overlib('<table width=300><tr><td nowrap>memo </td></tr><tr><td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['memo'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();">disapprove with explanation</a>
<input type="button" class="button" value="re-approve" onclick="doAcctFlow('re-approve?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap','<?php echo $this->_tpl_vars['item']['month']; ?>
', '<?php echo $this->_tpl_vars['item']['vendor_id']; ?>
');">
<?php endif;  if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid' || $this->_tpl_vars['item']['payment_flow_status'] == 'cpc' || $this->_tpl_vars['item']['payment_flow_status'] == 'cbill'): ?>
<input type="button" class="button" value="view invoice" onclick="window.open('/client_campaign/cp_invoice.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
&month=<?php echo $this->_tpl_vars['item']['month']; ?>
&role=<?php echo $this->_tpl_vars['item']['role']; ?>
', 'view_invoice',  'status=yes, width=900, height=400,  left=50,  top=50, scrollbars=yes, resizable=yes');">
<?php endif;  echo $this->_tpl_vars['adodb_log']; ?>