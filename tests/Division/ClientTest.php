<?php
namespace YsepaySdk\Tests\Division;

use YsepaySdk\Division\Client;
use YsepaySdk\Tests\TestCase;

class ClientTest extends TestCase
{
    public function testCreate()
    {
        $this->make(Client::class)->create([
            'notify_url'        => 'test_notify_url',
            'out_batch_no'      => 'test_out_batch_no',
            'out_trade_no'      => 'test_out_trade_no',
            'total_amount'      => 'test_total_amount',
            'is_divistion'      => 1,
            'is_again_division' => true,
            'division_mode'     => 2,
            'div_list'          => [
                'key1'  => 'val1',
                'key2'  => 'val2',
            ],
        ])->assertPostUri($this->urls['order_common'])
            ->assertPostFormParams([
                'method'    => 'ysepay.single.division.online.accept',
                'notify_url'        => 'test_notify_url',
                'biz_content'       => \GuzzleHttp\json_encode([
                    'out_batch_no'      => 'test_out_batch_no',
                    'out_trade_no'      => 'test_out_trade_no',
                    'payee_usercode'    => $this->config['seller_id'],
                    'total_amount'      => 'test_total_amount',
                    'is_divistion'      => '01',
                    'is_again_division' => 'Y',
                    'division_mode'     => '02',
                    'div_list'          => [
                        'key1'  => 'val1',
                        'key2'  => 'val2',
                    ],
                ], JSON_UNESCAPED_UNICODE)
            ]);
    }

    public function testQuery()
    {
        $this->make(Client::class)->query([
            'out_batch_no'      => 'test_out_batch_no',
            'out_trade_no'      => 'test_out_trade_no',
            'notify_url'        => 'test_notify_url',
        ])->assertPostUri($this->urls['order_common'])
            ->assertPostFormParams([
                'method'    => 'ysepay.single.division.online.query',
                'notify_url'        => 'test_notify_url',
                'biz_content'       => \GuzzleHttp\json_encode([
                    'out_batch_no'      => 'test_out_batch_no',
                    'out_trade_no'      => 'test_out_trade_no',
                    'src_usercode'      => $this->config['seller_id'],
                    "sys_flag"          => "DD"
                ], JSON_UNESCAPED_UNICODE)
            ]);
    }
    public function testRefundEnrollment()
    {
        $this->make(Client::class)->refundEnrollment([
            'out_trade_no'      => 'test_out_trade_no',
            'trade_no'          => 'test_trade_no',
            'notify_url'        => 'test_notify_url',
            'refund_amount'     => 'test_refund_amount',
            'refund_reason'     => 'test_refund_reason',
            'out_request_no'    => 'test_out_request_no',
            'tran_type'         => 2,
        ])->assertPostUri($this->urls['division_refund_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.trade.refund.split.register',
                'notify_url'        => 'test_notify_url',
                'tran_type'         => 2,
                'biz_content'       => \GuzzleHttp\json_encode([
                    'shopdate'      => date('Ymd'),
                    'out_trade_no'      => 'test_out_trade_no',
                    'trade_no'          => 'test_trade_no',
                    'refund_amount'     => 'test_refund_amount',
                    'refund_reason'     => 'test_refund_reason',
                    'out_request_no'    => 'test_out_request_no',
                ], JSON_UNESCAPED_UNICODE)
            ]);
    }

    public function testRefund()
    {
        $this->make(Client::class)->refund([
            'out_trade_no'      => 'test_out_trade_no',
            'trade_no'          => 'test_trade_no',
            'notify_url'        => 'test_notify_url',
            'refund_amount'     => 'test_refund_amount',
            'refund_reason'     => 'test_refund_reason',
            'out_request_no'    => 'test_out_request_no',
            'is_division'         => 1,
            'ori_division_mode'     => 1,
            'order_div_list'    => [
                'key1'  => 'val1',
                'key2'  => 'val2',
            ]
        ])->assertPostUri($this->urls['division_refund_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.trade.refund.split',
                'notify_url'        => 'test_notify_url',
                'biz_content'       => \GuzzleHttp\json_encode([
                    'out_trade_no'      => 'test_out_trade_no',
                    'shopdate'      => date('Ymd'),

                    'trade_no'          => 'test_trade_no',
                    'refund_amount'     => 'test_refund_amount',
                    'refund_reason'     => 'test_refund_reason',
                    'out_request_no'    => 'test_out_request_no',
                    'is_division'       => '01',
                    'ori_division_mode' => '01',
                    'order_div_list'    => [
                        'key1'  => 'val1',
                        'key2'  => 'val2',
                    ]
                ], JSON_UNESCAPED_UNICODE)
            ]);

        $this->make(Client::class)->refund([
            'out_trade_no'      => 'test_out_trade_no',
            'trade_no'          => 'test_trade_no',
            'notify_url'        => 'test_notify_url',
            'refund_amount'     => 'test_refund_amount',
            'refund_reason'     => 'test_refund_reason',
            'out_request_no'    => 'test_out_request_no',
            'is_division'         => 1,
            'ori_division_mode'     => 2,
            'refund_split_info'    => [
                'key1'  => 'val1',
                'key2'  => 'val2',
            ]
        ])->assertPostUri($this->urls['division_refund_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.trade.refund.split',
                'notify_url'        => 'test_notify_url',
                'biz_content'       => \GuzzleHttp\json_encode([
                    'out_trade_no'      => 'test_out_trade_no',
                    'shopdate'      => date('Ymd'),

                    'trade_no'          => 'test_trade_no',
                    'refund_amount'     => 'test_refund_amount',
                    'refund_reason'     => 'test_refund_reason',
                    'out_request_no'    => 'test_out_request_no',
                    'is_division'       => '01',
                    'ori_division_mode' => '02',
                    'refund_split_info'    => [
                        'key1'  => 'val1',
                        'key2'  => 'val2',
                    ]
                ], JSON_UNESCAPED_UNICODE)
            ]);
    }
}