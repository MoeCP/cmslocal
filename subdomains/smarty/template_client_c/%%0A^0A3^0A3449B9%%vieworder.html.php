<?php /* Smarty version 2.6.11, created on 2012-02-08 22:07:01
         compiled from client_campaign/vieworder.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'client_campaign/vieworder.html', 103, false),array('modifier', 'html_entity_decode', 'client_campaign/vieworder.html', 117, false),array('modifier', 'default', 'client_campaign/vieworder.html', 188, false),array('modifier', 'date_format', 'client_campaign/vieworder.html', 188, false),array('function', 'html_options', 'client_campaign/vieworder.html', 179, false),)), $this); ?>
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

<div id="page-box1">
  <h2><?php echo $this->_tpl_vars['title']; ?>
</h2>
<form action="" method="post" name="f_payment" id="f_payment" >
<input type="hidden"  name="order_id" id="order_id" value="<?php echo $this->_tpl_vars['info']['order_campaign_id']; ?>
" />
<input type="hidden"  name="payment_id" id="payment_id" value="<?php echo $this->_tpl_vars['priceinfo']['payment_id']; ?>
" />
<input type="hidden"  name="operation" id="operation" value="N" />
<div class="form-item"> 
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint" colspan="3" >Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=4><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Order ID</td>
    <td colspan="3"><?php echo $this->_tpl_vars['info']['order_campaign_id']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['client_is_loggedin'] != 1): ?>
  <tr>
    <td class="requiredInput">Client</td>
    <td colspan="3"><?php echo $this->_tpl_vars['all_client'][$this->_tpl_vars['info']['client_id']]; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['info']['campaign_name']; ?>
</td>
   <?php if ($this->_tpl_vars['priceinfo']): ?>
    <td class="requiredInput">Subtotal</td>
    <td>$<?php echo $this->_tpl_vars['priceinfo']['subtotal']; ?>
</td>
   <?php endif; ?>
  </tr>
  <tr>
    <td class="requiredInput">Category</td>
    <td><?php echo $this->_tpl_vars['category'][$this->_tpl_vars['info']['category_id']]; ?>
</td>
     <?php if ($this->_tpl_vars['priceinfo']): ?>
      <td class="requiredInput">Discount</td>
      <td>$<?php echo $this->_tpl_vars['priceinfo']['discount']; ?>
</td>
     <?php endif; ?>
  </tr>
  
  <tr>
    <td class="dataLabel">Domain</td>
    <td><?php if ($this->_tpl_vars['info']['source'] > 0):  echo $this->_tpl_vars['domains'][$this->_tpl_vars['info']['source']];  else: ?>n/a<?php endif; ?></td>
   <?php if ($this->_tpl_vars['priceinfo']): ?>
    <td class="requiredInput">Fees</td>
    <td>$<?php echo $this->_tpl_vars['priceinfo']['fees']; ?>
</td>
   <?php endif; ?>
  </tr>
  <tr>
    <td class="requiredInput">Content Type</td>
    <td><?php if ($this->_tpl_vars['info']['article_type'] > -1):  echo $this->_tpl_vars['article_types'][$this->_tpl_vars['info']['article_type']];  else: ?>n/a<?php endif; ?></td>
   <?php if ($this->_tpl_vars['priceinfo']): ?>
    <td class="requiredInput">Total</td>
    <td>$<?php echo $this->_tpl_vars['priceinfo']['total']; ?>
</td>
   <?php endif; ?>
  </tr>
  <tr>
    <td class="requiredInput">Content Qty</td>
    <td><?php echo $this->_tpl_vars['info']['qty']; ?>
</td>
  <?php if (( $this->_tpl_vars['client_is_loggedin'] == 0 || $this->_tpl_vars['info']['status'] == 10 ) && $this->_tpl_vars['priceinfo']['account']): ?>
    <td class="requiredInput">Merchant Account</td>
    <td><?php echo $this->_tpl_vars['priceinfo']['account']; ?>
</td>
  <?php endif; ?>
  </tr>
  <tr>
    <td class="dataLabel">Min Number of Word</td>
    <td><?php echo $this->_tpl_vars['info']['min_word']; ?>
</td>
  <?php if (( $this->_tpl_vars['client_is_loggedin'] == 0 || $this->_tpl_vars['info']['status'] == 10 ) && $this->_tpl_vars['priceinfo']['trans_num']): ?>
    <td class="requiredInput">Transaction Number</td>
    <td><?php echo $this->_tpl_vars['priceinfo']['trans_num']; ?>
</td>
  <?php endif; ?>
  </tr>
  <tr>
    <td class="requiredInput">Max Number of Word</td>
    <td><?php echo $this->_tpl_vars['info']['max_word']; ?>
</td>
  <?php if (( $this->_tpl_vars['client_is_loggedin'] == 0 || $this->_tpl_vars['info']['status'] == 10 ) && $this->_tpl_vars['priceinfo']['trans_num']): ?>
    <td class="requiredInput">Transaction Post Date</td>
    <td><?php echo $this->_tpl_vars['priceinfo']['trans_date']; ?>
</td>
  <?php endif; ?>
  </tr>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td colspan="3"><?php echo $this->_tpl_vars['info']['date_start']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Due Date</td>
    <td colspan="3"><?php echo $this->_tpl_vars['info']['date_end']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Target Audience</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['target_audience'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Article Tone</td>
    <td colspan="3"><?php if ($this->_tpl_vars['info']['article_tone'] > 0):  echo $this->_tpl_vars['tones'][$this->_tpl_vars['info']['article_tone']];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <?php if ($this->_tpl_vars['info']['is_mentioned'] == 1): ?>
  <tr>
    <td class="dataLabel">Business Name</td>
    <td colspan="3"><?php echo $this->_tpl_vars['info']['biz_name']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="dataLabel">Highlight Particular Things in Content</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['highlight_desc'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Particular Description for Article</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['particular_desc'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Do you want an image inserted into each content piece? </td>
    <td colspan="3"><?php if ($this->_tpl_vars['info']['is_insert_img'] == 1): ?>Yes<?php else: ?>No<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Content Instructions</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['keyword_instructions'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Additional Style Guide</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['campaign_requirement'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Content Sample</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['sample_content'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Special Instructions</td>
    <td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['special_instructions'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['keywords'] != ''): ?>
  <tr>
    <td class="requiredInput">Content Details:</td>
  </tr>
  <tr>
    <td colspan="4" align="center" >
  
    
    <table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
    <tr class="sortableTab">
      <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th class="table-left-2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <?php $_from = $this->_tpl_vars['keywords']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>    
      <th class="columnHeadInactiveBlack" ><?php echo $this->_tpl_vars['value']; ?>
</th>
      <?php endforeach; endif; unset($_from); ?>
      <th class="table-right-2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
    </tr>
    <?php $_from = $this->_tpl_vars['keywords']['optional1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
    <tr>
      <td class="table-left" >&nbsp;</td>
      <td class="table-left-2" >&nbsp;</td>
      <?php $_from = $this->_tpl_vars['keywords']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?> 
      <td><?php echo $this->_tpl_vars['keywords'][$this->_tpl_vars['k']][$this->_tpl_vars['key']]; ?>
</td>
      <?php endforeach; endif; unset($_from); ?>
      <td class="table-right-2" >&nbsp;</td>
      <td class="table-right" >&nbsp;</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </table>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['client_is_loggedin'] == 0 && ( ( $this->_tpl_vars['is_pay'] && $this->_tpl_vars['info']['status'] == 7 ) || ( $this->_tpl_vars['fadjust'] == 1 && $this->_tpl_vars['info']['status'] == 10 ) )): ?>
   <tr>
    <td class="requiredInput">Merchant Account</td>
    <td colspan="3"><select id="account" name="account" selected="<?php echo $this->_tpl_vars['priceinfo']['account']; ?>
"><option value="0" >[choose]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['accounts'],'output' => $this->_tpl_vars['accounts'],'selected' => $this->_tpl_vars['priceinfo']['account']), $this);?>
</select></td>
   </tr>
   <tr>
    <td class="requiredInput">Transaction Number</td>
    <td colspan="3"><input name="trans_num" id="trans_num" value="<?php echo $this->_tpl_vars['priceinfo']['trans_num']; ?>
" /></td>
  </tr>
   <tr>
    <td class="requiredInput">Transaction Posting Date</td>
    <td colspan="3">
      <input name="trans_date" id="trans_date" size="10" maxlength="10"  value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['priceinfo']['trans_date'])) ? $this->_run_mod_handler('default', true, $_tmp, time()) : smarty_modifier_default($_tmp, time())))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
" />
      <input type="button" class="button" id="btn_cal_trans_date" value="...">
      <script type="text/javascript">
      Calendar.setup({
          inputField  : "trans_date",
          ifFormat    : "%Y-%m-%d",
          showsTime   : false,
          button      : "btn_cal_trans_date",
          singleClick : true,
          step        : 1,
          range       : [1990, 2030]
      });
      </script>
    </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  
  <tr>
    <td align="right" colspan="4" >
    <?php if ($this->_tpl_vars['is_pay'] || $this->_tpl_vars['is_confirm'] || $this->_tpl_vars['fadjust']): ?>
      <?php if ($this->_tpl_vars['client_is_loggedin'] == 0): ?>
      <?php if ($this->_tpl_vars['is_pay'] && $this->_tpl_vars['info']['status'] == 7): ?>
      <input type="button" value="Mark as Paid" class="button" onclick="check_f_payment('paid');" />&nbsp;
      <?php elseif ($this->_tpl_vars['info']['status'] == 10 && $this->_tpl_vars['fadjust'] == 1): ?>
      <input type="button" value="Save" class="button" onclick="check_f_payment('save');" />&nbsp;
      <?php endif; ?>
      <input type="button" value="Back" class="button" onclick="window.location.href='/client_campaign/order_list.php'" />&nbsp;
      <?php elseif ($this->_tpl_vars['client_is_loggedin'] == 1 && $this->_tpl_vars['is_confirm'] == 1): ?>
      <?php if ($this->_tpl_vars['info']['status'] == 0): ?>
      <input type="button" value="Cancel" class="button" onclick="check_f_payment('deny')" />&nbsp;
      <?php endif; ?>
      <input type="button" value="Back" class="button" onclick="window.location.href='/client_campaign/order_list.php'" />&nbsp;
      <?php if ($this->_tpl_vars['info']['status'] == 4): ?>
      <input type="button" value="Deny" class="button" onclick="check_f_payment('deny')" />&nbsp;
      <input type="button" value="Pay by NetSuite" class="button" onclick="check_f_payment('confirm')" />&nbsp;
      <?php endif; ?>
      <?php endif; ?>
   <?php else: ?>
      <input type="button" value="Back" class="button" onclick="window.location.href='/client_campaign/order_list.php'" />&nbsp;
    <?php endif; ?>
    </td>
  </tr>
  <?php if ($this->_tpl_vars['info']['status'] == 4 && $this->_tpl_vars['client_is_loggedin'] == 1 && $this->_tpl_vars['is_confirm'] == 1): ?>
  <tr>
    <td align="right"  colspan="4" ><a href="#" onclick="check_f_payment('checkout')" ><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="right" style="margin-right:7px;"> </a></td>
  </tr>
  <?php endif; ?>
    <?php if ($this->_tpl_vars['info']['status'] == 4 && $this->_tpl_vars['client_is_loggedin'] == 1 && $this->_tpl_vars['is_confirm'] == 1): ?>
  <tr>
    <td colspan="4" align="right">Campaign will not begin on your order until payment has been processed</td>
  </tr>
  <?php endif; ?>
</table>
</div>
</div>
</form>
<?php echo '
<script language="JavaScript">
function check_f_payment(opt){
  var form = document.f_payment;
  form.operation.value = opt;
  if (opt == \'paid\' || opt == \'save\'){
    if (form.account.value == \'\') {
      alert(\'Please speciy the Merchant Account\');
      return false;
    }

    if (form.trans_num.value == \'\') {
      alert(\'Please speciy the Transaction Number\');
      return false;
    }

    if (form.trans_date.value == \'\'){
      alert(\'Please speciy the Transaction Posting Date\');
      return false;
    }
  }
  form.submit();
}
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>