<?php /* Smarty version 2.6.11, created on 2015-12-24 09:39:58
         compiled from client_campaign/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'client_campaign/index.html', 34, false),array('modifier', 'date_format', 'client_campaign/index.html', 86, false),)), $this); ?>
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
<table align="center" width="100%" >
  <tr><td>
    <table border="0" cellspacing="0" cellpadding="0" class="homeTable" >
    <h2 class="clienthometitle" >Campaign Overview</h2></tr>
    <tr class="sortableTab" >
      <th nowrap class="table-left-2">Campaign Name</th>
      <th>Progress</th>
      <th nowrap>Project Manager</th>
      <th nowrap >Start Date</th>
      <th nowrap>Due Date</th>
      <th class="table-right-2">Action</th>
    </tr>
    <?php $_from = $this->_tpl_vars['reports']['report']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
    <tr  class="odd" >
      <td class="table-left-2"  ><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
      <td >
      <div class="clear" >
      <div>
      <a <?php if ($this->_tpl_vars['item']['is_pop_style_guide'] == 1): ?>onclick="javascript:show(<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
)"<?php endif; ?>href="/article/<?php if ($this->_tpl_vars['item']['total_article_approved']): ?>pending_article_list.php<?php else: ?>article_keyword_list.php<?php endif; ?>?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
">
        <div class="graph" valign="center">
            <strong class="bar"style='width: <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%;'><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
%</strong>
        </div>
      </a>
      </div>
      <div class="floatleft" >
       &nbsp;&nbsp;
              
       </div>
       </div>
      </td>
      <td  ><a href="mailto:<?php echo $this->_tpl_vars['user_info']['email']; ?>
"><?php echo $this->_tpl_vars['user_info']['email']; ?>
</a></td>
      <td  ><?php echo $this->_tpl_vars['item']['date_start']; ?>
</td>
      <td  ><?php echo $this->_tpl_vars['item']['date_end']; ?>
</td>
      <td nowrap class="table-right-2" ><?php if ($this->_tpl_vars['item']['percent'] > 0): ?><input type="button" onclick="window.location.href='/article/download_article_list.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
'" value="Download Latest Articles" class="button" /><?php endif; ?>&nbsp</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </table><br />
    <div class="pagingpaddingleft" ></div>
  </td></tr>
<?php if ($this->_tpl_vars['articles']): ?>
<tr>
  <td>
<table id="table-1" cellspacing="0" cellpadding="0"  class="homeTable" >
  <h2 class="clienthometitle" >Articles Awaiting Approval</h2>
  <tr class="sortableTab">
    <td nowrap class="columnHeadInactiveBlack table-left-2">No.</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Noflow Status</td>	
    <td nowrap class="columnHeadInactiveBlack table-right-2">Action</td>
  </tr>
  <?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
    <input type="hidden" name="article_id[]" id="article_id_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['article_id']; ?>
" />
	    <td class="table-left-2"><?php echo $this->_foreach['loop']['iteration']; ?>
</td>
    <td><a href="javascript:void(0)" onclick="showArticleDialog('<?php echo $this->_tpl_vars['item']['article_id']; ?>
', '<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
', '<?php echo $this->_tpl_vars['campaign_id']; ?>
')" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
        <td><?php echo $this->_tpl_vars['item']['campaign_name']; ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <td><?php echo $this->_tpl_vars['item']['copywriter']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['editor']; ?>
</td>
    <?php endif; ?>
    <td ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td ><?php echo $this->_tpl_vars['item']['article_number']; ?>
</td>
	<td><?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>
</td>
	<td><?php echo $this->_tpl_vars['noflow_status'][$this->_tpl_vars['item']['noflow_status']]; ?>
</td>	
    <td  nowrap class="table-right-2">
	    <input type="button" class="button" value="Review Article" onclick="javasript:window.location='/article/approve_article.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
&fmp=home';" />
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
  <td  width="100%" class="contentLabel table-right-2 table-left-2 tdAlignLeft" colspan="7" ><?php if ($this->_tpl_vars['articles']): ?>If you would like more information, please click <a href="/article/pending_article_list.php">here</a><?php else: ?>No Result<?php endif; ?></td>
  </tr>
</table>
  </td>
</tr>
<?php endif; ?>
  
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>