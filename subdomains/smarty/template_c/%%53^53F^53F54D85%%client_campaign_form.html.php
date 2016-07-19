<?php /* Smarty version 2.6.11, created on 2014-09-04 14:34:27
         compiled from client_campaign/client_campaign_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/client_campaign_form.html', 133, false),array('modifier', 'default', 'client_campaign/client_campaign_form.html', 133, false),)), $this); ?>
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
//  tinyMCE.triggerSave(false,false);
  if (f.client_id.value.length == 0) {
    alert(\'Please choose a client\');
    f.client_id.focus();
    return false;
  }

  if (f.campaign_name.value.length == 0) {
    alert(\'Please enter campaign\\\'s name\');
    f.campaign_name.focus();
    return false;
  }
//  if (f.total_budget.value.length == 0) {
//    alert(\'Please enter campaign total budget\');
//    f.total_budget.focus();
//    return false;
//  }
  if (f.category_id.value == 0) {
    alert(\'Please specify category\');
    f.category_id.focus();
    return false;
  }

/*  if (f.total_budget.value.length !=0 && !isNumeric(f.total_budget.value)) {
    alert(\'Total budget must be a integer\');
    f.total_budget.focus();
    return false;
  }*/
  /*if (f.cost_per_article.value.length != 0 && !isNumeric(f.cost_per_article.value)) {
    alert(\'Copywriter cost per word must be a integer\');
    f.total_budget.focus();
    return false;
  }
  if (f.editor_cost.value.length != 0 && !isNumeric(f.editor_cost.value)) {
    alert(\'Editor cost per word must be a integer\');
    f.total_budget.focus();
    return false;
  }*/
  if (f.date_start.value.length == 0) {
    alert(\'Please enter start date of the campaign\');
    return false;
  }
  if (f.date_end.value.length == 0) {
    alert(\'Please enter Due Date of the campaign\');
    return false;
  }
  

  if (f.ordered_by.value.length == 0) {
    alert(\'Please specify Ordered By\');
    f.ordered_by.focus();
    return false;
  }

  if (f.max_word.value.length == 0) {
    alert(\'Please specify No. of Words\');
    f.max_word.focus();
    return false;
//  } else if (!isNumeric(f.max_word.value) || (f.max_word.value < 30 || f.max_word.value > 2000) && f.max_word.value != 0) {
  } else if (!isNumeric(f.max_word.value) || (f.max_word.value < 0) && f.max_word.value != 0) {
      alert(\'Please input number more than 0. If it\\\'s no limit, please input 0\');
      f.max_word.focus();
      return false;
  }
  return true;
}
'; ?>

var source = <?php if ($this->_tpl_vars['client_campaign_info']['source'] > 0):  echo $this->_tpl_vars['client_campaign_info']['source'];  else: ?>0<?php endif; ?>;
<?php echo '
//changeclient(client_id);
function changeclient(client_id)
{
    ajaxAction(\'/client_campaign/getdomains.php?cid=\'+client_id+\'&s=\'+source, \'domaindiv\');
}
function changeCampaignType(ctype)
{
     ajaxAction(\'/client_campaign/gettypes.php?tid=\'+ctype, \'campaigntypediv\');
}
function addDomain(client_id)
{
  window.open("/client/key_quick_add.php?client_id="+client_id,"add_domain","width=600,height=450,resizable=1,scrollbars=1");
}
'; ?>

<?php if ($this->_tpl_vars['user_role'] != 'client'): ?>
//tinyMCEInit('campaign_requirement,sample_content,keyword_instructions,special_instructions,acceptance_desc');
<?php endif;  echo '
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Client's Campaign Information Setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the client's campaign required information.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_client_campaign" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_client_campaign()"<?php endif; ?>>
  <input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_id']; ?>
">
  <input type="hidden" name="order_campaign_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['order_campaign_id']; ?>
">
  
  <?php if ($this->_tpl_vars['client_campaign_info']['order_campaign_id'] > 0): ?>
  <input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['parent_campaign_id']; ?>
">
  <input type="hidden" name="operation" value="<?php if ($this->_tpl_vars['client_campaign_info']['parent_id'] > 0): ?>copy<?php endif; ?>">
  <?php endif; ?>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['client_campaign_info']['campaign_id'] == '' && $this->_tpl_vars['user_role'] != 'client'): ?>
  <tr>
    <td class="requiredInput">Client</td>
    <td><select name="client_id" onchange="changeclient(this.value)"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_client'],'selected' => $this->_tpl_vars['client_campaign_info']['client_id']), $this);?>
</select>&nbsp;<img src="/image/select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("/client/client_quick_add.php","add_client","width=600,height=450,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'><input name="campaign_type" type="hidden" id="campaign_type" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['client_campaign_info']['article_type'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
" /></td>
  </tr>
    <?php else: ?>
  <tr>
    <td class="requiredInput">Client</td>
    <td>
      <?php echo $this->_tpl_vars['client_name']; ?>

      <input name="client_id" type="hidden" id="client_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['client_id']; ?>
" />
      <input name="campaign_type" type="hidden" id="campaign_type" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_type']; ?>
" />
      <input name="article_type" type="hidden" id="article_type" value="<?php echo $this->_tpl_vars['client_campaign_info']['article_type']; ?>
" />
      <?php if ($this->_tpl_vars['user_role'] == 'client'): ?>
      <input name="ordered_by" type="hidden" id="ordered_by" value="<?php echo $this->_tpl_vars['client_campaign_info']['ordered_by']; ?>
" />      
      <input type="hidden" name="title_param" id="title_param_custom" value="1" />
      <input type="hidden" name="meta_param" id="meta_param_default" value="0" />
      <?php endif; ?>
    </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><input name="campaign_name" type="text" id="campaign_name" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr style="display: none;">
    <td class="requiredInput">Category</td>
    <td>    <select name="category_id">
	<option value="4">Business</option>
    <!--<?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
    <?php endforeach; endif; unset($_from); ?>-->
    </select>
    </td>
  </tr>
  <?php if ($this->_tpl_vars['user_role'] != 'client'): ?>
  <tr>
    <td class="requiredInput"><span id="spanArticleType" >Default Article Type</span></td>
    <td>
    <select name="article_type" id="article_type" >
      <option value="-1" >[default]</option>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $this->_tpl_vars['client_campaign_info']['article_type']), $this);?>

    </select>
    <div id="campaigntypediv" ></div>
    </td>
  </tr>
<?php if ($this->_tpl_vars['client_campaign_info']['campaign_id'] == '' || $this->_tpl_vars['client_campaign_info']['template'] != ''): ?>
  <tr>
    <td class="dataLabel">Template</td>
    <td>
    <select name="template" id="template" >
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['templates'],'selected' => $this->_tpl_vars['client_campaign_info']['template']), $this);?>

    </select>
    </td>
  </tr>
<?php endif; ?>
  <?php endif; ?>
    <?php if ($this->_tpl_vars['client_campaign_info']['source'] > 0): ?>
  <tr>
    <td class="dataLabel">Domain</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['domains'][$this->_tpl_vars['client_campaign_info']['source']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<input type="hidden" name="source" id="source" value="<?php echo $this->_tpl_vars['client_campaign_info']['source']; ?>
" /></td>
  </tr>
  <?php else: ?>
  <tr>
    <td class="dataLabel">Domain</td>
    <td><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['domains'],'name' => 'source','id' => 'source','selected' => $this->_tpl_vars['client_campaign_info']['source']), $this);?>
&nbsp;<img src="/image/select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("/client/key_quick_add.php?client_id="+ $("client_id").value ,"add_domain","width=600,height=450,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'><div id="domaindiv" ></div></td>
  </tr>
  <?php endif; ?>
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
    <td class="requiredInput">No. of Words</td>
    <td><?php if (( $this->_tpl_vars['user_role'] == 'admin' || $this->_tpl_vars['user_role'] == 'project manager' || $this->_tpl_vars['user_role'] == 'client' ) && $this->_tpl_vars['client_campaign_info']['campaign_id'] == ''): ?> 
          <input id="max_word" name="max_word"  value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['client_campaign_info']['max_word'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" /><?php else:  if ($this->_tpl_vars['client_campaign_info']['max_word']):  echo $this->_tpl_vars['client_campaign_info']['max_word'];  else: ?>No Limit<?php endif; ?><input name="max_word" type="hidden" id="max_word" value="<?php echo $this->_tpl_vars['client_campaign_info']['max_word']; ?>
"   /><?php endif; ?></td>
  </tr>
  <?php if ($this->_tpl_vars['user_role'] == 'client'): ?>
  <tr>
    <td class="requiredInput">Total # of Articles</td>
    <td><?php if (( $this->_tpl_vars['user_role'] == 'admin' || $this->_tpl_vars['user_role'] == 'project manager' || $this->_tpl_vars['user_role'] == 'client' ) && $this->_tpl_vars['client_campaign_info']['campaign_id'] == ''): ?> 
    <input id="total_keyword" name="total_keyword"  value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['client_campaign_info']['total_keyword'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" /><?php else:  if ($this->_tpl_vars['client_campaign_info']['total_keyword']):  echo $this->_tpl_vars['client_campaign_info']['total_keyword'];  else: ?>No Limit<?php endif; ?><input name="total_keyword" type="hidden" id="total_keyword" value="<?php echo $this->_tpl_vars['client_campaign_info']['total_keyword']; ?>
"   /><?php endif; ?></td>
  </tr>
  <?php else: ?>
  <tr>
    <td class="dataLabel">Style Guide URL</td>
    <td><input name="style_guide_url" type="text" id="style_guide_url" value="<?php echo $this->_tpl_vars['client_campaign_info']['style_guide_url']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="60"></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ADDITIONAL_STYLE_GUIDE','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=no')" class="classtips">Assignment Details</a></td>
    <td><textarea name="campaign_requirement" class="ckeditor" cols="50" rows="15" id="campaign_requirement"><?php echo $this->_tpl_vars['client_campaign_info']['campaign_requirement']; ?>
</textarea></td>
  </tr>
  <!--<tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=EDITOR_GRADING_RUBRIC','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=no')" class="classtips">Editor Grading Rubric</a></td>
    <td><textarea name="editor_grading_rubric" class="ckeditor" cols="50" rows="15" id="editor_grading_rubric"><?php echo $this->_tpl_vars['client_campaign_info']['editor_grading_rubric']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=SAMPLE_CONTENT','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Sample Content</a></td>
    <td><textarea name="sample_content" class="ckeditor" cols="50" rows="15" id="sample_content"><?php echo $this->_tpl_vars['client_campaign_info']['sample_content']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=CONTENT_INSTRUCTIONS','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Content Instructions</a></td>
    <td><textarea name="keyword_instructions" class="ckeditor" cols="50" rows="15" id="keyword_instructions"><?php echo $this->_tpl_vars['client_campaign_info']['keyword_instructions']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=SPECIAL_INSTRUCTIONS','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips" >Special Instructions</a></td>
    <td><textarea name="special_instructions" class="ckeditor" cols="50" rows="15" id="special_instructions"><?php echo $this->_tpl_vars['client_campaign_info']['special_instructions']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel">Assignment Details</td>
    <td><textarea name="acceptance_desc" class="ckeditor" cols="50" rows="15" id="acceptance_desc"><?php echo $this->_tpl_vars['client_campaign_info']['acceptance_desc']; ?>
</textarea></td>
  </tr>-->
  <tr>
    <td class="requiredInput">Ordered By</td>
    <td><input name="ordered_by" type="text" id="ordered_by" value="<?php echo $this->_tpl_vars['client_campaign_info']['ordered_by']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="60"></td>
  </tr>
  
  <?php if ($this->_tpl_vars['order_campaign_id'] > 0 && ( $this->_tpl_vars['client_campaign_info']['campaign_id'] <= 0 || $this->_tpl_vars['client_campaign_info']['campaign_id'] == '' ) && $this->_tpl_vars['client_campaign_info']['monthly_recurrent'] == 1): ?>
  <input type="hidden" name="monthly_recurrent" id="monthly_recurrent" value="<?php echo $this->_tpl_vars['client_campaign_info']['monthly_recurrent']; ?>
" />
  <input type="hidden" name="recurrent_time" id="recurrent_time" value="<?php echo $this->_tpl_vars['client_campaign_info']['recurrent_time']; ?>
" />
  <?php else: ?>
    <?php if ($this->_tpl_vars['client_campaign_info']['monthly_recurrent'] != 2): ?>
    <tr>
      <td class="dataLabel">Monthly Recurrent</td>
      <td><input type="checkbox" name="monthly_recurrent" id="monthly_recurrent" value="1" <?php if ($this->_tpl_vars['client_campaign_info']['monthly_recurrent'] == 1): ?> checked <?php endif; ?> /></td>
    </tr>
    <?php endif; ?>
  <?php endif; ?>
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
  <?php if (( $this->_tpl_vars['client_campaign_info']['campaign_id'] <= 0 || $this->_tpl_vars['client_campaign_info']['campaign_id'] == '' )): ?>
  <tr>
    <td class="requiredInput">Display author bio on Article page</td>
    <td>
      <input type="radio" name="show_cp_bio" id="title_show_cp_bio" value="0" <?php if ($this->_tpl_vars['client_campaign_info']['show_cp_bio'] == 0): ?>checked<?php endif; ?>/><label for="title_show_cp_bio" >No</label>
      <input type="radio" name="show_cp_bio" id="title_hide_cp_bio" value="1" <?php if ($this->_tpl_vars['client_campaign_info']['show_cp_bio'] == 1): ?>checked<?php endif; ?>/><label for="title_hide_cp_bio" >Yes</label>
      
    </td>
  </tr>
  <?php endif; ?>
  <?php endif; ?>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
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