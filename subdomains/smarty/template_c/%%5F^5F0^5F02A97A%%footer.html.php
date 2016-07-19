<?php /* Smarty version 2.6.11, created on 2012-03-05 09:23:23
         compiled from themes/Default/footer.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'themes/Default/footer.html', 5, false),)), $this); ?>
    &nbsp;
    </div>
    <div id="footer" >&nbsp;
      <center><pre><?php echo $this->_tpl_vars['adodb_log'];  //print_r($_POST); ?></pre></center>
		  <img src="/images/copypress-logo-footer.gif" alt="CopyPress" align="middle" /> Copyright &copy;<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
 - CopyPress -  All Rights Reserved.
    </div>
  </div>
  </td>
  <td class="main-conter-r">&nbsp;</td>
</tr>
<tr class="last_row" >
  <td><img src="/images/main-corner-bl.jpg" width="31" height="31" /></td>
  <td class="main-conter-b" >&nbsp;</td>
  <td><img src="/images/main-corner-br.jpg" width="31" height="31" /></td>
</tr>
</table>
<?php if ($this->_tpl_vars['login_role'] == 'client'):  echo '
<!-- begin olark code --> 
  <script type=\'text/javascript\'>/*<![CDATA[*/ window.olark||(function(k){var g=window,j=document,a=g.location.protocol=="https:"?"https:":"http:",i=k.name,b="load",h="addEventListener";(function(){g[i]=function(){(c.s=c.s||[]).push(arguments)};var c=g[i]._={},f=k.methods.length;while(f--){(function(l){g[i][l]=function(){g[i]("call",l,arguments)}})(k.methods[f])}c.l=k.loader;c.i=arguments.callee;c.p={0:+new Date};c.P=function(l){c.p[l]=new Date-c.p[0]};function e(){c.P(b);g[i](b)}g[h]?g[h](b,e,false):g.attachEvent("on"+b,e);c.P(1);var d=j.createElement("script"),m=document.getElementsByTagName("script")[0];d.type="text/javascript";d.async=true;d.src=a+"//"+c.l;m.parentNode.insertBefore(d,m);c.P(2)})()})({loader:(function(a){return "static.olark.com/jsclient/loader1.js?ts="+(a?a[1]:(+new Date))})(document.cookie.match(/olarkld=([0-9]+)/)),name:"olark",methods:["configure","extend","declare","identify"]}); olark.identify(\'7044-124-10-2369\');/*]]>*/</script> 
<!-- end olark code --> 
'; ?>

<?php endif; ?>
</body>
</html>