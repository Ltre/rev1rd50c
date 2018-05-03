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
            $this->log("Req method[{$method}] failed, ret is: false, args is: ".print_r($args, 1));
            return false;
        }
        $response = json_decode($ret, 1);
        if (! @$response['ok']) {
            $this->log("Req method[{$method}] is not ok, ret is: {$ret}, args is: ".print_r($args, 1));
            return [false, $response];
        }
        $this->log("Req method[{$method}] is ok, ret is: {$ret}, args is: ".print_r($args, 1));
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


    /**
     * webhook
     * @param string $secret
     * @param array $update
     * 传入结构部分示例(json表示)：
     *  {
     *      "update_id":812401123,
     *      "message":
     *      {
     *          "message_id":17,
     *          "from":
     *          {
     *              "id":456123156,
     *              "is_bot":false,
     *              "first_name":"fdsfsda",
     *              "username":"adddfsa",
     *              "language_code":"zh-cn"
     *          },
     *          "chat":
     *          {
     *              "id":456123156,
     *              "first_name":"fdsfsda",
     *              "username":"adddfsa",
     *              "type":"private"
     *          },
     *          "date":1525247696,
     *          "text":"1"
     *      }
     *  }
     * 完整结构详见：https://core.telegram.org/bots/api#update
     * @return mixed
     */
    function hk($secret, array $update){
        $ourSecret = $this->getHkSecret();
        if ($secret != $ourSecret) {
            die('Invalid callback to webhook!');
        }
        $dp = new TgDispatch($update);
        $feed = $dp->analyze();
        $dp->dispatch($feed);
    }


    function getMe(){
        $meFile = DI_DATA_PATH.'cache/tg.me';
        @$meData = unserialize(file_get_contents($meFile)) ?: ["", 0];
        list ($me, $expire) = $meData;
        if (time() >= $expire) {
            list ($ok, $response) = $this->callMethod('getMe', []);
            if (! $ok || ! isset($response['result'])) {
                return null;
            }
            $me = $response['result'];//array {id:xx, is_bot:xx, first_name:xx, username:xx}
            $expire = time() + 86400*30;
            file_put_contents($meFile, serialize([$me, $expire]));
        }
        return $me;
    }


    //@todo 考虑是否支持第三个参数$cache：控制缓存：no-绕过，update-立即刷新，default-使用已有缓存
    function callMethod($method, array $params){
        $feed = $this->req($method, $params);
        $this->dealFeed($feed);
        list ($ok, $response) = $feed;
        return [$ok, $response];
    }

}

