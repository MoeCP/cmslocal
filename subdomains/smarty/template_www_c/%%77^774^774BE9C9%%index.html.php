<?php /* Smarty version 2.6.11, created on 2014-10-09 05:12:29
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'index.html', 62, false),array('function', 'html_options', 'index.html', 92, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="JavaScript">
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
<?php endif;  echo '
/*
jQuery(document).ready(function() {
  jQuery("#faq_tab").accordion();
});
*/
'; ?>

</script>
<div class="container" >
<img src="/images/logo.jpg" height="101" width="300" />
<h1>CopyPress Editorial Application</h1>

<h2>Please do not fill out this application unless you have been accepted to work with CopyPress.<br />Writers and editors who have not been accepted to work with CopyPress should <a href="http://community.copypress.com/jobs/" class="headTextLink" target="_blank">visit our job board</a> to find opportunities to work with CopyPress.</h2>

<h2>For more information about what it means to work with CopyPress as a writer, please see our <a href="http://community.copypress.com/work-with-copypress-faqs/" class="headTextLink" target="_blank">Human Resource FAQs</a> and <a href="http://community.copypress.com/copypress-writers-guide/" class="headTextLink" target="_blank">Editorial Guide</a>. Please email <a href="mailto:Community@copypress.com" class="headTextLink" target="_blank">Community@copypress.com</a> with any further questions regarding employment with CopyPress.</h2><br />
<div id="div-allforms" >
<form action="" method="post"  name="f_candidate" id="f_candidate" enctype="multipart/form-data" >

  <ul class="tabs">
    <li class="active"><a href="#divBasic">Basic</a></li>
	<li><a href="#agreement">Agreement<br /></a></li>
        <li><a href="#terms_conditions">Terms &amp; Conditions<br /></a></li>
	  </ul>
  <div style="display: block;" class="tab_container" >
  <div id="divBasic" class="tab_content" >
  <h2>Basic Information<br /></h2>
<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $this->_tpl_vars['cid']; ?>
" />
<input type="hidden" name="opt_index" id="opt_index" value="0" />
<input type="hidden" name="work_in_us" value="1" />
<input type="hidden" name="weekly_hours" value="31-40" />
<table border="0" cellspacing="1" cellpadding="4" align="left">  
  <tr>
    <td class="tdStyle" ><span class="spanlabel">*</span>First Name</td>
    <td class="tdStyle" ><span class="spanlabel">*</span>Last Name</td>
  </tr>
  <tr>
    <td class="tdStyle" ><input name="first_name" type="text" id="first_name" value="<?php echo $this->_tpl_vars['info']['first_name']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
    <td  class="tdStyle"><input name="last_name" type="text" id="last_name" value="<?php echo $this->_tpl_vars['info']['last_name']; ?>
" onchange="javascript:this.value=Trim(this.value)" /></td>
  </tr>
  <tr>
    <td class="tdStyle" ><span class="spanlabel">*</span>Email Address</td>
    <td class="tdStyle" >Birthday</td>
  </tr>
  <tr>
    <td class="tdStyle"><input name="email" type="text" id="email" value="<?php echo $this->_tpl_vars['info']['email']; ?>
" onchange="javascript:this.value=Trim(this.value)" /></td>
    <td class="tdStyle"><input type="text" name="dob" id="dob" size="10" maxlength="10" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['info']['dob'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['default_date']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['default_date'])); ?>
" />
        <input type="button" class="button" id="btn_cal_dob" value="...">
        <script type="text/javascript">
        <?php echo '
        Calendar.setup({
            inputField  : "dob",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_dob",
            singleClick : true,
            date:'; ?>
"<?php echo $this->_tpl_vars['default_date']; ?>
",
            step        : 1,
            range     : [1900, <?php echo $this->_tpl_vars['this_year'];  echo ']
        });'; ?>

        </script></td>
  </tr>
  <tr>
    <td class="tdStyle" ><span class="spanlabel">*</span>Street Address</td>
    <td class="tdStyle" >Apt, Unit</td>
  </tr>
  <tr>
    <td class="tdStyle"><input name="address" type="text" id="address" value="<?php echo $this->_tpl_vars['info']['address']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="30" /></td>
    <td class="tdStyle"><input name="address_apt" type="text" id="address_apt" value="<?php echo $this->_tpl_vars['info']['address_apt']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="30" /></td>
  </tr>
  <tr>
    <td class="tdStyle" ><span class="spanlabel">*</span>City</td>
    <td class="tdStyle" ><span class="spanlabel"></span>State</td>
  </tr>
  <tr>
    <td class="tdStyle" ><input name="city" type="text" id="city" value="<?php echo $this->_tpl_vars['info']['city']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
    <td class="tdStyle"><select name="state" id="state" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['states'],'selected' => $this->_tpl_vars['info']['state']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="tdStyle" ><span class="spanlabel"></span>Zip</td>
    <td class="tdStyle" ><span class="spanlabel">*</span>Country</td>
  </tr>
  <tr>
    <td class="tdStyle" ><input name="zip" type="text" id="zip" value="<?php echo $this->_tpl_vars['info']['zip']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
    <td class="tdStyle"><select name="country" id="country"  onchange="changeCountry(jQuery(this).val())" onkeyup="changeCountry(jQuery(this).val())" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['country'],'output' => $this->_tpl_vars['country'],'selected' => $this->_tpl_vars['info']['country']), $this);?>
</select></td>    
  </tr>
 <tr>
    <td class="tdStyle" nowrap colspan="2" ><span class="spanlabel">*</span>Gender<input type="radio" name="sex" value="male" <?php if ($this->_tpl_vars['info']['sex'] == 'male'): ?>checked<?php endif; ?>>male
      <input type="radio" name="sex" value="female" <?php if ($this->_tpl_vars['info']['sex'] == 'female'): ?>checked<?php endif; ?>>female</td>
    <td></td>
  </tr>
<tr>
    <td class="tdStyle" nowrap colspan="2" ><span class="spanlabel">*</span><label for="what_team" >Which editorial team are you applying for? <select name="cpermission" id="cpermission" ><option value="">[Select]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['permissions'],'selected' => $this->_tpl_vars['info']['cpermission']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="tdStyle" nowrap colspan="2" ><span class="spanlabel">*</span><label for="first_language" >What is your first language?</label><select name="first_language" id="first_language" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['first_languages'],'selected' => $this->_tpl_vars['info']['first_language']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="tdStyle" nowrap colspan="2" ><label for="pay_pref" >Preferred Method of Payment</label><select name="pay_pref" id="pay_pref" onchange="changePayPref(jQuery(this).val())"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['payment_preference'],'selected' => $this->_tpl_vars['info']['pay_pref']), $this);?>
</select></td>
  </tr>
  <tr id="tr_paypal_email" style="display:none;">
    <td  class="tdStyle" nowrap colspan="2" ><label for="paypal_email" >Paypal Email Address</label><input name="paypal_email" type="text" id="paypal_email" value="<?php echo $this->_tpl_vars['info']['paypal_email']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="2" >
     <input type="button" value="Next" class="button" onclick="submitTabOne()">&nbsp;&nbsp;<small>Having trouble submitting the form? Send email to Community@copypress.com&nbsp;</small></td>
  </tr>
</table>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "agreement.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "terms.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <br />
</div>
</form>
</div>
</div>
<div id="ajaxresult" ></div>
<script type="text/javascript" src="/js/candidate.js"></script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
