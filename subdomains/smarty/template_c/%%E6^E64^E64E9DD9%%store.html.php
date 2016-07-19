<?php /* Smarty version 2.6.11, created on 2012-06-21 13:12:24
         compiled from category/store.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'category/store.html', 30, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '
<script>
function onCheckCategoryForm()
{
  var f = document.categoryform;
    if (f.category.value == \'\')
    {
       alert("Please specify Specialty");
       f.category.focus();
       return false;
    }
    return true;
}
</script>
'; ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<center><div style="color:red;"><?php echo $this->_tpl_vars['feedback']; ?>
</div></center>
<?php endif; ?>
<div id="page-box1">
  <h2>Add Specialty</h2>
  <div id="campaign-search" >
    <strong></strong>
  </div>
  <div class="form-item" >
<form action="/category/add.php" id="categoryform" name="categoryform"  method="post" onsubmit="return onCheckCategoryForm()">
<input type="hidden" value="<?php echo $this->_tpl_vars['category_info']['category_id']; ?>
" name="category_id" id="category_id" />
<table align="center" >
  <tr>
    <td><select name="parent_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['parents'],'selected' => $this->_tpl_vars['category_info']['parent_id']), $this);?>
</select></td>
  </tr>
  <tr>
     <td align="center"><input type="text" name="category" id="category" value="<?php echo $this->_tpl_vars['category_info']['category']; ?>
" size="50 " /></td>
 </tr>
  <tr>
    <td align="left"><input type="submit" class="button" value="Submit"></td>
  </tr>
</table>
</form>
  </div>
  <div class="tablepadding" >
    <table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
      <tr>
        <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th class="table-left-2" >#</th>
        <th >Specialties</th>
        <th >Parent</th>
        <th class="table-right-2" >&nbsp;</th>
        <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
      </tr>
      <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
      <tr>
        <td class="table-left" >&nbsp;</td>
        <td class="table-left-2" ><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
        <td ><?php echo $this->_tpl_vars['item']['category']; ?>
</td>
        <td ><?php echo $this->_tpl_vars['item']['pcategory']; ?>
</td>
        <td class="table-right-2"><a href="/category/add.php?category_id=<?php echo $this->_tpl_vars['item']['category_id']; ?>
" >[Edit]</a></td>
        <td class="table-right" >&nbsp;</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
  <div class="pagingpaddingleft" >
    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
    </table>
  </div>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>