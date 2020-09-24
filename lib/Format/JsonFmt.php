<?php
namespace JsonRpc\Format;

class JsonFmt extends BaseFmt {
    const TYPE_RESPONSE = 'response';
    const TYPE_REQUEST  = 'request';

    /**
     * @var string 标识
     * @rule method:_check_jsonrpc|InvalidParamsException:-32602
     * @default string:2.0
     */
    public $jsonrpc;
    public function _check_jsonrpc($v){
        return boolval($v === '2.0');
    }

    /**
     * @var int|string 唯一id
     */
    public $id;

    /**
     * @var string 请求方法
     * @required[request] true|MethodNotFoundException:-32601
     */
    public $method;

    /**
     * @var array 请求参数
     * @required[request] true|InvalidParamsException:-32602
     */
    public $params;

    /**
     * @var mixed 响应对象
     * @required[response] method:_check_result|InternalErrorException:-32603
     */
    public $result;
    public function _check_result($v){
        return boolval(!(!$this->result and !$this->error));
    }

    /**
     * @var array 错误内容
     * @rule[response] method:_check_error|InternalErrorException:-32603
     */
    public $error;
    public function _check_error($v){
        if($v){
            $s = ErrorFmt::factory($v);
            $s->validate();
            $this->setSpecialCode($s->getSpecialCode());
            $this->setSpecialError($s->getSpecialError());
            return boolval($s->hasError());
        }
        return true;
    }
}