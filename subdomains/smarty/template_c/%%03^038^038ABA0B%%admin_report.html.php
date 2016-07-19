<?php /* Smarty version 2.6.11, created on 2012-03-05 09:23:37
         compiled from user/admin_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'user/admin_report.html', 34, false),)), $this); ?>
<div style="display:none" id="report_result" ></div>
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Project Manager</td>
    <td nowrap class="columnHeadInactiveBlack">Total Campaigns in All Reports</td>
    <td nowrap class="columnHeadInactiveBlack">Total Keywords</td>
    <td nowrap class="columnHeadInactiveBlack">% assigned</td>
    <td nowrap class="columnHeadInactiveBlack">% submitted</td>
    <td nowrap class="columnHeadInactiveBlack">% Editor approved</td>
    <td nowrap class="columnHeadInactiveBlack">% client Approved</td>
    <td nowrap class="columnHeadInactiveBlack">Submitted Today</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Client Approved(Month)</td>
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
    <td class="table-left-2"><a href="/client_campaign/campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
&is_home=1&month=<?php echo $this->_tpl_vars['month']; ?>
&archived=-1"  target="_blank"><?php echo $this->_tpl_vars['item']['company_name']; ?>
</a></td>
  <td><?php echo $this->_tpl_vars['item']['project_manager']; ?>
</td>
  <td nowrap >
    <?php if ($this->_tpl_vars['item']['total_camp'] > $this->_tpl_vars['campaign_limit']): ?>
    <a href="/client_campaign/campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
&archived=<?php echo $this->_tpl_vars['archived']; ?>
&is_home=1&month=<?php echo $this->_tpl_vars['month']; ?>
" target="_blank" title="Show All" ><span class="total-text"><?php echo $this->_tpl_vars['item']['total_camp']; ?>
</span></a>
    <?php else: ?>
    <?php echo $this->_tpl_vars['item']['total_camp']; ?>

    <?php endif; ?>
    <?php if ($this->_tpl_vars['item']['total_camp'] > 0): ?>
     <a href="javascript:void(0)" onclick="appendRsToObj($('tr<?php echo $this->_tpl_vars['item']['client_id']; ?>
'),this,<?php if ($this->_tpl_vars['item']['total_camp'] > $this->_tpl_vars['campaign_limit']):  echo $this->_tpl_vars['campaign_limit'];  else:  echo $this->_tpl_vars['item']['total_camp'];  endif; ?>, '/client_campaign/client_campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
&archived=<?php echo $this->_tpl_vars['archived']; ?>
&is_home=1&month=<?php echo $this->_tpl_vars['month'];  echo $this->_tpl_vars['query_string']; ?>
', 'report_result');return false;" >View Campaigns</a>
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
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['today_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	<td class="table-right-2"><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['month_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
  <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
  <td class="table-left" >&nbsp;</td>
  <td align="left" width="100%" class="contentLabel table-right-2 table-left-2" colspan="10" >If you would like more information, please click <a href="/client_campaign/client_list.php">here</a></td>
  <td class="table-right" >&nbsp;</td>
  </tr>
</table>
<center><pre><?php echo $this->_tpl_vars['adodb_log'];  //print_r($_POST); ?></pre></center>