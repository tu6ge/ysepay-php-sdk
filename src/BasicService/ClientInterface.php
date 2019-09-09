<?php
namespace YsepaySdk\BasicService;

use YsepaySdk\Kernel\YsepayException;

interface ClientInterface{
    public function signEncrypt($input) ;
    public function signStr(array $myParams, bool $no_empty):string;
    public function buildSign(array $myParams):array ;
}