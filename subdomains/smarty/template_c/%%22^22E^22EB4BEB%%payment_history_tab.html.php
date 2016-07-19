<?php /* Smarty version 2.6.11, created on 2012-03-05 16:15:24
         compiled from user/payment_history_tab.html */ ?>
<div class="page-box1-class">
<h2>Payment History</h2>
</div>
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">#</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Month/Year</td>
    <td nowrap class="columnHeadInactiveBlack" colspan="<?php echo $this->_tpl_vars['total_type']; ?>
" align="center">Total Client Approved Words</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Words Total</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Amount</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2" rowspan="2"></td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <tr class="sortableTab">
    <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop2']['iteration']++;
?>
    <td class="columnHeadInactiveBlack"><?php echo $this->_tpl_vars['item']; ?>
</td>
    <?php endforeach; endif; unset($_from); ?>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['histories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
        <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['month_format']; ?>
</td>
    <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item2']):
        $this->_foreach['loop2']['iteration']++;
?>
    <td><?php if ($this->_tpl_vars['item']['types'][$this->_tpl_vars['key']]['total']):  echo $this->_tpl_vars['item']['types'][$this->_tpl_vars['key']]['total'];  else: ?>0<?php endif; ?></td>
    <?php endforeach; endif; unset($_from); ?>
    <?php if ($this->_tpl_vars['login_role'] == 'editor' || $this->_tpl_vars['login_role'] == 'copy writer'): ?>
    <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/client_campaign/payment_log.php?month=<?php echo $this->_tpl_vars['item']['month']; ?>
" style="color:red;" ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else:  echo $this->_tpl_vars['item']['total'];  endif; ?></td>
    <?php else: ?>
    <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/client_campaign/payment_log.php?month=<?php echo $this->_tpl_vars['item']['month']; ?>
&user_id=<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
&role=<?php echo $this->_tpl_vars['user_info']['role']; ?>
" style="color:red;" ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else:  echo $this->_tpl_vars['item']['total'];  endif; ?></td>
    <?php endif; ?>
    <td>$<?php echo $this->_tpl_vars['item']['payment']; ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] == 'editor' || $this->_tpl_vars['login_role'] == 'copy writer'): ?>
    <td class="table-right-2"><input type="button" class="button" value="view invoice" onclick="window.open('/client_campaign/view_invoice.php?user_id=<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
&month=<?php echo $this->_tpl_vars['item']['month']; ?>
&role=<?php echo $this->_tpl_vars['user_info']['role']; ?>
', 'view_invoice',  'status=yes, width=900, height=400,  left=50,  top=50, scrollbars=yes, resizable=yes');"></td>
    <?php else: ?>
    <td class="table-right-2"><input type="button" class="button" value="view invoice" onclick="window.open('/client_campaign/cp_invoice.php?user_id=<?php echo $this->_tpl_vars['user_info']['user_id']; ?>
&month=<?php echo $this->_tpl_vars['item']['month']; ?>
&role=<?php echo $this->_tpl_vars['user_info']['role']; ?>
', 'view_invoice',  'status=yes, width=900, height=400,  left=50,  top=50, scrollbars=yes, resizable=yes');"></td>
    <?php endif; ?>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="table-left" >&nbsp;</td>
    <td colspan="2" class="table-left-2">Total</td>
    <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item2']):
        $this->_foreach['loop2']['iteration']++;
?>
    <td  style="color:red;" ><?php if ($this->_tpl_vars['stats']['types'][$this->_tpl_vars['key']]['total']):  echo $this->_tpl_vars['stats']['types'][$this->_tpl_vars['key']]['total'];  else: ?>0<?php endif; ?></td>
    <?php endforeach; endif; unset($_from); ?>
    <td  style="color:red;" ><?php echo $this->_tpl_vars['stats']['total']; ?>
</td>
    <td>$<?php echo $this->_tpl_vars['stats']['payment']; ?>
</td>
    <td class="table-right-2" >&nbsp;</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
</table>