<?php

class TgDo extends DIDo {

    //This is an webhook.
    function hk($hdl, $secret){
        $update = file_get_contents( "php://input");
// $update = '{"update_id":812401070,"message":{"message_id":19,"from":{"id":462394947,"is_bot":false,"first_name":"\u6a39\u9928\u9577","username":"sgz134","language_code":"zh-cn"},"chat":{"id":-275439610,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"group","all_members_are_administrators":true},"date":1525248932,"text":"/jj@pinkjjbot","entities":[{"offset":0,"length":13,"type":"bot_command"}]}}';
// $update = '{"update_id":812401078,"message":{"message_id":8,"from":{"id":533702151,"is_bot":false,"first_name":"\u63d2\u732a\u996d","username":"dd5766"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262460,"reply_to_message":{"message_id":7,"from":{"id":580288862,"is_bot":true,"first_name":"\u7c89\u8272\u7684\u5927\u6bcd\u9e21","username":"pinkjjbot"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262316,"text":"\u732a\u5c4c\u5305\u5df2\u4e0a\u684c\uff0c\u8bf7\u6162\u7528\uff01"},"text":"\u597d\u7684\uff0c\u64cd\u4f60\u5988\u903c\uff01"}}';
        $update = json_decode($update?:'{}', 1);
        Tg::inst($hdl)->hk($secret, $update);
    }

    //To set webhook
    function setHk($hdl, $forceUpdate = 0){
        Tg::inst($hdl)->setHk($forceUpdate);
    }

    //To set webhook for all
    function setHkAll($forceUpdate = 0){
        $hdls = array_keys($GLOBALS['tg']['bot_tokens']);
        foreach ($hdls as $hdl) {
            echo $hdl;//debug
            Tg::inst($hdl)->setHk($forceUpdate);
        }
    }

    function callMethod($hdl){
        putjson(0, Tg::inst($hdl)->callMethod(arg('method'), arg('params', [])));
    }

    function getMe($hdl){
        dump(Tg::inst($hdl)->getMe());
    }

    function view4sendMessage(){
        @$this->stpl();
    }

    //利用机器人推送私信（必须先start bot）
    function pushPrivateMsgThroughBot($hdl, $tgUserId = 0){
        $tgUserId = $tgUserId || ltreDeCrypt('xq40c3.(DuCzVTOITP');

        $msg = arg('msg');
        if (empty($hdl) || empty($msg)) {
            putjsonp(-1, null, 'param err!');
        }

        $tg = Tg::inst($hdl);
        list ($ok, $resp) = $tg->callMethod('sendMessage', [
            'chat_id' => $tgUserId,
            'text' => $msg,
        ]);

        putjsonp($ok?0:-1, $resp, $ok?'ok':'exception');
    }

    function login($auth = ''){
        if ($auth == 'auth') {
            try {
                $auth_data = TgLogin::checkTelegramAuthorization('ganmom', $_GET);
                TgLogin::saveTelegramUserData($auth_data);
            } catch (Exception $e) {
                die ($e->getMessage());
            }
            header('Location: /tg/login');
            exit;
        } elseif ($auth == 'logout') {
            setcookie('tg_user', '');
            header('Location: /tg/login');
            exit;
        } else {
            $tg_user = TgLogin::getTelegramUserData();
            if ($tg_user === false) {
                $this->botname = 'ganmom_bot';
                $this->stpl();
            } else {
                $first_name = htmlspecialchars($tg_user['first_name']);
                @$last_name = htmlspecialchars($tg_user['last_name']);
                @$username = htmlspecialchars($tg_user['username']?:'');
                @$photo_url = htmlspecialchars($tg_user['photo_url']?:'');
                echo "<html><head><meta charset='utf-8'></head><body>first_name: {$first_name}, last:name: {$last_name}, username: {$username}, photo: <img src='{$photo_url}'><a href='/tg/login/logout'>退出</a></body></html>";
            }
            exit;
        }
    }

}