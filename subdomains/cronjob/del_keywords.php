<?php
	require_once 'pre_cron.php';//parameter settings
    Campaign::deleteKeywordByKeywordIdScope(16978, 17057);

/**
SELECT COUNT(article_id) AS count FROM articles WHERE keyword_id >= 16978 AND keyword_id <= 17057;
SELECT COUNT(keyword_id) AS count FROM campaign_keyword WHERE keyword_id >= 16978 AND keyword_id <= 17057;
SELECT COUNT(keyword_id) AS count FROM user_payment_history WHERE keyword_id >= 16978 AND keyword_id <= 17057;

DELETE FROM articles_version_history WHERE keyword_id >= 16978 AND keyword_id <= 17057;

DELETE FROM articles WHERE keyword_id >= 16978 AND keyword_id <= 17057;

DELETE FROM user_payment_history WHERE keyword_id >= 16978 AND keyword_id <= 17057;

DELETE FROM campaign_keyword WHERE keyword_id >= 16978 AND keyword_id <= 17057;
 */
?>