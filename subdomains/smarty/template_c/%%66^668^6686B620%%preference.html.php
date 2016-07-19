<?php /* Smarty version 2.6.11, created on 2012-04-24 13:13:18
         compiled from user/preference.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/preference.html', 88, false),)), $this); ?>
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
function addUser(form) {
	var fl = $(\'editors\').length -1;
	var au = $(\'pref_value\').length -1;

	var users = "x";

	//build array of assiged users
	for (au; au > -1; au--) {
		users = users + "," + $(\'pref_value\').options[au].value + ","
	}

	//Pull selected resources and add them to list
	for (fl; fl > -1; fl--) {
		if ($(\'editors\').options[fl].selected && users.indexOf( "," + $(\'editors\').options[fl].value + "," ) == -1) {
			t = $(\'pref_value\').length
			opt = new Option( $(\'editors\').options[fl].text, $(\'editors\').options[fl].value);
			$(\'pref_value\').options[t] = opt
		}
	}
}

function removeUser(form) {
	fl = $(\'pref_value\').length -1;
	for (fl; fl > -1; fl--) {
		if ($(\'pref_value\').options[fl].selected) {
			//remove from hperc_assign
			var selValue = $(\'pref_value\').options[fl].value;			
			var re = ".*("+selValue+";).*";
      $(\'pref_value\').options[fl] = null;
		}
	}
}

function check_Form()
{
  var au = $(\'pref_value\').length;
  if (au <= 0)
  {
    alert("Please Choose User to move to Right Drop Down List");
    return false;
  } 
  else 
  {
    $(\'user_ids\').value = \'\';
    for(var i=0; i< au; i++)
    {
        $(\'user_ids\').value += $(\'pref_value\').options[i].value + ";";
    }
  }
	$(\'f_client_total_spend\').submit();
}
'; ?>

</script>
<div id="page-box1">
  <h2>Client Total Spend Priviledge Setting</h2>
  <div id="campaign-search" >
    <strong>Total spend ONLY show for users in selected user list .</strong>
  </div>
  <div class="form-item" >
<form action="" method="post" name="f_client_total_spend" id="f_client_total_spend" >
<table cellspacing="0" cellpadding="4" align="center" width="99%">
  <input type="hidden" name="pref_table" id="pref_table" value="users" />
  <input type="hidden" name="pref_field" id="pref_field" value="user_id" />
  <input type="hidden" name="user_ids" id="user_ids" value="" />
  <tr>
	  <td class="bodyBold">Basic Information</td>
	  <td align="right" class="requiredHint" colspan="10" >Required Information</td>
  </tr>
  <tr>
	  <td class="blackLine" colspan="10"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">
      All System User List<br />
      <select name="editors" id="editors" size="10" style="width:220px"  multiple >
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor']), $this);?>

      </select>
    </td>
    <td align="center">
        <input type="button" class="button" value="&gt;&gt;" onClick="addUser($('f_client_total_spend'))" /><br /><br />
        <input type="button" class="button" value="&lt;&lt;" onClick="removeUser($('f_client_total_spend'))" /><br />
    </td>
    <td align="left">
      Selected User List<br />
      <select name="pref_value" id="pref_value" size="10"  style="width:220px" >
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['selected_users']), $this);?>

      </select>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
	    <input type="button" name="save" class="button" value="Save" onclick="check_Form()"  />
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