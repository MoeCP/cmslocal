<?php /* Smarty version 2.6.11, created on 2012-03-05 13:35:34
         compiled from client_campaign/type_report.html */ ?>
<table cellspacing="0" cellpadding="4" align="center" class="even" width="99%">
    <tr>
    <?php $_from = $this->_tpl_vars['report']['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
    <td class="requiredInput"><?php echo $this->_tpl_vars['item']['type_name']; ?>
 Article Amount:</td><td>$<?php echo $this->_tpl_vars['item']['cost']; ?>
</td>
    <?php endforeach; endif; unset($_from); ?>
    <td class="requiredInput">Total Amount:</td>
    <td >$<?php echo $this->_tpl_vars['report']['all']['cost']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>