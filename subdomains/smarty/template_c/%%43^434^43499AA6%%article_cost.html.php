<?php /* Smarty version 2.6.11, created on 2012-05-07 07:25:38
         compiled from client_campaign/article_cost.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/article_cost.html', 61, false),)), $this); ?>
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
<?php endif;  echo '
<script language="JavaScript">
function check_f_article_cost( operation )
{
	var f = document.f_article_cost;
	f.operation.value = operation;
	f.submit();
}

function redirect(campaign_id, client_id)
{
  var param = \'\';
  if (campaign_id.length > 0 || client_id.length > 0)
  {
    param = \'?\';
    if (campaign_id > 0)
    {
        param += "campaign_id=" + campaign_id + \'&\';
    }

    if (client_id > 0)
    {
        param += "client_id=" + client_id + \'&\';
    }
    changeReturn(\'/client_campaign/article_cost.php\', param);
  }
}
</script>
'; ?>

<div id="page-box1">
  <h2>Custom Campaign Cost</h2>
  <div id="campaign-search" >
    <strong>Please enter the Campaign's custom cost per word for each type.</strong>
  </div>
  <div class="form-item" >
<form action="" method="post" name="f_article_cost" >
<table cellspacing="0" cellpadding="4" align="center" width="99%">
    <input type="hidden" name="operation" value="" />
    <input type="hidden" name="query_string" value="<?php echo $this->_tpl_vars['query_string']; ?>
" />
     <tr>
	      <td class="bodyBold">Basic Information</td>
	      <td align="right" class="requiredHint" colspan="10" >Required Information</td>
      </tr>
      <tr>
	      <td class="blackLine" colspan="10"><img src="/image/misc/s.gif"></td>
     </tr>
     <tr>
        <td class="requiredInput" >Client&nbsp;</td>
        <td align="left" colspan="10" >
          <select name="client" id="client" onchange="redirect($('campaign').value, this.value)" >
          <option value="" >[all]</option>
          <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_client'],'selected' => $_GET['client_id']), $this);?>

          </select>
        </td>
      </tr>
     <tr>
        <td class="requiredInput" >Campaign Name:&nbsp;</td>
        <td align="left" colspan="10" >
          <select name="campaign" id="campaign" onchange="redirect(this.value, $('client').value)" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaign_list'],'selected' => $_GET['campaign_id']), $this);?>
</select>
        </td>
      </tr>
  <?php if ($this->_tpl_vars['total_type'] > 0): ?>
  <?php $_from = $this->_tpl_vars['article_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
        $this->_foreach['loop']['iteration']++;
?>
          <tr>
          <td class="requiredInput">
      <input type="hidden" name="campaign_id[]"  value="<?php echo $this->_tpl_vars['type']['campaign_id']; ?>
" />
      <input type="hidden" name="article_type[]"  value="<?php echo $this->_tpl_vars['type']['article_type']; ?>
" />
      <input type="hidden" name="invoice_status[]"  value="<?php echo $this->_tpl_vars['type']['invoice_status']; ?>
" />
      <input type="hidden" name="cost_id[]"  value="<?php echo $this->_tpl_vars['type']['cost_id']; ?>
" />
      <?php echo $this->_tpl_vars['all_article_types'][$this->_tpl_vars['type']['article_type']]; ?>
 Copywriter Cost per Word:
      </td>
      <td align="left" nowrap>
      $<input type="text" name="cp_cost[]"  value="<?php echo $this->_tpl_vars['type']['cp_cost']; ?>
"  />
      </td>
      <td class="requiredInput"><?php echo $this->_tpl_vars['all_article_types'][$this->_tpl_vars['type']['article_type']]; ?>
 Editor Cost per word:</td>
      <td nowrap>$<input type="text" name="editor_cost[]"  value="<?php echo $this->_tpl_vars['type']['editor_cost']; ?>
"  /></td>
          </tr>
        <tr>
      <td></td>
      <td><input type="checkbox" name="pay_by_article[<?php echo $this->_tpl_vars['key']; ?>
]" value="1" <?php if ($this->_tpl_vars['type']['pay_by_article'] == 1): ?>checked<?php endif; ?> />Pay by Article</td>
    </tr>
    <tr>
      <td class="dataLabel"><?php echo $this->_tpl_vars['all_article_types'][$this->_tpl_vars['type']['article_type']]; ?>
 Copywriter Cost per Article</td>
      <td><input type="text" name="cp_article_cost[]"  value="<?php echo $this->_tpl_vars['type']['cp_article_cost']; ?>
"  /></td>
      <td class="dataLabel"><?php echo $this->_tpl_vars['all_article_types'][$this->_tpl_vars['type']['article_type']]; ?>
 Editor Cost per Article</td>
      <td nowrap>$<input type="text" name="editor_article_cost[]"  value="<?php echo $this->_tpl_vars['type']['editor_article_cost']; ?>
"  /></td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="blackLine" ><br /></td>
    </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
      <tr><td></td><td colspan="6" align="left" >There is no type cost per word for this campaign</td></tr>
  <?php endif; ?>
      <tr>
      <td colspan="6" align="center">
	      <?php if ($this->_tpl_vars['total_type'] > 0): ?><input type="button" class="button" name="save" value="Save" onclick="check_f_article_cost('save')"/>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        <input type="button" class="button" name="new_article_cost" id="new_article_cost" value="Add New Type" onclick="javascript:openWindow('/client_campaign/add_article_cost.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
', 'newwindow', 'height=341px,width=420px,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"/>
      </td>
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