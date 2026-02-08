<?php
namespace AbuyaAdmin\Room;

use AbuyaAdmin\Api\Api;
use AbuyaAdmin\Auth\Auth;
use CURLFile;
use Exception;

class Room
{
    private static $resource = 'rooms';
    public static $dataJson = [];

    public static function getRooms()
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

    public static function getRoomsList()
    {
        $rs = self::getRooms();
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];
                $featuredImage = $r['featured_image'];
                if (!empty($featuredImage))
                {
                    $featuredImageUrl = isset($featuredImage['url']) ? $featuredImage['url'] : null;
                    if (!empty($featuredImageUrl))
                    {
                        $featuredImage = <<<EOQ
<a href="{$featuredImageUrl}" target="_blank"><img src="{$featuredImageUrl}"></a>
EOQ;
                    }
                }

                $row = [
                    'sn' => $sn,
                    'title' => $r['title'],
                    'price' => getCurrencyAmount($r['price']),
                    'short_description' => stringToTitle($r['short_description']),
                    'featured_image' => $featuredImage,
                    'cdate' => $r['created_at'],
                    'mdate' => $r['updated_at'],
                ];
                $row['edit'] = <<<EOQ
<button type="button" class="btn btn-primary btn-sm" onclick="editRoom('{$id}')">
    Edit
</button>
EOQ;
                $row['delete'] = <<<EOQ
<button type="button" class="btn btn-danger btn-sm" onclick="deleteRoom('{$id}')">
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

    public static function getRoom($id)
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

    public static function addRoom($request)
    {
        $title = isset($request['title']) ? $request['title'] : '';
        $price = isset($request['price']) ? doTypeCastDouble($request['price']) : 0;
        $shortDescription = isset($request['short_description']) ? $request['short_description'] : '';
        $description = isset($request['description']) ? $request['description'] : '';
        $facilityIds = isset($request['facilities']) ? $request['facilities'] : [];

        if (empty($title) || $price == 0)
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'title' => $title,
            'price' => $price,
            'short_description' => $shortDescription,
            'description' => $description,
        ];
        //facilities
        if (!empty($facilityIds))
        {
            $payload['facility_ids[]'] = $facilityIds;
        }
        //featured image
        if (isset($_FILES['featured_image']))
        {
            if (!empty($_FILES['featured_image']['tmp_name']))
            {
                $payload['featured_image'] = new CURLFile(
                    $_FILES['featured_image']['tmp_name'],
                    $_FILES['featured_image']['type'],
                    $_FILES['featured_image']['name']
                );
            }
        }
        //gallery images
        if (isset($_FILES['gallery_images']))
        {
            if (!empty($_FILES['gallery_images']['tmp_name'][0]))
            {
                foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name)
                {
                    if (!empty($tmp_name))
                    {
                        $payload["gallery_images[{$key}]"] = new CURLFile(
                            $tmp_name,
                            $_FILES['gallery_images']['type'][$key],
                            $_FILES['gallery_images']['name'][$key]
                        );
                    }
                }
            }
        }

        $token = Auth::getUserToken();
        Api::callApi([
            'method' => 'POST',
            'resource' => self::$resource,
            'payload' => $payload,
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ],
            'contentType' => ""
        ]);

        self::$dataJson['msg'] = 'Record added successfully';
    }

    public static function updateRoom($request)
    {
        $id = isset($request['id']) ? doTypeCastInt($request['id']) : '';
        $title = isset($request['title']) ? $request['title'] : '';
        $price = isset($request['price']) ? doTypeCastDouble($request['price']) : 0;
        $shortDescription = isset($request['short_description']) ? $request['short_description'] : '';
        $description = isset($request['description']) ? $request['description'] : '';
        $facilityIds = isset($request['facilities']) ? $request['facilities'] : [];

        if ($id == 0 || empty($title) || $price == 0)
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            '_method' => 'PUT',
            'title' => $title,
            'price' => $price,
            'short_description' => $shortDescription,
            'description' => $description
        ];
        //facilites
        if (!empty($facilityIds))
        {
            $payload['facility_ids[]'] = $facilityIds;
        }
        if (isset($request['deleted_image_ids']) && !empty($request['deleted_image_ids']))
        {
            $payload['deleted_image_ids[]'] = explode(',', $request['deleted_image_ids']);
        }
        //featured image
        if (isset($_FILES['featured_image']))
        {
            if (!empty($_FILES['featured_image']['tmp_name']))
            {
                $payload['featured_image'] = new CURLFile(
                    $_FILES['featured_image']['tmp_name'],
                    $_FILES['featured_image']['type'],
                    $_FILES['featured_image']['name']
                );
            }
        }
        //gallery
        if (isset($_FILES['gallery_images']))
        {
            if (!empty($_FILES['gallery_images']['tmp_name'][0]))
            {
                foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name)
                {
                    if (!empty($tmp_name))
                    {
                        $payload["gallery_images[{$key}]"] = new CURLFile(
                            $tmp_name,
                            $_FILES['gallery_images']['type'][$key],
                            $_FILES['gallery_images']['name'][$key]
                        );
                    }
                }
            }
        }

        $token = Auth::getUserToken();
        $resource = self::$resource;
        Api::callApi([
            'method' => 'POST',
            'resource' => "{$resource}/{$id}",
            'payload' => $payload,
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ],
            'contentType' => ""
        ]);

        self::$dataJson['msg'] = 'Record updated successfully';
    }

    public static function deleteRoom($request)
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