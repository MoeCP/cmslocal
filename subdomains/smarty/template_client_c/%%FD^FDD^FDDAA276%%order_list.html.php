<?php /* Smarty version 2.6.11, created on 2012-02-29 15:59:47
         compiled from client_campaign/order_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/order_list.html', 19, false),array('modifier', 'date_format', 'client_campaign/order_list.html', 55, false),)), $this); ?>
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
  <h2>Campaign Orders</h2>
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
  <div id="campaign-search" >
  <div id="campaign-search-box" >
  <form name="f_assign_keyword_return" action="/client_campaign/order_list.php" method="get">
  <table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td nowrap>client</td>
    <td><?php echo smarty_function_html_options(array('name' => 'client_id','options' => $this->_tpl_vars['clients'],'selected' => $_GET['client_id']), $this);?>
</td>
    <td><input type="image" src="/images/button-search.gif" value="submit" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="70%">&nbsp;</td>
  </tr>
  </table>
  </form>
  </div>
  </div>
  <?php endif; ?>
</div>
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <td nowrap class="columnHeadInactiveBlack table-left-2">Client Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Category</td>
    <td nowrap class="columnHeadInactiveBlack">Content Type</td>
    <td nowrap class="columnHeadInactiveBlack">Order Date</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Created Date</td>
    <td nowrap class="columnHeadInactiveBlack">Creator</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_tpl_vars['item']['client_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['category']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['content_type']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['order_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <?php if ($this->_tpl_vars['item']['campaign_id']): ?>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_created'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
    <?php else: ?>
    <td></td>
    <td></td>
    <?php endif; ?>
    <td align="right" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['login_permission'] == 5 && $this->_tpl_vars['item']['is_confirm'] == 1 && $this->_tpl_vars['item']['status'] == 7 && $this->_tpl_vars['item']['pay_status'] > 0 && $this->_tpl_vars['item']['pay_status'] < 10): ?>
     <input type="button" class="button" value="Mark as Paid" onclick="window.location.href='/client_campaign/vieworder.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
&is_pay=1'" />
    <?php endif; ?>
    <?php if (! $this->_tpl_vars['item']['campaign_id']): ?>
      <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
        <?php if ($this->_tpl_vars['item']['status'] == 10 && $this->_tpl_vars['login_permission'] == 5): ?>
          <input type="button" class="button" value="Force adjust" onclick="window.location.href='/client_campaign/vieworder.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
&fadjust=1'" />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['item']['status'] == 0 && $this->_tpl_vars['item']['is_confirm'] == 1 && $this->_tpl_vars['login_permission'] == 5): ?>
          <input type="button" class="button" value="Confirm" onclick="window.location.href='/client_campaign/order_campaign_set.php?order_campaign_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
&is_confirm=<?php echo $this->_tpl_vars['item']['is_confirm']; ?>
';" />
        <?php elseif ($this->_tpl_vars['item']['is_confirm'] == 0 || $this->_tpl_vars['item']['status'] >= 7): ?>
          <input type="button" class="button" value="Create Campaign" onclick="createCampaign('<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
')" />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['item']['keyword_id'] == '' && $this->_tpl_vars['item']['download_file'] != ''): ?>
          <input type="button" class="button" value="Field Mapping" onclick="window.location.href='/client_campaign/fieldmapping.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
'" />
        <?php endif; ?>
      <?php elseif ($this->_tpl_vars['login_role'] == 'client'): ?>
        <?php if ($this->_tpl_vars['item']['is_confirm'] == 1 && $this->_tpl_vars['item']['status'] == 4): ?>
          <input type="button" class="button" value="Confirm" onclick="window.location.href='/client_campaign/vieworder.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
&is_confirm=<?php echo $this->_tpl_vars['item']['is_confirm']; ?>
';" />
        <?php elseif ($this->_tpl_vars['item']['is_confirm'] == 1 && $this->_tpl_vars['item']['status'] == 0): ?>
          <input type="button" class="button" value="Cancel" onclick="window.location.href='/client_campaign/vieworder.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
&is_confirm=1';" />
        <?php elseif ($this->_tpl_vars['item']['is_confirm'] == 0): ?>
          <input type="button" class="button" value="Update" onclick="window.location.href='/client_campaign/order_campaign_set.php?order_campaign_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
';" />
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['item']['is_confirm'] == 0 || $this->_tpl_vars['item']['is_confirm'] == 1 && $this->_tpl_vars['item']['status'] >= 4 ) && $this->_tpl_vars['item']['monthly_recurrent'] != 1): ?>
          <input type="button" class="button" value="Replicate Order" onclick="window.location.href = '/client_campaign/order_campaign_set.php?parent_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
';" />
        <?php endif; ?>
      <?php endif; ?>
      <input type="button" class="button" value="Comments" onclick="openWindow('/client_campaign/ajax_comment_add.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
','height=400,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['item']['campaign_id']): ?>
       Campaign Created
       <?php if ($this->_tpl_vars['login_permission'] == 5): ?>
	      <input type="button" class="button" value="Add Keywords" onclick="window.open('/client_campaign/keyword_add.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
');" />
        <?php if ($this->_tpl_vars['item']['parent_campaign_id'] > 0 && $this->_tpl_vars['item']['is_import_kw'] == 0): ?>
	      <input type="button" class="button" value="Replicate Keywords" onclick="window.open('/client_campaign/keyword_add.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&pid=<?php echo $this->_tpl_vars['item']['parent_campaign_id']; ?>
');" />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['item']['parent_id'] == 0 && $this->_tpl_vars['item']['monthly_recurrent'] != 1): ?>
        <input type="button" class="button" value="Replicate Campaign" onclick="doCopyOrder('<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
')" />
        <?php endif; ?>
       <?php endif; ?>
    <?php endif; ?>
    <input type="button" class="button" value="View" onclick="window.location.href='/client_campaign/vieworder.php?order_id=<?php echo $this->_tpl_vars['item']['order_campaign_id']; ?>
';" />
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
<form name="fcopyorder" id="fcopyorder"  action="" >
  <input type="hidden" name="order_campaign_id" id="order_campaign_id" />
  <input type="hidden" name="form_refresh" id="form_refresh" value="N" />
</form>
<div id="div-active-values" style="display:none;" ></div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "CaseInsensitiveString", "CaseInsensitiveString", "Number", "Number", "Date", "Date", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(0);

function doCopyOrder(order_id)
{
    if (confirm(\'Are you sure replicate campaign from this order?\'))
    {
    
      var  f = document.fcopyorder;
      f.form_refresh.value = \'o\';
      f.order_campaign_id.value = order_id;
      ajaxSubmit(\'/client_campaign/order_list.php\', \'fcopyorder\', \'div-active-values\', \'post\', {onComplete: redirectCreateCampaign});
      return false;
  }
}

function redirectCreateCampaign(response)
{
  var data = response.responseText;
  var arr = data.evalJSON();
  if (arr.order_id > 0) {
    createCampaign(arr.order_id);
  } else {
    alert(arr.feedback);
    ajaxdone();
  }
  return true;
}
function createCampaign(order_id)
{
    window.location.href="/client_campaign/client_campaign_add.php?order_campaign_id=" + order_id;
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>