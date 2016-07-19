<?php /* Smarty version 2.6.11, created on 2014-08-08 14:26:19
         compiled from article/article_search.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'article/article_search.html', 19, false),array('modifier', 'default', 'article/article_search.html', 19, false),array('modifier', 'truncate', 'article/article_search.html', 101, false),array('modifier', 'date_format', 'article/article_search.html', 109, false),array('function', 'html_options', 'article/article_search.html', 19, false),)), $this); ?>
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
  <h2>Articles Search</h2>
  <div id="campaign-search" >
    <strong><a href="#" onclick="showWindowDialog('/article/search_manual.php',600,400, 'Advanced Query Syntax')" >Search Manual</a></strong>
    <div id="campaign-search-box" >
      <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/article/article_search.php" method="get">
      <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td  nowrap>Keyword</td>
        <td nowrap colspan="7" ><input type="text" size="80" name="fst" id="search_keyword" value="<?php echo ((is_array($_tmp=$_GET['fst'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /><select name="kso"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_options'],'selected' => ((is_array($_tmp=@$_GET['kso'])) ? $this->_run_mod_handler('default', true, $_tmp, 3) : smarty_modifier_default($_tmp, 3))), $this);?>
</select>&nbsp;Show:<select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp; Start Date <input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $_GET['date_start']; ?>
" readonly/>
			<input type="button" class="button" id="btn_cal_date_start" value="...">
			<script type="text/javascript">
			Calendar.setup({
				inputField  : "date_start",
				ifFormat    : "%Y-%m-%d",
				showsTime   : false,
				button      : "btn_cal_date_start",
				singleClick : true,
				step        : 1,
				range       : [1990, 2030]
			});
			</script>
			 And 
			<input type="text" name="date_start_end" id="date_start_end" size="10" maxlength="10" value="<?php echo $_GET['date_start_end']; ?>
" readonly/>
			<input type="button" class="button" id="btn_cal_date_start_end" value="...">
			<script type="text/javascript">
			Calendar.setup({
				inputField  : "date_start_end",
				ifFormat    : "%Y-%m-%d",
				showsTime   : false,
				button      : "btn_cal_date_start_end",
				singleClick : true,
				step        : 1,
				range       : [1990, 2030]
			});
			</script></td>
     </tr>
     <tr>
        <td  nowrap>Filter By</td>
        <td><select name="tid"><option value="">Article Type</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['tid']), $this);?>
</select></td>
        <td><select name="uid"><option value="">Copywriter</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_writer'],'selected' => $_GET['uid']), $this);?>
</select></td>
        <td><select name="eid"><option value="">Editor</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['eid']), $this);?>
</select></td>
        <td><select name="cid"><option value="">Client</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_clients'],'selected' => $_GET['cid']), $this);?>
</select></td>
        <td nowrap rowspan="2" >
        <input type="image" src="/images/button-search.gif" onclick="$('f_assign_keyword_return').action='/article/article_search.php';" value="submit"  />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="$('f_assign_keyword_return').action='/article/export_search.php';" />
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
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
	  <td nowrap class="columnHeadInactiveBlack table-left-2">No.</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Article Title</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Client</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Number of Words</td>
    <?php if ($this->_tpl_vars['wordcount']): ?>
    <td nowrap class="columnHeadInactiveBlack">Keyword Match in Content</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Cost</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif;  if (( $this->_tpl_vars['item']['article_status'] == '0' && $this->_tpl_vars['login_permission'] == 1 || $this->_tpl_vars['item']['article_status'] == '1gc' && $this->_tpl_vars['login_permission'] >= 3 ) && $this->_tpl_vars['tomorrow'] >= $this->_tpl_vars['item']['date_end']): ?> rejected<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
"  target="_blank" ><?php echo $this->_tpl_vars['item']['article_number']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['title']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['keyword']; ?>
</td>
    <td><?php echo $this->_tpl_vars['all_clients'][$this->_tpl_vars['item']['client_id']]; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><?php echo $this->_tpl_vars['all_editor'][$this->_tpl_vars['item']['editor_id']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['all_writer'][$this->_tpl_vars['item']['copy_writer_id']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['word_count']; ?>
</td>
    <?php if ($this->_tpl_vars['wordcount']): ?>
    <td><?php echo $this->_tpl_vars['wordcount'][$this->_tpl_vars['item']['article_id']]; ?>
</td>
    <?php endif; ?>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td align="right" nowrap class="table-right-2"><?php echo $this->_tpl_vars['item']['cost_for_article']; ?>
</td>
    <td class="table-right" >&nbsp;</td>
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
  [null,"Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date", "Number", "None"]);

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