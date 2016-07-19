<?php /* Smarty version 2.6.11, created on 2013-01-18 09:39:08
         compiled from client_campaign/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'client_campaign/list.html', 73, false),)), $this); ?>
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
  <h2><?php if ($this->_tpl_vars['archived'] == 1): ?>Archived <?php endif; ?>Campaign List</h2>
  <div id="campaign-search" >
    <strong>Enter in the campaign name or keywords to narrow your search results</strong>
    <div id="campaign-search-box" >
  <form name="f_assign_keyword_return" action="/client_campaign/list.php" method="get">
  <input type="hidden" name="archived" id="archived" value="<?php echo $this->_tpl_vars['archived']; ?>
" />
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
<div class="tablepadding" >
<form action="/client_campaign/list.php" name="campaign_list" method="post" >
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <input type="hidden" name="campaign_id" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign ID</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Type</td>
    <td nowrap class="columnHeadInactiveBlack">Client Company Name</td>
	<?php if ($this->_tpl_vars['is_show']): ?>
    <td nowrap class="columnHeadInactiveBlack">Total Budget</td>
    <td nowrap class="columnHeadInactiveBlack">Cost/Article</td>
	<?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Total Google Clean Article</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Created Date</td>
    <td nowrap class="columnHeadInactiveBlack">Creator</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
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
    <td class="table-left-2">
      <?php if ($this->_tpl_vars['item']['campaign_type'] == 2): ?>
        <a href="/client_campaign/image_keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&archived=<?php echo $this->_tpl_vars['archived']; ?>
"  ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a>
      <?php else: ?>
        <a href="/client_campaign/keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&archived=<?php echo $this->_tpl_vars['archived']; ?>
"  ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a>
      <?php endif; ?>
    </td>
    <td><?php echo $this->_tpl_vars['item']['campaign_id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['campaign_type'][$this->_tpl_vars['item']['campaign_type']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['company_name']; ?>
</td>
	<?php if ($this->_tpl_vars['is_show']): ?>
    <td><?php echo $this->_tpl_vars['item']['total_budget']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['cost_per_article']; ?>
</td>
	<?php endif; ?>
    <td><?php echo $this->_tpl_vars['item']['total_gc']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_created'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['creator_user']; ?>
</td>
    <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['archived'] != 1): ?>
    <?php if ($this->_tpl_vars['item']['campaign_type'] == 1): ?>
    <?php if ($this->_tpl_vars['login_permission'] > 3 || $this->_tpl_vars['login_permission'] == 2): ?>	<?php if ($this->_tpl_vars['login_permission'] > 3): ?>
	  <input type="button" class="button" value="Add Keyword" onclick="openLink('/client_campaign/keyword_add.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
    <?php if ($this->_tpl_vars['login_permission'] >= 5): ?>
    <input type="button" class="button" value="Import Keywords" onclick="openLink('/client_campaign/uploadkeywordfile.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['item']['parent_id'] > 0 && $this->_tpl_vars['item']['is_import_kw'] == 0): ?>
    <input type="button" class="button" value="Replicate Keyword" onclick="openLink('/client_campaign/keyword_add.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&pid=<?php echo $this->_tpl_vars['item']['parent_id']; ?>
');" />
    <?php endif; ?>
	  <input type="button" class="button" value="Assign Keyword" onclick="openLink('/client_campaign/batch_assign_keyword.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
	  <?php endif; ?>
	  	  	    <input type="button" class="button" value="Update" onclick="openLink('/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
      <?php if ($this->_tpl_vars['login_permission'] >= 5): ?>
      <input type="submit" class="button" value="Delete" onclick="return deleteSubmit('campaign_list', 'campaign_id', '<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'D', 'This Client Campaign')" />
      <?php endif; ?>
     <?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] == 'admin' || $this->_tpl_vars['login_role'] == 'client'): ?>
	  <input type="button" class="button" value="Download Article" onclick="openLink('/article/download_article_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
    <?php endif; ?>
    <?php else: ?>
     <?php if ($this->_tpl_vars['login_permission'] > 3 || $this->_tpl_vars['login_permission'] == 2): ?>      <?php if ($this->_tpl_vars['login_permission'] > 3): ?>
      <input type="button" class="button" value="Add Keyword" onclick="openLink('/client_campaign/image_keyword_add.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
      <?php endif; ?>
     <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] == 'admin' || $this->_tpl_vars['login_role'] == 'project manager'): ?>
     <input type="button" class="button" value="Editorial notes" onclick="openLink('/client_campaign/campaign_notes.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
    <?php endif; ?>
   <?php endif; ?>
    </td>
<td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>

</table>
</form>
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
  ["None","CaseInsensitiveString", "CaseInsensitiveString", "Number", "Number", "Date", "Date", "None"]);

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