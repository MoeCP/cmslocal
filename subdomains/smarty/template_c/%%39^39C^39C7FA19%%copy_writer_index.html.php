<?php /* Smarty version 2.6.11, created on 2014-07-07 14:59:18
         compiled from user/copy_writer_index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'user/copy_writer_index.html', 77, false),)), $this); ?>
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
<script type="text/javascript">
<?php echo '
function show(campaign_id) {
    var url = \'/client_campaign/campaign_style_guide.php?campaign_id=\'+campaign_id;
    openWindow(url, \'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes\');
}
'; ?>

</script>

<?php echo '
<style>
    .graph { 
        /*position: relative; /* IE is dumb */
        width: 200px; 
        border: 1px solid #B1D632; 
        padding: 2px; 
    }
    .graph .bar { 
        display: block;
        /*position: relative;*/
        background: #B1D632; 
        text-align: center; 
        color: #333; 
        height: 2em; 
        line-height: 2em;            
    }
    .graph .bar span { position: absolute; left: 1em; }
    .graph .rejectedbar { 
        display: block;
        /*position: relative;*/
        background: #DA7166; 
        text-align: center; 
        color: #333; 
        height: 2em; 
        line-height: 2em;            
    }
    .graph .rejectedbar span { /*position: absolute;*/ left: 1em; }
</style>
'; ?>


<?php echo $this->_tpl_vars['xajax_javascript']; ?>


<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>
<div class="tablepadding" >
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
    <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>odd<?php else: ?>even<?php endif; ?>" >
      <td> Total articles completed to date:</td>
      <td><?php echo $this->_tpl_vars['reports']['total_completed_so_far']; ?>
</td>
    </tr>
    <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
      <td>Total current assignments:</td>
      <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['total_assigned'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
    </tr>
    <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>odd<?php else: ?>even<?php endif; ?>" >
      <td>Total client approved articles to date: </td>
      <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['total_client_approved_so_far'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
    </tr>
    <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" >
      <td>Total articles assigned so far: </td>
      <td><?php echo $this->_tpl_vars['reports']['total_assigned_so_far']; ?>
</td>
    </tr>
    <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>odd<?php else: ?>even<?php endif; ?>" >
      <td nowrap><span class="total-text">Total client approved articles <?php echo $this->_tpl_vars['monthtitle']; ?>
: </span></td><td><a href="/client_campaign/client_approval_list.php?month=<?php echo $this->_tpl_vars['showmonth']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['reports']['1gc_this_month'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</a></td>
    </tr>
    </table>    
    <table border="0" cellspacing="0" cellpadding="0" align="center" width="99%" class="all-link-text">
      <tr><td align="right" >Click <a href="/client_campaign/client_approval_list.php?month=<?php echo $this->_tpl_vars['showmonth']; ?>
" >here</a> to view your pay summary</td></tr>
    </table>
  </td>
</tr>
<tr valign="top" >
  <td>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/campaign_overview.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </td>
  <td>
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