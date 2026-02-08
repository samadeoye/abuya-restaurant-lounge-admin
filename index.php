<?php
require_once 'inc/utils.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//Remove base folder - for local
$basePath = DEF_ROOT_PATH;
$path = str_replace($basePath, '', $uri);

//Normalize
$path = trim($path, '/');

switch ($path)
{
    case '':
    case '/':
        require_once DEF_PATH_PAGES . '/dashboard.php';
        break;

    case 'actions':
        require_once DEF_PATH_ACTIONS;
        break;

    case 'modals':
        $modalFile = trim($_REQUEST['modalFile']);
        require_once DEF_PATH_MODALS."/{$modalFile}.php";
        break;

    default:
        $file = DEF_PATH_PAGES . "/{$path}.php";
        if (file_exists($file))
        {
            require_once $file;
        }
        else
        {
            http_response_code(404);
            require_once DEF_PATH_PAGES . '/404.php';
        }
}