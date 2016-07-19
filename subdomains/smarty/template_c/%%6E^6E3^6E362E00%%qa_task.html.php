<?php /* Smarty version 2.6.11, created on 2014-07-02 07:47:46
         compiled from user/qa_task.html */ ?>
<h2>QA Task</h2>
<table border="0" cellspacing="1" cellpadding="4" align="left" width="99%" class="sortableTable campaign-table" border="0" id="notification_table">
<tr>
  <th nowrap class="campaign-table-top-left">Campaign Name</th>
  <th class="campaign-table-top-right" >Total Assigned to me</th>
</tr>
<?php $_from = $this->_tpl_vars['tasks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<tr  class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td><a href="/client_campaign/keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></td>
  <td><?php echo $this->_tpl_vars['item']['total']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>