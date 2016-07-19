<?php /* Smarty version 2.6.11, created on 2012-05-10 15:30:11
         compiled from client_campaign/editor_client_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/editor_client_list.html', 46, false),)), $this); ?>
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
  <h2>Client List</h2>
  <div id="campaign-search" >
    <strong>You can enter the "client user name","company name","city" etc. into the keyword input to search the relevant client's information. Drop down list of current campaign as below, choose one option will go to that specific campaign<br /></strong>
     <div id="campaign-search-box" >
<form name="f_assign_keyword_return" action="/client/client_list.php" method="get">
<table border="0" cellspacing="1" cellpadding="4"> 
<tr>
  <td nowrap>Client Keyword</td>
  <td><input type="text" name="keyword" id="search_keyword"></td>
  <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
  <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td width="70%">&nbsp;</td>
</tr>
</table>
</form>
    </div>
  </div>
</div>

<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Total current articles</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2" >&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><a href="/client_campaign/ed_cp_campaign_list.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
&company_name=<?php echo $this->_tpl_vars['item']['company_name']; ?>
"><?php echo $this->_tpl_vars['item']['company_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['total_completed_articles']; ?>
</td>
    <td align="right" nowrap  class="table-right-2"><select name="campaign_id[]" id="campaign_id<?php echo $this->_tpl_vars['item']['client_id']; ?>
"  onchange="javascript:window.location='/article/article_keyword_list.php?campaign_id='+this.options[this.selectedIndex].value;"><option value="">[drop down list of current campaign]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['item']['id_name_campaign']), $this);?>
</select></td>
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
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  [ "None", "CaseInsensitiveString", "Number", "Number", "None"]);

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