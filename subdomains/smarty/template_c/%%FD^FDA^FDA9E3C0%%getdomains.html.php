<?php /* Smarty version 2.6.11, created on 2012-03-05 10:48:00
         compiled from client_campaign/getdomains.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/getdomains.html', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
</script>
<?php endif;  echo $this->_tpl_vars['adodb_log']; ?>

<script language="JavaScript">
$('source').update('<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['domains']), $this);?>
');
</script>