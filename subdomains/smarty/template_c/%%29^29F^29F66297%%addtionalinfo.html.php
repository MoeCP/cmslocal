<?php /* Smarty version 2.6.11, created on 2015-01-07 04:28:30
         compiled from user/addtionalinfo.html */ ?>
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
  <h2>Update Google Plus URL</h2>
  <div id="campaign-search" >
    <strong></strong>
  </div>
  <div class="form-item" >
  <div style="font-size: 12px;">
  <b>You must add the link to your unique Google+ profile page, not your Google+ homepage.</b><br /><br />
	The following link to your Google+ profile page is acceptable:
	<ul>
		<li>https://plus.google.com/106085836032733171526/</li>
		<li>https://plus.google.com/+youruniquename</li>
	</ul>
	The following links are not acceptable:
	<ul>
		<li>plus.google.com/106085836032733171526 is missing the "https://"</li>
		<li>plus.google.com/+youruniquename is missing the "https://"</li>
		<li>a link to your post page https://plus.google.com/u/0/106085836032733171526/posts</li>
		<li>a link to your about page https://plus.google.com/u/0/10608583603273317152/about</li>
		<li>a link to the homepage: https://plus.google.com/u/0/</li>
		<li>a link with the rel author tag: https://plus.google.com/106085836032733171526/about/p/pub/?rel=author</li>
		<li>your publication link: https://plus.google.com/106085836032733171526/about/p/pub</li>
	</ul>
	To find your Google+ profile page link:
	<ul>
	<li>Go to Google+.</li>
	<li>View your profile. (In the right hand corner of Google+ click your photo > View profile.)</li>
	<li>Go to your About page.</li>
	<li>Scroll down to Links tile.</li>
	<li>Click Get URL.</li>
	<li>Use the link after "Your public profile's URL is:"</li>
	</ul>
	</div>
<table border="0" cellspacing="1" cellpadding="4" align="center" style="margin-left: 10px;">
  <tr>
    <td class="bodyBold" nowrap colspan="2"></td>
    <td align="right" class="requiredHint"></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="3"><img src="/image/misc/s.gif"></td>
    
  </tr>
   <tr>
    <td  colspan="3"><img src="/image/misc/s.gif"></td>
    
  </tr>
  <form action="#" method="post" name="f_user">
  <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
  <tr>
    <td colspan="2" nowrap>Google Plus URL</td>
    <td><input name="googleplus_url" type="text" id="googleplus_url" value="<?php echo $this->_tpl_vars['user_info']['googleplus_url']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="50"></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="3"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td  colspan="3"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="Reset" class="button"></td>
  </tr>
  </form>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>