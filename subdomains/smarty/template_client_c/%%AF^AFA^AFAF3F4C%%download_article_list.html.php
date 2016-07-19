<?php /* Smarty version 2.6.11, created on 2015-10-14 13:47:19
         compiled from article/download_article_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'article/download_article_list.html', 111, false),array('function', 'eval', 'article/download_article_list.html', 212, false),array('modifier', 'date_format', 'article/download_article_list.html', 225, false),)), $this); ?>
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

<?php echo '
<script language="JavaScript">
<!--
var f_common = "document.f_download.";
var f = document.f_download;
function check_f_download() {
  var is_checked;
  var f = document.f_download;
  var ua = document.getElementsByName(\'isUpdate[]\');
  var un = ua.length;

  for (i = 1; i <= un; i++) {
    var is_update_id = \'isUpdate_\' + i;
    if (document.getElementById(is_update_id).checked)
    {
        is_checked = true;
    }
  }

  if (!is_checked)
  {
    alert("Please choose articles");  
    return false;
  }

  if (f.mode.value.length == 0) {
    alert(\'Please enter download mode\');
	f.mode.focus();
    return false;
  }

  return true;
}
var prevWindow = null;
function openWindow2(url, params)
{
	var d = new Date();
  if (prevWindow != null)
  {
    prevWindow.close();
    prevWindow = null;
  }
  var windowname = \'newwindow\' +  (d.getTime());
  prevWindow = window.open(url, windowname , params);
	// prevWindow = window.open(url, \'newwindow\' +  (d.getTime()), params);
}

function modeChange(obj) 
{
  var selected_value = obj.options[obj.selectedIndex].value;
  var ua = document.getElementsByName(\'isUpdate[]\');
  var un = ua.length;
  if (selected_value == \'xml\' || selected_value == \'atom\')
  {
      var ids = \'\';
      var id  = \'\';
      var is_checked = false;
      for (i = 1; i <= un; i++) {
        var is_update_id = \'isUpdate_\' + i;
        if (isObjectOrNot(document.getElementById(is_update_id)) && document.getElementById(is_update_id).checked) {
          id = document.getElementById(\'article_id_\' + i).value;
          is_checked = true;
          ids += id + \';\';
        }
      }
      if (!is_checked) {
        alert("Please choose articles");  
        obj.selectedIndex = 0;
        return false;
      } 
      else 
      {
		if (selected_value == \'atom\') {
			obj.selectedIndex = 0;
			openWindow2(\'/article/atom.php?cid=';  echo $this->_tpl_vars['campaign_id'];  echo '&article_ids=\' + ids, \'height=400,width=500,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes\');
		} else {
			obj.selectedIndex = 0;
			openWindow2(\'/article/download_option.php?cid=';  echo $this->_tpl_vars['campaign_id'];  echo '&article_ids=\' + ids, \'height=400,width=500,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes\');
		}
      }
  }
}

//-->
</script>
'; ?>

<div id="page-box1">
  <h2>Articles List</h2>
  <div id="campaign-search" >
    <strong>You can enter the "campaign name","keyword","article content" etc. into the keyword input to search the relevant article's information, And you can download finished articles</strong>
     <div id="campaign-search-box" >
<form name="f_assign_keyword_return" id="f_assign_keyword_return" action="<?php echo $_SERVER['REQUEST_URI']; ?>
" method="get">
<input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $this->_tpl_vars['campaign_id']; ?>
" />
<table border="0" cellspacing="1" cellpadding="4">
  <tr>
    <td  nowrap>Keyword</td>
    <td><input type="text" name="keyword" id="search_keyword" size="25"/></td>
    <td  nowrap>SCID</td>
    <td><input type="text" name="subcid" id="subcid" size="25"/></td>
	<td  nowrap>Client Ready</td>
    <td><select name="is_client_ready"><option value="">[choose]</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['cr_options'],'selected' => $_GET['is_client_ready']), $this);?>
</select></td>
            <td>&nbsp;</td>
    <td rowspan="2" ><input type="image" src="/images/button-search.gif" value="submit" onclick="submitFunc()" /></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
          </tr>
  <tr nowrap>
    <td  nowrap>Submit Date Range:&nbsp;&nbsp;&nbsp;From:</td>
    <td>
<input type="text" name="submit_date_start" id="submit_date_start" size="15" maxlength="10" value="<?php echo $_GET['submit_date_start']; ?>
" readonly/>
        <input type="button" class="button" id="btn_cal_submit_date_start" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "submit_date_start",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_submit_date_start",
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
</td>
<td nowrap  >
        To:
</td>
<td>
<input type="text" name="submit_date_end" id="submit_date_end" size="15" maxlength="10" value="<?php echo $_GET['submit_date_end']; ?>
" readonly/>
        <input type="button" class="button" id="btn_cal_submit_date_end" value="...">
        <script type="text/javascript">
        Calendar.setup({
            inputField  : "submit_date_end",
            ifFormat    : "%Y-%m-%d",
            showsTime   : false,
            button      : "btn_cal_submit_date_end",
            singleClick : true,
            step        : 1,
            range       : [1990, 2030]
        });
        </script>
    </td>
    <td nowrap>Show:<select name="perPage" onchange="this.form.submit();"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['g_pager_perPage'],'selected' => $_GET['perPage']), $this);?>
</select> row(s)</td>
    <td width="10%">&nbsp;</td>
  </tr>
</table>
</form>
    </div>
  </div>
</div>
<div class="tablepadding"> 
<br/>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="30" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
) (Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td></tr>
  </table>
</div>
<form action="download_checked_article.php" name="f_download" method="post" <?php if ($this->_tpl_vars['js_check'] == true): ?>onSubmit="return check_f_download()"<?php endif; ?>>
<table id="table-1" cellspacing="0" cellpadding="0" align="center" class="sortableTable">
  <thead>
  <tr class="sortableTab">
    <td class="table-left-2" ><input type="checkbox" name="Select_All" title="Select All" onClick="javascript:checkAll('isUpdate[]')" /></td>
	<td nowrap class="columnHeadInactiveBlack">No.</td>
	<td nowrap class="columnHeadInactiveBlack">Topic</td>
    <td nowrap class="columnHeadInactiveBlack">Article Number</td>
    <td nowrap class="columnHeadInactiveBlack">Available</td>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?><td nowrap class="columnHeadInactiveBlack">Copywriter</td>
    <td nowrap class="columnHeadInactiveBlack">Editor</td>
	<td nowrap class="columnHeadInactiveBlack">Client Ready</td><?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Start Date</td>
    <td nowrap class="columnHeadInactiveBlack">Due Date</td>
    <td nowrap class="columnHeadInactiveBlack">Submit Date</td>
    <td nowrap class="columnHeadInactiveBlack">Deliver Date</td>
    <td nowrap class="columnHeadInactiveBlack">Status</td>
    <td nowrap class="columnHeadInactiveBlack">SCID</td>
    <?php if ($this->_tpl_vars['login_role'] == 'client'): ?><td nowrap class="columnHeadInactiveBlack">Is Client Ready</td><?php endif; ?>
    <td nowrap class="columnHeadInactiveBlack">Latest Download</td>
    <td nowrap class="columnHeadInactiveBlack table-right-2">Download</td>
  </tr>
  </thead>
  <tbody>
  <?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
  <tr class="<?php if ($this->_foreach['loop']['iteration'] % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
  <td class="table-left-2">
  <input type="hidden" name="article_id[]" id="article_id_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['item']['article_id']; ?>
" />
	<input type="checkbox" name="isUpdate[]" id="isUpdate_<?php echo $this->_foreach['loop']['iteration']; ?>
" value="<?php echo $this->_foreach['loop']['iteration']; ?>
" onclick="javascript:checkItem('Select_All', f_common)" <?php if ($this->_tpl_vars['item']['article_status'] == 5 || $this->_tpl_vars['item']['article_status'] == 4 || $this->_tpl_vars['item']['article_status'] == 6 || ( $this->_tpl_vars['cp_completed'] == 1 && $this->_tpl_vars['item']['article_status'] )): ?>&nbsp;<?php else: ?>disabled<?php endif; ?> /></td>
  <?php echo smarty_function_eval(array('var' => $this->_tpl_vars['startNo']+$this->_foreach['loop']['iteration'],'assign' => 'rowNumber'), $this);?>

    <td><?php echo $this->_tpl_vars['rowNumber']; ?>
</td>
	<?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
    <td><a href="/article/article_set.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&campaign_id=<?php echo $this->_tpl_vars['item']['campaign_id']; ?>
" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></td>
	<?php else: ?><td>
        <a href="/article/approve_article.php?keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" ><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a>
    </td><?php endif; ?>
    <td><?php if ($this->_tpl_vars['item']['article_id'] != 0): ?><a href="article_details_info.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
" ><?php echo $this->_tpl_vars['item']['article_number']; ?>
</a><?php else:  echo $this->_tpl_vars['item']['article_number'];  endif; ?></td>
    <td><?php if ($this->_tpl_vars['item']['article_id'] != 0): ?><font color="red">&radic;</font><?php else: ?>&times;<?php endif; ?></td>
    <?php if ($this->_tpl_vars['login_role'] != 'client'): ?><td><?php echo $this->_tpl_vars['item']['uc_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['ue_name']; ?>
</td>
	<td><?php if ($this->_tpl_vars['item']['is_client_ready'] == 1): ?>Yes<?php endif; ?></td><?php endif; ?>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_start'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['date_end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y")); ?>
</td>
    <td><?php if ($this->_tpl_vars['item']['article_status'] == '0' || $this->_tpl_vars['item']['article_status'] == ''): ?>n/a<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['item']['cp_updated'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td><?php echo $this->_tpl_vars['item']['delivered_date']; ?>
</td>
    <td><?php echo $this->_tpl_vars['article_status'][$this->_tpl_vars['item']['article_status']]; ?>
</td>
    <td><?php echo $this->_tpl_vars['item']['subcid']; ?>
</td>
    <?php if ($this->_tpl_vars['login_role'] == 'client'): ?><td><select name="is_client_ready<?php echo $this->_tpl_vars['item']['article_id']; ?>
" onchange='clientReadyPost(<?php echo $this->_tpl_vars['item']['article_id']; ?>
, this.value)'><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['client_ready_statuses'],'selected' => $this->_tpl_vars['item']['is_client_ready']), $this);?>
</select></td><?php endif; ?>
    <td><?php if ($this->_tpl_vars['item']['curr_dl_time'] != '0000-00-00 00:00:00'):  echo ((is_array($_tmp=$this->_tpl_vars['item']['curr_dl_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d-%Y") : smarty_modifier_date_format($_tmp, "%m-%d-%Y"));  endif; ?></td>
    <td nowrap class="table-right-2">
     <?php if ($this->_tpl_vars['item']['article_status'] == 5 || $this->_tpl_vars['item']['article_status'] == 4 || $this->_tpl_vars['item']['article_status'] == 6 || ( $this->_tpl_vars['cp_completed'] == 1 && $this->_tpl_vars['item']['article_status'] )): ?>
     <a href='/article/download_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&mode=html'>Html</a>
     &nbsp; | &nbsp;
     <a href='/article/download_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&mode=text'>Text</a> 
     &nbsp; | &nbsp;
     <a href='/article/download_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&mode=doc'>Doc</a>
     &nbsp; | &nbsp;
     <a href='/article/download_article.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&mode=docx'>Docx</a>	 
     &nbsp; | &nbsp;
     <a href="javascript:void(0);" onclick="javascript:openWindow2('/article/download_option.php?cid=<?php echo $this->_tpl_vars['campaign_id']; ?>
&article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
', 'height=400,width=500,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');" >XML</a>
     &nbsp; | &nbsp;
     <a href='/article/atom.php?article_id=<?php echo $this->_tpl_vars['item']['article_id']; ?>
&keyword_id=<?php echo $this->_tpl_vars['item']['keyword_id']; ?>
&mode=atom'>Atom</a>	 
     <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  </tbody>
  <tr>
    <td colspan="4" align="right" class="table-left-2"><b>Download ALL completed articles(zip):</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td <?php if ($this->_tpl_vars['login_role'] == 'client'): ?>colspan="7"<?php else: ?>colspan="9"<?php endif; ?> align="left" nowrap class="table-right-2">
        <a href='/article/download_campaign.php?mode=html_zip<?php echo $this->_tpl_vars['qstring']; ?>
' target="_blank">Html</a>
    &nbsp; | &nbsp;
    <a href='/article/download_campaign.php?mode=text_zip<?php echo $this->_tpl_vars['qstring']; ?>
' target="_blank">Text</a> 
    &nbsp; | &nbsp;
    <a href='/article/download_campaign.php?mode=doc_zip<?php echo $this->_tpl_vars['qstring']; ?>
' target="_blank">Doc</a> 
    &nbsp; | &nbsp;
    <a href='/article/download_campaign.php?mode=docx_zip<?php echo $this->_tpl_vars['qstring']; ?>
' target="_blank">Docx</a> 	
    &nbsp; | &nbsp;
    <a href='javascript:void(0);' onclick="javascript:openWindow2('/article/download_option.php?cid=<?php echo $this->_tpl_vars['campaign_id'];  echo $this->_tpl_vars['qstring']; ?>
', 'height=400,width=500,status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes');">XML</a> 
    &nbsp; | &nbsp;
    <a href='/article/atom.php?mode=atom&cid=<?php echo $this->_tpl_vars['campaign_id'];  echo $this->_tpl_vars['qstring']; ?>
' target="_blank">Atom</a> 	
      </td>
    </tr>
  <tr>
    <td class="table-left-2"  align="right" colspan="4" >Choose Download Format&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td align="left" <?php if ($this->_tpl_vars['login_role'] == 'client'): ?>colspan="11"<?php else: ?>colspan="14"<?php endif; ?> class="table-right-2"><select name="mode" onchange="javascript:modeChange(this);" ><option value="html_zip">Html</option><option value="text_zip">Text</option><option value="doc_zip">Doc</option><option value="docx_zip">Docx</option><option value="xml">XML</option><option value="atom">Atom</option></select>&nbsp;&nbsp;<input type="submit" class="button" value="Download ALL checked articles(zip)" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Download ALL completed articles as xls" onclick="window.location.href='/article/download_campaign_xls.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
'" />&nbsp;&nbsp;</td>
    </tr>

	<?php if ($this->_tpl_vars['login_role'] != 'client'): ?>
	<tr>
	<td class="table-left-2" align="right" colspan="4" >Download Articles AS Excel Format&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class="table-right-2" align="left" colspan="14" >
		<input type="button" class="button" value="Download editor approved articles as xls" onclick="window.location.href='/article/download_campaign_xls.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
&article_status=4'" />&nbsp;&nbsp;
		<input type="button" class="button" value="Download ALL articles as xls" onclick="window.location.href='/article/download_campaign_xls.php?campaign_id=<?php echo $this->_tpl_vars['campaign_id']; ?>
&dlall=1'" />
    </td>
    </tr>
	<?php endif; ?>
  </form>
</table>
<div class="pagingpaddingleft" >
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr><td class="table-left table-paging-left" width="20" height="37" >&nbsp;</td><td class="table-bottom"><?php echo $this->_tpl_vars['pager']; ?>
 (Total Page:<?php echo $this->_tpl_vars['total']; ?>
) (Total Count:<?php echo $this->_tpl_vars['count']; ?>
)</td><td class="table-right table-paging-right" width="21">&nbsp;</td></tr>
  </table>
</div>
</div>
<div id="div_client_ready"></div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
var st = new SortableTable(document.getElementById("table-1"),
  ["None", "None", "Number","CaseInsensitiveString","CaseInsensitiveString", "None", "CaseInsensitiveString", "CaseInsensitiveString","CaseInsensitiveString", "Date", "Date", "CaseInsensitiveString", "Date", "None"]);

st.onsort = function () {
  var rows = st.tBody.rows;
  var l = rows.length;
  for (var i = 0; i < l; i++) {
    removeClassName(rows[i], i % 2 ? "odd" : "even");
    addClassName(rows[i], i % 2 ? "even" : "odd");
  }
};

st.asyncSort(1);
function downloadCPCompleted()
{
  $(\'cp_completed\').value = 1;
  $(\'article_status\').value = 1;
  $(\'f_assign_keyword_return\').submit();
}
function submitFunc()
{
  if ($(\'article_status\').value!=1)
  {
    $(\'cp_completed\').value = 0;
  }
}

function clientReadyPost(aid, status)
{
    ajaxAction(\'/article/change_client_ready.php?aid=\'+aid + \'&status=\' + status, \'div_client_ready\');
}
'; ?>

//]]>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "themes/".($this->_tpl_vars['theme'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>