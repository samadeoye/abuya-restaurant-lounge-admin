<?php
session_start();

$httpHost = $_SERVER['HTTP_HOST'];
$httpFolderPath = '';
$isProductionServer = true;
$isLocal = false;

if (in_array($httpHost, ['localhost', '127.0.0.1']))
{
    //LOCAL
    $httpFolderPath = '/abuya-admin';
    $httpHost = 'http://'.$httpHost;
    $isProductionServer = false;
    $isLocal = true;
}
else
{
    //PRODUCTION
    $httpHost = 'https://'.$httpHost;
}
define('DEF_ROOT_PATH', $httpFolderPath);
define('DEF_FULL_ROOT_PATH', $httpHost.$httpFolderPath);
define('DEF_ROOT_PATH_ADMIN', DEF_ROOT_PATH.'/admin');
define('DEF_DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/'. $httpFolderPath . '/');
define('DEF_DOC_ROOT_ADMIN', DEF_DOC_ROOT.'admin/');
define('DEF_IS_PRODUCTION', $isProductionServer);
define('DEF_IS_LOCAL', $isLocal);

//ASSETS
define('DEF_PATH_ASSETS_CSS', DEF_FULL_ROOT_PATH.'/public/assets/css');
define('DEF_PATH_ASSETS_JS', DEF_FULL_ROOT_PATH.'/public/assets/js');
define('DEF_PATH_ASSETS_IMG', DEF_FULL_ROOT_PATH.'/public/assets/images');
define('DEF_PATH_ASSETS_VENDORS', DEF_FULL_ROOT_PATH.'/public/assets/vendors');

define('DEF_PATH_INC', DEF_DOC_ROOT.'inc');
define('DEF_PATH_ACTIONS', DEF_PATH_INC.'/actions.php');
define('DEF_PATH_VIEWS', DEF_DOC_ROOT.'app/views');
define('DEF_PATH_PAGES', DEF_PATH_VIEWS.'/pages');
define('DEF_PATH_MODALS', DEF_PATH_VIEWS.'/modals');
define('DEF_PATH_NAVBAR', DEF_PATH_VIEWS.'/partials/navbar.php');
define('DEF_PATH_SIDEBAR', DEF_PATH_VIEWS.'/partials/sidebar.php');
define('DEF_PATH_FOOTER', DEF_PATH_VIEWS.'/partials/footer.php');

error_reporting(E_ALL);
if (DEF_IS_LOCAL)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL);
}

require_once DEF_DOC_ROOT.'vendor/autoload.php';
require_once DEF_DOC_ROOT.'inc/functions.php';
require_once DEF_DOC_ROOT.'inc/constants.php';
require_once DEF_DOC_ROOT.'inc/dropdowns.php';

$arAdditionalCSS = $arAdditionalJs = $arAdditionalJsScripts = $arAdditionalJsOnLoad = [];
$arUser = [];
$userId = '';
$isLoggedIn = false;
if (isset($_SESSION[SESSION_NAME]))
{
    $arUser = AbuyaAdmin\Auth\Auth::getUserSession();
    $userId = $arUser['id'];

    $isLoggedIn = true;
}