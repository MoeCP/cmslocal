<?php /* Smarty version 2.6.11, created on 2014-07-23 00:41:38
         compiled from client_campaign/client_login_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'client_campaign/client_login_list.html', 55, false),array('modifier', 'string_format', 'client_campaign/client_login_list.html', 57, false),)), $this); ?>
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
    <strong>You can enter the "client name","campaign name","company name" etc. into the keyword input to search the relevant client's campaign information</strong>
     <div id="campaign-search-box" >
     
<table border="0" cellspacing="1" cellpadding="4">
  <form name="f_assign_keyword_return" action="/client_campaign/list.php" method="get">
  <tr>
    <td nowrap>Campaign Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword"></td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="70%">&nbsp;</td>
  </tr>
  </form>
</table><br>
    </div>
  </div>
</div>

<div class="tablepadding"> 
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<table id="table-1" cellspacing="0" cellpadding="0" class="sortableTable" width="100%">
  <form action="/client_campaign/list.php" name="campaign_list" method="post" />
  <input type="hidden" name="campaign_id" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
    <td nowrap class="columnHeadInactiveBlack table-left-2">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Progress</td>
    <td nowrap class="columnHeadInactiveBlack">Project Manager</td>
        <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left-2"><strong><a href="/article/download_article_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
"><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></strong></td>
    <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</strong></td>
    <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</strong></td>
    <td><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['progress'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%</strong></td>
    <td><strong><?php echo $this->_tpl_vars['item']['project_manager']; ?>
</strong></td>
            <td class="table-right-2" align="center">
    <?php if ($this->_tpl_vars['item']['style_id'] > 0): ?>
    <input type="button" class="button" value="Edit Style Guide" onclick="openWindow('/client_campaign/campaign_style_guide_form.php?style_id=<?php echo $this->_tpl_vars['item']['style_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />
    <?php else: ?>
    <input type="button" class="button" value="Add Style Guide" onclick="openWindow('/client_campaign/campaign_style_guide_form.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=650,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['item']['status'] == -1): ?>
    <input type="button" class="button" value="Add Campaign" onclick="openLink('/client_campaign/campaign_questions.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
    <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </form>
</table>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table><br />
</div>
</div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None","CaseInsensitiveString", "Date", "Date", "Number", "CaseInsensitiveString"]);

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