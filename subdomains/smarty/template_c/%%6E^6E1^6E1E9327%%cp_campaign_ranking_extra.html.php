<?php /* Smarty version 2.6.11, created on 2012-03-19 23:37:17
         compiled from client_campaign/cp_campaign_ranking_extra.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'client_campaign/cp_campaign_ranking_extra.html', 3, false),)), $this); ?>
<?php if ($this->_tpl_vars['type'] != 3): ?>
<select name="<?php echo $this->_tpl_vars['name']; ?>
" id="<?php echo $this->_tpl_vars['name']; ?>
" <?php echo $this->_tpl_vars['action']; ?>
>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['all'],'selected' => $this->_tpl_vars['selected']), $this);?>

</select>
  <?php if ($this->_tpl_vars['type'] == 2): ?>
  <script type="text/javascript">
  loadRanking();
  </script>
  <?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['type'] != 1 && $this->_tpl_vars['type'] != 2): ?>
  <?php if ($this->_tpl_vars['ranking_info']['readability'] > 0 && $this->_tpl_vars['ranking_info']['informational_quality'] > 0 && $this->_tpl_vars['ranking_info']['timeliness'] > 0): ?>
  <script type="text/javascript">
  var read    = document.getElementsByName("readability");
  var quality = document.getElementsByName("informational_quality");
  var time    = document.getElementsByName("timeliness");
  for (var i = 0; i < read.length ; i++ )
  <?php echo '
  {
      '; ?>

      if ( read[i].value == <?php echo $this->_tpl_vars['ranking_info']['readability'];  echo ' )
      {   
          read[i].checked = true;
          break;
      }
  }
  for (var j = 0; j < quality.length ; j++ )
  {
      '; ?>

      if ( quality[j].value == <?php echo $this->_tpl_vars['ranking_info']['informational_quality']; ?>
 )
      <?php echo '
      {
          quality[j].checked = true;
          break;
      }
  }
  for (var k = 0; k < time.length ; k++ )
  {
      '; ?>

      if ( time[k].value == <?php echo $this->_tpl_vars['ranking_info']['timeliness']; ?>
 )
      <?php echo '
      {
          time[k].checked = true;
          break;
      }
  }'; ?>

  </script>
 <?php else: ?>
 <script type="text/javascript">
 
  var read    = document.getElementsByName("readability");
  var quality = document.getElementsByName("informational_quality");
  var time    = document.getElementsByName("timeliness");
  for (var i = 0; i < read.length ; i++ )
  <?php echo '
  if (read[i].value == 3)
  {
      read[i].checked = true;
  } else {
      read[i].checked = false;
  }

  for (var j = 0; j < quality.length ; j++ )
  { 
      if (quality[j].value == 3)
      {
          quality[j].checked = true;
      } else {
          quality[j].checked = false; 
      }
  }
  for (var k = 0; k < time.length ; k++ )
  { 
      if ( time[k].value == 3 )
      {
        time[k].checked = true;

      } else {
          time[k].checked = false;
      }
  }
  '; ?>

  </script>
   <?php endif; ?>
  <script>
  document.getElementById("c").innerHTML = "<textarea name='comments' id='comments' style='width: 630px; height: 200px;' ><?php echo $this->_tpl_vars['ranking_info']['comments']; ?>
</textarea>";
  document.getElementById("ranking_id_area").innerHTML = "<input type='hidden' name='ranking_id' id='ranking_id' value='<?php echo $this->_tpl_vars['ranking_info']['ranking_id']; ?>
'/>";
  </script>
<?php endif; ?>