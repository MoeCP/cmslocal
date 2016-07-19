<?php /* Smarty version 2.6.11, created on 2014-10-06 09:50:06
         compiled from writing_experience.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eval', 'writing_experience.html', 8, false),array('function', 'html_options', 'writing_experience.html', 72, false),)), $this); ?>
<div class="tab_content" style="display: block;" id="writing_experience">
  <h2>Writing Experience<br /> </h2>
  <div>1. Please provide a link to your personal website, portfolio, and/or blog. </div>
    <br />
  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">  
    <?php $_from = $this->_tpl_vars['plinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['foo']['iteration']++;
?>
    <?php if (($this->_foreach['foo']['iteration']-1)):  echo smarty_function_eval(array('var' => ($this->_foreach['foo']['iteration']-1),'assign' => 'pIndex'), $this); else:  echo smarty_function_eval(array('var' => '0','assign' => 'pIndex'), $this); endif; ?>
    <tr>
      <td><?php echo $this->_tpl_vars['index'];  echo $this->_tpl_vars['item']; ?>
<input type="hidden" name="plinks[type][]" value="<?php echo $this->_tpl_vars['key']; ?>
" /></td>
      <td><input type="text" name="plinks[value][]" value="<?php if ($this->_tpl_vars['info']['plinks']['value'][$this->_tpl_vars['pIndex']]):  echo $this->_tpl_vars['info']['plinks']['value'][$this->_tpl_vars['pIndex']];  else: ?>http://www.<?php endif; ?>" size="60"/></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>
  <br />
  <br />
    <div>2. Please list up to three specialities. Only list a specialty if you have a writing sample or relevant experience.</div>
  <div><span class="spanlabel" >*</span>You must choose at least one. </div>
  <br />

  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
       <tr class="titleStyle">
        <td>Categories</td>
        <td>	Sub-Category</td>
        <td colspan="2">Experience/Samples</td>
              </tr>
      <?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)0;
$this->_sections['foo']['loop'] = is_array($_loop=3) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
      <?php if ($this->_sections['foo']['index']):  echo smarty_function_eval(array('var' => $this->_sections['foo']['index'],'assign' => 'index'), $this); else:  echo smarty_function_eval(array('var' => '0','assign' => 'index'), $this); endif; ?>
      <?php if ($this->_tpl_vars['info']['categories']['parent_id'][$this->_tpl_vars['index']]):  echo smarty_function_eval(array('var' => $this->_tpl_vars['info']['categories']['parent_id'][$this->_tpl_vars['index']],'assign' => 'parent_id'), $this); else:  echo smarty_function_eval(array('var' => '0','assign' => 'parent_id'), $this); endif; ?>
      <tr>
        <td><select name="categories[parent_id][]" class="selectWidth" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scategories'],'selected' => $this->_tpl_vars['parent_id']), $this);?>
</select></td>
        <td><select name="categories[category_id][]" class="selectWidth"><?php if ($this->_tpl_vars['subcategeries'][$this->_tpl_vars['parent_id']]):  echo smarty_function_html_options(array('options' => $this->_tpl_vars['subcategeries'][$this->_tpl_vars['parent_id']],'selected' => $this->_tpl_vars['info']['categories']['category_id'][$this->_tpl_vars['index']]), $this); else: ?><option value="0" label="Select">Select</option><?php endif; ?></select></td>
        <td><select name="categories[experience_type][]" id="categories_experience_type_<?php echo $this->_tpl_vars['index']; ?>
" class="selectWidth" onchange="experience_type_Change(jQuery(this).val(), '<?php echo $this->_tpl_vars['index']; ?>
')"><option value="">Choose One</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['experience_types'],'selected' => $this->_tpl_vars['info']['categories']['experience_type'][$this->_tpl_vars['index']]), $this);?>
</select></td>
        <td nowrap valign="top">
          <div id="categories_link_<?php echo $this->_tpl_vars['index']; ?>
" style="display:none"><input type="text" name="categories[link][]" value="<?php if ($this->_tpl_vars['info']['categories']['link'][$this->_tpl_vars['index']]):  echo $this->_tpl_vars['info']['categories']['link'][$this->_tpl_vars['index']];  else: ?>http://www.<?php endif; ?>" /></div>
          <div id="categories_filename_<?php echo $this->_tpl_vars['index']; ?>
"  style="display:none"><input type="file" name="categories[fileField][]"  /><input type="hidden" name="categories[filename][]" value="<?php echo $this->_tpl_vars['info']['categories']['fileField']['filename'][$this->_tpl_vars['index']]; ?>
" /></div>
          <div id="categories_level_<?php echo $this->_tpl_vars['index']; ?>
" style="display:none;"><table border="0" cellspacing="0" cellpadding="0" ><tr><td valign="middle"><select name="categories[level][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['candidate_levels'],'selected' => $this->_tpl_vars['info']['categories']['level'][$this->_tpl_vars['index']]), $this);?>
</select><input type="hidden" name="categories[category][]" value="<?php echo $this->_tpl_vars['info']['categories']['category'][$this->_tpl_vars['index']]; ?>
" /></td><td><textarea name="categories[description][]"  cols="40" rows="5"><?php echo $this->_tpl_vars['info']['categories']['description'][$this->_tpl_vars['index']]; ?>
</textarea></td></tr></table></div>
        </td>
              </tr>
      <?php endfor; endif; ?>
  </table>
  <br />
  <br />
  <div>3. Additional comments</div>
  <br />
  <div><textarea rows="5" cols="50" id="comments" name="comments"></textarea></div>
  <div><input type="button" value="Next" class="button" onclick="submitTabTwo()" />&nbsp;<small>Having trouble submitting the form? Send email to Community@copypress.com </small>&nbsp;&nbsp;</div>
</div>