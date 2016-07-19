<?php /* Smarty version 2.6.11, created on 2013-01-15 09:22:51
         compiled from client/keyword_fields.html */ ?>
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
function check_f_fields()
{
   var checkElements = document.getElementsByName(\'is_checked[]\');  
   
   var total  = checkElements.length;
   var checkValue = \'\';
   for (var i=0; i< total; i++)
   {
        if (checkElements[i].checked) {
          checkValue = checkElements[i].value;
          var label = document.getElementsByName(\'clabel[\' + checkValue+\']\')[0];
          if (label.value == \'\' ) {
              alert(\'Please specify the label for \'+ checkValue);
              label.focus();
              return false;
          }
        }
   }
   return true;
}
function checkAllCustomField()
{
    var e = $(\'all_is_required\');
    for (i=1;i<=5 ;i++ ) {
      document.getElementsByName("is_required[custom_field"+ i +"]")[0].checked = e.checked;
    }
}

//-->
</script>
'; ?>


<div id="page-box1">
  <h2><?php echo $this->_tpl_vars['clientName']; ?>
's Keyword Custom Fields Settings</h2>
  <div class="form-item" >
<br>
<form action="" method="post"  name="f_client" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_fields()"<?php endif; ?>>
<input type="hidden" name="ctable" value="<?php echo $this->_tpl_vars['table']; ?>
" />
<input type="hidden" name="client_id" value="<?php echo $this->_tpl_vars['client_id']; ?>
">
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
<thead>
<tr class="sortableTab">
  <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  <td nowrap class="columnHeadInactiveBlack table-left-2"><input type="checkbox" onclick="javascript:checkAll('is_checked[]')" title="Select All" name="Select_All" /></td>
  <td nowrap class="columnHeadInactiveBlack">Field</td>
  <td nowrap class="columnHeadInactiveBlack">Label</td>
  <td nowrap class="columnHeadInactiveBlack table-right-2">Description</td>
   <td nowrap class="columnHeadInactiveBlack table-right-2">Required?</td>
  <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
</tr>
</thead>
<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<input type="hidden" name="field_id[<?php echo $this->_tpl_vars['item']; ?>
]" value="<?php echo $this->_tpl_vars['result'][$this->_tpl_vars['item']]['field_id']; ?>
" />
<input type="hidden" name="edit_role[<?php echo $this->_tpl_vars['item']; ?>
]" value="<?php echo $this->_tpl_vars['result'][$this->_tpl_vars['item']]['edit_role']; ?>
" />
<tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td class="table-left" >&nbsp;</td>
  <td class="table-left-2"><input type="checkbox" name="is_checked[]" value="<?php echo $this->_tpl_vars['item']; ?>
" <?php if ($this->_tpl_vars['result'][$this->_tpl_vars['item']]['field_id'] && $this->_tpl_vars['result'][$this->_tpl_vars['item']]['status']): ?>checked<?php endif; ?> /></td>
  <td><?php echo $this->_tpl_vars['item']; ?>
</td>
  <td><input type="text" name="clabel[<?php echo $this->_tpl_vars['item']; ?>
]" value="<?php echo $this->_tpl_vars['result'][$this->_tpl_vars['item']]['clabel']; ?>
" /></td>
  <td  class="table-right-2"><input type="text" name="description[<?php echo $this->_tpl_vars['item']; ?>
]" value="<?php echo $this->_tpl_vars['result'][$this->_tpl_vars['item']]['description']; ?>
" /></td>
    <td align="right" nowrap class="table-right-2"><?php if ($this->_tpl_vars['result'][$this->_tpl_vars['item']]['is_show_required']): ?><input type="checkbox" name="is_required[<?php echo $this->_tpl_vars['item']; ?>
]" value="<?php echo $this->_tpl_vars['result'][$this->_tpl_vars['item']]['is_required']; ?>
" <?php if ($this->_tpl_vars['result'][$this->_tpl_vars['item']]['is_required']): ?>checked<?php endif; ?>/>Yes<?php endif; ?></td>
  <td class="table-right" >&nbsp;</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr><td class="table-left" >&nbsp;</td><td class="table-left-2 table-right-2" colspan="5" ><input type="checkbox" name="all_is_required" id="all_is_required" value="1"  onclick="checkAllCustomField()"  checked />Are custom fields required?</td><td class="table-right" >&nbsp;</td></tr>
</table>
<table width="100%" >
<tr>
<td align="center" ><input type="submit" value="Submit" class="button" /></td>
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