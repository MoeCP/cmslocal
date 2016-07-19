<?php /* Smarty version 2.6.11, created on 2012-03-05 13:34:44
         compiled from user/payment_history.html */ ?>
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
<div id="page-box1">
  <h2>Payment History</h2>
</div>
<div class="tablepadding"> 
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
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td ><?php echo $this->_tpl_vars['item']['month_format']; ?>
</td>
    <?php $_from = $this->_tpl_vars['g_article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item2']):
        $this->_foreach['loop2']['iteration']++;
?>
    <td><?php if ($this->_tpl_vars['item']['types'][$this->_tpl_vars['key']]['total']):  echo $this->_tpl_vars['item']['types'][$this->_tpl_vars['key']]['total'];  else: ?>0<?php endif; ?></td>
    <?php endforeach; endif; unset($_from); ?>
    <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/client_campaign/payment_log.php?month=<?php echo $this->_tpl_vars['item']['month']; ?>
" style="color:red;" ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else:  echo $this->_tpl_vars['item']['total'];  endif; ?></td>
    <td>$<?php echo $this->_tpl_vars['item']['payment']; ?>
</td>
    <td class="table-right-2"><input type="button" class="button" value="view invoice" onclick="window.open('/client_campaign/view_invoice.php?month=<?php echo $this->_tpl_vars['item']['month']; ?>
', 'view_invoice',  'status=yes, width=900, height=400,  left=50,  top=50, scrollbars=yes, resizable=yes');"></td>
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
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>