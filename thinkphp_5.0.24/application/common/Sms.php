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
    /***
     * 调用接口发送短信
     * @param $phone 电话号码
     * @param $params 替换参数
     * @return mixed 短信接口返回内容
     * @throws \Exception guzzle异常
     */
    public static function SendSms($phone, $params)
    {
        $url = config('sms.host') . config('sms.path');
        $appCode = config('sms.appCode');
        $tplId = config('sms.tplId');

        $client = new Client();

        try
        {
            $result = $client->post($url, [
                'headers' => [
                    'Authorization' => 'APPCODE ' . $appCode
                ],
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