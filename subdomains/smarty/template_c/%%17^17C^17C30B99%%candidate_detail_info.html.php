<?php /* Smarty version 2.6.11, created on 2014-05-22 09:59:29
         compiled from user/candidate_detail_info.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'user/candidate_detail_info.html', 46, false),array('modifier', 'escape', 'user/candidate_detail_info.html', 213, false),array('modifier', 'nl2br', 'user/candidate_detail_info.html', 215, false),)), $this); ?>
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
  <h2>Candidate Information</h2>
  <div class="view-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan="2"></td>
  </tr>

  <tr>
    <td class="bodyBold">Basic Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput" width="15%">First Name&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['first_name'] != ''):  echo $this->_tpl_vars['candidate_res']['first_name'];  else: ?>n/a<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Last Name&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['last_name'] != ''):  echo $this->_tpl_vars['candidate_res']['last_name'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Sex&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['sex'] != ''):  echo $this->_tpl_vars['candidate_res']['sex'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Email&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['email'] != ''):  echo $this->_tpl_vars['candidate_res']['email'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Weekly Hours&nbsp;</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['candidate_res']['weekly_hours'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
  </tr>
   <tr>
    <td class="requiredInput"></td>
    <td><?php if ($this->_tpl_vars['candidate_res']['work_in_us'] == 1): ?>CopyPress is currently only hiring writers located in the United States, United Kingdom, Canada, and Australia. You are a citizen of one of these countries<?php else: ?>CopyPress is currently only hiring writers located in the United States, United Kingdom, Canada, and Australia. You are not a citizen of one of these countries<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Your First Language&nbsp;</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['candidate_res']['first_language'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Country</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['country'] != ''):  echo $this->_tpl_vars['candidate_res']['country'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">City&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['city'] != ''):  echo $this->_tpl_vars['candidate_res']['city'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">State&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['state'] != ''):  echo $this->_tpl_vars['candidate_res']['state'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Zip&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['zip'] != ''):  echo $this->_tpl_vars['candidate_res']['zip'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Address&nbsp;</td>
    <td><?php if ($this->_tpl_vars['candidate_res']['address'] != ''):  echo $this->_tpl_vars['candidate_res']['address'];  else: ?>n/a<?php endif; ?></td>
  </tr>
  <tr>
    <td class="requiredInput">Your links</td>
    <td>
    <?php if ($this->_tpl_vars['candidate_res']['plinks']): ?>
      <table id="table-2" class="sortableTable" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <td class="columnHeadInactiveBlack table-left-2" >Type</td>
          <td class="columnHeadInactiveBlack table-right-2" >	Link</td>
          <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
        </tr>
        <?php $_from = $this->_tpl_vars['candidate_res']['plinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
        <?php if ($this->_tpl_vars['row']['value']): ?>
        <tr>
          <td class="table-left" ></td>
          <td class="table-left-2"><?php echo $this->_tpl_vars['candidate_plinks'][$this->_tpl_vars['row']['type']]; ?>
</td>
          <td class="table-right-2"><?php echo $this->_tpl_vars['row']['value']; ?>
</td>
          <td class="table-right"></td>
        </tr>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
      </table>
    <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Specailties&nbsp;</td>
    <td>
      <?php if ($this->_tpl_vars['candidate_res']['categories'] != ''): ?>
        <table id="table-2" class="sortableTable" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td class="columnHeadInactiveBlack table-left-2" >Category</td>
            <td class="columnHeadInactiveBlack table-left-2" >URL</td>
            <td class="columnHeadInactiveBlack table-left-2" >Document</td>
            <td class="columnHeadInactiveBlack table-left-2" >	Relevant Experience</td>
            <td class="columnHeadInactiveBlack table-right-2" >Description</td>
            <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
          </tr>
          <?php $_from = $this->_tpl_vars['candidate_res']['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
          <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" name="loop" >
            <td class="table-left" >&nbsp;</td>
            <td class="table-left-2"><?php echo ((is_array($_tmp=@$this->_tpl_vars['row']['category'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
            <td ><?php echo ((is_array($_tmp=@$this->_tpl_vars['row']['link'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
            <td><?php if ($this->_tpl_vars['row']['fileField']['filename'] != ''): ?><a href="javascript:void(0)" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['candidate_res']['candidate_id']; ?>
&fd=candidate_categories&t=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['fileField']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&f=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['fileField']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['row']['fileField']['name']; ?>
</a><?php else: ?>n/a<?php endif; ?></td>
            <td class="table-left-2"><?php echo ((is_array($_tmp=@$this->_tpl_vars['user_levels'][$this->_tpl_vars['row']['level']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
            <td class="table-right-2"><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['row']['description'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            <td class="table-right" >&nbsp;</td>            
          </tr>
          <?php endforeach; endif; unset($_from); ?>
        </table>
        <?php if ($this->_tpl_vars['candidate_res']['is_categories_doc']): ?><input type="button" class="button" value="Download Documents" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['candidate_res']['candidate_id']; ?>
&fd=candidate_categories', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" /><?php endif; ?>  
      <?php else: ?>n/a<?php endif; ?>
    </td>
  </tr>

  <tr>
    <td class="requiredInput">Writing Samples&nbsp;</td>
    <td>
      <?php if ($this->_tpl_vars['candidate_res']['samples'] != ''): ?>
        <table id="table-2" class="sortableTable" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td class="columnHeadInactiveBlack table-left-2" >Sample Type</td>
            <td class="columnHeadInactiveBlack" >URL</td>
            <td class="columnHeadInactiveBlack table-right-2" >Document</td>
            <th class="table-right-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
          </tr>
          <?php $_from = $this->_tpl_vars['candidate_res']['samples']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
?>
          <?php if ($this->_tpl_vars['row']['link'] || $this->_tpl_vars['row']['fileField']['filename']): ?>
          <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>" name="loop" >
            <td class="table-left" >&nbsp;</td>
            <td class="table-left-2"><?php echo $this->_tpl_vars['sample_types'][$this->_tpl_vars['row']['type']]; ?>
</td>
            <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['row']['link'])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a')); ?>
</td>
            <td class="table-right-2"><?php if ($this->_tpl_vars['row']['fileField']['filename'] != ''): ?><a href="javascript:void(0)" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['candidate_res']['candidate_id']; ?>
&t=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['fileField']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&f=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['fileField']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['row']['fileField']['name']; ?>
</a><?php else: ?>n/a<?php endif; ?></td>
            <td class="table-right" >&nbsp;</td>            
          </tr>
          <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
        </table>
         <?php if ($this->_tpl_vars['candidate_res']['is_samples_doc']): ?><input type="button" class="button" value="Download Samples" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['candidate_res']['candidate_id']; ?>
', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" /><?php endif; ?>        
      <?php else: ?>n/a<?php endif; ?>
    </td>
  </tr>
  <tr>
    <td class="dataLabel" nowrap>Additional Comments&nbsp;</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['candidate_res']['comments'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td>Position</td>
    <td><?php echo $this->_tpl_vars['cpermissions'][$this->_tpl_vars['candidate_res']['cpermission']]; ?>
</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="2"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['candidate_res']['is_samples_doc'] || $this->_tpl_vars['candidate_res']['is_categories_doc']): ?>
  <tr>
    <td></td>
    <td><input type="button" class="button" value="Download All" onclick="javascript:openWindow('/user/sample_download.php?cid=<?php echo $this->_tpl_vars['candidate_res']['candidate_id']; ?>
&fd=all', 'height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" /></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>&nbsp;</td>
    <td align="" nowrap>
      <!--<input type="button" value="Close Window" class="button" onclick="window.close();">-->
    </td>
  </tr>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>