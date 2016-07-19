<?php /* Smarty version 2.6.11, created on 2012-03-06 20:28:56
         compiled from suggestions/suggestions.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'suggestions/suggestions.html', 21, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
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
<center><div style="color:red"><?php echo $this->_tpl_vars['feedback']; ?>
</div></center>
<div id="page-box1">
  <h2>Campaign Contact Form</h2>
  <div id="campaign-search" >
    <strong>Please use this form to submit any questions or concerns regarding a particular campaign. We will respond to your inquiry within one business day</strong>
  </div>
  <div class="form-item" >
<form action="" method="post">
<table width="90%">
<?php if ($this->_tpl_vars['campaigns'] && $this->_tpl_vars['role'] == 'client'): ?>
<tr>
  <td width="15%" align="right">Campaigns</td>
  <td><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'],'selected' => $this->_tpl_vars['campaign_id'],'name' => 'campaign_id'), $this);?>
</td>
</tr>
<?php endif; ?>
<tr>
  <td width="15%" align="right" class="requiredInput">Subject</td><td width="85%"><input style="width:460px;" type="text" value="<?php echo $this->_tpl_vars['subject']; ?>
" name="subject"></td>
</tr>
<tr>
  <td align="right" class="requiredInput">Message</td><td><textarea name="content" cols="70" rows="20"><?php echo $this->_tpl_vars['content']; ?>
</textarea></td>
</tr>
<tr>
  <td></td><td><input type="submit" class="button" value="Submit"></td>
</tr>
</table>
</form>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>