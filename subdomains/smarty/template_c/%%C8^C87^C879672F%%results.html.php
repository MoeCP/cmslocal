<?php /* Smarty version 2.6.11, created on 2012-04-02 18:29:46
         compiled from trends/results.html */ ?>
<table border="0" cellspacing="1" cellpadding="4" width="100%" class="divTrends">
<?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <?php if ($this->_tpl_vars['key']%2 == 0): ?><tr><?php endif; ?>
    <td width="50%" valign="top" class="tdTrends">
      <div class="divTrends" >
        <div class="divTrendLabel"><?php echo $this->_tpl_vars['item']['label']; ?>
</div>
        <div class="divTrendResults" >
        <?php $_from = $this->_tpl_vars['item']['rs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['subkey'] => $this->_tpl_vars['subitem']):
        $this->_foreach['subloop']['iteration']++;
?>
        <div class="divTrendResult">
        <table width="100%" <?php if ($this->_tpl_vars['subkey']%2 == 0): ?>class="grayrow"<?php endif; ?>>
        <tr  class="divTrendResultlink">
          <td width="70%">
          <?php if ($this->_tpl_vars['subitem']['link'] != ''): ?><a href="<?php echo $this->_tpl_vars['subitem']['link']; ?>
" target="_blank"><?php echo $this->_tpl_vars['subitem']['title']; ?>
</a><?php else:  echo $this->_tpl_vars['subitem']['title'];  endif; ?>
          </td>
          <td><?php echo $this->_tpl_vars['subitem']['pubDate']; ?>
</td>
        </tr>
        <tr class="divTrendResultContent">
          <td colspan="2"><?php echo $this->_tpl_vars['subitem']['content']; ?>
</td>
        </tr>
        </table>
        </div>
        <div>&nbsp;</div>
        <?php endforeach; endif; unset($_from); ?>
        </div>
      </div>
      <?php if ($this->_tpl_vars['item']['more'] != ''): ?>
      <div class="divTrendReadMore" >
        <a href="<?php echo $this->_tpl_vars['item']['more']; ?>
" target="_blank">More Results</a>
      </div>
      <?php endif; ?>
    </td>
  <?php if ($this->_tpl_vars['key']%2 == 1): ?></tr><?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</table>