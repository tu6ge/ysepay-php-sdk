<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 16:33
 */
namespace YsepaySdk\Wxpay;

use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

class Client extends BaseClient
{
    /**
     * 小微商户需上传：00,30,35,36,31
     * 企业商户需上传：00,30,19,31,37
     * 个体商户需上传：00,30,19,35,36,31
     */
    protected $pic_type_list = [
        '00'    => '公民身份证正面',
        '30'    => '公民身份证反面',
        '33'    => '手持身份证正扫面照',
        '34'    => '门头照',
        '35'    => '结算银行卡正面照',
        '36'    => '结算银行卡反面照',
        '19'    => '营业执照',
        '31'    => '客户协议',
        '32'    => '授权书',
        '37'    => '开户许可证或印鉴卡',
        '20'    => '组织机构代码证',
    ];
    /**
     * 组织机构类型对应的应该上传的图片
     * @var array
     */
    protected  $cust_type_pics = [
        '0'     => [00,30,35,36,31],    //小微
        'B'     => [00,30,19,31,37],    //企业
        'C'     => [00,30,19,35,36,31], //个体
    ];

    /**
     * App下单接口
     * @param $data
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/11 上午1:12
     */
    public function createApp($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.sdkpay';

        $myParams['notify_url'] = $data['notify_url'];
        $biz_content_arr = array(
            "out_trade_no"  => $data['out_trade_no'],
            "shopdate"      => date('Ymd'),
            "subject"       => $data['subject'],
            "total_amount"  => $data['total_amount'],
            "currency"      => $data['currency'] ?? "CNY",
            "seller_id"     => $this->app->config->seller_id,
            "seller_name"   => $this->app->config->seller_name,
            "timeout_express" => $data['timeout_express'] ?? "24h",
            "business_code" => $this->app->config->business_code,
            "bank_type"     => self::$bank_type_list['wxpay'],
            "appid"         => $this->app->config->appid,
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams,'ysepay_online_sdkpay_response');
    }

    /**
     * 公众号下单接口
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午1:16
     */
    public function createOfficialAccount($data, $is_minipg = 0)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.online.weixin.pay';
        $myParams['notify_url'] = $data['notify_url'];
        $biz_content_arr = array(
            "out_trade_no"  => $data['out_trade_no'],
            "shopdate"      => date('Ymd'),
            "subject"       => $data['subject'],
            "total_amount"  => $data['total_amount'],
            "currency"      => $data['currency'] ?? "CNY",
            "seller_id"     => $this->app->config->seller_id,
            "seller_name"   => $this->app->config->seller_name,
            "timeout_express" => $data['timeout_express'] ?? "24h",
            "business_code" => $this->app->config->business_code,
            "sub_openid"    => $data['openid'],
            "appid"         => $this->app->config->appid,
        );
        if($is_minipg==1){
            $biz_content_arr['is_minipg'] = 1;
        }
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams,'ysepay_online_weixin_pay_response');
    }

    /**
     * 小程序下单接口
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午1:20
     */
    public function createMiniProgram($data)
    {
        return $this->createOfficialAccount($data, 1);
    }

    /**
     * 获取注册token
     * @param $data
     * @return mixed
     * @author tu6ge
     * @date 2019/8/11 上午1:38
     */
    public function getToken($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.merchant.register.token.get';

        $myParams['notify_url'] = $data['notify_url'];
        $biz_content_arr = new \stdClass();
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['register_url'], $myParams, 'ysepay_merchant_register_token_get_response');
    }

    /**
     * 上传图片
     * @param $data
     * @author tu6ge
     * @date 2019/8/11 上午1:57
     */
    public function uploadPicture($data)
    {
        return $this->app->basic->httpUpload(
            $this->app_urls['upload_picture_url'],
            [
                'picFile'   => $data['picFile']
            ],
            [
                'picType'   => $data['picType'],
                'token'     => $data['token']
            ]
        );
    }

    /**
     * 商户注册接口
     *
     * 步骤：1.获取token
     *      2.用获取的token上传图片
     *      3.用获取的token上传注册文本信息
     * @param $data
     * @return \YsepaySdk\BasicService\ResponseInterface|array
     * @author tu6ge
     * @date 2019/8/11 上午2:30
     */
    public function register($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.merchant.register.accept';
        $biz_content = array(
            "merchant_no"       => $data['merchant_no'],
            "cust_type"         => $data['cust_type'],
            "token"             => $data['token'],
            "another_name"      => $data['another_name'],            //姓名
            "cust_name"         => $data['cust_name'],          //机构名称
            "industry"          => $data['industry'] ?? '20',            //行业
            "province"          => $data['province'],
            "city"              => $data['city'],
            "company_addr"      => $data['company_addr'],
            "legal_name"        => $data['legal_name'],    //企业法人名字,小微商户可空
            "legal_tel"         => $data['legal_tel'],      //企业法人手机号
            "legal_cert_type"   => '00',
            "legal_cert_no"     => $this->app->basic->ECBEncrypt($data['legal_cert_no']),   //证件号。DES加密
            "settle_type"       => $data['settle_type'] ?? 1,   //1银行卡账户，0平台内账户
            "bank_account_no"   => $data['bank_account_no'],
            "bank_account_name" => $data['bank_account_name'],
            "bank_account_type" => $data['bank_account_type'],      // personal 对私账户   corporate 对公账户
            "bank_card_type"    => $data['bank_card_type'],    //debit=借记卡 credit=贷记卡 unit=单位结算卡
            "bank_name"         => $data['bank_name'], // 开户行名称
            "bank_type"         => $data['bank_type'],                                                      //银行名称
            "bank_province"     => $data['bank_province'],
            "bank_city"         => $data['bank_city'],
            "cert_type"         => '00',    //目前只支持00，00是身份证
            "cert_no"           => $this->app->basic->ECBEncrypt($data['cert_no']),    //开户人证件号,DES加密
            "bank_telephone_no" => $data['bank_telephone_no'],                          //银行预留手机号
        );
        if($data['cust_type'] == 'B' || $data['cust_type']=='C'){
            $biz_content['bus_license']         = $data['bus_license'];         //营业执照,个体商户、企业户时为必填
            $biz_content['bus_license_expire']  = $data['bus_license_expire'];  //营业执照有效期
        }
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['register_url'], $myParams);
    }

    public function registerQuery($data)
    {
        $myParams = [];
        $myParams['method'] = 'ysepay.merchant.register.query';
        $myParams['notify_url'] = $data['notify_url'];
        $biz_content = array(
            'usercode'      => $data['usercode'],
        );

        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['register_url'], $myParams);
    }
}