<?php
namespace YsepaySdk\Authenticate;

use InvalidArgumentException;
use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

/**
 * 实名认证
 * Class Client
 * @package YsepaySdk\Authenticate
 */
class Client extends BaseClient
{
    /**
     * 四要素
     * @param $data
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/11 上午12:19
     */
    public function authenFour($data)
    {
        $myParams = [];
        $myParams['method']     = 'ysepay.authenticate.four.key.element';
        $biz_content = array(
            "out_trade_no"      => $data['out_trade_no'],
            "bank_account_name" => $data['bank_account_name'],
            "bank_account_no"   => $data['bank_account_no'],
            "id_card"           => $this->app->basic->ECBEncrypt($data['id_card']),
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, 320);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_authenticate_four_key_element_response');
    }

    /**
     * 手机验证
     * @param $data
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/11 上午12:19
     */
    public function authenMobile($data)
    {
        $myParams = [];
        $myParams['method']     = 'ysepay.authenticate.mobile.operators.three.key.element';
        $biz_content = array(
            "out_trade_no"      => $data['out_trade_no'],
            "name"              => $data['name'],
            "phone"             => $data['phone'],
            "id_card"           => $this->app->basic->ECBEncrypt($data['id_card']),
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, 320);//构造字符串
        return $this->app->basic->httpPost(
            $this->api_urls['order_url'],
            $myParams,
            'ysepay_authenticate_mobile_operators_three_key_element_response'
        );
    }
    /**
     * 两要素验证
     * @param $data
     * @return mixed
     * @throws ResponseException
     * @throws YsepayException
     * @author tu6ge
     * @date 2019/8/11 上午12:19
     */
    public function authenTwo($data)
    {
        $myParams = [];
        $myParams['method']     = 'ysepay.authenticate.id.card.img';
        $biz_content = array(
            "out_trade_no"      => $data['out_trade_no'],
            "name"              => $data['name'],
            "id_card"           => $this->app->basic->ECBEncrypt($data['id_card']),
        );
        $myParams['biz_content'] = \GuzzleHttp\json_encode($biz_content, 320);//构造字符串
        return $this->app->basic->httpPost( $this->api_urls['order_url'], $myParams, 'ysepay_authenticate_id_card_img_response');
    }

    /**
     * 保存base64图像到目录
     * @param $directory
     * @param string $filename
     * @param $content
     * @return bool|string
     * @author tu6ge
     * @date 2019/8/11 上午1:01
     */
    public function saveBas64Image($directory, $filename='', $content)
    {
        $directory = rtrim($directory, '/');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // @codeCoverageIgnore
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf("'%s' is not writable.", $directory));
        }
        if(empty($filename)){
            $filename = md5($content);
        }
        $filename = $filename. '.jpeg';
        $save_file = $directory .'/'. $filename;
        $rs = file_put_contents($save_file, base64_decode($content));
        return $rs>0 ? $filename : false;
    }
}