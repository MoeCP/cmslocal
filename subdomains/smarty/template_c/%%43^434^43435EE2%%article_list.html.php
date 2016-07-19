<?php /* Smarty version 2.6.11, created on 2014-08-27 16:02:25
         compiled from article/article_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/article_list.html', 20, false),array('function', 'eval', 'article/article_list.html', 75, false),array('modifier', 'nl2br', 'article/article_list.html', 79, false),array('modifier', 'strip', 'article/article_list.html', 79, false),array('modifier', 'escape', 'article/article_list.html', 79, false),array('modifier', 'truncate', 'article/article_list.html', 90, false),array('modifier', 'date_format', 'article/article_list.html', 97, false),)), $this); ?>
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
  <h2>Articles List</h2>
  <div id="campaign-search" >
    <!--<strong>You can enter the "campaign name","keyword","article content" etc. into the keyword input to search the relevant article's information</strong>-->
    <div id="campaign-search-box" >
      <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/article/article_list.php" method="get">
      <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td  nowrap>Keyword</td>
        <td><input type="text" name="keyword" id="search_keyword"></td>
        <td  nowrap>Article Type</td>
        <td><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select></td>
        <td  nowrap>Article Status</td>
        <td><select name="article_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_statuses'],'selected' => $_GET['article_status']), $this);?>
</select></td>
        <td  nowrap>Show:</td>
        <td nowrap><select name="perPage" onchange="this.form.submit();" nowrap><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
        <td nowrap>
        <input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='/article/article_list.php'" />
        <?php if ($_GET['article_status'] == '1gd'): ?>
        &nbsp;
        <input type="submit" value="Download Report" class="moduleButton" onclick="$('f_assign_keyword_return').action='/client_campaign/duplicated_export.php'" />
        <?php endif; ?>
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
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <?php if ($this->_tpl_vars['login_role'] == 'copy writer'): ?>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <?php else: ?>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Article Status</td>
    <?php if ($this->_tpl_vars['login_role'] != 'editor'): ?><td nowrap class="columnHeadInactiveBlack">Start Date</td><?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack"><?php if ($this->_tpl_vars['login_role'] == 'editor'): ?>Writer<?php endif; ?> Due Date </td>
    <?php if ($this->_tpl_vars['login_role'] != 'copy writer'): ?>
    <td nowrap class="columnHeadInactiveBlack">Writer Submitted</td>
    <!--<td nowrap class="columnHeadInactiveBlack">Editor Due Date</td>-->
    <td nowrap class="columnHeadInactiveBlack">Version</td>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_permission'] >= 3 || $this->_tpl_vars['login_permission'] == 1): ?>
    <td nowrap class="columnHeadInactiveBlack"><?php if ($this->_tpl_vars['login_permission'] >= 4): ?>Cost<?php else: ?>Pay Rate<?php endif; ?></td>
    <?php endif; ?>
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
    <td>
    <?php if ($this->_tpl_vars['login_role'] != 'copy writer'): ?>
      <a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
    <?php else: ?>
      <?php if ($this->_tpl_vars['login_permission'] == 1 && $this->_tpl_vars['item']['article_status'] != '0' && $this->_tpl_vars['item']['article_status'] != ''): ?>
        <a href="/article/article_review.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&from=article_list" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
      <?php else: ?>
		<?php echo $this->_tpl_vars['item']['keyword']; ?>

        <!--<a href="#" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();" > <?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>-->
      <?php endif; ?>
    <?php endif; ?>
    </td>
    <td><?php echo $this->_tpl_vars['item']['article_number']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] == 'copy writer'): ?>
    <td><a href="mailto:<?php echo $this->_tpl_vars['item']['editor_email']; ?>
" ><?php echo $this->_tpl_vars['item']['editor']; ?>
</a></td>
    <?php else: ?>
    <td><a href="mailto:<?php echo $this->_tpl_vars['item']['writer_email']; ?>
" ><?php echo $this->_tpl_vars['item']['copywriter']; ?>
</a></td>
    <?php endif; ?>
    <td><?php echo $this->_tpl_vars['article_statuses'][$this->_tpl_vars['item']['article_status']]; ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] != 'editor'): ?><td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td><?php endif; ?>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] != 'copy writer'): ?>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <!--<td><?php if ($this->_tpl_vars['item']['article_status'] == '1gc'):  echo ((is_array($_tmp=$this->_tpl_vars['item']['editor_due_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  else: ?>n/a<?php endif; ?></td>-->
    <td><?php echo $this->_tpl_vars['item']['current_version_number']; ?>
</td>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_permission'] >= 3 || $this->_tpl_vars['login_permission'] == 1): ?>
    <td><?php if ($this->_tpl_vars['login_permission'] >= 4):  echo $this->_tpl_vars['item']['cost_for_article'];  else:  echo $this->_tpl_vars['item']['cost_per_article'];  endif; ?></td>
    <?php endif; ?>
    <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['login_permission'] == 1 && $this->_tpl_vars['item']['article_status'] == '1'): ?>
	      <input type="button" class="button" value="Recall" onclick="check_recall(<?php echo $this->_tpl_vars['item']['article_id']; ?>
, '<?php echo $this->_tpl_vars['item']['article_status']; ?>
')" />
	      <input type="button" class="button" value="Review" onclick="window.open('/article/article_review.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&from=article_list&<?php echo $this->_tpl_vars['query_string']; ?>
');" />
    <?php endif; ?>
          <?php if ($this->_tpl_vars['login_role'] == 'copy writer' || $this->_tpl_vars['login_permission'] > 3): ?>
      <?php if (( $this->_tpl_vars['item']['article_status'] == 0 || $this->_tpl_vars['item']['article_status'] == '99' ) && ( $this->_tpl_vars['item']['creation_role'] == $this->_tpl_vars['login_role'] || $this->_tpl_vars['item']['copy_writer_id'] == $this->_tpl_vars['login_op_id'] ) || $this->_tpl_vars['item']['article_status'] == 2 || $this->_tpl_vars['login_permission'] > 3): ?>
      <?php if ($this->_tpl_vars['item']['current_version_number'] == '1.0' && $this->_tpl_vars['login_permission'] == 1 && $this->_tpl_vars['item']['article_status'] == '0' && ( ( $this->_tpl_vars['item']['creation_role'] == $this->_tpl_vars['login_role'] && $this->_tpl_vars['item']['creator'] == $this->_tpl_vars['login_op_id'] ) || $this->_tpl_vars['item']['copy_writer_id'] == $this->_tpl_vars['login_op_id'] ) && ( $this->_tpl_vars['item']['title'] == '' && $this->_tpl_vars['item']['body'] == '' )): ?>
      <input type="button" class="button" value="Add Article" onclick="javasript:window.location='/article/article_set.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&<?php echo $this->_tpl_vars['query_string']; ?>
';" />
      <?php else: ?>
	    <input type="button" class="button" value="Update" onclick="javasript:window.location='/article/article_set.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&from=article_list&<?php echo $this->_tpl_vars['query_string']; ?>
';" />
      <?php endif; ?>
      <?php endif; ?>
	  <?php else: ?>
		<?php if (( $this->_tpl_vars['item']['article_status'] == 0 && $this->_tpl_vars['item']['creator'] == $this->_tpl_vars['login_op_id'] && $this->_tpl_vars['item']['creation_role'] == $this->_tpl_vars['login_role'] ) || ( $this->_tpl_vars['login_role'] == 'client' && $this->_tpl_vars['item']['article_status'] == 4 ) || ( ( $this->_tpl_vars['item']['article_status'] == '1gc' || $this->_tpl_vars['item']['article_status'] == 3 || $this->_tpl_vars['item']['article_status'] == '1gd' ) && $this->_tpl_vars['login_role'] != 'client' )): ?>
	    <input type="button" class="button" value="Review" onclick="javasript:window.location='/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&fmp=<?php if ($_GET['article_status'] == '1gc'): ?>1gc<?php else: ?>article_list<?php endif; ?>&<?php echo $this->_tpl_vars['query_string']; ?>
';" />
		<?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] == 'editor' && ( $this->_tpl_vars['item']['article_status'] == '2' || $this->_tpl_vars['item']['article_status'] == 4 || $this->_tpl_vars['item']['article_status'] == '99' )): ?>
      <input type="button" class="button" value="Re-Edit" onclick="javasript:window.location='/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&<?php echo $this->_tpl_vars['query_string']; ?>
';" />
     <?php endif; ?>
	  <?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
     <input type="button" class="button" value="Comment" onclick="javasript:window.location='/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&<?php echo $this->_tpl_vars['query_string']; ?>
';" />
     <?php endif; ?>
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