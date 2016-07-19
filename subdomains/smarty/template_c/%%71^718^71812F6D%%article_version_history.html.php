<?php /* Smarty version 2.6.11, created on 2013-05-14 07:29:15
         compiled from article/article_version_history.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/article_version_history.html', 22, false),array('modifier', 'default', 'article/article_version_history.html', 35, false),array('modifier', 'date_format', 'article/article_version_history.html', 40, false),array('modifier', 'html_entity_decode', 'article/article_version_history.html', 66, false),array('modifier', 'nl2br', 'article/article_version_history.html', 99, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>
<div id="page-box1">
  <h2>Article History Version</h2>
  <?php if ($this->_tpl_vars['versions']): ?>
  <div id="campaign-search" >
     <div id="campaign-search-box" >
    <input type="hidden" name="article_id" id="article_id" value="<?php echo $this->_tpl_vars['article_info']['article_id']; ?>
" />
    <input type="hidden" name="keyword_id" id="keyword_id" value="<?php echo $this->_tpl_vars['article_info']['keyword_id']; ?>
" />
    <input type="hidden" name="campaign_id" id="campaign_id" value="<?php echo $this->_tpl_vars['article_info']['campaign_id']; ?>
" />
    <table border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td>Article History Verstion</td>
        <td><select name="version_history_id" onchange="versionChange(this.value)" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['versions'],'selected' => $_GET['version_history_id']), $this);?>
</select></td>
      </tr>
    </table>
    </div>
  </div>
  <?php endif; ?>
  <div class="view-item" >
<br><table border="0" cellspacing="1" cellpadding="4" align="center" width="95%">
  <tr>
    <td class="bodyBold" colspan="4"> Article Status Timestamps</td>
  </tr>
  <tr>
    <td class="requiredInput">Article Status</td>
    <td><?php if ($this->_tpl_vars['article_info']['copy_writer_id'] > 0):  echo ((is_array($_tmp=@$this->_tpl_vars['article_status'][$this->_tpl_vars['article_info']['article_status']])) ? $this->_run_mod_handler('default', true, $_tmp, 'n/a') : smarty_modifier_default($_tmp, 'n/a'));  else: ?>Unassigned<?php endif; ?></td>
  </tr>
  <?php if ($this->_tpl_vars['article_info']['article_status'] != '0'): ?>
  <tr>
    <td class="requiredInput">Copywriter Submit Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S"));  if ($this->_tpl_vars['posted_by']['submitted']['opt_id'] > 0): ?> by <?php echo $this->_tpl_vars['posted_by']['submitted']['opt_name'];  endif; ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Editor Approve Date</td>
    <td><?php if ($this->_tpl_vars['article_info']['approval_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['article_info']['approval_date'] > 0 && $this->_tpl_vars['article_info']['approval_date'] != ''):  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['approval_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
 <?php if ($this->_tpl_vars['posted_by']['approved']['opt_id'] > 0): ?> by <?php echo $this->_tpl_vars['posted_by']['approved']['opt_name'];  endif;  elseif ($this->_tpl_vars['article_info']['client_approval_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['article_info']['client_approval_date'] > 0 && $this->_tpl_vars['article_info']['client_approval_date'] != ''):  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['client_approval_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S"));  if ($this->_tpl_vars['posted_by']['client_approved']['opt_id'] > 0): ?> by <?php echo $this->_tpl_vars['posted_by']['client_approved']['opt_name'];  endif;  endif; ?></td>
    <td class="requiredInput">Client Approve Date</td>
    <td><?php if ($this->_tpl_vars['article_info']['client_approval_date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['article_info']['client_approval_date'] > 0 && $this->_tpl_vars['article_info']['client_approval_date'] != ''):  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['client_approval_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S"));  if ($this->_tpl_vars['posted_by']['client_approved']['opt_id'] > 0): ?> by <?php if (( $this->_tpl_vars['login_role'] == 'editor' || $this->_tpl_vars['login_role'] == 'copy writer' ) && $this->_tpl_vars['posted_by']['client_approved']['opt_type'] == 1): ?>Client<?php else:  echo $this->_tpl_vars['posted_by']['client_approved']['opt_name'];  endif;  endif;  endif; ?></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="bodyBold" colspan="4">Current Article Version Information</td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['article_info']['campaign_name']; ?>
</td>
    <td class="requiredInput">Article Keywords</td>
    <td><?php echo $this->_tpl_vars['article_info']['keyword']; ?>
</td>
  </tr>
  <?php $_from = $this->_tpl_vars['optional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <?php if ($this->_foreach['loop']['iteration']%2 == 1): ?>
  <tr>
  <?php endif; ?>
  <td class="requiredInput"><?php echo $this->_tpl_vars['item']['label']; ?>
</td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  <?php if ($this->_foreach['loop']['iteration']%2 == 0 || $this->_foreach['loop']['iteration'] == $this->_tpl_vars['total_optional']): ?>
   </tr>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td class="requiredInput">Due Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Date Created</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
</td>
    <td class="requiredInput">Article Title</td>
    <td><?php echo $this->_tpl_vars['article_info']['title']; ?>
</td>
  </tr>
  <?php $_from = $this->_tpl_vars['custom_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <?php if ($this->_foreach['loop']['iteration']%2 == 1): ?>
  <tr>
  <?php endif; ?>
  <td class="requiredInput"><?php echo $this->_tpl_vars['item']['label']; ?>
</td>
  <td><?php echo $this->_tpl_vars['article_info'][$this->_tpl_vars['key']]; ?>
</td>
  <?php if ($this->_foreach['loop']['iteration']%2 == 0 || $this->_foreach['loop']['iteration'] == $this->_tpl_vars['total_custom']): ?>
   </tr>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
    <td class="requiredInput">Mapping-ID</td>
    <td><?php echo $this->_tpl_vars['article_info']['mapping_id']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Article Content</td>
    <td colspan="3"><?php if ($this->_tpl_vars['article_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['article_info']['richtext_body'];  endif; ?></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
</table>
<div id="article-coments" >
<?php if ($this->_tpl_vars['comment_count'] != 0): ?>
<table border="0" cellspacing="0" cellpadding="0" width="85%" class="comments-info" >
  <tr class="comments-head" >
    <td class="comments-head-left">&nbsp;</td>
    <td  colspan="8" ><span class="comments-header">Current Articles Comments Information</span></td>
    <td class="comments-head-right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" >
  <?php $_from = $this->_tpl_vars['article_info']['comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
     <table cellspacing="0" cellpadding="10" bordercolor="#999999" border="1" width="100%">
   <tr>
    <td>
    <table cellspacing="0" width="100%">
  <tr>
    <td></td>
    <td align="right" class="comments-label">Role: &nbsp;</td>
    <td><?php echo $this->_tpl_vars['item']['creation_role']; ?>
</td>
    <td align="right" class="comments-label">Creator: &nbsp;</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client' || $this->_tpl_vars['login_role'] == 'client' && $this->_tpl_vars['item']['creation_role'] != 'editor' && $this->_tpl_vars['item']['creation_role'] != 'copy writer'): ?>
    <td><?php if ($this->_tpl_vars['item']['creation_role'] == 'client'):  echo $this->_tpl_vars['item']['ccreator'];  else:  echo $this->_tpl_vars['item']['creator'];  endif; ?></td>
    <?php else: ?>
    <td><?php echo $this->_tpl_vars['item']['creation_role']; ?>
</td>
    <?php endif; ?>
    <td align="right" class="comments-label">Comment Date: &nbsp; </td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y %H:%M:%S") : smarty_modifier_date_format($_tmp, "%m-%d-%Y %H:%M:%S")); ?>
</td>
    <td align="right" class="comments-label">Version: &nbsp;</td>
    <td><?php echo $this->_tpl_vars['item']['version_number']; ?>
</td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="10" ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['comment'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    <td></td>
  </tr>
    </table>
     </td>
   </tr>
    </table>
  <?php endforeach; endif; unset($_from); ?>
    </td>
  </tr>
</table>
<?php endif; ?>
</div>
  </div>
</div>
<?php echo '
<script language="JavaScript">
function versionChange(history_id)
{
    if (history_id > 0){
        window.location.href="/article/article_version_history.php?version_history_id=" + history_id;
    } else{
        window.location.href = "/article/article_comment_list.php?article_id="+ $(\'article_id\').value;
    }
}
</script>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>