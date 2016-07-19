<?php /* Smarty version 2.6.11, created on 2012-04-10 10:35:00
         compiled from suggestions/report_bugs.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'suggestions/report_bugs.html', 25, false),)), $this); ?>
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
<center><div style="color:red"><?php echo $this->_tpl_vars['feedback']; ?>
</div></center>
<div id="page-box1">
  <h2>Technical Support</h2>
  <div id="campaign-search" >
   <?php if ($this->_tpl_vars['user_permission_int'] > 0): ?>Use this form to report any technical issues you encounter while operating in the CopyPress system. Please note the  campaign and article number if you are having trouble with a particular article. Please clear your cache and restart your browser or computer and try again before submitting this form. We recommend using the latest version of Chrome or Firefox while using CopyPress.<?php else: ?>Use this form to report any technical issues you encounter while using CopyPress system.<?php endif; ?>
  </div><br />
  <div class="form-item" >
<form action="" method="post" id="f_bug" onsubmit="return check_f()">
<input type="hidden" name="campaign_name" id="campaign_name" value="<?php echo $this->_tpl_vars['info']['campaign_name']; ?>
" >
<table width="90%">
<tr>
<td width="15%" align="right" class="requiredInput">Problem Title</td><td width="85%"><input style="width:460px;" type="text" value="<?php echo $this->_tpl_vars['info']['subject']; ?>
" name="subject" id="subject" /></td>
</tr>
<tr>
  <td align="right" class="dataLabel">Campaign</td>
  <td>
    <select id="campaign_id" name="campaign_id"  onchange="setCampaignName(this)" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'],'selected' => $this->_tpl_vars['info']['campaign_id']), $this);?>
</select>
  </td>
</tr>
<tr>
  <td align="right" class="dataLabel">Article Number</td>
  <td><input style="width:460px;" type="text" value="<?php echo $this->_tpl_vars['info']['article_number']; ?>
" name="article_number" id="article_number" /></td>
</tr>
<tr>
<td width="15%" align="right" class="requiredInput">What happened</td><td width="85%"><textarea name="happened" id="happened" cols="70" rows="8"><?php echo $this->_tpl_vars['info']['happened']; ?>
</textarea></td>
</tr>
<tr>
<td width="15%" align="right" class="requiredInput">What should have happened</td><td width="85%"><textarea name="raw_happened" id="raw_happened" cols="70" rows="8"><?php echo $this->_tpl_vars['info']['raw_happened']; ?>
</textarea></td>
</tr>
<tr>
<td align="right" class="requiredInput">Steps to Reproduce</td><td><textarea name="steps" id="steps" cols="70" rows="8"><?php echo $this->_tpl_vars['info']['steps']; ?>
</textarea></td>
</tr>
<tr>
  <td align="right" class="requiredInput">Browser information</td>
  <td>
    Which brower were you using? <br />
    <select id="browser" name="browser" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['browsers'],'selected' => $this->_tpl_vars['info']['browser']), $this);?>
</select>
  </td>
</tr>
<tr>
  <td align="right" class="requiredInput" >Operating System</td>
  <td>
    Which OS were you using<br />
    <select id="operating_system" name="operating_system" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['os'],'selected' => $this->_tpl_vars['info']['operating_system']), $this);?>
</select>
  </td>
</tr>
<tr>
<td></td><td><input type="submit" class="button" value="Submit"></td>
</tr>
</table>
</form>
  </div>
</div>
<script>
<?php echo '
function setCampaignName(obj)
{
    $(\'campaign_name\').value = obj.options[obj.selectedIndex].text;
}
function check_f()
{
    var f = document.f_bug;
    if (f.subject.value.length == 0) {
        alert(\'Please input problem title\');
        f.subject.focus();
        return false;
    }
    if (f.happened.value.length == 0) {
        alert(\'Please decribe what happened\');
        f.happened.focus();
        return false;
    }
    if (f.raw_happened.value.length == 0) {
        alert(\'Please decribe what should have happened\');
        f.fact_happened.focus();
        return false;
    }
    if (f.steps.value.length == 0) {
        alert(\'Please decribe how can we reproduce the problem\');
        f.steps.focus();
        return false;
    }
    if (f.browser.value.length == 0) {
        alert(\'Please specify your browser\');
        f.browser.focus();
        return false;
    }
    if (f.operating_system.value.length == 0) {
        alert(\'Please specify your operating system\');
        f.operating_system.focus();
        return false;
    }
    return true;
}
'; ?>

</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>