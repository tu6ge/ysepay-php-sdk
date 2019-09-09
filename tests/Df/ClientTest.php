<?php
namespace YsepaySdk\Tests\Df;

use YsepaySdk\Df\Client;
use YsepaySdk\Tests\TestCase;

class ClientTest extends TestCase
{
    public function testCreateQuick()
    {
        $this->make(Client::class)->createQuick([
            'notify_url'        => 'test_notify_url',
            'extra_common_param'=> 'test_extra_common_param',
            'out_trade_no'      => 'test_out_trade_no',
            'currency'          => 'test_currency',
            'total_amount'      => 'test_total_amount',
            'subject'           => 'test_subject',
            'bank_name'         => 'test_bank_name',
            'bank_city'         => 'test_bank_city',
            'bank_account_no'   => 'test_bank_account_no',
            'bank_account_type' => 'test_bank_account_type',
            'bank_card_type'    => 'test_bank_card_type',
        ])->assertPostUri($this->urls['df_url'])
            ->assertPostFormParams([
            'method'            => 'ysepay.df.single.quick.accept',
            'notify_url'        => 'test_notify_url',
            'extra_common_param'=> 'test_extra_common_param',
            'biz_content'   => \GuzzleHttp\json_encode([
                'out_trade_no'      => 'test_out_trade_no',
                'business_code'     => $this->config['business_code'],
                'currency'          => 'test_currency',
                'total_amount'      => 'test_total_amount',
                'subject'           => 'test_subject',
                'bank_name'         => 'test_bank_name',
                'bank_city'         => 'test_bank_city',
                'bank_account_no'   => 'test_bank_account_no',
                'bank_account_type' => 'test_bank_account_type',
                'bank_card_type'    => 'test_bank_card_type',
            ], JSON_UNESCAPED_UNICODE),
        ]);
    }
    public function testCreateBatch()
    {
        $this->make(Client::class)->createBatch([
            'notify_url'        => 'test_notify_url',
            'out_batch_no'      => 'test_out_batch_no',
            'total_num'         => 'test_total_num',
            'total_amount'      => 'test_total_amount',
            'detail_data'       => 'test_detail_data',
            'currency'          => 'test_currency',
        ])->assertPostUri($this->urls['df_batch_url'])
            ->assertPostFormParams([
            'method'            => 'ysepay.df.batch.normal.accept',
            'notify_url'        => 'test_notify_url',
            'biz_content'   => \GuzzleHttp\json_encode([
                'out_batch_no'      => 'test_out_batch_no',
                'shopdate'          => date('Ymd'),
                'total_num'         => 'test_total_num',
                'total_amount'      => 'test_total_amount',
                'business_code'     => $this->config['business_code'],
                'currency'          => 'test_currency',
                'detail_data'       => 'test_detail_data',
            ], 320),
        ]);
    }
    public function testBatchQuery()
    {
        $this->make(Client::class)->batchQuery([
            "out_batch_no"  => 'test_out_batch_no',
            "shopdate"      => 'test_shopdate',
            "out_trade_no"  => 'test_out_trade_no',
        ])->assertPostUri($this->urls['df_query_url'])
            ->assertPostFormParams([
            'method'            => 'ysepay.df.batch.detail.query',
            'biz_content'   => \GuzzleHttp\json_encode([
                "out_batch_no"  => 'test_out_batch_no',
                "shopdate"      => 'test_shopdate',
                "out_trade_no"  => 'test_out_trade_no',
            ], 320),
        ]);
    }
}