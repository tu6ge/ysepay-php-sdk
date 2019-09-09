<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Df;

use YsepaySdk\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 单笔代付加急接口
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/9 上午12:09
     */
    public function createQuick($data){
        $myParams = [];
        $myParams['method'] = 'ysepay.df.single.quick.accept';
        $myParams['notify_url'] = $data['notify_url'];
        $myParams['extra_common_param'] = $data['extra_common_param'] ?? '';

        $biz_content_arr = array(
            "out_trade_no"      => $data['out_trade_no'],
            "business_code"     => $this->app->config->business_code,
            "currency"          => $data['currency'] ?? "CNY",
            "total_amount"      => $data['total_amount'],
            "subject"           => $data['subject'],
//            "bank_name" => "工商银行深圳支行",
//            "bank_city" => "深圳市",
//            "bank_account_no" => "9000101782233747700",
//            "bank_account_name" => "工行",
//            "bank_account_type" => "personal",
//            "bank_card_type" => "debit"
            "bank_name"         => $data['bank_name'],
            "bank_city"         => $data['bank_city'],
            "bank_account_no"   => $data['bank_account_no'],
            "bank_account_type" => $data['bank_account_type'] ?? 'personal',
            "bank_card_type"    => $data['bank_card_type'] ?? 'debit',
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串

        return $this->app->basic->httpPost( $this->api_urls['df_url'], $myParams, 'ysepay_df_single_quick_accept_response');
    }

    /**
     * 批量代付
     * @param $data
     * [detail_data] = [[
     *  "out_trade_no" => "$order",
        "amount" => "1.5",
        "subject" => "订单说明",
        "bank_name" => "中国银行深圳民治支行",
        "bank_province" => "广东省",
        "bank_city" => "深圳市",
        "bank_account_no" => "1111111111111111",
        "bank_account_name" => "李四",
        "bank_account_type" => "personal",
        "bank_card_type" => "credit",
     * ]...]
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午12:03
     */
    public function createBatch($data)
    {
        $myParams = [];
        $myParams['method']         = 'ysepay.df.batch.normal.accept';
        $myParams['notify_url']     = $data['notify_url'];
        $biz_content = array(
            "out_batch_no"          => $data['out_batch_no'],
            "shopdate"              => date('Ymd'),
            "total_num"             => $data['total_num'],
            "total_amount"          => $data['total_amount'],
            "business_code"         => $this->app->config->business_code,
            "currency"              => $data['currency'] ?? 'CNY',
            "detail_data"           => $data['detail_data'],
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, 320);//构造字符串

        return $this->app->basic->httpPost( $this->api_urls['df_batch_url'], $myParams, 'ysepay_df_batch_normal_accept_response');
    }

    /**
     * 代付查询
     * @param $data
     * @author tu6ge
     * @date 2019/8/11 上午12:05
     */
    public function batchQuery($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.df.batch.detail.query';
        $biz_content = array(
            "out_batch_no"  => $data['out_batch_no'],
            "shopdate"      => $data['shopdate'],
            "out_trade_no"  => $data['out_trade_no'],
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, 320);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['df_query_url'], $myParams, 'ysepay_df_batch_detail_query_response');
    }
}