<?php /* Smarty version 2.6.11, created on 2014-04-10 14:13:24
         compiled from user/candidate_edit.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'user/candidate_edit.html', 46, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header_jqjump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
<script language="JavaScript">
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
<?php endif;  echo '
</script>
'; ?>

<div id="page-box1" >
  <table>
    <tr>
      <td align="left"><h2>Candidate Form</h2></td>
    </tr>
    <tr><td colspan="2" >
      <div id="campaign-search" ><strong>Thank you for your interest in freelance writing and editing opportunities with CopyPress, a division of BlueGlass Interactive Inc. Our freelance team is made up of the brightest and best talent in the industry. Every author and editor at CopyPress understands the value of quality content, teamwork and community. A basic understanding and passion for these things is required. If you've never created quality content, don't value teamwork, prefer not to interact with others or can't tell us the difference between an adjective and noun, this isn't the company for you.<br /><br />Think you have what it takes to join the CopyPress team? Complete the application provided. If selected, we will be in contact with you.</strong> <br /><br /></div>
   </td></tr>
  </table>
  <ul class="tabs">
    <li class="active"><a href="#divBasic">Basic</a></li>
      </ul>
  <div style="display: block;" class="tab_container" >
  <div id="divBasic" class="tab_content">
  <h2>Basic Information<br /></h2>
<form action="" method="post"  name="f_candidate" <?php if ($this->_tpl_vars['js_check'] == true): ?> onsubmit="return check_f_candidate()"<?php endif; ?>>
<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $this->_tpl_vars['cid']; ?>
" />
<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
<input type="hidden" name="opt_index" value="0" />
<table border="0" cellspacing="1" cellpadding="4" align="left">  
  <tr>
    <td class="tdStyle" ><span class="spanlabel">*</span>First Name</td>
    <td class="tdStyle" ><span class="spanlabel">*</span>Last Name</td>
  </tr>
  <tr>
    <td class="tdStyle" ><input name="first_name" type="text" id="first_name" value="<?php echo $this->_tpl_vars['info']['first_name']; ?>
" onchange="javascript:this.value=Trim(this.value)" /></td>
    <td  class="tdStyle"><input name="last_name" type="text" id="last_name" value="<?php echo $this->_tpl_vars['info']['last_name']; ?>
" onchange="javascript:this.value=Trim(this.value)" /></td>
  </tr>
  <tr>
    <td class="tdStyle" ><span class="spanlabel">*</span>Email Address</td>
    <td class="tdStyle" >Country</td>

  </tr>
  <tr>
    <td class="tdStyle"><input name="email" type="text" id="email" value="<?php echo $this->_tpl_vars['info']['email']; ?>
" onchange="javascript:this.value=Trim(this.value)" /></td>
    <td class="tdStyle"><select name="country" id="country" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['country'],'output' => $this->_tpl_vars['country'],'selected' => $this->_tpl_vars['info']['country']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="tdStyle" >City</td>
    <td class="tdStyle" >State</td>
  </tr>
  <tr>
    <td class="tdStyle" ><input name="city" type="text" id="city" value="<?php echo $this->_tpl_vars['info']['city']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
    <td class="tdStyle"><select name="state" id="state" ><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['states'],'selected' => $this->_tpl_vars['info']['state']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="tdStyle" >Zip</td>
    <td class="tdStyle" >Address</td>
  </tr>
  <tr>
    <td class="tdStyle" ><input name="zip" type="text" id="zip" value="<?php echo $this->_tpl_vars['info']['zip']; ?>
" onchange="javascript:this.value=Trim(this.value)" size="20" /></td>
    <td class="tdStyle"><textarea name="address" id="address" cols="30" ><?php echo $this->_tpl_vars['info']['address']; ?>
</textarea></td>
  </tr>
  <tr>
    <td class="tdStyle" >Birthday</td>
    <td class="tdStyle" ></td>
  </tr>
  <tr>
    <td class="tdStyle" >
        <input name="dob" type="text" id="dob" readonly value="<?php echo $this->_tpl_vars['info']['dob']; ?>
" />
        <input type="button" value="..." id="btn_dob" class="button" />
        <?php echo '
        <script type="text/javascript">
        Calendar.setup({
          inputField  : "dob",
          ifFormat  : "%Y-%m-%d",
          showsTime   : false,
          button    : "btn_dob",
          singleClick : true,
          date:'; ?>
"<?php echo $this->_tpl_vars['info']['dob']; ?>
",
          step    : 1,
          range     : [1900, <?php echo $this->_tpl_vars['this_year'];  echo ']
         });
         </script>
        '; ?>

    </td>
    <td class="tdStyle"></td>
  </tr>
 <tr>
    <td class="tdStyle" nowrap colspan="2" ><span class="spanlabel">*</span>Gender<input type="radio" name="sex" value="male" <?php if ($this->_tpl_vars['info']['sex'] == 'male'): ?>checked<?php endif; ?>>male
      <input type="radio" name="sex" value="female" <?php if ($this->_tpl_vars['info']['sex'] == 'female'): ?>checked<?php endif; ?>>female</td>
    <td></td>
  </tr>
  <tr>
    <td class="tdStyle" colspan="2" ><span class="spanlabel">*</span><label for="work_in_us" >CopyPress is currently only hiring writers located in the United States, United Kingdom, Canada, and Australia. Are you a citizen of one of these countries?</label><input type="checkbox" name="work_in_us" id="work_in_us" value="1" <?php if ($this->_tpl_vars['info']['work_in_us'] == 1): ?>checked<?php endif; ?>/>Yes</td>
  </tr>
  <tr>
    <td class="tdStyle" nowrap colspan="2" ><span class="spanlabel">*</span><label for="what_team" >Which editorial team are you applying for? <select name="cpermission" id="cpermission" ><option value="">[Select]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['cpermissions'],'selected' => $this->_tpl_vars['info']['cpermission']), $this);?>
</select></td>
  </tr>
    <tr>
    <td class="tdStyle" nowrap colspan="2" ><span class="spanlabel">*</span><label for="work_in_us" >What is your first language?</label><select name="first_language" id="first_language" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['first_languages'],'selected' => $this->_tpl_vars['info']['first_language']), $this);?>
</select></td>
  </tr>
  <tr>
        <td class="tdStyle" nowrap colspan="2">Weekly Hours<select name="weekly_hours" id="weekly_hours" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['weekly_hours'],'selected' => $this->_tpl_vars['info']['weekly_hours']), $this);?>
</select></td>
  </tr>
  <tr>
    <td class="tdStyle" nowrap colspan="2" ><label for="pay_pref" >Preferred Method of Payment</label><select name="pay_pref" id="pay_pref" onchange="changePayPref(jQuery(this).val())"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['payment_preference'],'selected' => $this->_tpl_vars['info']['pay_pref']), $this);?>
</select></td>
  </tr>
  <tr id="tr_paypal_email" <?php if ($this->_tpl_vars['info']['pay_pref'] <> 3): ?>style="display:none;"<?php endif; ?>>
    <td  class="tdStyle" nowrap colspan="2" ><label for="paypal_email" >Paypal Email Address</label><input name="paypal_email" type="text" id="paypal_email" value="<?php echo $this->_tpl_vars['info']['paypal_email']; ?>
" onchange="javascript:this.value=Trim(this.value)"></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;Having trouble submitting the form? Send email to hr@copypress.com&nbsp;&nbsp;<input type="submit" value="Save" class="button">&nbsp;</td>
  </tr>
</table>
</form>
</div>
</div>
<div id="ajaxresult" ></div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer_jump.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="/js/candidate.js"></script>