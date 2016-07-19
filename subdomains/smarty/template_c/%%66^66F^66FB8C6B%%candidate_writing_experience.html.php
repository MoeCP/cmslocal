<?php /* Smarty version 2.6.11, created on 2012-10-05 06:10:42
         compiled from user/candidate_writing_experience.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eval', 'user/candidate_writing_experience.html', 123, false),array('function', 'html_options', 'user/candidate_writing_experience.html', 124, false),array('modifier', 'escape', 'user/candidate_writing_experience.html', 165, false),)), $this); ?>
<form action="" method="post"  name="f_candidate_s" id="f_candidate_s"  enctype="multipart/form-data" >
<input type="hidden" name="candidate_id" value="<?php echo $this->_tpl_vars['cid']; ?>
"/>
<input type="hidden" name="opt_index" value="1" />
<div class="tab_content" style="display: block;" id="writing_experience">
  <h2>Writing Experience<br /> </h2>
  <div>1. Please provide a link to your personal website, portfolio, and/or blog. </div>
    <br />
  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">  
    <?php $_from = $this->_tpl_vars['candidate_plinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['foo']['iteration']++;
?>
    <tr>
      <td><?php echo $this->_tpl_vars['item']; ?>
<input type="hidden" name="plinks[type][]" value="<?php echo $this->_tpl_vars['key']; ?>
" /></td>
      <td><input type="text" name="plinks[value][]" value="<?php if ($this->_tpl_vars['plinks'][$this->_tpl_vars['key']]['value']):  echo $this->_tpl_vars['plinks'][$this->_tpl_vars['key']]['value'];  else: ?>http://www.<?php endif; ?>" size="60"/></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>
  <?php if ($this->_tpl_vars['info']['plinks']): ?>
  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="sortableTable">
    <tr>
      <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <td class="columnHeadInactiveBlack table-left-2">Type</td>
      <td class="columnHeadInactiveBlack table-right-2">Url</td>
      <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
    </tr>
    <?php $_from = $this->_tpl_vars['info']['plinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <?php if ($this->_tpl_vars['item']['value']): ?>
    <tr>
      <td class="table-left" ></td>
      <td class="table-left-2" ><?php echo $this->_tpl_vars['candidate_plinks'][$this->_tpl_vars['item']['type']]; ?>
</td>
      <td class="table-right-2"><?php echo $this->_tpl_vars['item']['value']; ?>
</td>
      <td class="table-right" ></td>
    </tr>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </table>
  <?php endif; ?>
  <br />
  <br />
  <div>2. Please list up to three specialities. Only list a specialty if you have a writing sample or relevant experience.</div>
  <div><span class="spanlabel" >*</span>You must choose at least one. </div>
  <br />

  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
       <tr class="titleStyle">
        <td>Categories</td>
        <td>	Sub-Category</td>
        <td>	Add URL</td>
        <td class="title2Style" >(or)</td>
        <td>Upload a PDF/Word Document </td>
        <td class="title2Style" >(or)</td>
        <td>Relevant Experience</td>
        <td>Description</td>
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
      <tr>
      <?php if ($this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]): ?>
       <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['parent_id'],'assign' => 'parent_id'), $this);?>

        <td><select name="categories[parent_id][]" class="selectWidth" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scategories'],'selected' => $this->_tpl_vars['parent_id']), $this);?>
</select></td>
        <td><select name="categories[category_id][]" class="selectWidth"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['subcategeries'][$this->_tpl_vars['parent_id']],'selected' => $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['category_id']), $this);?>
</select></td>
        <td><input type="text" name="categories[link][]" size="15" value="<?php if ($this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['link']):  echo $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['link'];  else: ?>http://www.<?php endif; ?>" /></td>
        <td></td>
        <td><input type="file" name="categories[fileField][]"  /><input type="hidden" name="categories[filename][]" value="<?php echo $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['fileField']['filename']; ?>
" /></td>
        <td></td>
        <td><select name="categories[level][]"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['candidate_levels'],'selected' => $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['level']), $this);?>
</select><input type="hidden" name="categories[category][]" value="<?php echo $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['category']; ?>
" /></td>
        <td><textarea name="categories[description][]" class="selectWidth" ><?php echo $this->_tpl_vars['info']['categories'][$this->_sections['foo']['index']]['description']; ?>
</textarea></td>
      <?php else: ?>
        <td><select name="categories[parent_id][]" class="selectWidth" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scategories']), $this);?>
</select></td>
        <td><select name="categories[category_id][]" class="selectWidth"><option value="" >Select</option></select></td>
        <td><input type="text" name="categories[link][]" size="15" value="http://www." /></td>
        <td></td>
        <td><input type="file" name="categories[fileField][]"  /><input type="hidden" name="categories[filename][]" value="" /></td>
        <td></td>
        <td><select name="categories[level][]" class="selectWidth"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['candidate_levels']), $this);?>
</select><input type="hidden" name="categories[category][]" value="" /></td>
        <td><textarea name="categories[description][]" ></textarea></td>
      <?php endif; ?>
      </tr>
      <?php endfor; endif; ?>
  </table>
<?php if ($this->_tpl_vars['info']['categories']): ?>
  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="sortableTable">
    <tr>
      <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <td class="columnHeadInactiveBlack table-left-2">Category</td>
      <td class="columnHeadInactiveBlack">Url</td>
      <td class="columnHeadInactiveBlack">Relevant Experience</td>
      <td class="columnHeadInactiveBlack">Description</td>
      <td class="columnHeadInactiveBlack">Samples</td>
      <td class="columnHeadInactiveBlack table-right-2"></td>
      <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
    </tr>
  <?php $_from = $this->_tpl_vars['info']['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  <tr>
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_tpl_vars['item']['category']; ?>
</td>
    <td ><?php echo $this->_tpl_vars['item']['link']; ?>
</td>
    <td ><?php if ($this->_tpl_vars['item']['level']):  echo $this->_tpl_vars['candidate_levels'][$this->_tpl_vars['item']['level']];  endif; ?></td>
    <td ><?php if ($this->_tpl_vars['item']['level']):  echo $this->_tpl_vars['item']['description'];  endif; ?></td>
    <td ><?php echo $this->_tpl_vars['item']['fileField']['name']; ?>
</td>
    <td class="table-right-2"><?php if ($this->_tpl_vars['item']['fileField']): ?><input type="button" value="Donwload" class="button" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['info']['candidate_id']; ?>
&fd=candidate_categories&t=<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['fileField']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&f=<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['fileField']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"/> &nbsp;<input type="button" value="Delete File" class="button" onclick="delWritingSample(jQuery(this), '<?php echo $this->_tpl_vars['info']['candidate_id']; ?>
', '<?php echo $this->_tpl_vars['item']['fileField']['filename']; ?>
' , 'candidate_categories')" /><?php endif; ?></td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php if ($this->_tpl_vars['info']['is_categories_doc']): ?>
  <tr>
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2" colspan="5" ></td>
    <td class="table-right-2"><input type="button" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['info']['candidate_id']; ?>
&fd=candidate_categories', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" value="Download All" class="button"></td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endif; ?>
  </table>
<?php endif; ?>
  <br />
  <br />
  <div>3. Additional comments</div>
  <br />
  <div><textarea rows="5" cols="50" id="comments" name="comments"><?php echo $this->_tpl_vars['info']['comments']; ?>
</textarea></div>
  <div>&nbsp;Having trouble submitting the form? Send email to hr@copypress.com &nbsp;&nbsp;<input type="button" value="Submit" class="button" onclick="check_f_candidate_s()" /></div>
</div>
</form>