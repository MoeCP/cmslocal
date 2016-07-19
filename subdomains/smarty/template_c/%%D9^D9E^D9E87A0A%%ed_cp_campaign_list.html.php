<?php /* Smarty version 2.6.11, created on 2014-09-10 15:18:34
         compiled from client_campaign/ed_cp_campaign_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'client_campaign/ed_cp_campaign_list.html', 46, false),array('modifier', 'date_format', 'client_campaign/ed_cp_campaign_list.html', 105, false),)), $this); ?>
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
  <h2>Campaign List</h2>
  <div id="campaign-search" >
      <!--<strong>You can enter the "client name","campaign name","company name" etc. into the keyword input to search the relevant client's campaign information</strong>-->
      <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" action="/client_campaign/ed_cp_campaign_list.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td nowrap>Campaign Keyword</td>
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
<?php if ($this->_tpl_vars['login_role'] == 'copy writer'): ?>
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Progress</td>
    <!--<td nowrap class="columnHeadInactiveBlack">Project Manager</td>-->
    <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
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
    <td class="table-left-2"><strong><a href="/article/article_keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></strong></td>
    <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['progress'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%</strong></td>
    <!--<td><strong><?php echo $this->_tpl_vars['item']['project_manager']; ?>
</strong></td>-->
    <td class="table-right-2">
    <strong>
        <a href="javascript:void(0)" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Assignment Details</a>
    </strong>
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
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None","CaseInsensitiveString", "Number", "CaseInsensitiveString"]);

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

<?php else: ?>

<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Start Date</td>
    <!--<td nowrap class="columnHeadInactiveBlack">Campaign Due Date</td>-->
    <td nowrap class="columnHeadInactiveBlack">Progress</td>
    <!--<td nowrap class="columnHeadInactiveBlack">Project Manager</td>-->
    <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
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
    <td class="table-left-2"><strong><a href="/article/article_keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></strong></td>
    <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</strong></td>
    <!--<td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</strong></td>-->
    <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['progress'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%</strong></td>
    <!--<td><strong><?php echo $this->_tpl_vars['item']['project_manager']; ?>
</strong></td>-->
    <td class="table-right-2"><strong><a href="javascript:void(0)" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Assignment Details</a></strong></td>
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

<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "CaseInsensitiveString", "Date", "Date", "Number", "CaseInsensitiveString"]);

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
<?php endif; ?>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>