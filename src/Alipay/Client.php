<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Alipay;

use YsepaySdk\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 生成支付请求数组
     * @param array $order
     * @param string $return_type
     * @return array|string
     * @author tu6ge
     * @date 2019-08-06 9:37
     */
    public function create(array $order, $return_type='array')
    {
        $need_field = ['out_trade_no', 'subject', 'total_amount'];
        $this->check_require($order, $need_field);

        $myParams = [];
        $myParams['business_code']  = $this->app->config->business_code;
        $myParams['partner_id']     = $this->app->config->partner_id;
        $myParams['seller_id']      = $this->app->config->seller_id;
        $myParams['seller_name']    = $this->app->config->seller_name;
        $myParams['notify_url']     = $order['notify_url'] ?? $this->app->config->notify_url;
        $myParams['return_url']     = $order['return_url'] ?? $this->app->config->return_url;
        $this->check_require($myParams, ['notify_url', 'return_url']);

        $myParams['method']         = 'ysepay.online.wap.directpay.createbyuser';
        $myParams['out_trade_no']   = $order['out_trade_no'];
        $myParams['subject']        = $order['subject'];
        $myParams['timeout_express'] = $order['timeout_express'] ?? '1d'; // 1d表示必须 24h 内付款
        $myParams['total_amount']   = $order['total_amount'];

        $myParams['pay_mode']       = $order['pay_mode'] ?? 'native';
        $myParams['bank_type']      = self::$bank_type_list['alipay'];

        $myParams = $this->app->basic->buildSign($myParams);
        $result = [
            'action'    => $this->api_urls['order_url'],
            'method'    => 'POST',
            'param'     => $myParams,
        ];
        if($return_type=='html'){
            return $this->createFormHtml($result);
        }
        return $result;
    }

    /**
     * 生成表单
     * @param $data
     * @return string
     * @author tu6ge
     * @date 2019-08-06 9:41
     */
    public function createFormHtml($data)
    {
        $def_url = "<form method='" . $data['method'] . "' action='" . $data['action'] . "' target='_blank'>";
        foreach($data['param'] as $key=>$param) {
            $def_url .= "<input type = 'hidden' name='" . $key . "' value='" . $param . "' />";
        }
        $def_url .= "<button type='submit' >支付</button>";
        $def_url .= "</form>";
        return $def_url;
    }
}