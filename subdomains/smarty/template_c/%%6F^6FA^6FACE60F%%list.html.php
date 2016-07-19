<?php /* Smarty version 2.6.11, created on 2016-06-08 07:44:07
         compiled from user/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/list.html', 21, false),array('function', 'eval', 'user/list.html', 76, false),array('modifier', 'default', 'user/list.html', 104, false),)), $this); ?>
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
  <h2>User List&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_tpl_vars['user_permission_int'] > 4): ?><input type="button" class="button" value="Add User" onclick="javasript:window.location='/user/user_add.php';" /><?php endif; ?></h2>
  <div id="campaign-search" >
    <strong>You can enter the "user name","first name","last name","role" etc. into the keyword input to search the relevant user's information</strong>
    <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/user/list.php" method="get">
  <input type="hidden" name="get_operation" value="search" />
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>User Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword"></td>
    <td   nowrap>Role</td>
    <td><select name="role"><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_roles'],'selected' => $_GET['role']), $this);?>
</select></td>
        <td   nowrap>User Status:</td>
    <td><select name="status"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['total_status'],'selected' => $_GET['status']), $this);?>
</select></td>
        <td   nowrap>Pay Level</td>
    <td><select name="pay_level"><option value="">[show all]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['pay_levels'],'output' => $this->_tpl_vars['pay_levels'],'selected' => $_GET['pay_level']), $this);?>
</select></td>
    <td   nowrap>Show:</td>
    <td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td><input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='/user/list.php';" />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="$('f_assign_keyword_return').action='/user/export_list.php';" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
</table><br>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <form action="/user/list.php" name="users_list" method="post" />
  <input type="hidden" name="user_id" />
  <input type="hidden" name="frequency" />
  <input type="hidden" name="form_refresh" value="N" />
  <input type="hidden" name="operation" value="delete" />
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">User Name</td>
    <td nowrap class="columnHeadInactiveBlack">Pay Level</td>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
    <td nowrap class="columnHeadInactiveBlack">Password</td>
    <td nowrap class="columnHeadInactiveBlack">User ID</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">First Name</td>
    <td nowrap class="columnHeadInactiveBlack">Last Name</td>
    <td nowrap class="columnHeadInactiveBlack">Sex</td>
    <td nowrap class="columnHeadInactiveBlack">Email</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">First Language</td>
    <td nowrap class="columnHeadInactiveBlack">Payment Preference</td>
    <td nowrap class="columnHeadInactiveBlack">Role</td>
    <td nowrap class="columnHeadInactiveBlack">Points Month/Total</td>
    <td nowrap class="columnHeadInactiveBlack">Total Client Rejected</td>
    <?php if ($this->_tpl_vars['current_user_id'] == 3 || $this->_tpl_vars['login_role'] == 'admin'): ?>
    <td nowrap class="columnHeadInactiveBlack">Auditing Frequency</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
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
    <td nowrap>
    <?php if ($this->_tpl_vars['user_permission_int'] > 4): ?>
    <a href="javascript:void(0)" onclick="showUserDialog(<?php echo $this->_tpl_vars['item']['user_id']; ?>
)" class="js-cp-box-user" data-name="<?php echo $this->_tpl_vars['item']['user_name']; ?>
" data-id="user<?php echo $this->_tpl_vars['item']['user_id']; ?>
"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a>
    <?php else: ?>
    <?php echo $this->_tpl_vars['item']['user_name']; ?>

    <?php endif; ?>
    </td>
    <td><?php if ($this->_tpl_vars['item']['pay_level'] > 0):  echo $this->_tpl_vars['item']['pay_level'];  else: ?>n/a<?php endif; ?></td>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
    <td><?php echo $this->_tpl_vars['item']['user_pw']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_id']; ?>
</td>
    <?php endif; ?>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['sex']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td>
      <?php if ($this->_tpl_vars['item']['status'] == A): ?>
        <label style="color:red" ><?php echo $this->_tpl_vars['total_status']['A']; ?>
<label>
      <?php elseif ($this->_tpl_vars['item']['status'] == D): ?>
        <?php echo $this->_tpl_vars['total_status']['D']; ?>

      <?php endif; ?>
     </td>
     <td><?php echo $this->_tpl_vars['item']['first_language']; ?>
</td>
     <td><?php echo $this->_tpl_vars['item']['pay_pref']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['role'];  if ($this->_tpl_vars['item']['user_type'] == '-1' && $this->_tpl_vars['item']['role'] == 'admin'): ?><label style="color:red;">(Limited)</label><?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['permission'] == 1 || $this->_tpl_vars['item']['permission'] == 3):  echo ((is_array($_tmp=@$this->_tpl_vars['stats'][$this->_tpl_vars['item']['user_id']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  else: ?>n/a<?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['permission'] == 1 || $this->_tpl_vars['item']['permission'] == 3):  echo $this->_tpl_vars['item']['total_rejected'];  else: ?>n/a<?php endif; ?></td>
    <?php if ($this->_tpl_vars['current_user_id'] == 3 || $this->_tpl_vars['login_role'] == 'admin'): ?>
    <td>
     <?php if ($this->_tpl_vars['item']['role'] == 'editor' || ( $this->_tpl_vars['item']['user_id'] != 3 && $this->_tpl_vars['item']['role'] == 'project manager' )): ?>
	<select name="auditing_frequency" onchange="return setAuditingFrequency( this,'<?php echo $this->_tpl_vars['item']['user_id']; ?>
' ,  'set_frequency')"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['auditing_frequency'],'selected' => $this->_tpl_vars['item']['auditing_frequency']), $this);?>
</select>
     <?php else: ?>
	n/a
     <?php endif; ?>
     </td>
    <?php endif; ?>
    <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['user_permission_int'] == 5): ?>
            <?php if (( $this->_tpl_vars['item']['permission'] == 1 || $this->_tpl_vars['item']['permission'] == 3 ) && ( $this->_tpl_vars['pay_plugin'] == 'QuickBook' || $this->_tpl_vars['pay_plugin'] == 'NetSuit' )): ?>
      <?php if ($this->_tpl_vars['pay_plugin'] == 'NetSuit'): ?>
      <input type="button" class="button" value="<?php if ($this->_tpl_vars['item']['vendor_id'] == 0): ?>Create Vendor<?php else: ?>Update Vendor<?php endif; ?> " onclick="javasript:window.location='/user/netsuite.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
'" />
      <?php else: ?>
      <input type="button" class="button" value="<?php if ($this->_tpl_vars['item']['qb_vendor_id'] == 0): ?>Create Vendor<?php else: ?>Update Vendor<?php endif; ?> " onclick="javasript:window.location='/user/qb_vendor.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
'" />
      <?php endif; ?>
    <?php endif; ?>
    <input type="button" class="button" value="Esign Send" onclick="javasript:window.location='/user/esign.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
'" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['item']['permission'] == 1 || $this->_tpl_vars['item']['permission'] == 3): ?>
    <input type="button" class="button" value="Add Note" onclick="showNoteDialog(<?php echo $this->_tpl_vars['item']['user_id']; ?>
)" />
    <input type="button" class="button" value="Notes" onclick="javasript:window.location='/user/notes.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
'" />
    <?php endif; ?>
     <input type="button" class="button" value="Profile" onclick="javasript:window.location='/user/user_detail.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
';" />
    <?php if ($this->_tpl_vars['user_permission_int'] != 4): ?>
    <input type="submit" class="button" value="Send Account Info" onclick="return sendEmail('users_list', 'user_id', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'operation', 'send_account_info')" />
     <input type="button" class="button" value="Update" onclick="javasript:window.location='/user/user_set.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
';" />
     <input type="submit" class="button" value="<?php if ($this->_tpl_vars['item']['status'] == A): ?>Disable<?php else: ?>Enable<?php endif; ?>" onclick="return changeUserStatus('users_list', 'user_id', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', <?php if ($this->_tpl_vars['item']['status'] == A): ?>'D'<?php else: ?>'A'<?php endif; ?>, <?php if ($this->_tpl_vars['item']['status'] == A): ?>'delete'<?php else: ?>'active'<?php endif; ?>, 'This User')" />
     <?php endif; ?>
     </td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </form>
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
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>"CaseInsensitiveString",<?php endif; ?> "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

<?php echo '
st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
function setAuditingFrequency( o, user_id, operation )
{
	var f = document.users_list;
	f.operation.value = operation;
	f.frequency.value = o.options[o.selectedIndex].value;
	f.user_id.value =user_id;
	f.submit();
}
function showUserDialog(user_id) {
  var url = \'/user/ajax_user_set.php?user_id=\' + user_id;
  showWindowDialog(url, 600, 500, "Edit User Info.");
}

function showNoteDialog(user_id) {
  var url = \'/user/ajax_note_add.php?user_id=\' + user_id;
  showWindowDialog(url, 500, 500, "Add User Note");
}

'; ?>

//]]>
</script>

<?php if ($this->_tpl_vars['loggedin_user_name'] == 'admin' || $this->_tpl_vars['loggedin_user_name'] == 'mmcglothan'): ?>
<script type="text/javascript" src="/js/agora/jquery-1.9.1.js"></script>
<script type="text/javascript" >  
jQuery.noConflict();  
</script>
<script src="/js/agora/chatbox.js"
    data-app-id="49A7332C-96F1-45C9-A3DA-7E78F2848ECD"
    data-access-token=""
    data-image-url="//content.copypress.com/images/bgcornerb.png"
    data-user-id="user<?php echo $_SESSION['user_id']; ?>
" data-user-name="<?php echo $this->_tpl_vars['loggedin_user_name']; ?>
"></script>
</script>

<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>