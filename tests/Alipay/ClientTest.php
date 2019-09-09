<?php
namespace YsepaySdk\Tests\Alipay;

use YsepaySdk\Alipay\Client;
use YsepaySdk\BasicService\ClientInterface as BasicInterface;
use YsepaySdk\Tests\TestCase;
use Mockery;

class ClientTest extends TestCase
{
    public function testCreate()
    {
        $app = new \YsepaySdk\Client($this->config);

        $basic = new Basic();
        $app->basic = Mockery::mock(BasicInterface::class,function($mock) use($basic){
            $mock->shouldReceive('buildSign')->withArgs($basic->set_array());
        });

        $client = new Client($app);
        $res = $client->create([
            'out_trade_no'  => '111',
            'subject'       => '222',
            'total_amount'  => '333',
            'notify_url'    => '444',
            'return_url'    => '555',
            'timeout_express'   => '666',
            'pay_mode'      => '777',
        ]);
        $res['param'] = $basic->get_array();
        $this->assertSame($res, [
            'action'        => $this->urls['order_url'],
            'method'        => 'POST',
            'param'        => [
                'business_code' => $this->config['business_code'],
                'partner_id'    => $this->config['partner_id'],
                'seller_id'     => $this->config['seller_id'],
                'seller_name'   => $this->config['seller_name'],
                'notify_url'    => '444',
                'return_url'    => '555',
                'method'        => 'ysepay.online.wap.directpay.createbyuser',
                'out_trade_no'  => '111',
                'subject'       => '222',
                'timeout_express'   => '666',
                'total_amount'  => '333',
                'pay_mode'      => '777',
                'bank_type'     => 1903000,
                'sign'          => '123'
            ],
        ]);
    }

    public function testCreateFormHtml()
    {
        $res = $this->make(Client::class)->createFormHtml([
            'method'    => 'test_m',
            'action'    => 'test_a',
            'param'     => [
                'aaa'   => 111,
                'bbb'   =>'bbb',
            ]
        ]);
        $html = <<<EOT
<form method='test_m' action='test_a' target='_blank'>
<input type = 'hidden' name='aaa' value='111' />
<input type = 'hidden' name='bbb' value='bbb' />
<button type='submit' >支付</button>
</form>
EOT;
        $html = str_replace("\n",'',$html);

        $this->assertEquals($res, $html);
    }
}