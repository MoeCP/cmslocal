<?php /* Smarty version 2.6.11, created on 2014-08-21 16:09:07
         compiled from user/campaign_overview.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'user/campaign_overview.html', 21, false),array('modifier', 'default', 'user/campaign_overview.html', 26, false),)), $this); ?>
<div>
<h2>New Assignments</h2>
<?php if ($this->_tpl_vars['role'] == 'copy writer' || $this->_tpl_vars['role'] == 'editor'): ?>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="99%" class="campaign-table sortableTable" >
<tr>
  <th class="campaign-table-top-left" >Campaign Name</th>
  <th>Progress of Assignment Acceptance</th>
  <th><?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>Editor<?php else: ?>Project Manager<?php endif; ?></th>
  <th># of articles in progress</th>
  <!--<?php if ($this->_tpl_vars['role'] == 'editor'): ?>
  <th>Deadline</th>
  <?php endif; ?>-->
  <th class="campaign-table-top-right" >Action</th>
</tr>
<?php $_from = $this->_tpl_vars['reports']['new_report']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<tr  class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" >
  <td><?php echo $this->_tpl_vars['reports']['campaign'][$this->_tpl_vars['item']['campaign_id']]; ?>
</td>
  <td> 
  <a  href="/article/acceptance.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
">
    <div class="graph" valign="center">
        <strong class="bar" style='width: <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['pct_total_assigned'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%;'><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['pct_total_assigned'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%</strong>
    </div>
  </a>
  </td>
  <td><?php if ($this->_tpl_vars['role'] == 'copy writer'):  echo $this->_tpl_vars['reports']['campaign']['editor'][$this->_tpl_vars['item']['campaign_id']];  else:  echo $this->_tpl_vars['reports']['campaign']['pm'][$this->_tpl_vars['item']['campaign_id']];  endif; ?></td>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['working_on'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
  <!--<?php if ($this->_tpl_vars['role'] == 'editor'): ?>
  <td><?php echo $this->_tpl_vars['reports']['campaign']['date_end'][$this->_tpl_vars['item']['campaign_id']]; ?>
</td>
  <?php endif; ?>-->
  <td><a href="javascript:void(0)" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Style Guide</a></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<table border="0" cellspacing="0" cellpadding="0" align="center" width="99%" class="all-link-text">
  <tr><td align="right" >Click <a href="/article/acceptance.php" >here</a> to see all articles</td></tr>
</table>
<?php endif; ?>
</div>
<div>
<h2>Current Assignments</h2>
<?php if ($this->_tpl_vars['role'] == 'copy writer' || $this->_tpl_vars['role'] == 'editor'): ?>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="99%" class="campaign-table sortableTable" >
<tr>
  <th class="campaign-table-top-left" >Campaign Name</th>
  <th>Progress</th>
  <th><?php if ($this->_tpl_vars['role'] == 'copy writer'): ?>Editor<?php else: ?>Project Manager<?php endif; ?></th>
  <th># of articles in progress</th>
  <!--<?php if ($this->_tpl_vars['role'] == 'editor'): ?>
  <th>Deadline</th>
  <?php endif; ?>-->
  <th class="campaign-table-top-right" >Action</th>
</tr>
<?php $_from = $this->_tpl_vars['reports']['img_report']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<?php if ($this->_tpl_vars['item']['percent'] < 100): ?>
<tr  class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" >
  <td><?php echo $this->_tpl_vars['reports']['campaign'][$this->_tpl_vars['item']['campaign_id']]; ?>
</td>
  <td>      
    <a <?php if ($this->_tpl_vars['item']['is_pop_style_guide'] == 1): ?>onclick="javascript:show(<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
)"<?php endif; ?> href="/article/article_keyword_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
">
    <div class="graph" valign="center">
        <strong <?php if ($this->_tpl_vars['item']['total_rejected'] > 0): ?>class="rejectedbar"<?php else: ?>class="bar"<?php endif; ?> style='width: <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%;'><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%</strong>
    </div>
  </a>
  </td>
  <td><?php if ($this->_tpl_vars['role'] == 'copy writer'):  echo $this->_tpl_vars['reports']['campaign']['editor'][$this->_tpl_vars['item']['campaign_id']];  else:  echo $this->_tpl_vars['reports']['campaign']['pm'][$this->_tpl_vars['item']['campaign_id']];  endif; ?></td>
  <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['working_on'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
  <!--<?php if ($this->_tpl_vars['role'] == 'editor'): ?>
  <td><?php echo $this->_tpl_vars['reports']['campaign']['date_end'][$this->_tpl_vars['item']['campaign_id']]; ?>
</td>
  <?php endif; ?>-->
  <td><a href="javascript:void(0)" onclick="openWindow('/client_campaign/campaign_style_guide.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
', 'height=500,width=600,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"  >Style Guide</a></td>
</tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
<table border="0" cellspacing="0" cellpadding="0" align="center" width="99%" class="all-link-text">
  <tr><td align="right" >Click <a href="/client_campaign/ed_cp_campaign_list.php" >here</a> to see all campaigns</td></tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['role'] == 'designer' || $this->_tpl_vars['role'] == 'editor'): ?>
<?php endif; ?>
</div>