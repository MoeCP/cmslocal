<?php /* Smarty version 2.6.11, created on 2012-05-24 14:39:54
         compiled from article/article_details_info.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'article/article_details_info.html', 29, false),array('modifier', 'nl2br', 'article/article_details_info.html', 55, false),)), $this); ?>
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
<div id="page-box1">
  <h2>Article Details Info</h2>
  <div class="view-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="95%">
  <tr>
    <td class="bodyBold" colspan=4>Current Article Version Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=4><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['article_info']['campaign_name']; ?>
</td>
    <td class="requiredInput">Campaign Keywords</td>
    <td><?php echo $this->_tpl_vars['article_info']['keyword']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td class="requiredInput">Due Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['article_info']['tags']): ?>
  <tr>
    <td class="requiredInput">Tags</td>
    <td colspan="3" ><?php echo $this->_tpl_vars['article_info']['tags']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
  <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <td class="requiredInput">Copywriter</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['uc_name']; ?>
</td>
  <?php endif; ?>
    <td class="requiredInput">Editor</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['ue_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Date Created</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
</td>
    <td class="requiredInput">Project Manager</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['pm_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Article Content</td>
    <td colspan="3"><?php if ($this->_tpl_vars['article_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['article_info']['richtext_body'];  endif; ?></td>
  </tr>
  <tr>
    <td class="blackLine" colspan=4><img src="/image/misc/s.gif"></td>
  </tr>
</table>

<?php if ($this->_tpl_vars['comment_count'] != 0):  if ($this->_tpl_vars['login_role'] == 'client'): ?>
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="95%">
  <tr>
    <td class="bodyBold" colspan=8>Article Comments Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=8><img src="/image/misc/s.gif"></td>
  </tr>
  <?php $_from = $this->_tpl_vars['article_info']['comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <?php if ($this->_tpl_vars['item']['creation_role'] == 'client' || $this->_tpl_vars['item']['creation_role'] == 'editor'): ?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="requiredInput">Role</td>
    <td><?php echo $this->_tpl_vars['item']['creation_role']; ?>
</td>
    <td class="requiredInput">Creator</td>
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
    <td class="requiredInput">Comment Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
</td>
    <td class="requiredInput">Version</td>
    <td><?php echo $this->_tpl_vars['item']['version_number']; ?>
</td>
  </tr>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td colspan=8><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['comment'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
  </tr>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="blackLine" colspan=8><img src="/image/misc/s.gif"></td>
  </tr>
</table>
<?php else: ?>
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="95%">
  <tr>
    <td class="bodyBold" colspan=8>Article Comments Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=8><img src="/image/misc/s.gif"></td>
  </tr>
  <?php $_from = $this->_tpl_vars['article_info']['comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="requiredInput">Role</td>
    <td><?php echo $this->_tpl_vars['item']['creation_role']; ?>
</td>
    <td class="requiredInput">Creator</td>
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
    <td class="requiredInput">Comment Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
</td>
    <td class="requiredInput">Version</td>
    <td><?php echo $this->_tpl_vars['item']['version_number']; ?>
</td>
  </tr>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td colspan=8><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['comment'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="blackLine" colspan=8><img src="/image/misc/s.gif"></td>
  </tr>
</table>
<?php endif;  endif; ?>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>