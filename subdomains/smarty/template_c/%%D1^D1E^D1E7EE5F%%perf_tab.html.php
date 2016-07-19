<?php /* Smarty version 2.6.11, created on 2012-03-07 09:52:07
         compiled from user/perf_tab.html */ ?>
<div class="page-box1-class">
<h2>Performance</h2>
</div>
<table  cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">User</td>
    <td nowrap class="columnHeadInactiveBlack">Name</td>
    <td nowrap class="columnHeadInactiveBlack">Email</td>
    <td nowrap class="columnHeadInactiveBlack">Month</td>
    <td nowrap class="columnHeadInactiveBlack">Punctuation</td>
    <td nowrap class="columnHeadInactiveBlack">Grammar</td>
    <td nowrap class="columnHeadInactiveBlack">Structure</td>
    <td nowrap class="columnHeadInactiveBlack">AP Style</td>
    <td nowrap class="columnHeadInactiveBlack">Style Guide</td>
    <td nowrap class="columnHeadInactiveBlack">Content Guality</td>
    <td nowrap class="columnHeadInactiveBlack">Communication</td>
    <td nowrap class="columnHeadInactiveBlack">Cooperativeness</td>
    <td nowrap class="columnHeadInactiveBlack">Timeliness</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Overall</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <?php $_from = $this->_tpl_vars['peformance']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><a href="/client_campaign/cp_performance.php?rmonth=<?php echo $this->_tpl_vars['item']['report_month']; ?>
&user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
 <?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['month']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['punctuation']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['grammar']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['structure']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['ap_style']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['style_guide']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['quality']; ?>
 </td>
    <td><?php echo $this->_tpl_vars['item']['communication']; ?>
 </td>
    <td><?php echo $this->_tpl_vars['item']['cooperativeness']; ?>
 </td>
    <td><?php echo $this->_tpl_vars['item']['timeliness']; ?>
 </td>
    <td class="table-right-2"><?php echo $this->_tpl_vars['item']['ranking']; ?>
</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </form>
</table>