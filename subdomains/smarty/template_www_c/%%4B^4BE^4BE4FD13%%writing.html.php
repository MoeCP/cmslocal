<?php /* Smarty version 2.6.11, created on 2016-03-09 15:52:19
         compiled from writing.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'writing.html', 33, false),)), $this); ?>
<div class="title-top-box" >Writing Background</div>
<div class="form-item">
  <div class="title-desc-box">
  <strong>
  Please provide us with your writing background. Simply select the type of writing and provide us with the link, book, magazine, company, etc .where you completed this work.  You can include more than one writing experience by selecting the add button. <br />
  Please note: If you list online writing experience you MUST supply the link to the specific article under "source".  
  </strong>
  </div>
<form action="" method="post"  name="f_candidate_w" >
<input type="hidden" name="candidate_id" value="<?php echo $this->_tpl_vars['cid']; ?>
"/>
<input type="hidden" name="opt_index" value="3" />
<br>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">  
  <tr>
    <td class="bodyBold" colspan="2">Basic Information</td>
    <td align="right"  colspan="3" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="5"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td colspan="5">
    <table>
  <tr>
    <td><input type="button"  class="button" value="+" onclick="addRow('writingrow', <?php echo $this->_tpl_vars['totalwriting_background']; ?>
, 'tr')" /></td>
    <td align="left" >Writing Type</td>
    <td align="left" >Source</td>
  </tr>
  <?php if ($this->_tpl_vars['info']['writing_background']): ?>
  <?php $_from = $this->_tpl_vars['info']['writing_background']['type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr <?php if ($this->_tpl_vars['key'] == 0): ?>id="writingrow"<?php endif; ?>>
    <td><?php if ($this->_tpl_vars['key'] == 0): ?><span></span><?php else: ?><input type="button"  class="button" value="-" onclick="jQuery(this).parent().parent().remove();" /><?php endif; ?></td>
    <td><select name="writing_background[type][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['writing_background'],'selected' => $this->_tpl_vars['item']), $this);?>
</select></td>
    <td><input type="text" name="writing_background[source][]" value="<?php echo $this->_tpl_vars['info']['writing_background']['source'][$this->_tpl_vars['key']]; ?>
" size="100" /></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
  <tr id="writingrow" >
    <td><span></span></td>
    <td><select name="writing_background[type][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['writing_background']), $this);?>
</select></td>
    <td><input type="text" name="writing_background[source][]" value="" size="100" /></td>
  </tr>
  <?php endif; ?>
      </table>
    </td>
  </tr>
  <tr>
    <td class="blackLine" colspan="5"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td colspan="4" >&nbsp;Having trouble submitting the form? Send email to hr@copypress.com&nbsp;&nbsp;<input type="button" value="Next" class="button" onclick="check_f_candidate_w()" /></td>
  </tr>
</table>
</form>
</div>