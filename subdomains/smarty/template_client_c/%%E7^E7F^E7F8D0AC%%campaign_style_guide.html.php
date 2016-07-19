<?php /* Smarty version 2.6.11, created on 2014-07-30 09:37:04
         compiled from client_campaign/campaign_style_guide.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'client_campaign/campaign_style_guide.html', 48, false),array('modifier', 'html_entity_decode', 'client_campaign/campaign_style_guide.html', 54, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>
<?php echo '
h1{
	font-size: 20px;
	margin: 10px 0;
}
h2{
	height: auto !important;
}
.divMain, .divContent, .divTextarea {
  width:90%;
  text-align:left;
  border: 1px solid #8f8377;
}

.divContent2 {
  background: #fff;
  color:#000;
  overflow:auto;
  border: 1px solid #8f8377;
  padding-left: 15px;
}

.divTextarea {
  height:100px;
}
'; ?>

</style>
<div id="page-box1">
  <h2>Content Production Style Guide Information</h2>
  <div class="view-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan="4"></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput2">Project Name:</td>
    <td colspan="3"><strong><?php echo $this->_tpl_vars['info']['campaign_name']; ?>
</strong></td>
  </tr>
  <tr>
    <td class="requiredInput2">Contact:</td>
    <td><?php echo $this->_tpl_vars['info']['contact']; ?>
</td>
    <td class="requiredInput2" >Date:</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['info']['background'] != ''): ?>
  <tr>
    <td colspan="4" >
      <div class="requiredInput2" >Background:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['background'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['launch_feature'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2" >Launch Features:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['launch_feature'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['audience'] != ''): ?>
  <tr>    
    <td colspan="4">
      <div class="requiredInput2">The Audience:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['audience'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['challenge'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2">Challenges</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['challenge'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
     </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['objective'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2">Objectives:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['objective'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['message'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2">The Message: </div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['message'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
   </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['talking_point'] != ''): ?>
  <tr>    
    <td colspan="4">
      <div class="requiredInput2">The Talking Points: </div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['talking_point'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['mandatory'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2">Mandatories:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['mandatory'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['style_influence'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2">Style Influences:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['style_influence'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['others'] != ''): ?>
  <tr>
    <td colspan="4">
      <div class="requiredInput2">Anything else:</div>
      <div class="divContent" ><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['others'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div>
    </td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['campaign_requirement'] != ''): ?>
  <tr>
    <td colspan="4">
      <div nowrap="nowrap" class="requiredInput2"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=ADDITIONAL_STYLE_GUIDE','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=no')" class="classtips">Additional Style Guide</a></div>
      <div <?php if ($this->_tpl_vars['info']['style_id'] > 0): ?>class="divContent"<?php else: ?>class="divContent2"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['campaign_requirement'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div></td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['info']['sample_content'] != ''): ?>
  <tr>
    <td colspan="4">
      <div nowrap="nowrap" class="requiredInput2"><a href="javascript:void(0)" onclick="openWindow('/manual_content/tip.php?ukey=SAMPLE_CONTENT','height=370,width=450,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes')" class="classtips">Sample Content</a></div>
      <div <?php if ($this->_tpl_vars['info']['style_id'] > 0): ?>class="divContent"<?php else: ?>class="divContent2"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['sample_content'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</div></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>