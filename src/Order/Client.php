<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Order;

use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

class Client extends BaseClient
{



    /**
     * 余额查询
     * @author tu6ge
     * @date 2019-08-06 9:59
     */
    public function getBalance($user)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.user.account.get';

        $biz_content_arr = array(
            "user_code" => $user['user_code'],
            "user_name" => $user['user_name']
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_online_user_account_get_response');
    }

    /**
     * 查询订单
     * @param $order
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019-08-06 10:27
     */
    public function getOrder($order)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.trade.query';

        $biz_content_arr = array(
            "out_trade_no"  => $order['out_trade_no'],
            "trade_no"      => $order['trade_no']
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_online_trade_query_response');
    }

    /**
     * 同步响应
     * @param array $data
     * @return array|bool
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/13 下午9:04
     */
    public function respond(array $data=[])
    {
        if(empty($data)){
            $data = $_POST;
        }
        if(empty($data)){
            throw new YsepayException('POST data is empty');
        }

        //验签
        $sign = $data['sign'];
        unset($data['sign']);
        $signStr = $this->app->basic->signStr($data, true);
        if($this->app->basic->signCheck($sign, $signStr) == false){
            return false;
        }
        return true;
    }

    /**
     * 异步响应
     * @param array $data
     * @return array|bool
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/13 下午9:04
     */
    public function respondNotify(array $data=[])
    {
        if(empty($data)){
            $data = $_POST;
        }
        if(empty($data)){
            throw new YsepayException('POST data is empty');
        }
        $this->app->logger->info('notify data:'.\GuzzleHttp\json_decode($data));
        //验签
        $sign = $data['sign'];
        unset($data['sign']);
        $signStr = $this->app->basic->signStr($data, true);
        if($this->app->basic->signCheck($sign, $signStr) == false){
            return false;
        }
        return true;
    }

    /**
     * 退款接口
     * @param $info
     * @return mixed
     * @author tu6ge
     * @date 2019-08-06 11:32
     */
    public function createRefund($info)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.trade.refund';

        $biz_content_arr = array(
            "out_trade_no"      => $info['out_trade_no'],
            "trade_no"          => $info['trade_no'],
            "refund_amount"     => $info['refund_amount'],
            "refund_reason"     => $info['refund_reason'],
            "out_request_no"    => $info['out_request_no']
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_online_trade_refund_response');
    }

    /**
     * 查看退款记录
     * @param $info
     * @return mixed
     * @author tu6ge
     * @date 2019-08-06 11:35
     */
    public function getRefund($info)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.trade.refund.query';

        $biz_content_arr = array(
            "out_trade_no"      => $info['out_trade_no'],
            "trade_no"          => $info['trade_no'],
            "out_request_no"    => $info['out_request_no']
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_online_trade_refund_query_response');
    }

    /**
     * 对账单下载
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午1:05
     */
    public function billDownload($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.bill.downloadurl.get';

        $biz_content_arr = array(
            "account_date"      => $data['account_date'],
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_online_bill_downloadurl_get_response');

    }
}