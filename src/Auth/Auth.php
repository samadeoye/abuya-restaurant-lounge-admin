<?php
namespace AbuyaAdmin\Auth;

use AbuyaAdmin\Api\Api;
use Exception;

class Auth
{
    public static $dataJson = [];
    private static $sessionName = SESSION_NAME;

    public static function register($request)
    {
        $response = Api::callApi([
            'method' => 'POST',
            'resource' => 'register',
            'payload' => [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $request['password'],
                'password_confirmation' => $request['password_confirmation'],
                'role' => $request['role'],
            ]
        ]);
        if (isset($response['token']) && isset($response['user']))
        {
            $user = $response['user'];
            $user['token'] = $response['token'];
            //login
            $_SESSION[self::$sessionName] = $user;
        }
        else
        {
            throw new Exception('Registration failed! Please try again');
        }
    }

    public static function login($request)
    {
        $response = Api::callApi([
            'method' => 'POST',
            'resource' => 'login',
            'payload' => [
                'email' => $request['email'],
                'password' => $request['password']
            ]
        ]);
        if (isset($response['token']) && isset($response['user']))
        {
            $user = $response['user'];
            $user['token'] = $response['token'];
            //login
            $_SESSION[self::$sessionName] = $user;
        }
        else
        {
            throw new Exception('Login failed! Please try again');
        }
    }

    public static function logout()
    {
        $arUser = self::getUserSession();
        if (!empty($arUser))
        {
            $token = '';
            if (isset($arUser['token']) && strlen($arUser['token']) > 0)
            {
                $token = $arUser['token'];
            }

            //regardless of the API response, unset session
            unset($_SESSION[self::$sessionName]);
            session_destroy();

            if ($token != '')
            {
                Api::callApi([
                    'method' => 'POST',
                    'resource' => 'logout',
                    'additionalHeaders' => [
                        "Authorization: Bearer {$token}"
                    ]
                ]);
            }
        }
    }

    public static function forgotPassword($request)
    {
        $response = Api::callApi([
            'method' => 'POST',
            'resource' => 'forgot-password',
            'payload' => [
                'email' => $request['email'],
                'redirect_url' => DEF_FULL_ROOT_PATH.'/reset-password'
            ]
        ]);
        if (isset($response['status']) && isset($response['status']))
        {
            self::$dataJson['msg'] = $response['status'];
        }
        else
        {
            throw new Exception('Request failed! Please try again');
        }
    }

    public static function resetPassword($request)
    {
        $response = Api::callApi([
            'method' => 'POST',
            'resource' => 'reset-password',
            'payload' => [
                'token' => $request['token'],
                'email' => $request['email'],
                'password' => trim($request['password']),
                'password_confirmation' => trim($request['password_confirmation']),
            ]
        ]);
        if (isset($response['status']) && isset($response['status']))
        {
            self::$dataJson['msg'] = $response['status'];
        }
        else
        {
            throw new Exception('Request failed! Please try again');
        }
    }

    public static function getUserSession()
    {
        if (isset($_SESSION[self::$sessionName]))
        {
            return $_SESSION[self::$sessionName];
        }

        return [];
    }

    public static function getUserToken()
    {
        $arUser = self::getUserSession();
        $token = '';
        if (isset($arUser['token']) && strlen($arUser['token']) > 0)
        {
            $token = $arUser['token'];
        }
        return $token;
    }
}