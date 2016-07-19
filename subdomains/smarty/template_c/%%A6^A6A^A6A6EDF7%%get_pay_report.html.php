<?php /* Smarty version 2.6.11, created on 2014-01-19 00:30:11
         compiled from client_campaign/get_pay_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/get_pay_report.html', 14, false),array('modifier', 'nl2br', 'client_campaign/get_pay_report.html', 33, false),array('modifier', 'strip', 'client_campaign/get_pay_report.html', 33, false),array('modifier', 'escape', 'client_campaign/get_pay_report.html', 33, false),array('function', 'html_options', 'client_campaign/get_pay_report.html', 18, false),)), $this); ?>
  <td class="table-left" >&nbsp;</td>
  <?php if ($this->_tpl_vars['item']['payment_flow_status'] == 'cpc' && false): ?><td class="table-left-2"><input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['user_id']; ?>
" onclick="javascript:checkItem('Select_All', f_acct_flow)" /></td><?php endif; ?>
  <td class="table-left-2" ><?php echo $this->_tpl_vars['index_iteration']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
  <td><?php if ($this->_tpl_vars['item']['status'] == 'A'): ?><label style="color:red" ><?php echo $this->_tpl_vars['user_statuses']['A']; ?>
<label><?php else:  echo $this->_tpl_vars['user_statuses']['D'];  endif; ?></td>
  <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
        $this->_foreach['loop1']['iteration']++;
?>
  <td id='t<?php echo $this->_tpl_vars['key']; ?>
_count_<?php echo $this->_tpl_vars['item']['user_id']; ?>
' ><?php echo $this->_tpl_vars['item'][$this->_tpl_vars['key']]; ?>
</td>
  <?php endforeach; endif; unset($_from); ?>
  <td id='gct_count_<?php echo $this->_tpl_vars['item']['user_id']; ?>
' ><a href="/client_campaign/keyword_adjust.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
&month=<?php echo $this->_tpl_vars['month']; ?>
&role=<?php echo $this->_tpl_vars['role']; ?>
" target="_blank"><font color="red"><?php echo $this->_tpl_vars['item']['gct_count']; ?>
</font></a></td>
  <td id='pay_gct_count_<?php echo $this->_tpl_vars['item']['user_id']; ?>
' ><?php echo $this->_tpl_vars['item']['pay_gct_count']; ?>
</td>
  <td id='pay_article_count_<?php echo $this->_tpl_vars['item']['user_id']; ?>
' ><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pay_count_article'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
  <td>$<?php if ($this->_tpl_vars['item']['payment'] > 0 && $this->_tpl_vars['item']['invoice_status'] || $this->_tpl_vars['item']['payment_flow_status'] == 'paid'):  echo $this->_tpl_vars['item']['payment'];  elseif ($this->_tpl_vars['item']['pay_amount'] > 0):  echo $this->_tpl_vars['item']['pay_amount'];  else: ?>0<?php endif; ?></td>
  <td ><?php if ($this->_tpl_vars['item']['pay_pref'] == ''): ?>n/a<?php else:  echo $this->_tpl_vars['payment_preferences'][$this->_tpl_vars['item']['pay_pref']];  endif; ?></td>
  <td id="status_<?php echo $this->_tpl_vars['item']['user_id']; ?>
" ><?php if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid'):  echo $this->_tpl_vars['item']['payment_flow_status'];  else: ?>Not paid<?php endif; ?></td>
  <td><select name="month_<?php echo $this->_tpl_vars['item']['user_id']; ?>
"  onchange="onMonthChange(this, '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', '0', '<?php echo $this->_tpl_vars['index_iteration']; ?>
')" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select></td>
   <td nowrap id="payment_status_<?php echo $this->_tpl_vars['item']['user_id']; ?>
" class="table-right-2">
     <?php if ($this->_tpl_vars['item']['pay_gct_count'] != 0): ?>
	    <?php if ($this->_tpl_vars['item']['payment_flow_status'] == ''): ?>
	    <input type="button" class="button" value="Confirm First" onclick="doAcctFlow('Confirm with copywriter first?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <input type="button" class="button" value="Approve Now" onclick="doAcctFlow('Approve without copywriter confirmation?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cpc', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'ap'): ?>
	    <font color="red">[Awaiting <?php if ($this->_tpl_vars['role'] == 'editor'): ?>editor<?php else: ?>copywriter<?php endif; ?> confirmation]</font>
	    <input type="button" class="button" value="Confirm again" onclick="doAcctFlow('Send confirmation email to copywriter again?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap',  '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <input type="button" class="button" value="Force Approve" onclick="doAcctFlow('Approve without copywriter confirmation?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cpc', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
      <?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'cpc' && ( $this->_tpl_vars['item']['vendor_id'] > 0 && $this->_tpl_vars['pay_plugin'] == 'NetSuite' || $this->_tpl_vars['item']['qb_vendor_id'] > 0 && $this->_tpl_vars['pay_plugin'] == 'QuickBook' )): ?>
      <input type="button" class="button" value="create bill" onclick="doAcctFlow('create bill <?php echo $this->_tpl_vars['pay_plugin']; ?>
', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cbill', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'cpc' || $this->_tpl_vars['item']['payment_flow_status'] == 'cbill'): ?>
      <input type="button" class="button" value="mark as paid" onclick="doAcctFlow('paid?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'paid', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <?php elseif ($this->_tpl_vars['item']['payment_flow_status'] != 'paid'): ?>
	    <a href="#" target="_self" onMouseOver="return overlib('<table width=300><tr><td nowrap>memo </td></tr><tr><td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['memo'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();">disapprove with explanation</a>
	    <input type="button" class="button" value="re-approve" onclick="doAcctFlow('re-approve?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap','<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <?php endif; ?>
	    <?php if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid' || $this->_tpl_vars['item']['payment_flow_status'] == 'cpc' || $this->_tpl_vars['item']['payment_flow_status'] == 'cbill'): ?>
	    <input type="button" class="button" value="view invoice" onclick="window.open('/client_campaign/cp_invoice.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
&month=<?php echo $this->_tpl_vars['month']; ?>
&role=<?php echo $this->_tpl_vars['role']; ?>
', 'view_invoice',  'status=yes, width=900, height=400,  left=50,  top=50, scrollbars=yes, resizable=yes');">
	    <?php endif; ?>
    <?php endif; ?>
  </td>
  <td class="table-right" >&nbsp;</td>