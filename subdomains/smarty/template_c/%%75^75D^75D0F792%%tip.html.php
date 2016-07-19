<?php /* Smarty version 2.6.11, created on 2012-03-09 02:59:15
         compiled from manual_content/tip.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="page-box1">
  <h2><?php echo $this->_tpl_vars['info']['title'];  if ($this->_tpl_vars['info']['is_required'] == 1): ?>(Required)<?php elseif ($this->_tpl_vars['info']['is_required'] == 0): ?>(Optional)<?php endif; ?></h2>
  <div class="view-item"><?php echo $this->_tpl_vars['info']['description']; ?>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>