<?php /* Smarty version 2.6.11, created on 2014-05-07 23:18:01
         compiled from client_campaign/assign_keyword.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/assign_keyword.html', 159, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>

<?php echo '
<script language="JavaScript">
<!--
function check_f_assign_keyword()
{
  var f = document.f_assign_keyword;

  if (f.copy_writer_id.value.length == 0) {
    alert(\'Please provides copy wrter\');
    f.copy_writer_id.focus();
    return false;
  }

  if (f.editor_id.value.length == 0) {
    alert(\'Please provides editor\');
    f.editor_id.focus();
    return false;
  }

  if (f.date_start.value.length == 0) {
    alert(\'Please enter  start date of the campaign\');
    return false;
  }
  if (f.date_end.value.length == 0) {
    alert(\'Please enter Due Date of the campaign\');
    return false;
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
//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Assign Keyword To Editor AND Copywriter</h2>
  <div id="campaign-search" >
    <strong>Please enter the keyword required information.choose one editor and one copywriter.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_assign_keyword" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_assign_keyword()"<?php endif; ?>>
  <input type="hidden" name="keyword_id" value="<?php echo $this->_tpl_vars['keyword_info']['keyword_id']; ?>
">
  <input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
">
  <input type="hidden" name="note_id" value="<?php echo $this->_tpl_vars['note_info']['note_id']; ?>
">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="dataLabel">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['campaign_name']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Client Name</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['user_name']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Company Name</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['company_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Keywords</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['keyword']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Mapping-ID</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['mapping_id']; ?>
</td>
  </tr>
  <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr>
    <td class="dataLabel"><?php echo $this->_tpl_vars['item']['label']; ?>
</td>
    <td><?php echo $this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']]; ?>
</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="requiredInput">Copywriter</td>
    <td>
      <select name="copy_writer_id" id="assign_copy_writer_id" ><option value="" >[choose]</option><option value="0" >No Copy Writer</option><?php echo $this->_tpl_vars['copy_writer_options']; ?>
</select>
      <a href="javascript:void(0)" onclick="if ($('assign_copy_writer_id').value) javascript:openWindow('cp_assignment_ranking.php?copywriter_id=' + $('assign_copy_writer_id').value + '&campaign_id=<?php echo $this->_tpl_vars['keyword_info']['campaign_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes'); else alert('Please choose a copywriter');" >View Copywriter's Assignment Ranking</a>
   </td>
  </tr>
<?php if ($this->_tpl_vars['user_role'] == 'admin'): ?>
  <tr>
    <td class="dataLabel">QAer</td>
    <td>
      <select name="qaer_id" id="assign_qaer_id" ><option value="" >[choose]</option><option value="0" >No QAer</option><?php echo $this->_tpl_vars['qaer_options']; ?>
</select>
   </td>
  </tr>
<?php endif; ?>
<tr>
    <td class="requiredInput">Editor</td>
    <td>
      <select name="editor_id" id="assign_editor_id" ><option value="" >[choose]</option><option value="0" >No Editor</option><?php echo $this->_tpl_vars['editor_options']; ?>
</select>
   </td>
  </tr>
  <tr>
	<td class="requiredInput">Notes</td>
	<td><textarea name="notes" cols="60" rows="10"><?php echo $this->_tpl_vars['note_info']['notes']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td><input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['keyword_info']['date_start']; ?>
" readonly/>
        <input type="button" class="button" id="btn_cal_date_start" value="...">
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
        </script></td>
  </tr>
  <tr>
    <td class="requiredInput">Due Date</td>
    <td><input type="text" name="date_end" id="date_end" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['keyword_info']['date_end']; ?>
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
        </script></td>
  </tr>
  <tr>
	<td class="requiredInput">Article Type</td>
	<td>
    <?php if ($this->_tpl_vars['keyword_info']['article_type'] == '' || $this->_tpl_vars['keyword_info']['article_type'] == -1): ?>
      <select name="article_type"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $this->_tpl_vars['keyword_info']['article_type']), $this);?>
</select>
    <?php else: ?>
      <?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['keyword_info']['article_type']]; ?>

      <input name="article_type" type="hidden" value="<?php echo $this->_tpl_vars['keyword_info']['article_type']; ?>
" />
    <?php endif; ?>
  </td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>
    <input type="checkbox" name="is_forced" id="is_forced" value="1" />
    <label for="is_forced" >Force Reassign (Reassign articles to different editor or copywriter forcedly, even if articles have been paid or confirmed by other copywriter.)</label><br />
    <input type="checkbox" name="is_forced_not_free" id="is_forced_not_free" value="1" />
    <label for="is_forced_not_free" >Force Assign (assign even though copywriter/editor is busy between start and end date.)</label><br /> 
		<input type="checkbox" name="is_reserve_content" id="is_reserve_content" value="1" checked checked onclick="confirm_reserve_content(this)" />
		<label for="is_reserve_content" >Keep article content upon reassignment</label>	
	</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
  </form>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>