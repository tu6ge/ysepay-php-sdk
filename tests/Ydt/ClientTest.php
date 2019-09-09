<?php
namespace YsepaySdk\Tests\Ydt;

use YsepaySdk\Tests\TestCase;
use YsepaySdk\Ydt\Client;

class ClientTest extends TestCase
{
    public function testBind()
    {
        $this->make(Client::class)->bind([
            'notify_url'    => 'test_notify_url',
            "quest_no"       => 'test_quest_no',
            "userid"         => 'test_userid',
            "user_name"      => 'test_user_name',
            "idcard_no"      => 'test_idcard_no',
            "bank_name"      => 'test_bank_name',
            "card_no"        => 'test_card_no',
            "mobile"         => '13911112222',
            "bank_province"  => 'test_bank_province',
            "bank_city"      => 'test_bank_city',
            "bank_type"      => 'test_bank_type',
        ])->assertUri($this->urls['ydt_url'])
            ->assertPostFormParams([
                'interface_name' => 'pay.binding.single.acept',
                'merchant_code'  => $this->config['merchant_code'],
                'notify_url'    => 'test_notify_url',
                "quest_no"       => 'test_quest_no',
                "userid"         => 'test_userid',
                "user_name"      => 'test_user_name',
                "idcard_no"      => 'test_idcard_no',
                "bank_name"      => 'test_bank_name',
                "card_type"      => "debit", //todo
                "card_no"        => 'test_card_no',
                "mobile"         => '13911112222',
                "subject"        => "personal", //todo
                "bank_province"  => 'test_bank_province',
                "bank_city"      => 'test_bank_city',
                "bank_type"      => 'test_bank_type',//"1021000"; // todo
            ]);
    }

    public function testCreateOrder()
    {
        $this->make(Client::class)->createOrder([
            'notify_url'        => 'test_notify_url',
            "quest_no"          => 'test_quest_no',
            'bind_card_id'      => 'test_bind_card_id',
            "userid"            => 'test_userid',
            "order_amount"      => 12112,
            "subject"           => 'test_subject',
            "principal_interest"=> 'test_principal_interest',
            "principal"         => "test_principal",
            "agreement_no"      => 'test_agreement_no',
        ])->assertUri($this->urls['ydt_url_df'])
            ->assertPostFormParams([
                'interface_name'    => 'pay.remittransfer.single.accept',
                'merchant_code'     => $this->config['merchant_code'],
                'notify_url'        => 'test_notify_url',
                "quest_no"          => 'test_quest_no',
                'bind_card_id'      => 'test_bind_card_id',
                "userid"            => 'test_userid',
                "order_amount"      => 12112,
                "subject"           => 'test_subject',
                "principal_interest"=> 'test_principal_interest',
                "principal"         => "test_principal",
                "Periods"           => 1,
                "agreement_no"      => 'test_agreement_no',
            ]);
    }
}