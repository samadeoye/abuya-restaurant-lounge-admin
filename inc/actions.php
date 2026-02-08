<?php
require_once 'utils.php';

use AbuyaAdmin\Auth\Auth;
use AbuyaAdmin\User\User;
use AbuyaAdmin\ProductCategory\ProductCategory;
use AbuyaAdmin\Product\Product;
use AbuyaAdmin\Facility\Facility;
use AbuyaAdmin\Room\Room;

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
if ($action == '')
{
    getJsonRow(false, 'Invalid request!');
}

try
{
    $data = $extraData = [];
    
    switch ($action)
    {
        //AUTH
        case 'register':
            Auth::register($_REQUEST);
        break;

        case 'login':
            Auth::login($_REQUEST);
        break;

        case 'logout':
            Auth::logout();
        break;

        case 'forgotpassword':
            Auth::forgotPassword($_REQUEST);
            $extraData = Auth::$dataJson;
        break;

        case 'resetpassword':
            Auth::resetPassword($_REQUEST);
            $extraData = Auth::$dataJson;
        break;

        //USER
        case 'updateprofile':
            User::updateUser($_REQUEST);
            $extraData = User::$dataJson;
            break;

        case 'changepassword':
            User::changePassword($_REQUEST);
            $extraData = User::$dataJson;
            break;

        //PRODUCT CATEGORIES
        case 'getcategories':
            ProductCategory::getProductCategoriesList();
            $rs = ProductCategory::$dataJson;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addproductcategory':
            ProductCategory::addProductCategory($_REQUEST);
            $extraData = ProductCategory::$dataJson;
        break;

        case 'updateproductcategory':
            ProductCategory::updateProductCategory($_REQUEST);
            $extraData = ProductCategory::$dataJson;
        break;

        case 'deleteproductcategory':
            ProductCategory::deleteProductCategory($_REQUEST);
            $extraData = ProductCategory::$dataJson;
        break;

        //PRODUCTS
        case 'getproducts':
            Product::getProductsList($_REQUEST);
            $rs = Product::$dataJson;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addproduct':
            Product::addProduct($_REQUEST);
            $extraData = Product::$dataJson;
        break;

        case 'updateproduct':
            Product::updateProduct($_REQUEST);
            $extraData = Product::$dataJson;
        break;

        case 'deleteproduct':
            Product::deleteProduct($_REQUEST);
            $extraData = Product::$dataJson;
        break;

        //FACILITIES
        case 'getfacilities':
            Facility::getFacilitiesList();
            $rs = Facility::$dataJson;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addfacility':
            Facility::addFacility($_REQUEST);
            $extraData = Facility::$dataJson;
        break;

        case 'updatefacility':
            Facility::updateFacility($_REQUEST);
            $extraData = Facility::$dataJson;
        break;

        case 'deletefacility':
            Facility::deleteFacility($_REQUEST);
            $extraData = Facility::$dataJson;
        break;

        //ROOMS
        case 'getrooms':
            Room::getRoomsList();
            $rs = Room::$dataJson;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addroom':
            Room::addRoom($_REQUEST);
            $extraData = Room::$dataJson;
        break;

        case 'updateroom':
            Room::updateRoom($_REQUEST);
            $extraData = Room::$dataJson;
        break;

        case 'deleteroom':
            Room::deleteRoom($_REQUEST);
            $extraData = Room::$dataJson;
        break;

        default:
            getJsonRow(false, 'Unknown request!');
    }

    if (count($data) > 0)
    {
        getJsonList($data);
    }
    getJsonRow(true, 'Operation successful!', $extraData);
}
catch(Exception $ex)
{
	// $ex->getMessage();exit;
    getJsonRow(false, $ex->getMessage());
}