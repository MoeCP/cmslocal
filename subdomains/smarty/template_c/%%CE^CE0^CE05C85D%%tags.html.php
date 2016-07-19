<?php /* Smarty version 2.6.11, created on 2012-11-05 04:25:16
         compiled from client/tags.html */ ?>
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

<?php echo '
<script language="JavaScript">
<!--
function check_f_form()
{
  var f = document.f_generatekey;

  if (!isEmail(f.email.value)) {
    alert(\'Invalid email address\');
    f.email.focus();
    return false;
  }

  if (f.domain.value.length == 0) {
    alert(\'Please provide the domain\');
    f.domain.focus();
    return false;
  }


  if (f.apitype.value.length == 0) {
    alert(\'Please specify what\\\'s kind of API\');
    f.apitype.focus();
    return false;
  }
  if (f.email.value.length == 0) {
    alert(\'Please specify email\');
    f.email.focus();
    return false;
  }

  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Domain Tags</h2>
  <div class="form-item" >
<br>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <?php $_from = $this->_tpl_vars['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr><td><?php echo $this->_tpl_vars['item']['output_name']; ?>
</td></tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>