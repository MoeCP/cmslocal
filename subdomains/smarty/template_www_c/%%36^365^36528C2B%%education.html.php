<?php /* Smarty version 2.6.11, created on 2014-10-06 09:37:24
         compiled from education.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'education.html', 35, false),)), $this); ?>
<script language="JavaScript">
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
<?php endif; ?>
//-->
</script>
<div class="title-top-box" id="navEducation" >Education</div>
<div class="form-item" id="divEducation" >
  <div class="title-desc-box"> <strong>Please provide us with your educational background below. Simply select the types of degree completed and provide us with the school/program and the area of study. You can include additional degrees by selecting the add button.</strong></div>
<form action="" method="post"  name="f_candidate_edu" >
<input type="hidden" name="candidate_id" value="<?php echo $this->_tpl_vars['cid']; ?>
"/>
<input type="hidden" name="opt_index" value="1" />
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
    <td>
    <table>
  <tr>
    <td><input type="button"  class="button" value="+" onclick="addRow('educationrow', <?php echo $this->_tpl_vars['totaleducation']; ?>
, 'tr')" /></td>
    <td align="left" >Degree</td>
    <td align="left" >School</td>
    <td align="left" >Marjor/Minor/Certificate</td>
  </tr>
  <?php if ($this->_tpl_vars['info']['education']): ?>
  <?php $_from = $this->_tpl_vars['info']['education']['degree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr <?php if ($this->_tpl_vars['key'] == 0): ?>id="educationrow"<?php endif; ?>>
    <td><?php if ($this->_tpl_vars['key'] == 0): ?><span></span><?php else: ?><input type="button"  class="button" value="-" onclick="jQuery(this).parent().parent().remove();currentrow--" /><?php endif; ?></td>
    <td><select name="education[degree][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['education'],'selected' => $this->_tpl_vars['item']), $this);?>
</select></td>
    <td><input type="text" name="education[school][]" value="<?php echo $this->_tpl_vars['info']['education']['school'][$this->_tpl_vars['key']]; ?>
" size="60" /></td>
    <td><input type="text" name="education[major][]" value="<?php echo $this->_tpl_vars['info']['education']['major'][$this->_tpl_vars['key']]; ?>
" size="60" /></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
  <tr id="educationrow" >
    <td><span></span></td>
    <td><select name="education[degree][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['education']), $this);?>
</select></td>
    <td><input type="text" name="education[school][]" value="" size="60" /></td>
    <td><input type="text" name="education[major][]" value="" size="60" /></td>
  </tr>
  <?php endif; ?>
    </table>
    </td>
  </tr>
  <tr>
    <td class="blackLine" colspan="5"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td colspan="5" >
      &nbsp;Having trouble submitting the form? Send email to hr@copypress.com&nbsp;
      <input type="button" value="Next" class="button" onclick="check_f_candidate_edu()" />
    </td>
  </tr>
</table>
</form>
</div>