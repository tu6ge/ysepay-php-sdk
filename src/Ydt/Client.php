<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Ydt;

use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

class Client extends BaseClient
{
    /**
     * 绑卡
     * @param $data
     * @return \YsepaySdk\BasicService\ResponseInterface
     * @author tu6ge
     * @date 2019/8/10 下午11:44
     */
    public function bind($data)
    {
        $myParams = [];


        $myParams['interface_name'] = 'pay.binding.single.acept';
        $myParams['merchant_code']  = $this->app->config->merchant_code;
        $myParams['notify_url']     = $data['notify_url'];
        $myParams["quest_no"]       = $data['quest_no'];
        $myParams["userid"]         = $data['userid'];
        $myParams["user_name"]      = $data["user_name"];
        $myParams["idcard_no"]      = $data["idcard_no"];
        $myParams["bank_name"]      = $data["bank_name"];
        $myParams["card_type"]      = "debit"; //todo
        $myParams["card_no"]        = $data["card_no"];
        $myParams["mobile"]         = $data["mobile"];
        $myParams["subject"]        = "personal"; //todo
        $myParams["bank_province"]  = $data["bank_province"];
        $myParams["bank_city"]      = $data["bank_city"];
        $myParams["bank_type"]      = $data["bank_type"];//"1021000"; // todo

        return $this->app->basic->httpPost( $this->api_urls['ydt_url'], $myParams);

    }

    /**
     * 银贷通付款接口
     * @param $data
     * @return \YsepaySdk\BasicService\ResponseInterface
     * @author tu6ge
     * @date 2019/8/10 下午11:53
     */
    public function createOrder($data)
    {
        $myParams = [];

        $myParams['interface_name'] = 'pay.remittransfer.single.accept';
        $myParams['merchant_code']  = $this->app->config->merchant_code;
        $myParams['notify_url']     = $data['notify_url'];
        $myParams["quest_no"]       = $data['quest_no'];
        $myParams["bind_card_id"]   = $data['bind_card_id'];
        $myParams["userid"]         = $data['userid'];
        $myParams["order_amount"]   = $data['order_amount'];
        $myParams['subject']        = $data['subject'];
        $myParams['principal_interest'] = $data['principal_interest'];
        $myParams['principal']      = $data['principal'];
        $myParams['Periods']        = 1;
        $myParams['agreement_no']   = $data['agreement_no'];

        $result = $this->app->basic->httpPost( $this->api_urls['ydt_url_df'], $myParams);

        return $result;
    }

    /**
     * 异步响应
     * @param array $data
     * @return array|bool
     * @throws YsepayException
     * @author tu6ge
     * @date dtime
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
        return $data;
    }
}