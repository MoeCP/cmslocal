<?php /* Smarty version 2.6.11, created on 2016-06-28 21:01:38
         compiled from client_campaign/keyword_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/keyword_list.html', 67, false),array('function', 'eval', 'client_campaign/keyword_list.html', 184, false),array('function', 'formatpayperiod', 'client_campaign/keyword_list.html', 220, false),array('modifier', 'nl2br', 'client_campaign/keyword_list.html', 192, false),array('modifier', 'strip', 'client_campaign/keyword_list.html', 192, false),array('modifier', 'escape', 'client_campaign/keyword_list.html', 192, false),array('modifier', 'truncate', 'client_campaign/keyword_list.html', 209, false),array('modifier', 'date_format', 'client_campaign/keyword_list.html', 214, false),)), $this); ?>
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
  <div id="campaign-actions" >
  <?php if ($this->_tpl_vars['is_pay_adjust'] == 1): ?>
   <div id="campaign-actions-label"> Adjust Keywords for Payment</div>
  <?php else: ?>
    <div id="campaign-actions-label">Campaign Actions: </div>
  <ul id="campaign-nav">
    <?php if (( $this->_tpl_vars['login_permission'] > 3 || $this->_tpl_vars['login_permission'] == 2 ) && $this->_tpl_vars['campaign_id'] > 0): ?>        <li><a href="/client_campaign/keyword_add.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
"><img alt="Add Keyword" src="/images/button-add-keyword.gif" /></a></li>
        <?php if ($this->_tpl_vars['login_permission'] > 4): ?>
        <li><a href="/client_campaign/uploadkeywordfile.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
"><img alt="Add Keyword by File" src="/images/button-upload-file.gif" /></a></li>
                <?php endif; ?>
        <li><a href="/client_campaign/batch_assign_keyword.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
" ><img alt="Batch Assignment" src="/images/button-batch-assignment.gif" /></a></li><?php endif; ?>
    <?php if (( $this->_tpl_vars['login_role'] == 'admin' || $this->_tpl_vars['login_role'] == 'client' ) && $this->_tpl_vars['campaign_id'] != ''): ?>
        <li><a href="/article/download_article_list.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
" ><img alt="Download Article" src="/images/button-download-article.gif" /></a></li>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
      <li><a href="javascript:void(0)" onclick="batchClientApprove()" ><img alt="Batch Client Approve" src="/images/button-batch-client-approve.gif" /></a></li>
      <li><a href="javascript:void(0)" onclick="batchEditorApprove()" ><img alt="Update Keyword Instructions" src="/images/button-batch-editor-action.gif"></a></li>
      <?php if ($this->_tpl_vars['campaign_id'] > 0): ?>
      <li><a href="/client_campaign/update_keyword_instructions.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
"><img alt="Update Keyword Instructions" src="/images/button-update-keyword-instructions.gif" /></a></li>
      <li><a href="/client_campaign/change_article_type.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
"><img alt="Change Article Type" src="/images/button-change-article-type.gif" /></a></li>
      <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['campaign_id'] > 0 && ( $this->_tpl_vars['login_permission'] > 3 || $this->_tpl_vars['login_permission'] == 2 ) && $this->_tpl_vars['login_role'] != 'client'): ?>
        <li><a  href="/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
" ><img alt="Update" src="/images/button-update.gif" /></a></li>
      <?php if ($this->_tpl_vars['login_permission'] != 4): ?>
        <li><a href="javascript:void(0)" onclick="return deleteSubmit('campaign_list', 'campaign_id', '<?php echo $this->_tpl_vars['campaign_id']; ?>
', 'D', 'This Client Campaign')" ><img alt="Delete" src="/images/button-delete.gif" /></a></li>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['login_permission'] >= 4): ?>
      <li><a href="/article/acceptance.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
"><img alt="Assignment Acceptance" src="/images/button-accept-assignment.png" /></a></li>
      <?php if ($this->_tpl_vars['login_permission'] == 5): ?>
      <li><a href="/client_campaign/deliver_keywords.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
"><img alt="Deliver Keywords" src="/images/batch-deliver-button.png" /></a></li>
      <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
  </ul>
  <form action="/client_campaign/list.php" name="campaign_list" method="post" >
      <input type="hidden" name="campaign_id" />
      <input type="hidden" name="form_refresh" value="N" />
       <input type="hidden" name="archived" id="archived" value="<?php echo $this->_tpl_vars['archived']; ?>
" />
  </form>
  <?php endif; ?>
  </div>
  <div id="campaign-search" >
    <strong>You can enter the "keyword","campaign name","company name" etc. into the keyword input to search the relevant campaign's keyword information</strong>
    <div id="campaign-search-box" >
    <form name="f_assign_keyword_return" id="f_assign_keyword_return" action="/client_campaign/keyword_list.php" method="get">
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
      <td nowrap>Copywriter</td>
      <td><select name="copy_writer_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_copy_writer'],'selected' => $_GET['copy_writer_id']), $this);?>
</select></td>
              <!-- ................&copy_writer_id..@_writer_id //-->
      <input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
            <?php if ($this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['login_role'] != 'copywriter'): ?>
      <input name="is_googlecheck" id="is_googlecheck" type="hidden" value="<?php echo $_GET['is_googlecheck']; ?>
"/>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
      <input name="is_forceclientapprove" id="is_forceclientapprove" type="hidden" value="<?php echo $_GET['is_forceclientapprove']; ?>
"/>
      <?php endif; ?>
      <td>QAer</td>
      <td><select name="qaer_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_qaers'],'selected' => $_GET['qaer_id']), $this);?>
</select></td>
      <td >Editor</td>
      <td><select name="editor_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_editor'],'selected' => $_GET['editor_id']), $this);?>
</select></td>
      <td nowrap>Article Type</td>
      <td colspan="1"><select name="article_type"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_type'],'selected' => $_GET['article_type']), $this);?>
</select>
      </td>
      <td nowrap>Status</td>
      <td colspan="2" nowrap>
        <select name="article_status" id="article_status" ><option value="">[show all]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['article_status'],'selected' => $_GET['article_status']), $this);?>
</select>&nbsp;&nbsp;&nbsp;
      </td>
      <td rowspan="2" >
        <input type="image" src="/images/button-search.gif" value="submit">
      </td>
    </tr>
    <tr>
       <td nowrap>Keyword</td>
       <td   ><input type="text" name="keyword" id="search_keyword"></td>
              <td>Client</td>
       <td><select name="client_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_clients'],'selected' => $_GET['client_id']), $this);?>
</select></td>
       <td nowrap>Campaign</td>
       <td nowrap><select name="campaign_id"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all_campaigns'],'selected' => $_GET['campaign_id']), $this);?>
</select></td>
	   <td nowrap>Progress</td>
	   <td><select name="noflow_status"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['noflow_statuses'],'selected' => $_GET['noflow_status']), $this);?>
</select></td>
       <td nowrap>Show:</td>
       <td nowrap>
        <select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)&nbsp;&nbsp;&nbsp;
        </td>
        <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    </form>
    </div>
  </div>
</div>
<br>
<div class="tablepadding"> 
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" name="campaign_keyword_list" method="post" />
<input type="hidden" name="skeyword_id" />
<input type="hidden" name="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
<input type="hidden" name="article_id" />
<input type="hidden" name="target_pay_month" />
<input type="hidden" name="current_month" />
<input type="hidden" name="google_approved_time" />
<input type="hidden" name="operation" id="operation" />
<input type="hidden" name="is_forced_adjust" id="is_forced_adjust" value="0" />
<input type="hidden" name="is_canceled" />
<input type="hidden" name="is_delay" />
<input type="hidden" name="form_refresh" value="N" />
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <th class="table-left-corner" rowspan="2" >&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <td nowrap class="columnHeadInactiveBlack table-left-2">
<?php if ($this->_tpl_vars['is_pay_adjust'] != 1 && $this->_tpl_vars['total']): ?>
    <?php if ($this->_tpl_vars['show_cb'] == true || ( $this->_tpl_vars['is_forceclientapprove'] == 1 && $_GET['article_status'] == '4' ) || ( $this->_tpl_vars['is_googlecheck'] == 1 && $this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['login_role'] != 'copywriter' && $_GET['article_status'] == '1' )): ?>
      <input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" />    
    <?php endif;  endif; ?>
</td>
    <td nowrap class="columnHeadInactiveBlack table-left-2 table-right-2">Number</td>
    <td nowrap class="columnHeadInactiveBlack">Keyword</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">Progress</td>
    <td nowrap class="columnHeadInactiveBlack">QA</td>
    <td nowrap class="columnHeadInactiveBlack">Company Name</td>
    <td nowrap class="columnHeadInactiveBlack">Campaign Name</td>
    <td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">QAer</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Editor Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Client Approve Date</td>
    <td nowrap class="columnHeadInactiveBlack">Deliver Date</td>
    <td nowrap class="columnHeadInactiveBlack">Pay Period</td>
    <td nowrap class="columnHeadInactiveBlack">Article Type</td>
    <td nowrap class="columnHeadInactiveBlack">Total Words</td>
    <?php if ($this->_tpl_vars['login_permission'] >= 5): ?>
    <td nowrap class="columnHeadInactiveBlack">Cost</td>
    <?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack table-right-2">&nbsp;</td>
    <th class="table-right-corner">&nbsp;&nbsp;&nbsp;</th>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td class="table-left" >&nbsp;</td>
  <td class="table-left-2" >
  <?php if ($this->_tpl_vars['is_pay_adjust'] != 1): ?>
    <?php if (( $this->_tpl_vars['is_forceclientapprove'] == 1 && $this->_tpl_vars['item']['article_status'] == '4' ) || $this->_tpl_vars['item']['article_status'] == '1gc' || $this->_tpl_vars['item']['article_status'] == '3' || ( $this->_tpl_vars['is_googlecheck'] == '1' && $this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['login_role'] != 'copywriter' && $_GET['article_status'] == '1' )): ?>
      <input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_foreach['loop']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', campaign_keyword_list)" />
    <?php endif; ?>
  <?php endif; ?>
      <input type="hidden" name="keyword_id[<?php echo $this->_foreach['loop']['iteration']; ?>
]" id="keyword_id_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
" />
    </td>
    <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td class="table-left-2 table-right-2" ><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
    <td>
            <?php if ($this->_tpl_vars['login_permission'] == 2 || $this->_tpl_vars['login_permission'] == 4 && ( $this->_tpl_vars['item']['article_status'] <= '1' || $this->_tpl_vars['item']['article_status'] == '1gd' )): ?>
	<?php echo $this->_tpl_vars['item']['keyword']; ?>

    <?php else: ?>
	<a href="/article/approve_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&<?php echo $this->_tpl_vars['query_string']; ?>
" onMouseOver="return overlib('<table width=500><tr><td nowrap>Keyword Instructions</td><td ><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['keyword_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td></tr></table>');" onMouseOut="return nd();"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
    <?php endif; ?>
    </td>
        <td><?php echo $this->_tpl_vars['item']['article_number']; ?>
</td>
        <td>
            <?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>

        </td>
    <td><?php echo $this->_tpl_vars['noflow_statuses'][$this->_tpl_vars['item']['noflow_status']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['g_qa'][$this->_tpl_vars['item']['qa_complete']]; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['company_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</td>
    <td><?php if ($this->_tpl_vars['login_permission'] == 5): ?><a href="/client_campaign/client_campaign_set.php?campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" target="_blank" ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "...")); ?>
</a><?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['campaign_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...") : smarty_modifier_truncate($_tmp, 20, "..."));  endif; ?></td>
    <td><?php if ($this->_tpl_vars['login_role'] == 'agency' || $this->_tpl_vars['login_permission'] == 4):  echo $this->_tpl_vars['item']['uc_name'];  else: ?><a href="javascript:;" class="js-cp-box-user" data-name="<?php echo $this->_tpl_vars['item']['uc_name']; ?>
" data-id="user<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
" title="Add to chat"><img alt="Chat with this user" src="/images/invite-2-chat.png"></a> <a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['uc_name']; ?>
</a><?php if ($this->_tpl_vars['item']['pay_level'] > 0): ?>&nbsp;[<?php echo $this->_tpl_vars['item']['pay_level']; ?>
]<?php endif;  endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['qaer_id'] > 0):  if ($this->_tpl_vars['login_role'] == 'agency' || $this->_tpl_vars['login_permission'] == 4):  echo $this->_tpl_vars['item']['uq_name'];  else: ?><a href="javascript:;" class="js-cp-box-user" data-name="<?php echo $this->_tpl_vars['item']['uq_name']; ?>
" data-id="user<?php echo $this->_tpl_vars['item']['qaer_id']; ?>
" title="Add to chat"><img alt="Chat with this user" src="/images/invite-2-chat.png"></a> <a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['qaer_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['uq_name']; ?>
</a><?php endif;  else: ?>n/a<?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['login_role'] == 'agency' || $this->_tpl_vars['login_permission'] == 4):  echo $this->_tpl_vars['item']['ue_name'];  else: ?><a href="javascript:;" class="js-cp-box-user" data-name="<?php echo $this->_tpl_vars['item']['ue_name']; ?>
" data-id="user<?php echo $this->_tpl_vars['item']['editor_id']; ?>
" title="Add to chat"><img alt="Chat with this user" src="/images/invite-2-chat.png"></a> <a href="javascript:void(0)" onclick="openWindow('/user/user_detail_info.php?user_id=<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', 'height=300,width=400,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');"><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</a><?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['approval_date'] > 0 && ( $this->_tpl_vars['item']['article_status'] == '3' || $this->_tpl_vars['item']['article_status'] == '4' || $this->_tpl_vars['item']['article_status'] == '5' || $this->_tpl_vars['item']['article_status'] == '6' )):  echo $this->_tpl_vars['item']['approval_date'];  endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['client_approval_date'] > 0 && ( $this->_tpl_vars['item']['article_status'] == '5' || $this->_tpl_vars['item']['article_status'] == '6' )):  echo $this->_tpl_vars['item']['client_approval_date'];  endif; ?></td>
    <td><?php echo $this->_tpl_vars['item']['delivered_date']; ?>
</td>
    <td><?php echo format_pay_period(array('pmonth' => $this->_tpl_vars['item']['pay_month']), $this);?>
</td>
    <td><?php echo $this->_tpl_vars['article_type'][$this->_tpl_vars['item']['article_type']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['word_count']; ?>
</td>
    <?php if ($this->_tpl_vars['login_permission'] >= 5): ?>
    <td><?php echo $this->_tpl_vars['item']['cost_for_article']; ?>
</td>
    <?php endif; ?>
    <td align="left" nowrap class="table-right-2">
    <?php if ($this->_tpl_vars['login_permission'] > 3): ?><!--      <input type="button" class="button" value="Notes" onclick="window.open('/client_campaign/notes.php?copy_writer_id=<?php echo $this->_tpl_vars['item']['copy_writer_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
&editor_id=<?php echo $this->_tpl_vars['item']['editor_id']; ?>
', '_notes', 'status=no,toolbar=no,menubar=no,location=no,resizable=yes');" />-->
	<?php if ($this->_tpl_vars['is_pay_adjust'] == 1): ?>
        <?php if ($this->_tpl_vars['cph_user']['payment_flow_status'] != 'cpc' && $this->_tpl_vars['cph_user']['payment_flow_status'] != 'paid' || $_GET['forced_adjust'] != '' && $this->_tpl_vars['cph_user']['payment_flow_status'] != 'paid'): ?>
            <?php if ($this->_tpl_vars['item']['is_canceled'] == '0'): ?>
                <?php if ($this->_tpl_vars['item']['pay_this_month']): ?>
                <input type="button" class="button" value="Add to This Pay Period" onclick="javascript:updateTargetPayMonth('<?php echo $this->_tpl_vars['item']['article_id']; ?>
','<?php echo $this->_tpl_vars['item']['google_approved_time']; ?>
','<?php echo $this->_tpl_vars['item']['target_pay_month']; ?>
', '<?php echo $this->_tpl_vars['monthes'][$this->_tpl_vars['month']]; ?>
', '2')" />
                <?php endif; ?>
                <?php if ($this->_tpl_vars['item']['add_to_this_month_button']): ?>
                <input type="button" class="button" value="Add to This Pay Period" onclick="javascript:updateTargetPayMonth('<?php echo $this->_tpl_vars['item']['article_id']; ?>
','<?php echo $this->_tpl_vars['item']['google_approved_time']; ?>
','<?php echo $this->_tpl_vars['item']['target_pay_month']; ?>
', '<?php echo $this->_tpl_vars['monthes'][$this->_tpl_vars['month']]; ?>
', '0')" />
                <?php endif; ?>
                <?php if ($this->_tpl_vars['item']['is_show_adjust_button']): ?><input type="button" class="button" value="Delay Payment" onclick="javascript:updateTargetPayMonth('<?php echo $this->_tpl_vars['item']['article_id']; ?>
','<?php echo $this->_tpl_vars['item']['google_approved_time']; ?>
','<?php echo $this->_tpl_vars['item']['target_pay_month']; ?>
', '<?php echo $this->_tpl_vars['monthes'][$this->_tpl_vars['month']]; ?>
', '1')" />
                <?php endif; ?>	
                <?php if ($this->_tpl_vars['item']['log_id'] <= 0 && ( $this->_tpl_vars['item']['target_pay_month'] == $this->_tpl_vars['month'] || $this->_tpl_vars['item']['target_pay_month'] <= 0 )): ?>
                <input type="button" class="button" value="Cancel Keyword" onclick="javascript:cancelKeyword('<?php echo $this->_tpl_vars['item']['article_id']; ?>
', '<?php echo $this->_tpl_vars['month']; ?>
', '1')" />
                <?php endif; ?>
            <?php else: ?>
                <input type="button" class="button" value="Active Keyword" onclick="javascript:cancelKeyword('<?php echo $this->_tpl_vars['item']['article_id']; ?>
', '<?php echo $this->_tpl_vars['month']; ?>
', '0')" />
            <?php endif; ?>
        <?php endif; ?>
	<?php else: ?>
        <?php if ($this->_tpl_vars['login_role'] != 'copy writer' && $this->_tpl_vars['item']['article_id'] > 0): ?>
    <?php if ($this->_tpl_vars['login_permission'] == 4 && ( $this->_tpl_vars['item']['article_status'] <= '1' || $this->_tpl_vars['item']['article_status'] == '1gd' )): ?>
    <?php else: ?>
    <input type="button" class="button" value="Preview" onclick="openLink('/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
');" />
    <?php endif; ?>
    <?php else: ?>
    <input type="button" class="button" value="Comment" onclick="openLink('/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
');" />
    <?php endif; ?>
        		<input type="button" class="button" value="Assign" onclick="openLink('/client_campaign/assign_keyword.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
');" />
    		<input type="button" class="button" value="Update" onclick="openLink('/client_campaign/keyword_set.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
');" />
    		<input type="submit" class="button" value="Delete" onclick="return deleteSubmit('campaign_keyword_list', 'skeyword_id', '<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
', 'D', 'This Campaign Keyword')" />
        <?php if ($this->_tpl_vars['item']['article_status'] == '5'): ?>
		<input type="submit" class="button" value="Publish" onclick="return doSubmit('campaign_keyword_list', 'article_id', '<?php echo $this->_tpl_vars['item']['article_id']; ?>
', 'P')" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['login_permission'] == 5 && $this->_tpl_vars['item']['delivered_date'] == '' && ( $this->_tpl_vars['item']['article_status'] == '4' || $this->_tpl_vars['item']['article_status'] == '5' || $this->_tpl_vars['item']['article_status'] == '6' )): ?>
     		<input type="submit" class="button" value="Deliver" onclick="return doSubmit('campaign_keyword_list', 'article_id', '<?php echo $this->_tpl_vars['item']['article_id']; ?>
', 'Deliver')" />
    <?php endif; ?>
	<?php endif; ?>
    <?php elseif ($this->_tpl_vars['login_permission'] == 2): ?>
      <input type="button" class="button" value="Preview" onclick="window.open('/article/article_comment_list.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
');" />
    <?php endif; ?>
     </td>
     <td class="table-right" >&nbsp;</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </tbody>
</table>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
)(Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
<table align="center" width="100%" >
</table>
<?php if ($this->_tpl_vars['total']): ?>
  <?php if ($this->_tpl_vars['show_cb'] == true): ?>
  <table>
  <tr>
    <td class="requiredInput">Comments:&nbsp;&nbsp;
    <td >
          <textarea name="comment" style="width: 700px; height: 160px;" id="comment"></textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" >
     <input type="submit" value="Edit Request" class="button" onclick="$('operation').value='editor_rejected';" >
     <?php if ($this->_tpl_vars['login_role'] == 'admin'): ?>
     <input type="submit" value="Editor Approve" class="button" onclick="$('operation').value='editor_approve';" >
     <?php endif; ?>
   </td>
  </tr>
 </table>
  <?php else: ?>
   <?php if (( $this->_tpl_vars['is_googlecheck'] == '1' && $this->_tpl_vars['login_role'] != 'client' && $this->_tpl_vars['login_role'] != 'copywriter' && $_GET['article_status'] == '1' )): ?>
   <table width="100%" >
    <tr>
    <td align="center" >
    <input type="submit" value="Google Clean" class="button" onclick="$('operation').value='1gc';" />
    <input type="submit" value="Google Duplicated" class="button" onclick="$('operation').value='1gd';" />
    </td>
    </tr>
    </table>
   <?php endif; ?>
   <?php if ($this->_tpl_vars['is_forceclientapprove'] == 1 && $this->_tpl_vars['login_role'] == 'admin' && $_GET['article_status'] == '4'): ?>
   <table width="100%" >
    <tr>
    <td align="center" >
		<div style="border: 2px dashed red; display: inline; padding: 10px 5px; margin: 0px 5px 0px 0px;font: italic bold 12px/20px arial,sans-serif;">
        <input type="submit" value="Force Client Approve" class="button" onclick="$('operation').value='client_approve';" /> With Pay Cycle:
		<select name="forcec_pay_month"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthes'],'selected' => $this->_tpl_vars['onemonthlater']), $this);?>
</select>
		</div>
    </td>
    </tr>
    </table>
    <?php endif; ?>
  <?php endif;  endif; ?>
  </form>
</div>
<script type="text/javascript">
//<![CDATA[

var st = new SortableTable(document.getElementById("table-1"),
  [<?php if ($this->_tpl_vars['is_pay_adjust'] != 1): ?>'None',<?php endif; ?>'None',"Number", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "Date", "Date", "Date", "Date", "String", "String", "String", "None"]);
<?php echo '
st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};
st.asyncSort(0);
function updateTargetPayMonth( article_id, google_approved_time, target_pay_month, current_month, is_delay )
{
	var f  = document.campaign_keyword_list;
	f.article_id.value = article_id;
	f.google_approved_time.value = google_approved_time;
	f.target_pay_month.value = target_pay_month;
	f.current_month.value = current_month;
	f.is_delay.value = is_delay;
  if ($(\'forced_adjust\').checked)
  {
    f.is_forced_adjust.value = 1;
  }
  else
  {
    f.is_forced_adjust.value = 0;
  }
	f.operation.value = \'move_to_next_pay_peried\';
	f.submit();
}

function cancelKeyword( article_id, current_month, is_canceled )
{
	var f = document.campaign_keyword_list;
	f.article_id.value = article_id;
	f.is_canceled.value = is_canceled;
	f.current_month.value = current_month;
	f.operation.value = \'cancel_keyword\';
	f.submit();
}

function showCurrentMonthKeywords()
{
	var f = document.f_assign_keyword_return;
	f.show_current_month.value = 1;
}

function batchGoogleCheck()
{
  $("is_googlecheck").value = 1;
  $("article_status").value = 1;
  $(\'f_assign_keyword_return\').submit();
}

function batchClientApprove()
{
    if (isObjectOrNot($(\'is_pay_adjust\')))
    {
        $(\'is_pay_adjust\').value = \'\';
    }
    $("is_forceclientapprove").value = 1;
    $("article_status").value = 4;
    $(\'f_assign_keyword_return\').submit();
}
function batchEditorApprove()
{
    if (isObjectOrNot($(\'is_pay_adjust\')))
    {
        $(\'is_pay_adjust\').value = \'\';
    }
    $("article_status").value = \'1gc\';
    $("is_forceclientapprove").value = 0;
    $(\'f_assign_keyword_return\').submit();
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>