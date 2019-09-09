<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Division;

use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

class Client extends BaseClient
{

    /**
     * 分帐
     * @param $data
     * [div_list] = [[
     *      "division_mer_usercode" => "zhaoh41",   //分账商户号
            "div_amount" => "10.0",     //分账金额
            "is_chargeFee" => "02"      //是否收取手续费（01：是，02否）
     *  ]...]
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/11 上午1:27
     */
    public function create($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.single.division.online.accept';

        $myParams['notify_url'] = $data['notify_url'];
        $biz_content_arr = array(
            "out_batch_no"      => $data['out_batch_no'],
            "out_trade_no"      => $data['out_trade_no'],     //原订单号
            "payee_usercode"    => $this->app->config->seller_id,      //主商户号（原交易收款方）
            "total_amount"      => $data['total_amount'],
            "is_divistion"      => $data['is_divistion'] ==1  ? "01" : '02',     //原订单是否参与分账01：是，02否
            "is_again_division" => $data['is_again_division'] ? 'Y' : "N",     //是否重新分账Y：是，N：否
            "division_mode"     => $data['division_mode'] ==1 ? '01' : '02',    //分账模式01 ：比例，02：金额
            "div_list"          => $data['div_list'],
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_common'], $myParams, 'ysepay_single_division_online_accept_response');
    }

    /**
     * 查询
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午2:36
     */
    public function query($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.single.division.online.query';

        $myParams['notify_url'] = $data['notify_url'];
        $biz_content_arr = array(
            "out_batch_no"      => $data['out_batch_no'],
            "out_trade_no"      => $data['out_trade_no'],     //原订单号
            "src_usercode"      => $this->app->config->seller_id,      //主商户号（原交易收款方）
            "sys_flag"          => "DD"
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_common'], $myParams, 'ysepay_single_division_online_query_response');
    }

    /**
     * 分账退款登记接口
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午2:41
     */
    public function refundEnrollment($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.trade.refund.split.register';

        $myParams['notify_url'] = $data['notify_url'];
        $myParams['tran_type']  = $data['tran_type'] ?? 1;     //交易类型，说明：1或者空：即时到账，2：担保交易
        $biz_content_arr = array(
            "shopdate"          => date('Ymd'),
            "out_trade_no"      => $data['out_trade_no'],     //原订单号
            "trade_no"          => $data['trade_no'],       //原订单号
            "refund_amount"     => $data['refund_amount'],  //金额
            "refund_reason"     => $data['refund_reason'],  //退款理由
            "out_request_no"    => $data['out_request_no'], //退款订单号
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost(
            $this->api_urls['division_refund_url'],
            $myParams,
            'ysepay_online_trade_refund_split_register_response'
        );
    }

    /**
     * 分帐退款
     * @param $data
     * [refund_split_info] = [[
     *      "refund_mer_id" => "zhaoh41",
            "refund_amount" => 9.25
     * ]...]
     * [order_div_list] = [[
     *      "division_mer_id" => "zhaoh41",     //原订单分账收款方商户号
            "division_ratio" => 0.45,       //原订单分账比例
            "is_charge_fee" => "02"     //是否收取手续费（01：是，02否）
     * ]...]
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/11 上午2:48
     */
    public function refund($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.trade.refund.split';

        $myParams['notify_url'] = $data['notify_url'];
        $biz_content_arr = array(
            "out_trade_no"      => $data['out_trade_no'],
            "shopdate"          => date('Ymd'),
            "trade_no"          => $data['trade_no'],
            "refund_amount"     => $data['refund_amount'],  //金额
            "refund_reason"     => $data['refund_reason'],  //退款理由
            "out_request_no"    => $data['out_request_no'], //退款订单号
            "is_division"       => $data['is_division'] == 1 ? '01' : '02',      //原交易是否参与分账（01或空代表是，02代表否）
            "ori_division_mode" => $data['ori_division_mode'] == 1 ? '01' : '02',    //原交易分账模式（01：比例，02：金额）
        );
        if($biz_content_arr['ori_division_mode'] == '01'){
            $biz_content_arr['order_div_list'] = $data['order_div_list'];
        }else{
            $biz_content_arr['refund_split_info'] = $data['refund_split_info'];
        }
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost(
            $this->api_urls['division_refund_url'],
            $myParams,
            'ysepay_online_trade_refund_split_response'
        );
    }
}