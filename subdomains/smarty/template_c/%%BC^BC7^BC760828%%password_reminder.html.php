<?php /* Smarty version 2.6.11, created on 2012-03-05 12:23:56
         compiled from password_reminder.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 Transitional//EN">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['sys_charset']; ?>
">

<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>

<style type="text/css">@import url("/themes/Default/style.css"); </style>

<link href="/themes/Default/navigation.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" media="all" href="/js/calendar/calendar.css" />

<link type="text/css" rel="StyleSheet" href="/js/sortabletable/sortabletable.css" />
<style type="text/css">
<!--
<?php echo '
.loginSignin {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        color: #fff;
        font-weight:bold;
}
'; ?>

-->
</style>
<style type="text/css">@import url("admin_login.css");</style>
<script type="text/javascript" src="/js/min.rico.js"></script> 
<script type="text/javascript" src="/js/ajaxaction.js"></script> 
<script type="text/javascript" src="/js/common.js"></script>

<?php echo '

<script language="JavaScript">

function check_f_password_reminder( url )
{
	var f = document.f_password_reminder;
	if (!isEmail(f.email.value)) {
		alert(\'Invalid email address\');
		f.email.focus();
		return false;
	}

 if (f.user_name.value == \'\')
 {
    alert(\'Please specify the user name\');
    f.user_name.focus();
    return false;
 }
  new Ajax.Updater
  (
      \'show_status\',
       url, 
       {
           method:\'post\',  
           parameters: Form.serialize(\'f_password_reminder\'),
           evalScripts:true,
           onComplete:showResult
       }
  );
}

function showResult()
{
	Element.show(\'show_shape_end\');
}
</script>
'; ?>

</head>



<body background="/image/bgall.jpg">

<div id="ctr" align="center">
  <div class="login">
    <div class="login-form">
			<strong class="loginSignin">Password Reminder</strong>
			<form action="/password_reminder.php" method="post" name="f_password_reminder" id="f_password_reminder" onsubmit="check_f_password_reminder('<?php echo $this->_tpl_vars['url']; ?>
')" >
			<div class="form-block">
				<div class="inputlabel">User Name:</div>
				<div><input class="inputbox" name="user_name" type="text"></div>
				<div class="inputlabel">Email Address:</div>
				<div><input class="inputbox" name="email" type="text"></div>
        <div align="left">
				<input class="button" name="button" value="Submit" type="button" onclick="check_f_password_reminder('<?php echo $this->_tpl_vars['url']; ?>
')"></div>
			</div>
			</form>
	</div>
		<div class="login-text"><br>
			<div class="ctr"><img src="/image/logo.gif" width="194" height="56" alt="security login" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<p class="welecomefont" >Welcome to Copypress</p>
			<p>&nbsp;</p>
		</div>
    		<div class="clr" align="center">
      <div id="show_shape_end" class="corner" style="display:none;width:310px;z-index:1000;height: 30px;" > 
        <div class="ricohint" style="width:310px;z-index:1000;" id="show_status"  align="center" >saving...</div> 
      </div>
    </div>
     </div>
  </div>
</div>
<div id="break"></div>
<noscript>
!Warning! Javascript must be enabled for proper operation of the Administrator
</noscript>

</body>
</html>
