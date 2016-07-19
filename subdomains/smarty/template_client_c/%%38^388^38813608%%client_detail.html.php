<?php /* Smarty version 2.6.11, created on 2014-08-04 12:19:51
         compiled from client/client_detail.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link href="/js/prototype-window/themes/default.css" rel="stylesheet" type="text/css"/> 
<link href="/js/prototype-window/themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>
<div id="page-box1">
<h2>Client's Information <input type="button" class="button" value="Edit" onclick="showClientDialog(<?php echo $this->_tpl_vars['client_id']; ?>
)" /></h2>
<table border="0" cellspacing="0" cellpadding="0" class="sortableTable" width="50%" >
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr class="odd">
    <td class="requiredInput">User Name:</td>
    <td><?php echo $this->_tpl_vars['client_info']['user_name']; ?>
</td>
  </tr>
  <tr class="even" >
    <td class="requiredInput">Company Name:</td>
    <td><?php echo $this->_tpl_vars['client_info']['company_name']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="dataLabel">Company Address:</td>
    <td><?php echo $this->_tpl_vars['client_info']['company_address']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="requiredInput">City:</td>
    <td><?php echo $this->_tpl_vars['client_info']['city']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="requiredInput">Country:</td>
    <td><?php echo $this->_tpl_vars['client_info']['country']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="requiredInput">State:</td>
    <td><?php echo $this->_tpl_vars['client_info']['state']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="requiredInput">Zip:</td>
    <td><?php echo $this->_tpl_vars['client_info']['zip']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="dataLabel">Contact Name:</td>
    <td><?php echo $this->_tpl_vars['client_info']['contact_name']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="requiredInput">Email:</td>
    <td><?php echo $this->_tpl_vars['client_info']['email']; ?>
</td>
  </tr>
  <tr  class="even">
    <td class="dataLabel">Contact Phone:</td>
    <td><?php echo $this->_tpl_vars['client_info']['company_phone']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="dataLabel">Company Website:</td>
    <td><?php echo $this->_tpl_vars['client_info']['company_url']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="dataLabel">Billing Email:</td>
    <td><?php echo $this->_tpl_vars['client_info']['bill_email']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="dataLabel">Billing Office Phone:</td>
    <td><?php echo $this->_tpl_vars['client_info']['bill_office_phone']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="dataLabel">Technical Email:</td>
    <td><?php echo $this->_tpl_vars['client_info']['technical_email']; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="dataLabel">Technical Office Phone:</td>
    <td><?php echo $this->_tpl_vars['client_info']['technical_office_phone']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="requiredInput">Referrer Type:</td>
    <td><?php echo $this->_tpl_vars['referrer_types'][$this->_tpl_vars['client_info']['referrer_type']]; ?>
</td>
  </tr>
  <tr class="odd">
    <td class="requiredInput">Referrer Name:</td>
    <td><?php echo $this->_tpl_vars['client_info']['referrer_name']; ?>
</td>
  </tr>
  <tr class="even">
    <td class="dataLabel">Referrer Tracking:</td>
    <td><?php echo $this->_tpl_vars['client_info']['referrer_tracking']; ?>
</td>
  </tr>
</table>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '
<script type="text/javascript" >
function showClientDialog(client_id)
{
  
    var url = \'/client/ajax_client_set.php?client_id=\' + client_id + \'&f=detail\';
    showWindowDialog(url, 600, 500, "Edit Client Info.");
}
</script>
'; ?>
