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
        $appCode = config('sms.appCode');
        $tplId = config('sms.tplId');
        var_dump($url, $appCode, $tplId);
        $client = new Client([
            "Authorization" => "APPCODE " . $appCode
        ]);

        var_dump($url, $appCode);
        try
        {
            $result = $client->post($url, [
                'query' => [
                    'mobile' => $phone,
                    'param' => $params,
                    'tpl_id' => $tplId
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