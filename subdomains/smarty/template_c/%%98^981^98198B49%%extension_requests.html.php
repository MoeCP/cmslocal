<?php /* Smarty version 2.6.11, created on 2012-03-19 23:37:06
         compiled from user/extension_requests.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/extension_requests.html', 25, false),array('modifier', 'nl2br', 'user/extension_requests.html', 78, false),)), $this); ?>
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
  <h2>Extension Requests Page</h2>
  <div id="campaign-search" >
      <strong>You can enter the "copy write user name","copy write first name","copy write last name" into copy writer keyword input to search the relevant copy writer's information<br />You can enter the "Campaign name" into campaign keyword input to search the relevant campaign  information</strong>
      <div id="campaign-search-box" >
      <form name="f_extension_request_search" action="/user/extension_requests.php" method="get">
  <input type="hidden" name="get_operation" value="search" />
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td nowrap>Copy Writer Keyword</td>
    <td><input type="text" name="cp_keyword" id="cp_search_keyword" value="<?php echo $_GET['cp_keyword']; ?>
" ></td>
    <td nowrap>Campaign Keyword</td>
    <td><input type="text" name="c_keyword" id="c_search_keyword" value="<?php echo $_GET['c_keyword']; ?>
" ></td>
    <?php if ($this->_tpl_vars['role'] == 'admin'): ?>
    <td nowrap>Editor:</td>
    <td><select name="editor_id"><option value="" >[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
    <?php endif; ?>
    <td nowrap>Show:</td>
    <td nowrap><select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
</form>
      </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="/user/extension_requests.php" name="f_extension_requests" method="post" />
  <input type="hidden" name="copy_writer_id" />
  <input type="hidden" name="campaign_id" />
  <input type="hidden" name="extension_id" />
  <input type="hidden" name="editor_id" />
  <input type="hidden" name="ck_editor_id" />
  <input type="hidden" name="frequency" />
  <input type="hidden" name="form_refresh" value="N" />
  <input type="hidden" name="operation" value="grant" />
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Number of Articles</td>
    <td nowrap class="columnHeadInactiveBlack">Article Due Dates</td>
    <td nowrap class="columnHeadInactiveBlack">Days Requested</td>
    <td nowrap class="columnHeadInactiveBlack">Reason</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['editor'] != ''):  echo $this->_tpl_vars['item']['editor'];  else:  echo $this->_tpl_vars['item']['ckeditor'];  endif; ?></td>
    <td><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['date_start']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['date_end']; ?>
</td>
    <td><a href="/user/articles.php?copy_writer_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&editor_id=<?php echo $this->_tpl_vars['item']['ck_editor_id']; ?>
"  style="color:red" ><?php echo $this->_tpl_vars['item']['total_writing']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['due']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['days_asked']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['reason'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['item']['status'] == 0): ?>
      <input type="button" class="button" value="Grant Extension" onclick="extension_action('grant', '<?php echo $this->_tpl_vars['item']['extension_id']; ?>
', '<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', '<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', '<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', '<?php echo $this->_tpl_vars['item']['ck_editor_id']; ?>
')" />
      <input type="button" class="button" value="Reject Extension " onclick="extension_action('reject', '<?php echo $this->_tpl_vars['item']['extension_id']; ?>
', '<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', '<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', '<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', '<?php echo $this->_tpl_vars['item']['ck_editor_id']; ?>
')" />
      <?php elseif ($this->_tpl_vars['item']['status'] == 1): ?>
      Rejected
      <?php elseif ($this->_tpl_vars['item']['status'] == 2): ?>
      Granted
      <?php endif; ?>
    </td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
</form>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
</div>
<script type="text/javascript">
//<![CDATA[
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>"CaseInsensitiveString",<?php endif; ?> "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

<?php echo '
st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);

function extension_action(action, eid, cpid, cid, editorid, ckeid)
{
     var f = document.f_extension_requests;
     f.operation.value = action;
     f.extension_id.value= eid;
     f.copy_writer_id.value= cpid;
     f.editor_id.value= editorid;
     f.ck_editor_id.value= ckeid;
     f.campaign_id.value= cid;
     if (action== \'grant\')
     {
        var w = (window.open("/user/grant_extension.php?eid="+eid +"&ckeid="+ckeid,"GrantExtension","width=600, height=430, resizable=yes, scrollbars=yes"));
      }
     else
     {
        f.submit();
     }
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>