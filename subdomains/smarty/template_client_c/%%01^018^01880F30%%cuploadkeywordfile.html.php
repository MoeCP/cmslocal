<?php /* Smarty version 2.6.11, created on 2013-01-18 09:48:36
         compiled from client_campaign/cuploadkeywordfile.html */ ?>
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
function check_f_client_campaign()
{
  var f = document.f_client_campaign;
  if (f.uploadfile.value == \'\') {
    if (f.download_file.value.length == 0)
    {
      alert("Please specify the file");
      f.download_file.focus();
      return false;
    }
  }

  return true;
}
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Import Keyword from file for <?php echo $this->_tpl_vars['campaign_name']; ?>
</h2>
  <div id="campaign-search" >
    <strong>Sepcifies for each article - keywords, focus topic, title, intended URL, example URLs, links/words to include, etc</strong>
  </div>
  <div class="form-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_client_campaign" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_client_campaign()"<?php endif; ?> enctype="multipart/form-data">
  <input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
  <input type="hidden" name="uploadfile" value="<?php echo $this->_tpl_vars['uploadfile']; ?>
" />
  <tr>
    <td class="bodyBold"><h3>Step 1: Upload File</h3></td>
  </tr>
  <tr>
    <td class="bodyBold" colspan="2" >
    Upload a csv file with Keyword column, Optional field 1 column, Optinal field 2 column, Optinal field 3 column,  and so on. but you must have the Keyword column.  <br />
    You can use any of the options for the header: Keyword, Focus topic, Category, Tags, Suggested Title, Intended URL, Example URL, Links to include, Words to Include, Notes.
    </td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="dataLabel"> Upload Keyword File</td>
    <td><input type="file" name="filename" id="filename" value="" size="60" /></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2" ><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;
    <input type="submit" value="Next" class="button" />
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