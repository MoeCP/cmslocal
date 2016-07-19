<?php /* Smarty version 2.6.11, created on 2012-04-24 13:11:49
         compiled from user/note_category.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/note_category.html', 22, false),array('modifier', 'nl2br', 'user/note_category.html', 51, false),array('modifier', 'date_format', 'user/note_category.html', 53, false),)), $this); ?>
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
<div id="page-box1">
  <h2>User Note Category&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Add Note Category" onclick="showNoteCatDialog(0)" /> </h2>
  <div id="campaign-search" >
    <strong>You can enter the "title" and "notes" into the keyword input to search the relevant information of user note</strong>
    <div id="campaign-search-box" >
  <form name="f_assign_keyword_return" action="" method="get">
  <input type="hidden" name="get_operation" value="search" />
  <table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td  nowrap>Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword"></td>
    <td  nowrap>Show:</td>
    <td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
  </table><br>
  </form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Name</td>
    <td nowrap class="columnHeadInactiveBlack">Description</td>
    <td nowrap class="columnHeadInactiveBlack">Creator</td>
    <td nowrap class="columnHeadInactiveBlack">Created Date</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
        <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['created'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td align="right" nowrap class="table-right-2"><input type="button" class="button" value="update" onclick="showNoteCatDialog(<?php echo $this->_tpl_vars['item']['category_id']; ?>
)" /> </td>
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
<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>"CaseInsensitiveString",<?php endif; ?> "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

<?php echo '
st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);

function showNoteCatDialog(cid) {
  if (cid > 0)
  {
      var url = \'/user/ajax_cat_set.php?category_id=\' + cid;
      var title = \'Edit User Note Info\';
  } 
  else
  {
      var url = \'/user/ajax_cat_add.php\';
      var title = \'Add User Note Info\';
  }
  showWindowDialog(url, 600, 500, title);

}

'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>