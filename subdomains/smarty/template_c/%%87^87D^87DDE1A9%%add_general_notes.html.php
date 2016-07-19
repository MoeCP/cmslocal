<?php /* Smarty version 2.6.11, created on 2014-04-25 11:48:48
         compiled from client_campaign/add_general_notes.html */ ?>
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
function checkNotes()
{
  var f = document.add_general_note;
  if (f.subject.value.length == 0) {
    alert(\'Please enter note\\\'s subject\');
    f.subject.focus();
    return false;
  }
  if (f.body.value.length == 0) {
    alert(\'Please enter note\\\'s body\');
    f.body.focus();
    return false;
  }
  f.submit();
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>General Editorial Notes Setting&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Go Back" onclick="javascript:history.go(-1);" /></h2>
  <div id="campaign-search" >
    <strong>Please enter the genral notes required information.</strong>
  </div>
  <div class="form-item" >


<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="add_general_note">
  <input type="hidden" name="general_note_id" value="<?php echo $this->_tpl_vars['notes']['general_note_id']; ?>
">
  <input type="hidden" name="created_by" value="<?php echo $this->_tpl_vars['notes']['created_by']; ?>
">
  <input type="hidden" name="created_role" value="<?php echo $this->_tpl_vars['notes']['created_role']; ?>
">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Subject</td>
    <td><input name="subject" type="text" id="subject" value="<?php echo $this->_tpl_vars['notes']['subject']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="requiredInput">Body</td>
    <td><textarea name="body" cols="80" rows="10" id="body"><?php echo $this->_tpl_vars['notes']['body']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Submit" class="button" onclick="checkNotes()">&nbsp;<input type="reset" value="reset" class="button"></td>
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