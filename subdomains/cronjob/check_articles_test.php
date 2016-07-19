<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CRONJOB_INC_ROOT . 'lib' . DS . 'GoogleSearch.php';//load google search API
$domain = "http://cp.infinitenine.com";

$gs = new GoogleSearch();

//google search keys
$google_keys = array("kwFVimdQFHJBYBN4cje7+rVRNImwkGEU",
			"RHpWTjJQFHLqzdrhWasr4NPb/Bd9MOuo",
			"rN6VINZQFHIsH+a5gkzOgv1HM2j55Rxr",
			"HqDrF+lQFHIdoDr3xU3EJrreuX5X84nj",
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

$key = rand(0, 14);
$i = 0;

$gs->setKey( $google_keys[$key] );//set Google licensing key

$articles = Article::getArticleByParams( NULL, 15, 0, 1 );//get all the article_id and body that article status is 1

echo " the search result in: ".sizeof($articles);
foreach( $articles as $article_key => $article )
{
	if( strlen( $article ) != 0 )
	{	
		$article = str_replace("’", "'", $article);
		$article = str_replace("“", "\"", $article);
		$article = str_replace("”", "\"", $article);
		$gs->setQueryString("\"$article\"");	//set query string to search.
		$search_result = $gs->doSearch();//call search method on GoogleSearch object
		/*
		 *check whether the article is copied from other sites or not
		 *if the article is copied from other sites, set status as '1gc'--Google Clean
		 *if the article is written by author, set status as '1gd'--Possible Duplication
		*/
		if( is_object( $search_result ) )
		{
			$status = ($search_result->getEstimatedTotalResultsCount()===0) ? '1gc' : '1gd';
			$url = "http://www.google.com/search?hl=en&newwindow=1&q=\"". urlencode($article) . "\"";
			echo "\n{$url}\n";
				Article::setCheckingURL( $article_key, $status, $url ,1 );
				$tables = array(" `articles`  AS ar ", " `campaign_keyword` AS ck ");
				$where = array(" ar.keyword_id=ck.keyword_id ", " ck.copy_writer_id=u.user_id ");
				$params = array(
										'article_id' => $article_key,
										'table'        => $tables,
										'where'      => $where,
									);
				$user = User::getAllCopyWritersByParameters($params);
				$mailer_param = array(
                                  'smtp_host'     => 'smtp.gmail.com',
                                  'smtp_port' => '465',
                                  'smtp_username' => 'production@infinitenine.com',
                                  'smtp_password' => 'i9p139',
                                  'from'                     => 'nichole@searchandsocial.com',
                                  'from_name'         => "Second Step Search",
                                  'smtp_secure'     => 'ssl',
                                  'reply_to'               => 'nichole@searchandsocial.com');
				if (count($user))
				{
					$url = htmlspecialchars($url);
					foreach ($user as $k => $value)
					{
						$address = $value['email'];
						if (strlen($address))
						{
							if( strlen( $value['phone'] )==0 )
							{
								$value['phone'] = "n/a";
							}
							$subject = "Possible Duplicated Article";
							$body = "<div>
													Possible duplicated article:<br />
													{$domain}/article/article_comment_list.php?article_id={$article_key}<br />
													<a href='{$url}' >Google Search Link</a><br />
													please to re-submit <a href='{$domain}/article/article_set.php?article_id={$article_key}&keyword_id={$article_key}' >here</a><br /><br />
													<strong>Writer's Contact Info</strong><br />
													Name:&nbsp;{$value['first_name']}&nbsp;{$value['last_name']}<br />
													Email:&nbsp;{$value['email']}<br />
													Phone:&nbsp;{$value['phone']}<br />
											</div>";
							echo "\n{$body}\n";
							send_smtp_mail( $address, $subject, $body, $mailer_param );
						}
					}
				}
		}
		$i++;
		if( $i==5 )
		{			
			$i = 0;
			$key=rand(0, 14);
			$gs->setKey( $google_keys[$key] );//set Google licensing new key	
		}
	}
}
?>
