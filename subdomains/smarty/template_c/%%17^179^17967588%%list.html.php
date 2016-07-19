<?php /* Smarty version 2.6.11, created on 2012-03-08 11:40:53
         compiled from mail/list.html */ ?>
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
  <h2>Email Template &nbsp;&nbsp;&nbsp;&nbsp; <?php if ($this->_tpl_vars['user_permission_int'] >= 5): ?><input type="button" class="button" value="Add Template" onclick="javasript:window.location='/mail/add.php';" /><?php endif; ?></h2>
  <div id="campaign-search" >
    <strong>These templates will be useful.Utilize the email templates to accurately and canonical portray some notice automatically.</strong>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="4" align="center" class="sortableTable" width="98%">
  <form action="/mail/list.php" name="mail_list" method="post" />
  <input type="hidden" name="template_id" />
  <input type="hidden" name="form_refresh" value="N" />
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Template Name</td>
    <td nowrap class="columnHeadInactiveBlack">Subject</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['all_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_tpl_vars['email_event'][$this->_tpl_vars['item']['event_id']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['subject']; ?>
</td>
    <td align="right" nowrap class="table-right-2">
	  <input type="button" class="button" value="Update" onclick="javasript:window.location='/mail/set.php?template_id=<?php echo $this->_tpl_vars['item']['template_id']; ?>
';" />
      <input type="submit" class="button" value="Delete" onclick="return deleteSubmit('mail_list', 'template_id', '<?php echo $this->_tpl_vars['item']['template_id']; ?>
', 'D', 'This Template')" />
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