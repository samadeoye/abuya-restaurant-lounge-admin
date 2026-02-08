<?php
namespace AbuyaAdmin\Api;

use Exception;

class Api
{
    public static function curlApi($params)
    {
        $method = $params['method'];
        $url = $params['url'];
        $payload = isset($params['payload']) ? $params['payload'] : [];

        $ch = curl_init($url);

        $contentType = 'application/json';
        if (isset($params['contentType']))
        {
            $contentType = $params['contentType'];
        }

        $headers = [
            'Accept: application/json'
        ];
        if (!empty($contentType))
        {
            $headers[] = "Content-Type: {$contentType}";
        }
        
        $curlArray = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method)
        ];
        if (strtolower($method) == 'post')
        {
            $curlArray[CURLOPT_POST] = true;
        }
        if (isset($params['payload']))
        {
            $payload = $params['payload'];
            if ($contentType == 'application/json')
            {
                $payload = json_encode($params['payload']);
            }
            $curlArray[CURLOPT_POSTFIELDS] = $payload;
        }
        if (array_key_exists('additionalHeaders', $params))
        {
            $headers = array_merge($headers, $params['additionalHeaders']);
        }

        $curlArray[CURLOPT_HTTPHEADER] = $headers;
        //debugData($curlArray);
        curl_setopt_array($ch, $curlArray);

        $response = curl_exec($ch);
        if (curl_errno($ch))
        {
            echo "cURL Error: " . curl_error($ch);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        //print_r($data);
        return $data;
    }

    public static function getApiToken()
    {
        $response = self::curlApi([
            'url' => DEF_API_URL.'/login'
            , 'method' => 'post'
            , 'payload' => [
                'email' => DEF_API_USER_EMAIL
                , 'password' => API_USER_PASSWORD
            ]
        ]);
        $status = isset($response['status']) ? $response['status'] : false;
        if ($status)
        {
            return $response['token'];
        }
        else
        {
            throw new Exception("Api token error - {$response['message']}");
        }
    }

    public static function callApi($request)
    {
        $params = [
            'url' => DEF_API_URL.'/'.$request['resource'],
            'method' => strtoupper($request['method'])
        ];
        if (isset($request['payload']))
        {
            $params['payload'] = $request['payload'];
        }
        if (isset($request['additionalHeaders']))
        {
            $params['additionalHeaders'] = $request['additionalHeaders'];
        }
        if (isset($request['contentType']))
        {
            $params['contentType'] = $request['contentType'];
        }
        $response = self::curlApi($params);
        //debugData($response);
        
        $status = isset($response['status']) ? $response['status'] : false;
        if ($status)
        {
            return $response;
        }
        else
        {
            $message = isset($response['message']) ? $response['message'] : 'An error occurred during process';
            throw new Exception($message);
        }
    }
}