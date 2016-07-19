<?php
require_once '../pre.php';
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    exit;
}
$g_current_path = 'sat';
require_once '../cms_menu.php';
require_once 'db.php';

/*...............................................................
     initialize variables
...............................................................*/
$domainmetaid = 0;
$url = "";
$domainid = 0;
$keyhprase = "";
$keyphraseid = 0;
/*...............................................................
     finished initializing variables
...............................................................*/

/*...............................................................
     collect querystring and post data
...............................................................*/
$url = $_POST['url']; // Get the URL from the querystring
$domainid = $_GET['domainid'];
$domainmetaid = $_GET['domainmetaid'];
$keyphraseid = $_GET['keyphraseid'];

if ( isset($url) ){
     $url = str_replace('http://', '', $url);
     $url = str_replace('https://', '', $url);
}
$keyphrase = $_POST["keyphrase"];
/*...............................................................
     finish collecting querystring and post data
...............................................................*/

/*...............................................................*/
     function DisplayForm(){
          global $url, $keyphrase, $conn, $domainid, $dgdb, $domainmetaid, $keyphraseid;
          global $engine;
          if ( !isset($url) ) { $url = "www.davidgagne.net";}
          if ( !isset($keyphrase) ) { $keyphrase = "dolphin sex\nbanana care\nspeeding cops";}
          
          ?>
          <div style="float:left;border:2px solid white;margin:20px;"><div style="background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;">Site Analysis</div>
          <div style="padding:10px;">
          <form method="post" action="?" name="satSearch">
          <p>
          Enter a domain to analyze:
          <br />
          <input type="text" name="url" value="<?php echo $url; ?>" size="50" maxlength="255" />
          <br /><br />
          <span style="font-style:italic;">(Optional)</span>
          <br />
          Enter a list of keyphrases to search:
          <br />
          <textarea name="keyphrase" rows="10" cols="20" style="width:300px;"><?php echo $keyphrase; ?></textarea>
          <br />
          <!--
          <input type="checkbox" style="background-color:transparent;border:none;" name="engine[]" value="1" checked="checked" />Google
          <input type="checkbox" style="background-color:transparent;border:none;" name="engine[]" value="2" />Yahoo!
          <input type="checkbox" style="background-color:transparent;border:none;" name="engine[]" value="3" />MSN
          //-->
          <br />
          <br />
          <input type="submit" value="Go!" />
          </p>
          </form>
          </div></div>
          <div style="float:left;border:2px solid white;margin:20px;"><div style="background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;">Recent Searches</div>
          <div style="padding:10px;">
          <?php
          //$dgdb->flush();
          $q = "
               select    d.domainid,
                         d.domain,
                         count(dm.domainmetaid) as searches
               from      domains as d
               inner     join domainmetadata as dm
               on        dm.domainid = d.domainid
               group	by d.domainid, d.domain
               order     by d.updated desc
               limit     10
               ";
          $rs = $dgdb->get_results($q);
          if ( $rs ){
               ?>
               <table cellpadding="5" cellspacing="0" border="0">
               <tr><td style="font-weight:bold;" colspan="2">Domain</td><td style="font-weight:bold;">History</td></tr>
               <?php
               $counter = 1;
               foreach ( $rs as $rsitem ){
                    echo "<tr><td style=\"text-align:right;\">$counter.</td><td style=\"text-align:left;\"><a href=\"http://" . $rsitem->domain . "\" target=\"_blank\">" . $rsitem->domain . "</a></td><td style=\"text-align:right;\"><a href=\"?domainid=" . $rsitem->domainid . "\">" . $rsitem->searches . "</a></td></tr>";
                    $counter++;
               }
               ?>
               </table>
               <?php
          }
          else{
               echo "<p>Unable to connect to database.</p>";
          }
          ?>
          </div></div>
          <?php
          if ( isset($domainid) ){
               $q = "
                    select    dm.*
                    from      domainmetadata as dm
                    where     dm.domainid = $domainid
                    order     by dm.updated desc
                    ";
               $search = $dgdb->get_results($q);
               if ( $search ){
                    $domaintitle = trim($dgdb->get_var("select domain from domains where domainid = $domainid"));
                    echo "<div style=\"float:left;border:2px solid white;margin:20px;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Search History for \"$domaintitle\"&nbsp;</div>";
                    ?>
                    <div style="padding:10px;">
                    <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                    <td style="font-weight:bold;">Last Updated</td>
                    <td style="font-weight:bold;">Rank (A)</td>
                    <td style="font-weight:bold;">BackLinks (A)</td>
                    <td style="font-weight:bold;">Google PR</td>
                    <td style="font-weight:bold;">Indexed (G)</td>
                    <td style="font-weight:bold;">BackLinks (G)</td>
                    <td style="font-weight:bold;">KP Ranks (G)</td>
                    </tr>
                    <?php
                    foreach ( $search as $searchhistory ){
                         echo "<tr>";
                         echo "<td>" . $searchhistory->updated . "</td>";
                         echo "<td style=\"text-align:right;\">" . $searchhistory->alexarank . "</td>";
                         echo "<td style=\"text-align:right;\">" . $searchhistory->alexalinkcount . "</td>";
                         echo "<td style=\"text-align:right;\">" . $searchhistory->googlepr . "</td>";
                         echo "<td style=\"text-align:right;\">" . $searchhistory->googleindexed . "</td>";
                         echo "<td style=\"text-align:right;\">" . $searchhistory->googlebacklinks . "</td>";
                         echo "<td style=\"text-align:right;\"><a href=\"?domainid=$domainid&domainmetaid=" . $searchhistory->domainmetaid . "\">view</a></td>";
                         echo "</tr>";
                    }
                    ?>
                    </table>
                    </div></div>
                    <?php
               }
          }
          if ( isset($domainmetaid) ) {
               $q = "
                    select	k.keyphrase,
                    		dk.googlerank,
                    		k.keyphraseid
                    from		domainkeyphrase dk
                    inner	join keyphrase k
                    on		k.keyphraseid = dk.keyphraseid
                    where	dk.domainmetaid = $domainmetaid
                    order	by 2 desc
                    ";
               $ranks = $dgdb->get_results($q);
               if ( isset($ranks) ) {
                    $searchdate = $dgdb->get_var("select updated from domainmetadata where domainmetaid = $domainmetaid");
                    echo "<div style=\"float:left;border:2px solid white;margin:20px;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Keyphrase Ranks for \"$domaintitle\"&nbsp;</div>";
                    ?>
                    <div style="padding:10px;">
                    <p>Search Executed <?php echo $searchdate; ?></p>
                    <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                    <td style="font-weight:bold;">Keyphrase</td>
                    <td style="font-weight:bold;">Rank (G)</td>
                    <td style="font-weight:bold;">History</td>
                    </tr>
                    <?php
                    foreach ($ranks as $rank){
                         echo "<tr>";
                         echo "<td>" . $rank->keyphrase . "</a></td>";
                         echo "<td>" . $rank->googlerank . "</td>";
                         echo "<td><a href=\"?domainid=$domainid&domainmetaid=" . $domainmetaid . "&keyphraseid=" . $rank->keyphraseid . "\">view</a></td>";
                         echo "</tr>";
                    }
                    ?>
                    </table>
                    </div></div>
                    <?php
               }
               else{
                    echo "<h1>Nothing.</h1>";
               }
          }
          if ( isset($keyphraseid) ) {
               $q = "
                    select	k.keyphrase,
                    		dk.googlerank,
                    		d.domain,
                    		dm.updated
                    from		domainkeyphrase dk
                    inner	join domains d
                    on		d.domainid = dk.domainid
                    inner	join domainmetadata dm
                    on		dm.domainmetaid = dk.domainmetaid
                    inner	join keyphrase k
                    on		k.keyphraseid = dk.keyphraseid
                    where	dk.keyphraseid = $keyphraseid
                    order	by 3, 4 desc
                    ";
               $ranks = $dgdb->get_results($q);
               if ( isset($ranks) ) {
                    $q = "select keyphrase from keyphrase where keyphraseid = $keyphraseid";
                    $keyphrase = trim($dgdb->get_var($q));
                    echo "<div style=\"float:left;border:2px solid white;margin:20px;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Keyphrase Rank History for \"$keyphrase\"</div>";
                    ?>
                    <div style="padding:10px;">
                    <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                    <td style="font-weight:bold;">Domain</td>
                    <td style="font-weight:bold;">Rank (G)</td>
                    <td style="font-weight:bold;">Updated</td>
                    </tr>
                    <?php
                    foreach ($ranks as $rank){
                         echo "<tr>";
                         echo "<td>" . $rank->domain . "</td>";
                         echo "<td>" . $rank->googlerank . "</td>";
                         echo "<td>" . $rank->updated . "</td>";
                         echo "</tr>";
                    }
                    ?>
                    </table>
                    </div></div>
                    <?php
               }
               else{
                    echo "<h1>Nothing.</h1>";
               }
          }
     }
/*...............................................................*/

/*...............................................................*/
     function ShowRanks($domainmetaid){
     	global $url, $keyphrase, $domainid;
     	$rowcounter = 1;

          echo "<div style=\"float:left;border:2px solid white;margin:20px;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Google Ranks for Selected Keyphrases</div>";
          echo "<div style=\"padding:10px;\">";
          echo "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";

     	$keyphraseArray = GoogleKeyphraseRank($keyphrase, $url, $domainid, $domainmetaid); // save the results of the function in an array
     	foreach($keyphraseArray as $wordandrank){ // loop through the array
     		$rankArray = explode("=>",$wordandrank); // break results based upon the double arrow
     		echo "<tr>";
     		echo "<td style=\"text-align:right;\">$rowcounter.</td>";
     		echo "<td style=\"padding-right:10px;\">" . $rankArray[0] . "</td>";
     		echo "<td style=\"text-align:right;\">" . $rankArray[1] . "</td>";
     		echo "</tr>";
     		$rowcounter++;
     	}
     	echo "</table>";
          echo "</div></div><br style=\"clear:both;\" />";
     }
/*...............................................................*/

/*...............................................................*/
     function DisplayResults(){
          global $url, $conn, $keyphrase, $dgdb;
          $testing = 0;
          $itemcounter = 1;
     	$domainid = SaveURL($url);
     	include('includes/sa_code.php'); // Include the code file
     
     	echo "<h1>Site Analysis Tool</h1><div style=\"margin-left:20px;\"><img style=\"float:left;margin:0px 20px 20px 0px;border:1px solid #000;\" src=\"http://msnsearch.srv.girafa.com/srv/i?s=MSNSEARCH&amp;r=" . $url ."\" alt=\"site snapshot\" /><a style=\"font:bold medium verdana, sans-serif;line-height:150%;\" target=\"_blank\" href=\"$url\">" . $url . "</a><br style=\"clear:both;\" /></div>";
     	
     	if ( !$testing ){     
          	$pageInfo = pageInfo($url); // Call the Page function and store the returned array.
          
          	$alexa = AlexaDataXML($url); // Call the Alexa function and store the returned array.
          	
               $googlePageRank = 0;
               $googleTotalIndexedPages = 0;
               $msnTotalIndexedPages = 0;
               $yahooTotalIndexedPages = 0;
               $googleBackLinksvalue = 0;
               $msnBackLinksvalue = 0;
               $yahooBackLinksvalue = 0;
               
          	$googlePageRank = GooglePageRank($url); // Call the Google Pagerank function
          	$googleTotalIndexedPages = GoogleTotalIndexedPages($url); // Variable the Total Pages Indexed in Google. 
          	$msnTotalIndexedPages = MSNTotalIndexedPages($url); // Variable the Total Pages Indexed in Google. 
          	$yahooTotalIndexedPages = YahooTotalIndexedPages($url); // Variable the Total Pages Indexed in Google. 
          	$googleBackLinks = GoogleBackLinks($url); // Variable the Back Links Number.
          	$msnBackLinks = MSNBackLinks($url);
          	$yahooBackLinks = YahooBackLinks($url);

               $googleBackLinksvalue = $googleBackLinks["Total Back Links"];
               if ( !$googleBackLinksvalue ){
                    $googleBackLinksvalue = 0;
               }
               $msnBackLinksvalue = $msnBackLinks["Total Back Links"];
               if ( !$msnBackLinksvalue ){
                    $msnBackLinksvalue = 0;
               }
               $yahooBackLinksvalue = $yahooBackLinks["Total Back Links"];
               if ( !$yahooBackLinksvalue ){
                    $yahooBackLinksvalue = 0;
               }

               echo "<div style=\"float:left;border:2px solid white;margin:20px;width:33%;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Search Data</div>";
               echo "<div style=\"padding:10px;\">";
               echo "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";
          	foreach ( $pageInfo as $k=>$v ){
          		echo "<tr><td>$k:</td><td colspan=\"2\">$v</td></tr>";
          	}
          	foreach ( $alexa as $k=>$v ){
          		echo "<tr><td>Alexa $k:</td><td colspan=\"2\" style=\"text-align:right;font-weight:bold;\">$v</td></tr>";
          	}
          	echo "<tr><td>Google Page Rank:</td><td colspan=\"2\" style=\"text-align:right;font-weight:bold;\">" . $googlePageRank . "</td></tr>"; // Echo the Google PR. 
          	echo "<tr><td>Total Number of Indexed Pages:</td>";
          	echo "<td style=\"text-align:right;font-weight:bold;\">Google:</td><td style=\"text-align:right;font-weight:bold;\">" . $googleTotalIndexedPages . "</td></tr>";
          	echo "<tr><td>&nbsp;</td><td style=\"text-align:right;font-weight:bold;\">MSN:</td><td style=\"text-align:right;font-weight:bold;\">" . $msnTotalIndexedPages . "</td></tr>";
          	echo "<tr><td>&nbsp;</td><td style=\"text-align:right;font-weight:bold;\">Yahoo!:</td><td style=\"text-align:right;font-weight:bold;\">" . $yahooTotalIndexedPages . "</td></tr>";
          	echo "<tr><td>Total Number of Back Links:</td>";
          	echo "<td style=\"text-align:right;font-weight:bold;\">Google:</td><td style=\"text-align:right;font-weight:bold;\">" . $googleBackLinksvalue . "</td></tr>";
          	echo "<tr><td>&nbsp;</td><td style=\"text-align:right;font-weight:bold;\">MSN:</td><td style=\"text-align:right;font-weight:bold;\">" . $msnBackLinksvalue . "</td></tr>";
          	echo "<tr><td>&nbsp;</td><td style=\"text-align:right;font-weight:bold;\">Yahoo!:</td><td style=\"text-align:right;font-weight:bold;\">" . $yahooBackLinksvalue . "</td></tr>";
          	echo "</table>";
               echo "</div></div>";

          	if ( 1 == 2 ){
#          	if ( $googleBackLinksvalue > 0 ){
                    echo "<div style=\"float:left;border:2px solid white;margin:20px;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Top Twenty Google BackLinks</div>";
                    echo "<div style=\"padding:10px;\">";
                    echo "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";
          	     $itemcounter = 1;
               	foreach ($googleBackLinks["Back Link Pages"] as $k=>$v){ // Loop through the array. There is a key and a value.
               	     if ( $itemcounter <= 20 ) {
                    		echo   "<tr><td style=\"text-align:right;\">$itemcounter. </td><td><a target=\"_blank\" href=" . $v . ">" . $v . "</a>$k</td></tr>"; // Print just the value. Let's make it a link. 
               		}
               		$itemcounter++;
               	}
          		echo "<tr><td colspan=\"2\" style=\"text-align:right;\"><a target=\"_blank\" href=\"http://www.google.com/search?q=link:" . $url . "\">more ...</a></td></tr>";
               	echo "</table>";
                    echo "</div></div>";
          	}
          	
          	$alexarank = $alexa["Rank"];
          	$alexalinks = $alexa["Links"];
          	if ( !$alexarank ){
          	     $alexarank = 0;
          	}
          	if ( !$alexalinks ){
          	     $alexalinks = 0;
          	}
          	
          	$domaintitle = str_replace("'", "\'", $pageInfo["Title"]);

          	$q = "
          	     insert    into domainmetadata(domainid, domaintitle, alexarank, alexalinkcount, googlepr, googleindexed, googlebacklinks, updated)
          	     values    (" . $domainid . ", '" . $domaintitle . "', '" . $alexarank . "', '" . $alexalinks . "', '" . $googlePageRank . "', '" . $googleTotalIndexedPages . "', '" .  $googleBackLinksvalue . "', now())
          	";
#          	echo "<h1>$q</h1>";
          	$dgdb->query($q);
          	$q = "select domainmetaid from domainmetadata where domainid = $domainid order by 1 desc limit 1";
          	$domainmetaid = $dgdb->get_var($q);
          	
	          if ( strlen($keyphrase) ) {
               	$rowcounter = 1;
          
                    echo "<div style=\"float:left;border:2px solid white;margin:20px;\"><div style=\"background-color:white;color:#333;padding:4px;margin:0;font:bold medium sans-serif;\">Google Ranks for Selected Keyphrases</div>";
                    echo "<div style=\"padding:10px;\">";
                    echo "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";
          
               	$keyphraseArray = GoogleKeyphraseRank($keyphrase, $url, $domainid, $domainmetaid); // save the results of the function in an array
               	foreach($keyphraseArray as $wordandrank){ // loop through the array
               		$rankArray = explode("=>",$wordandrank); // break results based upon the double arrow
               		echo "<tr>";
               		echo "<td style=\"text-align:right;\">$rowcounter.</td>";
               		echo "<td style=\"padding-right:10px;\">" . $rankArray[0] . "</td>";
               		echo "<td style=\"text-align:right;\">" . $rankArray[1] . "</td>";
               		echo "</tr>";
               		$rowcounter++;
               	}
               	echo "</table>";
                    echo "</div></div><br style=\"clear:both;\" />";
	          }
	          else{
	               echo "<h1>?!</h1>";
	          }
     	}
     }
/*...............................................................*/

/*...............................................................*/
     function SaveURL($url){
          global $dgdb, $domainid;
          $q = "
               select    domainid
               from      domains
               where     domain = '$url'
               ";
          $domainid = $dgdb->get_var($q);
          if ( empty($domainid) ){
               $q = "
                    insert    into domains(
                         domain,
                         created,
                         updated
                         )
                    values (
                         '$url',
                         now(),
                         now()
                         )
                    ";
               $dg = $dgdb->query($q);
               $q = "
                    select    domainid
                    from      domains
                    where     domain = '$url'
                    ";
               $domainid = $dgdb->get_var($q);
          }
          else{
               $q = "
                    update    domains
                    set       updated = now()
                    where     domainid = $domainid;
                    ";
               $dg = $dgdb->query($q);
          }
          if ( !$domainid ){
               echo "<h1>Failed to save data.  Contact an Administrator.</h1>";
          }
          return $domainid;
     }
/*...............................................................*/

function GoogleKeyphraseRank($keyphrases, $url, $domainid, $domainmetaid){
     global $conn, $dgdb;
	$keyphraseArray = explode("\n", $keyphrases); //store the keyphrases in an array
	$keyphraseRankArray = array(); // set this now outside the loop so it exists
	foreach($keyphraseArray as $keyphrase){ // loop through the array. We are going to have to call each keyphrase separately
		$googlePage = @join( '', @file( "http://www.google.com/search?num=100&q=" . urlencode($keyphrase))); // Grab the Google SERPs. Use join to make it one string
		$resultsStart =  strpos($googlePage,"<div class=g>"); // find the start of the organic results. Look for <div class=g>
		$googlePage = substr($googlePage,$resultsStart,strlen($googlePage)); // Only use the organics. Start at the first and go to the last chr.  
		$googlePage = str_replace("<b>", "", $googlePage ); // Let's clean the page bit
		$googlePage = str_replace("</b>", "", $googlePage ); // More cleaning.
		preg_match_all('#<span class=a>(.*)/#U', $googlePage, $keyphraseSERP ); // Now the fun begins. Lets preg_match that string
		$keyphraseSERP = $keyphraseSERP[1]; // grab the second array without the html in the results from the preg match
		// print_r($keyphraseSERP);
		$therank = 0;
		if(in_array($url, $keyphraseSERP)){
			$keyphraseRank = array_search($url,$keyphraseSERP) + 1; // 0 bound array so add 1 to it	
			array_push($keyphraseRankArray, "$keyphrase=>$keyphraseRank");
			$therank = intval($keyphraseRank);
		}
		else{
			array_push($keyphraseRankArray, "$keyphrase=><b>NOT IN TOP 100</b>");
			$therank = 0;
		}
/*...............................................................
     get the keyphrase id
     store it if it doesn't exist
...............................................................*/
          $keyphraseid = 0;
          $keyphrase = str_replace("'", "\'", $keyphrase);
          $q = "
               select    keyphraseid
               from      keyphrase
               where     keyphrase = '$keyphrase'
               ";
          $keyphraseid = $dgdb->get_var($q);
          if ( empty($keyphraseid) ){
               $q = "
                    insert    into keyphrase(
                         keyphrase,
                         added
                         )
                    values (
                         '$keyphrase',
                         now()
                         )
                    ";
               $dgdb->query($q);
               $q = "
                    select    keyphraseid
                    from      keyphrase
                    where     keyphrase = '$keyphrase'
                    ";
               $keyphraseid = $dgdb->get_var($q);
          }
          $q = "insert into domainkeyphrase(domainid, keyphraseid, googlerank, domainmetaid, updated) values ('$domainid', '$keyphraseid', '$therank', '$domainmetaid', now());";
          $dgdb->query($q);
	}
	return $keyphraseRankArray;
}

$smarty->display('sat/index.html');
?>