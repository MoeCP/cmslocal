<?php /* Smarty version 2.6.11, created on 2013-10-23 11:01:56
         compiled from client_campaign/client_approval_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/client_approval_list.html', 28, false),array('modifier', 'truncate', 'client_campaign/client_approval_list.html', 67, false),array('modifier', 'date_format', 'client_campaign/client_approval_list.html', 70, false),)), $this); ?>
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

<?php echo $this->_tpl_vars['xajax_javascript']; ?>

<?php echo '
<script type="text/javascript">
function formSubmit(status)
{
'; ?>

  $('payment_flow_status').value = status;
  ajaxSubmit('<?php echo $this->_tpl_vars['uri']; ?>
', 'f_google_approve', 'google_approve_div', 'post');
<?php echo '
}
'; ?>

</script>
<div id="page-box1">
<div id="campaign-search" >
  <div id="campaign-search-box" >
   <form name="f_assign_keyword_return" id="f_assign_keyword_return"  action="" method="get">
  <table border="0" cellspacing="1" cellpadding="4">
    <tr><td>Invoice Pay Period:</td><td><select name="month" onchange="if (this.value > 0) this.form.submit();"><option value="" >Choose your pay period</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $_GET['month']), $this);?>
</select></td></tr>
  </table>
  </form>
  </div>
</div>
</div>
<div name="google_approve_div" id="google_approve_div">
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date </td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Word Count</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Cost</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php if ($this->_tpl_vars['result']): ?>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['keyword']; ?>
</td>
    <td>
    <?php if ($this->_tpl_vars['permission'] > 1): ?>
    <a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['item']['article_number']; ?>
</a>
    <?php else: ?>
      <?php echo $this->_tpl_vars['item']['article_number']; ?>

    <?php endif; ?>
    </td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['article_type_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['cp_updated']):  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php echo $this->_tpl_vars['item']['total_words']; ?>
</td>
    <td class="table-right-2"><?php if ($this->_tpl_vars['user_type'] == '1'):  echo $this->_tpl_vars['item']['cost_for_article'];  else: ?>0<?php endif; ?></td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
  <tr><td class="table-left" >&nbsp;</td><td colspan="11" align="center"  class="table-right-2 table-left-2">You have no client-approved articles this month. Please be sure to check the client-approval dates of your articles</td><td class="table-right" >&nbsp;</td></tr>
  <?php endif; ?>
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
<?php if ($this->_tpl_vars['report']['types']): ?>
<table cellspacing="0" cellpadding="4" align="center" class="even" width="99%">
    <tr>
    <?php $_from = $this->_tpl_vars['report']['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
    <td class="requiredInput"><?php echo $this->_tpl_vars['item']['type_name']; ?>
 Article Total Words:</td><td><?php echo $this->_tpl_vars['item']['num']; ?>
</td>
    <?php endforeach; endif; unset($_from); ?>
    <td class="requiredInput">Total Words:</td>
    <td ><?php echo $this->_tpl_vars['report']['all']['num']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
    <?php $_from = $this->_tpl_vars['report']['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
    <td class="requiredInput"><?php echo $this->_tpl_vars['item']['type_name']; ?>
 Article Amount:</td><td>$<?php echo $this->_tpl_vars['item']['cost']; ?>
</td>
    <?php endforeach; endif; unset($_from); ?>
    <td class="requiredInput">Total Amount:</td>
    <td >$<?php echo $this->_tpl_vars['report']['all']['cost']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
<?php endif;  if ($this->_tpl_vars['payment_info']['payment_flow_status'] == 'ap'): ?>
<form action="" name="f_google_approve" id="f_google_approve" method="post">
<input type="hidden" name="month" id="month" value="<?php echo $this->_tpl_vars['payment_info']['month']; ?>
" />
<input type="hidden" name="payment_flow_status" id="payment_flow_status" value="" />
<table border="0" cellspacing="1" cellpadding="4" width="100%">
  <tr>
  <td><table border="0" cellspacing="1" cellpadding="4">
        <tr>
          <td class="requiredInput">memo(explanation)</td>
          <td><textarea name="memo" cols="50" rows="4" id="memo"><?php echo $_POST['memo']; ?>
</textarea></td>
        </tr>
        <tr>
            <td colspan="2">
            <input type="button" class="button" value="Approve" onclick="formSubmit('cpc');" />&nbsp;&nbsp;
            <input type="button" class="button" value="disapprove with explanation" onclick="formSubmit('dwe');"/></td>
        </tr>
        </table></td></tr>
</table>
</form>
<?php endif; ?>

<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None","Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(1);
'; ?>

//]]>
</script>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>