<?php /* Smarty version 2.6.11, created on 2011-11-30 12:43:58
         compiled from client_campaign/order_campaign_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/order_campaign_form.html', 206, false),array('function', 'html_options', 'client_campaign/order_campaign_form.html', 220, false),)), $this); ?>
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
function check_f_client_campaign()
{
  var f = document.f_client_campaign;
  tinyMCE.triggerSave(false,false);
  if (f.campaign_name.value.length == 0) {
    alert(\'Please enter campaign\\\'s name\');
    f.campaign_name.focus();
    return false;
  }

  if (f.category_id.value == 0) {
    alert(\'Please specify category\');
    f.category_id.focus();
    return false;
  }

  if (f.qty.value < 0 || f.qty.value == \'\')
  {
      alert(\'Please specify the content qty\');
      f.qty.focus();
      return false;
  } else if (!isNumeric(f.qty.value)) {
    alert(\'Content qty must be a integer\');
      f.qty.focus();
      return false;
  }
  '; ?>

  <?php if ($this->_tpl_vars['user_permission_int'] <> 5): ?>
  <?php echo '
  if (parseInt(f.total.value) < 50)
  {
    alert(\'Total is less than $50, please check your input\');
    f.total.focus();
    return false;

  }
  '; ?>

  <?php endif; ?>
  <?php echo '
  if (f.date_start.value.length == 0) {
    alert(\'Please enter start date of the campaign\');
    f.date_start.focus();
    return false;
  }
  if (f.date_end.value.length == 0) {
    alert(\'Please enter Due Date of the campaign\');
    f.date_end.focus();
    return false;
  }
  var int_date_start = Date.parse(f.date_start.value);
  var int_date_end = Date.parse(f.date_end.value);
  if ((int_date_end - int_date_start) < 0)
  {
      alert(\'Incorrect date,Please try again\');
      f.date_end.focus();
      return false;
  } else if (((int_date_end - int_date_start)/86400000) < 14 ) {
    alert(\'Interval time is less than 14 days,Please try again\');
    f.date_end.focus();
    return false;
  }
  if (parseInt(f.min_word.value)> parseInt(f.max_word.value))
  {
    alert(\'min words is more than max words, please try again\');
    f.min_word.focus();
    return false;
  }

  if (f.is_mentioned.value == 1)
  {
    if (f.biz_name.length == 0)
    {
        alert("Please sepecify the business name");
        f.biz_name.focus();
        return false;
    }
  }

/*  if (f.campaign_requirement.value == \'\') {
    alert(\'Please enter Additional Style Guide \');
    f.campaign_requirement.focus();
    //f.date_end.focus();
    return false;
  }

  if (f.sample_content.value == \'\') {
    alert(\'Please enter Sample Content\');
    f.sample_content.focus();
    return false;
  }*/

  if (f.keyword_instructions.value == \'\') {
    alert(\'Please enter Content Instructions\');
    f.keyword_instructions.focus();
    return false;
  }

  if (f.ordered_by.value == \'\') {
    alert(\'Please enter Ordered By\');
    f.ordered_by.focus();
    return false;
  }

  '; ?>

  <?php if ($this->_tpl_vars['client_is_loggedin'] == 1): ?>
  <?php echo '
  if (!f.agreeterm.checked)
  {
      alert(\'Please agree the terms and conditions\');
      f.agreeterm.focus();
      return false;
  }
  '; ?>

  <?php elseif ($this->_tpl_vars['order_campaign_info']['status'] == 0 && $this->_tpl_vars['is_confirm'] == 1): ?>
  <?php echo '
  if (f.operation.value == \'confirm\') {
      if (f.discount.value.length == 0)
      {
          alert(\'Please specify the discount\');
          f.discount.focus();
          return false;    
      }
      if (f.fees.value.length == 0)
      {
          alert(\'Please specify the fees\');
          f.fees.focus();
          return false;    
      }
   }
  '; ?>

  <?php endif; ?>
  <?php echo '

  return true;
}
//-->
function startDateChange(start_date)
{
    var int_start_date = Date.parse(start_date)/1000;
    var int_end_date = (int_start_date + 1209600)*1000;
    var d = new Date();
    d.setTime(int_end_date);
    var dmonth = d.getMonth() + 1;
    if (dmonth < 10) dmonth = \'0\' + dmonth;
    $(\'date_end\').value = d.getFullYear()+"-" + dmonth +"-" + d.getDate();
    
}

function isShowBizName(is_mentioned)
{
    if (is_mentioned == 1)
    {
        $(\'divbiznameformat\').show();
    }
    else
    {
        $(\'divbiznameformat\').hide();
    }
}

function onUncheck(obj, comments)
{
    if (!obj.checked && confirm(comments)){
        obj.checked=false;
    } else {
        obj.checked = true;
    }
}
'; ?>

var source = <?php if ($this->_tpl_vars['order_campaign_info']['source'] > 0):  echo $this->_tpl_vars['order_campaign_info']['source'];  else: ?>0<?php endif; ?>;
<?php echo '
function changeclient(client_id)
{
    ajaxAction(\'/client_campaign/getdomains.php?cid=\'+client_id+\'&s=\'+source, \'domaindiv\');
}
tinyMCEInit(\'target_audience,highlight_desc,particular_desc, campaign_requirement,sample_content,keyword_instructions,special_instructions\');
</script>
'; ?>


<div id="page-box1">
  <h2><?php if ($this->_tpl_vars['order_campaign_info']['order_campaign_id']): ?>Client's Order Campaign Information Setting<?php else: ?>Order New Campaign<?php endif; ?></h2>
  <div id="campaign-search" >
    <strong>Please enter your campaign order details into the form. Please
refer to the <a href="/client_campaign/campaignorderguide.pdf" target="_blank" >campaign order guide</a> for more information
about how to fill out the form properly. If you have any questions
please contact us at <a href="mailto:support@copypress.com" >support@copypress.com</a></strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_client_campaign" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_client_campaign()"<?php endif; ?>>
  <input type="hidden" name="order_campaign_id" value="<?php echo $this->_tpl_vars['order_campaign_info']['order_campaign_id']; ?>
">
  <input type="hidden" name="operation" id="operation" value="N" />
  <input type="hidden" name="parent_id" id="parent_id" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['order_campaign_info']['parent_id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" />
  <input type="hidden" name="price_id" id="price_id" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['price']['price_id'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" />
  <input type="hidden" name="article_price" id="article_price" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['price']['article_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" />

  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint" colspan="3">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['client_is_loggedin'] == 0): ?>
  <tr>
    <td class="requiredInput">Client</td>
    <td colspan="3"><select name="client_id" onchange="changeclient(this.value)"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_client'],'selected' => $this->_tpl_vars['order_campaign_info']['client_id']), $this);?>
</select>&nbsp;<img src="/image/select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("/client/client_quick_add.php","add_client","width=600,height=450,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><input name="campaign_name" type="text" id="campaign_name" value="<?php echo $this->_tpl_vars['order_campaign_info']['campaign_name']; ?>
" onchange="javascript:this.value=Trim(this.value)" /></td>
    <td class="requiredInput">Subtotal</td>
    <td>$<input name="subtotal" type="text" id="subtotal" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['price']['subtotal'])) ? $this->_run_mod_handler('default', true, $_tmp, '0.00') : smarty_modifier_default($_tmp, '0.00')); ?>
" onchange="javascript:this.value=Trim(this.value)" readonly /></td>
  </tr>
  <tr>
    <td class="dataLabel">Domain</td>
    <td><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['domains'],'name' => 'source','id' => 'source','selected' => $this->_tpl_vars['order_campaign_info']['source']), $this);?>
<div id="domaindiv" ></div></td>
    <td class="requiredInput">Discount</td>
    <td>$<input name="discount" type="text" id="discount" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['price']['discount'])) ? $this->_run_mod_handler('default', true, $_tmp, '0.00') : smarty_modifier_default($_tmp, '0.00')); ?>
" onchange="javascript:this.value=Trim(this.value);<?php if ($this->_tpl_vars['client_is_loggedin'] == 0): ?>changeTotal()<?php endif; ?>" <?php if ($this->_tpl_vars['is_confirm'] != 1 || $this->_tpl_vars['client_is_loggedin'] == 1): ?>readonly<?php endif; ?> /></td>
  </tr>
  <tr>
    <td class="requiredInput">Category</td>
    <td>    <select name="category_id">
    <?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
    <option value="<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['order_campaign_info']['category_id'] == $this->_tpl_vars['k']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']['name']; ?>
</option>
    <?php $_from = $this->_tpl_vars['i']['chidren']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subk'] => $this->_tpl_vars['name']):
?>
    <option value="<?php echo $this->_tpl_vars['subk']; ?>
" <?php if ($this->_tpl_vars['order_campaign_info']['category_id'] == $this->_tpl_vars['subk']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
    </select>
    </td>
    <td class="requiredInput">Fees</td>
    <td>$<input name="fees" type="text" id="fees" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['price']['fees'])) ? $this->_run_mod_handler('default', true, $_tmp, '0.00') : smarty_modifier_default($_tmp, '0.00')); ?>
" onchange="javascript:this.value=Trim(this.value);<?php if ($this->_tpl_vars['client_is_loggedin'] == 0): ?>changeTotal()<?php endif; ?>" <?php if ($this->_tpl_vars['is_confirm'] != 1 || $this->_tpl_vars['client_is_loggedin'] == 1): ?>readonly<?php endif; ?> /></td>
  </tr>
  <tr>
    <td class="requiredInput">Content Type</td>
    <td>
    <select name="article_type" id="article_type" onchange="calculatePrice()" >
      <option value="-1" >[default]</option>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $this->_tpl_vars['order_campaign_info']['article_type']), $this);?>

    </select>
    </td>
    <td class="requiredInput">Total</td>
    <td>$<input name="total" type="text" id="total" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['price']['total'])) ? $this->_run_mod_handler('default', true, $_tmp, '0.00') : smarty_modifier_default($_tmp, '0.00')); ?>
" onchange="javascript:this.value=Trim(this.value)" readonly /></td>
  </tr>
  <tr>
    <td class="requiredInput">Content Qty</td>
    <td colspan="3"><input name="qty" type="text" id="qty" value="<?php echo $this->_tpl_vars['order_campaign_info']['qty']; ?>
" onchange="javascript:this.value=Trim(this.value);calculatePrice();"/></td>
  </tr>
  <tr>
    <td class="dataLabel">Min number of Words</td>
    <td colspan="3">
      <select id="min_word" name="min_word">
      <?php $_from = $this->_tpl_vars['word_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['total']):
?>
      <?php if ($this->_tpl_vars['total'] < 1000): ?><option value="<?php echo $this->_tpl_vars['total']; ?>
" <?php if ($this->_tpl_vars['order_campaign_info']['min_word'] == $this->_tpl_vars['total']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['total']; ?>
</option><?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Max number of Words</td>
    <td colspan="3">
      <select id="max_word" name="max_word" onchange="calculatePrice()" >
      <?php $_from = $this->_tpl_vars['word_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['total']):
?>
      <?php if ($this->_tpl_vars['total'] > 50): ?><option value="<?php echo $this->_tpl_vars['total']; ?>
" <?php if ($this->_tpl_vars['order_campaign_info']['max_word'] == $this->_tpl_vars['total']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['total']; ?>
</option><?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
      </select>
    </td>
  </tr>
    <td class="requiredInput"> Start Date</td>
    <td colspan="3"><input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['order_campaign_info']['date_start']; ?>
" readonly onchange="startDateChange(this.value)" />
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
    <td colspan="3"><input type="text" name="date_end" id="date_end" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['order_campaign_info']['date_end']; ?>
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
    <td></td>
    <td colspan="4">
    Who is your target audience (age, gender, expertise level, etc.)?<br />
    <textarea name="target_audience" cols="50" rows="4" id="target_audience"><?php echo $this->_tpl_vars['order_campaign_info']['target_audience']; ?>
</textarea></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4">
    Which sales approach would you like us to take in the copy?<select id="sale_type" name="sale_type">
    <option value="0" >[choose]</option>
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['sale_types'],'selected' => $this->_tpl_vars['order_campaign_info']['sale_type']), $this);?>
</select></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4">
    What tone would you prefer?<?php echo smarty_function_html_options(array('name' => 'article_tone','options' => $this->_tpl_vars['tones'],'selected' => $this->_tpl_vars['order_campaign_info']['article_tone']), $this);?>
</td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4">
    Do you want your business name mentioned in the text?
    <select id="is_mentioned" name="is_mentioned" onchange="isShowBizName(this.value)" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['yesorno'],'selected' => $this->_tpl_vars['order_campaign_info']['is_mentioned']), $this);?>
</select>
    <br />
    <div id="divbiznameformat">
      If so, how would you like it to appear?<input type="text" name="biz_name" id="biz_name" value="<?php echo $this->_tpl_vars['order_campaign_info']['biz_name']; ?>
" size="50" />
    </div>
    </td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4">
    Is there anything in particular about your company that you'd like us to highlight in the content?<br />
    <textarea name="highlight_desc" cols="50" rows="4" id="highlight_desc"><?php echo $this->_tpl_vars['order_campaign_info']['highlight_desc']; ?>
</textarea></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4">
    Any particular things that you DO or DON'T want to be discussed in the article/content?<br />
    <textarea name="particular_desc" cols="50" rows="4" id="particular_desc"><?php echo $this->_tpl_vars['order_campaign_info']['particular_desc']; ?>
</textarea></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="4">
    Do you want an image inserted into each content piece?
    <select id="is_insert_img" name="is_insert_img"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['yesorno'],'selected' => $this->_tpl_vars['order_campaign_info']['is_insert_img']), $this);?>
</select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ORDER_CONTENT_INSTRUCTIONS','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Content Instructions</a></td>
    <td colspan="3"><textarea name="keyword_instructions" cols="50" rows="4" id="keyword_instructions"><?php echo $this->_tpl_vars['order_campaign_info']['keyword_instructions']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ORDER_ADDITIONAL_STYLE_GUIDE','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=no')" class="classtips">Additional Style Guide</a></td>
    <td colspan="3"><textarea name="campaign_requirement" cols="50" rows="4" id="campaign_requirement"><?php echo $this->_tpl_vars['order_campaign_info']['campaign_requirement']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ORDER_SAMPLE_CONTENT','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Sample Content</a></td>
    <td colspan="3"><textarea name="sample_content" cols="50" rows="4" id="sample_content"><?php echo $this->_tpl_vars['order_campaign_info']['sample_content']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ORDER_SPECIAL_INSTRUCTIONS','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips" >Special Instructions</a></td>
    <td colspan="3"><textarea name="special_instructions" cols="50" rows="4" id="special_instructions"><?php echo $this->_tpl_vars['order_campaign_info']['special_instructions']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="requiredInput">Ordered By</td>
    <td colspan="3"><input name="ordered_by" type="text" id="ordered_by" value="<?php echo $this->_tpl_vars['order_campaign_info']['ordered_by']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="60" /></td>
  </tr>
  <?php if ($this->_tpl_vars['user_permission_int'] == 5): ?>
  <tr>
    <td class="dataLabel">Monthly Recurrent</td>
    <td>
      <input type="checkbox" name="monthly_recurrent" id="monthly_recurrent" value="1" <?php if ($this->_tpl_vars['client_campaign_info']['monthly_recurrent'] == 1): ?> checked <?php endif; ?> onclick="onClickMonthRecurrent(this)"  />
      <select id="recurrent_time" name="recurrent_time" <?php if ($this->_tpl_vars['client_campaign_info']['monthly_recurrent'] != 1): ?>disabled<?php endif; ?>>
      <option value="0" >[choose months]</option>
      <?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)2;
$this->_sections['foo']['loop'] = is_array($_loop=13) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
      <option value="<?php echo $this->_sections['foo']['index']; ?>
" <?php if ($this->_tpl_vars['order_campaign_info']['recurrent_time'] == $this->_sections['foo']['index']): ?>selected<?php endif; ?>><?php echo $this->_sections['foo']['index']; ?>
</option>
      <?php endfor; endif; ?>
      </select>
     </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>
    </td>
    <td align="left"  colspan="3">
      <?php if ($this->_tpl_vars['client_is_loggedin'] == 1 && $this->_tpl_vars['is_confirm'] != 1 || $this->_tpl_vars['client_is_loggedin'] == 0 && $this->_tpl_vars['is_confirm'] == 1): ?>
      <input name="is_confirm" value="1" type="checkbox" <?php if ($this->_tpl_vars['order_campaign_info']['is_confirm'] == 1): ?>checked<?php endif; ?> onclick="onUncheck(this, 'Does <?php if ($this->_tpl_vars['client_is_loggedin'] == 1): ?>admin<?php else: ?>client<?php endif; ?> need to confirm order? ')" /><?php if ($this->_tpl_vars['client_is_loggedin'] == 1): ?>Do you want to confirm revised content instructions?<?php else: ?>Does client need to confirm order?<?php endif; ?>
      <?php endif; ?>
    </td>
  </tr>
  <?php if ($this->_tpl_vars['client_is_loggedin'] == 1 && $this->_tpl_vars['order_campaign_info']['order_campaign_id'] == ''): ?>
  <tr>
    <td>
    </td>
    <td align="left"  colspan="3">
      <input name="agreeterm" value="1" type="checkbox" checked />I Agree to <a href="http://www.copypress.com/terms-and-conditions/ " target="_blank">Terms & Conditions</a>
    </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="blackLine"  colspan="4" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td  colspan="3">
    <?php if ($this->_tpl_vars['is_confirm'] == 1): ?>
      <?php if ($this->_tpl_vars['order_campaign_info']['status'] == 0 || $this->_tpl_vars['order_campaign_info']['status'] == 4): ?>
      <input type="submit" value="Confirm" class="button" onclick="$('operation').value='confirm';" />&nbsp;
      <input type="submit" value="Deny" class="button" onclick="$('operation').value='deny';" />&nbsp;
      <input type="button" value="Back" class="button" onclick="window.location.href='/client_campaign/order_list.php'" />&nbsp;
      <?php endif; ?>
    <?php else: ?>
      <input type="submit" value="Submit" class="button" />&nbsp;
    <?php endif; ?>
    </td>
  </tr>
  </form>
</table>
  </div>
</div>
<script>
var prices = <?php echo $this->_tpl_vars['prices']; ?>
;
<?php echo '
function calculatePrice()
{
    
    var type_id = $(\'article_type\').value;
    var qty = $(\'qty\').value;
    var max_word = $(\'max_word\').value;
    var arr = null;
    if (type_id >= 0 && max_word > 0) {
      if (isObjectOrNot(prices[type_id]))
      {
        arr = prices[type_id][max_word];
      }
    }
    var article_price = null;
    if (arr){
      article_price = arr[1];$(\'price_id\').value= arr[0];
    } else {
      article_price = 0;
      $(\'price_id\').value = 0;
    }
    var subtotal = qty * article_price;
    var discount =  parseFloat($(\'discount\').value);
    var fees =  parseFloat($(\'fees\').value);
    $(\'article_price\').value = article_price;
    $(\'subtotal\').value = subtotal;
    var total = subtotal - discount + fees;
    $(\'total\').value = decimal(total, 2);
}

function changeTotal()
{
    var subtotal =  parseFloat($(\'subtotal\').value);
    var discount =  parseFloat($(\'discount\').value);
    var fees =  parseFloat($(\'fees\').value);
    var total = subtotal - discount + fees;
    $(\'total\').value = decimal(total, 2);
}
function onClickMonthRecurrent(obj)
{
    if (obj.checked) {
      $(\'recurrent_time\').disabled = false;
    } else {
      $(\'recurrent_time\').disabled = true;
    }
    return true;
}
'; ?>

calculatePrice();
startDateChange($('date_start').value);
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>