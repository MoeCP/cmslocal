<?php

    function getArticleByParams($p = array(), $max_words = 20, $offset = 0, $where_status = 1)
    {
			        global $conn;
					//$rs = array();
					  
					$qw = "WHERE article_status = '".$where_status."' ";

				    $q = "SELECT article_id, body FROM articles ".$qw;
					$rs = $conn->Execute("SELECT article_id, body FROM articles where article_status = 1") or die(mysql_error());
					$num_rows = mysql_num_rows($rs);
					echo "$num_rows Rows\n";		
					$ret = array();
					if ($rs) {										   	
						while (!$rs->EOF) {
					   $ret[$rs->fields['article_id']] = _trunc($rs->fields['body'], $max_words, $offset);
					          $rs->MoveNext();
										 }
															            $rs->Close();
																        }
															        return $ret;
								 }//end getArticleByParams()


    function _trunc($phrase, $max_words, $offset = 0)
	    {
			        if (trim($phrase) == '') return "";

					        $phrase_array = explode(' ',$phrase);
							        if(count($phrase_array) > $max_words && $max_words > 0)
										            $phrase = implode(' ',array_slice($phrase_array, $offset, $max_words));
									        return $phrase;
											    }


/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings

require_once CRONJOB_INC_ROOT . 'lib' . DS . 'GoogleSearch.php';//load google search API

$gs = new GoogleSearch();

//google search keys
$google_keys = array(
			"rwrinKVQFHLg8QZUvlthnSvU9yhw/VDO",
			"0eUm/xhQFHKAMosVQMmRSDmL3L/BodN8",
			"Fy96f7JQFHIevli4Ohk4IzU5ylDztF5z",
			"CarhmCxQFHIgeBLWx9qLKHZRuSS3VgvZ",
			"Q8K/rjRQFHKTrhjOoCVEpk+UGZNAHoVx",
			"oAIGKqRQFHJN7QJ3f3IMHNVgFkH/j3zz",
			"h0hRExRQFHIVmbXYVmVMfCFByKoQmACN",
			"M34aNQlQFHITeV/oQ2ZiTJopuAiGD4Wz",
			"ACjlBDtQFHIqyMG1WLllD6CtN85CD4eT",
			"es2+dstQFHLFbkmDVa1A/t3mr2LdTpY8",
			"EGNNz+pQFHL6qh0lABOgPVyS/LQPoxWW");

$key = 0;
$i = 0;

$gs->setKey( $google_keys[$key] );//set Google licensing key

$articles = getArticleByParams( NULL, 15, 0, 1 );//get all the article_id and body that article status is 1


foreach( $articles as $article_key => $article )
{
	if( strlen( $article ) != 0 )
	{	
		$gs->setQueryString("\"$article\"");	//set query string to search.
		$search_result = $gs->doSearch();//call search method on GoogleSearch object

		/*
		 *check whether the article is copied from other sites or not
		 *if the article is copied from other sites, set status as '1gc'--Google Clean
		 *if the article is written by author, set status as '1gd'--Possible Duplication
		*/
		$status = ($search_result->getEstimatedTotalResultsCount()===0) ? '1gc' : '1gd';
		$url = "http://www.google.com/search?hl=zh-CN&newwindow=1&q=\"$article\"";
		if( $status=='1gc' )
		{
			Article::setArticleStatus( $article_key, $status, 1 );
		}
		else
		{
			Article::setCheckingURL( $article_key, $status, $url ,1 );
		}
		break;
		$i++;

		if( $i==1 )
		{			
			$i = 0;
			$key++;		
			$gs->setKey( $google_keys[$key] );//set Google licensing new key	
			echo "switch google key\n";
		}
	}
}
?>
