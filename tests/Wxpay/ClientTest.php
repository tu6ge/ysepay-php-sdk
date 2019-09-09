<?php
namespace YsepaySdk\Tests\Wxpay;

use YsepaySdk\Tests\TestCase;
use YsepaySdk\Wxpay\Client;

class ClientTest extends TestCase
{
    public function testCreateApp()
    {
        $this->make(Client::class)->createApp([
            'notify_url'    => 'test_notify_url',
            'out_trade_no'  => 'bar',
            'subject'       => 'foo',
            'total_amount'  => 12.33,
            'currency'      => 'test_currency',
            'timeout_express'   => 'test_timeout_express',
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.sdkpay',
                'notify_url'    => 'test_notify_url',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no'  => 'bar',
                    'shopdate'      => date('Ymd'),
                    'subject'       => 'foo',
                    'total_amount'  => 12.33,
                    'currency'      => 'test_currency',
                    'seller_id'     => $this->config['seller_id'],
                    'seller_name'   => $this->config['seller_name'],
                    'timeout_express'   => 'test_timeout_express',
                    'business_code' => $this->config['business_code'],
                    'bank_type'     => 1902000,
                    'appid'         => $this->config['appid'],
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }

    public function testCreateOfficialAccount()
    {
        $this->make(Client::class)->createOfficialAccount([
            'notify_url'    => 'test_notify_url',
            'out_trade_no'  => 'bar',
            'subject'       => 'foo',
            'total_amount'  => 12.33,
            'currency'      => 'test_currency',
            'timeout_express'   => 'test_timeout_express',
            "openid"        => 'test_openid',
        ])->assertUri($this->urls['order_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.online.weixin.pay',
                'notify_url'    => 'test_notify_url',
                'biz_content'   => \GuzzleHttp\json_encode([
                    'out_trade_no'  => 'bar',
                    'shopdate'      => date('Ymd'),
                    'subject'       => 'foo',
                    'total_amount'  => 12.33,
                    'currency'      => 'test_currency',
                    'seller_id'     => $this->config['seller_id'],
                    'seller_name'   => $this->config['seller_name'],
                    'timeout_express'   => 'test_timeout_express',
                    'business_code' => $this->config['business_code'],
                    "sub_openid"    => 'test_openid',
                    'appid'         => $this->config['appid'],
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }

    public function testGetToken()
    {
        $this->make(Client::class)->getToken([
            'notify_url'    => 'test_notify_url',
        ])->assertUri($this->urls['register_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.merchant.register.token.get',
                'notify_url'    => 'test_notify_url',
                'biz_content'   => \GuzzleHttp\json_encode(new \stdClass(), JSON_UNESCAPED_UNICODE),
            ]);
    }

//    public function testUploadPicture()
//    {
//
//    }

    public function testRegister()
    {
        $this->make(Client::class)->register([
            "merchant_no"       => 'test_merchant_no',
            "cust_type"         => 'test_cust_type',
            "token"             => 'test_token',
            "another_name"      => 'test_another_name',            //姓名
            "cust_name"         => 'test_cust_name',          //机构名称
            "industry"          => '50',            //行业
            "province"          => 'test_province',
            "city"              => 'test_city',
            "company_addr"      => 'test_company_addr',
            "legal_name"        => 'test_legal_name',    //企业法人名字,小微商户可空
            "legal_tel"         => 'test_legal_tel',      //企业法人手机号
            "legal_cert_no"     => '444',   //证件号。DES加密
            "settle_type"       => 'test_settle_type',   //1银行卡账户，0平台内账户
            "bank_account_no"   => 'test_bank_account_no',
            "bank_account_name" => 'test_bank_account_name',
            "bank_account_type" => 'test_bank_account_type',      // personal 对私账户   corporate 对公账户
            "bank_card_type"    => 'test_bank_card_type',    //debit=借记卡 credit=贷记卡 unit=单位结算卡
            "bank_name"         => 'test_bank_name', // 开户行名称
            "bank_type"         => 'test_bank_type',                                                      //银行名称
            "bank_province"     => 'test_bank_province',
            "bank_city"         => 'test_bank_city',
            "cert_no"           => '444',    //开户人证件号,DES加密
            "bank_telephone_no" => 'test_bank_telephone_no',                          //银行预留手机号
        ])->assertUri($this->urls['register_url'])
            ->assertPostFormParams([
                'method'    => 'ysepay.merchant.register.accept',
                'biz_content'   => \GuzzleHttp\json_encode([
                    "merchant_no"       => 'test_merchant_no',
                    "cust_type"         => 'test_cust_type',
                    "token"             => 'test_token',
                    "another_name"      => 'test_another_name',            //姓名
                    "cust_name"         => 'test_cust_name',          //机构名称
                    "industry"          => '50',            //行业
                    "province"          => 'test_province',
                    "city"              => 'test_city',
                    "company_addr"      => 'test_company_addr',
                    "legal_name"        => 'test_legal_name',    //企业法人名字,小微商户可空
                    "legal_tel"         => 'test_legal_tel',      //企业法人手机号
                    "legal_cert_type"   => '00',
                    "legal_cert_no"     => '/7XE1azJTkA=',   //证件号。DES加密
                    "settle_type"       => 'test_settle_type',   //1银行卡账户，0平台内账户
                    "bank_account_no"   => 'test_bank_account_no',
                    "bank_account_name" => 'test_bank_account_name',
                    "bank_account_type" => 'test_bank_account_type',      // personal 对私账户   corporate 对公账户
                    "bank_card_type"    => 'test_bank_card_type',    //debit=借记卡 credit=贷记卡 unit=单位结算卡
                    "bank_name"         => 'test_bank_name', // 开户行名称
                    "bank_type"         => 'test_bank_type',                                                      //银行名称
                    "bank_province"     => 'test_bank_province',
                    "bank_city"         => 'test_bank_city',
                    "cert_type"         => '00',    //目前只支持00，00是身份证
                    "cert_no"           => '/7XE1azJTkA=',    //开户人证件号,DES加密
                    "bank_telephone_no" => 'test_bank_telephone_no',                          //银行预留手机号
                ], JSON_UNESCAPED_UNICODE),
            ]);
    }
}