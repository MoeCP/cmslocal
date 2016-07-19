<?php /* Smarty version 2.6.11, created on 2012-05-21 05:40:32
         compiled from client_campaign/change_article_type.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/change_article_type.html', 19, false),array('modifier', 'nl2br', 'client_campaign/change_article_type.html', 86, false),array('modifier', 'strip', 'client_campaign/change_article_type.html', 86, false),array('modifier', 'escape', 'client_campaign/change_article_type.html', 86, false),array('modifier', 'truncate', 'client_campaign/change_article_type.html', 91, false),array('modifier', 'date_format', 'client_campaign/change_article_type.html', 94, false),)), $this); ?>
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
  <h2>Change Article Type</h2>
  <div id="campaign-search" >
    <strong>You can enter the "keyword","campaign name","company name" etc. into the keyword input to search the relevant campaign's keyword information</strong>
     <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/client_campaign/change_article_type.php" method="get">
<div class="tablepadding"> 
<table border="0" cellspacing="1" cellpadding="4">
	<td  nowrap>Copywriter</td>
	<td><select name="copy_writer_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['copy_writer_id']), $this);?>
</select></td>
		      <!-- ................&copy_writer_id..@_writer_id //-->
	<input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
  <td >Editor</td>
	<td><select name="editor_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
	<td  nowrap>Article Type</td>
	<td ><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select></td>
	<td  nowrap>Status</td>
	<td nowrap>
    <select name="article_status" id="article_status" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_status'],'selected' => $_GET['article_status']), $this);?>
</select>&nbsp;&nbsp;&nbsp;
  </td>
  <td rowspan="2" >
	  <input type="image" src="/images/button-search.gif" value="submit" />
	 </td>
</tr>
<tr>
	 <td  nowrap>Keyword</td>
	 <td   ><input type="text" name="keyword" id="search_keyword"></td>
	 <td  nowrap>Show:</td>
	 <td nowrap>
	 <select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp;
  </td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table><br>
</form>
    </div>
  </div>
</div>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" name="campaign_keyword_list" method="post" onsubmit="return batchChangeArticleType()" />
<input type="hidden" name="campaign_id"  id="campaign_id"  value="<?php echo $_GET['campaign_id']; ?>
"   />
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">
    <?php if ($this->_tpl_vars['total'] > 0): ?>
      <input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" />
    <?php endif; ?>
    </td>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Total Words</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_all'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_all']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop_all']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
      <td class="table-left" >&nbsp;</td>
      <td class="table-left-2">
        <input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_foreach['loop_all']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', campaign_keyword_list)" />
        <input type="hidden" name="keyword_id[<?php echo $this->_foreach['loop_all']['iteration']; ?>
]" id="keyword_id_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
"  />
        <input type="hidden" name="article_types[<?php echo $this->_foreach['loop_all']['iteration']; ?>
]" id="article_types_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['article_type']; ?>
" />

      </td>
    <td><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td>
	    <a href="/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" target="_blank" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
    </td>
    <td>
      <?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>

    </td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['company_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'newwindow<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'height=300,width=200,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['uc_name']; ?>
</a></td>
    <td><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'newwindow<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'height=300,width=200,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</a></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
    <td class="table-right-2"><?php echo $this->_tpl_vars['item']['word_count']; ?>
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
<?php if ($this->_tpl_vars['total'] > 0): ?>
  <table align="center" >
  <tr>
    <td >Article Type:&nbsp;&nbsp;
    <td ><select name="article_type" id="article_type" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select></textarea>
    </td>
  </tr>
  <?php if ($this->_tpl_vars['total'] > 1): ?>
  <tr>
    <td></td>
    <td><input type="checkbox" name="changed_all" id="changed_all" value="1" onclick="changedAll(this)" />Select all articles in campaign</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td colspan="2" align="center" ><input type="submit" value="Submit" class="button" /></td>
  </tr>
 </table>
 <?php endif; ?>
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

function batchChangeArticleType()
{
    var cbs  = document.getElementsByName("isUpdate[]");
    var len = cbs.length;
    var is_checked = false;
    if ($(\'article_type\').value.length == 0)
    {
        alert("Please specify the artice type");
        $(\'article_type\').focus();
        return false;
    }
    for (var i=0; i < len; i++)
    {
        if (cbs[i].checked == true)
        {
            var index = cbs[i].value;
            if ($(\'article_types_\' + index).value.length > 0)
            {
                return confirm("Some of the keywords already have article type.  Do you want to overwrite?");
            }
            is_checked = true;
        }
    }
    if (!is_checked)
    {
        alert("Please choose keywords");
        return false;
    }
    return true;
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>