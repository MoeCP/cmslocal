<?php /* Smarty version 2.6.11, created on 2014-07-07 14:58:32
         compiled from user/editor_index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'user/editor_index.html', 58, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="/js/nanjia/NanJia.js"></script>
<script type="text/javascript" src="/js/nanjia/Ajax.js"></script>
<script type="text/javascript" src="/js/nanjia/Array.js"></script>
<script type="text/javascript" src="/js/nanjia/String.js"></script>
<script type="text/javascript" src="/js/nanjia/Calendar.js"></script>
<script type="text/javascript" src="/js/nanjia/Event.js"></script>
<script type="text/javascript" src="/js/nanjia/File.js"></script>
<script type="text/javascript" src="/js/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="/js/calendar.css">
<?php echo '
<style>
    .graph { 
        position: relative; /* IE is dumb */
        width: 200px; 
        border: 1px solid #B1D632; 
        padding: 2px; 
    }
    .graph .bar { 
        display: block;
        position: relative;
        background: #B1D632; 
        text-align: center; 
        color: #333; 
        height: 2em; 
        line-height: 2em;            
    }
    .graph .bar span { position: absolute; left: 1em; }
</style>
'; ?>


<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>
<div class="tablepadding" >
<div id="page-box1">
  <h2>Editor Progress Report</h2>
</div> 
<div id="page-box2" >
<table border="0" cellspacing="1" cellpadding="4" width="100%" >
<tr valign="top" >
  <td width="70%">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/notification.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </td>
  <td width="25%" >
    <h2>Payment Overview</h2>
    <table border="0" cellspacing="1" cellpadding="4" align="left" width="99%" class="sortableTable" >
    <tr class="odd" >
      <td> Total Articles completed to date:</td>
      <td><?php echo $this->_tpl_vars['reports']['total_completed']; ?>
</td>
    </tr>
    <tr class="even" >
      <td>Total current assignments: </td>
      <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['total_assigned'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
    </tr>
    <tr class="odd" >
      <td>Total articles pending approval: </td>
      <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['total_pending'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
    </tr>
    <tr class="even" >
      <td>Total client approved articles to date: </td>
      <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['total_client_approved_so_far'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
    </tr>
    <tr class="odd" >
      <td colspan="2" ><a href="/client_campaign/client_approval_list.php?month=<?php echo $this->_tpl_vars['showmonth']; ?>
"><font color="red"><span class="total-text">Total client approved articles <?php echo $this->_tpl_vars['monthtitle']; ?>
: <?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['1gc_this_month'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
<span></font></a></td>
    </tr>
    </table>
    <table border="0" cellspacing="0" cellpadding="0" align="center" width="99%" class="all-link-text" >
      <tr><td align="right" >Click <a href="/client_campaign/client_approval_list.php?month=<?php echo $this->_tpl_vars['showmonth']; ?>
" >here</a> to view your pay summary</td></tr>
    </table>
  </td>
</tr>
<tr valign="top">
  <td width="70%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/campaign_overview.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </td>
  <td width="25%" >
    <h2>Calendar</h2>
    <div id="calendar_showdiv"></div>
  </td>
</tr>
</table>
</div>
</div>
<?php echo '
<script type="text/javascript">
function EventHandle() {
    return new Content.Cal.Event();
}
date = new Date();
ca = new NanJia.Calendar(EventHandle);
ca.Current();
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>