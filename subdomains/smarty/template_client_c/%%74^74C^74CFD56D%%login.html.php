<?php /* Smarty version 2.6.11, created on 2014-07-21 02:12:32
         compiled from client/login.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['sys_charset']; ?>
">
<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
<style type="text/css">@import url("admin_login.css");</style>
<link rel="shortcut icon" href="/images/favicon.ico" />
<script type="text/javascript" src="/js/common.js"></script>

<script language="javascript" type="text/javascript">
<?php echo '
<!--
	function setFocus() {
		document.loginForm.user_name.select();
		document.loginForm.user_name.focus();
	}

  function check_f_login()
  {
    var f = document.loginForm;
    if (f.user_name.value.length == 0) {
        alert(\'Please enter user name\');
        f.user_name.focus();
        return false;
    }
    if (f.user_pw.value.length == 0) {
        alert(\'Please enter password\');
        f.user_pw.focus();
        return false;
    }

    return true;
  }

'; ?>



//-->
</script>
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>

<?php echo '
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
'; ?>

</head>

<body onload="setFocus();" background="/image/bgall.jpg">
<div id="ctr" align="center">
  <div class="login">
    <div class="login-form">
			
			<form action="/login.php" method="post" name="loginForm" id="loginForm" onsubmit="return check_f_login()">
			<div class="form-block">
				<div class="inputlabel">Username</div>
				<div><input name="user_name" type="text" class="inputbox" size="15" /></div>
				<div class="inputlabel">Password</div>
				<div><input name="user_pw" type="password" class="inputbox" size="15" /></div>
				<div align="left">
          <input type="image" src="/images/backendlogin.png" name="submit" value="Login" />
        </div>
			</div>
			</form>
	</div>
		<div class="login-text"><br>
			<div class="ctr"><img src="/image/logo.gif" width="135" height="60" alt="security login" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<p class="welecomefont" >Welcome to CopyPress</p>
			<p class="descfont">Use a valid username and password to gain access to the content management system.</p>
		</div>
		<div class="clr"></div>
  </div>
</div>
<div id="break"></div>
<noscript>
!Warning! Javascript must be enabled for proper operation of the Administrator
</noscript>

</body>
</html>
