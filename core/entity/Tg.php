<?php

import('net/dwHttp');

class Tg extends DIEntity {

    protected $http;

    protected $token = "TjIb~3Zc(aT7XQF0)m_q.aJhVM'z(dLfAi*4Yc_0Zm@z*kSm*eQdM0Hb~q)~GmJnSeB1Xn)vc9*3ZX_~RJa2c4!@zzAsb6";

    protected $hk = "OuUD~mZX(4@KGdVgHF*3v9!Eqe'1-FykLgk2r0sfB9BdUI'1MgC6)zO9OM!2G1)*_7McCz!2Fd_IWtXuve";

    function __construct(){
        $this->http = new dwHttp;
        $this->token = ltreDeCrypt($this->token);
    }


    protected function log($msg){
        file_put_contents(DI_DATA_PATH.'cache/tg.log', "{$msg}\r\n", FILE_APPEND);
    }


    protected function req($method, array $args){
        $url = "https://api.telegram.org/bot{$this->token}/{$method}";
        $ret = $this->http->post($url, $args);
        if (false === $ret) {
            $this->log("Req method[{$method}] failed, ret is: false");
            return false;
        }
        $response = json_decode($ret, 1);
        if (! @$response['ok']) {
            $this->log("Req method[{$method}] is not ok, ret is: {$ret}");
            return [false, $response];
        }
        return [true, $response];
    }


    protected function dealFeed($feed){//@todo 以后修改输出格式为：根据请求类型判断，xhr用json返回, 其它直接dump
        if (false === $feed) {
            die('wtf');
        }
        list ($ok, $response) = $feed;
        if (! $ok) {
            dump($response, 1);
        }
    }

    //获取回调webhook时用的secret，本secret由我方系统生成，具有有效期
    protected function getHkSecret(){
        $secretFile = DI_DATA_PATH.'cache/tg.hk.secret';
        @$secretData = unserialize(file_get_contents($secretFile)) ?: ["", 0];
        list ($secret, $expire) = $secretData;
        if (time() >= $expire) {
            $secret = sha1(microtime(1));
            $expire = time() + 86400*30;
            file_put_contents($secretFile, serialize([$secret, $expire]));
        }
        return $secret;
    }


    //这个需要上定时任务，刷新tg官方回调的webhook url所用的secret部分
    function setHk(){
        $secret = $this->getHkSecret();
        // $url = ltreDeCrypt($this->hk.'/'.$secret);
        $url = ltreDeCrypt($this->hk).'/'.$secret;
        $feed = $this->req('setWebhook', ['url' => $url]);
        $this->dealFeed($feed);
        list ($ok, $response) = $feed;
        echo "url:{$url}<br>";
        dump($response);//@todo 如果这里失败，则需要告警，可使用机器人消息来告警
    }


    function hk($secret, $update){
        $ourSecret = $this->getHkSecret();
        if ($secret != $ourSecret) {
            die('Invalid callback to webhook!');
        }
    }


    function callMethod($method, array $params){
        $feed = $this->req($method, $params);
        $this->dealFeed($feed);
        list ($ok, $response) = $feed;
        dump($response);
    }

}

