<?php
namespace YsepaySdk\Tests;

use PHPUnit\Framework\TestCase;
use YsepaySdk\Client;

class ApplicationTest extends TestCase
{
    public function testRegister()
    {
        $app = new Client([
            //'response_type' => 'raw',
            'business_gate_cert' => __DIR__.'/certs/business.txt',
            'private_cert'  => __DIR__.'/certs/private.txt',
            'pfxpassword'   => 'sssss',
            'business_code' => 'test_business_code',
            'partner_id'    => 'test_partner_id',
            'seller_id'     => 'test_seller_id',
            'seller_name'   => 'test_seller_name',
            'app_id'        => 'test_appid',
            'log'   => [
                'path' => __DIR__
            ]
        ]);
        $providers = [
            'basic'     => \YsepaySdk\BasicService\Client::class,
            'config'    => \YsepaySdk\Kernel\Config::class,
            'http_client'   => \GuzzleHttp\Client::class,
            'logger'    => \Monolog\Logger::class,
            'order'     => \YsepaySdk\Order\Client::class,
            'qrcode'    => \YsepaySdk\Qrcode\Client::class,
            'alipay'    => \YsepaySdk\Alipay\Client::class,
            'wxpay'     => \YsepaySdk\Wxpay\Client::class,
            'df'        => \YsepaySdk\Df\Client::class,
            'division'  => \YsepaySdk\Division\Client::class,
            'ydt'       => \YsepaySdk\Ydt\Client::class,
            'auth'      => \YsepaySdk\Authenticate\Client::class,
        ];

        $this->assertCount(count($providers), $app->getProviders());
        foreach ($providers as $key=>$val)
        {
            $this->assertInstanceOf($val, $app->{$key});
        }
    }
}