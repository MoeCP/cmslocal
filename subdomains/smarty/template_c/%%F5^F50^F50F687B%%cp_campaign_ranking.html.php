<?php /* Smarty version 2.6.11, created on 2012-03-19 23:37:16
         compiled from client_campaign/cp_campaign_ranking.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_campaign_ranking.html', 128, false),array('function', 'html_radios', 'client_campaign/cp_campaign_ranking.html', 212, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">
function checkRanking() {
    var r = $("ranking_info").readability;
    var q = $("ranking_info").informational_quality;
    var t = $("ranking_info").timeliness;
    var r_selected = false;
    var q_selected = false;
    var t_selected = false;
    for (var i =0; i < r.length ; i++)
    {
        if (r[i].checked == true)
        {
            r_selected = true;
            break;
        }
    }
    for (var j =0; j < q.length ; j++)
    {
        if (q[j].checked == true)
        {
            q_selected = true;
            break;
        }
    }
    for (var k =0; k < t.length ; k++)
    {
        if (t[k].checked == true)
        {
            t_selected = true;
            break;
        }
    }
    if (r_selected == false )
    {
        alert("Please choose a number of Punctuation/Grammar/Spelling");
    } else if (q_selected == false)
    {
       alert("Please choose a number of Quality of Writing");
    } else if (t_selected == false)
    {
       alert("Please choose a number of Timeliness/Frequent Submissions ");
    } else {
        

        if ($("campaign_id").value < 0)
        {
            alert("No Campaign, please try again");
            return false;
        }

        if (senIsObject($("copywriter_id")))
        {
          var cp_id = $("copywriter_id");
          for (var b = 0; b < cp_id.length; b++)
          {
              if (cp_id[b].selected == true)
              {
                  $("cp_id").value = cp_id[b].value;
                  break;
              }
          }
        }
        else
        {
            alert("No Copywriter, please try again");
            return false;
        }
        document.getElementById("opt").value = "save";
        $("ranking_info").submit();
    }
}

function senIsObject(obj)
{
    try {
      if (obj === undefined) return false;
      if (obj === null) return false;
      if (obj == "undefined") return false;
	  return true;
    } catch (e) {
		  return false;
    }
}
</script>
'; ?>

<div id="page-box1">
  <h2>Specify Copywriter Campaign Ranking</h2>
  <div id="campaign-search" >
    <strong>You can change the copywriter ranking quotiety on System Setting Page</strong>
    <div id="campaign-search-box" >
    <form name="f_campaign_ranking_return" action="" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td nowrap>Campaign Keyword</td>
        <td><input type="text" name="keyword" id="search_keyword" /></td>
        <td nowrap>
        <input type="text" name="date_start_l" id="date_start_l" size="10" maxlength="10" value="<?php echo $_GET['date_end_l']; ?>
" readonly/>
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_start_l",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
        &lt;= Start Date&lt;=
        <input type="text" name="date_start_r" id="date_start_r" size="10" maxlength="10" value="<?php echo $_GET['date_start_l']; ?>
" readonly/>
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_start_r",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
        </td>
        <td rowspan="2" ><input type="image" src="/images/button-search.gif" value="submit" /></td>
      </tr>
      <tr>
        <?php if ($this->_tpl_vars['user_permission_int'] >= 4): ?>
        <td nowrap>Client</td>
        <td><select name="client_id"><option value="" >[all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['clients'],'selected' => $_GET['client_id']), $this);?>
</select></td>
        <?php else: ?>
        <td></td>
        <td></td>
        <?php endif; ?>
        <td nowrap>
          <input type="text" name="date_end_l" id="date_end_l" size="10" maxlength="10" value="<?php echo $_GET['date_end_l']; ?>
" readonly/>
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_end_l",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
        &lt;=Due Date &lt;=
        <input type="text" name="date_end_r" id="date_end_r" size="10" maxlength="10" value="<?php echo $_GET['date_end_l']; ?>
" readonly/>
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "date_end_r",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
        </td>
      </tr>
    </table>
    </form>
    </div>
  </div>
  <div class="form-item" >
<form id="ranking_info" name="ranking_info" method="post">
  <input type="hidden" name="opt" id="opt" />
  <div id="ranking_id_area"><input type="hidden" name="ranking_id" id="ranking_id" value="<?php echo $this->_tpl_vars['ranking_id']; ?>
"/></div>
<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
  <tr>
    <td class="bodyBold">Basic Information</td>
    <td align="right" class="requiredHint">Required Information</td>
  </tr>
  <tr>
    <td class="requiredInput" >Campaign </td>
    <td colspan="4">
      <select name="campaign_id" id="campaign_id" onchange="ajaxAction('/client_campaign/cp_campaign_ranking_extra.php?cid=' + this.value + '&cp_id=<?php echo $this->_tpl_vars['cp_selected']; ?>
&type=2', 'copywriter');">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campaigns'],'selected' => $this->_tpl_vars['selected']), $this);?>

      </select>
    </td>
  </tr>
  <tr>
    <td class="requiredInput">Copywriter Name</td>
    <td colspan="4">
      <?php if ($this->_tpl_vars['article_id'] != '' && $this->_tpl_vars['keyword_id'] != ''): ?>        
        <input type="hidden" id="copywriter_id" name="copywriter_id" value="<?php echo $this->_tpl_vars['cp_selected']; ?>
" />
        <?php echo $this->_tpl_vars['cp_username']; ?>

      <?php else: ?>
        <input type="hidden" id="cp_id" name="cp_id" />
        <div name="copywriter" id ="copywriter"></div>
        <?php echo ' 
        <script type="text/javascript">
        function loadCp()
        {
            var parent = null;
            if (senIsObject($("campaign_id")) && $("campaign_id").value > 0)
            {
                ajaxAction("/client_campaign/cp_campaign_ranking_extra.php?cid=" + $("campaign_id").value + "&cp_id=';  echo $this->_tpl_vars['cp_selected'];  echo '&type=2", \'copywriter\');
            }
            else
            {
                setTimeout("loadCp()", 500);
            }
        }
        loadCp();
        </script>
        '; ?>
 
      <?php endif; ?>
    </td>
  </tr>
  <div id="ranking" name="ranking"></div>
  <tr>
    <td class="requiredInput">Punctuation/Grammar/Spelling </td>
    <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['cp_ranking'],'checked' => $this->_tpl_vars['ranking_info']['readability'],'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;",'name' => 'readability'), $this);?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Quality of Writing</td>
    <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['cp_ranking'],'checked' => $this->_tpl_vars['ranking_info']['informational_quality'],'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;",'name' => 'informational_quality'), $this);?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Timeliness/Frequent Submissions</td>
    <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['cp_ranking'],'checked' => $this->_tpl_vars['ranking_info']['timeliness'],'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;",'name' => 'timeliness'), $this);?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Comments</td>
    <td><div id="c"><textarea name="comments" id="comments" style="width: 630px; height: 200px;" ><?php echo $this->_tpl_vars['ranking_info']['comments']; ?>
</textarea></div></td>
  </tr>
  <?php echo ' 
  <script type="text/javascript">
  function loadRanking()
  {
      if (senIsObject($("copywriter_id")))
      {
            ajaxAction("/client_campaign/cp_campaign_ranking_extra.php?cid=" + $("campaign_id").value + "&cp_id=" + $("copywriter_id").value + "&type=3", \'ranking\');
      }
      else
      {
          setTimeout("loadRanking()", 500);
      }
  }
  
  </script>
  '; ?>
 
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