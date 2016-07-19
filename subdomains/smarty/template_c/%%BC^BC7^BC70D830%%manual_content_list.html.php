<?php /* Smarty version 2.6.11, created on 2012-03-05 16:10:51
         compiled from manual_content/manual_content_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'manual_content/manual_content_list.html', 30, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>
<?php echo '
<script type="text/javascript">
function checkKeyword() {
    if (document.getElementById("keyword").value == \'\' ) {
        alert("Please enter keyword for search!");
    } else {
        document.search.submit();  
    }
}
</script>
'; ?>

<div id="page-box1">
  <h2>Manual Content List</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
  <form name="search" id="search" action="/manual_content/manual_content_list.php">
  <table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td >Keyword:</td>
    <td><input type="text" name="keyword" id="keyword"></td>
    <td><select id="category" name="category"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['category'],'selected' => $this->_tpl_vars['cat_selected']), $this);?>
</select></td>
    <td colspan="4">
        <input type="image" src="/images/button-search.gif" value="submit" onclick="checkKeyword()" />
    </td>
  </tr>
  </table>
  </form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <form action="/manual_content/manual_content_list.php" name="manual_content_list" method="post" />
  <input type="hidden" name="content_id" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">#</td>
    <td nowrap class="columnHeadInactiveBlack">Title</td>
    <td nowrap class="columnHeadInactiveBlack">Published</td>
    <td nowrap class="columnHeadInactiveBlack">ID</td>
    <td nowrap class="columnHeadInactiveBlack">Category</td>
    <td nowrap class="columnHeadInactiveBlack">Author</td>
    <td nowrap class="columnHeadInactiveBlack">Date</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['title']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['publish']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['content_id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['category'][$this->_tpl_vars['item']['category']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['author']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['created']; ?>
</td>
    <td align="right" nowrap class="table-right-2">
      <input type="button" class="button" value="View" onclick="javasript:window.location='/manual_content/view_manual_content.php?content_id=<?php echo $this->_tpl_vars['item']['content_id']; ?>
';" />
      <?php if ($this->_tpl_vars['user_permission'] >= 5): ?>
	  <input type="button" class="button" value="Update" onclick="javasript:window.location='/manual_content/add_manual_content.php?content_id=<?php echo $this->_tpl_vars['item']['content_id']; ?>
';" />
      <input type="submit" class="button" value="Delete" onclick="return deleteSubmit('manual_content_list', 'content_id', '<?php echo $this->_tpl_vars['item']['content_id']; ?>
', 'D', 'This Manual Content')" />
      <?php endif; ?>
    </td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </form>
</table>
</div>

<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
'; ?>

//]]>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>