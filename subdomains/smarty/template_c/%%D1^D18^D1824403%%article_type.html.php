<?php /* Smarty version 2.6.11, created on 2013-06-19 13:03:15
         compiled from article/article_type.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/article_type.html', 74, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>
<script language="JavaScript">
var page_url = '<?php echo $this->_tpl_vars['page_url']; ?>
';
<?php echo '
function check_f_article_type( operation )
{
  $(\'operation\').value = operation;
  if ($(\'type_name\').value == \'\')
  {
    alert("Please input article type name");
    return false;
  }
  if ($(\'cp_cost\').value == \'\')
  {
    alert("Please input Copywriter Cost");
    return false;
  }
  if ($(\'editor_cost\').value == \'\')
  {
    alert("Please input Editor Cost");
    return false;
  }
	$(\'f_article_type\').submit();
}
function redirect(type_id)
{
  if (type_id.length > 0)
  {
    page_url += "?type_id=" + type_id;
  }
  window.location.href = page_url;
}

function changeParentId(parent_id, type_id)
{
  ajaxAction(\'/article/load_article_type.php?tid=\'+ parent_id+ \'&cid=\' + type_id, \'loaddivid\');
}
'; ?>

</script>
<div id="page-box1">
  <h2>Add/Edit Article Type</h2>
  <div id="campaign-search" >
    <strong>Please enter the Article Type Cost per word And Article Type Name.</strong>
  </div>
  <div id="loaddivid" ></div>
  <div class="form-item" >
<form action="" method="post" name="f_article_type" id="f_article_type" >
<table cellspacing="0" cellpadding="4" align="center" width="99%">
  <input type="hidden" name="operation" id="operation" value="" />
  <input type="hidden" name="type_id" id="type_id" value="<?php echo $this->_tpl_vars['info']['type_id']; ?>
" />
  <input type="hidden" name="parent_article_type" id="parent_article_type" value="<?php echo $this->_tpl_vars['parent_article_type']; ?>
" />
  <input type="hidden" name="query_string" id="query_string" value="<?php echo $this->_tpl_vars['query_string']; ?>
" />
  <tr>
	  <td class="bodyBold">Basic Information</td>
	  <td align="right" class="requiredHint" colspan="10" >Required Information</td>
  </tr>
  <tr>
	  <td class="blackLine" colspan="10"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['type_num'] > 0): ?>
  <tr>
	  <td class="requiredInput">
    </td>
	  <td align="left" >&nbsp;
    <select name="article_type" id="article_type" onchange="redirect(this.value)" >
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_types'],'selected' => $this->_tpl_vars['selected_type']), $this);?>

    </select>
    </td>
    <td>
    <input type="button" name="add" id="add" class="button"  value="Add Article Type" onclick="redirect('')"/>
    </td>
  </tr>
  <?php endif; ?>
    <tr>
    <td class="dataLabel">Parent Type</td>
    <td colspan="3" >    
    <select name="parent_id" id="parent_id" onchange="changeParentId(this.value, <?php if ($this->_tpl_vars['selected_type'] == '' || $this->_tpl_vars['selected_type'] < 0): ?>''<?php else:  echo $this->_tpl_vars['selected_type'];  endif; ?>)">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['roots'],'selected' => $this->_tpl_vars['info']['parent_id']), $this);?>

    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">QB LISTID</td>
    <td colspan="3" ><input type="text" name="qd_listid" id="qd_listid" value="<?php echo $this->_tpl_vars['info']['qd_listid']; ?>
" size="30" /></td>
  </tr>
  <tr>
    <td class="requiredInput">Type Name:</td>
    <td>&nbsp;&nbsp;<input type="text" name="type_name" id="type_name" value="<?php echo $this->_tpl_vars['info']['type_name']; ?>
" size="30" />
    </td>
    <td><input type="checkbox" name="pay_by_article" id="pay_by_article" value="1" <?php if ($this->_tpl_vars['info']['pay_by_article'] == 1): ?>checked<?php endif; ?> />Pay by Article</td>
  </tr>
  <tr>
    <td class="requiredInput">Copywriter Cost per Word:</td>
    <td>
      $<input type="text" name="cp_cost" id="cp_cost" value="<?php if ($this->_tpl_vars['info']['type_id'] != ''):  echo $this->_tpl_vars['info']['cp_cost'];  else: ?>0<?php endif; ?>"  />
    </td>
    <td class="dataLabel">Copywriter Cost per Article</td>
    <td>
      $<input type="text" name="cp_article_cost" id="cp_article_cost" value="<?php if ($this->_tpl_vars['info']['type_id'] != ''):  echo $this->_tpl_vars['info']['cp_article_cost'];  else: ?>0<?php endif; ?>"  />
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Editor Cost per word:</td>
    <td>
      $<input type="text" name="editor_cost" id="editor_cost" value="<?php if ($this->_tpl_vars['info']['type_id'] != ''):  echo $this->_tpl_vars['info']['editor_cost'];  else: ?>0<?php endif; ?>"  />
    </td>
    <td class="dataLabel">Editor Cost per Article</td>
    <td>
      $<input type="text" name="editor_article_cost" id="editor_article_cost" value="<?php if ($this->_tpl_vars['info']['type_id'] != ''):  echo $this->_tpl_vars['info']['editor_article_cost'];  else: ?>0<?php endif; ?>"  />
    </td>
  </tr>
  <tr>
    <td class="requiredInput"></td>
    <td><input type="checkbox" name="is_hidden" id="is_hidden" value="1" <?php if ($this->_tpl_vars['info']['is_hidden'] == 1): ?>checked<?php endif; ?> />Hide in client interface?</td>
    <?php if ($this->_tpl_vars['info']['parent_id'] > 0 || $this->_tpl_vars['info']['parent_id'] == 0): ?>
    <td class="requiredInput"></td>
    <td><input type="checkbox" name="is_inactive" id="is_inactive" value="2" <?php if ($this->_tpl_vars['info']['is_inactive'] == 2): ?>checked<?php endif; ?> />Hide this article Type for all?</td>
    <?php endif; ?>
  </tr>
  <tr>
    <td colspan="3" align="center">
	    <input type="button" name="save" class="button"  value="Save" onclick="check_f_article_type('save')"  />
    </td>
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