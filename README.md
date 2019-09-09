# ysepay-php-sdk
银盛支付的php SDK

## Requirement
1. PHP >= 7.0
2. **[Composer](https://getcomposer.org)**
3. openssl 拓展

## Install

``composer require tu6ge/ysepay-sdk``

## Usage

```php
require_once "vendor/autoload.php";
$config = [
              'business_gate_cert'    => 'xxx',    //公钥路径
              'private_cert'          => 'xxx',           //私钥路径
              'partner_id'            => '',
              'seller_id'             => '',
              'seller_name'           => '',
          
              'pfxpassword'           => '',
              'merchant_code'         => '',
          
              'business_code'         => '',
              'log'   => [
                  'path' => __DIR__.'/info.log',
                  'name'  => 'ysepay',
              ]
$app = \YsepaySdk\Client($config);

//创建一个支付宝app支付的订单
$html = $app->alipay->create([
   'notify_url'            => 'http://www.xxx.com/return.php',
   'return_url'            => 'http://www.xxx.com/return.php',
   'out_trade_no'          => time(),
   'subject'               => 'test composer',
   'total_amount'          => 0.01,
], 'html');
echo $html;

//查询账户余额
$rs = $app->order->getBalance([
    'user_code'     => 'xxx',
    'user_name'     => 'xxx',
]);
print_r($rs);

//查询订单
$rs = $app->order->getOrder([
    "out_trade_no"  => '5998636703390937407',
    "trade_no"      => '01O190703750273951'
]);
print_r($rs);

```

## License

MIT
