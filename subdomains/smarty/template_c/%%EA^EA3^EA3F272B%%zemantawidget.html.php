<?php /* Smarty version 2.6.11, created on 2012-05-29 22:47:55
         compiled from article/zemantawidget.html */ ?>
<div>
    <link rel="stylesheet" type="text/css" href="/js/zemanta/zemanta-widget.css">
    <script src="/js/zemanta/jquery.js" type="text/javascript"></script>
    <?php echo '
    <script type="text/javascript">
	    jQuery.noConflict();
     // window.ZemantaGetAPIKey = function () {
      '; ?>

        // return '<?php echo $this->_tpl_vars['api_key']; ?>
';

      <?php echo '
      // }
    </script>
    '; ?>

  <div class="zemanta-wrap" id="editor-sidebar" >
    <div id='zemanta-sidebar'>
      <div id="zemanta-control" class="zemanta"></div>
      <div id="zemanta-message" class="zemanta">Loading Zemanta...</div>
      <div id="zemanta-filter" class="zemanta"></div>
      <div id="zemanta-gallery" class="zemanta"></div>
      <div id="zemanta-articles" class="zemanta"></div>
      <div id="zemanta-preferences" class="zemanta"></div>
    </div>
  </div>
  <div id="run-sidebar"><a href="" id="run-button" class="button">UPLOAD AN IMAGE</a></div>
</div>

<?php echo '
<script language="JavaScript">
(function($){
	$(\'#run-button\').one(\'click\', function () {
		$(this).unbind(\'click\').bind(\'click\', function(){ return false; });
		
		$(\'#run-sidebar\').fadeOut(\'default\', function(){
			$.getScript(\'/js/zemanta/jquery.zemanta.js\', function(){
				$.getScript(\'/js/zemanta/copypress.js\', function () {
					$(\'#editor-sidebar\').css(\'display\', \'block\');
				});
			});
		});
		return false;
	});
})(jQuery);
</script>
'; ?>