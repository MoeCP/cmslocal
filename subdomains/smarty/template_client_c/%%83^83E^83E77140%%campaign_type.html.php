<?php /* Smarty version 2.6.11, created on 2014-09-19 09:19:49
         compiled from client_campaign/campaign_type.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/campaign_type.html', 64, false),)), $this); ?>
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
function check_f_campaign_type()
{
  var f = document.f_campaign_type;
  if (f.campaign_name.value.length == 0) {
    alert("Please specify the campaign name");
    f.campaign_name.focus();
    return false;
  }

  if (f.article_type.value.length == 0) {
    alert("Please specify the article type");
    f.artice_type.focus();
    return false;
  }

  if (f.source.value.length == 0) {
    alert("Please specify the domain");
    f.source.focus();
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
<br>
<form action="/client_campaign/campaign_type.php" method="post"  name="f_campaign_type" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_campaign_type()"<?php endif; ?>>
<input name="client_id" id="client_id" value="<?php echo $this->_tpl_vars['client_id']; ?>
" type="hidden" />
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><input name="campaign_name" type="text" id="campaign_name" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
  </tr>
  <tr>
    <td class="requiredInput">Article Type</td>
    <td><select name="article_type" id="article_type" ><option value="-1" >[default]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type']), $this);?>
</select></td>
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
  <tr>
    <td class="requiredInput">Domain</td>
    <td><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['domains'],'name' => 'source','id' => 'source','selected' => $this->_tpl_vars['client_campaign_info']['source']), $this);?>
&nbsp;<img src="/image/select.gif" alt="Add an Domain" title="Select" LANGUAGE=javascript onclick='return window.open("/client/key_quick_add.php?client_id="+ $("client_id").value ,"add_domain","width=600,height=450,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'><div id="domaindiv" ></div></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Next" class="button"></td>
  </tr>
</table>
</form>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>