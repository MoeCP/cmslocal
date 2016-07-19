<?php /* Smarty version 2.6.11, created on 2014-08-22 11:46:08
         compiled from client_campaign/campaign_reporting_bydate.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/campaign_reporting_bydate.html', 19, false),array('function', 'eval', 'client_campaign/campaign_reporting_bydate.html', 87, false),)), $this); ?>
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
<div id="page-box1">
  <h2><?php if ($_GET['baseon'] > 1): ?> <?php echo $this->_tpl_vars['baseones'][$_GET['baseon']]; ?>
 <?php else: ?>Client Approval<?php endif; ?> &nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
 <form name="f_assign_keyword_return" id="f_assign_keyword_return"  action="<?php echo $this->_tpl_vars['actionurl']; ?>
" method="get">
<input type="hidden" name="opt_action" id="opt_action" value="" /> 
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>Client</td>
    <td><select name="client_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_clients'],'selected' => $_GET['client_id']), $this);?>
</select></td>
    <td nowrap>Based on:</td>
    <td><select name="baseon"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['baseones'],'selected' => $_GET['baseon']), $this);?>
</select></td>
    <td nowrap>Date Start</td>
    <td nowrap>
		<input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $_GET['date_start']; ?>
" readonly/>
			<input type="button" class="button" id="btn_cal_date_start" value="...">
			<script type="text/javascript">
			Calendar.setup({
				inputField  : "date_start",
				ifFormat    : "%Y-%m-%d",
				showsTime   : false,
				button      : "btn_cal_date_start",
				singleClick : true,
				step        : 1,
				range       : [1990, 2030]
			});
			</script>
	</td>
    <td nowrap>Date End</td>
    <td nowrap>
		<input type="text" name="date_start_end" id="date_end" size="10" maxlength="10" value="<?php echo $_GET['date_end']; ?>
" readonly/>
			<input type="button" class="button" id="btn_cal_date_end" value="...">
			<script type="text/javascript">
			Calendar.setup({
				inputField  : "date_end",
				ifFormat    : "%Y-%m-%d",
				showsTime   : false,
				button      : "btn_cal_date_end",
				singleClick : true,
				step        : 1,
				range       : [1990, 2030]
			});
			</script>
	</td>
    <td nowrap>Show:</td>
    <td nowrap><select name="perPage" onchange="onsearch('f_assign_keyword_return')"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td colspan="4" nowrap><input type="image" src="/images/button-search.gif" value="submit" onclick="onsearch('f_assign_keyword_return')" />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="exportcsv('f_assign_keyword_return')" /></td>
  </tr>
</table><br>
</form>       
    </div>
  </div>
</div>

<?php if ($_GET['baseon'] > 0): ?>
<div class="tablepadding"> 
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom">Total for <?php echo $this->_tpl_vars['total_rs']; ?>
 items: $<?php echo $this->_tpl_vars['total_amount']; ?>
</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">#</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Client Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Assigned Word Count</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Actual Word Count</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Estimate Money</td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><a href="/client_campaign/completed_keywords.php?is_paid=1&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&month=<?php echo $this->_tpl_vars['month']; ?>
&article_type=<?php echo $this->_tpl_vars['item']['article_type']; ?>
" target="_blank" ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td class="table-right-2" ><?php echo $this->_tpl_vars['item']['assigned_words']; ?>
</td>
    <td class="table-right-2" >0</td>
    <td class="table-right-2" >$<?php echo $this->_tpl_vars['item']['estimate_money']; ?>
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
<?php endif; ?>

<?php echo '
<script type="text/javascript">
//<![CDATA[
function exportcsv(formId){
    $(\'opt_action\').value = \'export\';
}
function onsearch(formId){
    $(\'opt_action\').value = \'\';
    $(formId).submit();
}
//]]>
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>