<?php /* Smarty version 2.6.11, created on 2012-03-05 09:59:25
         compiled from client_campaign/campaign_notes.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/campaign_notes.html', 62, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">
function addSubject() {
    var subject = document.getElementById("sub");
    var choice = subject.options[subject.selectedIndex].text;
    var content = document.getElementById("note").value;
    if (subject.selectedIndex != 0)
    {
        if(content != \'\') {
            document.getElementById("note").value = content + "\\n" + choice;
        }
        else 
            document.getElementById("note").value = choice;
   }
}
</script>
'; ?>

<style>
<?php echo '
.divTextarea {
  height:100px;
}
'; ?>

</style>
<div id="page-box1">
  <h2>Editorial Notes For Campaign</h2>
  <div id="campaign-search" >
    <?php if ($this->_tpl_vars['creator_role'] == 'admin' || $this->_tpl_vars['creator_role'] == 'project manager' || $this->_tpl_vars['creator_role'] == 'editor'): ?>
    <strong>Please enter Editorial Notes.</strong>
    <?php endif; ?>
  </div>
  <div class="form-item" >
<form action="" method="post" id="formNotes" name="formNotes">
<?php if ($this->_tpl_vars['creator_role'] == 'admin' || $this->_tpl_vars['creator_role'] == 'project manager' || $this->_tpl_vars['creator_role'] == 'editor'): ?>
	<input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
	<input type="hidden" name="creator" id="creator" value="<?php echo $this->_tpl_vars['creator']; ?>
" />
	<input type="hidden" name="operation" id="operation" value="append" />
	<input type="hidden" name="creator_role" id="creator_role" value="<?php echo $this->_tpl_vars['creator_role']; ?>
" />
<?php endif; ?>
<table width="90%" align="center" >  
<?php if ($this->_tpl_vars['notes'] != ''): ?>
  <tr class="sortableTab"><th ><div class="divMain" >&nbsp;&nbsp;&nbsp;&nbsp;Editorial Notes&nbsp;[<a href="javascript:void(0);" onclick="<?php echo 'if (confirm(\'delete all notes for this campaign?\')){document.getElementById(\'operation\').value=\'delete\';document.getElementById(\'formNotes\').submit(); }'; ?>
" style="color:red;" >&nbsp;delete all notes</a>]:</div></th></tr>
  <tr><td><div class="divContent" ><?php echo $this->_tpl_vars['notes']; ?>
</div></td></tr>
<?php else: ?>
  <?php if ($this->_tpl_vars['creator_role'] == 'copy writer'): ?>
  <tr><td><div class="divMain" >There are no notes</div></td></tr>
  <?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['creator_role'] == 'admin' || $this->_tpl_vars['creator_role'] == 'project manager' || $this->_tpl_vars['creator_role'] == 'editor'): ?>
<tr class="sortableTab">
  <th>
    <div class="divMain" >&nbsp;&nbsp;&nbsp;&nbsp;Append Notes:</div>
 </th>
</tr>
<tr>
<td><textarea name="note" id="note"  cols="100" rows="10" class="divTextarea" ></textarea></td>
</tr>
<tr>
  <td><input type="submit" class="button" value="save" onclick="document.getElementById('operation').value='append';" >
  <input type="submit" class="button" value="save & email notes" onclick="document.getElementById('operation').value='email';" >
  <select id="sub" name="sub" onchange="addSubject()"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['subject']), $this);?>
</select>
</tr> 
<input type="hidden" id="subject" name="subject" value="">
<th></th>
<tr><td><center><div style="color:red"><?php echo $this->_tpl_vars['feedback']; ?>
</div></center></td></tr>
<?php endif; ?>
</table>
</form>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>