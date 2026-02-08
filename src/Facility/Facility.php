<?php
namespace AbuyaAdmin\Facility;

use AbuyaAdmin\Api\Api;
use AbuyaAdmin\Auth\Auth;
use Exception;

class Facility
{
    private static $resource = 'facilities';
    public static $dataJson = [];

    public static function getFacilities()
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

    public static function getFacilitiesList()
    {
        $rs = self::getFacilities();
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];
                $icon = $r['icon'];
                if (!empty($icon))
                {
                    $icon = <<<EOQ
<i class="{$icon}"></i>
EOQ;
                }

                $row = [
                    'sn' => $sn,
                    'title' => $r['title'],
                    'icon' => $icon,
                    'cdate' => $r['created_at'],
                    'mdate' => $r['updated_at'],
                ];
                $row['edit'] = <<<EOQ
<button type="button" class="btn btn-primary btn-sm" onclick="editFacility('{$id}')">
    Edit
</button>
EOQ;
                $row['delete'] = <<<EOQ
<button type="button" class="btn btn-danger btn-sm" onclick="deleteFacility('{$id}')">
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

    public static function getFacility($id)
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

    public static function addFacility($request)
    {
        $title = isset($request['title']) ? $request['title'] : '';
        $icon = isset($request['icon']) ? $request['icon'] : '';

        if (empty($title) || empty($icon))
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'title' => $title,
            'icon' => $icon,
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

    public static function updateFacility($request)
    {
        $id = isset($request['id']) ? doTypeCastInt($request['id']) : '';
        $title = isset($request['title']) ? $request['title'] : '';
        $icon = isset($request['icon']) ? $request['icon'] : '';

        if ($id == 0 || empty($title) || empty($icon))
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'title' => $title,
            'icon' => $icon,
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

    public static function deleteFacility($request)
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