hi1
<pre>
<?php
exit();
ini_set('display_errors',1);
include('./wp-xml-rpc/WordpressClient.php');
include('./wp-xml-rpc/Exception/NetworkException.php');
include('./wp-xml-rpc/Exception/XmlrpcException.php');

$endpoint = "http://wp.copypress.com/xmlrpc.php";
$errors = fopen('xmlrpc-errors.txt', 'w');

$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient();
$wpClient->setCredentials($endpoint, 'jkuster', ')kQ*Ezgfl#cwM(x8yn0)62Wf');
$wpClient->onError(function ($error, $event) use($errors){
    $eventr = print_r($event);
    $buffer = $error . " : " . $eventr;
    fwrite($errors, $buffer);
});

//print_r($wpClient->getPosts());
print_r($wpClient->getPost(29));
$return = $wpClient->newPost('test post2', 'test body', array('post_type' => 'bulkpost',
                                                              'post_status' => 'draft',
                                                              'custom_fields' => array(
                                                                                       array( 'key' => '_mainwp_spinner_spin_content', 'value' => null),
                                                                                       array( 'key' => '_mainwp_spinner_spin_title', 'value' => null),
                                                                                       array( 'key' => '_mainwp_kl_disable', 'value' => 0),
                                                                                       array( 'key' => '_mainwp_kl_disable_post_link', 'value' => 0),
                                                                                       array( 'key' => 'mainwp_kl_not_allowed_keywords_on_this_post', 'value' => 'a:1:{i:0;a:0:{}}'),
                                                                                       array( 'key' => '_mainwp_spinner_saved_post_options', 'value' => 'yes'),
                                                                                       array( 'key' => '_mainwp_spinner_sp_spin_title', 'value' => 0),
                                                                                       array( 'key' => '_mainwp_post_plus', 'value' => 'yes'),
                                                                                       array( 'key' => '_saved_draft_random_privelege', 'value' => 'czowOiIiOw=='),
                                                                                       array( 'key' => '_saved_draft_random_category', 'value' => ''),
                                                                                       array( 'key' => '_saved_draft_random_publish_date', 'value' => ''),
                                                                                       array( 'key' => '_saved_draft_publish_date_from', 'value' => ''),
                                                                                       array( 'key' => '_saved_draft_publish_date_to', 'value' => ''),
                                                                                       array( 'key' => '_mainwp_post_dripper_sites_number', 'value' => 1),
                                                                                       array( 'key' => '_mainwp_post_dripper_time_number', 'value' => 1),
                                                                                       array( 'key' => '_mainwp_post_dripper_select_time', 'value' => 'days'),
                                                                                       array( 'key' => '_mainwp_post_dripper_use_post_dripper', 'value' => 0),
                                                                                       /*array( 'key' => 'mainwp_post_id', 'value' => ''),*/
                                                                                       array( 'key' => '_mainwp_boilerplate', 'value' => 'yes'),
                                                                                       array( 'key' => '_mainwp_spin_me', 'value' => 'yes'),
                                                                                       array( 'key' => '_mainwp_post_dripper_selected_drip_sites', 'value' => 'YTowOnt9'),
                                                                                       array( 'key' => '_mainwp_post_dripper_total_drip_sites', 'value' => '0'),
                                                                                       array( 'key' => '_selected_sites', 'value' => 'YToxOntpOjA7czoxOiIyIjt9'),
                                                                                       array( 'key' => '_selected_groups', 'value' => 'YTowOnt9'),
                                                                                       array( 'key' => '_selected_by', 'value' => 'site'),
                                                                                       array( 'key' => '_post_to_only_existing_categories', 'value' => 0),
                                                                                       array( 'key' => '_tags', 'value' => ''),
                                                                                       array( 'key' => '_saved_draft_tags', 'value' => ''),
                                                                                       array( 'key' => '_slug', 'value' => ''),
                                                                                       array( 'key' => '_saved_as_draft', 'value' => 'yes')
                                                                                       /*array( 'key' => '', 'value' => ''),
                                                                                       array( 'key' => '', 'value' => ''),*/
                                                                                       )
                                                              )
                             );
var_dump($return);
fclose($errors);
?>
</pre>
hi