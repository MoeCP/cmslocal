<?php
require_once 'pre_cron.php';
require_once  CMS_INC_ROOT . DS . 'Pref.class.php';
$file = CRONJOB_INC_ROOT .  "states.txt";
echo $file . "<br />";
$content = 'male
female';
$conn->debug = true;
$content = 'Tech/Vocational';
if (file_exists($file) || !empty($content)) {
    if (!empty($content)) {
        $arr = explode("\n", $content);       
        foreach ($arr as $key => $c ) {
            $pref_value[] = trim($c);
        }
        $values = array_unique($pref_value);
        $j = 0;
        foreach ($values as $v) {
            $value[$j] = $v;
            $j++;
        }

        $pat = array('pref_table' => 'candidates',
             'pref_field' => 'education',
             'pref_values' => $value
            );
       
        $res = Preference::storeBatch($pat);
        if ($res) {
            echo $feedback;
        }else {
            echo "failed";
        }
    } else {
        echo "file can't be read";
    }
} else {
    echo "file does not exist";
}
?>