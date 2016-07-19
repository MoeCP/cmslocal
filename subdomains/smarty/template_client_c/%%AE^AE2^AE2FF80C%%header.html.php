<?php /* Smarty version 2.6.11, created on 2014-10-30 10:47:04
         compiled from themes/Default/header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'themes/Default/header.html', 62, false),array('modifier', 'count', 'themes/Default/header.html', 70, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['sys_charset']; ?>
" />
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
<script type="text/javascript" src="/js/overlib/overlib_mini.js"></script>
<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="/js/tabpane/tabpane.css" />
<script type="text/javascript" src="/js/tabpane/tabpane.js"></script>
<script type="text/javascript" src="/js/prototype.js"></script>
<script type="text/javascript" src="/js/ajaxaction.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/prototype-window/window.js"> </script>
<link href="/js/prototype-window/themes/default.css" rel="stylesheet" type="text/css"/> 
<!-- Add this to have a specific theme--> 
<link href="/js/prototype-window/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<?php echo '
<!--[if IE]>
<style type="text/css" media="screen">
body { behavior: url("/themes/Default/csshover.htc"); }
</style>
<![endif]-->
<script language="JavaScript" type="text/JavaScript">
if (window != top) top.location.href = location.href;
</script>
'; ?>

</head>

<body <?php echo $this->_tpl_vars['onload']; ?>
>
<table  cellspacing="0" cellpadding="0" border="0" width="100%" id="container" >
<tr class="first-row" width="31" height="31">
  <td><img src="/images/main-corner-tl.jpg" width="31" height="31" /></td>
  <td class="main-conter-t" >&nbsp;</td>
  <td width="31" height="31"><img src="/images/main-corner-tr.jpg" width="31" height="31" /></td>
</tr>
<tr>
  <td class="main-conter-l">&nbsp</td>
  <td>
  <div id="main" >
      <div id="ajaxloading"  class="loadingajax2"  style="display:none;z-index:9999" >Loading...</div>
    <div id="header" >
      <div>
        <div id="company_logo">
        <a href="/" ><img alt="CopyPress" class="header-logo" src="/images/copypress-logo.gif" /></a>
        </div>
      </div>
      <div id="header-info" >
        <p>
          <span class="header-welcome">Welcome, <?php echo $this->_tpl_vars['loggedin_user_name']; ?>
</span> 
          <span class="header-logout"><a href="/logout.php">Logout</a></span>
        </p>
        <p>
          Last Login <span class="header-date"><?php if ($this->_tpl_vars['last_login_time'] == 0): ?>Never<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['last_login_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y, %I:%M %p") : smarty_modifier_date_format($_tmp, "%m-%d-%Y, %I:%M %p"));  endif; ?></span><br />
          Today's Date <span class="header-date"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</span>
        </p>      
      </div>
      <div id="topbar">
       <ul id="top-nav" >
        <?php $_from = $this->_tpl_vars['main_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_module'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_module']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['module']):
        $this->_foreach['loop_module']['iteration']++;
?>
          <?php if ($this->_tpl_vars['module']['pos'] != 'right'): ?>
          <li><a href="<?php echo $this->_tpl_vars['module']['url']; ?>
" <?php if ($this->_tpl_vars['module']['target']): ?>target="<?php echo $this->_tpl_vars['module']['target']; ?>
"<?php endif; ?> <?php if (( count($this->_tpl_vars['module']['sub_menu']) ) > 1): ?>onmouseover="mopen('m<?php echo $this->_foreach['loop_module']['iteration']; ?>
')" onmouseout="mclosetime()"<?php endif; ?>>
          <?php if ($this->_tpl_vars['module']['image'] != ''): ?><img alt="<?php echo $this->_tpl_vars['module']['module_name']; ?>
" src="/images/<?php echo $this->_tpl_vars['module']['image'];  if ($this->_tpl_vars['g_current_path'] == $this->_tpl_vars['module']['path']): ?>-on<?php endif; ?>.jpg"><?php else:  echo $this->_tpl_vars['module']['module_name'];  endif; ?>
          </a>
          <?php if (( count($this->_tpl_vars['module']['sub_menu']) ) > 1): ?>
          <div id="m<?php echo $this->_foreach['loop_module']['iteration']; ?>
" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
            <a class="listfirst" >&nbsp;</a>
            <?php $_from = $this->_tpl_vars['module']['sub_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_sub_menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_sub_menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub_menu']):
        $this->_foreach['loop_sub_menu']['iteration']++;
?>
            <a href="<?php echo $this->_tpl_vars['sub_menu']['url']; ?>
" <?php if ($this->_tpl_vars['sub_menu']['target']): ?>target="<?php echo $this->_tpl_vars['sub_menu']['target']; ?>
"<?php endif; ?> class="<?php if ($this->_foreach['loop_sub_menu']['iteration'] % 2 == 1): ?>limenuodd<?php else: ?>limenueven<?php endif; ?>" ><?php echo $this->_tpl_vars['sub_menu']['image']; ?>
 <?php echo $this->_tpl_vars['sub_menu']['label']; ?>
</a>
            <?php endforeach; endif; unset($_from); ?>
            <a class="listlast" >&nbsp;</a>
          </div>
            <?php endif; ?>
            </li>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <?php if ($this->_tpl_vars['main_menu']): ?>
        <li><img alt="" src="/images/topbar-none.jpg" width="50" /></li>
        <?php endif; ?>
        <?php $_from = $this->_tpl_vars['main_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_module'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_module']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['module']):
        $this->_foreach['loop_module']['iteration']++;
?>
          <?php if ($this->_tpl_vars['module']['pos'] == 'right'): ?>
          <li><a href="<?php echo $this->_tpl_vars['module']['url']; ?>
" <?php if ($this->_tpl_vars['module']['onclick']): ?>onclick="<?php echo $this->_tpl_vars['module']['onclick']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['module']['target']): ?>target="<?php echo $this->_tpl_vars['module']['target']; ?>
"<?php endif; ?> <?php if (( count($this->_tpl_vars['module']['sub_menu']) ) > 1): ?>onmouseover="mopen('m<?php echo $this->_foreach['loop_module']['iteration']; ?>
')" onmouseout="mclosetime()"<?php endif; ?>><?php if ($this->_tpl_vars['module']['image'] != ''): ?><img alt="<?php echo $this->_tpl_vars['module']['module_name']; ?>
" src="/images/<?php echo $this->_tpl_vars['module']['image'];  if ($this->_tpl_vars['g_current_path'] == $this->_tpl_vars['module']['path']): ?>-on<?php endif; ?>.jpg"><?php else:  echo $this->_tpl_vars['module']['module_name'];  endif; ?></a>
          <?php if (( count($this->_tpl_vars['module']['sub_menu']) ) > 1): ?>
          <div id="m<?php echo $this->_foreach['loop_module']['iteration']; ?>
" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
            <a class="listfirst" >&nbsp;</a>
            <?php $_from = $this->_tpl_vars['module']['sub_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_sub_menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_sub_menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sub_menu']):
        $this->_foreach['loop_sub_menu']['iteration']++;
?>
            <a href="<?php echo $this->_tpl_vars['sub_menu']['url']; ?>
" <?php if ($this->_tpl_vars['sub_menu']['target']): ?>target="<?php echo $this->_tpl_vars['sub_menu']['target']; ?>
"<?php endif; ?> class="<?php if ($this->_foreach['loop_sub_menu']['iteration'] % 2 == 1): ?>limenuodd<?php else: ?>limenueven<?php endif; ?>"><?php echo $this->_tpl_vars['sub_menu']['image']; ?>
 <?php echo $this->_tpl_vars['sub_menu']['label']; ?>
</a>
            <?php endforeach; endif; unset($_from); ?>
            <a class="listlast" >&nbsp;</a>
          </div>
            <?php endif; ?>
          </li>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
       </ul>
      </div>
    </div>
    <div id="page" >
      <!-- quick pane start -->
      <?php if ($this->_tpl_vars['quick_pane']): ?>
      <div id="breadcrumb" >
        <?php $_from = $this->_tpl_vars['quick_pane']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_quick_menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_quick_menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['quick_menu']):
        $this->_foreach['loop_quick_menu']['iteration']++;
?>
        <a href="<?php echo $this->_tpl_vars['quick_menu']['url']; ?>
"><?php echo $this->_tpl_vars['quick_menu']['lable']; ?>
</a>&nbsp;&gt;
        <?php endforeach; endif; unset($_from); ?>
      </div>
      <?php endif; ?>
      <!-- quick pane end -->

      