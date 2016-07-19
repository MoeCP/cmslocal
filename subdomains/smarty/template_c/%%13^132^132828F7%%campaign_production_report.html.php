<?php /* Smarty version 2.6.11, created on 2012-03-21 14:44:06
         compiled from client_campaign/campaign_production_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/campaign_production_report.html', 8, false),)), $this); ?>
&nbsp;<?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<tr>
  <td class="table-left" >&nbsp;</td>
  <td class="table-left-2" <?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>colspan="5"<?php else: ?>colspan="6"<?php endif; ?>>&nbsp;</td>
  <td nowrap ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
  <?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>
  <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/article/articles.php?copy_writer_id=<?php echo $this->_tpl_vars['user_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"  ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else: ?>0<?php endif; ?></td>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['pct_total_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, '0%') : smarty_modifier_default($_tmp, '0%')); ?>
 (<?php echo $this->_tpl_vars['item']['total_submit']; ?>
) </td>
  <?php else: ?>
  <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/article/articles.php?editor_id=<?php echo $this->_tpl_vars['user_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"  ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else: ?>0<?php endif; ?></td>
  <td><?php echo $this->_tpl_vars['item']['pct_total_pending_approval']; ?>
 (<?php echo $this->_tpl_vars['item']['total_pending_approval']; ?>
)</td>
  <?php endif; ?>
  <td><?php echo $this->_tpl_vars['item']['pct_total_editor_approval']; ?>
 (<?php echo $this->_tpl_vars['item']['total_editor_approval']; ?>
)</td>
  <td><?php echo $this->_tpl_vars['item']['pct_total_client_approval']; ?>
 (<?php echo $this->_tpl_vars['item']['total_client_approval']; ?>
)</td>
  <td></td>
  <td align="right" nowrap class="table-right-2">
      <input type="button" class="button" value="Editorial Notes" onclick="openWindow('/client_campaign/campaign_notes.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=485,width=550,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=yes');" />
      <input type="button" class="button" value="Update" onclick="openLink('/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');"/>
  </td>
  <td class="table-right" >&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from);  if ($this->_tpl_vars['adodb_log']): ?>
<tr>
  <td colspan="12" ><center><pre><?php echo $this->_tpl_vars['adodb_log']; ?>
</pre></center></td>
</tr>
<?php endif; ?>