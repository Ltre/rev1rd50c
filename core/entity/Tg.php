<?php

import('net/dwHttp');

class Tg extends DIEntity {

    protected $http;

    protected $token = "TjIb~3Zc(aT7XQF0)m_q.aJhVM'z(dLfAi*4Yc_0Zm@z*kSm*eQdM0Hb~q)~GmJnSeB1Xn)vc9*3ZX_~RJa2c4!@zzAsb6";

    protected $hk = "wcGpK5GE.8VFKhTeec@0MqXzKy'1x6TFJeIqWv~OQop1wk-rki*3Qb(!)5A0Ax-6OmUvv2w3Gp";

    function __construct(){
        $this->http = new dwHttp;
        $this->token = ltreDeCrypt($this->token);
    }


    protected function log($msg){
        file_put_contents(DI_DATA_PATH.'cache/tg.log', "{$msg}\r\n", FILE_APPEND);
    }


    protected function req($method, array $args){
        $url = "https://api.telegram.org/bot{$this->token}/{$method}";
        $ret = $this->http->post($url, $args);//@todo: Fatal error: Uncaught Error: Call to a member function post() on null in /home/wwwroot/rev1rd50c/core/entity/Tg.php:26 Stack trace: #0 /home/wwwroot/rev1rd50c/core/entity/Tg.php(68): Tg->req('setWebhook', Array) #1 /home/wwwroot/rev1rd50c/core/do/Tg.do.php(14): Tg->setHk() #2 /home/wwwroot/rev1rd50c/core/base/__lib.php(255): TgDo->setHk() #3 /home/wwwroot/rev1rd50c/core/base/__route.php(30): invoke_method(Object(TgDo), 'setHk', Array) #4 /home/wwwroot/rev1rd50c/core/base/__lib.php(255): DIRoute->route() #5 /home/wwwroot/rev1rd50c/core/base/__init.php(3): invoke_method(Object(DIRoute), 'route') #6 /home/wwwroot/rev1rd50c/core/base/__include.php(105): require('/home/wwwroot/r...') #7 /home/wwwroot/rev1rd50c/index.php(4): require('/home/wwwroot/r...') #8 {main} thrown in /home/wwwroot/rev1rd50c/core/entity/Tg.php on line 26
        if (false === $ret) {
            $this->log("Req method[{$method}] failed, ret is: false");
            return false;
        }
        $reponse = json_decode($ret, 1);
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
            $expire = time() + 7200;
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
        dump($response);//@todo 如果这里失败，则需要告警，可使用机器人消息来告警
    }


    function hk($secret){
        $ourSecret = $this->getHkSecret();
        if ($secret != $ourSecret) {
            die('Invalid callback to webhook!');
        }
    }

}

