<?php /* Smarty version 2.6.11, created on 2012-03-19 22:17:43
         compiled from client_campaign/general_notes.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'client_campaign/general_notes.html', 34, false),)), $this); ?>
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
<div id="page-box1">
  <h2>General Editing Notes&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_tpl_vars['user_permission_int'] >= 5): ?><input type="button" class="button" value="Add General Editing Note" onclick="javasript:window.location='/client_campaign/add_general_notes.php';" /><?php endif; ?></h2>
  <div id="campaign-search" >
    <strong>Please reference the general editing notes provided below. These notes will help you in accurately writing and editing your work.</strong>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <form action="/client_campaign/general_notes.php" name="general_note_list" method="post" />
  <input type="hidden" name="general_note_id" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Subject</td>
    <td nowrap class="columnHeadInactiveBlack">Content</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_tpl_vars['item']['subject']; ?>
</td>
    <td ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['user_permission_int'] >= 5): ?>
	    <input type="button" class="button" value="Update" onclick="javasript:window.location='/client_campaign/add_general_notes.php?general_note_id=<?php echo $this->_tpl_vars['item']['general_note_id']; ?>
';" />
      <input type="submit" class="button" value="Delete" onclick="return deleteSubmit('general_note_list', 'general_note_id', '<?php echo $this->_tpl_vars['item']['general_note_id']; ?>
', 'D', 'This General Editing Note')" />
    <?php endif; ?>
    </td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </form>
</table>
</div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);
'; ?>

//]]>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>