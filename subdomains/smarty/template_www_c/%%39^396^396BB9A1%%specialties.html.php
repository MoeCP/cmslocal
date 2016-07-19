<?php /* Smarty version 2.6.11, created on 2012-01-09 22:25:45
         compiled from specialties.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'specialties.html', 16, false),)), $this); ?>
<div class="tab_content" style="display: block;" id="specialties">
<h2>Specialties<br /> </h2>
  <div>If you have writing or work experience in any of the following, please select the <strong>FIVE</strong> categories in which you are the most experienced.You must choose a sub-category for each category selected.</div>
<br />
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">  
  <tr>
    <td><input type="button"  class="button" value="+" onclick="addRow('specialtyrow', 5, 'tr')" /></td>
    <td align="left" >Category</td>
    <td align="left" >Sub-Category</td>
    <td align="left" >Level</td>
  </tr>
  <?php if ($this->_tpl_vars['info']['categories'] && $this->_tpl_vars['info']['categories']['parent_id']): ?>
  <?php $_from = $this->_tpl_vars['info']['categories']['parent_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr <?php if ($this->_tpl_vars['key'] == 0): ?>id="specialtyrow"<?php endif; ?>>
    <td><?php if ($this->_tpl_vars['key'] == 0): ?><span></span><?php else: ?><input type="button"  class="button" value="-" onclick="jQuery(this).parent().parent().remove();" /><?php endif; ?></td>
    <td><select name="categories[parent_id][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scategories'],'selected' => $this->_tpl_vars['item']), $this);?>
</select></td>
    <td><select name="categories[category_id][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['subcategeries'][$this->_tpl_vars['item']],'selected' => $this->_tpl_vars['info']['categories']['category_id'][$this->_tpl_vars['key']]), $this);?>
</select></td>
    <td><select name="categories[level][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_levels'],'selected' => $this->_tpl_vars['info']['categories']['level'][$this->_tpl_vars['key']]), $this);?>
</select><input type="hidden" name="categories[category][]" value="<?php echo $this->_tpl_vars['info']['categories']['category'][$this->_tpl_vars['key']]; ?>
" /></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
  <tr id="specialtyrow">
    <td><span></span></td>
    <td><select name="categories[parent_id][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scategories']), $this);?>
</select></td>
    <td><select name="categories[category_id][]"><option value="0" >Select</option></select></td>
    <td><select name="categories[level][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_levels']), $this);?>
</select><input type="hidden" name="categories[category][]" value="" /></td>
  </tr>
  <?php endif; ?>
</table>
<br />
<br />
<br />
<br />
<div>Please provide <strong>THREE</strong> writing samples (blog posts, print articles, SEO copywriting, etc.) that best display your writing style and/or expertise in a certain field. Samples may be provided as a URL Link, PDF or Word Document. </div>
<div><span class="spanlabel" >*</span>Writing Samples </div>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" >
  <tr>
    <td><input type="button"  class="button" value="+" onclick="addRow('samplerow', 3, 'tr', 'Writing Samples')" /></td>
    <td>Add URLs</td>
    <td></td>
    <td>Upload a PDF or a Word Document</td>
    <td>Link to your online Portfolio</td>
    <td>Link to your online Blog</td>
  </tr>
  <tr id="samplerow">
    <td><span></span></td>
    <td><input type="text" name="samples[link][]" /></td>
    <td>Or</td>
    <td><input type="file" name="samples[fileField][]" /></td>
    <td><input type="text" name="samples[portfolio][]" /></td>
    <td><input type="text" name="samples[blog][]" /></td>
  </tr>
</table>
<div>&nbsp;Having trouble submitting the form? Send email to hr@copypress.com &nbsp;&nbsp;<input type="button" value="Next" class="button" onclick="submitTabTwo()" /></div>
</div>