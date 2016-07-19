<?php /* Smarty version 2.6.11, created on 2012-03-07 20:44:18
         compiled from client_campaign/request_extension.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script language="JavaScript">
function check_request_extension(){
   var f = document.request_extension;
  if (f.reason.value.length == 0) {
    alert(\'Please enter reason\');
    return false;
  }
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
  <h2>Request Extension</h2>
  <div class="form-item" >
<form action="" name="request_extension" method="post" onSubmit="return check_request_extension()">
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['campaign_id']; ?>
"  name="campaign_id" />
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['copy_writer_id']; ?>
"  name="copy_writer_id" />
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['editor_id']; ?>
"  name="editor_id" />
<input type="hidden" value="<?php echo $this->_tpl_vars['info']['extension_id']; ?>
"  name="extension_id" />
<tr>
	<td class="requiredInput">Subject</td>
	<td>
		request for extension on <?php echo $this->_tpl_vars['campaign_name']; ?>
 from <?php echo $this->_tpl_vars['copy_writer_name']; ?>

		<input type="hidden" value="request for extension on <?php echo $this->_tpl_vars['campaign_name']; ?>
 from <?php echo $this->_tpl_vars['copy_writer_name']; ?>
" name="subject"  />
	</td>
</tr>
<tr>
	<td class="requiredInput">Reason:</td>
	<td><textarea cols="50" rows="10" name="reason" ><?php echo $this->_tpl_vars['info']['reason']; ?>
</textarea></td>
</tr>
<tr>
	<td class="requiredInput">Days asked:</td>
	<td><input type="text" value="<?php if ($this->_tpl_vars['info']['days_asked'] == ''): ?>2<?php else:  echo $this->_tpl_vars['info']['days_asked'];  endif; ?>" name="days_asked" /></td>
</tr>
<tr>
<td colspan="2" align="center" ><input type="submit" class="button" name="button" value="submit"/></td>
</tr>
</table>
</form>
  </div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>