<?php /* Smarty version 2.6.11, created on 2016-05-06 12:25:56
         compiled from client_campaign/cp_month_performance.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_month_performance.html', 15, false),array('function', 'eval', 'client_campaign/cp_month_performance.html', 50, false),array('modifier', 'default', 'client_campaign/cp_month_performance.html', 57, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="page-box1">
  <h2>Writer Monthly Performance Report</h2>
  <div id="campaign-search" >
    <strong>You can search copywriters' ranking</strong>
    <div id="campaign-search-box" >
 <form id="search" name="search" action="" method="get">
<table cellspacing="0" cellpadding="4">
  <tbody>
    <tr align="left" >
      <td>Writer Search ( name, user name )</td>
      <td><input type="text" name="search_keyword" id="search_keyword" value="<?php echo $_GET['search_keyword']; ?>
" /></td>
      <td> 
      <select name="s_choice" id="s_choice">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_choice'],'selected' => $_GET['s_choice']), $this);?>

      </select> 
      </td>
      <td>
      <select name="rmonth" id="rmonth">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $_GET['rmonth']), $this);?>

      </select> 
      </td>
      <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
     </tr>
  </tbody>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">User</td>
    <td nowrap class="columnHeadInactiveBlack">Name</td>
    <td nowrap class="columnHeadInactiveBlack">Email</td>
    <td nowrap class="columnHeadInactiveBlack">Month</td>
    <td nowrap class="columnHeadInactiveBlack">Total # Of Articles</td>
    <td nowrap class="columnHeadInactiveBlack"># Of Submitted</td>
    <td nowrap class="columnHeadInactiveBlack"># Of Editor Approved</td>
    <td nowrap class="columnHeadInactiveBlack"># Of Client Approved</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Overall Score</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><a href="/client_campaign/cp_performance.php?rmonth=<?php echo $this->_tpl_vars['item']['report_month']; ?>
&user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
 <?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['month']; ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/article/articles.php?copy_writer_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
"   ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else: ?>0<?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_editor_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>	
    <td class="table-right-2"><?php echo $this->_tpl_vars['item']['score']; ?>
</td>
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
  ["None", "Number", "CaseInsensitiveString", "CaseInsensitiveString",  "Number", "Number", "Number", "Number", "Number","Number"]);

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