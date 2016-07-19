<?php /* Smarty version 2.6.11, created on 2016-05-04 12:59:12
         compiled from client_campaign/cp_ranking_search.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_ranking_search.html', 58, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">
function search_choice() {

    var search = $("s_choice");
    for (var s = 0; s < search.length ; s++ )
    {
        if (s !=0 && search[s].selected == true)
        {
            window.location.href = "/client_campaign/cp_ranking_search.php?s_choice=" + search[s].value;
            break;
        }
    }
}
</script>
'; ?>

<div id="page-box1">
  <h2>Copywriter Campaign Ranking List</h2>
  <div id="campaign-search" >
  <strong>You can search copywriters' ranking</strong>
    <div id="campaign-search-box" >
    <form id="search" name="search" action="" method="post">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td nowrap>Campaign Keyword</td>
        <td><input type="text" name="keyword" id="search_keyword" value="<?php echo $_POST['keyword']; ?>
" /></td>
        <td nowrap>
        <input type="text" name="date_start_l" id="date_start_l" size="10" maxlength="10" value="<?php echo $_POST['date_end_l']; ?>
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
        <input type="text" name="date_start_r" id="date_start_r" size="10" maxlength="10" value="<?php echo $_POST['date_start_l']; ?>
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
        <td><select name="client_id"><option value="" >[all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['clients'],'selected' => $_POST['client_id']), $this);?>
</select></td>
        <?php else: ?>
        <td></td>
        <td></td>
        <?php endif; ?>
        <td nowrap>
          <input type="text" name="date_end_l" id="date_end_l" size="10" maxlength="10" value="<?php echo $_POST['date_end_l']; ?>
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
        <input type="text" name="date_end_r" id="date_end_r" size="10" maxlength="10" value="<?php echo $_POST['date_end_l']; ?>
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
    </table><br>
        </form>
    </div>
  </div>
</div>
<div class="tablepadding" >
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">campaign name</td>
    <td nowrap class="columnHeadInactiveBlack">copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">copywriter name</td>
    <td nowrap class="columnHeadInactiveBlack">Punctuation/Grammar/Spelling</td>
    <td nowrap class="columnHeadInactiveBlack">Quality of Writing</td>
    <td nowrap class="columnHeadInactiveBlack">Timeliness/Frequent Submissions </td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Ranking</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <?php $_from = $this->_tpl_vars['ranking_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <td class="table-left" >&nbsp;</td>
    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['user_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['first_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['readability']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['informational_quality']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['timeliness']; ?>
</td>
    <td class="table-right-2"><?php echo $this->_tpl_vars['item']['ranking']; ?>
</td>
    <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>