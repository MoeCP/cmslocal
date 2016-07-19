<?php /* Smarty version 2.6.11, created on 2012-04-22 23:46:09
         compiled from client/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client/list.html', 21, false),array('modifier', 'default', 'client/list.html', 25, false),)), $this); ?>
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
    <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" action="/client/list.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td nowrap>Client Search ( name, company name )</td>
        <td><input type="text" name="keyword" id="search_keyword"></td>
        <td>Agency</td>
        <td><select name="agency_id"><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_agency'],'selected' => $_GET['agency_id']), $this);?>
</select></td>
        <td>Client Status</td>
        <td><select name="status"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['total_status'],'selected' => $_GET['status']), $this);?>
</select></td>
        <td>Show:</td>
        <td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => ((is_array($_tmp=@$_GET['perPage'])) ? $this->_run_mod_handler('default', true, $_tmp, 50) : smarty_modifier_default($_tmp, 50))), $this);?>
</select> row(s)</td>
        <td><input type="image" src="/images/button-search.gif" value="submit"></td>
      </tr>
    </table>
    </form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="/client/list.php" name="client_list" method="post" />
<input type="hidden" name="client_id" />
<input type="hidden" name="form_refresh" value="N" />
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
<thead>
<tr class="sortableTab">
  <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
  <td nowrap class="columnHeadInactiveBlack">User Name</td>
  <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
  <td nowrap class="columnHeadInactiveBlack">Password</td>
  <td nowrap class="columnHeadInactiveBlack">Contact Name</td>
  <?php endif; ?>
  <td nowrap class="columnHeadInactiveBlack">Company Name</td>
  <td nowrap class="columnHeadInactiveBlack">City</td>
  <td nowrap class="columnHeadInactiveBlack">State</td>
  <td nowrap class="columnHeadInactiveBlack">Zip</td>
  <td nowrap class="columnHeadInactiveBlack">Email</td>
  <td nowrap class="columnHeadInactiveBlack">Status</td>
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
  <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
  <!-- <td><a href="javascript:openWindow('/client/ajax_client_set.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
', 'newwindow', 'height=300,width=300,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td> -->
   <td><a href="javascript:showClientDialog('<?php echo $this->_tpl_vars['item']['client_id']; ?>
');"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td>
  <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
  <td><?php echo $this->_tpl_vars['item']['user_pw']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['contact_name']; ?>
</td>
  <?php endif; ?>
  <td><?php echo $this->_tpl_vars['item']['company_name']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['city']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['state']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['zip']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['status']; ?>
</td>
  <td align="right" nowrap class="table-right-2">
<input type="button" class="button" value="Update" onclick="javasript:window.location='/client/client_set.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
';" />
<?php if ($this->_tpl_vars['user_permission_int'] >= 4 || $this->_tpl_vars['user_permission_int'] >= 2): ?>
<input type="button" class="button" value="Generate API Key" onclick="javasript:window.location='/client/generatekey.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
';" />
<input type="button" class="button" value="API Keys" onclick="javasript:window.location='/client/keylist.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
';" />
<?php endif;  if ($this->_tpl_vars['user_permission_int'] >= 5 || $this->_tpl_vars['user_permission_int'] == 2): ?>
<input type="submit" class="button" value="Send Account Info" onclick="return sendEmail('client_list', 'client_id', '<?php echo $this->_tpl_vars['item']['client_id']; ?>
', 'form_refresh', 'send_account_info')" />
<input type="submit" class="button" value="<?php if ($this->_tpl_vars['item']['status'] == A): ?>Disable<?php else: ?>Enable<?php endif; ?>"  onclick="return changeUserStatus('client_list', 'client_id', '<?php echo $this->_tpl_vars['item']['client_id']; ?>
', <?php if ($this->_tpl_vars['item']['status'] == A): ?>'D'<?php else: ?>'A'<?php endif; ?>, <?php if ($this->_tpl_vars['item']['status'] == A): ?>'delete'<?php else: ?>'active'<?php endif; ?>, 'This Client')" />
<?php if ($this->_tpl_vars['user_permission_int'] >= 5): ?>
<input type="button" class="button" value="Keyword Field Settings" onclick="javasript:window.location='/client/keyword_fields.php?client_id=<?php echo $this->_tpl_vars['item']['client_id']; ?>
';" />
<?php endif;  endif; ?>
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

<?php echo '
<script type="text/javascript">
function showClientDialog(client_id) {
  var url = \'/client/ajax_client_set.php?client_id=\' + client_id;
  showWindowDialog(url, 500, 500, "Edit Client Info.");
};
</script>
'; ?>


<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  [null, "Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

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


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>