<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 15:09
 */
namespace YsepaySdk\Kernel;

use YsepaySdk\Client;

class BaseClient
{
    protected $app;
    protected $charset = 'UTF-8';
    protected $version = '3.0';
    protected $sign_type = 'RSA';
    protected static $bank_type_list = [
        'alipay'    => 1903000, //支付宝
        'wxpay'     => 1902000, //微信
        'qqmobile'  => 1904000, //手机QQ
        'unionpay'  => 9001002, //中国银联
        'suning'    => 1905000,
    ];
    protected static $bank_account_type_list = [
        'personal'  => '个人',
        'corporate' => '对公',
    ];
    protected static $bank_card_type_list = [
        'debit'     => '借记卡',
        'credit'    => '贷记卡',
        'unit'      => '单位结算卡',
    ];
    protected $api_urls = [];

    public function __construct(Client $app)
    {
        $this->app = $app;
        $this->api_urls = $this->getUrl();
    }

    public function check_require($data, $need_params)
    {
        foreach ($need_params as $val){
            if(!isset($data[$val]) || empty($data[$val])){
                throw new \InvalidArgumentException(sprintf('%s is require', $val));
            }
        }
    }

    public function publicParams()
    {
        return [
            'sign_type'         => $this->sign_type,
            'charset'           => $this->charset,
            'version'           => $this->version,
            'timestamp'         => date('Y-m-d H:i:s', time()),
            'partner_id'        => $this->app->config->partner_id,
        ];
    }

    public function getUrl()
    {
        $production = [
            'order_url'             => 'https://openapi.ysepay.com/gateway.do',
            'order_common'          => 'http://10.213.32.58:16000/openapi_dsf_gateway/gateway.do',
            'order_query_url'       => 'https://search.ysepay.com/gateway.do',
            'df_url'                => 'https://df.ysepay.com/gateway.do',
            'df_query_url'          => 'https://searchdf.ysepay.com/gateway.do',
            'df_batch_url'          => 'https://batchdf.ysepay.com/gateway.do',
            'ds_url'                => 'https://ds.ysepay.com/gateway.do',
            'ds_query_url'          => 'https://searchds.ysepay.com/gateway.do',
            'division_refund_url'   => 'http://10.213.32.58:12005/openapi_gateway/gateway.do',
            'register_url'          => 'http://10.213.32.58:10011/register_gateway/gateway.do',
            'upload_picture_url'    => 'http://10.213.32.58:13021/yspay-upload-service?method=upload',
            'ydt_url'               => 'ydt_url',
            'ydt_url_df'            => 'ydt_url_df',
        ];
        $development = [
            'order_url'             => 'https://mertest.ysepay.com/openapi_gateway/gateway.do',
            'order_common'          => 'http://10.213.32.58:16000/openapi_dsf_gateway/gateway.do', // 同上
            'order_query_url'       => 'https://mertest.ysepay.com/openapi_gateway/gateway.do',
            'df_url'                => 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do',
            'df_query_url'          => 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do',
            'df_batch_url'          => 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do', //todo 有待验证
            'ds_url'                => 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do',
            'ds_query_url'          => 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do',
            'division_refund_url'   => 'http://10.213.32.58:12005/openapi_gateway/gateway.do', //同上
            'register_url'          => 'http://10.213.32.58:10011/register_gateway/gateway.do',                      //同上
            'upload_picture_url'    => 'http://10.213.32.58:13021/yspay-upload-service?method=upload',    // 同上
            'ydt_url'               => 'ydt_url',
            'ydt_url_df'            => 'ydt_url_df',
        ];
        $urls = $production;
        if(isset($this->app->config->sandbox) && $this->app->config->sandbox ==true){
            $urls = $development;
        }
        return $urls;
    }
}