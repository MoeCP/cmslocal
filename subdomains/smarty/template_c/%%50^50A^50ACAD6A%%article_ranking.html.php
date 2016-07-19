<?php /* Smarty version 2.6.11, created on 2012-03-05 10:29:50
         compiled from client_campaign/article_ranking.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'client_campaign/article_ranking.html', 182, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <link href='/js/rating/jquery.rating.css' type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
<?php echo '
<script type="text/javascript">
jQuery.noConflict(); 
function checkRanking() {
    var punctuation = jQuery("input[name=\'punctuation\']:checked").val() || 0;
    var grammar = jQuery("input[name=\'grammar\']:checked").val() || 0;
    var structure = jQuery("input[name=\'structure\']:checked").val() || 0;
    var ap_style = jQuery("input[name=\'ap_style\']:checked").val() || 0;
    var style_guide = jQuery("input[name=\'style_guide\']:checked").val() || 0;
    var quality = jQuery("input[name=\'quality\']:checked").val() || 0;
    var communication = jQuery("input[name=\'communication\']:checked").val() || 0;
    var cooperativeness = jQuery("input[name=\'cooperativeness\']:checked").val() || 0;
    var timeliness = jQuery("input[name=\'timeliness\']:checked").val() || 0;
    var ranking = jQuery("#ranking").val() || 0;
    if ( ranking > 100) {
       alert("Please choose a total is more than 100, please to check you choose");
       jQuery("#ranking").val(100);
       jQuery("input[name=\'punctuation\']:radio").get(0).focus();
       return false;
    } else if ( punctuation == 0) {
        alert("Please choose a number of Punctuation");
        jQuery("input[name=\'punctuation\']:radio").get(0).focus();
        return false;
    } else if (grammar == 0) {
        alert("Please choose a number of Grammar");
        jQuery("input[name=\'grammar\']:radio").get(0).focus();
        return false;
    } else if (structure == 0) {
        alert("Please choose a number of Structure");
        jQuery("input[name=\'structure\']:radio").get(0).focus();
        return false;
    } else if (ap_style == 0) {
       alert("Please choose a number of AP Style");
        jQuery("input[name=\'ap_style\']:radio").get(0).focus();
        return false;
    } else if (style_guide == 0) {
       alert("Please choose a number of Style Guide");
        jQuery("input[name=\'style_guide\']:radio").get(0).focus();
        return false;
    } else if (quality == 0) {
        alert("Please choose a number of Overall Content Quality");
        jQuery("input[name=\'quality\']:radio").get(0).focus();
        return false;
    } else if (communication == 0) {
        alert("Please choose a number of Communication with Editor");
        jQuery("input[name=\'communication\']:radio").get(0).focus();
        return false;
    } else if (cooperativeness == 0) {
        alert("Please choose a number of Cooperativeness");
        jQuery("input[name=\'cooperativeness]:radio").get(0).focus();
        return false;
    } else if (timeliness == 0) {
        alert("Please choose a number of Timeliness ");
        jQuery("input[name=\'timeliness\']:radio").get(0).focus();
        return false;
    } else {
        jQuery("#opt").val("save");
        jQuery("#ranking_info").submit();
    }
    return false;
}

</script>
'; ?>


<div>
<?php $_from = $this->_tpl_vars['quotiety']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n'] => $this->_tpl_vars['v']):
?>
<input type="hidden" name="quotiety[<?php echo $this->_tpl_vars['n']; ?>
]" id="quotiety<?php echo $this->_tpl_vars['n']; ?>
" value="<?php echo $this->_tpl_vars['v']; ?>
" />
<?php endforeach; endif; unset($_from); ?>
</div>
<div id="page-box1">
  <h2>Specify Copywriter Article Ranking</h2>
  <div id="campaign-search" >
    <strong>You can change the copywriter ranking quotiety on System Setting Page</strong>
  </div>
  <div class="form-item" >
<form id="ranking_info" name="ranking_info" method="post">
  <input type="hidden" name="opt" id="opt" />
  <input type="hidden" name="ranking_id" id="ranking_id" value="<?php echo $this->_tpl_vars['ranking_info']['ranking_id']; ?>
"/>
  <input type="hidden" name="ranking" id="ranking" value="<?php echo $this->_tpl_vars['ranking_info']['ranking']; ?>
"/>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="requiredInput" >Keyword </td>
    <td colspan="4">
      <?php echo $this->_tpl_vars['ranking_info']['keyword']; ?>

      <input type="hidden" name="keyword_id" id="keyword_id" value="<?php echo $this->_tpl_vars['ranking_info']['keyword_id']; ?>
" />
    </td>
  </tr>
  <tr>
    <td class="requiredInput" >Campaign </td>
    <td colspan="4">
      <?php echo $this->_tpl_vars['ranking_info']['campaign_name']; ?>

      <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['ranking_info']['campaign_id']; ?>
" />
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Copywriter Name</td>
    <td colspan="4">
      <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->_tpl_vars['ranking_info']['user_id']; ?>
" />
      <?php echo $this->_tpl_vars['ranking_info']['user_name']; ?>

    </td>
  </tr>
  <tr>
    <td class="requiredInput">Punctuation</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="punctuation" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['punctuation'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Grammar</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="grammar" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['grammar'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Structure</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="structure" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['structure'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">AP Style</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="ap_style" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['ap_style'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Style Guide</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="style_guide" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['style_guide'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Overall Content Quality</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="quality" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['quality'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Communication with Editor</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="communication" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['communication'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>    
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Cooperativeness</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="cooperativeness" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['cooperativeness'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>   
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Timeliness</td>
    <td>
    <?php $_from = $this->_tpl_vars['cp_ranking']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <input type="radio" name="timeliness" value="<?php echo $this->_tpl_vars['v']; ?>
"  <?php if ($this->_tpl_vars['ranking_info']['timeliness'] == $this->_tpl_vars['v']): ?>checked<?php endif; ?>  />
    <?php endforeach; endif; unset($_from); ?>   
    </td>
  </tr>
  <tr><td colspan="12" align="center" ><div id="totaldiv" >Total:<?php echo ((is_array($_tmp=@$this->_tpl_vars['ranking_info']['ranking'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</div></td></tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" class="button" name="save" value="Save" onclick="checkRanking()"/></td>
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
<?php echo '
<script type="text/javascript">
jQuery("input:radio").each(function(index){
    jQuery(this).attr(\'class\', \'star\');
});
</script>
'; ?>

<script type="text/javascript" src="/js/rating/jquery.rating.js"></script>
<?php echo '
<script type="text/javascript">
var old_ratings = new Array();
function initRadios()
{
    jQuery("input:checked").each(function(index) {
        name = jQuery(this).attr(\'name\');
        quotiety = parseInt(jQuery("#quotiety" + name).val());
        value = parseInt(jQuery(this).val())/5.0;
        value *= quotiety;
        old_ratings[name] = value;
    });
}
initRadios();
jQuery(\'.star\').rating({required:true, callback:function(value,link){
    var total =jQuery(\'#ranking\').val() || 0;
    name = jQuery(this).attr(\'name\');
    quotiety = parseInt(jQuery("#quotiety" + name).val());
    value = parseInt(value)/5.0;
    value *= quotiety;
    total = parseInt(total);
    old_ratings[name] = old_ratings[name] || 0;
    if ( old_ratings[name] > 0) {
      total -= old_ratings[name];
    }
    total += parseInt(value);
    jQuery(\'#ranking\').val(total);
    old_ratings[name] = value;
    jQuery(\'#totaldiv\').html(\'Total&nbsp;:\' + total);
  }});
</script>
'; ?>
