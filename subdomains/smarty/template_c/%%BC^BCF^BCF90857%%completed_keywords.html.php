<?php /* Smarty version 2.6.11, created on 2013-05-30 11:18:08
         compiled from client_campaign/completed_keywords.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/completed_keywords.html', 19, false),array('modifier', 'nl2br', 'client_campaign/completed_keywords.html', 75, false),array('modifier', 'strip', 'client_campaign/completed_keywords.html', 75, false),array('modifier', 'escape', 'client_campaign/completed_keywords.html', 75, false),array('modifier', 'truncate', 'client_campaign/completed_keywords.html', 81, false),array('modifier', 'date_format', 'client_campaign/completed_keywords.html', 84, false),)), $this); ?>
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
  <h2>Client Approval List</h2>
    <strong>You can enter the "keyword","campaign name","company name" etc. into the keyword input to search the relevant campaign's keyword information</strong>
  <div id="campaign-search" >
     <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="" method="get">
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
	<td  nowrap>Copywriter</td>
	<td><select name="user_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['user_id']), $this);?>
</select></td>
  <td >Editor</td>
	<td><select name="editor_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td><td>Show:</td><td>
	   <select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp;
   </td>
  <td rowspan="2" >
	  <input type="image" src="/images/button-search.gif" value="submit" />
	 </td>
</tr>
<tr>
	 <td  nowrap>Keyword</td>
	 <td   ><input type="text" name="keyword" id="search_keyword"></td>
	<td  nowrap>Month:&nbsp;</td>
	<td><select name="month" onchange="this.form.submit();"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select>&nbsp;&nbsp;&nbsp;</td>
  <td  nowrap>Article Type&nbsp;</td><td><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select>&nbsp; </td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table><br>
    </div>
  </div>
</div>
<input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
<input name="role" type="hidden" id="role" value="<?php echo $this->_tpl_vars['role']; ?>
" />
<input name="is_paid" type="hidden" id="is_paid" value="<?php echo $this->_tpl_vars['is_paid']; ?>
" />
</form>
<br />
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Client Approved Date</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Total Words</td>
    <td nowrap class="columnHeadInactiveBlack">Cost Type</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Cost</td>
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
	    <a href="/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" target="_blank" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
    </td>
    <td><?php echo $this->_tpl_vars['item']['article_number']; ?>
</td>
    <td>
    <?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>

    </td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['company_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['uc_name']; ?>
</a></td>
    <td><a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</a></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['client_approval_date'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['client_approval_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['word_count']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['role']; ?>
</td>
    <td align="left" nowrap class="table-right-2"><?php echo $this->_tpl_vars['item']['article_cost']; ?>
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
function updateTargetPayMonth( article_id, user_id, log_id, article_type, client_id, campaign_id, client_approval_date, pay_month, current_month, is_delay )
{
	var f  = document.campaign_keyword_list;
	f.article_id.value = article_id;
	f.client_approval_date.value = client_approval_date;
	f.article_type.value = article_type;
	f.client_id.value = client_id;
	f.campaign_id.value = campaign_id;
	f.log_id.value = log_id;
	f.pay_month.value = pay_month;
	f.current_month.value = current_month;
	f.is_delay.value = is_delay;
  f.user_id.value = user_id;
  if ($(\'forced_adjust\').checked)
  {
    $(\'is_forced_adjust\').value = 1;
  }
  else
  {
    $(\'is_forced_adjust\').value = 0;
  }
	f.operation.value = \'move_to_next_pay_peried\';
	f.submit();
}

function cancelKeyword( article_id, user_id, log_id, article_type, client_id, campaign_id, client_approval_date, current_month, is_canceled )
{
	var f = document.campaign_keyword_list;
  f.is_canceled.value = is_canceled;
	f.article_id.value = article_id;
	f.user_id.value = user_id;
	f.client_approval_date.value = client_approval_date;
	f.article_type.value = article_type;
	f.client_id.value = client_id;
	f.campaign_id.value = campaign_id;
	f.log_id.value = log_id;
	f.current_month.value = current_month;
	f.operation.value = \'cancel_keyword\';
	f.submit();
}

function showCurrentMonthKeywords()
{
	var f = document.f_assign_keyword_return;
	f.show_current_month.value = 1;
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>