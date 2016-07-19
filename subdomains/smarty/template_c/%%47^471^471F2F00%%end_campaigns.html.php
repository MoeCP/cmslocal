<?php /* Smarty version 2.6.11, created on 2012-06-18 05:16:03
         compiled from client_campaign/end_campaigns.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eval', 'client_campaign/end_campaigns.html', 50, false),array('modifier', 'default', 'client_campaign/end_campaigns.html', 55, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>
<div id="page-box1">
  <h2>End/Reactive Client Campaign</h2>
  <div id="campaign-search" >
      <div id="campaign-search-box" >
<form name="f_assign_keyword_return" action="" method="get">
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td nowrap>Campaign Search (by campaign name or company name)</td>
    <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
" /></td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="70%">&nbsp;</td>
  </tr>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form name="f_opt_form" id="f_opt_form" action="/client_campaign/end_campaigns.php" method="post">
<input type="hidden" name="status" id="status" value="" />
<input type="hidden" name="campaign_id" id="campaign_id" value="" />
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">#</td>
    <td nowrap class="columnHeadInactiveBlack">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Total Keywords</td>
    <td nowrap class="columnHeadInactiveBlack">% unassigned</td>
    <td nowrap class="columnHeadInactiveBlack">% active unassigned</td>
    <td nowrap class="columnHeadInactiveBlack">% canceled</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
<?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<tr >
  <td class="table-left" >&nbsp;</td>
  <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

  <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['company_name']; ?>
</td>
	<td nowrap><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
	<td><?php echo $this->_tpl_vars['item']['total']; ?>
</td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_unassign'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_unassign']; ?>
)</td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_active'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_active']; ?>
)</td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_canceled'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_canceled']; ?>
)</td>
	<td><?php echo $this->_tpl_vars['item']['date_end']; ?>
</td>
  <td align="right" nowrap class="table-right-2">
  <input type="button" class="button" value="Keywords" onclick="window.open('/client_campaign/keywords.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
  <?php if ($this->_tpl_vars['item']['total_canceled'] > 0): ?>
      <input type="button" class="button" value="Reactived Keywords" onclick="formsubmit('A', <?php echo $this->_tpl_vars['item']['campaign_id']; ?>
)"/>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['item']['total_active'] > 0): ?>
      <input type="button" class="button" value="Cancel All Unassigned Keywords" onclick="formsubmit('D', <?php echo $this->_tpl_vars['item']['campaign_id']; ?>
)"/>
  <?php endif; ?>
  </td>
  <td class="table-right" >&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</form>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
</div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", "CaseInsensitiveString", "Number", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
function formsubmit(status, cid)
{
  var form =$(\'f_opt_form\');
  if (status == \'A\' && confirm(\'Are you sure reactive all keywords?\') || status == \'D\' &&confirm(\'Are you sure canceled unassiged keywords?\'))
  {
        form.status.value = status;
        form.campaign_id.value = cid;
        form.submit();
  }
  return false;
}
'; ?>

//]]>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>