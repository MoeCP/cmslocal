<?php /* Smarty version 2.6.11, created on 2012-03-15 10:07:18
         compiled from user/available_specialites_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/available_specialites_report.html', 20, false),array('modifier', 'nl2br', 'user/available_specialites_report.html', 102, false),)), $this); ?>
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
  <h2>Copywriters/Editors Specialties and Availability</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
  <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="#" method="get">
  <table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>User Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
" /></td>
    <td nowrap>User Type</td>
    <td><select name="permission"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_types'],'selected' => $_GET['user_type']), $this);?>
</select></td>
    <td nowrap>Pay Level</td>
    <td><select name="pay_level"><option value="">[show all]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['pay_levels'],'output' => $this->_tpl_vars['pay_levels'],'selected' => $_GET['pay_level']), $this);?>
</select></td>
    <td nowrap>Interests</td>
    <td><select name="cp_category" id="cp_category" >
    <?php $_from = $this->_tpl_vars['cp_interests']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($_GET['cp_category'] == $this->_tpl_vars['k']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']['name']; ?>
</option>
    <?php $_from = $this->_tpl_vars['i']['chidren']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subk'] => $this->_tpl_vars['name']):
?>
    <option value="<?php echo $this->_tpl_vars['subk']; ?>
" <?php if ($_GET['cp_category'] == $this->_tpl_vars['subk']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
    </select></td>
    <td  nowrap>Show:</td>
    <td nowrap ><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td colspan="4" nowrap rowspan="2" ><input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='/user/available_specialties_report.php';"/>&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="$('f_assign_keyword_return').action='/user/export_available_specialties_list.php';" /></td>
  </tr>
  <tr>
    <td nowrap colspan="8" >
      <input type="text" name="c_date_start" id="c_date_start" size="15" maxlength="10" value="<?php echo $_GET['c_date_start']; ?>
" readonly/>
        <input type="button" class="button" id="btn_cal_c_date_start" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "c_date_start",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_c_date_start",
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
        &lt= Unavailable Date &lt=
              <input type="text" name="c_date_end" id="c_date_end" size="15" maxlength="10" value="<?php echo $_GET['c_date_end']; ?>
" readonly/>
        <input type="button" class="button" id="btn_cal_c_date_end" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "c_date_end",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_c_date_end",
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
    </td>
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
    <td nowrap class="columnHeadInactiveBlack table-left-2">No.</td>
    <td nowrap class="columnHeadInactiveBlack">User</td>
    <td nowrap class="columnHeadInactiveBlack">First Name</td>
    <td nowrap class="columnHeadInactiveBlack">Last Name</td>
    <td nowrap class="columnHeadInactiveBlack">Email</td>
    <td nowrap class="columnHeadInactiveBlack">Pay Level</td>
    <td nowrap class="columnHeadInactiveBlack">Category - Level of Expertise</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Dates Unavailable</td>
    <th class="table-right-corner">&nbsp;&nbsp;</th>
</tr>
  </thead>
  <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
        <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'newwindow', 'height=370,width=280,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td>Level <?php echo $this->_tpl_vars['item']['pay_level']; ?>
</td>
    <td nowrap>
    <?php $_from = $this->_tpl_vars['item']['specialies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sitem']):
?>
    <?php if ($this->_tpl_vars['sitem']['is_link']): ?><a href="javascript:void(0)" onclick="showDialog('<?php echo $this->_tpl_vars['item']['user_id']; ?>
','<?php echo $this->_tpl_vars['sitem']['category_id']; ?>
')"><?php echo $this->_tpl_vars['sitem']['name']; ?>
</a><?php else:  echo $this->_tpl_vars['sitem']['name'];  endif; ?><br />
    <?php endforeach; endif; unset($_from); ?>
    </td>
    <td class="table-right-2"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['unavailable'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
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
  ["None", "Number", "CaseInsensitiveString", "CaseInsensitiveString",  "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Number","Number", "CaseInsensitiveString"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
function showDialog(user_id, cid) {
  var url = \'/user/show_specialite.php?user_id=\' + user_id + \'&cid=\' + cid;
  showWindowDialog(url, 500, 300, "Show Description");
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>