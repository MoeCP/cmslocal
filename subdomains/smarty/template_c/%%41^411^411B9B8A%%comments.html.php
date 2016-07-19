<?php /* Smarty version 2.6.11, created on 2014-09-10 15:21:09
         compiled from client_campaign/comments.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/comments.html', 22, false),array('function', 'eval', 'client_campaign/comments.html', 71, false),array('modifier', 'default', 'client_campaign/comments.html', 36, false),)), $this); ?>
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
  <h2>
  Comments Page &nbsp;&nbsp;&nbsp;&nbsp;</h2>
  <div id="campaign-search" >
    <div id="campaign-search-box" >
 <form name="f_assign_keyword_return" id="f_assign_keyword_return"  action="" method="get">
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td   nowrap>Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_GET['keyword']; ?>
"></td>
    <?php if ($this->_tpl_vars['user_permission_int'] <> 1 && $this->_tpl_vars['user_permission_int'] <> 3): ?>
    <td nowrap>Client</td>
    <td><select name="client_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['clients'],'selected' => $_GET['client_id']), $this);?>
</select></td>
    <?php endif; ?>
    <td nowrap>Campaign</td>
    <td><select name="campaign_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
    <?php if ($this->_tpl_vars['user_permission_int'] <> 3): ?>
    <td nowrap>Editor</td>
    <td><select name="editor_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['editors'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['user_permission_int'] <> 1): ?>
    <td nowrap>Copywriter</td>
    <td><select name="writer_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['writers'],'selected' => $_GET['writer_id']), $this);?>
</select></td>
    <?php endif; ?>
    <td nowrap>Show:</td>
    <td nowrap>
       <select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => ((is_array($_tmp=@$_GET['perPage'])) ? $this->_run_mod_handler('default', true, $_tmp, 50) : smarty_modifier_default($_tmp, 50))), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp;
    </td>
    <td colspan="4" nowrap><input type="image" src="/images/button-search.gif" value="submit" /></td>
  </tr>
</table><br>
</form>       
    </div>
  </div>
</div>
<div class="tablepadding"> 
<form action="" method="post"  name="f_acct_flow" id="f_acct_flow" >
  <input type="hidden" name="user_id" value="">
  <input type="hidden" name="payment_flow_status" value="">
  <input type="hidden" name="article_ids" value="">
  <input type="hidden" name="month" value="">
  <input type="hidden" name="vendor_id" value=""/>
  <input type="hidden" name="role" id="role"  value="<?php echo $this->_tpl_vars['role']; ?>
">
</form>
<table id="table-1" cellspacing="0" align="center" cellpadding="0" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2" rowspan="2">#</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Date</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Author</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Comment</td>
    <td nowrap class="columnHeadInactiveBlack" rowspan="2">Campaign</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2" rowspan="2">Article Title</td>
    <th class="table-right-corner" rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2"><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['creation_date']; ?>
</td>
    <td><a href="/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
    <td><?php if ($this->_tpl_vars['user_permission_int'] == 1 && $this->_tpl_vars['item']['creation_role'] == 'client'): ?>Client<?php else:  if ($this->_tpl_vars['item']['creation_role'] == 'client'):  echo $this->_tpl_vars['item']['creator'];  else:  echo $this->_tpl_vars['item']['author'];  endif;  endif; ?></td>
    <td><?php echo $this->_tpl_vars['item']['comment']; ?>
</td>
    <td><a href="<?php if ($this->_tpl_vars['user_permission_int'] == 1): ?>/article/article_keyword_list.php<?php elseif ($this->_tpl_vars['user_permission_int'] == 3): ?>/article/article_list.php<?php else: ?>/client_campaign/keyword_list.php<?php endif; ?>?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</a></td>
    <td nowrap class="table-right-2"><?php echo $this->_tpl_vars['item']['title']; ?>
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
<?php echo '
<script type="text/javascript">
//<![CDATA[
//]]>
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>