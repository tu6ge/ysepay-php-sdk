<?php
/**
 * Created by PhpStorm.
 * User: ZHIYUAN
 * Date: 2019-08-06
 * Time: 10:23
 */
namespace YsepaySdk\Kernel;

use Exception;

class ResponseException extends \Exception
{
    public $response;
    public function __construct($data)
    {
        $message = $data['msg'];
        if(isset($data['sub_msg'])){
            $message = $data['sub_msg'];
        }
        parent::__construct($message, $data['code']);
        $this->response = $data;
    }
}