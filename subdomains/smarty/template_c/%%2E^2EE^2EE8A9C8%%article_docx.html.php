<?php /* Smarty version 2.6.11, created on 2015-08-01 19:35:13
         compiled from article/article_docx.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'article/article_docx.html', 31, false),array('modifier', 'strip', 'article/article_docx.html', 33, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body><div><span><strong>Article Title:</strong> </span><span><?php echo $this->_tpl_vars['article_info']['title']; ?>
</span></div><br />
<div><span><strong>Article Content: </strong></span><br /><span><?php if ($this->_tpl_vars['article_info']['richtext_body'] != ''):  echo $this->_tpl_vars['article_info']['richtext_body'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  endif; ?></span></div><br />
<?php if ($this->_tpl_vars['article_info']['template'] == '2'):  if (((is_array($_tmp=$this->_tpl_vars['article_info']['small_image'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Small Image: </strong></span><span><?php echo $this->_tpl_vars['article_info']['small_image']; ?>
</span></div><br /><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['large_image'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Large Image: </strong></span><span><?php echo $this->_tpl_vars['article_info']['large_image']; ?>
</span></div><br /><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['image_credit'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Image Credit: </strong></span><span><?php echo $this->_tpl_vars['article_info']['image_credit']; ?>
</span></div><br /><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['image_caption'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Image Caption: </strong></span><span><?php echo $this->_tpl_vars['article_info']['image_caption']; ?>
</span></div><br /><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['blurb'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Blurb: </strong></span><span><?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['blurb'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</span></div><br /><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['meta_description'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Meta description: </strong></span><span><?php echo $this->_tpl_vars['article_info']['meta_description']; ?>
</span></div><br /><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['category_id'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><div><span><strong>Category: </strong></span><span><?php echo $this->_tpl_vars['article_info']['category_id']; ?>
</span></div><br /><?php endif;  endif;  if ($this->_tpl_vars['article_info']['show_cp_bio'] == '1'): ?>
<div><span><strong>Author Bio: </strong></span><br /><span><?php echo $this->_tpl_vars['article_info']['cp_bio']; ?>
</span></div>
<?php endif; ?>
</body></html>