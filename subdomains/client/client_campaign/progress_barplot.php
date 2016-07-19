<?php

if (ereg("MSIE [56789]", (isset($HTTP_USER_AGENT)) ? $HTTP_USER_AGENT : getenv("HTTP_USER_AGENT"))) {
    $cacheplus = ", pre-check=0, post-check=0, max-age=0";
}
header("Expires: Wed, 11 Nov 1998 11:11:11 GMT"); 
header("Cache-Control: no-cache, must-revalidate".$cacheplus);
header("Pragma: no-cache");

require_once '../pre.php';
include_once (JPGRAPH_INC_ROOT."/jpgraph.php");
include_once (JPGRAPH_INC_ROOT."/jpgraph_bar.php");
include_once (JPGRAPH_INC_ROOT."/jpgraph_scatter.php");//用来生成10*20空图片

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 4) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';

if (user_is_loggedin()) {
    if ($_GET['client_id'] == '') {
        $datax = array(0);
        $datay = array(0);

        $graph = new Graph(10,20,"auto");
        $graph->SetScale("linlin");
        $graph->SetFrame(false);
        $graph->img->SetMargin(1,1,1,1);        
        $graph->SetMarginColor('#ffffff');
        $graph->xaxis->Hide();
        $graph->yaxis->Hide();
        //$graph->SetShadow();
        //$graph->title->Set("A simple scatter plot");
        //$graph->title->SetFont(FF_FONT1,FS_BOLD);
        $sp1 = new ScatterPlot($datay,$datax);
        $sp1->mark->SetColor("#ffffff");
        $sp1->mark->SetWidth(0);
        $graph->Add($sp1);
        $graph->Stroke();
        exit();
    } else {
        $client_id = $_GET['client_id'];
    }
} else {
    $client_id = Client::getID();
}

$q = "SELECT campaign_id, campaign_name ".
     "FROM client_campaigns ".
     "WHERE client_id = '".$client_id."'";
$rs = &$conn->Execute($q);
$i = 0;
if ($rs) {
    $result = array();
    while (!$rs->EOF) {
        $result['campaign_id'][$i] = $rs->fields['campaign_id'];
        $result['campaign_name'][$i] = $rs->fields['campaign_name'];
        $rs->MoveNext();
        $i ++;
    }
    $rs->Close();
}

if ($i > 0) {
    foreach ($result['campaign_id'] AS $kr => $vr) {
        $key_count = Campaign::countKeywordByCampaignID($vr);
        if ($key_count > 0) {
            $article_count = Campaign::countArticleByCampaignID($vr, 5);
            $result['progress'][$kr] = ($article_count / $key_count) * 100;
            //$result['progress'][$kr] .= "%";
        } else {
            $result['progress'][$kr] = 0;
        }
    }
} else {
    $datax = array(0);
    $datay = array(0);

    $graph = new Graph(10,20,"auto");
    $graph->SetScale("linlin");
    $graph->SetFrame(false);
    $graph->img->SetMargin(1,1,1,1);        
    $graph->SetMarginColor('#ffffff');
    $graph->xaxis->Hide();
    $graph->yaxis->Hide();
    //$graph->SetShadow();
    //$graph->title->Set("A simple scatter plot");
    //$graph->title->SetFont(FF_FONT1,FS_BOLD);
    $sp1 = new ScatterPlot($datay,$datax);
    $sp1->mark->SetColor("#ffffff");
    $sp1->mark->SetWidth(0);
    $graph->Add($sp1);
    $graph->Stroke();
    exit();
}


$datax = $result['campaign_name'];
$datay = $result['progress'];

// Create the graph. These two calls are always required
if (trim($_GET['height']) == '') {
    //$count = count($datax);
    if ($i > 10) {
        $height = 300 + ($count - 10) * 30;
        $graph = new Graph(640,$height,"auto");
    } else {
        $graph = new Graph(640,300,"auto");
    }
} else {
    $height = $_GET['height'];
    $width = $_GET['width'];
    $graph = new Graph($width,$height,"auto");
}
$graph->SetScale("textlin");
$graph->SetMarginColor('#eeeeee');
//$graph->yaxis->scale->SetGrace(20);


$top = 30;
$bottom = 30;
$left = 140;
$right = 20;
$graph->Set90AndMargin($left,$right,$top,$bottom);

$graph->xaxis->SetPos('min');

// Nice shadow
$graph->SetShadow();

// Setup title
$graph->title->Set("Campaign Progress");
$graph->title->SetFont(FF_FONT1,FS_BOLD,14);
//$graph->subtitle->Set("(Axis at bottom)");

// Setup X-axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_FONT1,FS_BOLD,12);

// Some extra margin looks nicer
$graph->xaxis->SetLabelMargin(5);

// Label align for X-axis
$graph->xaxis->SetLabelAlign('right','center');

// Add some grace to y-axis so the bars doesn't go
// all the way to the end of the plot area
$graph->yaxis->scale->SetGrace(20);

// Setup the Y-axis to be displayed in the bottom of the 
// graph. We also finetune the exact layout of the title,
// ticks and labels to make them look nice.
$graph->yaxis->SetPos('max');

// First make the labels look right
//$graph->yaxis->HideLabels();

// The fix the tick marks
$graph->yaxis->SetTickSide(SIDE_LEFT);

// Finally setup the title
$graph->yaxis->SetTitleSide(SIDE_RIGHT);
//$graph->yaxis->SetTitleMargin(35);

$graph->yaxis->SetTextLabelInterval(2); 

// To align the title to the right use :
//$graph->yaxis->SetTitle('人','high');
//$graph->yaxis->title->Align('right');

// To center the title use :
//$graph->yaxis->SetTitle('Turnaround 2002','center');
//$graph->yaxis->title->Align('center');

$graph->yaxis->title->SetFont(FF_FONT1,FS_NORMAL,12);
$graph->yaxis->title->SetAngle(0);

$graph->yaxis->SetFont(FF_FONT2,FS_NORMAL);
// If you want the labels at an angle other than 0 or 90
// you need to use TTF fonts
//$graph->yaxis->SetLabelAngle(0);

// Now create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillColor("orange");
//$bplot->SetShadow();

//You can change the width of the bars if you like
//$bplot->SetWidth(0.5);

// We want to display the value of each bar at the top
$bplot->SetYBase(0);
$bplot->value->Show();
$bplot->value->SetFont(FF_FONT1,FS_BOLD,10);
$bplot->value->SetAlign('left','center');
$bplot->value->SetColor("black","darkred");
$bplot->value->SetFormat('%d percent');

// Add the bar to the graph
$graph->Add($bplot);

$graph->footer->right->Set(date('Y-m-d',time()));
$graph->Stroke();
?>