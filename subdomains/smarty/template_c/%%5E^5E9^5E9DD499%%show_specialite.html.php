<?php /* Smarty version 2.6.11, created on 2012-04-09 11:15:07
         compiled from user/show_specialite.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'user/show_specialite.html', 14, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>
<div id="page-box1">
  <h2>Description & Sample Article </h2>
  <fieldset>
  <legend><span class="requiredInput" >Description</span></legend>
  <div class="view-item" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
  </fieldset>
  <br />
  <br />
  <fieldset>
  <legend><span class="requiredInput" >Sample Article</span></legend>
  <div class="view-item" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['sample'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
  </fieldset>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>