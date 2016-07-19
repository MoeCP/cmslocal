<?php /* Smarty version 2.6.11, created on 2013-05-14 07:28:39
         compiled from article/article_review.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'html_entity_decode', 'article/article_review.html', 35, false),array('modifier', 'date_format', 'article/article_review.html', 47, false),array('modifier', 'nl2br', 'article/article_review.html', 79, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['feedback'] != ''): ?>
<script language="JavaScript">
<!--
alert("<?php echo $this->_tpl_vars['feedback']; ?>
");
//-->
</script>
<?php endif; ?>
<div id="page-box1">
  <h2>Article Details Info</h2>
  <div class="view-item" >
<br />
<form action="/article/<?php if ($this->_tpl_vars['from'] != ''):  echo $this->_tpl_vars['from'];  else: ?>article_keyword_list<?php endif; ?>.php" name="campaign_keyword_list" id="campaign_keyword_list" method="post">
  <input type="hidden" name="keyword_id" />
  <input type="hidden" name="article_id" value="<?php echo $this->_tpl_vars['article_info']['article_id']; ?>
" />
  <input type="hidden" name="operation" value="recall"  />
  <input type="hidden" name="old_status" value="<?php echo $this->_tpl_vars['article_info']['article_status']; ?>
"  />
  <input type="hidden" name="query_string" value="<?php echo $this->_tpl_vars['query_string']; ?>
"  />
  <input type="hidden" name="form_refresh" value="N" />
<table border="0" cellspacing="1" cellpadding="4" align="center" width="95%">
  <tr>
    <td class="bodyBold" colspan="4">Current Article Version Information</td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  <tr>
    <td class="requiredInput">Campaign Name</td>
    <td><?php echo $this->_tpl_vars['article_info']['campaign_name']; ?>
</td>
    <td class="requiredInput">Campaign Keywords</td>
    <td><?php echo $this->_tpl_vars['article_info']['keyword']; ?>
</td>
  </tr>
  <tr>
    <td width="13%" class="requiredInput">Optional Field 1</td>
    <td width="10%"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['optional1'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
    <td width="13%" class="requiredInput">Optional Field 2</td>
    <td width="10%"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['optional2'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td width="13%" class="requiredInput">Optional Field 3</td>
    <td width="10%"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['optional3'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
    <td width="13%" class="requiredInput">Optional Field 4</td>
    <td width="10%"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword_info']['optional4'])) ? $this->_run_mod_handler('html_entity_decode', true, $_tmp) : html_entity_decode($_tmp)); ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Start Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td class="requiredInput">Due Date</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput"><?php if ($this->_tpl_vars['login_role'] != 'client'): ?>Copywriter<?php endif; ?></td>
    <td><?php if ($this->_tpl_vars['login_role'] != 'client'):  echo $this->_tpl_vars['keyword_info']['uc_name'];  endif; ?></td>
    <td class="requiredInput">Editor</td>
    <td><?php echo $this->_tpl_vars['keyword_info']['ue_name']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Article Title</td>
    <td colspan="3" ><?php echo $this->_tpl_vars['article_info']['title']; ?>
</td>
  </tr>
  <?php if ($this->_tpl_vars['keyword_info']['title_param'] == '1'): ?>
  <tr>
    <td class="requiredInput">HTML Title Tag</td>
    <td colspan="3" ><?php echo $this->_tpl_vars['article_info']['html_title']; ?>
</td>
  </tr>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['keyword_info']['meta_param'] == '1'): ?>
  <tr>
    <td class="requiredInput">Meta Keywords</td>
    <td colspan="3"><?php echo $this->_tpl_vars['keyword_info']['keyword_meta']; ?>
</td>
  </tr>
  <tr>
    <td class="requiredInput">Meta Description</td>
    <td colspan="3"><?php echo $this->_tpl_vars['keyword_info']['description_meta']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="requiredInput">Article Content</td>
    <td colspan="3"><?php if ($this->_tpl_vars['article_info']['richtext_body'] == ''):  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  else:  echo $this->_tpl_vars['article_info']['richtext_body'];  endif; ?></td>
  </tr>
  <tr>
    <td class="blackLine" colspan="4"><img src="/image/misc/s.gif"></td>
  </tr>
  <?php if ($this->_tpl_vars['permission'] == 1 && $this->_tpl_vars['article_info']['article_status'] == '1'): ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">
	    <input type="submit" value="Recall" class="button" />&nbsp;
     </td>
  </tr>
  <?php endif; ?>
</table>
</form>
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
    <td><?php echo $this->_tpl_vars['item']['creator']; ?>
</td>
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
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>