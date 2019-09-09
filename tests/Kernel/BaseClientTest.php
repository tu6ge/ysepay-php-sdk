<?php
namespace YsepaySdk\Tests\Kernel;

use YsepaySdk\Client;
use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Tests\TestCase;

class BaseClientTest extends TestCase
{

    public function testPublicParams()
    {
        $config =  [
            'sign_type'         => 'RSA',
            'charset'           => 'UTF-8',
            'version'           => '3.0',
            'timestamp'         => date('Y-m-d H:i:s', time()),
            'partner_id'        => $this->config['partner_id'],
        ];
        $this->assertSame($this->make(BaseClient::class)->publicParams(), $config);
    }

    public function testGetUrl()
    {
        $this->assertSame($this->make(BaseClient::class)->getUrl(), $this->urls);

        $config = $this->config;
        $config['sandbox'] = true;
        $app = new Client($config);
        $base = new BaseClient($app);
        $this->assertSame($base->getUrl(),[
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
        ]);
    }
}