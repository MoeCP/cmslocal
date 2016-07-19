jQuery.noConflict(); 
var ratingDivId =  'ratingWindowDialog';
function checkRanking() {
    var punctuation = jQuery('#' + ratingDivId + " input[name='punctuation']:checked").val() || 0;
    var grammar = jQuery('#' + ratingDivId + " input[name='grammar']:checked").val() || 0;
    var structure = jQuery('#' + ratingDivId + " input[name='structure']:checked").val() || 0;
    var ap_style = jQuery('#' + ratingDivId + " input[name='ap_style']:checked").val() || 0;
    var style_guide = jQuery('#' + ratingDivId + " input[name='style_guide']:checked").val() || 0;
    var quality = jQuery('#' + ratingDivId + " input[name='quality']:checked").val() || 0;
    var communication = jQuery('#' + ratingDivId + " input[name='communication']:checked").val() || 0;
    var cooperativeness = jQuery('#' + ratingDivId + " input[name='cooperativeness']:checked").val() || 0;
    var timeliness = jQuery('#' + ratingDivId + " input[name='timeliness']:checked").val() || 0;
    var ranking = jQuery('#' + ratingDivId + " input[name=ranking]").val() || 0;
    if ( ranking > 100) {
       alert("Please choose a total is more than 100, please to check you choose");
       jQuery('#' + ratingDivId + " input[name=ranking]").val(100);
       jQuery('#' + ratingDivId + " input[name='punctuation']:radio").get(0).focus();
       return false;
    } else if ( punctuation == 0) {
        alert("Please choose a number of Punctuation");
        jQuery('#' + ratingDivId + " input[name='punctuation']:radio").get(0).focus();
        return false;
    } else if (grammar == 0) {
        alert("Please choose a number of Grammar");
        jQuery('#' + ratingDivId + " input[name='grammar']:radio").get(0).focus();
        return false;
    } else if (structure == 0) {
        alert("Please choose a number of Structure");
        jQuery('#' + ratingDivId + " input[name='structure']:radio").get(0).focus();
        return false;
    } else if (ap_style == 0) {
       alert("Please choose a number of AP Style");
        jQuery('#' + ratingDivId + " input[name='ap_style']:radio").get(0).focus();
        return false;
    } else if (style_guide == 0) {
       alert("Please choose a number of Style Guide");
        jQuery('#' + ratingDivId + " input[name='style_guide']:radio").get(0).focus();
        return false;
    } else if (quality == 0) {
        alert("Please choose a number of Overall Content Quality");
        jQuery('#' + ratingDivId + " input[name='quality']:radio").get(0).focus();
        return false;
    } else if (communication == 0) {
        alert("Please choose a number of Communication with Editor");
        jQuery('#' + ratingDivId + " input[name='communication']:radio").get(0).focus();
        return false;
    } else if (cooperativeness == 0) {
        alert("Please choose a number of Cooperativeness");
        jQuery('#' + ratingDivId + " input[name='cooperativeness]:radio").get(0).focus();
        return false;
    } else if (timeliness == 0) {
        alert("Please choose a number of Timeliness ");
        jQuery('#' + ratingDivId + " input[name='timeliness']:radio").get(0).focus();
        return false;
    } else {
        jQuery('#' + ratingDivId + " #operation").val("rating");
        var param = {target:'#afterRatedDiv',type:'post',data:jQuery('#' + ratingDivId + " #ranking_info").serialize(),dataType: 'json',url:'/client_campaign/article_ranking_save.php',error:function() { alert("Rating failed");}, success: function(data) { if (data) {var str = '<input type="hidden" name="ranking" id="ranking" value="'+data.ranking+'" /><input type="hidden" name="ranking_id" id="ranking_id" value="'+data.ranking_id+'" /><input type="button" name="modify_ranking" id="modify_ranking" value="Update Rating"  onclick="javascript:doRating(\'/article/approve_article.php?is_ajax=1&article_id='+ data.article_id +'&keyword_id='+ data.keyword_id +'\', \''+ data.user_id+'\', \'' + data.keyword_id+'\', \'' + data.article_id + '\', \'' + data.campaign_id +'\');" />&nbsp;&nbsp;&nbsp;&nbsp;Score: &nbsp;' + data.ranking;jQuery('#articleRatingButtonDiv').html(str);jQuery("#ratingWindowDialog").remove(); doAction(data.repost_action,data.repost_url);}}};
        jQuery.ajax(param);
    }
    return false;
}
function initRadios(ratingDivId)
{
    jQuery('#' + ratingDivId + " input:checked").each(function(index) {
        name = jQuery(this).attr('name');
        quotiety = parseInt(jQuery('#' + ratingDivId + " #quotiety" + name).val());
        value = parseInt(jQuery(this).val())/5.0;
        value *= quotiety;
        old_ratings[name] = value;
    });
}

function initRating(ratingDivId)
{
    jQuery('#' + ratingDivId +" input:radio").each(function(index){
        jQuery(this).attr('class', 'star');
    });
    initRadios(ratingDivId);
    var old_ratings = new Array();
    jQuery('#' + ratingDivId + ' input:radio.star').rating({required:true, callback:function(value,link){
        // alert(value);
        var total =jQuery('#' + ratingDivId + ' input[name=ranking]').val() || 0;
        name = jQuery(this).attr('name');
        quotiety = parseInt(jQuery("#quotiety" + name).val());
        
        value = parseInt(value)/5.0;
        value *= quotiety;
        total = parseInt(total);
        old_ratings[name] = old_ratings[name] || 0;
        //alert(old_ratings[name]);
        if ( old_ratings[name] > 0) {
          total -= old_ratings[name];
        }
        total += parseInt(value);
        jQuery('#' + ratingDivId + ' input[name=ranking]').val(total);
        //alert(name +': quotiety=' + quotiety + ',value=' + value + ', old_value=' +old_ratings[name] +', total='  + total );
        old_ratings[name] = value;
        jQuery('#' + ratingDivId + ' #totaldiv').html('Total&nbsp;:' + total);
      }});
}

/*jQuery("#articleRatingButtonDiv input:radio").each(function(index){
    jQuery(this).attr('class', 'star');
});*/