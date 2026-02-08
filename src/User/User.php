<?php
namespace AbuyaAdmin\User;

use AbuyaAdmin\Api\Api;
use AbuyaAdmin\Auth\Auth;
use Exception;

class User
{
    private static $resource = 'users';
    public static $dataJson = [];

    public static function getUsers()
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

    public static function getUsersList()
    {
        $rs = self::getUsers();
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];

                $row = [
                    'sn' => $sn,
                    'name' => $r['name'],
                    'email' => $r['email'],
                    'cdate' => $r['created_at'],
                    'mdate' => $r['updated_at'],
                ];
                $row['edit'] = <<<EOQ
<button type="button" class="btn btn-primary btn-sm" onclick="editUser('{$id}')">
    Edit
</button>
EOQ;
                $row['delete'] = <<<EOQ
<button type="button" class="btn btn-danger btn-sm" onclick="deleteUser('{$id}')">
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

    public static function getUser($id)
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

    public static function addUser($request)
    {
        $name = isset($request['name']) ? $request['name'] : '';
        $email = isset($request['email']) ? $request['email'] : '';

        if (empty($name) || empty($email))
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'name' => $name,
            'email' => $email,
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

    public static function updateUser($request)
    {
        $arUser = isset($GLOBALS['arUser']) ? $GLOBALS['arUser'] : [];
        if (empty($arUser))
        {
            throw new Exception('An error occurred!'); //user info must be found
        }

        $id = $arUser['id'];
        $name = isset($request['name']) ? $request['name'] : '';
        $email = isset($request['email']) ? $request['email'] : '';

        if ($id == 0 || empty($name) || empty($email))
        {
            throw new Exception('Invalid request!');
        }

        $payload = [
            'name' => $name,
            'email' => $email,
            'role' => $arUser['role'],
        ];

        $token = Auth::getUserToken();
        $resource = self::$resource;
        $response = Api::callApi([
            'method' => 'PUT',
            'resource' => "{$resource}/{$id}",
            'payload' => $payload,
            'additionalHeaders' => [
                "Authorization: Bearer {$token}"
            ],
        ]);
        if (isset($response['data']))
        {
            $data = $response['data'];
            self::$dataJson['data'] = [
                'name' => isset($data['name']) ? $data['name'] : '',
                'email' => isset($data['email']) ? $data['email'] : '',
            ];
        }

        self::$dataJson['msg'] = 'Record updated successfully';
    }

    public static function changePassword($request)
    {
        $arUser = isset($GLOBALS['arUser']) ? $GLOBALS['arUser'] : [];
        if (empty($arUser))
        {
            throw new Exception('An error occurred!'); //user info must be found
        }

        $id = $arUser['id'];
        $password = isset($request['password']) ? $request['password'] : '';
        $passwordConfirmation = isset($request['password_confirmation']) ? $request['password_confirmation'] : '';

        if ($id == 0 || empty($password) || empty($password))
        {
            throw new Exception('Please fill the required fields');
        }
        elseif (strlen($password) < 8 || strlen($passwordConfirmation) < 8)
        {
            throw new Exception('Password must be at least 8 characters');
        }
        elseif ($password != $passwordConfirmation)
        {
            throw new Exception('Passwords do not match');
        }

        $payload = [
            'password' => $password,
            'password_confirmation' => $passwordConfirmation
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

    public static function deleteUser($request)
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