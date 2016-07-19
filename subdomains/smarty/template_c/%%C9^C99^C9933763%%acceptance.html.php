<?php /* Smarty version 2.6.11, created on 2014-09-10 15:37:26
         compiled from article/acceptance.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/acceptance.html', 25, false),array('function', 'eval', 'article/acceptance.html', 118, false),array('modifier', 'truncate', 'article/acceptance.html', 122, false),array('modifier', 'date_format', 'article/acceptance.html', 125, false),)), $this); ?>
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
    <!--<strong>You can enter the "keyword","campaign name"  etc. into the keyword input to search the relevant campaign's keyword information</strong>-->
    <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/article/acceptance.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
       <td nowrap>Keyword</td>
       <td><input type="text" name="keyword" id="search_keyword"></td>
       <td nowrap>Article Status</td>
       <td colspan="1"><select name="article_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_statuses'],'selected' => $_GET['article_status']), $this);?>
</select></td>
       <td nowrap>Article Type</td>
       <td colspan="1"><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select></td>
       <?php if ($this->_tpl_vars['login_permission'] <> 1): ?>
       <td nowrap>Editor Status</td>
       <td colspan="1"><select name="editor_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['assign_statuses'],'selected' => $_GET['editor_status']), $this);?>
</select>
       </td>
       <?php endif; ?>
       <?php if ($this->_tpl_vars['login_permission'] <> 3): ?>
       <td nowrap>Copywriter Status</td>
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
      <td nowrap>Copywriter</td>
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
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Writer Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Article Status</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Editor Status</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter Status</td>
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
    <td><?php echo $this->_tpl_vars['item']['article_number']; ?>
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
    <td><?php echo $this->_tpl_vars['article_statuses'][$this->_tpl_vars['item']['article_status']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
        <td><?php if ($this->_tpl_vars['item']['editor_status'] == -2): ?>Auto Denied<?php else:  echo $this->_tpl_vars['assign_statuses'][$this->_tpl_vars['item']['editor_status']];  if ($this->_tpl_vars['item']['editor_status'] == 0 && $this->_tpl_vars['item']['e_deny_memo'] && $this->_tpl_vars['login_permission'] >= 4): ?><br />Reason:<?php echo $this->_tpl_vars['item']['e_deny_memo'];  endif;  endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['cp_status'] == -2): ?>Auto Denied<?php else:  echo $this->_tpl_vars['assign_statuses'][$this->_tpl_vars['item']['cp_status']];  if ($this->_tpl_vars['item']['cp_status'] == 0 && $this->_tpl_vars['item']['deny_memo'] && $this->_tpl_vars['login_permission'] >= 4): ?><br />Reason:<?php echo $this->_tpl_vars['item']['deny_memo'];  endif;  endif; ?></td>
    <td align="left" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['login_permission'] == 1 || $this->_tpl_vars['login_permission'] == 3): ?>
    <input type="button" class="button" value="Style Guide" onclick="javascript:openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
    <?php if ($this->_tpl_vars['item']['show_cb']): ?>
    <input type="button" class="button" value="Re-Assign" onclick="openLink('/client_campaign/assign_keyword.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&frm=acceptance');" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['item']['editor_status'] == '0' || $this->_tpl_vars['item']['cp_status'] == '0'): ?>
    <input type="button" class="button" value="Back Waiting Accept" onclick="javascript:backWaitingAccept('<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
','<?php if ($this->_tpl_vars['item']['editor_status'] == '0' && $this->_tpl_vars['item']['cp_status'] == '1'): ?>editor<?php elseif ($this->_tpl_vars['item']['editor_status'] == '1' && $this->_tpl_vars['item']['cp_status'] == '0'): ?>cp<?php else: ?>all<?php endif; ?>')" />
    <?php endif; ?>
    <input type="button" class="button" value="Update" onclick="openLink('/client_campaign/keyword_set.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
');" />
    <?php elseif ($this->_tpl_vars['login_permission'] == 1 && $this->_tpl_vars['item']['cp_status'] == -1 || $this->_tpl_vars['login_permission'] == 3 && $this->_tpl_vars['item']['editor_status'] == -1): ?>     <input type="button" class="button" value="Accept" onclick="javascript:assignedAction('<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
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
    <table border="0" cellspacing="1" cellpadding="4" width="100%">
  <tr>
    <td >
    <table class="helpTable" cellspacing="0" cellpadding="4">
      <tr><td valign="top">&nbsp;&#8226;&nbsp;</td><td>Please choose some keywords that you need update,enter the relevant information you need,then submit it.</td></tr></table></td>
  </tr>
    <tr>
    <td>
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td class="dateLable">Editor Notes</td>
        <td><textarea name="notes" cols="60" rows="4" id="notes"><?php echo $_POST['notes']; ?>
</textarea></td>
        <td>New or Append</td>
        <td>
          <select name="new_or_append">
            <option value="Append">Append</option>
            <option value="New">New</option>
          </select>
	      </td>
        <td></td>
      </tr> 
     </table>
   </td>
  </tr>
  <tr>
  <td>
  <table border="0" cellspacing="1" cellpadding="4">
  <tr>
  <td class="dateLable">Start Date</td>
  <td><input name="date_start" type="text" id="date_start" readonly value="<?php echo $_POST['date_start']; ?>
" />
    <input type="button" value="..." id="btn_cal_date_start" class="button">
    <script type="text/javascript">
    Calendar.setup({
      inputField  : "date_start",
      ifFormat  : "%Y-%m-%d",
      showsTime   : false,
      button    : "btn_cal_date_start",
      singleClick : true,
      step    : 1,
      range     : [1990, 2030]
    });
    </script></td>
  <td class="dateLable">Due Date</td>
  <td>
    <input name="date_end" type="text" id="date_end" readonly value="<?php echo $_POST['date_end']; ?>
" />
    <input type="button" value="..." id="btn_cal_date_end" class="button">
    <script type="text/javascript">
    Calendar.setup({
      inputField  : "date_end",
      ifFormat  : "%Y-%m-%d",
      showsTime   : false,
      button    : "btn_cal_date_end",
      singleClick : true,
      step    : 1,
      range     : [1990, 2030]
    });
    </script></td>
  </tr>
  </table></td></tr>
  <tr>
    <td>
      <input type="checkbox" name="is_forced_not_free" id="is_forced_not_free" value="1" />
      <label for="is_forced_not_free" >Force Assign when the copywriter/editor is busy between start date and Due Date</label><br />
			<input type="checkbox" name="is_reserve_content" id="is_reserve_content" value="1" checked onclick="confirm_reserve_content(this)"/>
      <label for="is_reserve_content" >Keep article content upon reassignment</label>
      <br />
    </td>
  </tr>
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="4">
        <tr>
          <td class="dateLable">Copywriter</td>
          <td>
          <select name="copy_writer_id" id="assign_copy_writer_id" >
            <option value="">[choose]</option>
            <option value="0">No Copywriter</option>
            <?php echo $this->_tpl_vars['copy_writer_options']; ?>

          </select>
          </td>
          <td><a href="javascript:void(0)" onclick="if ($('assign_copy_writer_id').value) javascript:openWindow('/client_campaign/cp_assignment_ranking.php?copywriter_id=' + $('assign_copy_writer_id').value + '&campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes'); else alert('Please choose a copywriter');" >View Copywriter's Assignment Ranking</a></td>
        </tr>
      </table>
    </td>
  </tr>
<?php if ($this->_tpl_vars['login_permission'] >= 5): ?>
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="4">
        <tr>
          <td class="dateLable">QAer</td>
          <td>
          <select name="qaer_id" id="assign_qaer_id" >
            <option value="">[choose]</option>
            <option value="0">No QAer</option>
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_qaer'],'selected' => $this->_tpl_vars['campaign_info']['qaer_id']), $this);?>
</select>
          </select>
          </td>
        </tr>
      </table>
    </td>
  </tr>  
<?php endif; ?>
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="4">
        <tr>
          <?php if ($this->_tpl_vars['campaign_info']['article_type'] == -1 || $this->_tpl_vars['campaign_info']['article_type'] == ''): ?>
          <td class="dateLable">Article Type</td>
          <td>
            <select name="article_type">
              <option value="">[default]</option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['leaf_types'],'selected' => $_POST['article_type']), $this);?>

            </select>
          </td>
          <?php endif; ?>
          <td class="dateLable">Editor
          <select name="editor_id" id="assign_editor_id" >
            <option value="">[choose]</option>
            <option value="0">No Editor</option>
            <?php echo $this->_tpl_vars['editor_options']; ?>

          </select>
          </td>
          <td colspan="2">
            <input type="submit" class="button" value="Re-Assign" />&nbsp;&nbsp;
            <input type="reset" class="button" value="Reset" />
          </td>
        </tr>
      </table>
     </td>
     </tr>
     </table>
    <?php elseif ($this->_tpl_vars['login_permission'] == 1 || $this->_tpl_vars['login_permission'] == 3): ?>
    <input type="submit" value="Accept" class="button" onclick="$('user_status').value='1';" />
    <input type="button" value="Decline" class="button" onclick="denyKeywords();" />
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
  if (status == 1)
  {
    var f  = document.campaign_keyword_list;
    f.keyword_id.value = keyword_id;
    f.user_status.value = status;
    f.operation.value = \'assignedAction\';
    f.submit();
  } else if (status == 0) {
     url = \'/article/decline_article.php?keyword_id=\' + keyword_id;
     showWindowDialog(url, 600, 500, \'What is your reason for declining this article\');
  }
}

function denyKeywords()
{
    var f = document.campaign_keyword_list;
    var ck_values = document.getElementsByName(\'isUpdate[]\'); 
    var keyword_id = \'\';
    for (var i =0;i < ck_values.length ; i++) {
      if (ck_values[i].checked) {
        keyword_id += \'|\' + $(\'keyword_id_\' + ck_values[i].value).value;
      }
    }
    if (keyword_id == \'\') {
      alert("Please choose an keyword");
      return false;
    }
    url = \'/article/decline_article.php?keyword_id=\' + keyword_id;
    showWindowDialog(url, 600, 500, \'What is your reason for declining this article\');
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