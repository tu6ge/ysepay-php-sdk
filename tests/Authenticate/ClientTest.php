<?php
namespace YsepaySdk\Tests\Authenticate;

use YsepaySdk\Authenticate\Client;
use YsepaySdk\Tests\TestCase;

class ClientTest extends TestCase
{
    public function testAuthenFour()
    {
        $this->make(Client::class)->authenFour([
            'out_trade_no'      => '111',
            'bank_account_name' => '222',
            'bank_account_no'   => '333',
            'id_card'           => '444',
        ])->assertPostUri($this->urls['order_url'])->assertPostFormParams([
            'method'    => 'ysepay.authenticate.four.key.element',
            'biz_content'   => json_encode([
                'out_trade_no'      => '111',
                'bank_account_name' => '222',
                'bank_account_no'   => '333',
                'id_card'           => '/7XE1azJTkA=',
            ], 320),
        ]);
    }

    public function testAuthenMobile()
    {
        $this->make(Client::class)->authenMobile([
            'out_trade_no'      => '111',
            'name'              => '222',
            'phone'             => '333',
            'id_card'           => '444',
        ])->assertPostUri($this->urls['order_url'])->assertPostFormParams([
            'method'    => 'ysepay.authenticate.mobile.operators.three.key.element',
            'biz_content'   => json_encode([
                'out_trade_no'      => '111',
                'name'              => '222',
                'phone'             => '333',
                'id_card'           => '/7XE1azJTkA=',
            ], 320),
        ]);
    }
    public function testAuthenTwo()
    {
        $this->make(Client::class)->authenTwo([
            'out_trade_no'      => '111',
            'name'              => '222',
            'id_card'           => '444',
        ])->assertPostUri($this->urls['order_url'])->assertPostFormParams([
            'method'    => 'ysepay.authenticate.id.card.img',
            'biz_content'   => json_encode([
                'out_trade_no'      => '111',
                'name'              => '222',
                'id_card'           => '/7XE1azJTkA=',
            ], 320),
        ]);
    }

    public function testSaveBas64Image()
    {
        $directory = __DIR__.'/../img/';
        $filename = 'new';
        $org_file = $directory.'demo.jpeg';
        $new_file = $directory.$filename.'.jpeg';
        $content = base64_encode(file_get_contents($org_file));
        $this->make(Client::class)->saveBas64Image($directory, $filename, $content);
        $this->assertFileEquals($org_file, $new_file);
        unlink($new_file);
    }
}