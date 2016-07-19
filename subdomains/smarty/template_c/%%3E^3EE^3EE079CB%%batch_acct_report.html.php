<?php /* Smarty version 2.6.11, created on 2012-03-19 08:51:00
         compiled from client_campaign/batch_acct_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/batch_acct_report.html', 46, false),array('modifier', 'date_format', 'client_campaign/batch_acct_report.html', 74, false),)), $this); ?>
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
<?php endif;  echo '
<script language="JavaScript">
<!--
function batchAcctFlow(flow_status)
{
  var f = document.f_acct_flow;
  if (flow_status == \'paid\' && !confirm(\'Are you sure pay all users you choose?\')) {
      return false;
  }

  if (flow_status == \'ap\' && !confirm(\'Are you sure confirm all users you choose?\')) {
      return false;
  }

  if (flow_status == \'cpc\' && !confirm(\'Are you sure approve without user confirmation for all users you choose?\')) {
      return false;
  }
  f.flow_status.value = flow_status;
  ajaxSubmit(\'/client_campaign/batch_change.php\', \'f_acct_flow\', \'show_status\', \'post\');
  Element.show(\'show_shape_end\');
}
//-->
</script>
'; ?>

<div id="page-box1">
  <h2><?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>Copywriter<?php else: ?>Editor<?php endif; ?> <?php if ($this->_tpl_vars['bstatus'] == ''): ?> Batch Confirm First and Approve Now <?php elseif ($this->_tpl_vars['bstatus'] == 'ap'): ?>Batch Force Approve<?php else: ?>Batch Mark as Paid<?php endif; ?>&nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div><a href="/client_campaign/cp_acct_report.php?month=<?php echo $this->_tpl_vars['month']; ?>
&status=<?php echo $this->_tpl_vars['user_status']; ?>
&user_type=<?php echo $this->_tpl_vars['role']; ?>
"><?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>Copywriter<?php else: ?>Editor<?php endif; ?> Accounting List</a></div>
  <div id="campaign-search" >
    <strong></strong>
    <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return"  action="<?php echo $this->_tpl_vars['actionurl']; ?>
" method="get">
<input type="hidden" name="user_type" value="<?php echo $this->_tpl_vars['role']; ?>
" /> 
<input type="hidden" name="bstatus" value="<?php echo $this->_tpl_vars['bstatus']; ?>
" /> 
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td  nowrap>Month:</td>
    <td><select name="month" id="cmonth" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select></td>
    <td  nowrap>User Status:</td>
    <td><select name="status" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users_status'],'selected' => $this->_tpl_vars['user_status']), $this);?>
</select></td>
  </tr>
</table><br>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="<?php echo $this->_tpl_vars['actionurl']; ?>
" method="post"  name="f_acct_flow" id="f_acct_flow" >
<input type="hidden" name="flow_status" value="" />
<input type="hidden" name="month" value="<?php echo $this->_tpl_vars['month']; ?>
" />
<input type="hidden" name="role" id="role" value="<?php echo $this->_tpl_vars['role']; ?>
" /> 
<input type="hidden" name="bstatus" value="<?php echo $this->_tpl_vars['bstatus']; ?>
" /> 
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner"  rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">
      <?php if ($this->_tpl_vars['total'] > 0): ?><input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" /><?php endif; ?>
    </td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">#</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">User Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">First Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Last Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Email</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Status</td>
    <td nowrap class="columnHeadInactiveBlack" colspan="<?php echo $this->_tpl_vars['total_type']+1; ?>
" align="center">Total Client Approved Words &nbsp;/&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['now'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%B, %Y") : smarty_modifier_date_format($_tmp, "%B, %Y")); ?>
</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Words Total</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Articles Total</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Amount</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Payment Preference</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Payment Status</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2" rowspan="2">&nbsp;</td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <tr class="sortableTab">
    <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop2']['iteration']++;
?>
    <td class="columnHeadInactiveBlack"><?php echo $this->_tpl_vars['item']; ?>
</td>
    <?php endforeach; endif; unset($_from); ?>
    <td class="columnHeadInactiveBlack" >Total</td>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" id="tr<?php echo $this->_tpl_vars['item']['user_id']; ?>
" >
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2">
      <?php if ($this->_tpl_vars['item']['payment_flow_status'] != 'dwe' && ( $this->_tpl_vars['item']['pay_gct_count'] > 0 || $this->_tpl_vars['item']['pay_count_article'] > 0 )): ?>
      <input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['user_id']; ?>
" onclick="javascript:checkItem('Select_All', f_acct_flow)" />
      <?php endif; ?>
    </td>
    <td><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['status'] == 'A'): ?><label style="color:red" ><?php echo $this->_tpl_vars['users_status']['A']; ?>
<label><?php else:  echo $this->_tpl_vars['users_status']['D'];  endif; ?></td>
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
' ><?php echo $this->_tpl_vars['item']['pay_count_article']; ?>
</td>
    <td>$<?php if ($this->_tpl_vars['item']['payment'] > 0):  echo $this->_tpl_vars['item']['payment'];  elseif ($this->_tpl_vars['item']['pay_amount'] > 0):  echo $this->_tpl_vars['item']['pay_amount'];  else: ?>0<?php endif; ?></td>
    <td ><?php if ($this->_tpl_vars['item']['pay_pref'] == ''): ?>n/a<?php else:  echo $this->_tpl_vars['payment_preferences'][$this->_tpl_vars['item']['pay_pref']];  endif; ?></td>
    <td id="status_<?php echo $this->_tpl_vars['item']['user_id']; ?>
" ><?php if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid'):  echo $this->_tpl_vars['item']['payment_flow_status'];  else: ?>Not paid<?php endif; ?></td>
    <td class="table-right-2"><?php if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid' || $this->_tpl_vars['item']['payment_flow_status'] == 'cpc' || $this->_tpl_vars['item']['payment_flow_status'] == 'cbill'): ?>
	    <input type="button" class="button" value="view invoice" onclick="window.open('/client_campaign/cp_invoice.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
&month=<?php echo $this->_tpl_vars['item']['month']; ?>
&role=<?php echo $this->_tpl_vars['role']; ?>
', 'view_invoice',  'status=yes, width=900, height=400,  left=50,  top=50, scrollbars=yes, resizable=yes');">
	    <?php endif; ?></td>
      <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<?php if ($this->_tpl_vars['total'] > 0): ?>
<table align="center">
  <tr>
    <td align="center" >
      <?php if ($this->_tpl_vars['bstatus'] == ''): ?>
        <input type="button" class="button" value="Confirm First" onclick="batchAcctFlow('ap')" />&nbsp;&nbsp;
        <input type="button" class="button" value="Approve Now" onclick="batchAcctFlow('cpc')" />&nbsp;&nbsp;
      <?php elseif ($this->_tpl_vars['bstatus'] == 'ap'): ?>
        <input type="button" class="button" value="Force Approve" onclick="batchAcctFlow('cpc')" />&nbsp;&nbsp;
      <?php else: ?>
        <input type="button" class="button" value="mark as paid" onclick="batchAcctFlow('paid')" />
      <?php endif; ?>
    </td>
   </tr>
   <tr><td align="center"><div id="show_shape_end" class="corner" style="display:none;width:310px;z-index:1000;height: 30px;" >
  <div class="ricohint" style="width:310px;z-index:1000;" id="show_status"  align="center" >saving...</div></div></td></tr>
</table>
<?php endif; ?>
</form>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>