<?php /* Smarty version 2.6.11, created on 2013-06-09 01:10:15
         compiled from graphics/batch_assign_keyword.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'graphics/batch_assign_keyword.html', 69, false),array('modifier', 'nl2br', 'graphics/batch_assign_keyword.html', 133, false),array('modifier', 'strip', 'graphics/batch_assign_keyword.html', 133, false),array('modifier', 'escape', 'graphics/batch_assign_keyword.html', 133, false),array('modifier', 'truncate', 'graphics/batch_assign_keyword.html', 135, false),)), $this); ?>
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

<?php echo '
<script language="JavaScript">
<!--
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
          alert(\'Please choose a designer or a editor for keyword\');
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
      if (!confirm(\'Are you sure you don\\\'t want to keep image upon reassignment\'))
      {
        obj.checked = true;
      }
  }
}

'; ?>

//-->
</script>
<div id="page-box1">
  <h2>Assign images to editors and designer</h2>
  <div id="campaign-search" >
    <strong>You can enter the "campaign name","keyword","company name","client name" etc. into the keyword input to search the relevant keyword's information</strong>
    <div id="campaign-search-box" >
<form name="f_assign_keyword_return" action="" method="get">
  <table border="0" cellspacing="1" cellpadding="4">
  <input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
  <tr>
    <td>Designer</td>
    <td><select name="copy_writer_id"><option value="">[choose]</option><option value="0">No Designerr</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['copy_writer_id']), $this);?>
</select></td>
    <td>Editor</td>
    <td><select name="editor_id"><option value="">[choose]</option><option value="0">No Editor</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
    <td nowrap>Status</td>
    <td><select name="image_status"><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_status'],'selected' => $_GET['image_status']), $this);?>
</select>
    &nbsp;&nbsp;&nbsp;
    </td>
    <td rowspan="2" ><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td nowrap>Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword"></td>
    <td nowrap>Image Type</td>
    <td><select name="image_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_type'],'selected' => $_GET['image_type']), $this);?>
</select></td>
    <td>Campaigns</td>
    <td><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
    <td><font>Show:</font></td><td><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<br />
<form action="" name="f_assign_keyword" method="post" <?php if ($this->_tpl_vars['js_check'] == true): ?>onSubmit="return check_f_assign_keyword('<?php echo $this->_tpl_vars['result_count']; ?>
')"<?php endif; ?>>
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
    <tr class="sortableTab">
      <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <td class="table-left-2" ><input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" /></td>
      <td nowrap class="columnHeadInactiveBlack">Number</td>
      <td nowrap class="columnHeadInactiveBlack">Assigned</td>
      <td nowrap class="columnHeadInactiveBlack">Keyword</td>
      <td nowrap class="columnHeadInactiveBlack">Status</td>
      <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
      <td nowrap class="columnHeadInactiveBlack">Client Name</td>
      <td nowrap class="columnHeadInactiveBlack">Company Name</td>
      <td nowrap class="columnHeadInactiveBlack">Designer</td>
      <td nowrap class="columnHeadInactiveBlack">Editor</td>
      <td nowrap class="columnHeadInactiveBlack">Image Type</td>
      <td nowrap class="columnHeadInactiveBlack">Start Date</td>
      <td nowrap class="columnHeadInactiveBlack table-right-2">Due Date</td>
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
      <input type="hidden" name="keyword_id[]" id="keyword_id_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
" />
      <input type="hidden" name="note_id[]" id="note_id_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['note_id']; ?>
" />
      <input type="hidden" name="old_notes[]" id="notes_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['notes']; ?>
" />
      <!--下面的keyword隐藏域是用于js的 //-->
      <input type="hidden" name="keyword[]" id="keyword_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword']; ?>
" />
      <input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop_all']['iteration']; ?>
" value="<?php echo $this->_foreach['loop_all']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', f_common)" />
      </td>
      <td><?php echo $this->_foreach['loop_all']['iteration']; ?>
</td>
      <td><?php if ($this->_tpl_vars['item']['copy_writer_id'] != 0 && $this->_tpl_vars['item']['editor_id'] != 0): ?><font color="red">&radic;</font><?php else: ?>&times;<?php endif; ?></td>
      <td><a href="#" target="_self" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
      <td><?php echo $this->_tpl_vars['image_status'][$this->_tpl_vars['item']['image_status']]; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['company_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['uc_name']; ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</td>
      <td><?php echo $this->_tpl_vars['image_type'][$this->_tpl_vars['item']['image_type']]; ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['date_start']; ?>
</td>
      <td class="table-right-2"><?php echo $this->_tpl_vars['item']['date_end']; ?>
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
<script language="JavaScript">
<!--
var post_checkbox_array = '<?php echo $this->_tpl_vars['post_checkbox_array']; ?>
';
checkPostItem('Select_All', post_checkbox_array, 'isUpdate[]', f_common);
//-->
</script>

<table border="0" cellspacing="1" cellpadding="4" width="100%">
  <tr>
    <td ><table class="helpTable" cellspacing="0" cellpadding="4">
      <tr><td valign="top">&nbsp;&#8226;&nbsp;</td><td>Please choose some keywords that you need update,enter the relevant information you need,then submit it.</td></tr></table></td>
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
      <input type="checkbox" name="is_forced" id="is_forced" value="1" />
      <label for="is_forced" >Force Assign(If images have been confirmed by designer or paid, reassign those images to other editor or designer forcedly)</label>
      <br />
      <input type="checkbox" name="is_forced_not_free" id="is_forced_not_free" value="1" />
      <label for="is_forced_not_free" >Force Assign when the designer/editor is busy between start date and Due Date</label><br />
			<input type="checkbox" name="is_reserve_content" id="is_reserve_content" value="1" checked onclick="confirm_reserve_content(this)"/>
      <label for="is_reserve_content" >Keep image upon reassignment</label>
      <br />
    </td>
  </tr>
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="4">
        <tr>
          <td class="dateLable">Designer</td>
          <td>
          <select name="copy_writer_id" id="assign_copy_writer_id" >
            <option value="">[choose]</option>
            <option value="0">No Designer</option>
            <?php echo $this->_tpl_vars['copy_writer_options']; ?>

          </select>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="4">
        <tr>
          <?php if ($this->_tpl_vars['campaign_info']['image_type'] == -1 || $this->_tpl_vars['campaign_info']['image_type'] == ''): ?>
          <td class="dateLable">Image Type</td>
          <td>
            <select name="image_type">
              <option value="">[default]</option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['leaf_types'],'selected' => $_POST['image_type']), $this);?>

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
            <input type="submit" class="button" value="Assign" />&nbsp;&nbsp;
            <input type="reset" class="button" value="Reset" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "None",  "Number", "None", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None", "None", "None"]);

// restore the class names
st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(2);
//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>