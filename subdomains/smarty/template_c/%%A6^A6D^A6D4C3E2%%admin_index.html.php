<?php /* Smarty version 2.6.11, created on 2014-05-07 22:41:26
         compiled from user/admin_index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'user/admin_index.html', 32, false),array('modifier', 'default', 'user/admin_index.html', 41, false),array('function', 'html_options', 'user/admin_index.html', 41, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif;  if ($this->_tpl_vars['user_permission_int'] >= 5): ?>
<div id="page-box2" >
<table width="100%">
  <tr>
    <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/qa_task.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  </tr> 
</table>
</div>
<?php endif;  if ($this->_tpl_vars['user_permission_int'] >= 3): ?>
<div id="page-box2" >
<table width="100%">
  <tr>
    <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/notification.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  </tr> 
</table>
</div>
<?php endif; ?>
<div id="page-box1">
  <div>
    <table width="100%"  cellspacing="1" cellpadding="4">
      <tr>
        <td align="left"><h2>Campaign Progress Report</h2></td>
        <td align="right"><span><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e, %Y") : smarty_modifier_date_format($_tmp, "%A, %B %e, %Y")); ?>
</span></td>
      </tr>
    </table>
  </div>
  <div id="campaign-search-box" >
  <form name="f_index_search" id="f_index_search" action="/index.php" method="get">
  <table border="0" cellspacing="1" cellpadding="4">
    <tr>
      <td nowrap>Month:</td>
      <td><select name="month" onchange="ajaxSubmit('/user/admin_index.php', 'f_index_search', 'adminreport')" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => ((is_array($_tmp=@$this->_tpl_vars['smart']['get']['monthes'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['month']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['month']))), $this);?>
</select></td>
      <?php if ($this->_tpl_vars['user_permission_int'] >= 5): ?>
      <td nowrap>Project Manager</td>
      <td><select name="project_manager_id" onchange="ajaxSubmit('/user/admin_index.php', 'f_index_search', 'adminreport')"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['pms'],'selected' => ((is_array($_tmp=@$this->_tpl_vars['smart']['get']['project_manager_id'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, ''))), $this);?>
</select></td> 
      <?php endif; ?>
    </tr>
  </table>
  </form>
  </div>
</div>
<div class="tablepadding" >
  <div id="adminreport" ></div>
</div>
<script>ajaxSubmit('/user/admin_index.php', 'f_index_search', 'adminreport');</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>