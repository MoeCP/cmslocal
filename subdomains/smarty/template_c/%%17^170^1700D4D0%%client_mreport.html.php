<?php /* Smarty version 2.6.11, created on 2016-05-06 13:56:13
         compiled from client_campaign/client_mreport.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/client_mreport.html', 22, false),)), $this); ?>
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
  <h2>
  Client Accounting List &nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
 <form name="f_assign_keyword_return" id="f_assign_keyword_return"  action="<?php echo $this->_tpl_vars['actionurl']; ?>
" method="get">
<input type="hidden" name="opt_action" id="opt_action" value="" /> 
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>Client</td>
    <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
"></td>
    <td nowrap>Campaign</td>
    <td><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaign_list'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
    <td nowrap>Agency</td>
    <td><select name="agency_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_agency'],'selected' => $_GET['agency_id']), $this);?>
</select></td>
    <td nowrap>Month:</td>
    <td><select name="month" onchange="onsearch('f_assign_keyword_return');"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['month']), $this);?>
</select></td>
    <td nowrap>Assignment Month</td>
    <td><input type="text" name="date_assigned" id="date_assigned" value="<?php echo $_GET['date_assigned']; ?>
" size="10" maxlength="10" value=""/>
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_assigned",
            ifFormat    : "%Y-%m",
            showsTime   : false,
            singleClick : true,
            //showsOtherMonths : true,
            step        : 1,
            range       : [2008, 2030]
        });
        </script></td>
    <td nowrap>Show:</td>
    <td nowrap><select name="perPage" onchange="onsearch('f_assign_keyword_return');"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td colspan="4" nowrap><input type="image" src="/images/button-search.gif" value="submit" onclick="onsearch('f_assign_keyword_return');" />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="exportcsv('f_assign_keyword_return')" /></td>
  </tr>
</table><br>
</form>       
    </div>
  </div>
</div>
<div class="tablepadding"> 
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom">Total for <?php echo $this->_tpl_vars['total_rs']; ?>
 items: $<?php echo $this->_tpl_vars['total_amount']; ?>
</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<form action="" method="post"  name="f_acct_flow" id="f_acct_flow" >
  <input type="hidden" name="user_id" value="">
  <input type="hidden" name="payment_flow_status" value="">
  <input type="hidden" name="article_ids" value="">
  <input type="hidden" name="month" value="">
  <input type="hidden" name="vendor_id" value=""/>
  <input type="hidden" name="role" id="role"  value="<?php echo $this->_tpl_vars['role']; ?>
">
</form>
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">#</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Client Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Contact Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Email</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Status</td>
    <?php if ($this->_tpl_vars['user_permission_int'] == 5): ?> 
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Total Words for Editor</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Total Articles for Editor</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Total Words for Writer</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Total Articles for Writer</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Total Words for Editor</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Total Articles for Editor</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Total Words for Writer</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Total Articles for Writer</td>
    <?php else: ?>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Total Words</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Total Articles</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Total Words</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Pay Total Articles</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack table-right-2" rowspan="2">Cost Amount</td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['contact_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['status'] == 'A'): ?><label style="color:red" ><?php echo $this->_tpl_vars['users_status']['A']; ?>
<label><?php else:  echo $this->_tpl_vars['users_status']['D'];  endif; ?></td>
    <?php if ($this->_tpl_vars['user_permission_int'] == 5): ?>
    <td><?php echo $this->_tpl_vars['item']['total_word_for_editor']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['total_article_for_editor']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['total_word_for_writer']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['total_article_for_writer']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['pay_total_words_for_editor']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['pay_total_articles_for_editor']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['pay_total_words_for_writer']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['pay_total_articles_for_writer']; ?>
</td>
    <?php else: ?>
    <td><?php echo $this->_tpl_vars['item']['total_word']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['total_article']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['pay_total_words']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['pay_total_articles']; ?>
</td>
    <?php endif; ?>

    <td nowrap class="table-right-2"><a href="/client_campaign/campaign_mreport.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
&month=<?php echo $this->_tpl_vars['month']; ?>
"  style="color:red"  >$<?php echo $this->_tpl_vars['item']['cost']; ?>
</a></td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
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
<?php echo '
<script type="text/javascript">
//<![CDATA[
function exportcsv(formId)
{
    $(\'opt_action\').value = \'export\';
}
function onsearch(formId)
{
    $(\'opt_action\').value = \'\';
    //$(\'date_assigned\').value = \'\';
    $(formId).submit();
}
//]]>
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>