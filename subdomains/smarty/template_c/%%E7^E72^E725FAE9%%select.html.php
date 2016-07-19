<?php /* Smarty version 2.6.11, created on 2013-01-18 10:01:48
         compiled from category/select.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'category/select.html', 42, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
<script>jQuery.noConflict();</script>
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<center><div style="color:red;"><?php echo $this->_tpl_vars['feedback']; ?>
</div></center>
<?php endif; ?>
<div id="page-box1">
  <h2><?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>Copywriter<?php elseif ($this->_tpl_vars['role'] == 'designer'): ?>Designer<?php else: ?>Editor<?php endif; ?> Specialties</h2>
  <div id="campaign-search" >
    <strong>We’ll try to match you up with assignments that fit your areas of expertise.  While we can’t guarantee that every assignment will be in line with your specialty areas, we’ll try our best.  Please choose your specialty areas from the categories below. Simply check the box next to the category and click Update once you’re finished.</strong>
  </div>
  <div class="form-item" >
<?php if ($this->_tpl_vars['user_id'] != 0 && $this->_tpl_vars['user_id'] != ''): ?>
<form action="" method="post" name="f_specialties" id="f_specialties" >
<table border="0" cellspacing="0" cellpadding="1" align="center" style="font-size:10px" >
 <tr>
    <td class="bodyBold" nowrap >Select Category</td>
    <td align="right" class="requiredHint" colspan="4" >Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="5"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <th class="columnHeadInactiveBlack">Category</th>
    <th class="columnHeadInactiveBlack">Level</th>
    <th class="columnHeadInactiveBlack">Description</th>
    <th class="columnHeadInactiveBlack">Sample</th>
 </tr>
  <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr >
    <td align="left" valign="top" >
      <input type="checkbox" name="parent_id[]" id="parent_id<?php echo $this->_tpl_vars['item']['category_id']; ?>
" <?php if ($this->_tpl_vars['item']['user_id'] != 0): ?>checked="true"<?php endif; ?> value="<?php echo $this->_tpl_vars['item']['category_id']; ?>
" onclick="parentCheck(jQuery(this))"><strong><span id="catelabel<?php echo $this->_tpl_vars['item']['category_id']; ?>
" ><?php echo $this->_tpl_vars['item']['category']; ?>
</span></strong><input type="hidden" name="category[<?php echo $this->_tpl_vars['item']['category_id']; ?>
]" value="<?php echo $this->_tpl_vars['item']['category']; ?>
" />
    </td>
    <td></td>
    <td rowspan="<?php echo $this->_tpl_vars['item']['total_row']; ?>
" valign="top"><textarea name="descs[<?php echo $this->_tpl_vars['item']['category_id']; ?>
]" id="desc<?php echo $this->_tpl_vars['item']['category_id']; ?>
" cols="30" rows="<?php echo $this->_tpl_vars['item']['area_row']; ?>
"  ><?php echo $this->_tpl_vars['item']['description']; ?>
</textarea></td>
    <td rowspan="<?php echo $this->_tpl_vars['item']['total_row']; ?>
" valign="top"><textarea name="sample[<?php echo $this->_tpl_vars['item']['category_id']; ?>
]" id="sample<?php echo $this->_tpl_vars['item']['category_id']; ?>
" cols="30" rows="<?php echo $this->_tpl_vars['item']['area_row']; ?>
"  ><?php echo $this->_tpl_vars['item']['sample']; ?>
</textarea></td>
   </tr>
   <?php if ($this->_tpl_vars['item']['children']): ?>
    <?php $_from = $this->_tpl_vars['item']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item2']):
        $this->_foreach['loop2']['iteration']++;
?>
    <tr>
      <td nowrap height="20" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="category_id[<?php echo $this->_tpl_vars['item']['category_id']; ?>
][]" id="category_id<?php echo $this->_tpl_vars['item2']['category_id']; ?>
" <?php if ($this->_tpl_vars['item2']['user_id'] != 0): ?>checked="true"<?php endif; ?> value="<?php echo $this->_tpl_vars['item2']['category_id']; ?>
" onclick="jQuery('#parent_id<?php echo $this->_tpl_vars['item']['category_id']; ?>
').attr('checked', true);"><strong><span id="catelabel<?php echo $this->_tpl_vars['item2']['category_id']; ?>
" ><?php echo $this->_tpl_vars['item2']['category']; ?>
</span></strong><input type="hidden" name="category[<?php echo $this->_tpl_vars['item2']['category_id']; ?>
]" value="<?php echo $this->_tpl_vars['item2']['category']; ?>
" /></td>
      <td colspan nowrap><select name="level[<?php echo $this->_tpl_vars['item']['category_id']; ?>
][<?php echo $this->_tpl_vars['item2']['category_id']; ?>
]" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_levels'],'selected' => $this->_tpl_vars['item2']['level']), $this);?>
</select></td>
      <td></td>
    </tr>     
    <?php endforeach; endif; unset($_from); ?>
    <?php else: ?>
    <tr>
      <td nowrap height="20" ></td>
      <td colspan nowrap><select name="level[<?php echo $this->_tpl_vars['item']['category_id']; ?>
]" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_levels'],'selected' => $this->_tpl_vars['item']['level']), $this);?>
</select></td>
      <td></td>
    </tr>    
    <?php endif; ?>
    <tr><td colspan="5" class="blackLine"><img src="/image/misc/s.gif">&nbsp;<br /><br /></td></tr>
  <?php endforeach; endif; unset($_from); ?>
   <tr>
    <td class="blackLine" colspan="5"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
  	<td align="center" colspan="3"><input type="button" class="button" value="Update" onclick="f_specialties_check()"></td>
  </tr>
</table>
</form>
<?php endif; ?>
  </div>
</div>
<script type="text/javascript" >
<?php echo '
function parentCheck(obj)
{
    if (!obj.attr("checked")) {
      if (!confirm(\'It will remove all sub categories for this category. Are you remove it?\')) {
          obj.attr("checked", true);return false;
      } else {
          jQuery(\'input[name="category_id[\' + obj.val() + \'][]"]\').attr(\'checked\', false);
          return true;
      }
    }
}


function f_specialties_check()
{
  var form = document.f_specialties;
  var cates = document.getElementsByName(\'parent_id[]\');
  var len = cates.length;
  var  is_checkedp= false;
  for (var i = 0; i < len;  i++)
  {
      if (jQuery(cates[i]).attr(\'checked\')) {
          var pid = jQuery(cates[i]).val();
          var desc   = jQuery(\'textarea[name="descs[\' +  pid +\']"]\');
          var sample = jQuery(\'textarea[name="sample[\' +  pid +\']"]\');
          is_checkedp = true;
          var pname = jQuery(\'#catelabel\' +pid).text();
          if (desc.val() == \'\') {
            alert(\'Please specify description for \' + pname);
            desc.focus();
            return false;
          }
          if (sample.val() == \'\') {
            alert(\'Please specify sample for \' + pname);
            sample.focus();
            return false;
          }
          var subcates = document.getElementsByName(\'category_id[\' + pid+ \'][]\');
          var is_checked = false;
          var total = subcates.length;
          if (total == 0) {
              level = jQuery(\'select[name="level[\' + pid+ \']"]\');
              if (level.val() == \'\') {
                    alert(\'Please sepcify level for \' + pname );
                    level.focus();
                    return false;
              }
              is_checked = true;
          } else {
            
            for (var j=0; j < total; j++) {
              var subcate = jQuery(subcates[j]);
              var cid = subcate.val();
              if (subcate.attr(\'checked\')) {
                  is_checked = true;
                  var level =  jQuery(\'select[name="level[\' + pid+ \'][\'+cid+\']"]\');
                  if (level.val() == \'\') {
                    var name = jQuery(\'#catelabel\' +cid).text();
                    alert(\'Please sepcify level for \' + name );
                    level.focus();
                    return false;
                  }
              }
            }
          }
          if (!is_checked) {
              alert(\'Please choose one sub category for \' + pname );
              jQuery(subcates[0]).focus();
              return false;
          }
      }
  }
  if (!is_checkedp) {
    alert(\'You must choose at least one\');
    return false;
  }
  jQuery(form).submit();
  return true;
}
'; ?>

</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>