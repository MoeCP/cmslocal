<?php /* Smarty version 2.6.11, created on 2012-03-11 21:17:13
         compiled from user/esign_tab.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'user/esign_tab.html', 18, false),array('modifier', 'nl2br', 'user/esign_tab.html', 22, false),)), $this); ?>
<div class="page-box1-class">
<h2>E-Sign Information Detail</h2>
</div>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan="2"></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="15"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php $_from = $this->_tpl_vars['egroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['egloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['egloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['esigngroup']):
        $this->_foreach['egloop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['egloop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="requiredInput">Email</td>
    <td><?php echo $this->_tpl_vars['esigngroup']['email']; ?>
</td>
    <td class="requiredInput">Title</td>
    <td><?php echo $this->_tpl_vars['esigngroup']['title']; ?>
</td>
    <td class="requiredInput">Created</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['esigngroup']['created'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M:%S") : smarty_modifier_date_format($_tmp, "%D %H:%M:%S")); ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Message</td>
    <td colspan="5" ><?php if ($this->_tpl_vars['esigngroup']['message'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['esigngroup']['message'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  endif; ?></td>
  </tr>
  <?php if ($this->_tpl_vars['esigngroup']['sub']): ?>
  <tr>
    <td class="requiredInput" style="text-align:left" colspan="10">E-Sign Documents</td>
  </tr>
  <tr>
    <td colspan="10" >
    <?php $_from = $this->_tpl_vars['esigngroup']['sub']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['eloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['eloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['esign']):
        $this->_foreach['eloop']['iteration']++;
?>
    <table border="0" cellspacing="1" cellpadding="4" align="center" width="90%">
      <tr>
        <td class="dataLabel">Title:</td>
        <td><?php echo $this->_tpl_vars['esign']['title']; ?>
</td>
        <td colspan="6" >
        <?php if ($this->_tpl_vars['esign']['estatus'] > 4 && $this->_tpl_vars['esign']['filename'] != ''): ?>
         Signed Document:&nbsp;<a href="/user/download.php?user_id=<?php echo $this->_tpl_vars['esigngroup']['user_id']; ?>
&f=<?php echo $this->_tpl_vars['esign']['filename']; ?>
" ><?php echo $this->_tpl_vars['esign']['filename']; ?>
</a>
        <?php endif; ?>
        </td>
      </tr>
      <tr>
        <td class="dataLabel">Sent Date:</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['esign']['sent'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M:%S") : smarty_modifier_date_format($_tmp, "%D %H:%M:%S")); ?>
</td>
        <td class="dataLabel">Signed Date:</td>
        <td><?php if ($this->_tpl_vars['esign']['signed'] != ''):  echo ((is_array($_tmp=$this->_tpl_vars['esign']['signed'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M:%S") : smarty_modifier_date_format($_tmp, "%D %H:%M:%S"));  else: ?>n/a<?php endif; ?></td>
        <td class="dataLabel">E-Sign Status:</td>
        <td><?php echo $this->_tpl_vars['estatuses'][$this->_tpl_vars['esign']['estatus']]; ?>
</td>
      </tr>
            <?php if ($this->_tpl_vars['esign']['sub']): ?>
      <tr>
        <td class="dataLabel">E-Sign Details:</td>
        <td colspan="20" >
          <table cellspacing="0" align="left" cellpadding="0" class="sortableTable">
            <tr>
              <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th class="table-left-2" >E-Sign Status</th>
              <th>Date</th>
              <th class="table-right-2" >Description</th>
                            <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
            </tr>
          <?php $_from = $this->_tpl_vars['esign']['sub']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['logloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['logloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['log']):
        $this->_foreach['logloop']['iteration']++;
?>
          <tr class="<?php if ($this->_foreach['logloop']['iteration'] % 2 == 0): ?>odd<?php else: ?>even<?php endif; ?>">
            <th class="table-left">&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td class="table-left-2"><?php echo $this->_tpl_vars['estatuses'][$this->_tpl_vars['log']['estatus']]; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['log']['created'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M:%S") : smarty_modifier_date_format($_tmp, "%D %H:%M:%S")); ?>
</td>
            <td class="table-right-2"><?php echo $this->_tpl_vars['log']['description']; ?>
</td>
            <th class="table-right">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      </tr>
          <?php endforeach; endif; unset($_from); ?>
          </table>
        </td>
      </tr>
      <?php endif; ?>
      <tr>
        <td class="blackLine" colspan="10"><img src="/image/misc/s.gif"></td>
      </tr>
      </table>
    <?php endforeach; endif; unset($_from); ?>
    </td>
   </tr>
   <?php endif; ?>
  <tr>
    <td class="blackLine" colspan="15"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>