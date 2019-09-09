<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-05
 * Time: 14:52
 */
namespace YsepaySdk\BasicService;

use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use YsepaySdk\Kernel\BaseClient;
use YsepaySdk\Kernel\ResponseException;
use YsepaySdk\Kernel\YsepayException;

/**
 * Class Client
 * @property \GuzzleHttp\Client             $http_client
 * @package YsepaySdk\BasicService
 */
class Client extends BaseClient implements ClientInterface
{
    /**
     * 签名加密
     * @param $input
     * @return string
     * @throws YsepayException
     * @author tu6ge
     * @date 2019-08-05 16:51
     */
    public function signEncrypt($input)
    {
        $pkcs12 = file_get_contents($this->app->config->private_cert); //私钥
        if (openssl_pkcs12_read($pkcs12, $certs, $this->app->config->pfxpassword) == false) {
            throw new YsepayException('openssl_pkcs12_read fail');
        }
        $privateKey = $certs['pkey'];
        $signedMsg = "";
        if (openssl_sign($input, $signedMsg, $privateKey, OPENSSL_ALGO_SHA1) == false) {
            throw new YsepayException('openssl_sign fail');
        }
        return base64_encode($signedMsg);
    }

    /**
     * 获取待签名字符串
     * @param $myParams
     * @return string
     * @author tu6ge
     * @date dtime
     */
    public function signStr(array $myParams, bool $no_empty=false):string
    {
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            if($no_empty){
                if($val){
                    $signStr .= $key . '=' . $val . '&';
                }
            }else{
                $signStr .= $key . '=' . $val . '&';
            }
        }
        return rtrim($signStr, '&');
    }
    public function http_build_query($myParams, $no_empty=false)
    {
        $signStr = "";
        foreach ($myParams as $key => $val) {
            if($no_empty){
                if($val){
                    $signStr .= $key . '=' . $val . '&';
                }
            }else{
                $signStr .= $key . '=' . $val . '&';
            }
        }
        return rtrim($signStr, '&');
    }

    /**
     * 异步回调的签名验证
     * @param $sign
     * @param $data
     * @return int
     * @author tu6ge
     * @date dtime
     */
    public function signCheck($sign, $data)
    {
        $certificateCAcerContent = file_get_contents($this->app->config->businessgatecerpath);
        $certificateCApemContent = '-----BEGIN CERTIFICATE-----' . PHP_EOL . chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL) . '-----END CERTIFICATE-----' . PHP_EOL;
        // 签名验证
        return openssl_verify($data, base64_decode($sign), openssl_get_publickey($certificateCApemContent), OPENSSL_ALGO_SHA1);
    }

    public function buildSign(array $data):array
    {
        unset($data['sign']);
        $sign = $this->signEncrypt(
            $this->signStr($data)
        );
        $data['sign'] = $sign;
        return $data;
    }
    /**
     * DES加密方法
     * @param $data 传入需要加密的证件号码
     * @return string 返回加密后的字符串
     */
    function ECBEncrypt($data, $key='')
    {
        if(empty($key)){
            $key = sprintf('%8.8s', $this->app->config->partner_id);
        }
        $encrypted = openssl_encrypt($data, 'DES-ECB', $key, 1);
        return base64_encode($encrypted);
    }

    /**
     * DES解密方法
     * @param $data 传入需要解密的字符串
     * @return string 返回解密后的证件号码
     */
    function ECBDecrypt($data, $key)
    {
        $encrypted = base64_decode($data);
        $decrypted = openssl_decrypt($encrypted, 'DES-ECB', $key, 1);
        return $decrypted;
    }

    public function httpPost($url, $params, $result_field="")
    {
        return $this->request($url, 'POST', ['form_params'=>$params], $result_field);
    }

    public function httpGet($url)
    {
        return $this->request($url);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     * @param array  $query
     *
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', [
            'query'             => $query,
            'multipart'         => $multipart,
            'connect_timeout'   => 30,
            'timeout'           => 30,
            'read_timeout'      => 30,
            'handler'           => HandlerStack::create(),
        ]);
    }

    /**
     * 公共参数 中间件
     * @return \Closure
     * @author tu6ge
     * @date 2019/8/13 下午9:34
     */
    public function publicParamsMiddleware()
    {
        return function (callable $handler){
            return function (RequestInterface $request, array $options) use ($handler) {
                $params = $this->publicParams();
                $allUrl = $this->getUrl();
                if(in_array($request->getUri(),[$allUrl['ydt_url'],$allUrl['ydt_url_df']])){
                    $params['quest_time'] = $params['timestamp'];
                    unset($params['timestamp']);
                }
                $request->getBody()->rewind();
                $con = $request->getBody()->getContents();
                parse_str($con, $form_params);
                $form_params = array_merge($form_params, $params);
                $request->getBody()->rewind();
                $request->getBody()->write(http_build_query($form_params, '&'));
                return $handler($request, $options);
            };
        };
    }

    /**
     * 给guzzle添加sign中间件
     * @return \Closure
     * @author tu6ge
     * @date 2019/8/12 下午9:18
     */
    public function signMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $request->getBody()->rewind();
                $con = $request->getBody()->getContents();
                parse_str($con, $form_params);
                $form_params = $this->buildSign($form_params);
                $request->getBody()->rewind();
                $request->getBody()->write(http_build_query($form_params, '&'));

                return $handler($request, $options);
            };
        };
    }

    /**
     * @param $url
     * @param string $method
     * @param array $options
     * @param string $result_field
     * @return array|mixed|ResponseInterface|string
     * @throws ResponseException
     * @throws YsepayException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author tu6ge
     * @date 2019/8/13 下午8:59
     */
    public function request($url, $method = 'GET', $options = [], $result_field='')
    {
        $method = strtoupper($method);

        $handlerStack = HandlerStack::create();
        $handlerStack->push($this->publicParamsMiddleware());
        $handlerStack->push($this->signMiddleware());

        empty($options['handler']) && $options['handler'] = $handlerStack;

        $response = $this->app->http_client->request($method, $url, $options);

        if(isset($this->app->config->response_type) && $this->app->config->response_type=='raw'){
            return $response;
        }

        $res = $response->getBody()->getContents();

        $res = \GuzzleHttp\json_decode($res, true);
        if(empty($result_field)){
            return $res;
        }
        if(!isset($res[$result_field])){
            throw new YsepayException('return data is fail');
        }
        return $res[$result_field] ?? [];
    }


}