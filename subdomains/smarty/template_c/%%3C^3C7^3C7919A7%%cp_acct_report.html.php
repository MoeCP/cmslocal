<?php /* Smarty version 2.6.11, created on 2016-04-29 09:33:26
         compiled from client_campaign/cp_acct_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_acct_report.html', 61, false),array('function', 'eval', 'client_campaign/cp_acct_report.html', 117, false),array('modifier', 'date_format', 'client_campaign/cp_acct_report.html', 97, false),array('modifier', 'nl2br', 'client_campaign/cp_acct_report.html', 150, false),array('modifier', 'strip', 'client_campaign/cp_acct_report.html', 150, false),array('modifier', 'escape', 'client_campaign/cp_acct_report.html', 150, false),)), $this); ?>
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
<script language="JavaScript">
<!--
var acctrole = '<?php echo $this->_tpl_vars['role']; ?>
';
<?php echo '
function  doAcctFlow(hint, user_id, flow_status, month, vendor_id) {
  if (confirm(hint))
  {
      var role= acctrole;
      f = document.f_acct_flow;
      f.user_id.value = user_id;
      f.payment_flow_status.value = flow_status;
      f.month.value = month;
      f.vendor_id.value = vendor_id;
      ajaxSubmit(\'/client_campaign/status_change.php\', \'f_acct_flow\', \'payment_status_\'+user_id, \'post\');
  }
}
function onMonthChange(o, user_id, count, index)
{
	var f = document.f_acct_flow;
	var month=o.options[o.selectedIndex].value;
  var role= acctrole;
	if( count==0 )
	{
    ajaxAction(\'/client_campaign/get_pay_report.php?user_id=\'+user_id+\'&month=\'+month+\'&user_type=\' + role + \'&index=\' + index, \'tr\'+user_id);
	}
	else if  ( user_info[0]==\'\' || user_info[0]==null)
	{
		count++;
		setTimeout(\'onMonthChange( \' +o+ \', \'+user_id+\', \' +count +\',\' + index +\')\', 250);
	}
} 
//-->
</script>
'; ?>

<div id="page-box1">
  <h2>
  <?php if ($this->_tpl_vars['role'] == 'editor'): ?>Editor<?php else: ?>Copywriter<?php endif; ?> Accounting List &nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div>
  <a href="/client_campaign/batch_acct_report.php?month=<?php echo $this->_tpl_vars['month']; ?>
&status=<?php echo $this->_tpl_vars['user_status']; ?>
&user_type=<?php echo $this->_tpl_vars['role']; ?>
&bstatus=">Batch Confirm First</a>&nbsp;&nbsp;
  <a href="/client_campaign/batch_acct_report.php?month=<?php echo $this->_tpl_vars['month']; ?>
&status=<?php echo $this->_tpl_vars['user_status']; ?>
&user_type=<?php echo $this->_tpl_vars['role']; ?>
&bstatus=ap">Batch Force Approve</a>&nbsp;&nbsp;
  <a href="/client_campaign/batch_acct_report.php?month=<?php echo $this->_tpl_vars['month']; ?>
&status=<?php echo $this->_tpl_vars['user_status']; ?>
&user_type=<?php echo $this->_tpl_vars['role']; ?>
&bstatus=cpc">Batch Mark as Paid</a>&nbsp;&nbsp;
  </div>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
 <form name="f_assign_keyword_return" id="f_assign_keyword_return"  action="<?php echo $this->_tpl_vars['actionurl']; ?>
" method="get">
<input type="hidden" name="user_type" value="<?php echo $this->_tpl_vars['role']; ?>
" /> 
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>User Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
"></td>
    <td nowrap>Campaign</td>
    <td><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaign_list'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
    <td nowrap>Show:</td>
    <td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td nowrap>Month:</td>
    <td><select name="month" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select></td>
    <td nowrap>User Status:</td>
    <td><select name="status" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users_status'],'selected' => $this->_tpl_vars['user_status']), $this);?>
</select></td>
    <td nowrap><input type="checkbox" name="show_all" id="show_all" onclick="this.form.submit();"  <?php if ($_GET['show_all'] == 'on'): ?> checked <?php endif; ?>/><label>Show All <?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>Copywriters<?php else: ?>Editor<?php endif; ?></label></td>
    <td colspan="4"><input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='<?php echo $this->_tpl_vars['actionurl']; ?>
'" />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="exportcsv('f_assign_keyword_return')" /></td>
  </tr>
</table><br>
</form>       
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="" method="post"  name="f_acct_flow" id="f_acct_flow" >
  <input type="hidden" name="user_id" value="">
  <input type="hidden" name="payment_flow_status" value="">
  <input type="hidden" name="article_ids" value="">
  <input type="hidden" name="month" value="">
  <input type="hidden" name="vendor_id" value=""/>
  <input type="hidden" name="role" id="role"  value="<?php echo $this->_tpl_vars['role']; ?>
">
</form>
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">#</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">User Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">First Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Last Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Email</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Paypal Email</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">INTL</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Status</td>
    <td nowrap class="columnHeadInactiveBlack" colspan="<?php echo $this->_tpl_vars['total_type']+1; ?>
" align="center">Total Client Approved Words &nbsp;/&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['now'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%B, %Y") : smarty_modifier_date_format($_tmp, "%B, %Y")); ?>
</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Words Total</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Articles Total</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Amount</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Payment Preference</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Payment Status</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">&nbsp;</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2" rowspan="2">&nbsp;</td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <tr class="sortableTab">
    <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop2']['iteration']++;
?>
    <td class="columnHeadInactiveBlack" nowrap><?php echo $this->_tpl_vars['item']; ?>
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
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['paypal_email']; ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['country'] == 'United States of America'):  else: ?> X <?php endif; ?></td>
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
    <td>$<?php if ($this->_tpl_vars['item']['payment'] > 0 || $this->_tpl_vars['item']['payment_flow_status'] == 'paid'):  echo $this->_tpl_vars['item']['payment'];  elseif ($this->_tpl_vars['item']['pay_amount'] > 0):  echo $this->_tpl_vars['item']['pay_amount'];  else: ?>0<?php endif; ?></td>
    <td ><?php if ($this->_tpl_vars['item']['pay_pref'] == ''): ?>n/a<?php else:  echo $this->_tpl_vars['payment_preferences'][$this->_tpl_vars['item']['pay_pref']];  endif; ?></td>
    <td id="status_<?php echo $this->_tpl_vars['item']['user_id']; ?>
" ><?php if ($this->_tpl_vars['item']['payment_flow_status'] == 'paid'):  echo $this->_tpl_vars['item']['payment_flow_status'];  else: ?>Not paid<?php endif; ?></td>
    <td><select name="month_<?php echo $this->_tpl_vars['item']['user_id']; ?>
"  onchange="onMonthChange(this, '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', '0', '<?php echo $this->_foreach['loop']['iteration']; ?>
')" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['item']['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select></td>
     <td nowrap id="payment_status_<?php echo $this->_tpl_vars['item']['user_id']; ?>
"  class="table-right-2">
     <?php if ($this->_tpl_vars['item']['pay_gct_count'] != 0 || $this->_tpl_vars['item']['pay_count_article']): ?>
	    <?php if ($this->_tpl_vars['item']['payment_flow_status'] == ''): ?>
	    <input type="button" class="button" value="Confirm First" onclick="doAcctFlow('Confirm with <?php echo $this->_tpl_vars['role']; ?>
 first?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <input type="button" class="button" value="Approve Now" onclick="doAcctFlow('Approve without <?php echo $this->_tpl_vars['role']; ?>
 confirmation?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'cpc', '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <?php elseif ($this->_tpl_vars['item']['payment_flow_status'] == 'ap'): ?>
	    <font color="red">[Awaiting <?php if ($this->_tpl_vars['role'] == 'editor'): ?>editor<?php else: ?>copywriter<?php endif; ?> confirmation]</font>
	    <input type="button" class="button" value="Confirm again" onclick="doAcctFlow('Send confirmation email to <?php echo $this->_tpl_vars['role']; ?>
 again?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'ap',  '<?php echo $this->_tpl_vars['month']; ?>
', '<?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuite'):  echo $this->_tpl_vars['item']['vendor_id'];  else:  echo $this->_tpl_vars['item']['qb_vendor_id'];  endif; ?>');">
	    <input type="button" class="button" value="Force Approve" onclick="doAcctFlow('Approve without <?php echo $this->_tpl_vars['role']; ?>
 confirmation?', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
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
</div>
<?php echo '
<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", "CaseInsensitiveString",  "CaseInsensitiveString", "CaseInsensitiveString"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
function exportcsv(formId)
{
    $(formId).action = \'/client_campaign/acct_export.php\';
}
//]]>
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>