<?php /* Smarty version 2.6.11, created on 2015-05-06 03:28:06
         compiled from article/articles.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/articles.html', 22, false),array('function', 'formatpayperiod', 'article/articles.html', 101, false),array('modifier', 'nl2br', 'article/articles.html', 89, false),array('modifier', 'strip', 'article/articles.html', 89, false),array('modifier', 'escape', 'article/articles.html', 89, false),array('modifier', 'truncate', 'article/articles.html', 95, false),array('modifier', 'date_format', 'article/articles.html', 98, false),array('modifier', 'count_characters', 'article/articles.html', 101, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>

<br />
<div id="page-box1">
  <h2><?php echo $this->_tpl_vars['titile_perfix']; ?>
 Articles</h2>
  <div id="campaign-search" >
    <strong>You can enter the "keyword","campaign name","company name" etc. into the keyword input to search the relevant campaign's keyword information</strong>
     <div id="campaign-search-box" >
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
  <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="" method="get">
 <td  nowrap>Keyword</td>
 <td><input type="text" name="keyword" size="35" id="search_keyword"></td>
 <td  nowrap>Copywriter</td>
 <td><select name="copy_writer_id" id="copy_writer_id" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['copy_writer_id']), $this);?>
</select></td>
 <td  nowrap>Editor</td>
 <td><select name="editor_id" id="editor_id" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
  <td  nowrap>Article Type</td>
	<td colspan="1"><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select>
	</td>
  <td rowspan="2" >
	  <input type="image" src="/images/button-search.gif" value="submit">
	 </td>
</tr>
<tr>
  <td  nowrap>Campaign</td>
  <td colspan="3" ><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
	 <td  nowrap>Show:</td>
	 <td nowrap>
	 <select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp;
  </td>
	<td  nowrap>Status</td>
	<td nowrap>
    <select name="article_status" id="article_status" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_status'],'selected' => $_GET['article_status']), $this);?>
</select>&nbsp;&nbsp;&nbsp;
  </td>
  </form>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table><br>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" name="campaign_keyword_list" method="post" />
  <input type="hidden" name="keyword_id" />
  <input type="hidden" name="article_id" />
  <input type="hidden" name="operation" id="operation" />
  <input type="hidden" name="is_canceled" />
  <input type="hidden" name="is_delay" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Pay Period</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Total Words</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
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
    <td>
        <?php if ($this->_tpl_vars['login_permission'] == 2): ?>
	    <?php echo $this->_tpl_vars['item']['keyword']; ?>

    <?php else: ?>
	    <a href="/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"   onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
    <?php endif; ?>
    </td>
    <td>
      <?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>

    </td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['company_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><?php if ($this->_tpl_vars['login_role'] == 'agency'):  echo $this->_tpl_vars['item']['uc_name'];  else: ?><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'newwindow<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'height=300,width=200,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['uc_name']; ?>
</a><?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['login_role'] == 'agency'):  echo $this->_tpl_vars['item']['ue_name'];  else: ?><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'newwindow<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'height=300,width=200,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</a><?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php if (((is_array($_tmp=$this->_tpl_vars['item']['pay_month'])) ? $this->_run_mod_handler('count_characters', true, $_tmp) : smarty_modifier_count_characters($_tmp)) == 7):  echo format_pay_period(array('pmonth' => $this->_tpl_vars['item']['pay_month']), $this); else: ?>--<?php endif; ?></td>
    <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['word_count']; ?>
</td>
    <td align="left" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['login_permission'] > 3): ?>      <?php if ($this->_tpl_vars['login_role'] != 'copy writer' && $this->_tpl_vars['item']['article_id'] > 0): ?>
      <input type="button" class="button" value="Preview" onclick="openLink('/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
');" />
      <?php endif; ?>
      <input type="button" class="button" value="Assign" onclick="openLink('/client_campaign/assign_keyword.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
');" />
      <input type="button" class="button" value="Update" onclick="openLink('/client_campaign/keyword_set.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
');" />
      <input type="submit" class="button" value="Delete" onclick="return deleteSubmit('campaign_keyword_list', 'keyword_id', '<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
', 'D', 'This Campaign Keyword')" />
      <?php if ($this->_tpl_vars['item']['article_status'] == '5'): ?>
      <input type="submit" class="button" value="Publish" onclick="return doSubmit('campaign_keyword_list', 'article_id', '<?php echo $this->_tpl_vars['item']['article_id']; ?>
', 'P')" />
      <?php endif; ?>
	  <?php endif; ?>
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
) (Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
  </form>
</div>
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
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>