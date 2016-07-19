<?php /* Smarty version 2.6.11, created on 2012-05-29 12:02:34
         compiled from client_campaign/cp_performance.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_performance.html', 28, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">
function search_choice() {

    var search = $("s_choice");
    for (var s = 0; s < search.length ; s++ )
    {
        if (s !=0 && search[s].selected == true)
        {
            window.location.href = "/client_campaign/cp_performance_report.php?s_choice=" + search[s].value;
            break;
        }
    }
}
</script>
'; ?>

<div id="page-box1">
  <h2>Individual Writer Performance Report</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
 <form id="search" name="search" action="" method="get">
<table cellspacing="0" cellpadding="4">
  <tbody>
    <tr align="left" >
      <td> 
      <select name="cp_id" id="cp_id">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_cp'],'selected' => $_GET['cp_id']), $this);?>

      </select> 
      </td>
      <td>
      <select name="rmonth" id="rmonth">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $_GET['rmonth']), $this);?>

      </select> 
      </td>
      <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
     </tr>
  </tbody>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign</td>
    <td nowrap class="columnHeadInactiveBlack">Punctuation</td>
    <td nowrap class="columnHeadInactiveBlack">Grammar</td>
    <td nowrap class="columnHeadInactiveBlack">Structure</td>
    <td nowrap class="columnHeadInactiveBlack">AP Style</td>
    <td nowrap class="columnHeadInactiveBlack">Style Guide</td>
    <td nowrap class="columnHeadInactiveBlack">Content Guality</td>
    <td nowrap class="columnHeadInactiveBlack">Communication</td>
    <td nowrap class="columnHeadInactiveBlack">Cooperativeness</td>
    <td nowrap class="columnHeadInactiveBlack">Timeliness</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Overall</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['article_number']; ?>
</td>
    <td><a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
    <td><a href="/client_campaign/keyword_list.php?article_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></td>
    <td><?php echo $this->_tpl_vars['item']['punctuation']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['grammar']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['structure']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['ap_style']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['style_guide']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['quality']; ?>
 </td>
    <td><?php echo $this->_tpl_vars['item']['communication']; ?>
 </td>
    <td><?php echo $this->_tpl_vars['item']['cooperativeness']; ?>
 </td>
    <td><?php echo $this->_tpl_vars['item']['timeliness']; ?>
 </td>
    <td class="table-right-2"><?php echo $this->_tpl_vars['item']['ranking']; ?>
</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
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
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "Number", "CaseInsensitiveString", "CaseInsensitiveString",  "Number", "Number", "Number", "Number", "Number","Number"]);

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