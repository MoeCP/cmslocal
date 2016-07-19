<?php /* Smarty version 2.6.11, created on 2014-04-09 12:25:44
         compiled from user/candidates.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/candidates.html', 22, false),array('modifier', 'date_format', 'user/candidates.html', 96, false),array('modifier', 'escape', 'user/candidates.html', 120, false),array('modifier', 'nl2br', 'user/candidates.html', 125, false),)), $this); ?>
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
  <h2>Candidate List</h2>
  <div id="campaign-search" >
    <strong>You can enter the "first name","last name" etc. into the keyword input to search the relevant candidate's information</strong>
    <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/user/candidates.php" method="get">
<input type="hidden" name="get_operation" value="search" />
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>Candidate Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword" /></td>
    <td   nowrap>Status</td>
    <td><select name="status" id="status" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['candidate_statuses'],'selected' => $_GET['status']), $this);?>
</select></td>
    <td   nowrap>Education</td>
    <td><select name="education" id="education" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['education'],'output' => $this->_tpl_vars['education'],'selected' => $_GET['education']), $this);?>
</select></td>
    <td   nowrap>Country</td>
    <td><select name="country" id="country" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['country'],'output' => $this->_tpl_vars['country'],'selected' => $_GET['country']), $this);?>
</select></td>
  </tr>
  <tr>
    <td   nowrap>Years Experience</td>
    <td><select name="experience" id="experience" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['experience'],'output' => $this->_tpl_vars['experience'],'selected' => $_GET['experience']), $this);?>
</select></td>
    <td   nowrap>Position</td>
    <td><select name="cpermission" id="cpermission" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['cpermissions'],'selected' => $_GET['cpermission']), $this);?>
</select></td>
    <td   nowrap>Specialies</td>
    <td><select name="categories" id="categories" >
    <?php $_from = $this->_tpl_vars['cp_interests']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($_GET['categories'] == $this->_tpl_vars['k']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']['name']; ?>
</option>
    <?php $_from = $this->_tpl_vars['i']['chidren']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subk'] => $this->_tpl_vars['name']):
?>
    <option value="<?php echo $this->_tpl_vars['subk']; ?>
" <?php if ($_GET['categories'] == $this->_tpl_vars['subk']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
    </select></td>
    <td colspan="20" ><input type="image" src="/images/button-search.gif" value="submit" onclick="$('f_assign_keyword_return').action='/user/candidates.php';"/>&nbsp;<input type="submit" value="Export CSV" class="moduleButton" onclick="$('f_assign_keyword_return').action='/user/export_candidates.php';" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
</table><br>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="/user/candidates.php" name="operate_candidate"  id="operate_candidate" method="post" />
<input type="hidden" name="status"  id="operate_status" value="" />
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td class="table-left-2" rowspan="2">
      <?php if ($this->_tpl_vars['is_show_operate']): ?>
      <input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" />
      <?php endif; ?>
    </td>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">Number</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Name</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2" >Email</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">State</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Date Applied</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Position</td>
    <td nowrap class="columnHeadInactiveBlack" colspan="1">Writing Experience</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Category & Samples</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Your First Language</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Comments</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Status</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2" rowspan="2">Action</td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <tr class="sortableTab">
    <td class="columnHeadInactiveBlack" nowrap>Links</td>
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
      <?php if ($this->_tpl_vars['item']['status'] == 'new' || $this->_tpl_vars['item']['status'] == 'hired'): ?>
      <input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_foreach['loop']['iteration']; ?>
" onclick="javascript:checkItem('Select_All')" />
      <?php endif; ?>
      <input type="hidden" name="candidate_id[]"  id="candidate_id_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
" />
    </td>
    <td><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><a href="javascript:showCandidateDialog(<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
, 'candidate_detail_info.php', 'View Candidate Info');"><?php echo $this->_tpl_vars['item']['first_name']; ?>
 <?php echo $this->_tpl_vars['item']['last_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['email']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['state']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_applied'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo $this->_tpl_vars['cpermissions'][$this->_tpl_vars['item']['cpermission']]; ?>
</td>
    <td>
    <?php $_from = $this->_tpl_vars['item']['plinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
    <?php if ($this->_tpl_vars['row']['value']): ?>
    <?php if ($this->_tpl_vars['candidate_plinks'][$this->_tpl_vars['row']['type']]):  echo $this->_tpl_vars['candidate_plinks'][$this->_tpl_vars['row']['type']]; ?>
: <?php endif;  echo $this->_tpl_vars['row']['value']; ?>
<br /><br />
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </td>
        <td>
    <?php $_from = $this->_tpl_vars['item']['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
    <?php echo $this->_tpl_vars['category']['category']; ?>
:&nbsp;<?php if ($this->_tpl_vars['category']['link']):  echo $this->_tpl_vars['category']['link'];  if ($this->_tpl_vars['category']['fileField'] || $this->_tpl_vars['category']['level']): ?>&nbsp;|&nbsp;<?php endif;  endif;  if ($this->_tpl_vars['category']['fileField']): ?><a href="javascript:void(0)" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
&fd=candidate_categories&t=<?php echo ((is_array($_tmp=$this->_tpl_vars['category']['fileField']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&f=<?php echo ((is_array($_tmp=$this->_tpl_vars['category']['fileField']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['category']['fileField']['name']; ?>
</a><?php if ($this->_tpl_vars['category']['level']): ?>&nbsp;|&nbsp;<?php endif;  endif;  if ($this->_tpl_vars['category']['level']):  echo $this->_tpl_vars['user_levels'][$this->_tpl_vars['category']['level']];  endif; ?><br /><br />
    <?php endforeach; endif; unset($_from); ?>
    <?php if ($this->_tpl_vars['item']['is_categories_doc']): ?><a href="javascript:void(0)" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
&fd=candidate_samples', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');">Download All</a><?php endif; ?>
    </td>
    <td><?php echo $this->_tpl_vars['item']['first_language']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['comments'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['status']; ?>
</td>
    <td align="right" nowrap class="table-right-2">
     <input type="button" class="button" value="Review" onclick="javascript:showCandidateDialog(<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
, 'candidate_detail_info.php', 'View Candidate Info');" />
     <?php if ($this->_tpl_vars['item']['status'] == 'new'): ?>
     <input type="button" class="button" value="Hire" onclick="javascript:submitOperateParam('hired', <?php echo $this->_foreach['loop']['iteration']; ?>
);" />
     <input type="button" class="button" value="Reject" onclick="javascript:submitOperateParam('rejected', <?php echo $this->_foreach['loop']['iteration']; ?>
);" />
     <?php endif; ?>
     <?php if ($this->_tpl_vars['item']['status'] == 'hired'): ?>
     <input type="button" class="button" value="Resend Confirmation" onclick="javascript:submitOperateParam('resend', <?php echo $this->_foreach['loop']['iteration']; ?>
);" />
     <?php endif; ?>
     <input type="button" class="button" value="Update" onclick="javascript:candidateOpenWindow(<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
, 'candidate_edit.php', 'Edit Candidate Info',800);" />
     <?php if ($this->_tpl_vars['item']['is_samples_doc'] || $this->_tpl_vars['item']['is_categories_doc']): ?>
     <input type="button" class="button" value="Download" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
&fd=all', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />     
     <?php endif; ?>
     <?php if ($this->_tpl_vars['item']['resume_file']): ?>
     <input type="button" class="button" value="Resume Download" onclick="javascript:openWindow('/user/resume_download.php?cid=<?php echo $this->_tpl_vars['item']['candidate_id']; ?>
&f=<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['resume_file'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />
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
<?php if ($this->_tpl_vars['is_show_operate']): ?>
<table align="center">
  <tr>
    <td>
        <input type="button" class="button" value="Hire" onclick="javascript:submitAllOperateParam('hired');" />
        <input type="button" class="button" value="Reject" onclick="javascript:submitAllOperateParam('rejected');" />
    </td>
  </tr>
</table>
<?php endif; ?>
</div>
<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>"CaseInsensitiveString",<?php endif; ?> "CaseInsensitiveString", "None"]);

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
function submitAllOperateParam(status)
{
    var l = document.getElementsByName("candidate_id[]").length;
    var is_checked = false;
    $(\'operate_status\').value = status;
    for (var i=1 ;i <= l ;i++)
    {
        if (isObjectOrNot($(\'isUpdate_\' + i)))
        {
          if ($(\'isUpdate_\' + i).checked)
          {
              if (!is_checked) is_checked = !is_checked;
          }
        }
    }
    if (!is_checked)
    {
        alert("Please specify canidate(s)");
        return false;
    }
    else
    {
        $("operate_candidate").submit();
    }
}
function submitOperateParam(status, pos, cid)
{
    if (status.length <= 0)
    {
        alert("Please specify the operate type");
        return false;
    }
    else
    {
      if (cid > 0)
      {
        $(\'candidate_id_\' + pos).value = cid;
      }
      $(\'operate_status\').value = status;
      $(\'isUpdate_\' + pos).checked = true;
      $("operate_candidate").submit();
    }
}

function showCandidateDialog(candidate_id, page, title) {
  var url = \'/user/\' + page +\'?candidate_id=\' + candidate_id;
  var weight = arguments[3]|600;
  var height = arguments[4]|500;
  showWindowDialog(url, weight, height, title);
};
function candidateOpenWindow(candidate_id, page, title) {
  var url = \'/user/\' + page +\'?candidate_id=\' + candidate_id;
  var weight = arguments[3]|600;
  var height = arguments[4]|500;
  openWindow(url, \'height=\'+height+\',width=\'+ weight + \',status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes,dependent=yes\');
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>