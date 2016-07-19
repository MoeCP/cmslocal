<?php /* Smarty version 2.6.11, created on 2012-05-07 07:26:25
         compiled from client_campaign/add_article_cost.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/add_article_cost.html', 54, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
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
<?php endif;  echo '
<script language="JavaScript">
function redirect(article_type, campaign_id)
{
  var param = \'\';
  var url = \'/client_campaign/add_article_cost.php?campaign_id=\' + campaign_id;
  if (article_type.length > 0)
  {
    param = "&article_type=" + article_type;
    changeReturn(url, param);
  }
}

function check_f_new_article_cost( operation )
{
	var f = document.f_new_article_cost;
	f.operation.value = operation;
	f.submit();
}
</script>
'; ?>

<div id="page-box1">
  <h2>Specify New Article Type for <?php echo $this->_tpl_vars['campaign_info']['campaign_name']; ?>
</h2>
  <div class="form-item" >
<form action="" method="post" name="f_new_article_cost" id="f_new_article_cost" >
  <input type="hidden" name="operation" id="operation" value="" />
  <input type="hidden" name="invoice_status" id="invoice_status" value="" />
  <input type="hidden" name="query_string" id="query_string" value="<?php echo $this->_tpl_vars['query_string']; ?>
" />
  <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_info']['campaign_id']; ?>
" />
  <input type="hidden" name="cost_id" id="cost_id" value="" />
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan="2" ></td>
  </tr>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['total_selected'] > 0): ?>
  <tr>
    <td class="dataLabel">Selected Article Types:</td>
    <td><select name="selected_article_type" id="selected_article_type" size="5" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['selected_types']), $this);?>
</select></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Article Types:</td>
    <td><select name="article_type" id="article_type" onchange="redirect(this.value, <?php echo $this->_tpl_vars['campaign_info']['campaign_id']; ?>
)" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_types'],'selected' => $_GET['article_type']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="dataLabel">Default Copywriter cost per word for this campaign:</td>
    <td>$<input type="text" name="cp_cost" id="cp_cost" value="<?php echo $this->_tpl_vars['type_info']['cp_cost']; ?>
"  /></td>
  </tr>
  <tr>
    <td class="dataLabel">Default  Editor cost per word for this campaign:</td>
    <td>$<input type="text" name="editor_cost" id="editor_cost" value="<?php echo $this->_tpl_vars['type_info']['editor_cost']; ?>
"  /></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="checkbox" name="pay_by_article" id="pay_by_article" value="1" <?php if ($this->_tpl_vars['type_info']['pay_by_article'] == 1): ?>checked<?php endif; ?> />Pay by Article</td>
  </tr>
  <tr>
    <td class="dataLabel">Default Copywriter cost per article for this campaign:</td>
    <td>$<input type="text" name="cp_article_cost" id="cp_article_cost" value="<?php echo $this->_tpl_vars['type_info']['cp_article_cost']; ?>
"  /></td>
  </tr>
  <tr>
    <td class="dataLabel">Default  Editor cost per article for this campaign:</td>
    <td>$<input type="text" name="editor_article_cost" id="editor_article_cost" value="<?php echo $this->_tpl_vars['type_info']['editor_article_cost']; ?>
"  /></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="button" name="save" class="button" value="Save" onclick="check_f_new_article_cost('save')"/></td>
  </tr>
</table>
</form>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>