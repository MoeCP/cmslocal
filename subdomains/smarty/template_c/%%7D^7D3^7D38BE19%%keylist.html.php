<?php /* Smarty version 2.6.11, created on 2013-04-16 11:26:12
         compiled from client/keylist.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client/keylist.html', 65, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script language="JavaScript">
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
<?php endif; ?>
var url = '<?php echo $this->_tpl_vars['url']; ?>
';
<?php echo '
function generate_api_key(cu_id, client_id)
{
//    $(\'operation\').value = \'generate\';
//    ajaxApiKeyPost(cu_id);
  window.location.href="generatekey.php?cu_id=" +cu_id + \'&client_id=\' + client_id  ;
}

function sent_api_key(cu_id) 
{
  $(\'operation\').value = \'sent\';
  ajaxApiKeyPost(cu_id);
}

function operation_api(opt, cu_id)
{
  if (opt == \'delete\')
  {
      if (!confirm(\'If you delete this api, you can\\\'t do any operations for this api key. Are you sure to delete it? \'))
      { return false;
      }
  }
  $(\'operation\').value = opt;
  ajaxApiKeyPost(cu_id);
}

function ajaxApiKeyPost(cu_id)
{
    $(\'cu_id\').value = cu_id;
    ajaxSubmit(url, \'f_keylist\' , \'post_result_div\', \'post\')
}
'; ?>

</script>

<form action="/client/keylist.php" id="f_keylist"  name="f_keylist" method="post" >
  <input type="hidden"  id="cu_id"  name="cu_id" />
  <input type="hidden"  id="operation"  name="operation" />
</form>

<div id="page-box1">
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
 <table width="100%"  cellspacing="1" cellpadding="4"><tr><td align="left">
  <h2>Client API Key List</h2></td><td>
  <ul id="campaign-nav">
    <li><input type="button" onclick="window.location.href='/client/generatekey.php?client_id=<?php echo $_GET['client_id']; ?>
&pfrom=keylist'" value="Generate API Key" class="button" /></li>
  </ul></td></tr>
</table>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" action="/client/keylist.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td nowrap>Domain</td>
        <td><input type="text" name="domain" id="domain" value="<?php echo $_GET['domain']; ?>
"></td>
        <td nowrap>Client</td>
        <td><select name="client_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['clients'],'selected' => $_GET['client_id']), $this);?>
</select></td>
        <td>API Type</td>
        <td><select name="apitype"><option value="" >[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['types'],'selected' => $_GET['apitype']), $this);?>
</select></td>
        <td><input type="image" src="/images/button-search.gif" value="submit"></td>
      </tr>
    </table>
    </form>
    </div>
  </div>
  <?php else: ?>
   <table width="100%"  cellspacing="1" cellpadding="4"><tr><td align="left">
    <h2>Client API Key List</h2></td><td>
    <ul id="campaign-nav">
      <li><input type="button" onclick="openWindow('http://www.box.net/shared/4i6847lhr9sco3jqksvx')" value="Download WP Plugin" class="button" /></li>
    </ul></td></tr>
  </table>
  <?php endif; ?>
</div>
<div class="tablepadding"> 
  <div id="post_result_div"></div>
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
<thead>
<tr class="sortableTab">
  <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
  <td nowrap class="columnHeadInactiveBlack">User Name</td>
  <td nowrap class="columnHeadInactiveBlack">Email</td>
  <td nowrap class="columnHeadInactiveBlack">API Key</td>
  <td nowrap class="columnHeadInactiveBlack">Token</td>
    <td nowrap class="columnHeadInactiveBlack">Domain</td>
  <td nowrap class="columnHeadInactiveBlack">Client</td>
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
  <td><?php echo $this->_tpl_vars['item']['user']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['apikey']; ?>
</td>
  <td><?php echo $this->_tpl_vars['item']['token']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['domain']; ?>
</td>
  <td><a href="javascript:showClientDialog('<?php echo $this->_tpl_vars['item']['client_id']; ?>
');"><?php echo $this->_tpl_vars['item']['company_name']; ?>
</a></td>
  <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['item']['total_tags'] > 0): ?>
    <input type="button" class="button" value="View Tags" onclick="javascript:showDialog(<?php echo $this->_tpl_vars['item']['client_user_id']; ?>
)" />
    <?php endif; ?>    
    <?php if ($this->_tpl_vars['item']['apisig'] == ''): ?>
    <input type="button" class="button" value="Generate API Key" onclick="javascript:generate_api_key('<?php echo $this->_tpl_vars['item']['client_user_id']; ?>
', '<?php echo $this->_tpl_vars['item']['client_id']; ?>
')" />
    <?php else: ?>
    <input type="button" class="button" value="Send API Key" onclick="javascript:operation_api('sent', <?php echo $this->_tpl_vars['item']['client_user_id']; ?>
)" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
    <input type="button" class="button" value="Delete" onclick="javascript:operation_api('delete', <?php echo $this->_tpl_vars['item']['client_user_id']; ?>
)" />
    <?php endif; ?>
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
st.asyncSort(0);
function showDialog(source) {
  var url = \'/client/tags.php?source=\' + source;
  showWindowDialog(url, 500, 350, "Domain Tags");
};
'; ?>

//]]>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>