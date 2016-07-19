<?php /* Smarty version 2.6.11, created on 2012-08-08 15:51:56
         compiled from client_campaign/keyword_set.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/keyword_set.html', 118, false),array('function', 'html_options', 'client_campaign/keyword_set.html', 123, false),)), $this); ?>
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
function check_f_keyword()
{
  var f = document.f_keyword;

  if (f.keyword.value.length == 0) {
    alert(\'Please provides keyword\');
    f.keyword.focus();
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
  if (f.article_type.value.length == 0) {
    alert(\'Please provides article type\');
    f.article_type.focus();
    return false;
  }

  return true;
}
tinyMCEInit(\'keyword_description\');
//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Campaign Keyword Settings</h2>
  <div id="campaign-search" >
    <strong>Please enter the Client's campaign keyword information according to requirement.</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_keyword" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_keyword()"<?php endif; ?>>
  <input type="hidden" name="keyword_id" value="<?php echo $this->_tpl_vars['keyword_info']['keyword_id']; ?>
">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="dataLabel">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['campaign_name']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Client Name</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['user_name']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Company Name</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['company_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Keywords</td>
    <td><input name="keyword" value="<?php echo $this->_tpl_vars['keyword_info']['keyword']; ?>
" id="keyword" size="80" /></td>
  </tr>
  <tr>
    <td class="dataLabel">Mapping-ID</td>
    <td><input name="mapping_id" value="<?php echo $this->_tpl_vars['keyword_info']['mapping_id']; ?>
" id="mapping_id" size="80" /></td>
  </tr>
  <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr>
    <td class="dataLabel"><?php echo $this->_tpl_vars['item']['label']; ?>
</td>
    <td><input name="<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['keyword_info'][$this->_tpl_vars['key']]; ?>
" id="<?php echo $this->_tpl_vars['key']; ?>
" size="80" /></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td><input type="text" name="date_start" id="date_start" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['keyword_info']['date_start']; ?>
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
    <td><input type="text" name="date_end" id="date_end" size="10" maxlength="10" value="<?php echo $this->_tpl_vars['keyword_info']['date_end']; ?>
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
  <tr><td class="dataLabel" >SCID</td><td><input name="subcid" id="subcid" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['keyword_info']['subcid'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" size="80"/></td></tr>
  <tr>
	<td class="requiredInput">Article Type</td>
	<td>
   <?php if ($this->_tpl_vars['keyword_info']['article_type'] == '' || $this->_tpl_vars['keyword_info']['article_type'] == -1 || $this->_tpl_vars['keyword_info']['copy_writer_id'] == 0): ?>
  <select name="article_type"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $this->_tpl_vars['keyword_info']['article_type']), $this);?>
</select>
  <?php else: ?>
  <?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['keyword_info']['article_type']]; ?>

  <input name="article_type" type="hidden" value="<?php echo $this->_tpl_vars['keyword_info']['article_type']; ?>
" />
  <?php endif; ?>
  </td>
  </tr>
  <tr>
    <td class="dataLabel">Keyword Instructions</td>
    <td><textarea name="keyword_description" cols="60" rows="6" id="keyword_description"><?php echo $this->_tpl_vars['keyword_info']['keyword_description']; ?>
</textarea></td>
  </tr>
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