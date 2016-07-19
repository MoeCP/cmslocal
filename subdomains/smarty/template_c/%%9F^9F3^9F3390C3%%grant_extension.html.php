<?php /* Smarty version 2.6.11, created on 2012-06-01 10:25:21
         compiled from user/grant_extension.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script language="JavaScript">
function check_request_extension(){
   var f = document.f_grant_extension;
  if (f.days_asked.value.length == 0) 
  {
    alert(\'Please enter day asked\');
    return false;
  }
  return true;
}
</script>
'; ?>

<div id="page-box1">
  <h2>Grant Extantion</h2>
  <div class="form-item" >
<form action="" name="f_grant_extension" method="post" onSubmit="return check_request_extension()">
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['campaign_id']; ?>
"  name="campaign_id" />
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['copy_writer_id']; ?>
"  name="copy_writer_id" />
<input type="hidden" value="<?php echo $this->_tpl_vars['ck_editor_id']; ?>
"  name="ck_editor_id" />
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['extension_id']; ?>
"  name="extension_id" />
<tr>
	<td class="requiredInput">Days asked:</td>
	<td><input type="text" value="<?php if ($this->_tpl_vars['info']['days_asked'] == ''): ?>2<?php else:  echo $this->_tpl_vars['info']['days_asked'];  endif; ?>" name="days_asked" /></td>
</tr>
<tr>
<td colspan="2" align="center" ><input type="submit" class="button" name="button" value="submit"/></td>
</tr>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>