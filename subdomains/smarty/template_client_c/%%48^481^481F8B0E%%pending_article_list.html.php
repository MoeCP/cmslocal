<?php /* Smarty version 2.6.11, created on 2015-12-24 09:32:38
         compiled from article/pending_article_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/pending_article_list.html', 59, false),array('function', 'eval', 'article/pending_article_list.html', 105, false),array('modifier', 'date_format', 'article/pending_article_list.html', 114, false),)), $this); ?>
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
<script language="JavaScript">
<!--
var f_common = "document.f_pending_article_list.";
var f = document.f_pending_article_list;
function check_f_pending_article_list(result_count) {
  var is_checked;
  var f = document.f_pending_article_list;

  for (i = 1; i <= result_count; i++) {
    var is_update_id = \'isUpdate_\' + i;
    if (document.getElementById(is_update_id).checked)
    {
      is_checked = true;
    }
  }

  if (!is_checked)
  {
    alert("Please choose one article.");  
    return false;
  }

  return true;
}

//-->
</script>
'; ?>

<div id="page-box1">
  <div id="campaign-actions" >
  <div id="campaign-actions-label"> Articles Awaiting Approval</div>
  <?php if ($this->_tpl_vars['login_role'] == 'client' && $this->_tpl_vars['campaign_id'] != ''): ?>
  <ul id="campaign-nav">
    <li><a href="/article/download_article_list.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
" target="_blank"><img alt="Download Article" src="/images/button-download-article.gif" /></a></li>
  </ul>
  <?php endif; ?>
  </div>
  <div id="campaign-search" >
    <strong>Enter in the campaign name or keywords to narrow your search results</strong>
    <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" action="/article/pending_article_list.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td  nowrap>Keyword</td>
        <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
"></td>
        <?php if ($this->_tpl_vars['login_role'] == 'client'): ?>
        <td  nowrap>Campaigns:</td>
        <td nowrap><select name="campaign_id" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
        <?php endif; ?>
        <td  nowrap>Show:</td>
        <td nowrap><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
        <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="50%">&nbsp;</td>
      </tr>
    </table>
    </form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<br />
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<form action="/article/pending_article_list.php" name="f_pending_article_list" method="post"  <?php if ($this->_tpl_vars['js_check'] == true): ?>onSubmit="return check_f_pending_article_list('<?php echo $this->_tpl_vars['result_count']; ?>
')"<?php endif; ?> />
<table id="table-1" cellspacing="0" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
  <?php if ($this->_tpl_vars['result']): ?>
	<td nowrap class="columnHeadInactiveBlack table-left-2"><input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]', event)" /></td>
  <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">No.</td>
    <td nowrap class="columnHeadInactiveBlack">Topic</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Noflow Status</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <input type="hidden" name="article_id[]" id="article_id_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['article_id']; ?>
" />
	<td class="table-left-2" align="center"><input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_foreach['loop']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', f_common, event)" /></td>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><a href="javascript:void(0)" onclick="showArticleDialog('<?php echo $this->_tpl_vars['item']['article_id']; ?>
', '<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
', '<?php echo $this->_tpl_vars['campaign_id']; ?>
')" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
        <td><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <td><?php echo $this->_tpl_vars['item']['copywriter']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['editor']; ?>
</td>
    <?php endif; ?>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['article_number']; ?>
</td>
	<td><?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>
</td>	
	<td><?php echo $this->_tpl_vars['noflow_status'][$this->_tpl_vars['item']['noflow_status']]; ?>
</td>	
    <td align="center" nowrap class="table-right-2">
	    <input type="button" class="button" value="Review Article" onclick="javasript:window.location='/article/approve_article.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
&fmp=pending_article_list';" />
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div><br />
<?php if ($this->_tpl_vars['result']): ?>
<table align="center">
  <tr><td align="center" ><input type="submit" class="button" value="Approval all pending articles" /></td></tr>
</table>
<?php endif; ?>
</form>
</div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  [null, "None", "Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);
function showArticleDialog(aid, kid, cid) {
  var url = \'/article/ajax_approve_article.php?article_id=\' + aid + \'&keyword_id=\' + kid + \'&campaing_id=\' + cid+ \'&fmp=pending_article_list\';
  showWindowDialog(url, 900, 450, "Approve article");
}

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(2);
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>