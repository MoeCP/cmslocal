<?php /* Smarty version 2.6.11, created on 2012-03-07 09:52:07
         compiled from user/note_tab.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'user/note_tab.html', 22, false),array('modifier', 'date_format', 'user/note_tab.html', 24, false),)), $this); ?>
<div class="page-box1-class">
<h2>User Notes&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Add Note" onclick="showNoteDialog(0)" /></h2>
</div>
<table id="table-2" cellspacing="0" align="center" cellpadding="0" class="sortableTable" >
  <tr>
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Category</td>
    <td nowrap class="columnHeadInactiveBlack">Subject</td>
    <td nowrap class="columnHeadInactiveBlack">Note</td>
    <td nowrap class="columnHeadInactiveBlack">Creator</td>
    <td nowrap class="columnHeadInactiveBlack">Created Date</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <?php $_from = $this->_tpl_vars['notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['ucategories'][$this->_tpl_vars['item']['category_id']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['title']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['notes'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['created'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td align="right" nowrap  class="table-right-2"><input type="button" class="button" value="update" onclick="showNoteDialog(<?php echo $this->_tpl_vars['item']['note_id']; ?>
)" /> </td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>