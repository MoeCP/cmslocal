<?php /* Smarty version 2.6.11, created on 2014-10-09 05:10:06
         compiled from user/notification.html */ ?>
<h2>Notifications</h2>
<table border="0" cellspacing="1" cellpadding="4" align="left" width="99%" class="sortableTable campaign-table" border="0" id="notification_table">
<tr>
  <th nowrap class="campaign-table-top-left">Clear Item<?php if ($this->_tpl_vars['reports']['total_notifications'] > 0): ?>&nbsp;<a href="javascript:void(0)" onclick="removeAllNotification(this, '/index.php?operation=deletenote&uid=<?php echo $this->_tpl_vars['uid']; ?>
')" >Clear All</a><?php endif; ?>
</th>
  <th>You have <span id="total_notifications" ><?php if ($this->_tpl_vars['role'] == 'designer'):  echo $this->_tpl_vars['reports2']['total_notifications'];  else:  echo $this->_tpl_vars['reports']['total_notifications'];  endif; ?></span> items that need your attention</th>
  <th class="campaign-table-top-right" >Date</th>
</tr>
<?php if ($this->_tpl_vars['role'] == 'designer'): ?>
<?php $_from = $this->_tpl_vars['reports2']['notifications']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
 if ($this->_tpl_vars['item']['notes'] != ''): ?>
<tr id="trnote<?php echo $this->_tpl_vars['item']['notification_id']; ?>
" class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td>
    <a href="javascript:void(0)" onclick="removeNotification($('trnote<?php echo $this->_tpl_vars['item']['notification_id']; ?>
'), '/index.php?operation=deletenote&id=<?php echo $this->_tpl_vars['item']['notification_id']; ?>
')" ><img alt="Clear Item" src="/images/button-clear-item.jpg" /></a>
  </td>
  <td><?php echo $this->_tpl_vars['item']['notes']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['generate_date']; ?>
</td>
</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php else: ?>
<?php $_from = $this->_tpl_vars['reports']['notifications']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
 if ($this->_tpl_vars['item']['notes'] != ''): ?>
<tr id="trnote<?php echo $this->_tpl_vars['item']['notification_id']; ?>
" class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td>
    <a href="javascript:void(0)" onclick="removeNotification($('trnote<?php echo $this->_tpl_vars['item']['notification_id']; ?>
'), '/index.php?operation=deletenote&id=<?php echo $this->_tpl_vars['item']['notification_id']; ?>
')" ><img alt="Clear Item" src="/images/button-clear-item.jpg" /></a>
  </td>
  <td><?php echo $this->_tpl_vars['item']['notes']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['generate_date']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from); ?>
<?php endif; ?>
</table>