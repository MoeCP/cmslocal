<?php /* Smarty version 2.6.11, created on 2013-04-26 15:43:42
         compiled from client_campaign/image_keyword_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/image_keyword_form.html', 177, false),)), $this); ?>
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
function check_f_keyword()
{
  var f = document.f_keyword;
  tinyMCE.triggerSave(false,false);
  if (f.keyword.value.length == 0) {
    alert(\'Please enter campaign keywords\');
    return false;
  }
/*  if (f.mapping_id.value.length > 0)
  {
      mappings = f.mapping_id.value.split("\\n");
      keywords = f.keyword.value.split("\\n");
      is_not_match = false;
      var klen = keywords.length;
      var mlen = mappings.length;
      if (klen != mlen) {
          is_not_match = true;
      } else {
        for (var i=0; i < klen; i++) {
            if (keywords[i].length > 0 && mappings[i].length == 0) {
                is_not_match = true;
            }
        }
      }
      if (is_not_match) {
          alert("Each keyword must have one mapping ID");
          return false;        
      }
  }*/
  /*
  if (f.date_start.value.length == 0) {
    alert(\'Please enter start date of the campaign\');
    return false;
  }
  if (f.date_end.value.length == 0) {
    alert(\'Please enter Due Date of the campaign\');
    return false;
  }
  */

  if (f.copy_writer_id.value.length != 0 || f.editor_id.value.length != 0) {
	  /*if (f.copy_writer_id.value.length == 0) {
		alert(\'Please choose a copywriter for these keywords\');
		f.copy_writer_id.focus();
		return false;
	  }*/
	  if (f.editor_id.value.length == 0) {
		alert(\'Please choose an editor for these keywords\');
		f.editor_id.focus();
		return false;
	  }
  }

  return true;
}
tinyMCEInit(\'keyword_description\');
//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Campaign Keyword Settings</h2>
  <div id="campaign-search" >
    <strong>Please enter the Client's campaign keyword information according to requirement.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_keyword" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_keyword()"<?php endif; ?>>
  <input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['campaign_info']['campaign_id']; ?>
" />
  <?php if ($this->_tpl_vars['parent_id'] > 0): ?><input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['parent_id']; ?>
" /><?php endif; ?>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint" colspan="6">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="10"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="dataLabel">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['campaign_info']['campaign_name']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Client Name</td>
    <td><?php echo $this->_tpl_vars['campaign_info']['user_name']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Company Name</td>
    <td><?php echo $this->_tpl_vars['campaign_info']['company_name']; ?>
</td>
  </tr>
    <tr>
    <td class="requiredInput">Add Campaign Keywords</td>
    <td>
      <textarea name="keyword" cols="35" rows="15" id="keyword" onblur="cmsCalcLine(this.value,'keyword')"><?php echo $this->_tpl_vars['keyword_info']['keyword']; ?>
</textarea>
      <div id="keyword_lines"></div>
    </td>
    <td class="dataLabel">Mapping-ID</td>
    <td>
      <textarea name="mapping_id" cols="35" rows="15" id="mapping_id" onblur="cmsCalcLine(this.value,'mapping_id')"><?php echo $this->_tpl_vars['keyword_info']['mapping_id']; ?>
</textarea>
      <div id="mapping_id_lines"></div>
    </td>
  </tr>
  <?php $_from = $this->_tpl_vars['optional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <?php if ($this->_foreach['loop']['iteration']%2 == 1): ?>
  <tr>
  <?php endif; ?>
  <td class="dataLabel"><?php echo $this->_tpl_vars['item']['label']; ?>
</td>
  <td>
    <textarea name="<?php echo $this->_tpl_vars['key']; ?>
" cols="35" rows="15" id="<?php echo $this->_tpl_vars['key']; ?>
" onblur="cmsCalcLine(this.value,'<?php echo $this->_tpl_vars['key']; ?>
')"><?php echo $this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']]; ?>
</textarea>
    <div id="<?php echo $this->_tpl_vars['key']; ?>
_lines"></div>
  </td>
  <?php if ($this->_foreach['loop']['iteration']%2 == 0 || $this->_foreach['loop']['iteration'] == $this->_tpl_vars['total_optional']): ?>
   </tr>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td><input type="text" name="date_start" id="date_start" size="20" maxlength="10" value="<?php echo $this->_tpl_vars['keyword_info']['date_start']; ?>
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
    <td><input type="text" name="date_end" id="date_end" size="20" maxlength="10" value="<?php echo $this->_tpl_vars['keyword_info']['date_end']; ?>
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
    <td class="dataLabel">SCID</td>
    <td colspan="3" >
      <textarea name="subcid" cols="35" rows="15" id="subcid" onblur="cmsCalcLine(this.value,'subcid')"><?php echo $this->_tpl_vars['keyword_info']['subcid']; ?>
</textarea>
      <div id="subcid_lines"></div>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Keyword Instructions</td>
    <td colspan="3" ><textarea name="keyword_description" cols="70" rows="15" id="keyword_description"><?php echo $this->_tpl_vars['keyword_info']['keyword_description']; ?>
</textarea></td>
  </tr>
  <?php if ($this->_tpl_vars['keyword_info']['image_type'] == '' || $this->_tpl_vars['keyword_info']['image_type'] == -1): ?>
  <tr>
  <td class="requiredInput">Article Type</td>
  <td><select name="image_type"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['image_type'],'selected' => $this->_tpl_vars['keyword_info']['image_type']), $this);?>
</select></td>
  </tr>
  <?php else: ?>
  <input type="hidden" name="image_type" value="<?php echo $this->_tpl_vars['keyword_info']['image_type']; ?>
" />
  <?php endif; ?>
  <tr>
    <td class="dataLabel">Designer</td>
    <td><select name="copy_writer_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $this->_tpl_vars['keyword_info']['copy_writer_id']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="requiredInput">Editor</td>
    <td><select name="editor_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $this->_tpl_vars['keyword_info']['editor_id']), $this);?>
</select></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="checkbox" value="0" id="keyword_status" name="keyword_status" /><label for="keyword_status">Require Client Approval</lable></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
  </form>
</table>
  </div>
</div>
<script type="text/javascript">
var calc_fields = 'keyword,mapping_id';
<?php $_from = $this->_tpl_vars['optional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  calc_fields+=',' +'<?php echo $this->_tpl_vars['key']; ?>
';
<?php endforeach; endif; unset($_from); ?>
cmsCalcLineByFields(calc_fields);
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>