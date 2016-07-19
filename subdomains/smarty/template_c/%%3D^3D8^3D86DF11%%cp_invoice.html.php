<?php /* Smarty version 2.6.11, created on 2013-10-25 02:10:06
         compiled from client_campaign/cp_invoice.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'client_campaign/cp_invoice.html', 62, false),array('modifier', 'date_format', 'client_campaign/cp_invoice.html', 62, false),array('modifier', 'regex_replace', 'client_campaign/cp_invoice.html', 137, false),array('modifier', 'truncate', 'client_campaign/cp_invoice.html', 224, false),array('function', 'html_options', 'client_campaign/cp_invoice.html', 132, false),array('function', 'eval', 'client_campaign/cp_invoice.html', 143, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif;  echo '
<script language="JavaScript">
function check_f_invoice( operation )
{
	var f = document.f_invoice;
	f.operation.value = operation;

	if (operation == \'paid\')
	{
		if (confirm(\'paid?\'))
		{
			f.submit();
		}
	}
	else
	{
		if (operation == \'submit\' || operation == \'print\')
		{
			window.print();
      if (operation == \'print\')
      {
        f.operation.value = \'save\';
      }
		}
		if(f.invoice_no.value==\'\')
		{
			alert("Please input invoice number");
			 f.invoice_no.focus();
			 return false;
		}
	//	if(f.check_no.value==\'\')
	//	{
	//		alert("Please input check number");
	//		 f.check_no.focus();
	//		 return false;
	//	}
	//	if(f.reference_no.value==\'\')
	//	{
	//		 alert("Please input reference number");
	//		 f.reference_no.focus();
	//		 return false;
	//	}
		f.submit();
	}
}
function onInvoiceSearch()
{
  var f = document.invoice_search;
  f.submit();
}
</script>
'; ?>

<div id="page-box1">
  <h2><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['monthes'][$this->_tpl_vars['month']])) ? $this->_run_mod_handler('cat', true, $_tmp, "-01") : smarty_modifier_cat($_tmp, "-01")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%B %Y") : smarty_modifier_date_format($_tmp, "%B %Y")); ?>
 Invoice</h2>
  <div class="form-item" >
<table cellspacing="0" cellpadding="4" align="center" class="even" width="99%">
<form action="" method="post" name="f_invoice" >
    <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
    <input type="hidden" name="role" value="<?php echo $this->_tpl_vars['role']; ?>
" />
    <input type="hidden" name="month" value="<?php echo $this->_tpl_vars['month']; ?>
" />
    <input type="hidden" name="operation" value="" />
    <input type="hidden" name="query_string" value="<?php echo $this->_tpl_vars['query_string']; ?>
" />

        <input type="hidden" name="invoice_status" value="<?php echo $this->_tpl_vars['cp_payment_info']['invoice_status']; ?>
" />
    
    <tr>
    <th colspan="8"  align="left"><strong></strong></th>
    </tr>
    <tr>
    <td class="requiredInput">Copy Writer:</td><td><?php echo $this->_tpl_vars['user_info']['first_name']; ?>
 <?php echo $this->_tpl_vars['user_info']['last_name']; ?>
</td>
    <td class="requiredInput">Invoice Date:</td>
    <td nowrap>
	<input name="invoice_date" type="text" id="invoice_date" readonly value="<?php if ($this->_tpl_vars['cp_payment_info']['invoice_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['cp_payment_info']['invoice_date'] != ''):  echo $this->_tpl_vars['cp_payment_info']['invoice_date'];  else:  echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S"));  endif; ?>" />
	<input type="button" class="button" id="btn_invoice_date" value="...">
	<script type="text/javascript">
	Calendar.setup({
	    inputField  : "invoice_date",
	    ifFormat    : "%Y-%m-%d",
	    showsTime   : false,
	    button      : "btn_invoice_date",
	    singleClick : true,
	    step        : 1,
	    range       : [1990, 2100]
	});
	</script>
    </td>
    <td class="requiredInput">Date Paid:</td>
    <td nowrap>
     <input name="date_pay" type="text" id="date_pay" value="<?php if ($this->_tpl_vars['cp_payment_info']['date_pay'] != '0000-00-00 00:00:00' && $this->_tpl_vars['cp_payment_info']['date_pay'] != ''):  echo $this->_tpl_vars['cp_payment_info']['date_pay'];  else:  echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S"));  endif; ?>" />
	<input type="button" class="button" id="btn_date_pay" value="...">
	<script type="text/javascript">
	Calendar.setup({
	    inputField  : "date_pay",
	    ifFormat    : "%Y-%m-%d",
	    showsTime   : false,
	    button      : "btn_date_pay",
	    singleClick : true,
	    step        : 1,
	    range       : [1990, 2100]
	});
	</script>
     </td>
    </tr>
    <tr>
    <td class="requiredInput">Invoice Number:</td><td><input type="text" name="invoice_no"  value="<?php echo $this->_tpl_vars['cp_payment_info']['user_id']; ?>
-<?php echo $this->_tpl_vars['cp_payment_info']['month_invoice']; ?>
-<?php echo $this->_tpl_vars['cp_payment_info']['month_order']; ?>
" readonly /></td>
    <td class="requiredInput">Check/Billing Pay No.:</td><td><input type="text" name="check_no"  value="<?php echo $this->_tpl_vars['cp_payment_info']['check_no']; ?>
" /></td>
    <td class="requiredInput">Amount Paid:</td><td><input type="text" name="payment"  value="<?php if ($this->_tpl_vars['cp_payment_info']['payment'] > 0):  echo $this->_tpl_vars['cp_payment_info']['payment'];  elseif ($this->_tpl_vars['report']['all']['cost'] > 0):  echo $this->_tpl_vars['report']['all']['cost'];  else: ?>0<?php endif; ?>" <?php if ($this->_tpl_vars['cp_payment_info']['invoice_status'] == 1): ?>readonly<?php endif; ?>/></td>
    </tr>
    <tr>
        <td class="requiredInput">Notes:</td>
        <td colspan="10"><textarea name="notes" id="notes" rows="5" cols="100"><?php echo $this->_tpl_vars['cp_payment_info']['notes']; ?>
</textarea></td>
    </tr>
     <tr>
    <td class="requiredInput">Approved By:</td>
    <td colspan="5" >
	<select name="approved_user"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $this->_tpl_vars['cp_payment_info']['approved_user']), $this);?>
</select>
    </td>
    </tr>
<?php $_from = $this->_tpl_vars['article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
        $this->_foreach['loop']['iteration']++;
?>
    <tr><td class="requiredInput" >Campaign Name:&nbsp;</td>
    <td align="left" ><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/:\d+/", "") : smarty_modifier_regex_replace($_tmp, "/:\d+/", "")); ?>
</strong></td></tr>
	<?php $_from = $this->_tpl_vars['type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item_key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
    <?php if ($this->_tpl_vars['item_key'] != 'num' || $this->_tpl_vars['item_key'] == '0'): ?>
    <?php if ($this->_tpl_vars['item_key']%3 == 0): ?>
    <tr>
    <?php endif; ?>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['item']['campaign_id'],'assign' => 'cid'), $this);?>

    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['item']['article_type'],'assign' => 'atype'), $this);?>

    <td class="requiredInput">
    <input type="hidden" name="campaign_id[]" value="<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" />
    <input type="hidden" name="article_type[]" value="<?php echo $this->_tpl_vars['item']['article_type']; ?>
" />
    <input type="hidden" name="history_id[]" value="<?php echo $this->_tpl_vars['item']['history_id']; ?>
" />
    <input type="hidden" name="article_type_name[]" value="<?php echo $this->_tpl_vars['item']['article_type_name']; ?>
" />
    <input type="hidden" name="total_article[]" value="<?php if ($this->_tpl_vars['item']['total_article'] > 0):  echo $this->_tpl_vars['item']['total_article'];  else:  echo $this->_tpl_vars['campaign_costs'][$this->_tpl_vars['cid']][$this->_tpl_vars['atype']]['num'];  endif; ?>" />
    <input type="hidden" name="total_cost[]" value="<?php if ($this->_tpl_vars['item']['total_cost'] > 0):  echo $this->_tpl_vars['item']['total_cost'];  else:  echo $this->_tpl_vars['campaign_costs'][$this->_tpl_vars['cid']][$this->_tpl_vars['atype']]['cost'];  endif; ?>" />
      <input type="hidden" name="cost_id[]" value="<?php echo $this->_tpl_vars['item']['cost_id']; ?>
" />
    <?php echo $this->_tpl_vars['item']['article_type_name']; ?>
 Article Cost per <?php if ($this->_tpl_vars['item']['checked'] == 1): ?>Article<?php else: ?>Word<?php endif; ?>:
    </td>
    <td>
        $<?php echo $this->_tpl_vars['item']['cost_per_unit']; ?>

    <input type="hidden" name="type_cost[]"  value="<?php echo $this->_tpl_vars['item']['cost_per_article']; ?>
" />
        </td>
    <?php if ($this->_tpl_vars['item_key']%3 == 2 || $this->_tpl_vars['item_key']+1 == $this->_tpl_vars['type']['num']): ?>
    </tr>
    <?php endif; ?>
    <?php endif; ?>
	<?php endforeach; endif; unset($_from);  endforeach; endif; unset($_from); ?>
    <tr>
    <td >
    &nbsp;
    </td>
    <td colspan="2" align="center" >
	    <input type="button" name="save" class="button" value="Save Invoice" onclick="check_f_invoice('save')"/>
    </td>
    <td align="center" >&nbsp;
    </td>
    <td colspan="2" align="center" >
	    <input type="button" name="print" class="button" value="Print Invoice" onclick="check_f_invoice('print')" />
    </td>
    </tr>
    </form>
<!search invoice by month//-->
    <form name="invoice_search"  action="" method="get"  >
    <input type="hidden" name="user_id"  value="<?php echo $this->_tpl_vars['cp_payment_info']['user_id']; ?>
"/>
    <input type="hidden" name="role"  value="<?php echo $this->_tpl_vars['role']; ?>
"/>
     <tr>
     <td class="requiredInput">Please Select Month:&nbsp;</td>
    <td colspan="3" align="left" ><select name="month" onchange="onInvoiceSearch()" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select></td>
    </tr>
    </form>
   <!End//-->
</table>
  </div>
</div>
<div class="tablepadding"> 
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "client_campaign/type_report.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable" width="100%">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Article Title</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Cost</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['keyword']; ?>
</td>
    <td><a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['item']['article_number']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['title']; ?>
</td>
    <td><?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['article_type_name']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td class="table-right-2"><?php if ($this->_tpl_vars['user_info']['user_type'] == '1' || $this->_tpl_vars['cp_payment_info']['payment'] > 0):  echo $this->_tpl_vars['item']['cost_for_article'];  else: ?>0<?php endif; ?></td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </tbody>
</table>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "client_campaign/type_report.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(1);
'; ?>

//]]>
</script>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>