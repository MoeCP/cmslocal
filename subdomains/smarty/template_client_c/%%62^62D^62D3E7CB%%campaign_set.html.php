<?php /* Smarty version 2.6.11, created on 2013-10-31 05:52:27
         compiled from client_campaign/campaign_set.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/campaign_set.html', 88, false),)), $this); ?>
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
  if (f.client_id.value.length == 0) {
    alert(\'Please choose a client\');
    f.client_id.focus();
    return false;
  }
  if (f.campaign_name.value.length == 0) {
    alert(\'Please specify the campaign name\');
    f.campaign_name.focus();
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

  if (f.ordered_by.value.length == 0) {
    alert(\'Please specify Ordered By\');
    f.ordered_by.focus();
    return false;
  }
  return true;
}
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
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Client</td>
    <td>
      <?php echo $this->_tpl_vars['client_name']; ?>

      <input name="client_id" type="hidden" id="client_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['client_id']; ?>
" />
      <input name="campaign_type" type="hidden" id="campaign_type" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_type']; ?>
" />
      <input name="ordered_by" type="hidden" id="ordered_by" value="<?php echo $this->_tpl_vars['client_campaign_info']['ordered_by']; ?>
" />      
      <input type="hidden" name="title_param" id="title_param_custom" value="1" />
      <input type="hidden" name="meta_param" id="meta_param_default" value="0" />
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><input name="campaign_name" type="text" id="campaign_name" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="dataLabel">Domain</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['domains'][$this->_tpl_vars['client_campaign_info']['source']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<input type="hidden" name="source" id="source" value="<?php echo $this->_tpl_vars['client_campaign_info']['source']; ?>
" /></td>
  </tr>
  <tr>
    <td class="dataLabel">Article Type</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['article_type'][$this->_tpl_vars['client_campaign_info']['article_type']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<input type="hidden" name="article_type" id="article_type" value="<?php echo $this->_tpl_vars['client_campaign_info']['article_type']; ?>
" /></td>
  </tr>
  <tr>
    <td class="dataLabel">Template</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['templates'][$this->_tpl_vars['client_campaign_info']['template']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
<input type="hidden" name="template" id="template" value="<?php echo $this->_tpl_vars['client_campaign_info']['template']; ?>
" /></td>
  </tr>
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
    <td><input id="max_word" name="max_word"  value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['client_campaign_info']['max_word'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Total # of Articles</td>
    <td><input id="total_keyword" name="total_keyword"  value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['client_campaign_info']['total_keyword'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" /></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Next" class="button"></td>
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