<?php /* Smarty version 2.6.11, created on 2014-07-04 05:29:37
         compiled from user/user_detail.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link type="text/css" rel="StyleSheet" href="/js/fabtabulous/tabs.css" />
<link href="/js/prototype-window/themes/default.css" rel="stylesheet" type="text/css"/> 
<link href="/js/prototype-window/themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
<?php if ($this->_tpl_vars['user_info']['role'] == 'editor' || $this->_tpl_vars['user_info']['role'] == 'copy writer'): ?>
<script type="text/javascript" src="/js/nanjia/NanJia.js"></script>
<script type="text/javascript" src="/js/nanjia/Ajax.js"></script>
<script type="text/javascript" src="/js/nanjia/Array.js"></script>
<script type="text/javascript" src="/js/nanjia/String.js"></script>
<script type="text/javascript" src="/js/nanjia/Calendar.js"></script>
<script type="text/javascript" src="/js/nanjia/Event.js"></script>
<script type="text/javascript" src="/js/nanjia/File.js"></script>
<script type="text/javascript" src="/js/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="/js/calendar.css">
<?php endif; ?>
<script type="text/javascript" src="/js/multifile.js"></script>
<div id="page-box1">
  <div class="view-item" >
  <div class="tablepadding"> 
<div id="container">
  <div id="banner">&nbsp;</div>
  <div id="mainmenu">
    <ul id="tabs">
      <li>
        <a class="" href="#profile">Profile</a>
      </li>
            <?php if ($this->_tpl_vars['user_info']['role'] == 'copy writer' && $this->_tpl_vars['login_role'] == 'admin'): ?>
      <li>
        <a class="" href="#performance">Performance</a>
      </li>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['user_info']['role'] == 'editor' || $this->_tpl_vars['user_info']['role'] == 'copy writer'): ?>
	        <li>
        <a class="" href="#availability">Availability</a>
      </li>      
      <li>
        <a class="" href="#paymenthistory">Payment History</a>
      </li>
        <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
      <li>
        <a class="" href="#notes">Notes</a>
      </li>
        <?php endif; ?>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['egroups']): ?>
      <li>
        <a class="" href="#esign">Contracts</a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="panel" id="profile">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/profile_tab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
    <?php if ($this->_tpl_vars['user_info']['role'] == 'copy writer' && $this->_tpl_vars['login_role'] == 'admin'): ?>
  <div class="panel" id="performance">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/perf_tab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['user_info']['role'] == 'editor' || $this->_tpl_vars['user_info']['role'] == 'copy writer'): ?>
  <div class="panel" id="availability">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/available_tab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
    <div class="panel" id="paymenthistory">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/payment_history_tab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
  <div class="panel" id="notes">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/note_tab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
    <?php endif; ?>
   <?php endif; ?>
   <?php if ($this->_tpl_vars['egroups']): ?>
   <div class="panel" id="esign">
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/esign_tab.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   </div>
   <?php endif; ?>
</div>
  </div> 
  </div>
</div>

<script type="text/javascript" src="/js/fabtabulous/fabtabulous.js"></script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<?php echo '
var user_id = \'';  echo $this->_tpl_vars['user_info']['user_id'];  echo '\';
function showNoteDialog(note_id) {
  if (note_id > 0)
  {
      var url = \'/user/ajax_note_set.php?note_id=\' + note_id + \'&user_id=\' + user_id;
      var title = \'Edit User Note Info\';
  } 
  else
  {
      var url = \'/user/ajax_note_add.php?user_id=\'+user_id;
      var title = \'Add User Note Info\';
  }
  url += \'&f=detail\';
  showWindowDialog(url, 600, 500, title);
}
function showUserDialog() {
  var url = \'/user/ajax_user_set.php?user_id=\' + user_id + \'&f=detail\';
  showWindowDialog(url, 600, 500, "Edit User Info.");
}
'; ?>

<?php if ($this->_tpl_vars['user_info']['role'] == 'editor' || $this->_tpl_vars['user_info']['role'] == 'copy writer'):  echo '
function EventHandle() {
    return new Content.Cal.Event();
}

date = new Date();
ca = new NanJia.Calendar(EventHandle, user_id);
ca.Current();
'; ?>

<?php endif; ?>
</script>