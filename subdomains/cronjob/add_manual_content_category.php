<?php
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . "manual_content.class.php";
$p['pref_table'] = "cp_candidates";
$p['pref_field'] = "from_where";
$categories = array("Search Engine", "Word of Mouth", "Infinitenine Writer", "Job Posting", "Other");

if (gettype($cagegories) == "string" ) {
    $pat = "/,_-[ ];|/";
    $cat = preg_replace($pat, ",", $categories);
    $cat_arr = explode(",", $cat_arr);
} else if (is_array($categories)) {
    $cat_arr = $categories;
}
foreach($cat_arr as $cat) {
    $p['pref_value'] = $cat;
    $res = ManualContent::addContentCategory($p);
    if (!$res) {
        echo $cat . " : " . $feedback . "<br />";
        continue;
    }
}
echo "succeed!";
?>