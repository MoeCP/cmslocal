<?php
require_once '../pre.php';
require_once CMS_INC_ROOT . '/data_logs.class.php';
require_once CMS_INC_ROOT . '/restapi.class.php';
require_once CMS_INC_ROOT . '/g_tag_map.php';
require_once CMS_INC_ROOT . '/g_parameters.php';
require_once CMS_INC_ROOT . '/Pref.class.php';
#½âÎöURL
$uri = empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REQUEST_URI'] : $_SERVER['REDIRECT_URL'];
function rempty($e)
{
	return (!empty($e) || ($e === 0 || $e === '0'));
}
$u = explode('/', $uri);
$u = array_filter($u, 'rempty');
$u = array_values($u);
$u = array_pad($u, 2, null);
$moduleName = $u[0];
$actionName = $u[1];
if (empty($actionName)) {
    $actionName = 'client';
} 
$params = array_slice($u, 2);
//if (empty($params)) $params = array();
$cr = call_user_func_array(array(new $moduleName(), $actionName), $params);
echo $cr;
?>