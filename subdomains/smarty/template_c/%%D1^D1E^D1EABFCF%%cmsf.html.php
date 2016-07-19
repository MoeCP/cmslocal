<?php /* Smarty version 2.6.11, created on 2012-03-05 13:39:32
         compiled from manual_content/cmsf.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript" language="javascript">
function iFrameHeight() {   
    var ifm= document.getElementById("iframepage");   
    var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;   
    if(ifm != null && subWeb != null) {
       ifm.height = subWeb.body.scrollHeight;
          if ( ifm.height < 500)
         {
            ifm.height = 500;
         }
    }
}
'; ?>

</script>
<iframe src="<?php echo $this->_tpl_vars['url']; ?>
" id="iframepage" name="iframepage" frameBorder="0" scrolling="yes" height="500" width="100%" onLoad="iFrameHeight()" ></iframe>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>