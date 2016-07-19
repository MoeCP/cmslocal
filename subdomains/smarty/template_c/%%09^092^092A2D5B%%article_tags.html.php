<?php /* Smarty version 2.6.11, created on 2012-03-05 09:25:52
         compiled from article/article_tags.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'article/article_tags.html', 7, false),)), $this); ?>
<div class="form-item">
<table cellspacing="0" cellpadding="5" border="0" width="100%">
  <tr>
    <td align="right" class="form-label"><strong>Article Tag</strong></td>
    <td align="left">
<?php $_from = $this->_tpl_vars['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<input type="checkbox" id="article_tag<?php echo $this->_tpl_vars['key']; ?>
" onclick="ajaxSubmitTag('<?php echo $this->_tpl_vars['key']; ?>
', '<?php echo $this->_tpl_vars['article_id']; ?>
')" name="article_tag[]" value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['item']['selected']): ?>checked<?php endif; ?> /><label><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['output_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</label>
&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($this->_foreach['loop']['iteration'] % 6 == 0): ?>
<br />   
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
    </td>
   </tr>
</table>
</div>
<?php echo '
<script language="JavaScript">
function ajaxSubmitTag(tid, aid) 
{
    opt = $(\'article_tag\' + tid).checked? \'add\' : \'del\';
    //var element = {\'tag_id\':tid, \'article_id\': aid, \'opt\'};
    var query_str = \'tag_id=\' + tid + \'&article_id=\' +aid + \'&opt=\' + opt;
    ajaxAction(\'/article/add_article_tag.php\', \'\', {parameters:query_str}, \'post\');
}
</script>
'; ?>