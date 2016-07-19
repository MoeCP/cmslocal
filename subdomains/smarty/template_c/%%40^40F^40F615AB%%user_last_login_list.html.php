<?php /* Smarty version 2.6.11, created on 2012-04-24 13:11:55
         compiled from user/user_last_login_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/user_last_login_list.html', 19, false),array('modifier', 'date_format', 'user/user_last_login_list.html', 68, false),)), $this); ?>
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
  <h2>User Access Report</h2>
  <div id="campaign-search" >
    <strong>Search user's login information by role</strong>
    <div id="campaign-search-box" >
  <form name="f_assign_keyword_return" action="/user/user_last_login_list.php" method="get">
  <table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td  nowrap>Role</td>
    <td><select name="role"><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_roles'],'selected' => $_GET['role']), $this);?>
</select></td>
    <td  nowrap>Show:</td>
    <td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
  </table>
  </form>   
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">User Name</td>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
    <td nowrap class="columnHeadInactiveBlack">Password</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">First Name</td>
    <td nowrap class="columnHeadInactiveBlack">Last Name</td>
    <td nowrap class="columnHeadInactiveBlack">Sex</td>
    <td nowrap class="columnHeadInactiveBlack">Email</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Role</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Last Login Time</td>
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
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'newwindow', 'height=300,width=280,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td>

    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
    <td><?php echo $this->_tpl_vars['item']['user_pw']; ?>
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
    <td><?php echo $this->_tpl_vars['item']['status']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['role']; ?>
</td>
    <td nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['item']['time'] != NULL): ?><font color="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M:%S") : smarty_modifier_date_format($_tmp, "%D %H:%M:%S")); ?>
</font>
    <?php else: ?>
	<form action="/user/user_last_login_list.php" method="post" name="form_<?php echo $this->_tpl_vars['item']['user_id']; ?>
" >
    	<input name="button" type="submit" value="send welcome email">
    	<input name="user_id" type="hidden" value="<?php echo $this->_tpl_vars['item']['user_id']; ?>
">
    	<input name="operation" type="hidden" value="welcome_email">
	</form>
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
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>