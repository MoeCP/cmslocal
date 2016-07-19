<?php /* Smarty version 2.6.11, created on 2013-12-04 04:40:08
         compiled from article/decline_article.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'article/decline_article.html', 35, false),)), $this); ?>
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
check_f_decline_article = function ()
{ 
  var f = document.f_decline_article;
  var len = f.decline_reason.length;
  for (var i=0;i < len ; i++) {
    if (f.decline_reason[i].checked) {
      return true;
    }
  }
  alert(\'Please specify the decline reason\');
  return false;
}
'; ?>

//-->
</script>
<div id="page-box1">
  <h2>What is your reason for declining this article</h2>
  <div class="form-item" >
<form action="decline_article.php" method="post"  id="f_decline_article" name="f_decline_article" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_decline_article()"<?php endif; ?>>
<input type="hidden" name="keyword_id" value="<?php echo $this->_tpl_vars['keyword_ids']; ?>
">
<br /><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td colspan="2" ><?php echo smarty_function_html_radios(array('name' => 'decline_reason','options' => $this->_tpl_vars['decline_reason'],'separator' => '<br />'), $this);?>
</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" value="Submit" class="button" />&nbsp;<input type="reset" value="reset" class="button"></td>
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