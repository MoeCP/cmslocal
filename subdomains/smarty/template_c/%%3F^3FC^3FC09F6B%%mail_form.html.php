<?php /* Smarty version 2.6.11, created on 2012-03-08 11:42:29
         compiled from mail/mail_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'mail/mail_form.html', 59, false),)), $this); ?>
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

<?php echo '
<script language="JavaScript">
<!--
function check_f_email_template()
{
  var f = document.f_email_template;

  if (f.event_id.value.length == 0) {
    alert(\'Please choose a template name\');
    f.event_id.focus();
    return false;
  }

  if (f.subject.value.length == 0) {
    alert(\'Please enter email\\\'s subject\');
    f.subject.focus();
    return false;
  }
  if (f.body.value.length == 0) {
    alert(\'Please enter email\\\'s subject\');
    f.body.focus();
    return false;
  }

  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Email template setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the email template required information.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_email_template" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_email_template()"<?php endif; ?>>
  <input type="hidden" name="template_id" value="<?php echo $this->_tpl_vars['template_info']['template_id']; ?>
">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Tempate Name</td>
    <td><select name="event_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['email_event'],'selected' => $this->_tpl_vars['template_info']['event_id']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="requiredInput">Subject</td>
    <td><input name="subject" type="text" id="subject" size="80" value="<?php echo $this->_tpl_vars['template_info']['subject']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Body</td>
    <td><textarea name="body" cols="80" rows="7" id="body"><?php echo $this->_tpl_vars['template_info']['body']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel">Description</td>
    <td><textarea name="description" cols="50" rows="4" id="description"><?php echo $this->_tpl_vars['template_info']['description']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
  </form>
</table>
<br>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>