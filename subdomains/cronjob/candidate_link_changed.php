<?php
require_once 'pre_cron.php';
$sql = "select * from candidates WHERE plinks LIKE '%http://www.http://%' OR samples LIKE '%http://www.http://%' OR categories LIKE '%http://www.http://%'";
$conn->debug= true;
$result = $conn->GetAll($sql);
foreach ($result as $k => $item) {
    $plinks = unserialize($item['plinks']);
    $samples = unserialize($item['samples']);
    $categories = unserialize($item['categories']);
    if (!empty($plinks)) {
        foreach ($plinks as $subkey => $row) {
            $link = $row['value'];
            if (!empty($link)) {
                $plinks[$subkey]['value'] = generateValidateUrl($link);
            }
        }
    }
    if (!empty($samples)) {
        foreach ($samples as $subkey => $row) {
            $link = $row['link'];
            if (!empty($link)) {
                $samples[$subkey]['link'] = generateValidateUrl($link);
            }
        }
    }
    if (!empty($categories)) {
        foreach ($categories as $subkey => $row) {
            $link = $row['link'];
            if (!empty($link)) {
                $categories[$subkey]['link'] = generateValidateUrl($link);
            }
        }
    }
    if ($item['candidate_id'] > 0) {
        $sql = "UPDATE candidates set categories='" . serialize($categories) . "', samples='" . serialize($samples) . "', plinks='" . serialize($plinks) ."' WHERE candidate_id = " . $item['candidate_id'];
        $conn->Execute($sql);
    }
}
?>