<?
include('xmlparser.php'); // Include the XML parser

define('GOOGLE_MAGIC', 0xE6359A60);  // Some constant for the checksum. 



###############################################################################################################################
#                BEGIN GOOGLE PREWRITTEN FUNCTIONS FOR PR                                                                     #
#																															  #
###############################################################################################################################

#######################################################################

/*
 * convert a string to a 32-bit integer
 */
function StrToNum($Str, $Check, $Magic)
{
    $Int32Unit = 4294967296;  // 2^32

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic; 	
        //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
        //  the result of converting to integer is undefined
        //  refer to http://www.php.net/manual/en/language.types.integer.php
        if ($Check >= $Int32Unit) {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
            //if the check less than -2^31
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i}); 
    }
    return $Check;
}

/* 
 * Genearate a hash for a url
 */
function HashURL($String)
{
    $Check1 = StrToNum($String, 0x1505, 0x21);
    $Check2 = StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2; 	
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	
	
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
	
    return ($T1 | $T2);
}

/* 
 * genearate a checksum for the hash string
 */
function CheckHash($Hashnum)
{
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
	
    for ($i = $length - 1;  $i >= 0;  $i --) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {              
            $Re += $Re;     
            $Re = (int)($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag ++;	
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2) ) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }

    return '7'.$CheckByte.$HashStr;
}

#######################################################################

###############################################################################################################################
#                 END GOOGLE PREWRITTEN FUNCTIONS FOR PR                                                                      #
#																															  #
###############################################################################################################################




###############################################################################################################################
#                 BEGIN CUSTOM FUNCTIONS FOR SITE ANALYSIS                                                                    #
#					JOSH AXELMAN   12/16/2006																				  #
###############################################################################################################################

function pageInfo($url){
	$page = @join( '', @file("http://" . $url)); // Grab the Page
	$page =  strtolower($page); // Make all the text lowercase for easy parsing. 
	preg_match_all('#<title>(.*)</title>#U', $page, $pageTitle); // Now the fun begins. Lets preg_match the title
	preg_match_all('#<meta name="description" content="(.*)"#U', $page, $pageDescription); // Match the meta description
	$pageArray = array("Title"=>$pageTitle[1][0],"Description"=>$pageDescription[1][0]); // Create an array with titles
	return $pageArray;
}



function AlexaDataXML($url){
	$alx_address = 'http://data.alexa.com/data?cli=10&dat=snbamz&url=' . $url; // build the URL
	$contents = file_get_contents($alx_address); // save the XML
	$alexaArray = GetXMLTree($contents); // turn it into an array
	//print_r($alexaArray);
	$alexaDateCreated = $alexaArray["ALEXA"]["SD"]["CREATED"]["DATE"]; // grab the date
	$alexaRank = $alexaArray["ALEXA"]["SD"]["POPULARITY"]["TEXT"]; // apparently this is popularity  and not the rank, go figure
	$alexaLinks = $alexaArray["ALEXA"]["SD"]["LINKSIN"]["NUM"]; // grab the links
	$alexa = array("DateCreated"=>"$alexaDateCreated","Rank"=>"$alexaRank","Links"=>"$alexaLinks"); // build a new array to return
	return $alexa; // return it
}

#######################################################################

function GooglePageRank($url) { 
	$iURL = "info:" . $url;  // Create the info URL. Note: doesn't like urlencode 
	$ch = CheckHash(HashURL($url));  // Run the Google Magic or Checksum ** use $url not the $iURL
	$file = "http://www.google.com/search?client=navclient-auto&ch=$ch&features=Rank&q=$iURL"; // The new URL with the checksum
	$googlePage = file($file); // Save the results page.
	$rankArray = explode (':', $googlePage[2]); // Create array with : as the delimiter. 
	$rank = $rankArray[2]; // Grab the rank from the array.
	return $rank; // Return the rank and remember Kobe is the greatest. 53 pts 12/16/2006 :)
} 



/*...............................................................
     Begin Total Indexed Pages Code
...............................................................*/
function GoogleTotalIndexedPages($url){
	$sURL = urlencode("site:" . $url); // Create the site: url 
	$googlePage = @join( '', @file( "http://www.google.com/search?num=10&q=" . $sURL )); // Grab the Google SERPs. Use join to make it one string
	preg_match_all('#about <b>(.*)</b>#U', $googlePage, $indexedPages ); // Now the fun begins. Lets preg_match that string
	$totalNumberPages = $indexedPages[1][0]; // This was the results of pattern match and the print_r of the array. Note:If this ever breaks, make sure this is correct
	if (!$totalNumberPages){
	     $totalNumberPages = 0;
	}
	# print_r($indexedPages);
	return $totalNumberPages; // Return the total number of indexed pages. 
}

function MSNTotalIndexedPages($url){
	$sURL = urlencode("site:" . $url); // Create the site: url 
	$msnPage = @join( '', @file( "http://search.msn.com/results.aspx?mkt=en-US&form=QBRE&q=" . $sURL )); // Grab the MSN SERPs. Use join to make it one string
	preg_match_all('#<h5>Page 1 of(.*)results#U', $msnPage, $indexedPages ); // Now the fun begins. Lets preg_match that string
	$totalNumberPages = $indexedPages[1][0]; // This was the results of pattern match and the print_r of the array. Note:If this ever breaks, make sure this is correct
	 # print_r($indexedPages);
	return $totalNumberPages; // Return the total number of indexed pages. 
}

function YahooTotalIndexedPages($url){
	# $sURL = urlencode("site:" . $url); // Create the site: url 
	$yahooPage = @join( '', @file( "http://siteexplorer.search.yahoo.com/advsearch?p=" . $url . "&bwm=p&bwmf=u&bwmo=d" . $sURL )); // Grab the Yahoo SERPs. Use join to make it one string
	preg_match_all('#</strong> of about <strong>(.*) #U', $yahooPage, $indexedPages ); // Now the fun begins. Lets preg_match that string
	$totalNumberPages = $indexedPages[1][0]; // This was the results of pattern match and the print_r of the array. Note:If this ever breaks, make sure this is correct
	 # print_r($indexedPages);
	return $totalNumberPages; // Return the total number of indexed pages. 
}

/*...............................................................
     End Total Indexed Pages Code
...............................................................*/


/*...............................................................
     Begin BackLinks Code
...............................................................*/
function GoogleBackLinks($url){
	$lURL = urlencode("link:" . $url); // Create the link: url 
	$googlePage = @join( '', @file( "http://www.google.com/search?num=50&filter=0&q=" . $lURL )); // Grab the Google SERPs. Use join to make it one string
	preg_match_all('#about <b>(.*)</b>#U', $googlePage, $backLinks ); // Now the fun begins. Lets preg_match that string for the total num of backlinks
	$totalBackLinks = $backLinks[1][0]; // This was the results of pattern match and the print_r of the array. Note: If this ever breaks, make sure this is correct
	// Note: This is subject to change. If broken, look here. 
	$googlePage = str_replace("<span dir=ltr>", "", $googlePage ); // Let's clean the page bit
	$googlePage = str_replace("</span>", "", $googlePage ); // More cleaning.
	$googlePage = str_replace("<b>", "", $googlePage ); // More cleaning.
	$googlePage = str_replace("</b>", "", $googlePage ); // More cleaning.
	// end clean
	preg_match_all('#<div class=g><h2 class=r><a class=l href="(.*)"#U', $googlePage, $backLinksPages ); // Now we preg match for the first 20 links
	//print_r($backLinksPages);
	$backLinksArray = array("Total Back Links"=>"$totalBackLinks","Back Link Pages"=>$backLinksPages[1]); // Create one array to return
	return $backLinksArray; // Return the array

}


function MSNBackLinks($url){
	$lURL = urlencode("link:" . $url . " -" . $url); // Create the link: url 
	$msnPage = @join( '', @file( "http://search.msn.com/results.aspx?mkt=en-US&form=QBRE&q=" . $lURL . "&n=50")); // Grab the MSN SERPs. Use join to make it one string
	preg_match_all('#<h5>Page 1 of(.*)results#U', $msnPage , $backLinks ); // Now the fun begins. Lets preg_match that string for the total num of backlinks
	$totalBackLinks = $backLinks[1][0]; // This was the results of pattern match and the print_r of the array. Note: If this ever breaks, make sure this is correct
	// Note: This is subject to change. If broken, look here. 
	$msnPage = str_replace("<a href=", "", $msnPage ); // Let's clean the page bit
	//$msnPage = str_replace("", "", $msnPage ); // Let's clean the page bit
	//$googlePage = str_replace("</span>", "", $googlePage ); // More cleaning.
	//$googlePage = str_replace("<b>", "", $googlePage ); // More cleaning.
	//$googlePage = str_replace("</b>", "", $googlePage ); // More cleaning.
	// end clean
	preg_match_all('#<h3>"(.*)" gping#U', $msnPage, $backLinksPages ); // Now we preg match for the first 20 links
	//print_r($backLinksPages);
	$backLinksArray = array("Total Back Links"=>"$totalBackLinks","Back Link Pages"=>$backLinksPages[1]); // Create one array to return
	return $backLinksArray; // Return the array

}

function YahooBackLinks($url){
	# $lURL = urlencode("link:" . $url . " -" . $url); // Create the link: url 
	$yahooPage = @join( '', @file("http://siteexplorer.search.yahoo.com/advsearch?p=" . $url . "&bwm=i&bwmf=a&bwms=p")); // Grab the MSN SERPs. Use join to make it one string
	preg_match_all('#</strong> of about <strong>(.*) #U', $yahooPage , $backLinks ); // Now the fun begins. Lets preg_match that string for the total num of backlinks
	$totalBackLinks = $backLinks[1][0]; // This was the results of pattern match and the print_r of the array. Note: If this ever breaks, make sure this is correct
	// Note: This is subject to change. If broken, look here. 
	//$msnPage = str_replace("<a href=", "", $yahooPage ); // Let's clean the page bit
	//$msnPage = str_replace("", "", $msnPage ); // Let's clean the page bit
	//$googlePage = str_replace("</span>", "", $googlePage ); // More cleaning.
	//$googlePage = str_replace("<b>", "", $googlePage ); // More cleaning.
	//$googlePage = str_replace("</b>", "", $googlePage ); // More cleaning.
	// end clean
	preg_match_all('#<h3>"(.*)" gping#U', $msnPage, $backLinksPages ); // Now we preg match for the first 20 links
	//print_r($backLinksPages);
	$backLinksArray = array("Total Back Links"=>"$totalBackLinks","Back Link Pages"=>$backLinksPages[1]); // Create one array to return
	return $backLinksArray; // Return the array

}
/*...............................................................
     End BackLinks Code
...............................................................*/


###############################################################################################################################
#                 END CUSTOM FUNCTIONS FOR SITE ANALYSIS																	  #
#					JOSH AXELMAN   12/16/2006																				  #
###############################################################################################################################


?>