<?php /* Smarty version 2.6.11, created on 2013-06-09 01:11:59
         compiled from graphics/acceptance.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'graphics/acceptance.html', 25, false),array('function', 'eval', 'graphics/acceptance.html', 118, false),array('modifier', 'truncate', 'graphics/acceptance.html', 122, false),array('modifier', 'date_format', 'graphics/acceptance.html', 125, false),)), $this); ?>
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

<br />
<div id="page-box1">
  <div id="campaign-actions" >
  <div id="campaign-actions-label"> Assignment Acceptance</div>
  </div>
  <div id="campaign-search" >
    <strong>You can enter the "keyword","campaign name"  etc. into the keyword input to search the relevant campaign's keyword information</strong>
    <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/graphics/acceptance.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
       <td nowrap>Keyword</td>
       <td><input type="text" name="keyword" id="search_keyword"></td>
       <td nowrap>Image Status</td>
       <td colspan="1"><select name="image_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_statuses'],'selected' => $_GET['image_status']), $this);?>
</select></td>
       <td nowrap>Image Type</td>
       <td colspan="1"><select name="image_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_type'],'selected' => $_GET['image_type']), $this);?>
</select></td>
       <?php if ($this->_tpl_vars['login_permission'] <> 1): ?>
       <td nowrap>Editor Status</td>
       <td colspan="1"><select name="editor_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['assign_statuses'],'selected' => $_GET['editor_status']), $this);?>
</select>
       </td>
       <?php endif; ?>
       <?php if ($this->_tpl_vars['login_permission'] <> 3): ?>
       <td nowrap>Designer Status</td>
       <td colspan="1"><select name="cp_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['assign_statuses'],'selected' => $_GET['cp_status']), $this);?>
</select>
       </td>
       <?php endif; ?>
       <td nowrap>Show:</td>
       <td nowrap>
        <select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp;
        </td>
        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
      <td rowspan="2" >
        <input type="image" src="/images/button-search.gif" value="submit">
      </td>
    </tr>
    <tr>
      <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
      <td nowrap>Designer</td>
      <td><select name="copy_writer_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['copy_writer_id']), $this);?>
</select></td>
      <input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
      <td >Editor</td>
      <td><select name="editor_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
       <td>Client</td>
       <td><select name="client_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_clients'],'selected' => $_GET['client_id']), $this);?>
</select></td>
       <td nowrap>Campaign</td>
       <td nowrap><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
       <?php endif; ?>
      </tr>
    </table>
    </form>
    </div>
  </div>
</div>
<br>
<div class="tablepadding"> 
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" name="campaign_keyword_list" method="post" />
<input type="hidden" name="single_keyword_id" id="keyword_id" value="" />
<input type="hidden" name="user_status" id="user_status" value="" />
<input type="hidden" name="operation" id="operation" value="" />
<input type="hidden" name="form_refresh" value="N" />
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner" rowspan="2" >&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">
      <?php if ($this->_tpl_vars['show_cb']): ?><input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" /><?php endif; ?>    
    </td>
    
    <td nowrap class="columnHeadInactiveBlack table-left-2 table-right-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Image Number</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Designer</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Image Status</td>
    <td nowrap class="columnHeadInactiveBlack">Image Type</td>
    <td nowrap class="columnHeadInactiveBlack">Editor Status</td>
    <td nowrap class="columnHeadInactiveBlack">Designer Status</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_all'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_all']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop_all']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop_all']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td class="table-left" >&nbsp;</td>
  
   <td class="table-left-2" >
   <?php if ($this->_tpl_vars['item']['show_cb']): ?>
      <input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_foreach['loop_all']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', campaign_keyword_list)" />
   <?php endif; ?>
      <input type="hidden" name="keyword[]" id="keyword_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword']; ?>
" />
    <input type="hidden" name="keyword_id[]" id="keyword_id_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
" />
      <input type="hidden" name="note_id[]" id="note_id_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['note_id']; ?>
" />
      <input type="hidden" name="old_notes[]" id="notes_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['notes']; ?>
" />
   
   </td>
   
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop_all']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2 table-right-2" ><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['keyword']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['image_number']; ?>
</td>
    <td><?php if ($this->_tpl_vars['login_permission'] == 5): ?><a href="/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" target="_blank" ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</a><?php else: ?><a href="/article/acceptance.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</a><?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['login_permission'] <= 4):  echo $this->_tpl_vars['item']['cp_name'];  else: ?><a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['cp_name']; ?>
</a><?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['login_permission'] <= 4):  echo $this->_tpl_vars['item']['ue_name'];  else: ?><a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</a><?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo $this->_tpl_vars['image_statuses'][$this->_tpl_vars['item']['image_status']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['image_type'][$this->_tpl_vars['item']['image_type']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['assign_statuses'][$this->_tpl_vars['item']['editor_status']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['assign_statuses'][$this->_tpl_vars['item']['cp_status']]; ?>
</td>
    <td align="left" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['login_permission'] == '1.2' || $this->_tpl_vars['login_permission'] == 3): ?>
    <input type="button" class="button" value="Style Guide" onclick="javascript:openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
        <?php elseif ($this->_tpl_vars['login_permission'] == '1.2' && $this->_tpl_vars['item']['cp_status'] == -1 || $this->_tpl_vars['login_permission'] == 3 && $this->_tpl_vars['item']['editor_status'] == -1): ?>     <input type="button" class="button" value="Accept" onclick="javascript:assignedAction('<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
',1)" />
    <input type="button" class="button" value="Decline" onclick="javascript:assignedAction('<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
',0)" />
    <?php endif; ?>
     </td>
     <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </tbody>
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
  <?php if ($this->_tpl_vars['show_cb'] == true): ?>
   <table align="center">
    <tr>
    <td align="center" >
    <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
        <?php elseif ($this->_tpl_vars['login_permission'] == '1.2' || $this->_tpl_vars['login_permission'] == 3): ?>
    <input type="submit" value="Accept" class="button" onclick="$('user_status').value='1';" />
    <input type="submit" value="Decline" class="button" onclick="$('user_status').value='0';" />
    <?php endif; ?>
    </td>
    </tr>
    </table>
  <?php endif; ?>
  </form>

<script type="text/javascript">
//<![CDATA[

var st = new SortableTable(document.getElementById("table-1"),
  [<?php if ($this->_tpl_vars['is_pay_adjust'] != 1): ?>'None',<?php endif; ?>'None',"Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date", "None"]);
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
function assignedAction( keyword_id, status )
{
	var f  = document.campaign_keyword_list;
	f.keyword_id.value = keyword_id;
	f.user_status.value = status;
  f.operation.value = \'assignedAction\';
	f.submit();
}

function backWaitingAccept(keyword_id, role)
{
  var rolename  = (role == \'all\' ? \'copy writer and editor\' : (role==\'cp\') ? \'copy writer\': role);
    if (confirm(\'Are you sure back Waiting Accept for the \' + rolename)) {
        var f  = document.campaign_keyword_list;
        f.keyword_id.value = keyword_id;
        f.operation.value = (role == \'editor\') ? \'editorback\' : (role==\'cp\'?\'writerback\':\'allback\');
        f.user_status.value = -1;
        f.submit();
    }
}


var f_common = "document.f_assign_keyword.";
var f = document.f_assign_keyword;
function check_f_assign_keyword(result_count) {
  var is_checked;
  var f = document.f_assign_keyword;

  for (i = 1; i <= result_count; i++) {
    var is_update_id = \'isUpdate_\' + i;
    var keyword = document.getElementById("keyword_"+i).value;
    if (document.getElementById(is_update_id).checked)
    {
      is_checked = true;
    }
  }

  if (!is_checked) {
    alert("Please choose one keyword.");  
    return false;
  }

  if (f.editor_id.value.length == 0 && f.copy_writer_id.value.length == 0) {
      if (f.date_start.value.length == 0 && f.date_end.value.length == 0) {
          alert(\'Please choose a copywriter or a editor for keyword\');
          f.copy_writer_id.focus();
          return false;
      }
  }
  return true;
}

function confirm_reserve_content(obj)
{
  if (!obj.checked)
  {
      if (!confirm(\'Are you sure you don\\\'t want to keep articles upon reassignment\'))
      {
        obj.checked = true;
      }
  }
}


'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>