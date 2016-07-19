<?php
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'GeographicName.class.php';
$handle = fopen(CRONJOB_INC_ROOT . 'log.txt', 'w');
$content = file_get_contents(CRONJOB_INC_ROOT . 'geographic-additional.csv');
$content = str_replace("\r\n", "\n", $content);
$contentLines = explode("\n", $content);
//fwrite($handle, var_export($contentLines, true) . "\n");
$len = count($contentLines);
$types = array(
    1=>'country',
    2=>'state',
    3=>'city',
);
$flipTypes = array_flip($types);
$totalGeos = array();
for ($i = 0;$i < $len; $i++)
{
    $line = $contentLines[$i];
    if (empty($line))
        continue;
    $geos = explode(";", $line);
    $parent = 0;
    $items = array(
        'country' => trim($geos[0], '"'),
        'state' => trim($geos[1], '"'),
        'city' => trim($geos[2], '"'),
    );
    //fwrite($handle, var_export($items, true) . "\n");
    $ids = array();
    //fwrite($handle,  "{$geo_id}: {$item} {$type}; {$geos[0]}; {$geos[1]}; {$geos[2]}\n");
    foreach ($items as $k => $item)
    {
        if (empty($item)) continue;
        $type = $flipTypes[$k];
        if ($type == 1)
        {
            $parent_id = 0;
        }
        else
        {
            $parent_id = isset($ids['state']) ? $ids['state'] : $ids['country'];
        }
        $geo_id = GeographicName::getIDByNameAndType($item, $type, $parent_id);
        //fwrite($handle, "geo id" . $geo_id . "\n");
        if ($geo_id <= 0)
        {
            $hash['name'] = $item;
            $hash['type']  = $type;
            $hash['parent_id'] = $parent_id;
            //fwrite($handle, var_export($hash, true) . "\n");
            $geo_id = GeographicName::store($hash);
        }
        $ids[$k] = $geo_id;
    }
    //fwrite($handle, var_export($ids, true) . "\n");
/*    for ($j = 0; $j < count($geos); $j++)
    {
        fwrite($handle, 'parent id: ' . $parent . "\n");
        $name = $geos[$j];
        $type  = $j+1;
        if ($type == 1)
            $parent = 0;
        $hash['parent_id'] = $parent;
        $hash['type']       = $type;
        $hash['name']      = trim($name, '"');
        fwrite($handle, var_export($hash, true) . "\n");
        if (empty($name))
        {
            continue;
        }
        else
        {
            $get_id = GeographicName::getIDByNameAndType($name, $type, $parent);
            fwrite($handle, 'Get ID by name and type: ' . $get_id . "\n");
            if ($get_id > 0)
            {
                $parent = $get_id;
                continue;
            }
            else
            {
                $parent = GeographicName::store($hash);
            }
            fwrite($handle, 'parent id: ' . $parent . "\n");
        }
    }*/
}
fclose($handle);
?>