<?php /* Smarty version 2.6.11, created on 2012-03-05 09:25:06
         compiled from themes/Default/header_jump.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['sys_charset']; ?>
">
<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
<style type="text/css">@import url("/themes/<?php echo $this->_tpl_vars['theme']; ?>
/style.css"); </style>
<link rel="stylesheet" type="text/css" media="all" href="/js/calendar/calendar.css" />
<link type="text/css" rel="StyleSheet" href="/js/sortabletable/sortabletable.css" />
<link rel="shortcut icon" href="/images/favicon.ico" />
<script type="text/javascript" src="/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/js/calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="/js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="/js/sortabletable/sortabletable.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/prototype.js"></script>
<script type="text/javascript" src="/js/rico.js"></script>
<script type="text/javascript" src="/js/prototype-window/window.js"> </script>
<link href="/js/prototype-window/themes/default.css" rel="stylesheet" type="text/css"/> 
<!-- Add this to have a specific theme--> 
<link href="/js/prototype-window/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>

<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="/js/tabpane/tabpane.css" />
<script type="text/javascript" src="/js/tabpane/tabpane.js"></script>

<body <?php echo $this->_tpl_vars['onload']; ?>
 background="/image/bgall.jpg">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr><td>
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
  <?php if ($this->_tpl_vars['is_include_left'] == true): ?>
  <td class="leftRegion"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php endif; ?><td class="mainContent">