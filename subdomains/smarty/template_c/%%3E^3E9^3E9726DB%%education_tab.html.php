<?php /* Smarty version 2.6.11, created on 2012-03-05 16:15:24
         compiled from user/education_tab.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'user/education_tab.html', 21, false),)), $this); ?>
<div class="page-box1-class">
<h2>My Specialities&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_tpl_vars['login_role'] == 'copy writer' || $this->_tpl_vars['login_role'] == 'editor' || $this->_tpl_vars['login_role'] == 'admin'): ?><input type="button" class="button" value="
    Add/Edit" onclick="window.location.href='/category/select.php?user_id=<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
&frm=user_detail'" /><?php endif; ?></h2>
</div>
<table id="table-2" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <tr>
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Category</td>
    <td nowrap class="columnHeadInactiveBlack">Level</td>
    <td nowrap class="columnHeadInactiveBlack">Description</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Sample</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['category']; ?>
</td>
    <td><?php echo $this->_tpl_vars['g_user_levels'][$this->_tpl_vars['item']['level']]; ?>
</td>
    <td rowspan="<?php echo $this->_tpl_vars['item']['total_row']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td rowspan="<?php echo $this->_tpl_vars['item']['total_row']; ?>
" class="table-right-2"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['sample'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php if ($this->_tpl_vars['item']['children']): ?>
  <?php $_from = $this->_tpl_vars['item']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item2']):
        $this->_foreach['loop2']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop2']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['item2']['category']; ?>
</td>
    <td><?php echo $this->_tpl_vars['g_user_levels'][$this->_tpl_vars['item2']['level']]; ?>
</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</table>