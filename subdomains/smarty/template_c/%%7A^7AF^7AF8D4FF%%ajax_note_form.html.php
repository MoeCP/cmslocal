<?php /* Smarty version 2.6.11, created on 2012-05-24 12:39:00
         compiled from user/ajax_note_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/ajax_note_form.html', 62, false),)), $this); ?>
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
check_f_note = function()
{
  var f = document.f_note;

  if (f.user_id.value == 0 || f.user_id.value == \'\')
  {
      alert(\'Please specify the user\');
      return false;
  }

  if (f.title.value.length == 0) {
      alert(\'Please enter title\');
      f.title.focus();
      return false;
  }

  if (f.notes.value.length == 0) {
      alert(\'Please enter note\');
      f.notes.focus();
      return false;
  }

  f.submit();
  return true;
}
'; ?>

//-->
</script>
<div id="page-box1">
  <h2>User Note</h2>
  <div id="campaign-search" >
    <strong>Please enter required information of user note.</strong>
  </div>
  <div class="form-item" >
<form action="<?php echo $this->_tpl_vars['request_uri']; ?>
" method="post"  name="f_note" id="f_note" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_note()"<?php endif; ?>>
<input type="hidden" name="created" id="created" value="<?php echo $this->_tpl_vars['user_info']['created']; ?>
" />
<input type="hidden" name="modified" id="modified" value="<?php echo $this->_tpl_vars['user_info']['modified']; ?>
" />
<input type="hidden" name="modified_by" id="modified_by" value="<?php echo $this->_tpl_vars['user_info']['modified_by']; ?>
" />
<input type="hidden" name="created_by" id="created_by" value="<?php echo $this->_tpl_vars['user_info']['created_by']; ?>
" />
<br /><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">User&nbsp;</td>
    <td>
     <?php if ($this->_tpl_vars['user_id'] > 0 || $this->_tpl_vars['note_id'] > 0): ?>
     <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
     <?php echo $this->_tpl_vars['users'][$this->_tpl_vars['user_id']]; ?>

     <?php else: ?>
     <select name="user_id" id="user_id" >
     <option value="" >[choose]</option>
     <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['user_info']['user_id']), $this);?>

     </select>
    <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Category&nbsp;</td>
    <td>
     <select name="category_id" id="category_id" >
     <option value="0" >[choose]</option>
     <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['categories'],'selected' => $this->_tpl_vars['user_info']['category_id']), $this);?>

     </select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Title&nbsp;</td>
    <td><input name="title" type="text" id="title" size="40" value="<?php echo $this->_tpl_vars['user_info']['title']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Note&nbsp;</td>
    <td><textarea name="notes" cols="30" rows="6" id="notes"><?php echo $this->_tpl_vars['user_info']['notes']; ?>
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