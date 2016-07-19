<?php /* Smarty version 2.6.11, created on 2013-01-18 09:42:01
         compiled from client_campaign/campaign_questions.html */ ?>
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
<form action="" method="post"  name="f_campaign_type" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_campaign_type()"<?php endif; ?>>
<input name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['client_campaign_info']['campaign_id']; ?>
" type="hidden" />
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Domain Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Domain</td>
    <td><input name="source" type="hidden" id="source" value="<?php echo $this->_tpl_vars['client_campaign_info']['source']; ?>
" /><?php echo $this->_tpl_vars['domains'][$this->_tpl_vars['client_campaign_info']['source']]; ?>
</td>
  </tr>
  <?php $_from = $this->_tpl_vars['q_titles']['source']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr>
    <td class="dataLabel"><?php echo $this->_tpl_vars['item']; ?>
</td>
    <td><textarea name="questions[source][<?php echo $this->_tpl_vars['key']; ?>
]" cols="40" rows="5" id="questions_source_<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['questions']['source'][$this->_tpl_vars['key']]; ?>
</textarea></td>
  </tr>
 <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="bodyBold">Article Type Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Article Type</td>
    <td><input name="article_type" type="hidden" id="article_type" value="<?php echo $this->_tpl_vars['client_campaign_info']['article_type']; ?>
" /><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['client_campaign_info']['article_type']]; ?>
</td>
  </tr>
  <?php $_from = $this->_tpl_vars['questions']['article_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr>
    <td class="dataLabel"><?php echo $this->_tpl_vars['item']['q']; ?>
</td>
    <td><textarea name="questions[article_type][<?php echo $this->_tpl_vars['key']; ?>
][v]" cols="40" rows="5" id="questions_article_type_<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']['v']; ?>
</textarea><input type="hidden" name="questions[article_type][<?php echo $this->_tpl_vars['key']; ?>
][q]" value="<?php echo $this->_tpl_vars['item']['q']; ?>
"/></td>
  </tr>
 <?php endforeach; endif; unset($_from); ?>
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