<?php
require_once('../pre.php');
$g_current_path = 'home';
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Email.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
global $feedback;

if (!empty($_POST)) {
    $p = $_POST;
    $cid = isset($_GET['cid']) && $_GET['cid'] ? $_GET['cid'] : $p['candidate_id'];
    $user_id = isset($_GET['user_id']) && $_GET['user_id'] ? $_GET['user_id'] : $p['user_id'];
    $opt_index = intval($p['opt_index']);
    unset($p['opt_index']);
    if ($opt_index == 0) {
        $cid = Candidate::saveBasic($p);
        echo "<script>alert('".$feedback."');";
        if ($cid > 0) {
            if ($opt_index ==0)  echo 'jQuery(\'input[name="candidate_id"]\').each(function(){jQuery(this).val(\'' . $cid .'\')});';
            if ($opt_index <  1) {
                //$opt_index++;
                echo "showTab(" . $opt_index . ");";
            }
        }
        echo "</script>";
    } else if ($opt_index > 0) {
        $info = Candidate::getCandidateInfo($cid);
        if ($cid > 0 && !empty($_FILES)) {
            $feedback = 'Uploaded invalid files, please to check.';
            if (!empty($_FILES['samples']['name']['fileField'])) {
                $result = uploadedFiles('samples', $cid, 'candidate_samples');
                //print_r($result);
                if (is_array($result) && !empty($result)) {
                    $oldData = $info['samples']['fileField'];
                    foreach ($result as $k => $item) {
                        if (empty($item) && !empty($oldData[$k])) {
                            $result[$k] = $oldData[$k];
                        }
                    }
                    $p['samples']['fileField'] = $result;
                } else {
                    echo $feedback;exit();
                }
            }
            if (!empty($_FILES['categories']['name']['fileField'])) {
                $result = uploadedFiles('categories', $cid, 'candidate_categories');
                if (is_array($result) && !empty($result)) {
                    $oldData = $info['categories']['fileField'];
                    foreach ($result as $k => $item) {
                        if (empty($item) && !empty($oldData[$k])) {
                            $result[$k] = $oldData[$k];
                        }
                    }
                    $p['categories']['fileField'] = $result;
                } else {
                    echo $feedback;exit();
                }
            }
        } else if ($cid > 0) {
            if (!empty($info['samples'])) {
                $p['samples']['fileField'] = $info['samples']['fileField'];
            }
            if (!empty($info['categories'])) {
                $p['categories']['fileField'] = $info['categories']['fileField'];
            }
        }
        $cid = Candidate::saveInfo($p);
        echo $feedback;exit();
    }
}
?>