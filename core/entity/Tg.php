<?php

import('net/dwHttp');

class Tg extends DIEntity {

    const API_PRE = 'https://api.telegram.org';

    protected $http;

    protected $hdl;

    protected $token = "TjIb~3Zc(aT7XQF0)m_q.aJhVM'z(dLfAi*4Yc_0Zm@z*kSm*eQdM0Hb~q)~GmJnSeB1Xn)vc9*3ZX_~RJa2c4!@zzAsb6";//afnaygnahz

    protected $hk = "OuUD~mZX(4@KGdVgHF*3v9!Eqe'1-FykLgk2r0sfB9BdUI'1MgC6)zO9OM!2G1)*_7McCz!2Fd_IWtXuve";


    /**
     * 创建一个机器人对应的tg实例
     *
     * @param string $hdl
     * @return Tg
     */
    static function inst($hdl){
        static $objs = [];
        if (! isset($objs[$hdl])) {
            $objs[$hdl] = new self($hdl);
        }
        return $objs[$hdl];
    }


    function __construct($hdl){
        if (! isset($GLOBALS['tg']['bot_tokens'][$hdl])) {
            throw new DIException("tg.bot_tokens.{$hdl} is not found!");
        }
        $token = $GLOBALS['tg']['bot_tokens'][$hdl];
        $this->http = new dwHttp;
        $this->hdl = $hdl;
        $this->token = ltreDeCrypt($token);
    }


    protected function log($msg){
        file_put_contents(DI_DATA_PATH."log/tg.{$this->hdl}.".date('Ymd').".log", "{$msg}\r\n", FILE_APPEND);
    }


    protected function req($method, array $args){
        $hasFile = false;
        foreach ($args as $k => $v) {
            if ($v instanceof CURLFile) {
                $hasFile = true;
                break;
            }
        }

        $url = self::API_PRE."/bot{$this->token}/{$method}";

        if ($hasFile) {
            $ret = $this->http->postFile($url, $args);
        } else {
            $ret = $this->http->post($url, $args);
        }

        if (false === $ret) {
            $failMsg = "Req method[{$method}] failed, ret is: false, args is: ".print_r($args, 1);
            $this->log($failMsg);
            return [false, ['failMsg' => $failMsg]];
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
        list ($ok, $response) = $feed;
        if (! $ok) {
            dump($response);
        }
    }

    //获取回调webhook时用的secret，本secret由我方系统生成，具有有效期
    protected function getHkSecret($forceUpdate = 0){
        $secretFile = DI_DATA_PATH."cache/tg.{$this->hdl}.hk.secret";
        @$secretData = unserialize(file_get_contents($secretFile)) ?: ["", 0];
        list ($secret, $expire) = $secretData;
        if (time() >= $expire || $forceUpdate) {
            $secret = sha1(microtime(1).rand(0, 99999));
            $expire = time() + 86400*30;
            file_put_contents($secretFile, serialize([$secret, $expire]));
        }
        return $secret;
    }


    //这个需要上定时任务，刷新tg官方回调的webhook url所用的secret部分
    function setHk($forceUpdate){
        $secret = $this->getHkSecret($forceUpdate);
        $url = ltreDeCrypt($this->hk)."/{$this->hdl}/{$secret}";
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
            // die('Invalid callback to webhook!');
        }
        $dp = TgDispatch::inst($this->hdl);
        $feed = $dp->analyze($update);
        $dp->dispatch($feed, $update);
    }


    function getMe(){
        $meFile = DI_DATA_PATH."cache/tg.{$this->hdl}.me";
        @$meData = unserialize(file_get_contents($meFile)) ?: ["", 0];
        list ($me, $expire) = $meData;
        if (time() >= $expire) {
            list ($ok, $response) = $this->callMethod('getMe', []);
            if (! $ok || ! isset($response['result'])) {
                return null;
            }
            $me = $response['result'];//array {id:xx, is_bot:xx, first_name:xx, username:xx}
            $expire = time() + 86400*7;
            file_put_contents($meFile, serialize([$me, $expire]));
        }
        return $me;
    }


    //@todo TEST
    function getChatMember($chatId, $userId){
        $chatMemberFile = DI_DATA_PATH."cache/tg.{$this->hdl}.chatmember.{$chatId}.{$userId}";
        @$chatMemberData = unserialize(file_get_contents($chatMemberFile)) ?: ["", 0];
        list ($chatMember, $expire) = $chatMemberData;
        if (time() >= $expire) {
            list ($ok, $response) = $this->callMethod('getChatMember', [
                'chat_id' => $chatId,
                'user_id' => $userId,
            ]);
            if (! $ok || ! isset($response['result'])) {
                return null;
            }
            $chatMember = $response['result'];
            $expire = time() + 86400*7;
            file_put_contents($chatMemberFile, serialize([$chatMember, $expire]));
        }
        return $chatMemeberData;
    }


    //@todo TEST
    function isGroupAdmin($chatId, $userId){
        $chatMemeber = $this->getChatMember($chatId, $userId);
        $chatMemeber['status'];//The member's status in the chat. Can be “creator”, “administrator”, “member”, “restricted”, “left” or “kicked”.  https://core.telegram.org/bots/api#chatmember
    }


    //@todo: 开发中。。example for https://core.telegram.org/bots#deep-linking
    function deepLinking(){
        import('store/dwCache');
        $mmc = new dwCache(__CLASS__.__FUNCTION__);
        $memcache_key = "vCH1vGWJxfSeofSAs0K5PA";
        $mmc->set($memcache_key, 123);
    }


    //@todo TEST
    function filelink($fileId){
        if (! $fileId) return [false, '', null];

        list ($ok, $feed) = $this->callMethod('getFile', [
            'file_id' => $fileId,
        ]);

        if (!$ok || !isset($feed['result']['file_path'])) return [false, '', $feed];
        
        $path = $feed['result']['file_path'];
        $link = self::API_PRE."/file/bot{$this->token}/{$path}";

        return [true, $link, $feed];
    }


    //@todo 考虑是否支持第三个参数$cache：控制缓存：no-绕过，update-立即刷新，default-使用已有缓存
    function callMethod($method, array $params){
        $feed = $this->req($method, $params);
        // $this->dealFeed($feed);
        list ($ok, $response) = $feed;
        return [$ok, $response];
    }

}

