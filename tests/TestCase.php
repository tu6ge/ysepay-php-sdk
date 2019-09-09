<?php
namespace YsepaySdk\Tests;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;
use YsepaySdk\Client;
use Mockery;

class TestCase extends BaseTestCase
{
    protected $config = [];
    protected $urls = [];
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->config = [
            'response_type' => 'raw',
            'business_gate_cert' => __DIR__.'/certs/business.txt',
            'private_cert'  => __DIR__.'/certs/private.txt',
            'pfxpassword'   => 'sssss',
            'business_code' => 'test_business_code',
            'partner_id'    => 'test_partner_id',
            'seller_id'     => 'test_seller_id',
            'seller_name'   => 'test_seller_name',
            'appid'         => 'test_appid',
            'merchant_code' => 'test_merchant_code',
            'log'   => [
                'path' => __DIR__
            ]
        ];
        $this->urls = [
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
    }

    public function make($client)
    {
        $app = new Client($this->config);
        $response = new TestResponse(200, [], '{"mock": "test"}');

        $app->http_client = Mockery::mock(ClientInterface::class,function($mock)use($response){
            $mock->shouldReceive('request')->withArgs($response->setExpectedArguments())->andReturn($response);
        });
        return new $client($app);
    }

}