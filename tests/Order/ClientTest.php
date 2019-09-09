<?php
namespace YsepaySdk\Tests;

use YsepaySdk\Order\Client;

class ClientTest extends TestCase
{
    public function testGetBalance()
    {
        $this->make(Client::class)->getBalance([
            'user_code' => 'bar',
            'user_name' => 'foo',
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.user.account.get',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'user_code' => 'bar',
                    'user_name' => 'foo',
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }
    public function testGetOrder()
    {
        $this->make(Client::class)->getOrder([
            'out_trade_no' => 'bar',
            'trade_no' => 'foo',
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.trade.query',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no' => 'bar',
                    'trade_no' => 'foo',
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }

    public function testCreateRefund()
    {
        $this->make(Client::class)->createRefund([
            'out_trade_no'      => 'bar',
            'trade_no'          => 'foo',
            'refund_amount'     => 'test',
            'refund_reason'     => 'demo',
            'out_request_no'    => 'demo2'
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.trade.refund',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no' => 'bar',
                    'trade_no' => 'foo',
                    'refund_amount'     => 'test',
                    'refund_reason'     => 'demo',
                    'out_request_no'    => 'demo2'
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }
    public function testGetRefund()
    {
        $this->make(Client::class)->getRefund([
            'out_trade_no'      => 'bar',
            'trade_no'          => 'foo',
            'out_request_no'    => 'demo2'
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.trade.refund.query',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no'      => 'bar',
                    'trade_no'          => 'foo',
                    'out_request_no'    => 'demo2'
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }
    public function testBillDownload()
    {
        $this->make(Client::class)->billDownload([
            'account_date'      => 'bar',
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.bill.downloadurl.get',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'account_date'      => 'bar',
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }
}