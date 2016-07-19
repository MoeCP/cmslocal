<?php /* Smarty version 2.6.11, created on 2012-10-05 06:12:16
         compiled from user/candidate_tab.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'user/candidate_tab.html', 2, false),array('modifier', 'default', 'user/candidate_tab.html', 21, false),)), $this); ?>
<div class="page-box1-class">
<h2>Candidate info&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_tpl_vars['candidate']['resume_file'] != ''): ?><input type="button" class="button" value="Resume Download" onclick="javascript:openWindow('/user/resume_download.php?cid=<?php echo $this->_tpl_vars['candidate']['candidate_id']; ?>
&f=<?php echo ((is_array($_tmp=$this->_tpl_vars['candidate']['resume_file'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" /><?php endif;  if ($this->_tpl_vars['login_permission'] == 5): ?><input type="button" class="button" value="Add/Edit" onclick="javascript:openWindow('/user/candidate_edit.php?candidate_id=<?php echo $this->_tpl_vars['candidate']['candidate_id']; ?>
&user_id=<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
', 'height=500,width=800,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" /><?php endif; ?></h2>  

</div>
<?php if ($this->_tpl_vars['candidate']['candidate_id'] > 0): ?>
<fieldset>
<legend class="requiredInput" >&nbsp;&nbsp;&nbsp;&nbsp;Category</legend>
<div>
  <?php if ($this->_tpl_vars['candidate']['categories'] != ''): ?>
	<table id="table-2" class="sortableTable" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<td class="columnHeadInactiveBlack table-left-2" >Category</td>
		<td class="columnHeadInactiveBlack" >	Level</td>
		<td class="columnHeadInactiveBlack table-right-2" >Description</td>
		<th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
	  </tr>
	  <?php $_from = $this->_tpl_vars['candidate']['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
	  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" name="loop" >
		<td class="table-left" >&nbsp;</td>
		<td class="table-left-2"><?php echo ((is_array($_tmp=@$this->_tpl_vars['row']['category'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
		<td class="table-right-2"><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_levels'][$this->_tpl_vars['row']['level']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
		<td class="table-right-2"><?php echo $this->_tpl_vars['row']['description']; ?>
</td>
		<td class="table-right" >&nbsp;</td>            
	  </tr>
	  <?php endforeach; endif; unset($_from); ?>
	</table>
  <?php else: ?>n/a<?php endif; ?>
</div>
</fieldset>
<?php endif; ?>