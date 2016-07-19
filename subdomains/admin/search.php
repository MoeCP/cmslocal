<?php
$g_current_path = 'article';
require_once 'pre.php';
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('client/cms_client_menu.php');
} else {
    require_once('cms_menu.php');
}

if ((!user_is_loggedin() || User::getPermission() < 4) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

//require_once CMS_INC_ROOT.'/Campaign.class.php';
//require_once CMS_INC_ROOT.'/Article.class.php';

if ($_GET['search_type'] == 'users' && User::getPermission() >= 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/user/list.php?keyword=".$_GET['keyword']);
    exit;
} elseif ($_GET['search_type'] == 'keywords') {
    if (User::getPermission() >= 3) { // 2=>3
        header("Location: http://".$_SERVER['HTTP_HOST']."/client_campaign/keyword_list.php?keyword=".$_GET['keyword']);
        exit;
    } else {
        header("Location: http://".$_SERVER['HTTP_HOST']."/article/article_keyword_list.php?keyword=".$_GET['keyword']);
        exit;
    }
} elseif ($_GET['search_type'] == 'campaings' && (User::getPermission() >= 3 || client_is_loggedin())) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/client_campaign/list.php?keyword=".$_GET['keyword']);
    exit;
} elseif ($_GET['search_type'] == 'clients' && User::getPermission() >= 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/client/list.php?keyword=".$_GET['keyword']);
    exit;
} else {
    if (client_is_loggedin()) {
        header("Location: http://".$_SERVER['HTTP_HOST']."/article/article_keyword_list.php?keyword=".$_GET['keyword']);
        exit;
    } else {
        if (client_is_loggedin())  {
            header("Location: http://".$_SERVER['HTTP_HOST']."/client_campaign/list.php?keyword=".$_GET['keyword']);
            exit;
        } else {
            header("Location: http://".$_SERVER['HTTP_HOST']."/article/article_list.php?keyword=".$_GET['keyword']);
            exit;
        }
    }
}
?>