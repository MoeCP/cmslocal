<?php /* Smarty version 2.6.11, created on 2013-01-18 09:42:54
         compiled from article/article_type_questions.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/article_type_questions.html', 20, false),array('function', 'eval', 'article/article_type_questions.html', 52, false),)), $this); ?>
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
  <h2>Article Type Question<input type="button" class="button" value="Add Article Type Question" onclick="javasript:window.location='/article/type_question_add.php';" /></h2>
  <div id="campaign-search" >
    <strong>You can enter the "campaign name","keyword","article content" etc. into the keyword input to search the relevant article's information</strong>
    <div id="campaign-search-box" >
      <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="" method="get">
      <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td  nowrap>Keyword</td>
        <td><input type="text" name="keyword" id="search_keyword"></td>
        <td  nowrap>Article Type</td>
        <td><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select></td>
        <td  nowrap>Show:</td>
        <td nowrap><select name="perPage" onchange="this.form.submit();" nowrap><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
        <td nowrap>
        <input type="image" src="/images/button-search.gif" value="submit" />
        </td>
        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="60%">&nbsp;</td>
      </tr>
      </table>
      </form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="/article/article_list.php" name="article_list" id="article_list" method="post" >
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <input type="hidden" name="keyword_id" />
  <input type="hidden" name="article_id" />
  <input type="hidden" name="operation" />
  <input type="hidden" name="old_status" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
	<td nowrap class="columnHeadInactiveBlack table-left-2">No.</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Question</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif;  if (( $this->_tpl_vars['item']['article_status'] == '0' && $this->_tpl_vars['login_permission'] == 1 || $this->_tpl_vars['item']['article_status'] == '1gc' && $this->_tpl_vars['login_permission'] >= 3 ) && $this->_tpl_vars['tomorrow'] >= $this->_tpl_vars['item']['date_end']): ?> rejected<?php endif; ?>">
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['type_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['question']; ?>
</td>
    <td align="right" nowrap class="table-right-2">
	  </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
</form>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
) (Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
</div>
<?php echo '
<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  [ "Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date", "Number", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
function check_recall( article_id, status )
{
   var f = document.article_list;
   f.article_id.value = article_id;
   f.operation.value = \'recall\';
   f.old_status.value = status;
   f.submit();
}
//]]>
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>