<?php /* Smarty version 2.6.11, created on 2013-06-10 11:25:29
         compiled from client_campaign/article_ranking_quotiety.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script>
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
</script>
<?php endif; ?>
<?php echo '
<script language="JavaScript">
function check_quotiety()
{
    var p = $("punctuation").value;
    var g = $("grammar").value;
    var s = $("structure").value;
    var a = $("ap_style").value;
    var sg = $("style_guide").value;
    var q = $("quality").value;
    var c = $("communication").value;
    var coop= $("cooperativeness").value;
    var t = $("timeliness").value;
    var sum = Number(p) + Number(g) + Number(s) + Number(a) + Number(sg) + Number(q) + Number(c) + Number(coop) + Number(t);
    if (p == \'\') {
        alert("Please enter Punctuation");
    } else if (g == \'\') {
        alert("Please enter grammer");
    } else if (s == \'\') {
        alert("Please enter structure");
    } else if (a == \'\') {
        alert("Please enter AP style");
    } else if (sg == \'\') {
        alert("Please enter style guide");
    } else if (q == \'\') {
        alert("Please enter content quality");
    } else if (c == \'\') {
        alert("Please enter communication");
    } else if (coop == \'\') {
        alert("Please enter cooperativeness");
    } else if (t == \'\') {
        alert("Please enter Timeliness");
    } else if (sum != 100) {
        alert("Quotieties addition result should equal to 100");
    } else {
        if (sum < 100 ) {
            var last = 100 - Number(r) - Number(i);
            $("timeliness").value = last;
        }
        if (window.confirm("Are you sure to update the copywriter ranking quotiety?"))
        {
            $("operation").value = "save";
            $("ranking_quotiety").submit();
        }
    }
}
</script>
'; ?>

<div id="page-box1">
  <h2>Specify New Copywriter Ranking Quotity</h2>
  <div id="campaign-search" >
    <strong></strong>
  </div>
  <div class="form-item" >
<form action="" method="post" name="ranking_quotiety" id="ranking_quotiety" >
  <input type="hidden" name="operation" id="operation" value="" />
  <input type="hidden" name="punctuation_id" id="punctuation_id" value="<?php echo $this->_tpl_vars['punctuation']['pref_id']; ?>
" />
  <input type="hidden" name="grammar_id" id="grammar_id" value="<?php echo $this->_tpl_vars['grammar']['pref_id']; ?>
" />
  <input type="hidden" name="structure_id" id="structure_id" value="<?php echo $this->_tpl_vars['structure']['pref_id']; ?>
" />
  <input type="hidden" name="ap_style_id" id="ap_style_id" value="<?php echo $this->_tpl_vars['ap_style']['pref_id']; ?>
" />
  <input type="hidden" name="style_guide_id" id="style_guide_id" value="<?php echo $this->_tpl_vars['style_guide']['pref_id']; ?>
" />
  <input type="hidden" name="quality_id" id="quality_id" value="<?php echo $this->_tpl_vars['quality']['pref_id']; ?>
" />
  <input type="hidden" name="communication_id" id="communication_id" value="<?php echo $this->_tpl_vars['communication']['pref_id']; ?>
" />
  <input type="hidden" name="cooperativeness_id" id="cooperativeness_id" value="<?php echo $this->_tpl_vars['cooperativeness']['pref_id']; ?>
" />
  <input type="hidden" name="timeliness_id" id="timeliness_id" value="<?php echo $this->_tpl_vars['timeliness']['pref_id']; ?>
" />
  <table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="moduleTitle" colspan=2></td>
  </tr>
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan=2><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Punctuation:</td>
    <td><input type="text" id="punctuation" name="punctuation" value="<?php if ($this->_tpl_vars['punctuation']['pref_value'] > 0):  echo $this->_tpl_vars['punctuation']['pref_value'];  else: ?>5<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Grammar:</td>
    <td><input type="text" id="grammar" name="grammar" value="<?php if ($this->_tpl_vars['grammar']['pref_value'] > 0):  echo $this->_tpl_vars['grammar']['pref_value'];  else: ?>10<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Structure:</td>
    <td><input type="text" id="structure" name="structure" value="<?php if ($this->_tpl_vars['structure']['pref_value'] > 0):  echo $this->_tpl_vars['structure']['pref_value'];  else: ?>10<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">AP Style Violations:</td>
    <td><input type="text" id="ap_style" name="ap_style" value="<?php if ($this->_tpl_vars['ap_style']['pref_value'] > 0):  echo $this->_tpl_vars['ap_style']['pref_value'];  else: ?>10<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Style Guide Violations:</td>
    <td><input type="text" id="style_guide" name="style_guide" value="<?php if ($this->_tpl_vars['style_guide']['pref_value'] > 0):  echo $this->_tpl_vars['style_guide']['pref_value'];  else: ?>25<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Overall Content Quality :</td>
    <td><input type="text" id="quality" name="quality" value="<?php if ($this->_tpl_vars['quality']['pref_value'] > 0):  echo $this->_tpl_vars['quality']['pref_value'];  else: ?>15<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Communication with Editor:</td>
    <td><input type="text" id="communication" name="communication" value="<?php if ($this->_tpl_vars['communication']['pref_value'] > 0):  echo $this->_tpl_vars['communication']['pref_value'];  else: ?>10<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Cooperativeness:</td>
    <td><input type="text" id="cooperativeness" name="cooperativeness" value="<?php if ($this->_tpl_vars['cooperativeness']['pref_value'] > 0):  echo $this->_tpl_vars['cooperativeness']['pref_value'];  else: ?>5<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td class="requiredInput">Timeliness:</td>
    <td><input type="text" id="timeliness" name="timeliness" value="<?php if ($this->_tpl_vars['timeliness']['pref_value'] > 0):  echo $this->_tpl_vars['timeliness']['pref_value'];  else: ?>10<?php endif; ?>"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" class="button" name="save" value="Save" onclick="check_quotiety()"/></td>
  </tr>
</table>
</form>
  </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>