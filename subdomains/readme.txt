#added by snug xu 2007-11-04 22:19
1. fix bug: client approval to editor reject
2.  copewriter disapproval email go to Chris Stout instead (Chris@ckmg.com <mailto:Chris@ckmg.com>)?
3.  put full name rather than user name http://admin.ckmg.com/client_campaign/keyword_list.php?campaign_id=87

#add by Liu ShuFen 2007-11-05
1. show the information of copywriter and campaign http://admin.ckmg.com/client_campaign/cp_campiagn_reprot.php.
2  #sql:
   2.1. SELECT count(DISTINCT ck.campaign_id, us.user_id) AS num 
	FROM campaign_keyword AS ck 
	LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) 
	LEFT JOIN articles AS ar ON ( ar.keyword_id = ck.keyword_id ) 
	LEFT JOIN article_action AS aa ON ( aa.article_id = ar.article_id ) 
	LEFT JOIN users as us ON (us.user_id = ck.copy_writer_id) 
	WHERE 1 AND aa.created_time >='2007-11-01 00:00:00' 
	AND aa.created_time <='2007-12-01 00:00:00' 
	AND aa.status =4 AND aa.new_status =5 
	AND aa.curr_flag=1 
	AND ( ar.article_status =5 OR ar.article_status =6 ) 
	ORDER BY ck.copy_writer_id 

   2.2. SELECT DISTINCT ck.campaign_id, us.user_id, us.user_name, us.first_name, us.last_name, us.status, us.email 
	FROM campaign_keyword AS ck 
	LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) 
	LEFT JOIN articles AS ar ON ( ar.keyword_id = ck.keyword_id ) 
	LEFT JOIN article_action AS aa ON ( aa.article_id = ar.article_id ) 
	LEFT JOIN users as us ON (us.user_id = ck.copy_writer_id) 
	WHERE 1 
	AND aa.created_time >='2007-11-01 00:00:00' 
	AND aa.created_time <='2007-12-01 00:00:00' 
	AND aa.status =4 AND aa.new_status =5 
	AND aa.curr_flag=1 AND ( ar.article_status =5 OR ar.article_status =6 ) 
	ORDER BY ck.copy_writer_id 

   2.3. SELECT cc.campaign_id, cc.campaign_name, ck.article_type, us.user_id, COUNT( DISTINCT ck.keyword_id ) AS num 
	FROM campaign_keyword AS ck 
	LEFT JOIN articles AS ar ON ( ar.keyword_id = ck.keyword_id ) 
	LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) 
	LEFT JOIN users as us ON (us.user_id = ck.copy_writer_id) 
	LEFT JOIN article_action AS aa ON ( aa.article_id = ar.article_id ) 
	WHERE 1 AND aa.created_time >='2007-11-01 00:00:00' 
	AND aa.created_time <='2007-12-01 00:00:00' 
	AND aa.status =4 
	AND aa.new_status =5 
	AND aa.curr_flag=1 
	AND ( ar.article_status =5 OR ar.article_status =6 ) 
	AND ck.copy_writer_id IN (19,20,22,28,44,46,57,59,63,68,86,90,97,120,124,127,128,132,133,144,145,146) 
	GROUP BY ck.campaign_id, ck.article_type, ck.copy_writer_id ORDER BY ck.copy_writer_id

#add by Liu ShuFen 11:32 2007-11-6 
1. add menu: "copywrite campaign report" to file：admin/cms_menu.php

#Add by Liu ShuFen 12:56 2007-11-8
1. modify "campaign requirement" to "additional style guide" on the page: http://admin.ckmg.com/client_campaign/client_campaign_set.php?campaign_id=68
2. add "additional style guide" to page: http://admin.ckmg.com/client_campaign/campaign_style_guide.php
   when the copywriter view style guide
3. add a "preview" button to the page: http://admin.ckmg.com/client_campaign/keyword_list.php?campaign_id=68

#add by liu shu fen 9:33 2007-11-15
1. add a "download latest articles" on the client index page
2. add file:create_xml.php and download_latest_articles.php to the ckmediagroup\client\article
   add function: createXML() in file:include\utils.php
   add function: getDownloadInfo(), getCampaignsByClientId(), createXML() in file:include\Article.class.php
   modify function :getInfo() in file:include\Campaign.class.php

1. add a drop-down list of "general notes" to page:admin\client_campaign\campaign_notes.php
2. add two links to admin\cms_menu.php: "General Notes" and "Add General Notes"
3. add two files to fold admin\client_campaign: general_notes.php and add_general_notes.php
   general_notes.php is used for list all the general notes. the left of the page have two buttons one is updte
   used for update the information of note. The other is delete used for delete the note.
4. add functions: getGeneralNotes, delGeneralNotes and addGeneralNotes to file include\campaign_notes.class.php  

//add by liu shu fen 16:38 2007-12-6
1. add two menu to "home": Manual Content List and Add Manual Content
2. add one folder: manual_content to root: /admin 
   add three files into it: manual_content_list.php, add_manual_content.php and view_manual_content.php
   add one foler: manual_content to root /smarty
   add two files into it: manual_content_list.html, add_manual_content.html and and view_manual_content.html
   add one file manual_content.class.php to root /include. it includes functions of getManualContent(),
       addManualContent() and delManualByContentId().
3. Description: 
  Users have permission more than 5 can see these two menu. Manual Content List shows the list of manual 
  contents. with buttons of "view", "update" and "delete", users can modify the content. Add Manual Content can add  a new content.
  copywrite can see the manual cotent list and view the content.

add one mune to homepage: add manual content category, it add records into "preference" to add content category selection.

#add by liu shu fen 11:38 2007-12-11
1. write cronjob files 
   "add_manual_content_category". this uses "preference" table and add categories for manual_content.
   "add_category", it uses "category" table to add category.

#SQL:
#add by liu shu fen 9:33 2007-11-15
CREATE TABLE `article_download_log` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`client_id` int( 11 ) NOT NULL ,
`campaign_ids` text CHARACTER SET utf8 NOT NULL ,
`article_ids` mediumtext CHARACTER SET ucs2 NOT NULL ,
`imported_start` datetime NOT NULL ,
`imported_end` datetime NOT NULL ,
`curr_flag` tinyint( 4 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

# add by Liu ShuFen 15:04 2007-11-27
CREATE TABLE `general_notes` (
 `general_note_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `subject` varchar(255) NOT NULL,
 `body` TEXT NOT NULL ,
 `created_by` int(11) NOT NULL ,
 `created_role` VARCHAR( 128 ) NOT NULL ,
 `created` DATETIME NOT NULL DEFAULT '00-00-00 00:00:00'
 ) ENGINE = innodb CHARACTER SET utf8 COLLATE utf8_general_ci;

#add by liu shu fen 10:31 2007-12-5
CREATE TABLE `manual_content` (
 `content_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
 `title` varchar( 100 ) NOT NULL default '',
 `introtext` mediumtext NOT NULL ,
 `full_text` mediumtext NOT NULL ,
 `category` varchar(255) NOT NULL,
 `created` datetime NOT NULL default '0000-00-00 00:00:00',
 `created_by` int( 11 ) unsigned NOT NULL default '0',
 `modified` datetime NOT NULL default '0000-00-00 00:00:00',
 `modified_by` int( 11 ) unsigned NOT NULL default '0',
 `version` int( 11 ) unsigned NOT NULL default '1',
 `state` tinyint( 3 ) NOT NULL default '0',
  PRIMARY KEY ( `content_id` )
  ) ENGINE = MYISAM DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;
#added by snug xu 2007-12-24 17:06 
#cp_candidates数据, 请导入cp_candidates.sql文件

DROP TABLE IF EXISTS `cp_candidates`;
CREATE TABLE `cp_candidates` (
  `candidate_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `confidentional_info` text NOT NULL,
  `birthday` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `from_where` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `opt_address` varchar(255) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `state` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `writing_sample` text NOT NULL,
  `resume_file` varchar(255) default NULL,
  `is_active` tinyint(1) NOT NULL default '0',
  `is_sent` tinyint(1) NOT NULL default '0',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`candidate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# added by snug xu 2007-12-26 13:38
# if keyword_status =0, copywriter start to write articles for the keywords after client who is in possession of those keywords approved them
#  keyword_status = -1 means don't need to let client approval
#  keyword_status =0 means need to let client approval
#  keyword_status = 1 means client approved those keywords
ALTER TABLE `campaign_keyword` ADD `keyword_status` TINYINT( 4 ) NOT NULL DEFAULT '-1' AFTER `status` ;
ALTER TABLE `articles` CHANGE `approval_date` `approval_date` DATETIME NULL ;
ALTER TABLE `articles` CHANGE `acurr_dl_time` `curr_dl_time` DATETIME NULL ;
ALTER TABLE `articles` CHANGE `paid_time` `paid_time` DATETIME NULL ;
ALTER TABLE `articles` CHANGE `checking_url` `checking_url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;


# add by liu shu fen 18:19 2007-12-20
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `category` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`category_id`),
  KEY `Parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- 
-- 导出表中的数据 `category`
-- 

INSERT INTO `category` (`category_id`, `parent_id`, `category`) VALUES 
(1, 0, 'Arts &amp; Entertainment'),
(2, 0, 'Automotive'),
(3, 0, 'Business &amp; Finance'),
(4, 0, 'Education'),
(5, 0, 'Health &amp; Wellness'),
(6, 0, 'Home Improvement'),
(7, 0, 'Legal'),
(8, 0, 'Lifestyle'),
(9, 0, 'Local'),
(10, 0, 'News'),
(11, 0, 'Opinion/Editorial'),
(12, 0, 'Political'),
(13, 0, 'Seniors'),
(14, 0, 'Sports'),
(15, 0, 'Travel');

DROP TABLE IF EXISTS `user_calendar`;
CREATE TABLE `user_calendar` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `user_name` varchar(255) NOT NULL,
  `c_date` date NOT NULL,
  `role` varchar(255) NOT NULL,
  `is_free` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `preference` CHANGE `pref_table` `pref_table` ENUM( 'users','client','client_campaign','campaign_keyword','articles','cp_candidates','cp_campaign_ranking') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'users';

#add by liu shu fen 20:18 2008-1-15
DROP TABLE IF EXISTS `cp_campaign_ranking`;
CREATE TABLE `cp_campaign_ranking` (
  `ranking_id` int(11) NOT NULL auto_increment,
  `campaign_id` int(11) NOT NULL,
  `copywriter_id` int(11) NOT NULL,
  `readability` tinyint(4) NOT NULL,
  `informational_quality` tinyint(4) NOT NULL,
  `timeliness` tinyint(4) NOT NULL,
  `comments` text,
  `ranking` float NOT NULL,
  PRIMARY KEY  (`ranking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


#added by nancy 2009-04-14 14:32
这是一个稳定的单语言版本，

#added by nancy xu 2009-09-25 10:29
# client_approval_date recode the client approval time
ALTER TABLE `articles` ADD `client_approval_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `approval_date` ;
ALTER TABLE `articles_version_history` ADD `client_approval_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `approval_date` ;
UPDATE `articles_version_history` SET `client_approval_date` = `approval_date` WHERE  `article_status` REGEXP '5|6';
UPDATE `articles` SET `client_approval_date` = `approval_date` WHERE  `article_status` REGEXP '5|6';


#added by nancy xu 2009-09-27 13:56
CREATE TABLE `notifications` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT NOT NULL DEFAULT '0',
`role` VARCHAR( 255 ) NOT NULL ,
`generate_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`field_name` VARCHAR( 255 ) NOT NULL ,
`campaign_name` VARCHAR( 255 ) NOT NULL ,
`campaign_id` INT NOT NULL DEFAULT '0',
`notes` TEXT NOT NULL 
) ENGINE = MYISAM ;
ALTER TABLE `notifications` ADD `total` INT NOT NULL DEFAULT '0';
ALTER TABLE `notifications` CHANGE `id` `notification_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

#added by nancy xu 2009-10-14 17:12
# http://docs.google.com/a/infinitenine.com/Doc?docid=0AdGPm5Tx_F7jZGRqNmgya3dfMTEzZjlkcWpkNQ&hl=en

#added by nancy xu 2009-10-27 11:05
CREATE TABLE `user_performance` (
`user_performance_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'copy writer' ) NOT NULL ,
`readability` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0.00',
`informational_quality` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0.00',
`timeliness` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0.00',
`ranking` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0.00',
`editor_approved` INT NOT NULL DEFAULT '0',
`client_approved` INT NOT NULL DEFAULT '0',
`total` INT NOT NULL DEFAULT '0',
`pct_editor_approved` VARCHAR( 20 ) NOT NULL ,
`pct_client_approved` VARCHAR( 20 ) NOT NULL ,
PRIMARY KEY ( `user_performance_id` ) 
) ENGINE = MYISAM;
CREATE TABLE `seq_user_performance_id` (
  `id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;
INSERT INTO `seq_user_performance_id` (`id`) VALUES 
(0);
RENAME TABLE `i9cms`.`seq_user_performance_id` TO `i9cms`.`seq_user_performance_user_performance_id` ;
ALTER TABLE `user_performance` ADD `user_name` VARCHAR( 255 ) NOT NULL AFTER `user_id` ,
ADD `first_name` VARCHAR( 255 ) NOT NULL AFTER `user_name` ,
ADD `last_name` VARCHAR( 255 ) NOT NULL AFTER `first_name` ,
ADD `email` VARCHAR( 255 ) NOT NULL AFTER `last_name` ;
ALTER TABLE `preference` CHANGE `pref_table` `pref_table` ENUM( 'users', 'client', 'client_campaign', 'campaign_keyword', 'articles', 'cp_candidates', 'cp_campaign_ranking', 'manual_content', 'candidates' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'users';
#end

#added by nancy xu 2009-10-28 16:42
#copywriter application form
ALTER TABLE `users` ADD `candidate_id` INT NOT NULL DEFAULT '0',
ADD `country` VARCHAR( 60 ) NULL ;
CREATE TABLE `seq_candidates_candidate_id` (
`id` INT NOT NULL DEFAULT '0'
) ENGINE = MYISAM;
INSERT INTO `seq_candidates_candidate_id` ( `id` )
VALUES (
'0'
);
CREATE TABLE `candidates` (
`candidate_id` INT NOT NULL ,
`first_name` VARCHAR( 255 ) NOT NULL ,
`last_name` VARCHAR( 255 ) NOT NULL ,
`sex` ENUM( 'male', 'female' ) NOT NULL ,
`email` VARCHAR( 255 ) NOT NULL ,
`city` VARCHAR( 255 ) NOT NULL ,
`state` VARCHAR( 255 ) NOT NULL ,
`country` VARCHAR( 60 ) NOT NULL ,
`phone` VARCHAR( 60 ) NOT NULL ,
`education` VARCHAR( 255 ) NOT NULL ,
`field_of_study` VARCHAR( 255 ) NOT NULL ,
`dob` DATETIME NOT NULL ,
`writing_background` VARCHAR( 1000 ) NOT NULL ,
`experience` VARCHAR( 60 ) NOT NULL ,
`published_work` TEXT NOT NULL ,
`writing_sample` TEXT NOT NULL ,
`work_in_us` TINYINT NOT NULL DEFAULT '0',
`status` VARCHAR( 60 ) NOT NULL DEFAULT 'new',
`date_applied` DATETIME NOT NULL ,
PRIMARY KEY ( `candidate_id` )
) ENGINE = MYISAM;
#end
#added by nancy xu 2009-11-04 16:51
ALTER TABLE `candidates` CHANGE `dob` `dob` DATE NOT NULL;
#added by nancy xu 2009-11-05 13:02
ALTER TABLE `candidates` ADD `address` VARCHAR( 255 ) NOT NULL AFTER `state` ;
INSERT INTO `preference` (`pref_id`, `pref_table`, `pref_field`, `pref_value`) VALUES 
(1686, 'candidates', 'education', 'High School'),
(1687, 'candidates', 'education', 'Associates'),
(1688, 'candidates', 'education', 'Bachelors'),
(1689, 'candidates', 'education', 'Masters'),
(1690, 'candidates', 'education', 'Phd'),
(1691, 'candidates', 'experience', '0-1'),
(1692, 'candidates', 'experience', '2-3'),
(1693, 'candidates', 'experience', '3-5'),
(1694, 'candidates', 'experience', '5+'),
(1695, 'candidates', 'writing_background', 'Published online'),
(1696, 'candidates', 'writing_background', 'Published in print'),
(1697, 'candidates', 'writing_background', 'SEO writing experience'),
(1698, 'candidates', 'writing_background', 'Marketing writing experience'),
(1699, 'candidates', 'writing_background', 'Research-based writing experience'),
(1700, 'candidates', 'writing_background', 'Editorial experience'),
(1701, 'candidates', 'writing_background', 'Fact-checking experience'),
(1702, 'candidates', 'sex', 'male'),
(1703, 'candidates', 'sex', 'female');

#added by nancy xu 2009-11-11 18:58
ALTER TABLE `candidates` ADD `resume_file` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `candidates` CHANGE `first_name` `first_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `city` `city` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `state` `state` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
UPDATE `preference` SET `pref_value` = '2-4' WHERE `preference`.`pref_id` =1692  AND pref_table = 'candidates' AND pref_field = 'experience'  ;
DELETE FROM `preference` WHERE `preference`.`pref_id` = 1693 AND pref_table = 'candidates' AND pref_field = 'experience';

#added by nancy xu 2009-12-11 10:07
#sql
ALTER TABLE `articles` ADD `total_words` INT NOT NULL DEFAULT '0';
ALTER TABLE `articles_version_history` ADD `html_title` VARCHAR( 255 ) NULL ,
ADD `lastcheck` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `curr_dl_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `checking_url` VARCHAR( 255 ) NULL ,
ADD `google_approved_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `target_pay_month` INT NOT NULL DEFAULT '0',
ADD `paid_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `is_canceled` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD `action_status` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD `rating` TINYINT NOT NULL DEFAULT '3',
ADD `is_rated` TINYINT NOT NULL DEFAULT '0',
ADD `cp_updated` DATETIME NULL ,
ADD `total_words` INT NOT NULL DEFAULT '0'
#end

#added by nancy xu 2009-12-11 17:37
ALTER TABLE `users` ADD `pay_pref` ENUM( 'Check', 'Direct Deposit', 'Paypal' ) NOT NULL ,
ADD `form_submitted` ENUM( 'W-9', 'Contract', 'Direct Deposit' ) NOT NULL ,
ADD `notes` TEXT NULL ;
ALTER TABLE `users` CHANGE `pay_pref` `pay_pref` TINYINT NOT NULL ,
CHANGE `form_submitted` `form_submitted` TINYINT NOT NULL;
ALTER TABLE `articles_version_history` CHANGE `approval_date` `approval_date` DATETIME NULL;
#end

#added by nancy xu 2009-12-12 1:27
1. Change “Type Cost” to “Article Cost per word”.   The user will enter cost on a per-word basis. 
2. After clicking on Add New Type to This Campaign, there is a popup window.  In the window, please change “Default Cost for This Campaign” to “Default Cost per word for this campaign”.  The user enters the per-word cost. 
3. Enable Force Client Approve (for admin)
4. After client approves article, user gets directed to the wrong page - fixed
5.  User can Review Article After submission
6. Allow Duplicate Keywords for a campaign
7. http://content.secondstepsearch.com/client_campaign/client_list.php - fixed bug
8.Copywriter Accounting User Flow Changes
9. add Payment Preference, Forms Submitted,User Notes to  User Information Setting 

#added by nancy xu 2009-12-14 15:14
 Please add a check box to the left of “number”.  Checking this box selects every article on the page.
 Add a title to the page: “Batch Client Approve”.  The title can appear above the words “Campaign Actions”
 Add instructions: “Please select articles to Force Client Approve, then click on the Force Client Approve button.” 
#end

#added by nancy xu 2009-12-15 17:03
Add Article Timestamps for article comments list page 
change keyword list page
	1. Remove “Article Number” field
	2. Add a field called “Submit Date”.  This is the date the copywriter submitted the article.
	3. Change “Copywriter Name” to “Copywriter” 
	4. Batch Client Approve Page, add title
change payment ajust
	1. fixed bug: after keyword was canceled, it still contain in pay total
	2.Pay adjust page should be for adjusting payments only.  There should be no campaign actions on this page.  Remove any campaign actions.  I circled the part you should remove.
	3.Change the breadcrumb to : Copywriter Accounting > Payment Adjust 
	4. Explain to me what “Cancel keyword” does.  Does it cancel the payment for the keyword? 
#end

#added by nancy xu 2009-12-16 15:25
#sql
ALTER TABLE `users` CHANGE `form_submitted` `form_submitted` VARCHAR( 20 ) NOT NULL;
ALTER TABLE `users` CHANGE `pay_pref` `pay_pref` VARCHAR( 2 ) NULL DEFAULT NULL;
#task
1. fixed bug: I got an email saying this article is a possible duplication.  However, the status on the article is google clean.  Shouldn't the status be "Possible Duplication"? 
2. Change “Date Start” > “Start Date”, Change “Date End” > “Due Date” 
3. When adding articles, user should be able to define Start Date and Due Date for all articles.
# Make the Default Start Date the date article is being created.  In other words, the default start date is today’s date.  (User can adjust this date.)
# Make the Default Due Date to be one week from Start Date (User can adjust this date.)
4. Add a field after Pay Total called: Pay Amount.  This is the amount to be paid, according to the invoice. 
5. Change “Type Cost” to “Type Cost per word” 
6.  Default Payment Preference should be blank.  The user will then select from the choices.
• Forms submitted should NOT be a dropdown.  It should display three options with a check box next to each option. 
#end

#added by nancy xu 2009-12-24 10:15
#types format:
# array(
#		'type1' => array('name' =>xxx, 'total'=>xxx, 'total_cost' => xxx), 
#		'type2' => array('name' =>xxx, 'total'=>xxx, 'total_cost' => xxx)
# )
ALTER TABLE `cp_payment_history` ADD `types` TEXT NOT NULL ;
ALTER TABLE `cp_payment_history` CHANGE `types` `types` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `cp_payment_history` ADD `total` INT NOT NULL AFTER `payment` ;
ALTER TABLE `cp_payment_history` CHANGE `total` `total` INT( 11 ) NULL;
#sql
#work
Add Payment History to copywriter part
http://content.secondstepsearch.com/client_campaign/cp_google_approved_list.php
Change “confirm it” > “Approve” 
change copywriter overview
http://content.secondstepsearch.com/index.php
Add a link to “Overview” section: “Set your payment preference”.  This allows the copywriter to set their payment preference (Check, Direct Deposit, Paypal).  This adjusts the Payment Preference field in the User’s Information Setting.
bug fixed:
http://content.secondstepsearch.com/client_campaign/cp_acct_report.php?campaign_id=&perPage=25&month=200912&status=0
The drop down dates next to Payment status don’t do anything.
#added by nancy xu 2009-12-31 10:06
#sql
ALTER TABLE `cp_payment_history` CHANGE `memo` `memo` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

#added by nancy xu 2010-01-06 14:03
ALTER TABLE `notifications` ADD `is_hidden` TINYINT NOT NULL DEFAULT '0' AFTER `total` ;

#added by nancy xu 2010-01-25 17:13
ALTER TABLE `article_payment_log` ADD `pay_month` INT NOT NULL DEFAULT '0',
ADD `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'copy writer' ) NOT NULL DEFAULT 'copy writer',
ADD `is_canceled` INT NOT NULL DEFAULT '0',
ADD `client_id` INT NOT NULL DEFAULT '0';
ALTER TABLE `article_cost_history` ADD `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'copy writer' ) NOT NULL DEFAULT 'copy writer' AFTER `user_id` ;
ALTER TABLE `cp_payment_history` ADD `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'copy writer' ) NOT NULL DEFAULT 'copy writer';
ALTER TABLE `article_type` ADD `editor_cost` DECIMAL( 8, 2 ) NOT NULL ,
ADD `cp_cost` DECIMAL( 8, 2 ) NOT NULL ;
ALTER TABLE `article_cost` ADD `editor_cost` DECIMAL( 8, 2 ) NOT NULL ,
ADD `cp_cost` DECIMAL( 8, 2 ) NOT NULL ;
ALTER TABLE `article_cost` CHANGE `cost_per_article` `cost_per_article` DECIMAL( 8, 3 ) NOT NULL ,
CHANGE `editor_cost` `editor_cost` DECIMAL( 8, 3 ) NOT NULL ,
CHANGE `cp_cost` `cp_cost` DECIMAL( 8, 3 ) NOT NULL;
ALTER TABLE `article_cost_history` CHANGE `cost_per_article` `cost_per_article` DECIMAL( 8, 3 ) NOT NULL ,
CHANGE `total_cost` `total_cost` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `article_type` CHANGE `type_cost` `type_cost` DECIMAL( 8, 3 ) NOT NULL ,
CHANGE `editor_cost` `editor_cost` DECIMAL( 8, 3 ) NOT NULL ,
CHANGE `cp_cost` `cp_cost` DECIMAL( 8, 3 ) NOT NULL;
#added  by nancy xu 2010-01-29 
ALTER TABLE `article_payment_log` CHANGE `paid_time` `paid_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
#added by nancy xu 2010-02-04 16:30
ALTER TABLE `campaign_keyword` ADD `mapping_id` VARCHAR( 255 ) NULL ;

#added by nancy xu 2010-01-31 17:08
update  `cp_payment_history` set `role` ='copy writer';
update  `article_type` set `cp_cost` = `type_cost`;
update  `article_cost` set `cp_cost` = `cost_per_article`;
UPDATE article_payment_log AS apl, client_campaigns AS c SET apl.client_id = c.client_id WHERE c.campaign_id=apl.campaign_id;
update  `article_payment_log` set `pay_month` = `month` where `pay_month` = 0;
#UPDATE article_payment_log AS apl, articles AS ar, campaign_keyword as ck  set apl.month = DATE_FORMAT(ar.client_approval_date,'%Y%m'), apl.is_canceled=ar.is_canceled WHERE ar.article_id = apl.article_id AND (ar.article_status = '5'  or ar.article_status='6') and apl.role='copy writer' and apl.user_id = ck.copy_writer_id;
UPDATE article_payment_log AS apl, articles AS ar, campaign_keyword as ck , article_action AS aa 
set apl.month = DATE_FORMAT(aa.created_time,'%Y%m'), apl.is_canceled=ar.is_canceled 
WHERE aa.article_id=apl.article_id AND aa.status = '4' AND aa.new_status = '5' AND aa.curr_flag = 1 AND aa.copy_writer_id=apl.user_id 
AND ar.article_id = apl.article_id AND apl.user_id = ck.copy_writer_id AND (ar.article_status = '5'  or ar.article_status='6') and apl.role='copy writer' ;
#added by nancy xu 2010-02-02 13:47
ALTER TABLE `users_categories` ADD `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'copy writer' ) NOT NULL DEFAULT 'copy writer';
#最后要运行一次
UPDATE article_payment_log AS apl, articles AS ar, campaign_keyword as ck set apl.pay_month = ar.target_pay_month,  apl.is_canceled=ar.is_canceled WHERE ar.article_id = apl.article_id and apl.role='copy writer' and apl.user_id = ck.copy_writer_id and ar.target_pay_month > 0; 
#在做editor payment的操作之前要先设置editor cost per word;
#added by nancy xu 2010-02-20 16:10
ALTER TABLE `campaign_keyword` ADD `optional1` VARCHAR( 255 ) NOT NULL ,
ADD `optional2` VARCHAR( 255 ) NOT NULL ,
ADD `optional3` VARCHAR( 255 ) NOT NULL ,
ADD `optional4` VARCHAR( 255 ) NOT NULL ;
#end
#added by nancy xu 2010-03-05 9:34
CREATE TABLE `bugs` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`subject` VARCHAR( 255 ) NOT NULL ,
`happened` TEXT NOT NULL ,
`raw_happened` TEXT NOT NULL ,
`steps` TEXT NOT NULL ,
`browser` VARCHAR( 255 ) NOT NULL ,
`operating_system` VARCHAR( 255 ) NOT NULL ,
`reported_by` INT NOT NULL DEFAULT '0',
`report_time` DATETIME NOT NULL
) ENGINE = innodb;
ALTER TABLE `bugs` ADD `user_role` VARCHAR( 255 ) NOT NULL ;
#`status` 0 表示未被解决的bug，1表示正在处理中的bug, 2不能解决的bug,3已经解决的bug
ALTER TABLE `bugs` ADD `status` TINYINT NOT NULL DEFAULT '0';
#end
#added by nancy xu 2010-03-09 15:01
ALTER TABLE `client_campaigns` ADD `timestamp` DATETIME NULL ;
#end
#added by nancy xu 2010-03-11 15:54
#0表示未处理 submitted，1表示rejected, 2表示Granted
ALTER TABLE `request_extension` ADD `status` TINYINT NOT NULL DEFAULT '0';
#end
#added by nancy xu 2010-03-15 14:39
ALTER TABLE `campaign_keyword` ADD `length` INT NOT NULL DEFAULT '0',
ADD `translation` TEXT NULL ;
CREATE TABLE `data_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `sssdata` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `others` text,
  `created` datetime NOT NULL,
  `parsed` datetime DEFAULT NULL,
  `sssreply` text,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `campaign_keyword` ADD `deadline` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `campaign_keyword` ADD `vertical` INT NOT NULL DEFAULT '0',
ADD `cancel_memo` TEXT NULL ;
#end

#added by nancy xu 2010-03-16 13:55
CREATE TABLE `client_users` (
`client_user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user` VARCHAR( 255 ) NOT NULL ,
`apikey` VARCHAR( 255 ) NOT NULL ,
`campaign_id` INT NOT NULL DEFAULT '0',
`client_id` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
ALTER TABLE `campaign_keyword` CHANGE `deadline` `deadline` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `campaign_keyword` CHANGE `optional1` `optional1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `optional2` `optional2` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `optional3` `optional3` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `optional4` `optional4` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end
#added by nancy xu 2010-03-19 13:38
# is_bk means is backup, 1 means backup row, 0 means current row
ALTER TABLE `request_extension` ADD `is_bk` TINYINT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-04-02 10:24
CREATE TABLE `order_campaigns` (
  `order_campaign_id` int(11) NOT NULL DEFAULT '0',
  `client_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `campaign_name` varchar(200) DEFAULT NULL,
  `total_budget` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost_per_article` decimal(8,2) NOT NULL DEFAULT '0.00',
  `campaign_site_url` varchar(240) DEFAULT NULL,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `campaign_requirement` text,
  `campaign_date` date DEFAULT '0000-00-00',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `monthly_recurrent` tinyint(1) DEFAULT '0',
  `meta_param` tinyint(4) NOT NULL DEFAULT '0',
  `title_param` tinyint(4) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` datetime DEFAULT NULL,
  `download_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`order_campaign_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `seq_order_campaigns_id` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 
-- 导出表中的数据 `seq_order_campaigns_id`
-- 

INSERT INTO `seq_order_campaigns_id` (`id`) VALUES 
(0);
#end

#added by nancy xu 2010-04-06 11:14
ALTER TABLE `order_campaigns` ADD `editor_cost` FLOAT(10, 2 ) NOT NULL AFTER `cost_per_article` ;
ALTER TABLE `client_campaigns` ADD `editor_cost` FLOAT( 10, 2 ) NOT NULL AFTER `cost_per_article` ; 
ALTER TABLE `client_campaigns` CHANGE `total_budget` `total_budget` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.00',
CHANGE `cost_per_article` `cost_per_article` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `client_campaigns` CHANGE `editor_cost` `editor_cost` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.00';
ALTER TABLE `order_campaigns` CHANGE `total_budget` `total_budget` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.00',
CHANGE `cost_per_article` `cost_per_article` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.00',
CHANGE `editor_cost` `editor_cost` FLOAT( 10, 3 ) NOT NULL DEFAULT '0.00';
#end
#added by nancy xu 2010-04-09 16:19
ALTER TABLE `order_campaigns` ADD `writer_expertise` VARCHAR( 255 ) NOT NULL ,
ADD `content_level` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD `description` TEXT NOT NULL ,
ADD `sample_content` TEXT NOT NULL ,
ADD `keyword_instructions` TEXT NOT NULL ;
ALTER TABLE `client_campaigns` ADD `writer_expertise` VARCHAR( 255 ) NOT NULL ,
ADD `content_level` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD `description` TEXT NOT NULL ,
ADD `sample_content` TEXT NOT NULL ,
ADD `keyword_instructions` TEXT NOT NULL ;
ALTER TABLE `client_campaigns` CHANGE `writer_expertise` `writer_expertise` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `sample_content` `sample_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `keyword_instructions` `keyword_instructions` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end
#added by nancy xu 2010-04-13 10:26
CREATE TABLE `request_extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `copy_writer_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `editor_id` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `days_asked` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `total` int(11) NOT NULL DEFAULT '0',
  `is_bk` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`extension_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
ALTER TABLE `request_extension` ADD `editor_id` INT NOT NULL DEFAULT '0' AFTER `status` ,
ADD `total` INT NOT NULL DEFAULT '0' AFTER `editor_id` ;
#end

#added by nancy xu 2010-04-23 15:37
http://i9cms/mail/set.php?template_id=17
#end
#added by nancy xu 2010-04-28 14:56 
CREATE TABLE `sss_check_logs` (
`log_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`campaign_id` INT NOT NULL ,
`article_id` INT NOT NULL ,
`keyword_id` INT NOT NULL ,
`percent` DECIMAL( 8, 4 ) NOT NULL ,
`article_status` VARBINARY( 10 ) NOT NULL ,
`response_xml` TEXT NOT NULL ,
`created` DATETIME NOT NULL
) ENGINE = innodb;
ALTER TABLE `sss_check_logs` CHANGE `article_status` `article_status` VARCHAR( 10 ) NOT NULL ;
#end
#added by nancy xu 2010-05-10 16:55
ALTER TABLE `sss_check_logs` ADD `copy_writer_id` INT NOT NULL DEFAULT '0'  ;
ALTER TABLE `sss_check_logs` ADD `keyword` VARCHAR( 255 ) NOT NULL AFTER `keyword_id` ;
#end

#added by nancy xu 2010-06-08 15:40
ALTER TABLE `client_campaigns` ADD `archived` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `client_campaigns` ADD `completed_date` DATE NULL ;
#end

#added by nancy xu 2010-06-09 16:45
CREATE TABLE `user_notes` (
`note_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 255 ) NOT NULL ,
`notes` TEXT NOT NULL ,
`category_id` INT NOT NULL ,
`created` DATETIME NOT NULL ,
`created_by` INT NOT NULL ,
`modified` DATETIME NOT NULL ,
`modified_by` INT NOT NULL
) ENGINE = innodb;
CREATE TABLE `user_note_category` (
`category_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`description` TEXT NOT NULL ,
`created` DATETIME NOT NULL ,
`created_by` INT NOT NULL ,
`modified` DATETIME NOT NULL ,
`modified_by` INT NOT NULL
) ENGINE = innodb;
ALTER TABLE `user_notes` ADD `user_id` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-06-11 16:28
ALTER TABLE `users_categories` ADD `level` TINYINT NOT NULL DEFAULT '1',
ADD `description` TEXT NULL ;
#end

#added by nancy xu 2010-06-13 15:04
ALTER TABLE `cp_campaign_ranking` CHANGE `comments` `comments` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2010-07-02 11:43
CREATE TABLE `system_mails` (
`mail_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`subject` VARCHAR( 255 ) NOT NULL ,
`mail_body` TEXT NOT NULL ,
`to_ids` TEXT NOT NULL ,
`ccs` VARCHAR( 255 ) NOT NULL ,
`from` VARCHAR( 255 ) NOT NULL ,
`attachments` TEXT NOT NULL
) ENGINE = innodb;
ALTER TABLE `system_mails` CHANGE `mail_body` `mailbody` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `ccs` `cc_email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `from` `from_email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `system_mails` ADD `email_event` INT NOT NULL ;
ALTER TABLE `system_mails` ADD `status` TINYINT NOT NULL DEFAULT '0';
#0 means pending, 1 means finished
ALTER TABLE `system_mails` CHANGE `attachments` `attachments` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `system_mails` CHANGE `cc_email` `cc_email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `from_email` `from_email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `system_mails` ADD `login_link` VARCHAR( 255 ) NOT NULL ;
#end

#added by nancy xu 2010-07-06 17:31
ALTER TABLE `articles` ADD `rejected_memo` TEXT NULL ;
#end

#added by nancy xu 2010-07-12 17:45
ALTER TABLE `users` ADD `routing_number` VARCHAR( 255 ) NULL AFTER `bank_info` ;
#end

#added by nancy xu 2010-07-13 10:44
ALTER TABLE `client_campaigns` ADD `source` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `client_campaigns` CHANGE `source` `source` INT NOT NULL DEFAULT '0';
#0表示来自系统，source > 0表示来自哪个client_user
#end

#added by nancy xu 2010-07-14 11:34
CREATE TABLE `article_status` (
`article_id` INT NOT NULL ,
`started` TINYINT NOT NULL DEFAULT '0',
`completed` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY ( `article_id` )
) ENGINE = innodb;
#end

#added by nancy xu 2010-07-14 17:03
ALTER TABLE `articles_version_history` ADD `article_number` VARCHAR( 255 ) NOT NULL AFTER `article_id` ;
ALTER TABLE `articles_version_history` ADD `date_assigned` DATETIME NULL ,
ADD `copy_writer_id` INT NOT NULL DEFAULT '0',
ADD `editor_id` INT NOT NULL DEFAULT '0',
ADD `article_type` INT NULL ,
ADD `keyword_description` TEXT NULL ,
ADD `date_start` DATE NULL ,
ADD `date_end` DATE NULL ,
ADD `date_created` DATETIME NULL ,
ADD `keyword_meta` VARCHAR( 255 ) NULL ,
ADD `description_meta` TEXT NULL ,
ADD `mapping_id` VARCHAR( 255 ) NULL ,
ADD `optional1` VARCHAR( 255 ) NULL ,
ADD `optional2` VARCHAR( 255 ) NULL ,
ADD `optional3` VARCHAR( 255 ) NULL ,
ADD `optional4` VARCHAR( 255 ) NULL ;
ALTER TABLE `articles_version_history` CHANGE `body` `body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
#end 

#added by nancy xu 2010-07-15 13:56
ALTER TABLE `article_action` ADD `editor_id` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-07-16 11:10
ALTER TABLE `users` ADD `paypal_email` VARCHAR( 255 ) NULL ;
#end

#added by nancy xu 2010-07-19 10:25
ALTER TABLE `users` ADD `w9pdf` VARCHAR( 255 ) NULL ;
#end

#added by nancy xu 2010-08-02  15:19
CREATE TABLE `user_esign_config` (
`config_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`username` VARCHAR( 255 ) NOT NULL ,
`pwd` VARCHAR( 255 ) NOT NULL ,
`api_key` VARCHAR( 255 ) NOT NULL ,
`esign_url` VARCHAR( 255 ) NOT NULL ,
`gtitle` varchar(255) NOT NULL,
`docs` text,
`is_default` TINYINT NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

CREATE TABLE `user_esigns` (
`esign_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT NOT NULL DEFAULT '0',
`email` VARCHAR( 255 ) NOT NULL ,
`doc_key` VARCHAR( 255 ) NOT NULL ,
`estatus` INT NOT NULL ,
`dstatus` TINYINT NOT NULL ,
`sent` DATETIME NOT NULL ,
`signed` DATETIME NULL ,
`docs` TEXT NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`archived` DATETIME NULL ,
`cancelled` DATETIME NULL ,
`removed` DATETIME NULL ,
`created` DATETIME NOT NULL
) ENGINE = innodb;

ALTER TABLE `user_esigns` ADD `message` TEXT NULL AFTER `email` ;
ALTER TABLE `user_esign_config` ADD `params` TEXT NOT NULL ,
ADD `libs` TEXT NOT NULL ;
ALTER TABLE `user_esign_config` CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `libs` `libs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `user_esigns` CHANGE `filename` `filename` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2010-08-03 13:40
CREATE TABLE `user_esign_logs` (
`log_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`estatus` INT NOT NULL ,
`created` DATETIME NOT NULL ,
`documentVersionKey` VARCHAR( 255 ) NOT NULL ,
`description` TEXT NOT NULL ,
`esign_id` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
ALTER TABLE `user_esigns` ADD `latest_doc_key` VARCHAR( 255 ) NOT NULL AFTER `doc_key` ;
#end

#added by nancy xu 2010-08-04 15:59
CREATE TABLE `user_esign_groups` (
`group_id` INT NOT NULL ,
`email` VARCHAR( 255 ) NOT NULL ,
`message` TEXT NOT NULL ,
`created` DATETIME NOT NULL ,
`user_id` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
ALTER TABLE `user_esigns`
  DROP `user_id`,
  DROP `email`,
  DROP `message`,
  DROP `created`;
ALTER TABLE `user_esign_groups` ADD `title` VARCHAR( 255 ) NOT NULL AFTER `email` ;
ALTER TABLE `user_esign_groups` ADD PRIMARY KEY ( `group_id` ) ;
ALTER TABLE `user_esign_groups` CHANGE `group_id` `group_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `user_esigns` ADD `group_id` INT NOT NULL DEFAULT '0';
#end

#added  by nancy xu 2010-08-05 11:29
ALTER TABLE `client_campaigns` ADD `ordered_by` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `order_campaigns` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2010-08-06 16:15
ALTER TABLE `order_campaigns` ADD `article_tone` TINYINT NOT NULL ,
ADD `ordered_by` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `order_campaigns` ADD `special_instructions` TEXT NULL ;
ALTER TABLE `client_campaigns` ADD `article_tone` TINYINT NOT NULL ,
ADD `special_instructions` TEXT NOT NULL ;
#end

#added by nancy xu 2010-08-08 16:06
CREATE TABLE `suggestions` (
`suggestion_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`from` VARCHAR( 255 ) NOT NULL ,
`role` VARCHAR( 255 ) NOT NULL ,
`created` DATETIME NOT NULL ,
`created_by` INT NOT NULL ,
`sender` VARCHAR( 255 ) NOT NULL ,
`subject` VARCHAR( 255 ) NOT NULL ,
`content` TEXT NOT NULL
) ENGINE = innodb;
ALTER TABLE `bugs` ADD `report_to` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `suggestions` ADD `campaign_id` INT NOT NULL DEFAULT '0';
#end
#added by nancy xu 2010-08-10 16:03
ALTER TABLE `user_esigns` ADD `fields` TEXT NULL AFTER `group_id` ;
#added by nancy xu 2010-08-15 16:00
update `user_esign_config` set `is_default` = '0';	
INSERT INTO `user_esign_config` (`username`, `pwd`, `api_key`, `gtitle`, `esign_url`, `docs`, `is_default`, `params`, `libs`) VALUES 
('copypress@BlueGlass.com', '830blue@', 'X434S6H5CXV2S', 'BlueGlass Interactive Documents', 'http://www.echosign.com/services/EchoSignDocumentService9?wsdl', 'a:3:{i:0;s:47:"BlueGlass Interactive Inc. Contractor Agreement";i:1;s:29:"Blueglass Direct Deposit Form";i:2;s:48:"W-9 (Request for Taxpayer Identification Number)";}', 1, 'a:1:{s:10:"libUpdated";i:1281859515;}', 'a:3:{s:8:"PERSONAL";a:2:{i:0;a:4:{s:11:"documentKey";s:13:"ZD3Y56Q62QG7F";s:12:"modifiedDate";s:25:"2010-07-12T11:16:41-07:00";s:4:"name";s:47:"BlueGlass Interactive Inc. Contractor Agreement";s:5:"scope";s:8:"PERSONAL";}i:1;a:4:{s:11:"documentKey";s:13:"LW6VE3E395F2W";s:12:"modifiedDate";s:25:"2010-06-24T07:47:33-07:00";s:4:"name";s:29:"Blueglass Direct Deposit Form";s:5:"scope";s:8:"PERSONAL";}}s:6:"SHARED";a:2:{i:0;a:4:{s:11:"documentKey";s:13:"ZA3R9K757X336";s:12:"modifiedDate";s:25:"2010-07-11T21:10:17-07:00";s:4:"name";s:20:"BlueGlass Mutual NDA";s:5:"scope";s:6:"SHARED";}i:1;a:4:{s:11:"documentKey";s:13:"NWLZA7I3KXL4T";s:12:"modifiedDate";s:25:"2010-07-08T12:55:29-07:00";s:4:"name";s:31:"Certificate of Merger (revised)";s:5:"scope";s:6:"SHARED";}}s:6:"GLOBAL";a:5:{i:0;a:4:{s:11:"documentKey";s:13:"E5LPD4G637ZXY";s:12:"modifiedDate";s:25:"2010-04-26T09:29:22-07:00";s:4:"name";s:42:"I-9 (Employment Eligibility Verification) ";s:5:"scope";s:6:"GLOBAL";}i:1;a:4:{s:11:"documentKey";s:13:"2JX53633SX645";s:12:"modifiedDate";s:25:"2009-10-15T12:12:27-07:00";s:4:"name";s:32:"Independent Contractor Agreement";s:5:"scope";s:6:"GLOBAL";}i:2;a:4:{s:11:"documentKey";s:13:"2JX4PL6FY4Q6Y";s:12:"modifiedDate";s:25:"2009-10-15T12:11:32-07:00";s:4:"name";s:30:"NDA (Non-Disclosure Agreement)";s:5:"scope";s:6:"GLOBAL";}i:3;a:4:{s:11:"documentKey";s:13:"2JX583N3P572J";s:12:"modifiedDate";s:25:"2009-10-15T12:13:08-07:00";s:4:"name";s:46:"W-4 (IRS Employee&#39;s Withholding Allowance)";s:5:"scope";s:6:"GLOBAL";}i:4;a:4:{s:11:"documentKey";s:13:"2JX56F6UXXXEG";s:12:"modifiedDate";s:25:"2009-10-15T12:12:48-07:00";s:4:"name";s:48:"W-9 (Request for Taxpayer Identification Number)";s:5:"scope";s:6:"GLOBAL";}}}');
#end
#added by nancy xu 2010-08-16 10:13
ALTER TABLE `user_esign_groups` ADD `config_id` INT NOT NULL DEFAULT '0';
#end
#added by nancy xu 2010-08-16 14:04
ALTER TABLE `users` ADD `vendor_id` INT NOT NULL DEFAULT '0',
ADD `w9_status` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-08-26 11:34
ALTER TABLE `users` ADD `bank_name` VARCHAR( 255 ) NULL AFTER `bank_info` ;
ALTER TABLE `candidates` ADD `zip` VARCHAR( 60 ) NOT NULL AFTER `state` ;
ALTER TABLE `users` ADD `city` VARCHAR( 60 ) NOT NULL AFTER `address` ,
ADD `state` VARCHAR( 60 ) NOT NULL AFTER `city` ,
ADD `zip` VARCHAR( 60 ) NOT NULL AFTER `state` ;
#end
#added by nancy xu 2010-08-26 22:16
ALTER TABLE `users` ADD `address2` VARCHAR( 255 )  NULL AFTER `address` ;
ALTER TABLE `users` CHANGE `bank_info` `bank_info` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
#end
#added by nancy xu 2010-08-28 14:49
ALTER TABLE `users` ADD `bank_acct_type` INT NOT NULL DEFAULT '0' AFTER `bank_info` ;
#end
#added by nancy xu  2010-08-29 17:32
ALTER TABLE `candidates` CHANGE `sex` `sex` ENUM( 'male', 'female' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2010-09-03 11:19 for dev server
INSERT INTO `category` ( `category` ) values ('Children'), ('Culinary'), ('Fashion/Lifestyle'), ('Graphic Design'), ('Literature'), ('Legal'), ('Medicine'), ('News'), ('Pop Culture'), ('Technical Writing');
INSERT INTO `category` ( `category` ) values ('Politics & Government'),('Environmental'),('Charities & Society'),('Security & Protection');
#end

#added by nancy xu 2010-09-03 23:14
ALTER TABLE `candidates` ADD `productivity` INT NOT NULL DEFAULT '0' AFTER `candidate_id` ,
ADD `start_date` DATE NULL AFTER `productivity` ,
ADD `hear_from` TEXT NULL AFTER `start_date` ,
ADD `categories` TEXT NULL AFTER `hear_from` ;
ALTER TABLE `candidates` CHANGE `productivity` `productivity` VARCHAR( 255 ) NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-09-06 18:04
ALTER TABLE `client_campaigns` CHANGE `ordered_by` `ordered_by` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `client_campaigns` CHANGE `article_tone` `article_tone` TINYINT( 4 ) NULL ;
#end

#added by nancy xu 2010-09-07 11:09
ALTER TABLE `client_campaigns` ADD `is_sent` TINYINT NOT NULL DEFAULT '0' AFTER `special_instructions` ;
ALTER TABLE `article_payment_log` ADD `date_bill` DATETIME NULL AFTER `paid_time` ;
CREATE TABLE `payment_bills` (
`bill_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`nbill_id` INT NOT NULL DEFAULT '0' COMMENT 'netsuit bill id',
`vendor_id` INT NOT NULL DEFAULT '0',
`customForm` INT NOT NULL DEFAULT '103',
`postingPeriod` VARCHAR( 255 ) NOT NULL ,
`dueDate` VARCHAR( 255 ) NOT NULL ,
`tranDate` VARCHAR( 255 ) NOT NULL ,
`tranId` VARCHAR( 255 ) NOT NULL ,
`userTotal` DECIMAL( 8, 3 ) NOT NULL ,
`memo` VARCHAR( 255 ) NOT NULL ,
`expenseList` TEXT NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `cp_payment_history` ADD `date_bill` DATE NOT NULL AFTER `date_pay` ;
ALTER TABLE `cp_payment_history` CHANGE `date_bill` `date_bill` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
#end

#added by nancy xu 2010-09-08 10:35
ALTER TABLE `article_cost_history` ADD `date_bill` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `article_cost_history`  ENGINE = innodb;
ALTER TABLE `payment_bills`  ENGINE = innodb;
ALTER TABLE `payment_bills` ADD `user_id` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-09-08 22:51
ALTER TABLE `users` ADD `vaddresses` TEXT NULL AFTER `vendor_id` ;
#end

#added by nancy xu 2010-09-13 15:44
ALTER TABLE `articles` ADD `phandle` VARCHAR( 500 ) NULL ;
#end
#added by nancy xu 2010-09-13 17:40
ALTER TABLE `client_campaigns` ADD `has_new` INT NOT NULL DEFAULT '0';	
#end

#added by nancy xu 2010-09-14 10:54
update client_campaigns set has_new=1 WHERE campaign_id in ( select  DISTINCT  campaign_id from campaign_keyword AS ck, articles as ar WHERE ar.keyword_id=ck.keyword_id AND ck.copy_writer_id=0 AND ck.editor_id=0 and ar.article_status = 0 );
ALTER TABLE `campaign_keyword` CHANGE `deadline` `deadline` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2010-09-20 15:31
ALTER TABLE `candidates` ADD `cpermission` INT NOT NULL DEFAULT '0';
ALTER TABLE `order_campaigns` CHANGE `writer_expertise` `writer_expertise` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `order_campaigns` CHANGE `article_tone` `article_tone` TINYINT( 4 ) NULL ;
ALTER TABLE `order_campaigns` CHANGE `is_copy` `copy_from` INT NOT NULL DEFAULT '0'
#end

#added  by nancy xu 2010-09-23 16:49
fixed bug that when client appored by api, don't create payment log in system
#end

#added by nancy xu 2010-09-23 17:50
CREATE TABLE `label_field_description` (
`desc_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 255 ) NOT NULL ,
`unique_key` VARCHAR( 255 ) NOT NULL ,
`description` TEXT NOT NULL ,
`created` DATETIME NOT NULL
) ENGINE = innodb;
INSERT INTO `label_field_description` (`desc_id`, `title`, `unique_key`, `description`, `created`) VALUES 
(3, 'Additional Style Guide', 'ADDITIONAL_STYLE_GUIDE', 'Your style guide creates and defines the quality, guidelines and informational standards for your content. It promotes style-consistency and serves as a reference source and training tool so that we can tailor your content to your needs. Please add specific details about your content, including your requirements for information-quality, style, tone, voice, dos and don’ts to ensure your content is delivered exactly how you want it.', '2010-09-23 17:47:16'),
(4, 'Sample Content', 'SAMPLE_CONTENT', 'When it comes to writing, “showing” is as equally important as “telling.” Include an example of the type of content you would like to emulate. A concrete example helps us create the content you envision. Include links to other websites or an example of content from your own website, highlighting an emphasis on tone, style, voice and informational quality.', '2010-09-23 17:47:16'),
(5, 'Content Instructions', 'CONTENT_INSTRUCTIONS', 'Your content is only as good at the instructions you provide. Keyword instructions set the guidelines for content creation. Include specific instructions that will help us meet your content needs. For example, information such as article word count, special formatting, keyword use, voice and audience will help create your content. Is your content a blog? Is it an article? Be as specific as possible.     ', '2010-09-23 17:48:09'),
(6, 'Special Instructions', 'SPECIAL_INSTRUCTIONS', 'Include any additional instructions pertaining to your content order. For example, if you require content to be delivered via RSS feed directly to multiple websites, please include complete instruction about how you would like your content divided etc. ', '2010-09-23 17:48:09');
#end


#added by nancy xu 2010-09-28 14:25
ALTER TABLE `client` ADD `contact_name` VARCHAR( 255 ) NULL AFTER `company_url` ;
#end

#replicate keywords
#added by nancy xu 2010-09-29 10:40
ALTER TABLE `client_campaigns` ADD `parent_id` INT NOT NULL DEFAULT '0';
ALTER TABLE `order_campaigns` ADD `parent_campaign_id` INT NOT NULL DEFAULT '0';
ALTER TABLE `order_campaigns` CHANGE `copy_from` `parent_id` INT( 11 ) NOT NULL DEFAULT '0';
UPDATE `order_campaigns` AS o1, `order_campaigns` AS o2  SET o1.parent_campaign_id=o2.campaign_id WHERE o1.parent_id=o2.order_campaign_id;
UPDATE `order_campaigns` AS oc, `client_campaigns` AS cc SET cc.parent_id=oc.parent_campaign_id WHERE cc.campaign_id=oc.campaign_id;
ALTER TABLE `client_campaigns` ADD `is_import_kw` TINYINT NOT NULL DEFAULT '0';
#end

#replicate monthly campaigns 
#added by nancy xu 2010-09-29 17:04
#end

// 未检查全部api
get all the campaignIDs and names for a client

<sssRequest>
 <user>blogmarket</user>
 <apikey>Efeer$45k9833</apikey>
 <getallcampaigns>
  <field>campaignName</field>
  <field>campaignId</field>
 </getallcampaigns>
</sssRequest>

reply get all the campaignIDs and names for a client

<sssReply>
<campaign>
 <campaignName>xxx</campaignName>
 <campaignId>xx</campaignId>
</campaign>
<campaign>
 <campaignName>xx</campaignName>
 <campaignId>xx</campaignId>
</campaign>
</sssReply>

#added by nancy xu 2010-10-10 15:05
ALTER TABLE `articles` ADD `updated` TIMESTAMP NOT NULL ;
#end

#added by nancy xu 2010-10-11 10:18
ALTER TABLE `articles_version_history` ADD `updated` DATETIME NOT NULL AFTER `date_created` ;
#end

#added by nancy xu 2010-10-13 11:13
CREATE TABLE `client_settings` (
  `client_setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `article_status` varchar(4) NOT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `to_article_status` varchar(4) NOT NULL,
  `campaign_id` varchar(255) NOT NULL DEFAULT '0',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`client_setting_id`)
) ENGINE=MyISAM
#end

#added by nancy xu 
#当editor完成所有的文章之后，需要发送邮件给client，加一个字段表征是否已经发送邮件给client
ALTER TABLE `client_campaigns` ADD `is_editor_finished` INT NOT NULL DEFAULT '0';
ALTER TABLE `client_campaigns` CHANGE `is_editor_finished` `is_sent_client` TINYINT( 1 ) NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-10-22 10:55
ALTER TABLE `order_campaigns` ADD `cost_per_word` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0';
ALTER TABLE `client_campaigns` ADD `cost_per_word` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0';
#cost_per_article和editor_cost早已经废弃掉
#end

#added by nancy xu 2010-10-28 16:22
ALTER TABLE `client_users` ADD `domain` VARCHAR( 255 ) NOT NULL ,
ADD `token` VARCHAR( 255 ) NOT NULL ,
ADD `apisig` VARCHAR( 5000 ) NOT NULL ,
ADD `passwd` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `client_users` CHANGE `user` `user` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `apikey` `apikey` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `domain` `domain` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `token` `token` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `apisig` `apisig` VARCHAR( 5000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `passwd` `passwd` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `client_users` ADD `subuser` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `client_users` CHANGE `passwd` `subpasswd` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `client_users` ADD `email` VARCHAR( 255 ) NOT NULL AFTER `user` ;
ALTER TABLE `client_users` CHANGE `subpasswd` `subpasswd` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `subuser` `subuser` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `client_users` ADD `description` TEXT NULL AFTER `subuser` ;
ALTER TABLE `client_users` ADD `apitype` VARCHAR( 255 ) NOT NULL ;
#end
#added by nancy xu 2010-11-01 11:34
RENAME TABLE `i9cms`.`seq_order_campaigns_id` TO `i9cms`.`seq_order_campaigns_order_campaign_id` ;
RENAME TABLE `com_i9cms_dev`.`seq_order_campaigns_id` TO `com_i9cms_dev`.`seq_order_campaigns_order_campaign_id` ;
ALTER TABLE `client_campaigns` ADD `order_campaign_id` INT NOT NULL DEFAULT '0';
ALTER TABLE `client_campaigns` CHANGE `order_campaign_id` `order_id` INT( 11 ) NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-11-18 10:46
ALTER TABLE `articles_version_history` ADD `phandle` VARCHAR( 500 ) NULL ;
#end

#article type changes
#added by nancy xu 2010-11-11 16:04
ALTER TABLE `article_type` ADD `parent_id` INT NOT NULL DEFAULT '-1';
ALTER TABLE `article_type` ADD `total_nodes` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-11-18 10:19
ALTER TABLE `article_type` ADD `is_hidden` TINYINT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2010-11-18 11:04
ALTER TABLE `client_campaigns` ADD `article_type` INT NOT NULL DEFAULT '-1';
ALTER TABLE `order_campaigns` ADD `article_type` INT NOT NULL DEFAULT '-1';
#end

#batch create campaign
#added by nancy xu 2010-11-30 12:46
CREATE TABLE `seq_campaign_files_campaign_file_id` (
`id` INT NOT NULL DEFAULT '0',
PRIMARY KEY ( `id` )
) ENGINE = innodb;
CREATE TABLE `seq_campaign_logs_campaign_log_id` (
`id` INT NOT NULL DEFAULT '0',
PRIMARY KEY ( `id` )
) ENGINE = innodb;
INSERT INTO `seq_campaign_files_campaign_file_id` ( `id` )
VALUES (
'0'
);
INSERT INTO `seq_campaign_logs_campaign_log_id` ( `id` )
VALUES (
'0'
);
CREATE TABLE `campaign_files` (
`campaign_file_id` INT NOT NULL DEFAULT '0',
`client_id` INT NOT NULL DEFAULT '0',
`category_id` INT NOT NULL DEFAULT '0',
`article_type` INT NOT NULL DEFAULT '-1',
`date_start` DATE NOT NULL DEFAULT '0000-00-00',
`date_end` DATE NOT NULL DEFAULT '0000-00-00',
`campaign_requirement` TEXT NULL ,
`sample_content` TEXT NULL ,
`keyword_instructions` TEXT NULL ,
`special_instructions` TEXT NULL ,
`ordered_by` VARCHAR( 255 ) NOT NULL ,
`meta_param` TINYINT NOT NULL DEFAULT '0',
`title_param` TINYINT NOT NULL DEFAULT '1',
`date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`creation_user_id` INT NOT NULL DEFAULT '0',
PRIMARY KEY ( `campaign_file_id` )
) ENGINE = innodb;
CREATE TABLE `campaign_logs` (
`campaign_log_id` INT NOT NULL DEFAULT '0',
`domain` VARCHAR( 255 ) NOT NULL ,
`campaign_name` VARCHAR( 255 ) NOT NULL ,
`campaign_id` INT NOT NULL DEFAULT '0',
`keyword` VARCHAR( 255 ) NOT NULL ,
`keyword_description` TEXT NOT NULL ,
`repeat_time` INT NOT NULL DEFAULT '0',
`mapping_id` VARCHAR( 255 ) NOT NULL ,
`optional1` VARCHAR( 255 ) NOT NULL ,
`optional2` VARCHAR( 255 ) NOT NULL ,
`optional3` VARCHAR( 255 ) NOT NULL ,
`optional4` VARCHAR( 255 ) NOT NULL ,
`creation_user_id` INT NOT NULL DEFAULT '0',
`creation_role` VARCHAR( 255 ) NULL ,
`campaign_file_id` INT NOT NULL DEFAULT '0',
`date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY ( `campaign_log_id` )
) ENGINE = innodb;
ALTER TABLE `campaign_files` ADD `is_parsed` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `campaign_files` ADD `filename` VARCHAR( 255 ) NOT NULL AFTER `campaign_file_id` ;
ALTER TABLE `campaign_logs` ADD `is_parsed` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `campaign_files` ADD `creation_role` VARCHAR( 255 ) NOT NULL AFTER `creation_user_id` ;
#end

#added by nancy xu 2010-12-08 14:53
CREATE TABLE `user_month_performance` (
`performance_id` INT NOT NULL ,
`user_id` INT NOT NULL DEFAULT '0',
`role` VARCHAR( 255 ) NOT NULL ,
`report_month` INT NOT NULL DEFAULT '0',
`readability` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`informational_quality` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`timeliness` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`ranking` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`editor_approved` INT NOT NULL DEFAULT '0',
`client_approved` INT NOT NULL DEFAULT '0',
`total` INT NOT NULL DEFAULT '0',
`pct_editor_approved` VARCHAR( 20 ) NOT NULL ,
`pct_client_approved` VARCHAR( 20 ) NOT NULL ,
PRIMARY KEY ( `performance_id` )
) ENGINE = innodb;

CREATE TABLE `seq_user_month_performance_performance_id` (
`id` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
INSERT INTO `seq_user_month_performance_performance_id` ( `id` )
VALUES (
'0'
);
ALTER TABLE `user_month_performance` ADD `user_name` VARCHAR( 255 ) NOT NULL AFTER `user_id` ,
ADD `first_name` VARCHAR( 255 ) NOT NULL AFTER `user_name` ,
ADD `last_name` VARCHAR( 255 ) NOT NULL AFTER `first_name` ,
ADD `email` VARCHAR( 255 ) NOT NULL AFTER `last_name` ;
#end

#added by nancy 2010-12-23 14:56
#campaign order changes
CREATE TABLE `client_article_prices` (
`aricle_price_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type_id` INT NOT NULL DEFAULT '0',
`max_word` INT NOT NULL DEFAULT '0',
`article_price` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0'
) ENGINE = MYISAM;
CREATE TABLE `order_campaign_keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `optional1` text,
  `optional2` text,
  `optional3` text,
  `optional4` text,
  `fields` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `order_campaign_payments` (
`payment_id` INT NOT NULL ,
`subtotal` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`discount` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`fees` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`total` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`status` INT NOT NULL DEFAULT '0',
`is_confirm` TINYINT NOT NULL DEFAULT '0',
`order_id` INT NOT NULL DEFAULT '0',
`qty` INT NOT NULL DEFAULT '0',
`price_id` INT NOT NULL DEFAULT '0',
`article_price` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0',
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT NOT NULL DEFAULT '0',
`creation_role` VARCHAR( 255 ) NOT NULL
) ENGINE = innodb;
ALTER TABLE `order_campaigns` ADD `is_confirm` TINYINT NOT NULL DEFAULT '0',
ADD `qty` INT NOT NULL DEFAULT '0',
ADD `max_word` INT NOT NULL DEFAULT '0',
ADD `min_word` INT NOT NULL DEFAULT '0';
ALTER TABLE `order_campaigns` ADD `source` INT NOT NULL DEFAULT '0';
ALTER TABLE `order_campaign_payments` ADD PRIMARY KEY ( `payment_id` ) ;
ALTER TABLE `order_campaign_payments` CHANGE `payment_id` `payment_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
#end

#added by nancy xu 2010-12-28 17:44
CREATE TABLE `order_keyword_xref_campaigns` (
`xref_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`campaign_id` INT NOT NULL DEFAULT '0',
`keyword_id` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
CREATE TABLE `comments_on_order_campaign` (
`comment_id` INT NOT NULL ,
`order_id` INT NOT NULL DEFAULT '0',
`creation_role` VARCHAR( 255 ) NOT NULL DEFAULT 'admin',
`creation_user_id` INT NOT NULL DEFAULT '0',
`creation_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`language` VARCHAR( 255 ) NOT NULL DEFAULT 'en',
`comment` TEXT NOT NULL ,
PRIMARY KEY ( `comment_id` )
) ENGINE = innodb;
CREATE TABLE `seq_comments_on_order_campaign_comment_id` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `seq_comments_on_order_campaign_comment_id` (`id`) VALUES 
(0);
#end

#added by nancy xu 2011-01-04 14:35
ALTER TABLE `order_campaign_payments` ADD `trans_num` VARCHAR( 255 ) NULL ,
ADD `trans_date` DATETIME NULL ;
ALTER TABLE `order_campaign_payments` ADD `account` VARCHAR( 255 ) NULL ;
ALTER TABLE `order_campaign_payments` CHANGE `trans_date` `trans_date` DATE NULL DEFAULT NULL ;
ALTER TABLE `client_article_prices` CHANGE `aricle_price_id` `price_id` INT( 11 ) DEFAULT NULL AUTO_INCREMENT ;
#end

#added by nancy xu 2011-01-05 17:50
INSERT INTO `label_field_description` (`desc_id`, `title`, `unique_key`, `description`, `created`) VALUES 
(7, 'Content Instructions', 'ORDER_CONTENT_INSTRUCTIONS', '<p><strong>&nbsp;</strong>We want to ensure that your content is delivered exactly how you want it so please make sure that you outline those requests here. Your instructions will serve as the primary guide for the writers and editors working on your content.&nbsp; Be as specific as possible.&nbsp;</p>\r\n<p>●&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Would you like your content&rsquo;s voice to read as professional and straightforward or casual and friendly?&nbsp;</p>\r\n<p>●&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Are there any specific details you would like included in your content? Provide details on your target audience, desired content tone and style requests.</p>', '2011-01-05 17:41:15'),
(8, 'Additional Style Guide', 'ORDER_ADDITIONAL_STYLE_GUIDE', '<p>We provide a style guide for each type of content but we give you the option of adding an additional style guide. This style guide creates and defines the quality, guidelines and informational standards for your content. It promotes style-consistency and serves as a reference source and training tool so that we can tailor your content to your needs.</p>', '2011-01-05 17:42:16'),
(9, 'Content Sample', 'ORDER_SAMPLE_CONTENT', '<p>Please provide a content sample here. This could be a piece from your existing site or something you&rsquo;ve seen elsewhere online that you really like. This will serve as an example of the style, tone and quality that you&rsquo;d like your content to emulate. This could be a link to the content or text article.<strong> <br /></strong></p>', '2011-01-05 17:44:30'),
(10, 'Special InstructionsSpecial Instructions', 'ORDER_SPECIAL_INSTRUCTIONS', '<p>If you have any special requests that aren&rsquo;t related to the content&rsquo;s substance, please specify them here. This could include any requests regarding delivery methods or time-frames.<strong></strong></p>', '2011-01-05 17:44:30');
ALTER TABLE `label_field_description` ADD `is_required` INT NOT NULL DEFAULT '-1';
UPDATE `label_field_description` SET `is_required` = '1' WHERE `label_field_description`.`desc_id` =7;
UPDATE `label_field_description` SET `is_required` = '0' where `desc_id` > 7;
#end

#upload keyword file  to add keyword for campaign
CREATE TABLE `keyword_files` (
`file_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`campaign_id` INT NOT NULL DEFAULT '0',
`filename` VARCHAR( 255 ) NOT NULL ,
`created` DATETIME NOT NULL ,
`fields` TEXT NULL ,
`data` TEXT NULL
) ENGINE = innodb;
ALTER TABLE `keyword_files` CHANGE `file_id` `file_id` INT( 11 ) NOT NULL ;
CREATE TABLE `seq_keyword_files_file_id` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
INSERT INTO `seq_keyword_files_file_id` (`id`) VALUES 
(0);
ALTER TABLE `keyword_files` ADD `is_parsed` INT NOT NULL DEFAULT '0';
ALTER TABLE `order_campaign_keywords` ADD `is_parsed` TINYINT NOT NULL DEFAULT '0';
#end


#added by nancy xu 2011-01-07 11:01
ALTER TABLE `campaign_keyword` CHANGE `optional1` `optional1` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional2` `optional2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional3` `optional3` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional4` `optional4` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end

#added by nancy xu 2011-01-11 14:02
ALTER TABLE `client_users` ADD `is_active` TINYINT NOT NULL DEFAULT '1';
#end


#added by nancy xu 2011-01-12 10:12
#payment
ALTER TABLE `order_campaign_payments` ADD `token` VARCHAR( 255 ) NOT NULL ,
ADD `payid` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `order_campaign_payments` CHANGE `payid` `payer_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `order_campaign_payments` ADD `detail_data` TEXT NOT NULL ;
ALTER TABLE `order_campaign_payments` CHANGE `token` `token` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `payer_id` `payer_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `detail_data` `detail_data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2011-01-20 
CREATE TABLE `zemanta_apis` (
  `api_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(255) NOT NULL,
  `unused_per_sec` int(11) NOT NULL DEFAULT '20',
  `unused_per_day` int(11) NOT NULL DEFAULT '15000',
  `last_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`api_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
INSERT INTO `zemanta_apis` (`api_id`, `api_key`, `unused_per_sec`, `unused_per_day`, `last_used`) VALUES 
(1, 'mdty8258ev2gchp3hwcb74sa', 20, 15000, 0);
ALTER TABLE `client` CHANGE `status` `status` ENUM( 'A', 'inactive', 'D' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A';
#end

#added by nancy xu 2011-01-20 18:13
ALTER TABLE `order_campaigns` ADD `target_audience` TEXT NULL ,
ADD `sale_type` TINYINT NULL ,
ADD `prefer_tone` TINYINT NULL ,
ADD `is_mentioned` TINYINT NULL ,
ADD `biz_name_formatbiz_name_format` TEXT NULL ,
ADD `highlight_desc` TEXT NULL ,
ADD `particular_desc` TEXT NULL ,
ADD `is_insert_img` TINYINT NULL ;
#end

#added by nancy xu 2011-01-21 14:35
ALTER TABLE `order_campaigns` CHANGE `biz_name_formatbiz_name_format` `biz_name_format` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `order_campaigns` DROP `prefer_tone` ;
ALTER TABLE `order_campaigns` CHANGE `article_tone` `article_tone` TINYINT( 4 ) NULL DEFAULT NULL ,
CHANGE `biz_name_format` `biz_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
#end

#added by nancy xu 2011-01-27 17:46
ALTER TABLE `client` ADD `referrer_type` TINYINT NOT NULL DEFAULT '0',
ADD `referrer_name` VARCHAR( 255 ) NOT NULL ,
ADD `referrer_tracking` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `client` CHANGE `referrer_name` `referrer_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `referrer_tracking` `referrer_tracking` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2011-01-31 20:45
ALTER TABLE `order_campaigns` ADD `shareasale` TEXT;
#end

#added by nancy xu 2011-02-05 15:55
ALTER TABLE `campaign_keyword` CHANGE `article_type` `article_type` INT;
ALTER TABLE `article_cost_history` ADD INDEX ( `month` ) ;
ALTER TABLE `article_cost_history` ADD INDEX ( `campaign_id` ) ;
ALTER TABLE `article_cost_history` ADD INDEX ( `article_type` ) ;
#end

#added  by nancy xu 2011-02-14 9:53
CREATE TABLE `sas_listeners` (
`listener_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT NOT NULL DEFAULT '0',
`tracking` VARCHAR( 255 ) NOT NULL ,
`trans_date` DATETIME NOT NULL ,
`commission` TEXT NOT NULL ,
`trans_id` VARCHAR( 255 ) NOT NULL
) ENGINE = innodb;
ALTER TABLE `sas_listeners` ADD `created` DATETIME NOT NULL ;
ALTER TABLE `sas_listeners` CHANGE `trans_date` `trans_date` VARCHAR( 255 ) DEFAULT NULL ;
ALTER TABLE `sas_listeners` ADD `amount` DECIMAL( 8, 3 ) DEFAULT '0.00' NOT NULL ;
#end

#added by nancy xu 2011-02-18 11:53
ALTER TABLE `users` ADD `pay_level` INT NOT NULL DEFAULT '0';
update  `users` set `pay_level` = 1 where `permission` =1;
#end

#added by nancy xu 2011-02-25 16:46
CREATE TABLE `article_searchresults` (
`search_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`article_id` INT NOT NULL DEFAULT '0',
`response` TEXT NOT NULL ,
`created` DATETIME NOT NULL
) ENGINE = innodb;
#end

ALTER TABLE `keyword_files` CHANGE `data` `data` BLOB NULL DEFAULT NULL ;
ALTER TABLE `keyword_files` CHANGE `data` `data` LONGTEXT NULL DEFAULT NULL ;
ALTER TABLE `keyword_files` CHANGE `data` `data` MEDIUMBLOB NULL DEFAULT NULL ;

#added by nancy xu 2011-03-14 16:45
ALTER TABLE `article_searchresults` ADD `body` TEXT NOT NULL ;
ALTER TABLE `article_searchresults` CHANGE `body` `body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2011-03-11 17:20
CREATE TABLE `items` (
`item_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`slug` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;
CREATE TABLE `domain_tags` (
`domain_tag_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tag_id` INT NOT NULL DEFAULT '0',
`ptag_id` INT NOT NULL DEFAULT '0',
`item_id` INT NOT NULL DEFAULT '0',
`pitem_id` INT NOT NULL DEFAULT '0',
`source` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
CREATE TABLE `article_tags` (
`article_id` INT NOT NULL DEFAULT '0',
`tag_id` INT NOT NULL DEFAULT '0',
`source` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
ALTER TABLE `article_tags` ADD PRIMARY KEY ( `article_id` , `tag_id` ) ;
#end

#added by nancy xu 2011-03-21 14:56
ALTER TABLE `client` ADD `agency_id` INT NOT NULL DEFAULT '0';
#end

#added  by nancy xu 2011-03-31 16:12
ALTER TABLE `order_campaigns` ADD `recurrent_time` INT NOT NULL DEFAULT '0' AFTER `monthly_recurrent` ;
#end

#added by nancy xu 2011-04-01 17:23
ALTER TABLE `articles_version_history` CHANGE `optional1` `optional1` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional2` `optional2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional3` `optional3` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional4` `optional4` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end

#added by nancy xu 2011-04-11 17:39
CREATE TABLE `feed_articles` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 2000 ) NOT NULL ,
`link` VARCHAR( 2000 ) NOT NULL ,
`description` TEXT NOT NULL ,
`created` DATETIME NOT NULL
) ENGINE = innodb;
#end

#added by nancy xu 2011-04-12 
CREATE TABLE `feed_urls` (
`url_id` INT NOT NULL  PRIMARY KEY ,
`feed_url` VARCHAR( 2000 ) NOT NULL ,
`campaign_id` INT NOT NULL DEFAULT '0',
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE = innodb;
ALTER TABLE `feed_articles` ADD `url_id` INT NOT NULL DEFAULT '0';
ALTER TABLE `feed_articles` ADD `article_id` INT NOT NULL DEFAULT '0';

CREATE TABLE `seq_feed_urls_url_id` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `seq_feed_urls_url_id` (`id`) VALUES (0);
ALTER TABLE `feed_urls` ADD `title` VARCHAR( 2000 ) NULL ,
ADD `description` TEXT NULL ,
ADD `link` VARCHAR( 2000 ) NULL ;
#end
#added by nancy xu 2011-04-13 15:19
ALTER TABLE `feed_urls` ADD `xml_str` LONGBLOB NOT NULL ;
ALTER TABLE `feed_urls` CHANGE `xml_str` `xml_str` MEDIUMTEXT NOT NULL;
ALTER TABLE `feed_articles` ADD `index` INT DEFAULT '0' NOT NULL ;
#end

#added by nancy xu 2011-04-20 13:50
ALTER TABLE `feed_urls` DROP `xml_str`;
#end

#added by nancy xu 2011-04-26 12:49
CREATE TABLE sph_counter(
counter_id INTEGER PRIMARY KEY NOT NULL ,
max_doc_id INTEGER NOT NULL
);
#end

#added by ancy xu 2011-04-28 14:59
ALTER TABLE `article_payment_log` ADD `approval_date` DATE NULL ;
#end

#added by nancy xu 2011-05-03 14:08
# month后面加上0,1，3，0表是一个月只支付一次，1表14号之前的文章，2表示15号之后的文章
ALTER TABLE  `cp_payment_history` CHANGE  `month`  `month` MEDIUMINT( 7 ) NOT NULL DEFAULT  '0',
CHANGE  `invoice_status`  `invoice_status` TINYINT( 1 ) NOT NULL DEFAULT  '0';
#end

#added by nancy xu 2011-05-04 10:27
ALTER TABLE `article_payment_log` CHANGE `month` `month` MEDIUMINT( 7 ) NOT NULL ,
CHANGE `pay_month` `pay_month` MEDIUMINT( 7 ) NOT NULL DEFAULT '0';
ALTER TABLE `cp_payment_history` CHANGE `month` `month` MEDIUMINT( 7 ) DEFAULT '0';
update article_payment_log set `month` = `month`*10+1 where `month` > 0;
update article_payment_log set pay_month=pay_month*10+1  where pay_month > 0;	
update cp_payment_history set month = month*10+1 where month > 0;
#end
#added by nancy xu 2011-05-05 10:40
CREATE TABLE `payment_settings` (
`setting_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`start_month` MEDIUMINT( 7 ) NOT NULL DEFAULT '0',
`end_month` MEDIUMINT( 7 ) NOT NULL DEFAULT '0',
`pay_per_month` TINYINT NOT NULL DEFAULT '0'
) ENGINE = innodb;
INSERT INTO `payment_settings` ( `setting_id` , `start_month` , `end_month` , `pay_per_month` )
VALUES (
NULL , '0', '2011041', '1'
), (
NULL , '2011051', '0', '2'
);
#end
#added by nancy xu 2011-05-05 16:58
ALTER TABLE `article_payment_log` ADD `approval_date` DATETIME;
#end

#added by nancy xu 2011-05-11 17:51
ALTER TABLE `articles_version_history` ADD `project_manager_id` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2011-05-13 12:25
CREATE TABLE `track_emails` (
`email_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`subject` VARCHAR( 255 ) NOT NULL ,
`content` TEXT NOT NULL ,
`sender` VARCHAR( 255 ) NOT NULL ,
`error` TEXT NOT NULL ,
`sent` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT NOT NULL DEFAULT '0',
`permission` INT NOT NULL DEFAULT '0'
) ENGINE = innodb;
ALTER TABLE `track_emails` ADD `receiver` TEXT NOT NULL ,
ADD `cc` TEXT NOT NULL ,
ADD `bcc` VARCHAR( 2000 ) NOT NULL ;
#end

#added by nancy xu 2011-05-17 15:52
ALTER TABLE `article_cost_history` CHANGE `month` `month` MEDIUMINT( 7 ) DEFAULT '0';
update article_cost_history set `month`=`month`*10+1 WHERE `month` <= 201104;
#end


#added by nancy xu 2011-05-18 14:26
ALTER TABLE `users_categories` ADD `sample` TEXT NULL ;
#end

#added by nancy xu 2011-05-18 15:31
ALTER TABLE `users` ADD `total_rejected` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2011-05-19 15:56
ALTER TABLE  `bugs` ADD  `campaign_id` INT NOT NULL ,
ADD  `campaign_name` VARCHAR( 255 ) NOT NULL ,
ADD  `article_number` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE  `bugs` CHANGE  `campaign_id`  `campaign_id` INT( 11 ) NOT NULL DEFAULT  '0',
CHANGE  `campaign_name`  `campaign_name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `article_number`  `article_number` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE  `bugs` CHANGE  `campaign_name`  `campaign_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE  `article_number`  `article_number` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
#end

#added by nancy xu 2011-05-25 15:39
ALTER TABLE `article_type` ADD `pay_by_article` TINYINT NOT NULL DEFAULT '0' AFTER `cp_cost` ,
ADD `cp_article_cost` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0' AFTER `pay_by_article` ,
ADD `editor_article_cost` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0' AFTER `cp_article_cost` ;
ALTER TABLE `article_cost` ADD `pay_by_article` TINYINT NOT NULL DEFAULT '0' AFTER `cost_id` ,
ADD `cp_article_cost` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0' AFTER `pay_by_article` ,
ADD `editor_article_cost` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0' AFTER `cp_article_cost` ;
#end

#added by nancy xu 2011-05-26 15:23
ALTER TABLE `article_payment_log` ADD `aricle_cost` DECIMAL( 8, 3 ) NOT NULL DEFAULT '0';
ALTER TABLE `article_cost_history` ADD `pay_by_article` TINYINT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2011-06-10 11:42
ALTER TABLE `users` ADD `qb_vendor_id` INT NOT NULL DEFAULT '0',
ADD `qb_sequence` VARCHAR( 255 ) NULL ;
#end
#added by nancy xu 2011-06-11 0:14
ALTER TABLE `payment_bills` ADD `pay_plugin` VARCHAR( 255 ) NOT NULL DEFAULT 'NetSuit';
#end

#added by nancy xu 2011-06-14 12:01
ALTER TABLE `articles` ADD `rejected` DATETIME NULL AFTER `rejected_memo` ;
ALTER TABLE `articles_version_history` ADD `rejected` DATETIME NULL AFTER `project_manager_id` ;
#end

#added by nancy xu 2011-06-14 18:08
ALTER TABLE `article_cost_history` ADD `qd_listid` INT NOT NULL DEFAULT '0';
ALTER TABLE `article_type` ADD `qd_listid` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2011-06-23 12:43
ALTER TABLE `candidates` ADD `plinks` TEXT NULL ;
#end

#added by nancy xu 2011-06-23 16:37
ALTER TABLE `candidates` CHANGE `education` `education` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `candidates` CHANGE `field_of_study` `field_of_study` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `writing_background` `writing_background` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `experience` `experience` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `published_work` `published_work` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `writing_sample` `writing_sample` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `date_applied` `date_applied` DATETIME NULL ,
CHANGE `resume_file` `resume_file` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `candidates` CHANGE `resume_file` `resume_file` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#addd by nancy xu 2011-06-24 17:44
ALTER TABLE `preference` ADD `pref_ordering` INT NOT NULL DEFAULT '0';
ALTER TABLE `candidates` CHANGE `education` `education` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end

#added by nancy xu 2011-06-28 10:21
ALTER TABLE `category` ADD `is_hidden` TINYINT NOT NULL DEFAULT '0';
UPDATE `category` SET `parent_id` = '9' WHERE `category`.`category_id` =20;
UPDATE `category` SET `is_hidden` = '1' WHERE `category`.`category_id` in (8, 14,20) ;
update category set parent_id = 38 where category = 'Environmental';
update category set parent_id = 33 where category = 'Technical Writing';
update category set parent_id = 12 where category = 'Shopping';
update category set parent_id = 1 where category in ('Audio/TV/Film','Graphic Design','Literature');
update category set parent_id = 4 where category in ('Careers','Finance','Real Estate','Security & Protection');
update category set parent_id = 9 where category in ('Charities & Society','Children','Education','Fashion/Lifestyle','Food and Beverages','History/Culture','Personal Development','Politics & Government','Pop Culture','Religion/Spirituality');
update category set parent_id = 13 where category in ('Medical/Medicine','Addiction/Mental Health');
UPDATE category SET category='Careers and Job Advancement' WHERE category='Careers';
UPDATE category SET category='Financial Services' WHERE category='Finance';
UPDATE category SET category='Security' WHERE category='Security & Protection';
UPDATE category SET category='Computers ' WHERE category='Computers and Internet';
UPDATE category SET category='Lifestyle & Relationships' WHERE category='Family and Relationships';
UPDATE category SET category='Charities' WHERE category='Charities & Society';
UPDATE category SET category='Fashion' WHERE category='Fashion/Lifestyle';
UPDATE category SET category='Food' WHERE category='Food and Beverages';
UPDATE category SET category='History' WHERE category='History/Culture';
UPDATE category SET category='Politics' WHERE category='Politics & Government';
UPDATE category SET category='Religion and Spirituality' WHERE category='Religion/Spirituality';
UPDATE category SET category='Conditions, Diseases, and Treatment' WHERE category='Medical/Medicine';
UPDATE category SET category='Mental Health' WHERE category='Addiction/Mental Health';
UPDATE category SET category='Hobbies & Games' WHERE category='Gaming';
UPDATE category SET category='Science and Industry' WHERE category='Science/Astronomy';
UPDATE category SET category='Travel' WHERE category='Travel and Tourism';
INSERT INTO `category` (`category_id`, `parent_id`, `category`, `is_hidden`) VALUES
(41, 2, 'Birds', 0),
(42, 2, 'Cats', 0),
(43, 2, 'Dogs', 0),
(44, 2, 'Exotic Animals', 0),
(45, 2, 'Farm animals', 0),
(46, 2, 'Fish', 0),
(47, 2, 'Horses', 0),
(48, 2, 'Insects', 0),
(49, 2, 'Mammals', 0),
(50, 2, 'Non-traditional Pets', 0),
(51, 2, 'Pet Health', 0),
(52, 2, 'Reptiles and Amphibians', 0),
(53, 2, 'Zoo Animals', 0),
(54, 1, 'Animation, Design, and Illustration', 0),
(55, 1, 'Audio and Music', 0),
(56, 1, 'Beauty Pageants', 0),
(57, 1, 'Body Art', 0),
(58, 1, 'Books', 0),
(59, 1, 'Celebrities', 0),
(60, 1, 'Commercials and Advertising', 0),
(62, 1, 'Fine Arts', 0),
(63, 1, 'Magic', 0),
(64, 1, 'People', 0),
(65, 1, 'Performing Arts', 0),
(66, 1, 'Photography', 0),
(67, 1, 'Visual Arts', 0),
(68, 3, 'Aircraft', 0),
(69, 3, 'Auto Racing', 0),
(70, 3, 'Cars', 0),
(71, 3, 'Clubs', 0),
(72, 3, 'Custom Auto', 0),
(73, 3, 'Driving and Safety', 0),
(74, 3, 'Events and Shows', 0),
(75, 3, 'Green Vehicles', 0),
(76, 3, 'Large Vehicles', 0),
(77, 3, 'Mobile Homes', 0),
(78, 3, 'Motorcycles', 0),
(79, 3, 'Off Road Vehicles', 0),
(80, 3, 'Parts and Accessories', 0),
(81, 3, 'Repair', 0),
(82, 3, 'Tractors', 0),
(83, 3, 'Watercraft', 0),
(84, 4, 'Business Services', 0),
(85, 4, 'Consumer Information', 0),
(86, 4, 'Green Office', 0),
(87, 4, 'Human Resources', 0),
(88, 4, 'Investment', 0),
(89, 4, 'Law', 0),
(90, 4, 'Major Companies', 0),
(91, 4, 'Management', 0),
(92, 4, 'Marketing and Promotion', 0),
(93, 4, 'Office Supplies and Equipment', 0),
(94, 4, 'Personal Finance', 0),
(95, 4, 'Small Businesses', 0),
(96, 4, 'Startups', 0),
(97, 4, 'Logistics', 0),
(98, 4, 'Insurance', 0),
(99, 4, 'Sales', 0),
(100, 4, 'Work Life', 0),
(101, 33, 'Building Computers', 0),
(102, 33, 'Computer Science', 0),
(103, 33, 'Digital Imaging', 0),
(104, 33, 'Hardware', 0),
(105, 33, 'Internet', 0),
(106, 33, 'Operating Systems', 0),
(107, 33, 'Printers and Scanners', 0),
(108, 33, 'Programming', 0),
(109, 33, 'Purchasing Computers', 0),
(110, 33, 'Software', 0),
(111, 33, 'Troubleshooting and Repair', 0),
(112, 33, 'Virus and Spyware Protection', 0),
(113, 9, 'Analysis and Opinion', 0),
(114, 9, 'Astrology', 0),
(115, 9, 'Dating', 0),
(116, 9, 'Ethics', 0),
(117, 9, 'Family', 0),
(118, 9, 'Holidays and Celebrations', 0),
(119, 9, 'LGBT', 0),
(120, 9, 'Marriage', 0),
(121, 9, 'Organizations', 0),
(122, 9, 'Parenting', 0),
(123, 9, 'People and Societies', 0),
(124, 9, 'Reference', 0),
(125, 9, 'Relationships', 0),
(126, 9, 'Rural', 0),
(127, 9, 'Seniors', 0),
(128, 9, 'Student Life', 0),
(129, 9, 'Teens/20''s', 0),
(130, 9, 'Urban', 0),
(131, 9, 'Wedding', 0),
(132, 7, 'Cameras', 0),
(133, 7, 'Car Audio', 0),
(134, 7, 'Consumer Electronics', 0),
(135, 7, 'Phones and PDAs', 0),
(136, 13, 'Addiction', 0),
(137, 13, 'Alternative Medicine', 0),
(138, 13, 'Dental Health', 0),
(139, 13, 'Dieting and Nutrition', 0),
(140, 13, 'Drugs and Medicine', 0),
(141, 13, 'Exercise Equipment', 0),
(142, 13, 'Geriatrics', 0),
(143, 13, 'Personal Care and Beauty', 0),
(144, 13, 'Personal Fitness', 0),
(145, 13, 'Pregnancy', 0),
(146, 13, 'Reproductive Health', 0),
(147, 13, 'Self Help', 0),
(148, 13, 'Sexuality', 0),
(149, 13, 'Weightlifting', 0),
(150, 13, 'Weight Loss', 0),
(151, 13, 'Western Medicine', 0),
(152, 13, 'Women''s Health', 0),
(153, 12, 'Antiques and Collectibles', 0),
(154, 12, 'Backyard and Outdoor Games', 0),
(155, 12, 'Billiards', 0),
(156, 12, 'Board Games', 0),
(157, 12, 'Card Games', 0),
(158, 12, 'Crafts', 0),
(159, 12, 'Models', 0),
(160, 12, 'Party Games', 0),
(161, 12, 'Puzzles', 0),
(162, 12, 'RC Vehicles', 0),
(163, 12, 'Toys', 0),
(164, 12, 'Video Games', 0),
(165, 12, 'Woodworking', 0),
(166, 15, 'Appliances', 0),
(167, 15, 'Bathroom', 0),
(168, 15, 'Bedroom', 0),
(169, 15, 'Cleaning', 0),
(170, 15, 'Closets', 0),
(171, 15, 'Emergency Preparation', 0),
(172, 15, 'Flowers', 0),
(173, 15, 'Gardening', 0),
(174, 15, 'Green Living', 0),
(175, 15, 'Home Improvement', 0),
(176, 15, 'Interior Design', 0),
(177, 15, 'Kitchen', 0),
(178, 15, 'Living Room', 0),
(179, 15, 'New Homes', 0),
(180, 15, 'Outdoors', 0),
(181, 15, 'Tools', 0),
(182, 24, 'Civil Suit', 0),
(183, 24, 'Contract Law', 0),
(184, 24, 'Criminal Law', 0),
(185, 24, 'Divorce and Family', 0),
(186, 24, 'Immigration Law', 0),
(187, 24, 'Other Law', 0),
(188, 24, 'Tax Law', 0),
(189, 38, 'Building and Construction', 0),
(190, 38, 'Chemistry and Physics', 0),
(191, 38, 'Electrical', 0),
(192, 38, 'Engineering and Mechanics', 0),
(193, 38, 'Environment', 0),
(194, 38, 'Experiments', 0),
(195, 38, 'Facts', 0),
(196, 38, 'Heavy Industry', 0),
(197, 38, 'Math', 0),
(198, 38, 'Natural Science and Weather', 0),
(199, 38, 'Other Science', 0),
(200, 17, 'Coaching', 0),
(201, 17, 'Individual Sports', 0),
(202, 17, 'Martial Arts', 0),
(203, 17, 'Motor Sports', 0),
(204, 17, 'Multi-Sports', 0),
(205, 17, 'Outdoors and Camping', 0),
(206, 17, 'Spectator Sports', 0),
(207, 17, 'Team Sports', 0),
(208, 17, 'Water Sports', 0),
(209, 17, 'Winter Sports', 0),
(210, 18, 'Beach and Resort', 0),
(211, 18, 'Budget', 0),
(212, 18, 'Cruises', 0),
(213, 18, 'Family', 0),
(214, 18, 'Hotels and Lodging', 0),
(215, 18, 'Outdoors and Adventure', 0),
(216, 18, 'Planning Your Trip', 0),
(217, 18, 'Romance', 0),
(218, 18, 'Transportation', 0);

ALTER TABLE `candidates` CHANGE `experience` `experience` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end

#added by nancy xu 2011-06-29 10:54
ALTER TABLE `candidates` CHANGE `writing_background` `writing_background` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2011-06-29 17:21
UPDATE `preference` SET `pref_ordering` = '1' WHERE `preference`.`pref_id` =1686 LIMIT 1 ;
UPDATE `preference` SET `pref_ordering` = '3' WHERE `preference`.`pref_id` =1687 LIMIT 1 ;
UPDATE `preference` SET `pref_ordering` = '4' WHERE `preference`.`pref_id` =1688 LIMIT 1 ;
UPDATE `preference` SET `pref_ordering` = '5' WHERE `preference`.`pref_id` =1689 LIMIT 1 ;
UPDATE `preference` SET `pref_ordering` = '6' WHERE `preference`.`pref_id` =1690 LIMIT 1 ;
#end

#added by nancy xu 2011-07-01 11:27
ALTER TABLE `candidates` ADD `revised_article` TEXT NULL ,
ADD `provide_feedback` TEXT NULL ;
ALTER TABLE `candidates` CHANGE `provide_feedback` `feedback` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end

#added by nancy xu 2011-7-6 17:41
ALTER TABLE `users_categories` ADD `parent_id` INT NOT NULL DEFAULT '0';
update users_categories set category_id=11 where category_id=20;
update users_categories set category_id=1 where category_id=8;
update users_categories set category_id=12 where category_id=14;
#end

#added by nancy xu 2011-7-13 18:28
update order_campaigns set category_id=11 where category_id=20;
update order_campaigns set category_id=1 where category_id=8;
update order_campaigns set category_id=12 where category_id=14;
update client_campaigns set category_id=11 where category_id=20;
update client_campaigns set category_id=1 where category_id=8;
update client_campaigns set category_id=12 where category_id=14;
#end

#added by nancy xu 2011-7-18 12:37
CREATE TABLE IF NOT EXISTS `article_rankings` (
  `ranking_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `punctuation` tinyint(4) NOT NULL DEFAULT '0',
  `grammar` tinyint(4) NOT NULL DEFAULT '0',
  `structure` tinyint(4) NOT NULL DEFAULT '0',
  `ap_style` tinyint(4) NOT NULL DEFAULT '0',
  `style_guide` tinyint(4) NOT NULL DEFAULT '0',
  `quality` tinyint(4) NOT NULL DEFAULT '0',
  `communication` tinyint(4) NOT NULL DEFAULT '0',
  `ranking` decimal(6,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ranking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
ALTER TABLE `article_rankings` ADD `cooperativeness` TINYINT NOT NULL DEFAULT '0' AFTER `ranking` ,
ADD `timeliness` TINYINT NOT NULL DEFAULT '0' AFTER `cooperativeness` ;
ALTER TABLE `article_rankings` ADD `user_id` INT NOT NULL DEFAULT '0' AFTER `campaign_id` ;
ALTER TABLE `article_rankings` CHANGE `article_id` `keyword_id` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `preference` CHANGE `pref_table` `pref_table` ENUM( 'users', 'client', 'client_campaign', 'campaign_keyword', 'articles', 'cp_candidates', 'cp_campaign_ranking', 'manual_content', 'candidates', 'article_rankings' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'users';
#end

#added by nancy xu 2011-7-20 11:49
ALTER TABLE `article_rankings` CHANGE `created` `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
CREATE TABLE IF NOT EXISTS `user_month_rankings` (
  `ranking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `report_month` int(11) NOT NULL DEFAULT '0',
  `punctuation` decimal(8,2) NOT NULL DEFAULT '0.00',
  `grammar` decimal(8,2) NOT NULL DEFAULT '0.00',
  `structure` decimal(8,2) NOT NULL DEFAULT '0.00',
  `ap_style` decimal(8,2) NOT NULL DEFAULT '0.00',
  `style_guide` decimal(8,2) NOT NULL DEFAULT '0.00',
  `quality` decimal(8,2) NOT NULL DEFAULT '0.00',
  `communication` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cooperativeness` decimal(8,2) NOT NULL DEFAULT '0.00',
  `timeliness` decimal(8,2) NOT NULL DEFAULT '0.00',
  `ranking` decimal(8,2) NOT NULL DEFAULT '0.00',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ranking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `seq_user_month_rankings_ranking_id` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `seq_user_month_rankings_ranking_id` (`id`) VALUES (0);
ALTER TABLE `user_month_rankings` CHANGE `role` `permission` INT( 255 ) NOT NULL ;
ALTER TABLE `user_month_rankings` ADD `total` INT NOT NULL DEFAULT '0' AFTER `report_month` ;
#end

#added by nancy xu 2011-8-17 11:18
ALTER TABLE `users` ADD `photo` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
ADD `bio` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `users` CHANGE `photo` `photo` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `bio` `bio` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2011-8-18 16:05
ALTER TABLE `users` ADD `big_photo` VARCHAR( 1000 ) NULL AFTER `photo` ;
UPDATE  `phpbb_config` SET `config_value` = '1' WHERE  `config_name` = 'allow_avatar_remote';
#end

#added by nancy xu 2011-8-23 15:34
ALTER TABLE `articles_version_history` ADD `created_by` INT DEFAULT '0' NOT NULL ;
ALTER TABLE `articles_version_history` ADD `role` VARCHAR( 255 ) NULL ;
ALTER TABLE `articles_version_history` ADD `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `articles_version_history` CHANGE `role` `created_role` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end 

#added by nancy xu 2011-8-29 10:59
ALTER TABLE `articles_version_history` ADD `posted_by` TEXT NULL ;
ALTER TABLE `articles` ADD `posted_by` TEXT NULL ;
#end 


#added by nancy xu 2011-9-28 15:53
ALTER TABLE `candidates` ADD `samples` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2011-10-24 11:26
ALTER TABLE `article_payment_log` CHANGE `article_type` `article_type` INT( 1 ) NOT NULL ;
#end

#added by nancy xu 2011-11-28 11:18
ALTER TABLE `article_type` ADD `is_inactive` INT NOT NULL DEFAULT '0';
update article_type set is_inactive=1 where type_id in (27,28,29,31,32,37,41,46,47,49,57,58,59,60,61,65,66,67,69,70,71,72,86,89,93,95,96,97);
#end

#added by nancy xu 2012-03-05
# writer_level: What best describes you as a writer? 
ALTER TABLE `candidates` ADD `writer_level` VARCHAR( 255 ) NULL DEFAULT NULL ;
ALTER TABLE `candidates` ADD `comments` TEXT NULL DEFAULT NULL ;
#end

#added by nancy xu 2012-03-20
#用来控制copy writer写字总数，如果copywriter的文章的字数不能超过campaign里面规定的，如果copywriter写的字数超过50个，那么就要提醒copywriter(editor/pm/admin在编辑的时候，也需要提醒).如果copywriter的字数超过这里定义，那么支付的时候，只能按照这里定义的字数来计算
ALTER TABLE `client_campaigns` ADD `max_word` INT NOT NULL DEFAULT '0';
ALTER TABLE `articles` ADD `real_words` INT NOT NULL DEFAULT '0' AFTER `total_words` ;
update  `articles` set `real_words` = `total_words`  where real_words=0;
ALTER TABLE `articles_version_history` ADD `real_words` INT NOT NULL DEFAULT '0' AFTER `total_words` ;
update  `articles_version_history` set `real_words` = `total_words`  where real_words=0;
#end

#added by nancy xu 2012-04-10 12:09
#如果editor/cp同意状态就变成1，不同意状态为0，初始状态为-1
#pm对editor/cp操作accept/decline的描述
ALTER TABLE `campaign_keyword` ADD `editor_status` TINYINT NOT NULL DEFAULT '-1',
ADD `cp_status` TINYINT NOT NULL DEFAULT '-1';
ALTER TABLE `article_action` ADD `editor_status` TINYINT NOT NULL DEFAULT '-1',
ADD `cp_status` TINYINT NOT NULL DEFAULT '-1';
update `article_action` set `editor_status` =1, `cp_status`=1;
ALTER TABLE `client_campaigns` ADD `acceptance_desc` TEXT NOT NULL ;
ALTER TABLE `articles_version_history` ADD `editor_status` TINYINT NOT NULL DEFAULT '1',
ADD `cp_status` TINYINT NOT NULL DEFAULT '1';
#end

#added by nancy xu 2012-04-18 17:40
#sk = Secondary Keyword 1
ALTER TABLE `campaign_keyword` ADD `primary_keyword` VARCHAR( 255 ) NULL ,
ADD `sk1` VARCHAR( 255 ) NULL ,
ADD `sk2` VARCHAR( 255 ) NULL ;
ALTER TABLE `campaign_keyword` CHANGE `primary_keyword` `custom_field1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `sk1` `custom_field2` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `sk2` `custom_field3` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
CREATE TABLE IF NOT EXISTS `custom_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `clabel` varchar(255) NOT NULL,
  `cfield` varchar(255) NOT NULL,
  `ctable` varchar(255) NOT NULL DEFAULT 'campaign_keyword',
  `description` varchar(255) DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `is_required` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `campaign_keyword` ADD `optional5` VARCHAR( 255 ) NULL AFTER `optional4` ,
ADD `optional6` VARCHAR( 255 ) NULL AFTER `optional5` ,
ADD `optional7` VARCHAR( 255 ) NULL AFTER `optional6` ;
ALTER TABLE `custom_fields` ADD `edit_role` VARCHAR( 255 ) NOT NULL DEFAULT 'copy writer' AFTER `is_required` ;
ALTER TABLE `custom_fields` CHANGE `edit_role` `edit_role` INT( 11 ) NOT NULL DEFAULT '1';
ALTER TABLE `articles_version_history` ADD `optional5` VARCHAR( 255 ) NULL ,
ADD `optional6` VARCHAR( 255 ) NULL ,
ADD `optional7` VARCHAR( 255 ) NULL ,
ADD `custom_field1` VARCHAR( 255 ) NULL ,
ADD `custom_field2` VARCHAR( 255 ) NULL ,
ADD `custom_field3` VARCHAR( 255 ) NULL ;
ALTER TABLE `custom_fields` ADD `status` TINYINT DEFAULT '1' NOT NULL ;
#end

#added by nancy xu 2012-05-04 
#assignment acceptance function 
ALTER TABLE `campaign_keyword` ADD `cp_accept_time` DATETIME NULL AFTER `cp_status` ;
ALTER TABLE `articles_version_history` ADD `cp_accept_time` DATETIME NULL AFTER `cp_status` ;
UPDATE `campaign_keyword` set cp_status = 1, editor_status = 1, `cp_accept_time` = NOW() WHERE editor_id > 0 and copy_writer_id > 0;
#end

#added by nancy xu 2012-06-05 16:57
#1 paid by max word
#2 paid by max word if  real word more than  max word?
#3 paid by real word
ALTER TABLE `client_campaigns` ADD `pay_type` INT NOT NULL DEFAULT '1' COMMENT '1: paid by max word; 2: paid by max word if real word more than max word?; 3 paid by real word; ' AFTER `max_word` 
#end

#added by nancy xu 2012-07-18 11:15
ALTER TABLE `campaign_keyword` CHANGE `optional5` `optional5` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional6` `optional6` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `optional7` `optional7` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
#end

#added by nancy xu 2012-07-26 12:29
#When keywords/articles were denied by editor/writer, alert to PM
ALTER TABLE `notifications` ADD `keyword_id` INT NOT NULL DEFAULT '0' AFTER `campaign_id` ;
ALTER TABLE `notifications` ADD `from_user` INT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2012-08-01 18:21
ALTER TABLE `campaign_keyword` ADD `optional8` VARCHAR( 2000 ) NOT NULL AFTER `optional7` ,
ADD `optional9` VARCHAR( 2000 ) NOT NULL AFTER `optional8` ,
ADD `optional10` VARCHAR( 2000 ) NOT NULL AFTER `optional9` ;
ALTER TABLE `campaign_keyword` ADD `subcid` INT NOT NULL DEFAULT '0';
ALTER TABLE `articles_version_history` ADD `optional8` VARCHAR( 2000 ) NOT NULL AFTER `optional7` ,
ADD `optional9` VARCHAR( 2000 ) NOT NULL AFTER `optional8` ,
ADD `optional10` VARCHAR( 2000 ) NOT NULL AFTER `optional9` ,
ADD `subcid` INT NOT NULL DEFAULT '0' AFTER `optional10` ;
#end

#added by nancy xu 2012-08-03 15:44
ALTER TABLE `campaign_keyword` CHANGE `subcid` `subcid` VARCHAR( 255 ) NOT NULL DEFAULT '0';
ALTER TABLE `campaign_logs` ADD `optional5` VARCHAR( 2000 ) NULL AFTER `optional4` ,
ADD `optional6` VARCHAR( 2000 ) NULL AFTER `optional5` ,
ADD `optional7` VARCHAR( 2000 ) NULL AFTER `optional6` ,
ADD `optional8` VARCHAR( 2000 ) NULL AFTER `optional7` ,
ADD `optional9` VARCHAR( 2000 ) NULL AFTER `optional8` ,
ADD `optional10` VARCHAR( 2000 ) NULL AFTER `optional9` ;
#end

#added by nancy xu 2012-09-27 9:30
ALTER TABLE `candidates` ADD `first_language` VARCHAR( 255 ) NOT NULL   AFTER `work_in_us` ,
ADD `weekly_hours` VARCHAR( 255 ) NOT NULL AFTER `first_language`;
ALTER TABLE `users` ADD `first_language` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `users` CHANGE `first_language` `first_language` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
ALTER TABLE `candidates` CHANGE `first_language` `first_language` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
#end

#added by nancy xu 2012-11-01 16:04
ALTER TABLE `data_logs` ADD `datatype` VARCHAR( 50 ) NOT NULL DEFAULT 'xml' AFTER `log_id` ;
#end

#added by nancy xu 2012-11-13 10:31
ALTER TABLE `articles` CHANGE `creation_role` `creation_role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer', 'client' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer';
ALTER TABLE `articles_version_history` CHANGE `creation_role` `creation_role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer', 'client' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer';
ALTER TABLE `article_cost_history` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer';
ALTER TABLE `article_payment_log` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer';
ALTER TABLE `campaign_keyword` CHANGE `creation_role` `creation_role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer', 'client' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'project manager';
ALTER TABLE `client` CHANGE `creation_role` `creation_role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'admin';
ALTER TABLE `comments_on_articles` CHANGE `creation_role` `creation_role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer', 'client' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'admin';
ALTER TABLE `cp_payment_history` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer';
ALTER TABLE `users` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer',
CHANGE `permission` `permission` DECIMAL( 5, 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `users_categories` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'copy writer';
ALTER TABLE `user_payment_history` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'admin';
ALTER TABLE `user_performance` CHANGE `role` `role` ENUM( 'admin', 'project manager', 'editor', 'agency', 'designer', 'copy writer' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
#end

#added by nancy xu 2012-11-19 21:53
#image tables
CREATE TABLE IF NOT EXISTS `image_keyword` (
  `keyword_id` int(11) NOT NULL DEFAULT '0',
  `date_assigned` datetime NOT NULL,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `copy_writer_id` int(11) NOT NULL DEFAULT '0',
  `editor_id` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(255) DEFAULT NULL,
  `image_type` varchar(120) NOT NULL DEFAULT '',
  `keyword_description` text NOT NULL,
  `date_start` date DEFAULT '0000-00-00',
  `date_end` date DEFAULT '0000-00-00',
  `creation_user_id` int(11) NOT NULL DEFAULT '0',
  `creation_role` enum('admin','project manager','editor','agency','designer','copy writer','client') NOT NULL DEFAULT 'project manager',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keyword_meta` varchar(255) DEFAULT NULL,
  `description_meta` text,
  `status` enum('A','D') NOT NULL DEFAULT 'A',
  `keyword_status` tinyint(4) NOT NULL DEFAULT '-1',
  `is_sent` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'keyword has been sent email to tara and tony',
  `mapping_id` varchar(255) DEFAULT NULL,
  `optional1` text,
  `optional2` text,
  `optional3` text,
  `optional4` text,
  `optional5` varchar(500) DEFAULT NULL,
  `optional6` varchar(500) DEFAULT NULL,
  `optional7` varchar(500) DEFAULT NULL,
  `optional8` varchar(2000) NOT NULL,
  `optional9` varchar(2000) NOT NULL,
  `optional10` varchar(2000) NOT NULL,
  `translation` text,
  `cancel_memo` text,
  `editor_status` tinyint(4) NOT NULL DEFAULT '-1',
  `cp_status` tinyint(4) NOT NULL DEFAULT '-1',
  `cp_accept_time` datetime DEFAULT NULL,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `subcid` varchar(255) NOT NULL DEFAULT '0',
  `creator` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `cpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  `modified` int(11) NOT NULL DEFAULT '0',
  `mender` int(11) NOT NULL DEFAULT '0',
  `mpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  PRIMARY KEY (`keyword_id`),
  KEY `camp_idx` (`campaign_id`),
  KEY `cp_idx` (`copy_writer_id`),
  KEY `editor_id` (`editor_id`),
  KEY `keyword` (`keyword`),
  KEY `image_type` (`image_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `image_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '-1',
  `total_nodes` int(11) NOT NULL DEFAULT '0',
  `is_hidden` tinyint(4) NOT NULL DEFAULT '0',
  `qd_listid` int(11) NOT NULL DEFAULT '0',
  `is_inactive` int(11) NOT NULL DEFAULT '0',
  `creator` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `cpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  `modified` int(11) NOT NULL DEFAULT '0',
  `mender` int(11) NOT NULL DEFAULT '0',
  `mpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) NOT NULL DEFAULT '0',
  `image_number` varchar(160) NOT NULL,
  `keyword_id` int(11) NOT NULL DEFAULT '0',
  `creation_user_id` int(11) NOT NULL DEFAULT '0',
  `creation_role` enum('admin','project manager','editor','agency','designer','copy writer','client') NOT NULL DEFAULT 'copy writer',
  `creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) DEFAULT NULL,
  `html_title` varchar(255) DEFAULT NULL,
  `image_name` text,
  `image_param` text,
  `image_status` varchar(4) DEFAULT NULL,
  `current_version_number` decimal(2,1) DEFAULT '0.0',
  `approval_date` datetime DEFAULT NULL,
  `client_approval_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cp_updated` datetime DEFAULT NULL,
  `rejected_memo` text,
  `rejected` datetime DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `posted_by` text,
  `creator` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `cpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  `modified` int(11) NOT NULL DEFAULT '0',
  `mender` int(11) NOT NULL DEFAULT '0',
  `mpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  PRIMARY KEY (`image_id`),
  KEY `image_id` (`image_id`),
  KEY `keyword_id` (`keyword_id`),
  KEY `approval_date` (`approval_date`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `image_version_history` (
  `version_history_id` int(11) NOT NULL DEFAULT '0',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `image_number` varchar(255) NOT NULL,
  `keyword_id` int(11) NOT NULL DEFAULT '0',
  `creation_role` enum('admin','project manager','editor','agency','designer','copy writer','client') NOT NULL DEFAULT 'copy writer',
  `creation_user_id` int(11) NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` varchar(10) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `image_name` text,
  `image_param` text,
  `image_status` varchar(4) DEFAULT NULL,
  `version_number` decimal(2,1) NOT NULL DEFAULT '0.0',
  `approval_date` datetime DEFAULT NULL,
  `client_approval_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `html_title` varchar(255) DEFAULT NULL,
  `cp_updated` datetime DEFAULT NULL,
  `date_assigned` datetime DEFAULT NULL,
  `copy_writer_id` int(11) NOT NULL DEFAULT '0',
  `editor_id` int(11) NOT NULL DEFAULT '0',
  `image_type` int(11) DEFAULT NULL,
  `keyword_description` text,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `updated` datetime NOT NULL,
  `keyword_meta` varchar(255) DEFAULT NULL,
  `description_meta` text,
  `mapping_id` varchar(255) DEFAULT NULL,
  `optional1` text,
  `optional2` text,
  `optional3` text,
  `optional4` text,
  `project_manager_id` int(11) NOT NULL DEFAULT '0',
  `rejected` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_role` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `role` varchar(255) DEFAULT NULL,
  `posted_by` text,
  `editor_status` tinyint(4) NOT NULL DEFAULT '1',
  `cp_status` tinyint(4) NOT NULL DEFAULT '1',
  `cp_accept_time` datetime DEFAULT NULL,
  `optional5` varchar(255) DEFAULT NULL,
  `optional6` varchar(255) DEFAULT NULL,
  `optional7` varchar(255) DEFAULT NULL,
  `optional8` varchar(2000) NOT NULL,
  `optional9` varchar(2000) NOT NULL,
  `optional10` varchar(2000) NOT NULL,
  `subcid` int(11) NOT NULL DEFAULT '0',
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `creator` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `cpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  `modified` int(11) NOT NULL DEFAULT '0',
  `mender` int(11) NOT NULL DEFAULT '0',
  `mpermission` varchar(120) NOT NULL DEFAULT 'project manager',
  PRIMARY KEY (`version_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#campaign_type: 1 means article campaign, 2 means image campaign
ALTER TABLE `client_campaigns` ADD `campaign_type` INT NOT NULL DEFAULT '1'; 
CREATE TABLE IF NOT EXISTS `seq_image_type_type_id` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `seq_image_type_type_id` (`id`) VALUES
(0);
CREATE TABLE IF NOT EXISTS `seq_image_keyword_keyword_id` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `seq_image_keyword_keyword_id` (`id`) VALUES
(0);
CREATE TABLE IF NOT EXISTS `seq_images_image_id` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `seq_images_image_id` (`id`) VALUES
(0);

#end
#added by nancy xu 2012-12-04  18:43
CREATE TABLE IF NOT EXISTS `image_comments` (
  `comment_id` int(11) NOT NULL DEFAULT '0',
  `image_id` int(11) NOT NULL DEFAULT '0',
  `creation_role` enum('admin','project manager','editor','agency','designer','copy writer','client') NOT NULL DEFAULT 'admin',
  `creation_user_id` int(11) NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` varchar(10) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `version_number` decimal(2,1) NOT NULL DEFAULT '1.0',
  PRIMARY KEY (`comment_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `image_version_history` CHANGE `version_history_id` `version_history_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `image_comments` CHANGE `comment_id` `comment_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `images` ADD `curr_dl_time` DATETIME AFTER `posted_by` ;
#end

#added by nancy xu 2012-12-12 11:02
ALTER TABLE `client_campaigns` ADD `total_keyword` INT NOT NULL DEFAULT '0' AFTER `max_word` ;
ALTER TABLE `client_campaigns` ADD `creation_role` VARCHAR( 255 ) NOT NULL DEFAULT 'admin' AFTER `creation_user_id` ;
#end

#added by nancy xu 2012-12-26 21:21
ALTER TABLE `client_campaigns` ADD `questions` TEXT NULL ;
ALTER TABLE `client_campaigns` ADD `keyword_file` TEXT NULL ;
CREATE TABLE IF NOT EXISTS `article_type_questions` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `question` varchar(2000) NOT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
#end

#added by nancy xu 2013-01-14 16:10
ALTER TABLE `campaign_keyword` ADD `custom_field4` VARCHAR( 255 ) NULL AFTER `custom_field3` ,
ADD `custom_field5` VARCHAR( 255 ) NULL AFTER `custom_field4` ;
UPDATE `custom_fields` SET `is_required` =1 WHERE `cfield` LIKE 'custom_field%';
ALTER TABLE `articles_version_history` ADD `custom_field4` VARCHAR( 255 ) NULL AFTER `custom_field3` ,
ADD `custom_field5` VARCHAR( 255 ) NULL AFTER `custom_field4` ;
#end

#added by nancy xu 2013-02-25 15:58
ALTER TABLE `client_campaigns` ADD `show_cp_bio` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `articles` ADD `cp_bio` TEXT NULL ;
ALTER TABLE `articles_version_history` ADD `cp_bio` TEXT NULL ;
#end

#added by nancy xu 2013-03-14 15:19
ALTER TABLE `campaign_style_guide` ADD INDEX ( `campaign_id` ) ;
#end

#added by nacy xu 2013-05-21 15:15
ALTER TABLE `candidates` ADD `clocation` VARCHAR( 45 ) NOT NULL DEFAULT 'United States';
#end

#added by nancy xu 2013-07-03 14:13
ALTER TABLE `articles` ADD `delivered_date` DATETIME NULL ;
ALTER TABLE `articles_version_history` ADD `delivered_date` DATETIME NULL ;
#end

#added by nancy xu 2013-09-22 16:03
CREATE TABLE `article_additional_fields` (
 `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `article_id` int(11) NOT NULL ,
 `small_image` varchar(2000) NOT NULL,
 `large_image` varchar(2000) NOT NULL,
 `image_credit` varchar(2000) NOT NULL,
 `image_caption` varchar(2000) NOT NULL,
 `meta_description` varchar(2000) NOT NULL,
 `blurb` TEXT NOT NULL 
 ) ENGINE = innodb CHARACTER SET utf8 COLLATE utf8_general_ci;
 ALTER TABLE `client_campaigns` ADD `template` TINYINT NULL ;
 ALTER TABLE `client_campaigns` CHANGE `template` `template` TINYINT( 4 ) NULL DEFAULT '0';
 ALTER TABLE `article_additional_fields` ADD `created_by` INT NOT NULL ,
ADD `created` INT NOT NULL ,
ADD `modified_by` INT NOT NULL ,
ADD `modified` INT NOT NULL;
#end 

#added by nancy xu 2013-10-14 11:36
ALTER TABLE `users` ADD `user_type` TINYINT NOT NULL DEFAULT '1';
#end

#added by nancy xu 2013-11-29 11:52
ALTER TABLE `article_additional_fields` ADD `deny_option` INT NULL ,
ADD `deny_memo` VARCHAR( 2000 ) NULL ;
#end

#added by nancy xu 2013-12-06 16:00
ALTER TABLE `article_additional_fields` ADD `category_id` INT NULL AFTER `blurb` ;
#end
 VARCHAR( 2 ) NOT NULL ,
ADD `paypal_email` VARCHAR( 255 ) NOT NULL ;
#end
#added by nancy xu 2014-03-18 10:03
ALTER TABLE `article_extra_info` ADD `qa_complete` TINYINT NOT NULL DEFAULT '0';
#end

#added by nancy xu 2014-04-24 12:11
CREATE TABLE IF NOT EXISTS `article_score` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) NOT NULL DEFAULT '0',
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `score` tinyint(4) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`score_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `user_month_score` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `report_month` int(11) NOT NULL DEFAULT '0',
  `score` tinyint(4) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`score_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `user_month_score` CHANGE `score` `score` DECIMAL( 8, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `user_month_score` ADD `total` INT NOT NULL DEFAULT '0';
ALTER TABLE `user_month_score` ADD `permission` INT NOT NULL ;
#general notes back up
INSERT INTO `general_notes` VALUES (1, 'Books, plays and magazines', 'Italicize names of books, plays, and magazines.', 1, 'admin', '2010-01-04 18:05:00');
INSERT INTO `general_notes` VALUES (2, 'Its versus It''s', 'The word &quot;ITS&quot; shows that something belongs to something else; for example, &quot;The\r\ndog chewed ITS bone.&quot; \r\n\r\nThe word &quot;IT''S&quot; is a contraction for the words &quot;it is.&quot; For example, &quot;IT''S a beautiful day!&quot; means &quot;It is a beautiful day.&quot;', 1, 'admin', '2010-01-04 18:06:27');
INSERT INTO `general_notes` VALUES (3, 'Capitalization of the Internet', 'When referring to the Web or the Internet as nouns, please capitalize as shown in this sentence. When referring to them as adjectives (describing words), do not capitalize (Example: &quot;internet connection,&quot; &quot;web site&quot;).', 1, 'admin', '2010-01-04 18:05:42');
INSERT INTO `general_notes` VALUES (4, 'Numbers', 'Numbers one through nine are spelled out like this, while numbers 10 and greater are written in numerals.', 1, 'admin', '2010-01-04 18:06:49');
INSERT INTO `general_notes` VALUES (5, 'Eras', 'When referring to eras, you need no apostrophes (for example, 1990s). If abbreviating the &quot;19&quot; in the 20th century, use the apostrophe BEFORE the decade (the ''80s).', 1, 'admin', '2010-01-04 18:07:30');
INSERT INTO `general_notes` VALUES (6, 'Run-on sentences', 'Watch out for run-on sentences.  These need to be broken into two distinct sentences. \r\n\r\nFor example: \r\n\r\nIncorrect: &quot;You can work alongside the cowboys at one of Idaho''s guest ranches, accommodations are modern.&quot;\r\nCorrect: &quot;You can work alongside the cowboys at one of Idaho''s guest ranches.  Accommodations are modern.&quot;', 1, 'admin', '2010-01-04 18:08:37');
INSERT INTO `general_notes` VALUES (7, 'Fragments', 'Avoid sentence fragments. Make sure all sentences have a complete subject and predicate.', 1, 'admin', '2010-01-04 18:09:07');
INSERT INTO `general_notes` VALUES (8, 'Obvious Information', 'Do not include any information that is overly obvious. Statements such as, &quot;California is a state in the United States of America,&quot; is very obvious so it comes across to the reader as filler.', 1, 'admin', '2010-01-04 18:09:59');
INSERT INTO `general_notes` VALUES (9, 'Your versus You''re', 'The word &quot;YOUR&quot; shows that something belongs to you; for example, &quot;Pick up YOUR clothes.&quot;\r\nThe word &quot;YOU''RE&quot; is a contraction for the words, &quot;you are.&quot;  For example, &quot;YOU''RE my best friend,&quot; which means, &quot;You are my best friend.&quot;) Please try not to mix these up.', 1, 'admin', '2010-01-04 18:10:41');
INSERT INTO `general_notes` VALUES (10, 'Proofread', 'Proofread your work to ensure it is free from word usage errors and typos.', 1, 'admin', '2010-01-04 18:11:32');
INSERT INTO `general_notes` VALUES (11, 'Plural Acronyms', 'To form the plural of all capitalized acronyms, you must add a lower-case S immediately following the acronym--no apostrophe is necessary. For example, &quot;The ATMs were all busy so I had to wait to deposit my check.&quot;', 1, 'admin', '2010-01-04 18:13:43');
INSERT INTO `general_notes` VALUES (12, 'Possessive S', 'If you are showing possession for a singular noun ending in the letter s, you still must add an apostrophe and an additional s to be correct; for example: &quot;Thomas''s book.&quot; \r\n\r\nIncorrect: &quot;your business'' critical and valuable packages&quot;\r\nCorrect: &quot;your business''s critical and valuable packages&quot;', 1, 'admin', '2010-01-04 18:14:51');
INSERT INTO `general_notes` VALUES (13, 'Tight Writing', 'Eliminate extraneous words for tighter writing; for example, “It’s an area with a rich maritime history, where over the years boat building, fishing, as well as lobster fishing have been the core industries.” This could read better by tightening up the writing;  “It’s an area with a rich maritime history. Over the years, boat building, fishing, and lobster fishing have been its core industries.”', 1, 'admin', '2010-01-04 18:18:08');
INSERT INTO `general_notes` VALUES (14, 'Repetition', 'Avoid repetition in your articles.  If you''ve already stated something once, it should not be restated later in the article.', 1, 'admin', '2010-01-04 18:21:49');
#TRUNCATE TABLE `general_notes` ;
#end

#added by nancy xu 2014-05-05 11:23
ALTER TABLE `campaign_keyword` ADD `qaer_id` INT NOT NULL DEFAULT '0' AFTER `copy_writer_id` ;
#end