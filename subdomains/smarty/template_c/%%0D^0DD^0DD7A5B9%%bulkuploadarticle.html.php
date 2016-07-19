<?php /* Smarty version 2.6.11, created on 2014-08-21 11:52:01
         compiled from article/bulkuploadarticle.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>

<?php echo '
<script language="JavaScript">
<!--
function check_f_bulk_article()
{
  var f = document.f_bulk_article;
  if (f.filename.value.length == 0)
  {
	alert("Please specify the file");
	f.filename.focus();
	return false;
  }

  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Bulk Upload Article feature only for Client#350 (Hipmunk)</h2>
  <div id="article-search" >
    <strong>Sepcifies for each article - Article ID, Article Name, Content, etc</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_bulk_article" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_bulk_article()"<?php endif; ?> enctype="multipart/form-data">
  <input type="hidden" name="client_id" value="350" />
  <tr>
    <td class="bodyBold"><h3>Upload Article File (Only Support CSV format)</h3></td>
  </tr>
  <tr>
    <td class="bodyBold" colspan="2" >
    Upload a csv file with Article ID, Article Name, Content, and so on. but you must have the Keyword column.  <br />
    </td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="dataLabel"> Upload Article File</td>
    <td><input type="file" name="filename" id="filename" value="" size="60" /></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;
    <input type="submit" value="Upload" class="button" />
    </td>
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