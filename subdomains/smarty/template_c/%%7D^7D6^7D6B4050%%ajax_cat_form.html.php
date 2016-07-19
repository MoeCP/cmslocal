<?php /* Smarty version 2.6.11, created on 2015-10-19 10:27:03
         compiled from user/ajax_cat_form.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="JavaScript">
<!--
var login_permission = '<?php echo $this->_tpl_vars['login_permission']; ?>
';
<?php echo '
check_f_category = function()
{
  var f = document.f_note;
  if (f.name.value.length == 0) {
      alert(\'Please enter name\');
      f.title.focus();
      return false;
  }
  return true;
}
'; ?>

//-->
</script>
<div id="page-box1">
  <h2>User Note Category</h2>
  <div id="campaign-search" >
    <strong>Please enter required information of user note category.</strong>
  </div>
  <div class="form-item" >
<form action="<?php echo $this->_tpl_vars['request_uri']; ?>
" method="post"  name="f_note" id="f_note" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_category()"<?php endif; ?>>
<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->_tpl_vars['category_id']; ?>
" />
<input type="hidden" name="created" id="created" value="<?php echo $this->_tpl_vars['info']['created']; ?>
" />
<input type="hidden" name="modified" id="modified" value="<?php echo $this->_tpl_vars['info']['modified']; ?>
" />
<input type="hidden" name="modified_by" id="modified_by" value="<?php echo $this->_tpl_vars['info']['modified_by']; ?>
" />
<input type="hidden" name="created_by" id="created_by" value="<?php echo $this->_tpl_vars['info']['created_by']; ?>
" />
<br /><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold" nowrap>Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Name&nbsp;</td>
    <td><input name="name" type="text" id="name" size="40" value="<?php echo $this->_tpl_vars['info']['title']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Description&nbsp;</td>
    <td><textarea name="description" cols="35" rows="6" id="description"><?php echo $this->_tpl_vars['info']['description']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
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