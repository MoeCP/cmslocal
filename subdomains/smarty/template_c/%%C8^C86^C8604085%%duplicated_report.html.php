<?php /* Smarty version 2.6.11, created on 2012-07-03 15:10:09
         compiled from client_campaign/duplicated_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/duplicated_report.html', 20, false),)), $this); ?>

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
  <h2>Possible Duplicate Report</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/client_campaign/duplicated_report.php" method="get">
<input name="opt" id="opt" type="hidden" value="" />
<table border="0" cellspacing="1" cellpadding="4">
<tr>
	<td   nowrap>Copywriter</td>
	<td><select name="cp_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['cp_id']), $this);?>
</select></td>
 	<td   nowrap>Editor</td>
	<td><select name="editor_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
  <td   nowrap>Campaign</td>
	<td nowrap><select name="cid"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_campaigns'],'selected' => $_GET['cid']), $this);?>
</select></td>
  <td>
  <input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='/client_campaign/duplicated_report.php'" />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="$('f_assign_keyword_return').action='/client_campaign/duplicated_export.php'" /></td>
</tr>
</table><br />
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Article Id</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">URL</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Detected Time</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['article_id']; ?>
</td>
    <td><a href="/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['checking_url']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['editor']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
    <td class="table-right-2"><?php echo $this->_tpl_vars['item']['detected_date']; ?>
</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>

  </tbody>
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
<form name="f_article_list" id="f_article_list" action="/article/article_report.php" method="post">
  <input id="operation" name="operation" value="" type="hidden" />
  <input id="keyword_id" name="keyword_id" value="" type="hidden" />
  <input id="overdue" name="overdue" value="" type="hidden" />
</form>
<script type="text/javascript">
//<![CDATA[

var st = new SortableTable(document.getElementById("table-1"),
  [<?php if ($this->_tpl_vars['is_pay_adjust'] != 1): ?>'None',<?php endif; ?>"Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date", "None"]);
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
function sendReminder(keyword_id, overdue, opt)
{
    var f = document.f_article_list;
    f.operation.value = opt;
    f.keyword_id.value = keyword_id;
    f.overdue.value = overdue;
    f.submit();
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>