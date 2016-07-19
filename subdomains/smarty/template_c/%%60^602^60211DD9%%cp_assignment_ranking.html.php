<?php /* Smarty version 2.6.11, created on 2012-05-29 10:02:26
         compiled from client_campaign/cp_assignment_ranking.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan=2>Copywriter Assignment Ranking</td>
  </tr>
  <tr>
    <td class="bodyBold">Basic Information</td>
  </tr>
  <tr>
    <td colspan="2" class="blackLine"><img src="/image/misc/s.gif"/></td>
  </tr>
  <tr>
    <td class="requiredInput" width="40%">Copywriter Name</td>
    <td colspan="4"><?php echo $this->_tpl_vars['copywriter_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Edit Request</td>
    <td colspan="4"><?php echo $this->_tpl_vars['editor_reject']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Client Reject </td>
    <td><?php echo $this->_tpl_vars['client_reject']; ?>
</td>
  </tr>
  
  <tr>
    <td class="requiredInput">Ranking</td>
    <td><?php echo $this->_tpl_vars['ranking']; ?>
</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td><input type="button" value="Close Window" onclick="window.close();"></td>
  </tr>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>