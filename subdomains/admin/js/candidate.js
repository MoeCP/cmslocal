jQuery.noConflict(); 
function addRow(divid, maxrow, tag)
{
    if (tag.length == 0)
    {
        tag = 'div';
    }
   var hint = arguments[3] || 'Category';
   var rawdev = jQuery('#'+divid);
   var chidren = rawdev.children().clone();
   var button = '<input type="button"  class="button" value="-" onclick="jQuery(this).parent().parent().remove();" />';
   if (rawdev.parent().first().parent().first().find('input[type="button"]').length == maxrow) {
       alert('You cant input ' + hint + ' items more than ' + maxrow);
       return false;
   }
   chidren.children().first().replaceWith(button);
   chidren.children().each(function(index){
       if (jQuery(this).is('input[type!="button"]') || jQuery(this).is('select')) {
           jQuery(this).val('');
           if (jQuery(this).is('select[name="categories[category_id][]"]')) {
               jQuery(this).html('<option value="0">Select</option');
           }
       }
   });
   var odiv = jQuery('<' +  tag + '></' + tag + '>').append(chidren);
   rawdev.parent().children().last().after(odiv);
   if (divid == 'specialtyrow') {
        initCategory();
   }
   return true;
}

function check_f_candidate()
{
  var f = document.f_candidate;
  if (check_f_basic(f)) {
       ajax_post(f);
  }
  return false;
}

function check_f_basic(f)
{
  f.opt_index.value = arguments[1]||0;
  if (f.first_name.value.length == 0) {
      alert('Please specify first name');
      f.first_name.focus();
      return false;
  }

  if (f.last_name.value.length == 0) {
      alert('Please specify last name');
      f.last_name.focus();
      return false;
  }


  if (f.email.value.length == 0) {
      alert('Please specify email');
      f.last_name.focus();
      return false;
  }
  if (f.country.value.length == 0) {
      alert('Please specify country');
      f.country.focus();
      return false;
  }
  if (f.city.value.length == 0) {
      alert('Please specify city');
      f.city.focus();
      return false;
  }
//  if (f.state.value.length == 0) {
//      alert('Please specify state');
//      f.state.focus();
//      return false;
//  }
//  if (f.zip.value.length == 0) {
//      alert('Please specify zip');
//      f.zip.focus();
//      return false;
//  }

  /*if (f.productivity.value == '') {
    alert('Please specify productivity');
    f.productivity.focus()
    return false;
  }*/
  if (f.address.value.length == 0) {
      alert('Please specify address');
      f.address.focus();
      return false;
  }
  if (!(f.sex[0].checked || f.sex[1].checked)) {
    alert('Please enter the gender ');
    f.sex[0].focus()
    return false;
  }
  if (f.first_language.value == '') {
    alert('Please specify first language');
    f.first_language.focus()
    return false;
  }
  if (f.weekly_hours.value == '') {
    alert('Please specify weekly hours');
    f.weekly_hours.focus()
    return false;
  }

  return true;
}

function check_f_candidate_edu()
{
  var f = document.f_candidate_edu;
  if (check_f_education(f)) {
      ajax_post(f);
  }
  return false;
}

function ajax_post(f)
{
    jQuery.ajax({
      url:"savecandidate.php",
      type: 'POST',
      async: false,
      enctype: 'multipart/form-data',
      data:jQuery(f).serialize(),
      success:function(msg) {jQuery('#ajaxresult').html(msg);}
    });
    return true;
}

function ajax_get(pdata, url)
{
    jQuery.ajax({
      url:url,
      type: 'get',
      async: false,
      data:pdata,
      success:function(msg) {jQuery('#ajaxresult').html(msg);}
    });
    return true;
}

function delWritingSample(obj, cid, file, folder)
{
    if (ajax_get({'fd':folder, 'cid':cid, 'f':file}, 'sample_delete.php')) {
     obj.parent().parent().remove();
    }
}

function changePayPref(value) {
    if (value == 3) {
        jQuery('#tr_paypal_email').show();
    } else {
        jQuery('#tr_paypal_email').hide();
    }
}

function changeCountry(value)  {
    var len = jQuery('#pay_pref').children().length;
    if (value == 'United States of America' && len == 2) {
        jQuery('#pay_pref option:first-child').first().after('<option value="2" label="Direct Deposit">Direct Deposit</option>');
    } else if (len > 2 && value != 'United States of America'){
        jQuery('#pay_pref').children().eq(1).remove();
    }  
}


function check_f_education(f)
{
  var degrees = document.getElementsByName('education[degree][]');
  var schools = document.getElementsByName('education[school][]');
   f.opt_index.value = arguments[1]||1;
  // var majors = document.getElementsByName('education[major][]');
  var len = degrees.length;
  var has_one = false;
  for (var i=0;i<len ; i++) {
    if (degrees[i].value == 'Select' || degrees[i].value.length==0) {
        continue;
    } else if (degrees[i].value.length == 0 || schools[i].value.length == 0) {
        alert('Please specify school for ' + degrees[i].value);
        jQuery(schools[i]).focus();
        return false;
    } else if (degrees[i].value.length >  0 && schools[i].value.length > 0) {
        has_one = true;
    }
  }
  if (!has_one) {
    alert('There is no education, please to check');
    return false;
  }
  return true;
}

function check_f_candidate_wp()
{
  var f = document.f_candidate_wp;
  if (check_f_experience(f)) {
    ajax_post(f);

  }
  return false;
}

function check_f_experience(f) 
{
    var job = document.getElementsByName('experience[job][]');
    var company = document.getElementsByName('experience[company][]');
    f.opt_index.value = arguments[1]||2;
    // var majors = document.getElementsByName('education[major][]');
    var len = job.length;
    var has_one = false;
    for (var i=0;i<len; i++) {
      if (job[i].value == 'Select' || job[i].value.length==0) {
          continue;
      } else if (job[i].value.length == 0 || company[i].value.length == 0) {
          alert('Please specify company for ' + jQuery(job[i]).children('option:selected').get(0).text);
          jQuery(company[i]).focus();
          return false;
      } else if (job[i].value.length >  0 && company[i].value.length > 0) {
          has_one = true;
      }
    }
    if (!has_one && f.opt_index.value > 0) {
        next = parseInt(f.opt_index.value)+ 1;
        showOnePart(next);
        return false;
    }
    return true;
}

function check_f_candidate_w()
{
  var f = document.f_candidate_w;
  if (check_f_writing_background(f)){
     ajax_post(f);
  }
  return false;
}

function check_f_writing_background(f)
{
  var arr1 = document.getElementsByName('writing_background[type][]');
  var arr2 = document.getElementsByName('writing_background[source][]');
  f.opt_index.value = arguments[1]||3;
  var len = arr1.length;
  var has_one = false;
  for (var i=0;i<len; i++) {
    if (arr1[i].value == 'Select' || arr1[i].value.length==0) {
        continue;
    } else if (arr1[i].value.length == 0 || arr2[i].value.length == 0) {
        alert('Please specify writing background for ' + jQuery(arr1[i]).children('option:selected').get(0).text);
        jQuery(arr2[i]).focus();
        return false;
    } else if (arr1[i].value.length >  0 && arr2[i].value.length > 0) {
        has_one = true;
    }
  }
  if (!has_one) {
    alert('You must specify one writing background at least.');
    return false;
  }
  return true;
}

function check_f_candidate_s()
{
  var f = document.f_candidate_s;
  if (check_f_specialty(f)) {
      ajax_uploaded('f_candidate_s', 'savecandidate.php');
    // ajax_post(f);
  } 
  return false;
}

function check_f_specialty(f)
{
  var pids = document.getElementsByName('categories[parent_id][]');
  var cateids = document.getElementsByName('categories[category_id][]');
  var levels = document.getElementsByName('categories[level][]');
  var descriptions = document.getElementsByName('categories[description][]');
  var catefiles = document.getElementsByName('categories[fileField][]');
  var catelinks = document.getElementsByName('categories[link][]');
  var slinks = document.getElementsByName('samples[link][]');
  var files = document.getElementsByName('samples[fileField][]');
  var slinks = document.getElementsByName('samples[link][]');
  var files = document.getElementsByName('samples[fileField][]');
  var plinktypes = document.getElementsByName('plinks[type][]');
  var plinks = document.getElementsByName('plinks[value][]');
  var writerlevels = document.getElementsByName('writer_level[]');
  var candidate_id = jQuery('input[name="candidate_id"]').val();
  var catefilenames = document.getElementsByName("categories[filename][]");
  var samplefilenames = document.getElementsByName("samples[filename][]");
  f.opt_index.value = arguments[1]||4;
  var len = cateids.length;
  var has_one = false;
  for (var i=0;i<len; i++) {
    olength = cateids[i].options.length;
    if (pids[i].value == 'Select' || pids[i].value.length==0) {
        catefiles[i].value = '';
        continue;
    } else if (pids[i].value.length >  0 && ((levels[i].value.length  == 0 && catelinks[i].value == 'http://www.' &&  catefiles[i].value == '' && (candidate_id > 0 && catefilenames[i].value == '')) || (cateids[i].value == 0 || cateids[i].value.length == 0) && olength > 1)) {
      if (olength > 1 && (cateids[i].value.length  == 0 || cateids[i].value == 0)) {
          alert('Please choose sub-category for ' + jQuery(pids[i]).children('option:selected').text());
          jQuery(cateids[i]).focus();
      } else {
          if (olength > 1) {
             var text =  jQuery(cateids[i]).children('option:selected').text();
          } else if (olength == 1){
             var text =  jQuery(pids[i]).children('option:selected').text();
          }
          alert('Please specify an URL, Document or Relevant Experience for ' + text);
          jQuery(levels[i]).focus();
      }
      return false;
    } else if (pids[i].value.length >  0 && (cateids[i].value  > 0 && olength > 1 || olength == 1) &&  (levels[i].value.length  > 0 || catelinks[i].value.length >  12 || catefiles[i].value.length > 0)){
        if (levels[i].value.length  > 0) {
            if (descriptions[i].value.length == 0) {
                alert('Please specify Relevant Experience Description for ' + text);
                jQuery(descriptions[i]).focus();
                return false;
            }
            has_one = true;
        } else {
            has_one = true;
        }
    }
  }
  if (!has_one) {
      alert('You cant input Category items at least one');
      jQuery(pids[0]).focus()
      return false;
  }
  if (has_one) {
      has_one = false;
      has_one = true;
      len = slinks.length;
      for (var i=0;i<len; i++) {
          if (slinks[i].value.length > 12 || files[i].value != '' || (candidate_id > 0 && samplefilenames[i].value != '')) {
              has_one = true;
          }
      }
  }
  if (!has_one) {
      alert('You cant input Writing Samples items at least one');
      jQuery(slinks[0]).focus();
      return false;
  } else {
      var len = plinks.length;
      var has_one = false;
      has_one = true;
      for (var i=0;i<len ;i++ ) {
          if (plinks[i].value.length > 12) {
              has_one = true;break;
          }
      }
      if (!has_one) {
          alert('Please provide a link to your personal website, portfolio, and/or blog');
          jQuery(plinks[0]).focus();
          return false;
      } else {
          len = writerlevels.length;
          has_one = false;
          has_one = true;
          for (var i=0;i<len ;i++ ) {
              if (writerlevels[i].checked) {
                  has_one = true;break;
              }
          }
          if (!has_one) {
              alert('Please provide what the best describes you as a writer.');
              jQuery(writerlevels[0]).focus();
              return false;
          }
      }
  }
  if (!has_one && f.opt_index.value > 0) {
    // next = parseInt(f.opt_index.value) + 1;
    // showOnePart(next);
    return false;
  }
  return true;
}

function check_f_candidate_ws()
{
  var f = document.f_candidate_ws;
 
  if (check_f_writing_sample(f)) {
     ajax_post(f);
  }
  return false;
}

function check_f_writing_sample(f)
{
  f.opt_index.value = arguments[1]||5;
  if (f.writing_sample.value.length == 0){
      alert("Please specify writing sample");
      f.writing_sample.focus();
      return false;
  }
  return true;
}

function check_f_candidate_test()
{
  var f = document.f_candidate_test;
  if (check_f_test(f)) {
        ajax_post(f);
  }
  return false;
}

function check_f_test(f)
{
    var pf = document.f_candidate;
    f.opt_index.value = arguments[1]||6;
    if (f.revised_article.value.length == 0){
      alert("Please specify revised sample");
      f.revised_article.focus();
      return false;
    }
    if (pf.cpermission.value != 1 && f.feedback.value == '')
    {
      alert("Please provide feedback");
      f.feedback.focus();
      return false;
    }
    return true;
}

function submitApplication()
{
    var f = document.f_candidate;
    if (!check_f_basic(f)) {
        showTab(0);
    } else if (!check_f_specialty(f)) {
        showTab(1);
    } else {
         ajax_uploaded('f_candidate', 'savecandidate.php');
    }
    return false;
}

function termRead(obj)
{
    if (obj.value == 0) {
        alert('If you choose decline, you cannot submit application.');
        if (!jQuery('#submit_button').attr('disabled')) {
            jQuery('#submit_button').attr('disabled', true);
        }
    } else if (obj.value == 1) {
        if (jQuery('#submit_button').attr('disabled')) {
            jQuery('#submit_button').attr('disabled', false);
        }
    }
   return false;
}

function submitAll()
{
    showAllPart();
    var f = document.f_candidate;
    if (check_f_basic(f, -1) && 
    check_f_education(document.f_candidate_edu,-2)  && 
    check_f_experience(document.f_candidate_wp, -3)  && 
    check_f_writing_background(document.f_candidate_w, -4) && 
    check_f_specialty(document.f_candidate_s, -5) && 
    check_f_writing_sample(document.f_candidate_ws, -6) && 
    check_f_test(document.f_candidate_test, -7))
    {
      ajax_post(f);
    }
    return false;
}
jQuery('div[class="title-top-box"]').each(function(index) {
  var item = jQuery('div[class="form-item"]:eq(' + index +')');
  if (index > 0) {
    item.hide('slow');
  }
  jQuery(this).click(function () {
    if (item.css('display') == 'none'){
      item.show('slow');
    } else {
      item.hide('slow');
    }
  });
});

function showOnePart(show_index)
{
    jQuery('div[class="form-item"]').each(function(index) {
        if (index < 7) {
            if (show_index == index){
              jQuery(this).show('slow');
            } else {
              jQuery(this).hide('slow');
            }
        }
    });
}

function showAllPart()
{
    jQuery('div[class="form-item"]').each(function(index) {
        jQuery(this).show();
    });
}

function initCategory() {
  jQuery('select[name="categories[parent_id][]"]').each(function(index) {
    var sub_item = jQuery('select[name="categories[category_id][]"]:eq(' + index+')');
    var cate = jQuery('input[name="categories[category][]"]:eq(' + index+')');
    jQuery(this).change(function(){
      jQuery.ajax({
        url:'loadsubcate.php',
        type: 'GET',
        data:{pid:jQuery(this).val()},
        success:function(msg){sub_item.html(msg);}
      });
      cate.val(jQuery(this).children('option:selected').text());
    });
  });
  jQuery('select[name="categories[category_id][]"]').each(function(index) {
    var cate = jQuery('input[name="categories[category][]"]:eq(' + index+')');
    var cate_id = jQuery('select[name="categories[parent_id][]"]:eq(' + index+')');
    jQuery(this).change(function(){
      var text = cate_id.children('option:selected').text();
      if (jQuery(this).text() == '') {
        cate.val(text);
      } else {
        cate.val(text + '>>' + jQuery(this).children('option:selected').text());
      }
    });
  });
}

initCategory();

jQuery(document).ready(function() {
	//Default Action
	jQuery(".tab_content").hide(); //Hide all content
	jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".tab_content:first").show(); //Show first tab content
	//On Click Event
	jQuery("ul.tabs li").click(function() {
		jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(".tab_content").hide(); //Hide all tab content
		var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); //Fade in the active content
		return false;
	});
});

function showTab(show_index)
{
    jQuery("ul.tabs li").removeClass("active");
    jQuery(".tab_content").hide();
    jQuery('ul.tabs li').each(function(index) {
        if (index < 7) {
            if (show_index == index){
              jQuery(this).addClass("active");
              var activeTab = jQuery(this).find("a").attr("href");
              jQuery(activeTab).fadeIn(); 
            }
        }
    });
}


function ajax_uploaded(formid, url) 
{
    // hooking into.
    var jForm = jQuery( "#" + formid );

    // Attach an event to the submit method. Instead of
    // submitting the actual form to the primary page, we
    // are going to be submitting the form to a hidden
    // iFrame that we dynamically create.
    var strName = "uploaderiframe";
    jForm.submit(function( objEvent ){
        var jThis = jQuery( this );

        // Create a unique name for our iFrame. We can
        // do this by using the tick count from the date.
        
        if (isObjectOrNot(jQuery('#' + strName))) {
            jQuery('#' + strName).remove();
        }
        // Create an iFrame with the given name that does
        // not point to any page - we can use the address
        // "about:blank" to get this to happen.
        var jFrame = jQuery( "<iframe name=\"" + strName + "\" id=\""+strName +"\" src=\"about:blank\" />" );

        // We now have an iFrame that is not attached to
        // the document. Before we attach it, let's make
        // sure it will not be seen.
        jFrame.css( "display", "none" );

        // Since we submitting the form to the iFrame, we
        // will want to be able to get back data from the
        // form submission. To do this, we will have to
        // set up an event listener for the LOAD event
        // of the iFrame.
        jFrame.load(function( objEvent ){
            // Get a reference to the body tag of the
            // loaded iFrame. We are doing to assume
            // that this element will contain our
            // return data in JSON format.
            var objUploadBody = window.frames[ strName ].document.getElementsByTagName( "body" )[ 0 ];
           //  var objUploadBody = document.getElementById(strName).document.getElementsByTagName( "body" )[ 0 ];
            // Get a jQuery object of the body so
            // that we can have better access to it.
            var jBody = jQuery( objUploadBody );
            // Assuming that our return data is in
            // JSON format, evaluate the body html
            // to get our return data.
            var objData = jBody.html();
            str = '<script type="text/javascript">alert("'+ objData + '");window.opener.location.reload();window.close();</script>';
            jQuery('#ajaxresult', window.parent.document).html(str);
            // "Alert" the return data (this should
            // be an array of the server-side files
            // that were uploaded).
            // prompt( "Return Data:", objData );
             // Remove the iFrame from the document.
            // Because FireFox has some issues with
            // "Infinite thinking", let's put a small
            // delay on the frame removal.
            setTimeout(function(){jFrame.remove();},100);
        });


        // Attach to body.
        jQuery( "body:first" ).append( jFrame );
        // Now that our iFrame it totally in place, hook
        // up the frame to post to the iFrame.
        jThis
            .attr( "action", url )
            .attr( "method", "post" )
            .attr( "enctype", "multipart/form-data" )
            .attr( "encoding", "multipart/form-data" )
            .attr( "target", strName);
    });
    jForm.submit();
}