<?php /* Smarty version 2.6.11, created on 2016-04-25 10:33:49
         compiled from client_campaign/cp_production_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_production_report.html', 51, false),array('function', 'eval', 'client_campaign/cp_production_report.html', 89, false),array('modifier', 'escape', 'client_campaign/cp_production_report.html', 95, false),array('modifier', 'default', 'client_campaign/cp_production_report.html', 97, false),array('modifier', 'date_format', 'client_campaign/cp_production_report.html', 100, false),)), $this); ?>
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
<div style="display:none" id="report_result" ></div>
<div id="page-box1">
  <h2>Writer Production Report</h2>
  <div id="campaign-search" >
      <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="" method="get">
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td>From:</td>
    <td>
      <input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $_GET['date_start']; ?>
" readonly/>
      <input type="button" class="button" id="btn_cal_date_start" value="..." />
      <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_start",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_date_start",
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
     </td>
    <td>To:</td>
    <td>
      <input type="text" name="date_end" id="date_end" size="10" maxlength="10" value="<?php echo $_GET['date_end']; ?>
" readonly/>
      <input type="button" class="button" id="btn_cal_date_end" value="...">
      <script type="text/javascript">
      Calendar.setup({
          inputField  : "date_end",
          ifFormat    : "%Y-%m-%d",
          showsTime   : false,
          button      : "btn_cal_date_end",
          singleClick : true,
          step        : 1,
          range       : [1990, 2030]
      });
      </script>
    </td>
    <td  nowrap>Campaign</td>
    <td><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaign_list'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
    <td nowrap>Role</td>
    <td><select name="user_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users_types'],'selected' => $_GET['user_type']), $this);?>
</select></td>
    <td  nowrap>Show:</td>
    <td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td colspan="4"><input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='<?php echo $this->_tpl_vars['actionurl']; ?>
'" />&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="$('f_assign_keyword_return').action='<?php echo $this->_tpl_vars['exporturl']; ?>
'" /></td>
  </tr>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="/client_campaign/cp_production_report.php" name="users_list" id="users_list<?php echo $this->_tpl_vars['item']['user_id']; ?>
" method="post" >
  <input type="hidden" name="user_id" />
  <input type="hidden" name="operation"  value="'auto_reminder'" />
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">No.</td>
    <td nowrap class="columnHeadInactiveBlack">User</td>
    <td nowrap class="columnHeadInactiveBlack">First Name</td>
    <td nowrap class="columnHeadInactiveBlack">Last Name</td>
    <td nowrap class="columnHeadInactiveBlack">Email</td>
    <td nowrap class="columnHeadInactiveBlack">Total Campaigns in All Reports</td>
    <td nowrap class="columnHeadInactiveBlack">Total Assigned</td>    
    <td nowrap class="columnHeadInactiveBlack"># Of Submitted</td>
    <td nowrap class="columnHeadInactiveBlack"># Of Editor Approved</td> 
    <td nowrap class="columnHeadInactiveBlack"># Of Client Approved</td> 
    <td nowrap class="columnHeadInactiveBlack">Last Login</td>
      <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>	
      <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
	</tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr id="tr<?php echo $this->_tpl_vars['item']['user_id']; ?>
" class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><a href="javascript:openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'height=370,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['last_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td nowrap ><?php echo $this->_tpl_vars['item']['total_camp']; ?>
 <?php if ($this->_tpl_vars['item']['total_camp']): ?><a href="javascript:void(0)" onclick="appendRsToObj($('tr<?php echo $this->_tpl_vars['item']['user_id']; ?>
'),this,<?php echo $this->_tpl_vars['item']['total_camp']; ?>
, '<?php echo $this->_tpl_vars['ajaxurl']; ?>
?user_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
&role=<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['role'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo ((is_array($_tmp=$this->_tpl_vars['query_string'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'report_result');return false;" >View Campaigns</a><?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['total'] > 0): ?><a href="/article/articles.php?copy_writer_id=<?php echo $this->_tpl_vars['item']['user_id']; ?>
"   ><?php echo $this->_tpl_vars['item']['total']; ?>
</a><?php else: ?>0<?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_submit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_editor_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['total_client_approval'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>
    <td nowrap><?php if ($this->_tpl_vars['item']['time'] != NULL): ?><font color="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M:%S") : smarty_modifier_date_format($_tmp, "%D %H:%M:%S")); ?>
</font><?php else: ?>&nbsp;<?php endif; ?></td>
	  <td align="right" nowrap class="table-right-2">
      <a href="/mail/ck_mailer.php?list1=<?php echo $this->_tpl_vars['item']['user_id']; ?>
" ><input type="button" value="send mail" class="button" /></a>
      <input type="submit" class="button" value="auto-reminder" onclick="return sendEmail('users_list', 'user_id', '<?php echo $this->_tpl_vars['item']['user_id']; ?>
', 'operation', 'auto_reminder')" />
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
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>