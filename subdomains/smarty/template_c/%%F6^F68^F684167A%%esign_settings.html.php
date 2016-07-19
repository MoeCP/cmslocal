<?php /* Smarty version 2.6.11, created on 2012-03-29 12:51:33
         compiled from user/esign_settings.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'user/esign_settings.html', 68, false),)), $this); ?>
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
function esign()
{
  var f = document.f_esign;

  return true;
}
//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Default EchoSign Documents Settings</h2>
  <div class="form-item" >
<br>
<form action="" method="post"  name="f_esign" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_email_template()"<?php endif; ?>>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <input type="hidden" name="gtitle" id="gtitle" value="<?php echo $this->_tpl_vars['config']['gtitle']; ?>
" />
  <input type="hidden" name="config_id" value="<?php echo $this->_tpl_vars['config']['config_id']; ?>
" />
  <tr>
    <td class="bodyBold" nowrap>Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Title</td>
    <td><input type="text" name="gtitle" id="gtitle" size="40" value="<?php echo $this->_tpl_vars['config']['gtitle']; ?>
"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Documents
    </td>
    <td align="left">
      <div class="sendLibrarySelector" >
      <table cellspacing="0" cellpadding="0" border="0">
      <?php $_from = $this->_tpl_vars['libs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['items']):
        $this->_foreach['loop']['iteration']++;
?>
      <tr class="shelf" >
        <th colspan="3" >
        <?php if ($this->_tpl_vars['k'] == 'PERSONAL'): ?>My Documents <?php elseif ($this->_tpl_vars['k'] == 'SHARED'): ?>Shared Documents<?php elseif ($this->_tpl_vars['k'] == 'GLOBAL'): ?>EchoSign Documents <?php endif; ?>
        </th>
      </tr>
      <tr>
        <th class="checkbox">&nbsp;</th>
        <th class="name">Name</th>
        <th class="modified">Last Modified</th>
      </tr>
      <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
      <tr>
          <td class="checkbox">
            <input type="checkbox" value="<?php echo $this->_tpl_vars['item']['name']; ?>
" name="docs[]" id="<?php echo $this->_tpl_vars['item']['documentKey']; ?>
" <?php if ($this->_tpl_vars['item']['checked'] == '1'): ?>checked<?php endif; ?>>
          </td>
          <td class="name">
            <label for="<?php echo $this->_tpl_vars['item']['documentKey']; ?>
"><?php echo $this->_tpl_vars['item']['name']; ?>
</label>
          </td>
          <td class="modified" nowrap>
            <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['modifiedDate'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%D %H:%M:%S') : smarty_modifier_date_format($_tmp, '%D %H:%M:%S')); ?>

          </td>
        </tr>
      <?php endforeach; endif; unset($_from); ?>
      <?php endforeach; endif; unset($_from); ?>
      </table>
      </div>
    </td>
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
<br>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>