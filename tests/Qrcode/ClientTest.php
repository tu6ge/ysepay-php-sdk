<?php
namespace YsepaySdk\Tests\Qrcode;

use YsepaySdk\Qrcode\Client;
use YsepaySdk\Tests\TestCase;

class ClientTest extends TestCase
{
    public function testPay()
    {
        $this->make(Client::class)->pay([
            'return_url'    => 'test_return_url',
            'notify_url'    => 'test_notify_url',
            'out_trade_no'  => 'test_out_trade_no',
            'subject'       => 'test_subject',
            'total_amount'  => 'test_total_amount',
            'timeout_express'   => 'test_timeout_express',
            'bank_type'     => '123',
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.qrcodepay',
                'return_url'    => 'test_return_url',
                'notify_url'    => 'test_notify_url',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no'  => 'test_out_trade_no',
                    'shopdate'      => date('Ymd'),
                    'subject'       => 'test_subject',
                    'total_amount'  => 'test_total_amount',
                    'seller_id'     => $this->config['seller_id'],
                    'seller_name'   => $this->config['seller_name'],
                    'timeout_express'   => 'test_timeout_express',
                    'business_code' => $this->config['business_code'],
                    'bank_type'     => '123',
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }

    public function testBarcodePay()
    {
        $this->make(Client::class)->barcodePay([
            'notify_url'    => 'test_notify_url',
            'out_trade_no'  => 'test_out_trade_no',
            'subject'       => 'test_subject',
            'total_amount'  => 'test_total_amount',
            'timeout_express'   => 'test_timeout_express',
            'bank_type'     => 1903000,
            'auth_code'     => 'test_auth_code',
            "scene" => "bar_code"
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.barcodepay',
                'notify_url'    => 'test_notify_url',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no'  => 'test_out_trade_no',
                    'shopdate'      => date('Ymd'),
                    'subject'       => 'test_subject',
                    'total_amount'  => 'test_total_amount',
                    'seller_id'     => $this->config['seller_id'],
                    'seller_name'   => $this->config['seller_name'],
                    'timeout_express'   => 'test_timeout_express',
                    'business_code' => $this->config['business_code'],
                    'bank_type'     => 1903000,
                    'auth_code'     => 'test_auth_code',
                    "scene" => "bar_code"
                ], JSON_UNESCAPED_UNICODE),
            ]);

        $this->make(Client::class)->barcodePay([
            'notify_url'    => 'test_notify_url',
            'out_trade_no'  => 'test_out_trade_no',
            'subject'       => 'test_subject',
            'total_amount'  => 'test_total_amount',
            'timeout_express'   => 'test_timeout_express',
            'bank_type'     => 9001002,
            'auth_code'     => 'test_auth_code',
            "device_info" => "test_device_info"
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.barcodepay',
                'notify_url'    => 'test_notify_url',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no'  => 'test_out_trade_no',
                    'shopdate'      => date('Ymd'),
                    'subject'       => 'test_subject',
                    'total_amount'  => 'test_total_amount',
                    'seller_id'     => $this->config['seller_id'],
                    'seller_name'   => $this->config['seller_name'],
                    'timeout_express'   => 'test_timeout_express',
                    'business_code' => $this->config['business_code'],
                    'bank_type'     => 9001002,
                    'auth_code'     => 'test_auth_code',
                    "device_info" => "test_device_info"
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }
}