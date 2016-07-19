<?php /* Smarty version 2.6.11, created on 2012-04-05 14:14:23
         compiled from client_campaign/kfieldmapping.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/kfieldmapping.html', 62, false),)), $this); ?>
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
  var opts = document.getElementsByName("fieldnames[]");
  var is_select = false;
  for (var i=0;i<opts.length; i++)
  {
      if (opts[i].value!=\'skip\')
      {
          is_select= true;
      }
  }

 if (is_select == false)
 {
    alert("You must choose mapping field at least one");
    return false;
 }
  
  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Import Keywords from file for <?php echo $this->_tpl_vars['campaign_name']; ?>
</h2>
  <div id="campaign-search" >
    <strong>Sepcifies for each article - keywords, focus topic, title, intended URL, example URLs, links/words to include, etc</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="/client_campaign/kfieldmapping.php?file_id=<?php echo $this->_tpl_vars['file_id']; ?>
" method="post"  name="f_client_campaign" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_client_campaign()"<?php endif; ?>>
  <input type="hidden" name="file_id" value="<?php echo $this->_tpl_vars['file_id']; ?>
">
  <tr>
    <td class="bodyBold"><h3>Step 2: Field Mapping</h3></td>
  </tr>
  <tr>
    <td class="bodyBold" colspan="2" >Match the keyword, optional fields and Mapping-ID with labels below</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
  <tr>
    <td class="dataLabel"><?php echo $this->_tpl_vars['value']; ?>
</td>
    <td>
      <input type="hidden" name="fieldlabels[]" value="<?php echo $this->_tpl_vars['value']; ?>
">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gfields'],'name' => "fieldnames[]",'selected' => $this->_tpl_vars['info'][$this->_tpl_vars['key']]), $this);?>

    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <input type="button" value="Back" onclick="window.location.href='/client_campaign/uploadkeywordfile.php?file_id=<?php echo $this->_tpl_vars['file_id']; ?>
'" class="button" />&nbsp;<input type="submit" value="Next" class="button" />&nbsp; 
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