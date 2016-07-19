<?php /* Smarty version 2.6.11, created on 2012-08-30 17:00:52
         compiled from article/download_option.html */ ?>
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
<style type="text/css">
.disabled_input { border-right: #000033 0px solid; border-top: #000033 0px solid; font-size: 12px; border-left: #000033 0px solid; border-bottom: #000033 0px solid; background:#ffffff}
</style>
<script language="JavaScript">
<!--
function formSubmit()
{
  var form = document.getElementById(\'f_article\');
  if (form.title.checked || form.mk.checked || form.md.checked || form.body.checked)
  {
    form.submit();
  } else {
    alert("Please pick one option at least");
    return false;
  }
}
//-->
</script>
'; ?>

<div id="page-box1" >
<h2>Download options</h2>
  <div id="campaign-search" >
    <strong>Please pick download options for XML items</strong>
  </div>
  <div class="form-item" style="width:400px">
<form action="/article/xml.php" method="get" name="f_article" id="f_article" target="_blank">
<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
<input type="hidden" name="<?php echo $this->_tpl_vars['k']; ?>
" value="<?php echo $this->_tpl_vars['v']; ?>
" />
<?php endforeach; endif; unset($_from); ?>
<input type="hidden" name="cid" id="cid" value="<?php echo $_GET['cid']; ?>
" />
<input type="hidden" name="article_id" id="article_id" value="<?php echo $_GET['article_id']; ?>
" />
<input type="hidden" name="article_ids" id="article_ids" value="<?php echo $_GET['article_ids']; ?>
" />
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" >  
  <tr>
    <td ><input type="checkbox" name="title" id="title" value="1" checked /><label>Title</label></td>
  </tr>
  <tr>
    <td ><input type="checkbox" name="mk" id="mk" value="1" checked /><label>Meta Keyword</label></td>
  </tr>
  <tr>
    <td ><input type="checkbox" name="md" id="md" value="1" checked /><label>Meta Description</label></td>
  </tr>
  <tr>
    <td ><input type="checkbox" name="mid" id="mid" value="1" checked /><label>Mapping-ID</label></td>
  </tr>
  <tr>
    <td ><input type="checkbox" name="rich_body" id="rich_body" value="1" checked /><label>Rich Body</label></td>
  </tr>
  <tr>
    <td ><input type="checkbox" name="text_body" id="text_body" value="1" /><label>Text Body</label></td>
  </tr>
  <tr>
    <td ><input type="checkbox" name="ht" id="ht" value="1"  /><label>Html Title</label></td>
  </tr>
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
  <tr>
    <td ><input type="checkbox" name="author" id="author" value="1"  /><label>Copywriter</label></td>
  </tr>
  <?php endif; ?>
  <?php $_from = $this->_tpl_vars['optional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr>
    <td ><input type="checkbox" name="<?php echo $this->_tpl_vars['key']; ?>
" id="<?php echo $this->_tpl_vars['key']; ?>
" value="1" /><label><?php echo $this->_tpl_vars['item']['label']; ?>
</label></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td align="center" ><input type="button" name="button" id="button" class="button" value="Submit" onclick="javascript:formSubmit();" /></td>
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