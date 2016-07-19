<?php /* Smarty version 2.6.11, created on 2013-08-26 21:55:34
         compiled from article/pending_keyword_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/pending_keyword_list.html', 53, false),array('function', 'eval', 'article/pending_keyword_list.html', 96, false),array('modifier', 'nl2br', 'article/pending_keyword_list.html', 98, false),array('modifier', 'strip', 'article/pending_keyword_list.html', 98, false),array('modifier', 'escape', 'article/pending_keyword_list.html', 98, false),array('modifier', 'truncate', 'article/pending_keyword_list.html', 99, false),)), $this); ?>
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
var f_common = "document.f_pending_keyword.";
var f = document.f_pending_keyword;
function check_f_pending_keyword(result_count) {
  var is_checked;
  var f = document.f_pending_keyword_return;

  for (i = 1; i <= result_count; i++) {
    var is_update_id = \'isUpdate_\' + i;
    var keyword = $("keyword_"+i).value;
    if ($(is_update_id).checked)
    {
      is_checked = true;
    }
  }

  if (!is_checked)
  {
    alert("Please choose one keyword.");  
    return false;
  }
  return true;
}
'; ?>

//-->
</script>
<div id="page-box1">
  <h2>Pending Keywords</h2>
  <div id="campaign-search" >
    <strong>You can enter the "campaign name","keyword" etc. into the keyword input to search the relevant keyword's information</strong>
     <div id="campaign-search-box" >
<form name="f_pending_keyword_return" action="" method="get">
  <input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td nowrap>Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
"></td>
    <?php if ($this->_tpl_vars['login_role'] == 'client'): ?>
    <td nowrap>Campaigns:</td>
    <td nowrap><select name="campaign_id" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
    <?php endif; ?>
    <td nowrap>Article Type</td>
    <td><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select></td>
    <td nowrap>Show:</td>
    <td nowrap><select name="perPage" onchange="this.form.submit();" nowrap><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="" name="f_pending_keyword" method="post" <?php if ($this->_tpl_vars['js_check'] == true): ?>onSubmit="return check_f_pending_keyword('<?php echo $this->_tpl_vars['result_count']; ?>
')"<?php endif; ?>>
<table id="table-1" cellspacing="0" cellpadding="4" align="center" class="sortableTable" width="100%">
  <thead>
    <tr class="sortableTab">
      <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <td class="table-left-2" ><?php if ($this->_tpl_vars['result_count'] > 0): ?><input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" /><?php endif; ?></td>
      <td nowrap class="columnHeadInactiveBlack table-left-2 table-right-2 ">Number</td>
      <td nowrap class="columnHeadInactiveBlack">Keyword</td>
      <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
      <td nowrap class="columnHeadInactiveBlack">Client Name</td>
      <td nowrap class="columnHeadInactiveBlack">Company Name</td>
      <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <td nowrap class="columnHeadInactiveBlack">Editor</td>
      <?php endif; ?>
      <td nowrap class="columnHeadInactiveBlack table-right-2">Article Type</td>
      <td nowrap class="columnHeadInactiveBlack table-right-2">Article Number</td>
      <th class="table-right-corner">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_all'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_all']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop_all']['iteration']++;
?>
    <tr class="<?php if ($this->_foreach['loop_all']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
      <td class="table-left" >&nbsp;</td>
      <input type="hidden" name="keyword_id[]" id="keyword_id_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
" />
      <!--下面的keyword隐藏域是用于js的 //-->
      <input type="hidden" name="keyword[]" id="keyword_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword']; ?>
" />
      <td class="table-left-2"><input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_foreach['loop_all']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', f_common)" /></td>
      <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

      <td><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
      <td><a href="#" target="_self" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['company_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
      <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
      <td><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</td>
      <?php endif; ?>
      <td class="table-right-2"><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
      <td class="table-right-2"><?php echo $this->_tpl_vars['item']['article_number']; ?>
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
<?php if ($this->_tpl_vars['result_count'] > 0): ?>
<script language="JavaScript">
<!--
var post_checkbox_array = '<?php echo $this->_tpl_vars['post_checkbox_array']; ?>
';
checkPostItem('Select_All', post_checkbox_array, 'isUpdate[]', f_common);
//-->
</script>
<table align="center">
  <tr><td align="center"><input type="submit" class="button" value="Approval Keyword" /></td></tr>
</table>
<?php endif; ?>
</form>
</div>
<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "None", "Number",  "None", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None", "None", "None"]);

// restore the class names
st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(1);
//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>