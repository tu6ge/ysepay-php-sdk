<?php
namespace YsepaySdk\Tests\Alipay;

class Basic
{
    public $array = [];
    public function set_array()
    {
        return function(){
            $this->array = func_get_args()[0];
            return true;
        };
    }

    public function get_array()
    {
        $this->array['sign'] = '123';
        return $this->array;
    }
}