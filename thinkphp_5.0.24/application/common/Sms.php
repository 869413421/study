<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/6
 * Time: 11:44
 */

namespace app\common;


use GuzzleHttp\Client;

class Sms
{
    public static function SendSms($phone, $params)
    {
        $url = config('sms.host') . config('sms.path');
        $client = new Client([
            "Authorization" => "APPCODE " . config('sms.appCode')
        ]);

        try
        {
            $result = $client->get($url, [
                'query' => [
                    'mobile' => $phone,
                    'param' => $params,
                    'tpl_id' => config('sms.appCode')
                ]
            ]);
        }
        catch (\Exception $exception)
        {
            throw new \Exception($exception->getMessage());
        }

        return json_decode($result->getBody()->getContents(), true);
    }
}