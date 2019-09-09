<?php
namespace YsepaySdk\Qrcode;

use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

class Client extends BaseClient
{

    /**
     * 扫码支付
     * @param $data
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/9 上午12:28
     */
    public function pay($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.qrcodepay';
        $myParams['return_url'] = $data['return_url'];
        $myParams['notify_url'] = $data['notify_url'];

        $biz_content_arr = array(
            "out_trade_no" => $data['out_trade_no'],
            "shopdate" => date('Ymd'),
            "subject" => $data['subject'],
            "total_amount" => $data['total_amount'],
            "seller_id" => $this->app->config->seller_id,
            "seller_name" => $this->app->config->seller_name,
            "timeout_express" => $data['timeout_express'] ?? "24h",
            "business_code" => $this->app->config->business_code,
            "bank_type" => $data['bank_type'],
            //"bank_type" => "1903000" //支付宝
//            "bank_type" => "9001002"  //银联
//            "bank_type" => "1904000"  //QQ
//            "bank_type" => "1902000"  //微信
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_online_qrcodepay_response');
    }

    /**
     * 被扫
     * @param $data
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/9 上午12:28
     */
    public function barcodePay($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.barcodepay';
        $myParams['notify_url'] = $data['notify_url'];

        $biz_content_arr = array(
            "out_trade_no"      => $data['out_trade_no'],
            "shopdate"          => date('Ymd'),
            "subject"           => $data['subject'],
            "total_amount"      => $data['total_amount'],
            "seller_id"         => $this->app->config->seller_id,
            "seller_name"       => $this->app->config->seller_name,
            "timeout_express"   => $data['timeout_express'] ?? "24h",
            "business_code"     => $this->app->config->business_code,
            "bank_type"         => $data['bank_type'],
            //            "bank_type" => "1903000",  //支付宝
//            "bank_type" => "9001002",  //中国银联
//            "bank_type" => "1905000",  //苏宁
//            "scene" => "bar_code",   //支付场景，支付宝时必填
//            "scene" => "wave_code",   //支付宝时必填
            "auth_code"         => $data['auth_code'],
//            "device_info" => "cs002356",  //终端设备号，中国银联时必填
        );
        if($data['bank_type'] == self::$bank_type_list['alipay']){
            if(!in_array($data['scene'], ['bar_code','wave_code'])){
                throw new YsepayException('scene is illegal');
            }
            $biz_content_arr['scene'] = $data['scene'];
        }
        if($data['bank_type'] == self::$bank_type_list['unionpay']){
            $biz_content_arr['device_info'] = $data['device_info'];
        }
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams,'ysepay_online_barcodepay_response');
    }
}