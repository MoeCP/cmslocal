<?php /* Smarty version 2.6.11, created on 2015-12-11 16:36:05
         compiled from article/article_html.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'article/article_html.html', 1, false),array('modifier', 'strip', 'article/article_html.html', 3, false),)), $this); ?>
<?php if ($this->_tpl_vars['article_info']['richtext_body'] != ''):  echo $this->_tpl_vars['article_info']['richtext_body'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['article_info']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp));  endif;  if ($this->_tpl_vars['article_info']['template'] == '2'):  if (((is_array($_tmp=$this->_tpl_vars['article_info']['small_image'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Small Image: <?php echo $this->_tpl_vars['article_info']['small_image']; ?>
</p><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['large_image'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Large Image: <?php echo $this->_tpl_vars['article_info']['large_image']; ?>
</p><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['image_credit'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Image Credit: <?php echo $this->_tpl_vars['article_info']['image_credit']; ?>
</p><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['image_caption'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Image Caption: <?php echo $this->_tpl_vars['article_info']['image_caption']; ?>
</p><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['blurb'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Blurb: <?php echo ((is_array($_tmp=$this->_tpl_vars['article_info']['blurb'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['meta_description'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Meta description: <?php echo $this->_tpl_vars['article_info']['meta_description']; ?>
</p><?php endif;  if (((is_array($_tmp=$this->_tpl_vars['article_info']['category_id'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)) != ''): ?><p>Category: <?php echo $this->_tpl_vars['article_info']['category_id']; ?>
</p><?php endif;  endif;  if ($this->_tpl_vars['article_info']['show_cp_bio'] == '1'): ?>
<p>Author Bio: <?php echo $this->_tpl_vars['article_info']['cp_bio']; ?>
</p>
<?php endif; ?>