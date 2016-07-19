<?php /* Smarty version 2.6.11, created on 2012-06-25 23:06:47
         compiled from user/user_detail_info.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'user/user_detail_info.html', 61, false),array('modifier', 'default', 'user/user_detail_info.html', 74, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
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
<div id="page-box1">
  <h2>User's Information setting</h2>
  <div class="view-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan=2></td>
  </tr>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">User Name</td>
    <td><?php echo $this->_tpl_vars['user_info']['user_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">First Name</td>
    <td><?php echo $this->_tpl_vars['user_info']['first_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Last Name</td>
    <td><?php echo $this->_tpl_vars['user_info']['last_name']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['user_role'] == 'admin' || $this->_tpl_vars['user_role'] == 'project manager' || $this->_tpl_vars['login_user_id'] == $this->_tpl_vars['user_info']['user_id']): ?>
  <tr>
    <td class="requiredInput">Gender</td>
    <td><?php echo $this->_tpl_vars['user_info']['sex']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Email</td>
    <td><?php echo $this->_tpl_vars['user_info']['email']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['user_role'] == 'admin' || $this->_tpl_vars['user_role'] == 'project manager' || $this->_tpl_vars['user_role'] == 'agency' || $this->_tpl_vars['login_user_id'] == $this->_tpl_vars['user_info']['user_id']): ?>
  <tr>
    <td class="requiredInput">Address</td>
    <td><?php echo $this->_tpl_vars['user_info']['address']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Phone Number</td>
    <td><?php echo $this->_tpl_vars['user_info']['phone']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Mobile Telephone Number</td>
    <td><?php echo $this->_tpl_vars['user_info']['cell_phone']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Birthday</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['birthday'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Join Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['date_join'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['user_role'] == 'admin' || $this->_tpl_vars['user_role'] == 'project manager' || $this->_tpl_vars['login_user_id'] == $this->_tpl_vars['user_info']['user_id']): ?>
  <tr>
    <td class="dataLabel">Social Security Number</td>
    <td><?php echo $this->_tpl_vars['user_info']['social_security_number']; ?>
</td>
  </tr>
  <tr>
      <td class="dataLabel">Back Rounting Number</td>
      <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_info']['routing_number'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Account Number</td>
    <td><?php echo $this->_tpl_vars['user_info']['bank_info']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="dataLabel">Role</td>
    <td><?php echo $this->_tpl_vars['user_info']['role']; ?>
</td>
  </tr>
  <tr>
    <td class="dataLabel">Selected category</td>
    <td>
    <table cellspacing="0" align="left" cellpadding="1" class="sortableTable" width="99%">
      <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
      <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
        <td align="left"><strong><?php echo $this->_tpl_vars['item']['category']; ?>
</strong></td>
      </tr>
      <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
        <td>
          <?php if ($this->_tpl_vars['item']['children']): ?>
          <table>
            <?php $_from = $this->_tpl_vars['item']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['child']):
        $this->_foreach['loop2']['iteration']++;
?>
            <?php if ($this->_foreach['loop2']['iteration'] % 5 == 1): ?>
            <tr>
            <?php endif; ?>
        <td><label><?php echo $this->_tpl_vars['child']['category']; ?>
</label></td>
            <?php if ($this->_foreach['loop2']['iteration'] % 5 == 0): ?>
            </tr>
            <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
        </table>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
   	</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="close window" class="button" onclick="window.close();"></td>
  </tr>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>