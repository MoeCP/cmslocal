<?php /* Smarty version 2.6.11, created on 2013-01-18 09:42:35
         compiled from client_campaign/client_campaign_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/client_campaign_list.html', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['result']): ?>
&nbsp;<?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?><tr>
  <td class="table-left" height="30">&nbsp;</td>
  <td class="table-left-2" <?php if ($this->_tpl_vars['is_show']): ?>colspan="2"<?php endif; ?>>&nbsp;</td>
  <?php if ($this->_tpl_vars['is_home'] == 1): ?><td class="table-left-2"></td><?php endif; ?>
	<td nowrap><?php if ($this->_tpl_vars['item']['campaign_type'] == 2): ?><a href="/client_campaign/image_keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a><?php else: ?><a href="/client_campaign/keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo $this->_tpl_vars['item']['campaign_name'];  endif; ?></td>
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
  <?php if ($this->_tpl_vars['is_home'] == 1): ?>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['today_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
  <td class="table-right-2"><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['month_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
  <?php else: ?>
  <td><?php echo $this->_tpl_vars['item']['date_end']; ?>
</td>
  <?php if ($this->_tpl_vars['item']['archived'] == 1): ?>
  <td><?php echo $this->_tpl_vars['item']['completed_date']; ?>
</td>
  <?php else: ?>
	<td><?php if ($this->_tpl_vars['item']['total_assign'] > $this->_tpl_vars['item']['total_client_approval'] && $this->_tpl_vars['item']['past_days'] > 0):  echo $this->_tpl_vars['item']['past_days'];  endif; ?></td>
  <?php endif; ?>
  <td align="right" nowrap class="table-right-2">
      <span id="spanaction<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" >
      <?php if ($this->_tpl_vars['item']['archived'] == 1): ?>
      <strong>Archived</strong>
      <?php else: ?>
      <input type="button" class="button" value="Archive" onclick="formsubmit(1, <?php echo $this->_tpl_vars['item']['campaign_id']; ?>
,<?php if (( $this->_tpl_vars['item']['total_assign'] == $this->_tpl_vars['item']['total_client_approval'] )): ?>1<?php else: ?>0<?php endif; ?>, <?php echo $this->_tpl_vars['total']; ?>
, <?php echo $this->_tpl_vars['client_id']; ?>
)"/>
      <?php endif; ?>
      </span>
      <input type="button" class="button" value="Editorial Notes" onclick="openWindow('/client_campaign/campaign_notes.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=485,width=550,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=yes');"/>
      <input type="button" class="button" value="Update" onclick="openLink('/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');"/>
  </td>
  <?php endif; ?>
  <td class="table-right" >&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from);  endif;  if ($this->_tpl_vars['adodb_log']): ?>
<tr>
  <td colspan="12" ><center><pre><?php echo $this->_tpl_vars['adodb_log']; ?>
</pre></center></td>
</tr>
<?php endif; ?>