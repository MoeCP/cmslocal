<?php /* Smarty version 2.6.11, created on 2014-12-30 05:44:57
         compiled from client_campaign/estimatecost.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/estimatecost.html', 20, false),array('modifier', 'number_format', 'client_campaign/estimatecost.html', 52, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert('<?php echo $this->_tpl_vars['feedback']; ?>
');
//-->
</script>
<?php endif; ?>

<br />
<div id="page-box1">
  <h2>Forecasting Report</h2>
  <div id="campaign-search" >
      <div id="campaign-search-box" >
        <form name="f_assign_keyword_return" action="/client_campaign/estimatecost.php" method="get">
        <table border="0" cellspacing="1" cellpadding="4">
          <tr>
            <td nowrap>Start Month</td>
            <td><select name="starttime"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $_GET['starttime']), $this);?>
</select></td>
            <td nowrap>Number of payroll you wanna show:</td>
            <td><input type="text" name="nofpayroll" id="nofpayroll" value="<?php echo $_GET['nofpayroll']; ?>
"></td>
            <td ><input type="image" src="/images/button-search.gif" value="submit" /></td>
            <td nowrap>&nbsp; </td>
            <td width="70%">&nbsp;</td>
          </tr>
          </form>
        </table>
        </form>
      </div>
  </div>
</div>

<br />
<div class="tablepadding"> 
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner">&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">Today</td>
	<?php $_from = $this->_tpl_vars['estcost']['nofprk']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loopkey'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loopkey']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loopkey']['iteration']++;
?>
	<td nowrap class="columnHeadInactiveBlack"><?php echo $this->_tpl_vars['item']; ?>
</td>
	<?php endforeach; endif; unset($_from); ?>
    <td nowrap class="columnHeadInactiveBlack">Total</td>
  </tr>
  </thead>
  <tbody>
  <tr>
	<td nowrap class="columnHeadInactiveBlack">Client Approved Cost</td>
	<td></td>
	<?php $_from = $this->_tpl_vars['estcost']['nofacost']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
	<td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 1) : number_format($_tmp, 1)); ?>
</td>
	<?php endforeach; endif; unset($_from); ?>
	<td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['estcost']['nofcatotalcost'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 1) : number_format($_tmp, 1)); ?>
</td>
  </tr>
  <tr>
	<td class="columnHeadInactiveBlack"># of articles</td>
	<td></td>
	<?php $_from = $this->_tpl_vars['estcost']['nofa']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
	<?php endforeach; endif; unset($_from); ?>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['estcost']['nofcatotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
  </tr>
  <tr><td colspan="<?php echo $this->_tpl_vars['estcost']['nofpayroll']+3; ?>
">&nbsp;</td></tr>

  <tr>
	<td nowrap class="columnHeadInactiveBlack">Est. Editor Approved Cost</td>
	<td colspan="<?php echo $this->_tpl_vars['estcost']['nofpayroll']+2; ?>
">$<?php echo ((is_array($_tmp=$this->_tpl_vars['estcost']['esteac'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 1) : number_format($_tmp, 1)); ?>
</td>
  </tr>
  <tr>
	<td class="columnHeadInactiveBlack"># of articles</td>
	<td colspan="<?php echo $this->_tpl_vars['estcost']['nofpayroll']+2; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['estcost']['nofoea'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
  </tr>
  <tr><td colspan="<?php echo $this->_tpl_vars['estcost']['nofpayroll']+3; ?>
">&nbsp;</td></tr>

  <tr>
	<td nowrap class="columnHeadInactiveBlack">Est. All Other Status Cost</td>
	<td colspan="<?php echo $this->_tpl_vars['estcost']['nofpayroll']+2; ?>
">$<?php echo ((is_array($_tmp=$this->_tpl_vars['estcost']['estaosc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 1) : number_format($_tmp, 1)); ?>
</td>
  </tr>
  <tr>
	<td class="columnHeadInactiveBlack"># of articles</td>
	<td colspan="<?php echo $this->_tpl_vars['estcost']['nofpayroll']+2; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['estcost']['nofoaos'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
  </tr>
  </tbody>
</table>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>