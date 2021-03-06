<?php /* Smarty version 2.6.11, created on 2015-03-23 15:06:10
         compiled from client_campaign/campaign_style_guide_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'client_campaign/campaign_style_guide_form.html', 103, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
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
function check_f_style_guide()
{
  var f = document.f_style_guide;
  tinyMCE.triggerSave(false,false);
  if (f.background.value.length == 0) {
    alert(\'Please enter background\');
    f.background.focus();
    return false;
  }

  if (f.launch_feature.value.length == 0) {
		  alert(\'Please enter Launth Features\');
		  f.launch_feature.focus();
		  return false;
  }

  if (f.challenge.value.length == 0) {
    alert(\'Please enter challenges\');
    f.challenge.focus();
    return false;
  }
  if (f.objective.value.length == 0) {
    alert(\'Please enter objectives\');
    f.objective.focus();
    return false;
  }
  if (f.message.value.length == 0) {
    alert(\'Please enter message\');
    f.message.focus();
    return false;
  }
  if (f.talking_point.value.length == 0) {
      alert(\'Please enter talking points\');
      f.talking_point.focus();
      return false;
  }

  if (f.style_influence.value.length == 0) {
      alert(\'Please enter style influences\');
      f.style_influence.focus();
      return false;
  }

  if (f.mandatory.value.length == 0) {
    alert(\'Please enter mandatories\');
    f.mandatory.focus();
    return false;
  }

  if (!isEmail(f.contact.value)) {
    alert(\'Invalid email address\');
    f.contact.focus();
    return false;
  }

  return true;
}
tinyMCEInit(\'background,launch_feature,challenge,audience,objective,message,talking_point,mandatory,style_influence,others\');
//-->
</script>
'; ?>


<div id="page-box1">
  <h2>Content Production Style Guide Information setting</h2>
  <div id="campaign-search" >
    <strong>Please enter the  Style Guide required information.</strong>
  </div>
  <div class="form-item popwindow-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <form action="" method="post"  name="f_style_guide"  <?php if ($this->_tpl_vars['js_check'] == true): ?>onsubmit="return check_f_style_guide()" <?php endif; ?>>
  <input type="hidden" name="style_id" id="style_id" value="<?php echo $this->_tpl_vars['info']['style_id']; ?>
" />
  <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['info']['campaign_id']; ?>
" />
  <tr>
    <td class="bodyBold" nowrap >Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="3"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Project Name:</td>
    <td><strong><?php echo $this->_tpl_vars['info']['campaign_name']; ?>
</strong></td>
  </tr>
  <tr>
    <td class="dataLabel">Contact:</td>
    <td><input name="contact" type="text" id="contact" value="<?php echo $this->_tpl_vars['info']['contact']; ?>
" /></td>
  </tr>
  <tr>
    <td class="dataLabel" >Date</td>
    <td><input name="date" type="text" id="date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
"  readonly/></td>
  </tr>
  <tr>
    <td class="requiredInput">
      Background:
    </td>
    <td>
      Tell us about your company, your site, your history and your goals<br />
      <textarea name="background" cols="50" rows="4" id="background"><?php echo $this->_tpl_vars['info']['background']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Launch Features:</td>
    <td>
      Where will the content be located? Are there complementary components that would be helpful to talk about? <br />
      <textarea name="launch_feature" cols="50" rows="4" id="launch_feature"><?php echo $this->_tpl_vars['info']['launch_feature']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">The Audience:</td>
    <td>
      Where will the content be located? Are there complementary components that would be helpful to talk about? <br />
      <textarea name="audience" cols="50" rows="4" id="audience"><?php echo $this->_tpl_vars['info']['audience']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Challenges</td>
    <td>
      Any possible brand complications or considerations <br />
      <textarea name="challenge" cols="50" rows="4" id="challenge"><?php echo $this->_tpl_vars['info']['challenge']; ?>
</textarea>
     </td>
  </tr>
  <tr>
    <td class="requiredInput">Objectives:</td>
    <td>
      What is the purpose of the content? Are you hoping to sell product, create a trusted brand, or simply inform? Tell us everything<br />
      <textarea name="objective" cols="50" rows="4" id="objective"><?php echo $this->_tpl_vars['info']['objective']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">The Message: </td>
    <td>
      The overall theme within your campaign<br />
      <textarea name="message" cols="50" rows="4" id="message"><?php echo $this->_tpl_vars['info']['message']; ?>
</textarea>
   </td>
  </tr>
  <tr>
    <td class="requiredInput">The Talking Points: </td>
    <td>
      Individual keywords or sentences that convey your brand identity, or anything you feel should be mentioned throughout the articles <br />
      <textarea name="talking_point" cols="50" rows="4" id="talking_point"><?php echo $this->_tpl_vars['info']['talking_point']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Mandatories:</td>
    <td>
      Must-have elements that you cannot live without <br />
      <textarea name="mandatory" cols="50" rows="4" id="mandatory"><?php echo $this->_tpl_vars['info']['mandatory']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Style Influences:</td>
    <td>
      Web sites you like, publications you admire, links to the tone/voice that grabs you <br />
      <textarea name="style_influence" cols="50" rows="4" id="style_influence"><?php echo $this->_tpl_vars['info']['style_influence']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="dataLabel">Anything else:</td>
    <td>
      Misc. notes to our writers or technical staff about what you want to see <br />
      <textarea name="others" cols="50" rows="4" id="others"><?php echo $this->_tpl_vars['info']['others']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="blackLine" colspan="3"><img src="/image/misc/s.gif"></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" class="button">&nbsp;<input type="reset" value="reset" class="button"></td>
  </tr>
  </form>
</table>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>