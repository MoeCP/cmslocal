<?php /* Smarty version 2.6.11, created on 2013-07-10 08:45:55
         compiled from client_campaign/campaigns.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/campaigns.html', 20, false),array('function', 'eval', 'client_campaign/campaigns.html', 65, false),array('modifier', 'default', 'client_campaign/campaigns.html', 72, false),)), $this); ?>
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
  <h2><?php if ($this->_tpl_vars['is_view_all']): ?>Client Campaign Report<?php else: ?>Client Campaign Setting<?php endif; ?></h2>
  <div id="campaign-search" >
      <div id="campaign-search-box" >
<form name="f_assign_keyword_return" action="" method="get">
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td nowrap>Campaign Search (by campaign name or company name)</td>
    <td><input type="text" name="keyword" id="search_keyword"></td>
    <?php if ($this->_tpl_vars['user_permission_int'] > 3): ?>
    <td   nowrap>Campaign Status:</td>
    <td><select name="archived"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['archived_status'],'selected' => $_GET['archived']), $this);?>
</select></td>
    <td   nowrap>Client Agency:</td>
    <td><select name="agency"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_agency'],'selected' => $_GET['agency']), $this);?>
</select></td>
    <?php endif; ?>
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
<form name="f_archive_form" id="f_archive_form" action="/client_campaign/campaigns.php" method="post">
<input type="hidden" name="status" id="status" value="" />
<input type="hidden" name="campaign_id" id="campaign_id" value="" />
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">#</td>
    <td nowrap class="columnHeadInactiveBlack">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Type</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign ID</td>
    <td nowrap class="columnHeadInactiveBlack">Total Keywords</td>
    <td nowrap class="columnHeadInactiveBlack">% assigned</td>
    <td nowrap class="columnHeadInactiveBlack">% submitted</td>
    <td nowrap class="columnHeadInactiveBlack">% Editor approved</td>
    <td nowrap class="columnHeadInactiveBlack">% client Approved</td>
    <td nowrap class="columnHeadInactiveBlack">% delivered</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Days Past Due/Completed Date</td>
    <?php if ($this->_tpl_vars['is_home']): ?>
    <td nowrap class="columnHeadInactiveBlack">Submitted Today</td>
    <td nowrap class="columnHeadInactiveBlack">Client Approved(Month)</td>
    <?php endif; ?>
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
	<td nowrap><?php if ($this->_tpl_vars['item']['campaign_type'] == 1): ?><a href="/client_campaign/keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a><?php else: ?><a href="/client_campaign/image_keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a><?php endif; ?></td>
	<td><?php echo $this->_tpl_vars['campaign_type'][$this->_tpl_vars['item']['campaign_type']]; ?>
</td>
	<td><?php echo $this->_tpl_vars['item']['campaign_id']; ?>
</td>
	<td><?php echo $this->_tpl_vars['item']['total']; ?>
</td>
	<td ><div <?php if ($this->_tpl_vars['item']['old_assigned']): ?>class="greenclass"<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_assign'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_assign']; ?>
)</div></td>
	<td ><div <?php if ($this->_tpl_vars['item']['old_submitted']): ?>class="yellowclass"<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_submit']; ?>
)</div></td>
	<td ><div <?php if ($this->_tpl_vars['item']['old_approved']): ?>class="redclass"<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_editor_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_editor_approval']; ?>
)</div></td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_client_approval']; ?>
)</td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_delivered'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo $this->_tpl_vars['item']['total_delivered']; ?>
)</td>
	<td><?php echo $this->_tpl_vars['item']['date_end']; ?>
</td>
  <?php if ($this->_tpl_vars['item']['archived'] == 1): ?>
  <td><?php echo $this->_tpl_vars['item']['completed_date']; ?>
</td>
  <?php else: ?>
	<td><?php if ($this->_tpl_vars['item']['total_assign'] > $this->_tpl_vars['item']['total_client_approval'] && $this->_tpl_vars['item']['past_days'] > 0):  echo $this->_tpl_vars['item']['past_days'];  endif; ?></td>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['is_home']): ?>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['today_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['month_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
  <?php endif; ?>
  <td align="right" nowrap class="table-right-2" id="tdaction<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" >
  <span id="span<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" >
  <?php if ($this->_tpl_vars['item']['archived'] == 1): ?>
      <strong>Archived</strong>
  <?php else: ?>
      <input type="button" class="button" value="Archive" onclick="formsubmit(1, <?php echo $this->_tpl_vars['item']['campaign_id']; ?>
,<?php if (( $this->_tpl_vars['item']['total_assign'] == $this->_tpl_vars['item']['total_client_approval'] )): ?>1<?php else: ?>0<?php endif; ?>)"/>
  <?php endif; ?>
  </span>
  <?php if ($this->_tpl_vars['is_view_all']): ?>
      <input type="button" class="button" value="Editorial Notes" onclick="openWindow('/client_campaign/campaign_notes.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=485,width=550,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=yes');"/>
      <input type="button" class="button" value="Update" onclick="window.open('/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');"/>
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
function formsubmit(status, cid, completed)
{
    var form = $(\'f_archive_form\');
    form.status.value = status;
    form.campaign_id.value = cid;
    if (completed == 0)
    {
        if (!confirm(\'This campaign is not completed. Are you sure set it as archived?\'))
        {
            return false;
        }
    }
    eid = \'span\'+cid;
    archiveCampaign(eid, \'f_archive_form\');
}
'; ?>

//]]>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>