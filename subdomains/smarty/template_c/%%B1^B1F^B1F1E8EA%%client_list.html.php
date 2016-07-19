<?php /* Smarty version 2.6.11, created on 2014-02-17 05:18:24
         compiled from client_campaign/client_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/client_list.html', 21, false),array('modifier', 'default', 'client_campaign/client_list.html', 23, false),)), $this); ?>
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
<div style="display:none" id="report_result" ></div>
<div id="page-box1">
  <h2><?php if ($this->_tpl_vars['archived'] == 1): ?>Archived <?php endif; ?>Client Campaigns</h2>
  <div id="campaign-search" >
      <div id="campaign-search-box" >
        <form name="f_assign_keyword_return" action="/client_campaign/client_list.php" method="get">
        <table border="0" cellspacing="1" cellpadding="4">
          <tr>
            <td nowrap>Campaign Search (by campaign name or company name)</td>
            <td><input type="text" name="keyword" id="search_keyword"></td>
            <td   nowrap>Campaign Status:</td>
            <td><select name="archived"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['archived_status'],'selected' => $_GET['archived']), $this);?>
</select></td>
            <td   nowrap>Client Status:</td>
            <td><select name="status"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['total_status'],'selected' => ((is_array($_tmp=@$_GET['status'])) ? $this->_run_mod_handler('default', true, $_tmp, 'A') : smarty_modifier_default($_tmp, 'A'))), $this);?>
</select></td>
            <?php if ($this->_tpl_vars['user_permission_int'] > 3): ?>
            <td   nowrap>Client Agency:</td>
            <td><select name="agency"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_agency'],'selected' => $_GET['agency']), $this);?>
</select></td>
            <?php endif; ?>
            <td ><input type="image" src="/images/button-search.gif" value="submit" /></td>
            <td nowrap>&nbsp; </td>
            <td width="70%">&nbsp;</td>
          </tr>
          </form>
        </table>
        </form>
      </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Company Name</td>
<?php if ($this->_tpl_vars['is_show']): ?>
	<td nowrap class="columnHeadInactiveBlack">Total spend so far</td>
<?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Total Campaigns in All Reports</td>
    <td nowrap class="columnHeadInactiveBlack">Total Keywords</td>
    <td nowrap class="columnHeadInactiveBlack">% assigned</td>
    <td nowrap class="columnHeadInactiveBlack">% submitted</td>
    <td nowrap class="columnHeadInactiveBlack">% Editor approved</td>
    <td nowrap class="columnHeadInactiveBlack">% client Approved</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <?php if ($this->_tpl_vars['archived'] == 1): ?>
    <td nowrap class="columnHeadInactiveBlack">Completed Date</td>
    <?php else: ?>
    <td nowrap class="columnHeadInactiveBlack">Days Past Due</td>
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
  <tr id="tr<?php echo $this->_tpl_vars['item']['client_id']; ?>
" class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2">    <a href="/client_campaign/campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
&archived=-1"  target="_blank"><?php echo $this->_tpl_vars['item']['company_name']; ?>
</a></td>
<?php if ($this->_tpl_vars['is_show']): ?>
	<td><?php echo $this->_tpl_vars['item']['total_count']; ?>
</td>
<?php endif; ?>
  <td nowrap id="td<?php echo $this->_tpl_vars['item']['client_id']; ?>
" >
    <?php echo $this->_tpl_vars['item']['total_camp']; ?>

    <?php if ($this->_tpl_vars['item']['total_camp'] > 0): ?>
     <a href="javascript:void(0)" id="ahref<?php echo $this->_tpl_vars['item']['client_id']; ?>
" onclick="appendRsToObj($('tr<?php echo $this->_tpl_vars['item']['client_id']; ?>
'),this,<?php if ($this->_tpl_vars['item']['total_camp'] > $this->_tpl_vars['campaign_limit']):  echo $this->_tpl_vars['campaign_limit'];  else:  echo $this->_tpl_vars['item']['total_camp'];  endif; ?>, '/client_campaign/client_campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id'];  echo $this->_tpl_vars['query_string']; ?>
', 'report_result');return false;" >View Campaigns</a>
     <?php endif; ?>
     <?php if ($this->_tpl_vars['item']['total_camp'] > $this->_tpl_vars['campaign_limit']): ?>
     <a href="/client_campaign/campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id'];  echo $this->_tpl_vars['query_string']; ?>
" target="_blank" title="Show All" ><span class="total-text">[more]</span></a>
     <?php endif; ?>
  </td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	<td ><div <?php if ($this->_tpl_vars['item']['old_assigned']): ?>class="greenclass"<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_assign'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_assign'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)</div></td>
	<td ><div <?php if ($this->_tpl_vars['item']['old_submitted']): ?>class="yellowclass"<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)</div></td>
	<td ><div <?php if ($this->_tpl_vars['item']['old_approved']): ?>class="redclass"<?php endif; ?>><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_editor_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_editor_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)</div></td>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, "0%") : smarty_modifier_default($_tmp, "0%")); ?>
(<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)</td>
	<td></td>
	<td></td>
  <td align="right" nowrap class="table-right-2"><select name="campaign_id[]" id="campaign_id<?php echo $this->_tpl_vars['item']['client_id']; ?>
"  onchange="javascript:window.location='/client_campaign/keyword_list.php?archived=<?php echo $this->_tpl_vars['archived']; ?>
&campaign_id='+this.options[this.selectedIndex].value;"><option value="">[drop down list of current campaign]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'][$this->_tpl_vars['item']['client_id']]), $this);?>
</select></td>
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
<form name="f_archive_form" id="f_archive_form" action="/client_campaign/client_list.php" method="post">
<input type="hidden" name="status" id="status" value="" />
<input type="hidden" name="campaign_id" id="campaign_id" value="" />
<input type="hidden" name="total_row" id="total_row" value="" />
<input type="hidden" name="client_id" id="client_id" value="" />
<input type="hidden" name="query_string" id="query_string" value="<?php echo $this->_tpl_vars['query_string']; ?>
" />
</form>
<div id="postresult" ></div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "CaseInsensitiveString", "Number", "Number", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);

function formsubmit(status, cid, completed,  total, client_id)
{
    var form = $(\'f_archive_form\');
    form.status.value = status;
    form.campaign_id.value = cid;
    form.client_id.value = client_id;
    form.total_row.value = total;
    if (completed == 0)
    {
        if (!confirm(\'This campaign is not completed. Are you sure set it as archived?\'))
        {
            return false;
        }
    }
    archiveCampaign(\'postresult\', \'f_archive_form\');
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>