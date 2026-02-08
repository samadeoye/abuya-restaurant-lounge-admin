<?php
namespace AbuyaAdmin\ProductCategory;

use AbuyaAdmin\Api\Api;
use AbuyaAdmin\Auth\Auth;
use Exception;

class ProductCategory
{
    private static $resource = 'categories';
    public static $dataJson = [];

    public static function getProductCategories()
    {
        $token = Auth::getUserToken();

        $response = Api::callApi([
            'method' => 'GET',
            'resource' => self::$resource,
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ]
        ]);
        if (isset($response['data']))
        {
            return checkIsArray($response['data']);
        }
        else
        {
            throw new Exception('Records could not be retrieved!');
        }
    }

    public static function getProductCategoriesList()
    {
        $rs = self::getProductCategories();
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];

                $row = [
                    'sn' => $sn,
                    'title' => $r['title'],
                    'cdate' => $r['created_at'],
                    'mdate' => $r['updated_at'],
                ];
                $row['edit'] = <<<EOQ
<button type="button" class="btn btn-primary btn-sm" onclick="editProductCategory('{$id}')">
    Edit
</button>
EOQ;
                $row['delete'] = <<<EOQ
<button type="button" class="btn btn-danger btn-sm" onclick="deleteProductCategory('{$id}')">
    Delete
</button>
EOQ;
                $rows[] = $row;
                $sn++;
            }
            $data = [
                'status' => true,
                'msg' => 'Records fetched successfully!',
                'data' => $rows
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg' => 'No record found!',
                'data' => []
            ];
        }
        self::$dataJson = $data;
    }

    public static function getProductCategory($id)
    {
        $token = Auth::getUserToken();
        $resource = self::$resource;
        $response = Api::callApi([
            'method' => 'GET',
            'resource' => "{$resource}/{$id}",
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ]
        ]);
        if (isset($response['data']))
        {
            return checkIsArray($response['data']);
        }
        else
        {
            throw new Exception('Record could not be retrieved!');
        }
    }

    public static function addProductCategory($request)
    {
        $title = isset($request['title']) ? $request['title'] : '';
        $description = isset($request['description']) ? $request['description'] : '';

        if (empty($title))
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'title' => $title,
            'description' => $description,
        ];

        $token = Auth::getUserToken();
        Api::callApi([
            'method' => 'POST',
            'resource' => self::$resource,
            'payload' => $payload,
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ],
        ]);

        self::$dataJson['msg'] = 'Record added successfully';
    }

    public static function updateProductCategory($request)
    {
        $id = isset($request['id']) ? doTypeCastInt($request['id']) : '';
        $title = isset($request['title']) ? $request['title'] : '';
        $description = isset($request['description']) ? $request['description'] : '';

        if ($id == 0 || empty($title))
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'title' => $title,
            'description' => $description,
        ];

        $token = Auth::getUserToken();
        $resource = self::$resource;
        Api::callApi([
            'method' => 'PUT',
            'resource' => "{$resource}/{$id}",
            'payload' => $payload,
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ],
        ]);

        self::$dataJson['msg'] = 'Record updated successfully';
    }

    public static function deleteProductCategory($request)
    {
        $id = isset($request['id']) ? doTypeCastInt($request['id']) : '';

        if ($id == 0)
        {
            throw new Exception('Invalid request!');
        }

        $token = Auth::getUserToken();
        $resource = self::$resource;
        Api::callApi([
            'method' => 'DELETE',
            'resource' => "{$resource}/{$id}",
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ]
        ]);

        self::$dataJson['msg'] = 'Record deleted successfully';
    }
}