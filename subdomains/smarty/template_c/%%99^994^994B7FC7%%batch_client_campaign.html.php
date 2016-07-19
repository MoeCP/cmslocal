<?php /* Smarty version 2.6.11, created on 2014-06-11 09:32:47
         compiled from client_campaign/batch_client_campaign.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/batch_client_campaign.html', 86, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

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
function check_f_client_campaign()
{
  var f = document.f_client_campaign;
  if (f.filename.value.length == 0) {
    alert(\'Please choose a file\');
    f.filename.focus();
    return false;
  } else {
    if (getFileType(f.filename.value) != \'csv\')
    {
        alert(\'Invalid File Type, the allowed file type is \\\'csv\\\'\');
        f.filename.focus();
        return false;
    }
  }

  if (f.client_id.value.length == 0) {
    alert(\'Please choose a client\');
    f.client_id.focus();
    return false;
  }

  if (f.category_id.value == 0) {
    alert(\'Please specify category\');
    f.category_id.focus();
    return false;
  }

  if (f.date_start.value.length == 0) {
    alert(\'Please enter start date of the campaign\');
    return false;
  }
  if (f.date_end.value.length == 0) {
    alert(\'Please enter Due Date of the campaign\');
    return false;
  }
  

  if (f.ordered_by.value == 0) {
    alert(\'Please specify Ordered By\');
    f.ordered_by.focus();
    return false;
  }
  return true;
}
</script>
'; ?>


<div id="page-box1">
  <h2>Create Batch Client Campaign by Uploading File</h2>
  <div id="campaign-search" >
    <strong>Please enter the client's campaign required information.</strong>
  </div>
  <div class="form-item" >
<br>
<form action="" method="post"  name="f_client_campaign" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_client_campaign()"<?php endif; ?> enctype='multipart/form-data'>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign File</td>
    <td><input type="file" name="filename" id="filename" value="" size="60" /></td>
  </tr>
  <?php if ($this->_tpl_vars['client_campaign_info']['campaign_file_id'] == ''): ?>
  <tr>
    <td class="requiredInput">Client</td>
    <td><select name="client_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_client'],'selected' => $this->_tpl_vars['client_campaign_info']['client_id']), $this);?>
</select>&nbsp;<img src="/image/select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("/client/client_quick_add.php","add_client","width=600,height=450,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
  </tr>
  <?php else: ?>
  <tr>
    <td class="requiredInput">Client</td>
    <td><?php echo $this->_tpl_vars['client_name']; ?>
<input name="client_id" type="hidden" id="client_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['client_id']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Category</td>
    <td>
    <select name="category_id">
    <?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['client_campaign_info']['category_id'] == $this->_tpl_vars['k']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']['name']; ?>
</option>
    <?php $_from = $this->_tpl_vars['i']['chidren']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subk'] => $this->_tpl_vars['name']):
?>
    <option value="<?php echo $this->_tpl_vars['subk']; ?>
" <?php if ($this->_tpl_vars['client_campaign_info']['category_id'] == $this->_tpl_vars['subk']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Default Article Type</td>
    <td>
    <select name="article_type">
      <option value="-1" >[default]</option>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $this->_tpl_vars['client_campaign_info']['article_type']), $this);?>

    </select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput"> Start Date</td>
    <td><input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['client_campaign_info']['date_start']; ?>
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
    <td><input type="text" name="date_end" id="date_end" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['client_campaign_info']['date_end']; ?>
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
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ADDITIONAL_STYLE_GUIDE','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=no')" class="classtips">Additional Style Guide</a></td>
    <td><textarea name="campaign_requirement" cols="50" rows="4" id="campaign_requirement"  class="ckeditor"><?php echo $this->_tpl_vars['client_campaign_info']['campaign_requirement']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=SAMPLE_CONTENT','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Sample Content</a></td>
    <td><textarea name="sample_content" cols="50" rows="4" id="sample_content"  class="ckeditor"><?php echo $this->_tpl_vars['client_campaign_info']['sample_content']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=CONTENT_INSTRUCTIONS','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Content Instructions</a></td>
    <td><textarea name="keyword_instructions" cols="50" rows="4" id="keyword_instructions"  class="ckeditor"><?php echo $this->_tpl_vars['client_campaign_info']['keyword_instructions']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=SPECIAL_INSTRUCTIONS','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips" >Special Instructions</a></td>
    <td><textarea name="special_instructions" cols="50" rows="4" id="special_instructions"  class="ckeditor"><?php echo $this->_tpl_vars['client_campaign_info']['special_instructions']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Ordered By</td>
    <td><input name="ordered_by" type="text" id="ordered_by" value="<?php echo $this->_tpl_vars['client_campaign_info']['ordered_by']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="60"></td>
  </tr>
  <tr>
    <td class="requiredInput">Meta Info Setting</td>
    <td>
      <input type="radio" name="meta_param" id="meta_param_default" value="0" <?php if ($this->_tpl_vars['client_campaign_info']['meta_param'] == 0): ?>checked<?php endif; ?>/><label for="meta_param_default" >Default Meta Information</label>
      <input type="radio" name="meta_param" id="meta_param_custom" value="1" <?php if ($this->_tpl_vars['client_campaign_info']['meta_param'] == 1): ?>checked<?php endif; ?>/><label for="meta_param_custom" >Custom Meta Information</label>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Article & HTML Title Setting</td>
    <td>
      <input type="radio" name="title_param" id="title_param_default" value="0" <?php if ($this->_tpl_vars['client_campaign_info']['title_param'] == 0): ?>checked<?php endif; ?>/><label for="title_param_default" >Default Article & HTML Title</label>
      <input type="radio" name="title_param" id="title_param_custom" value="1" <?php if ($this->_tpl_vars['client_campaign_info']['title_param'] == 1): ?>checked<?php endif; ?>/><label for="title_param_custom" >Custom Article & HTML Title</label>
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