<?php

/**
 * tg分派后的具体处理逻辑存放
 */
class TgDeal extends DIEntity {

    protected $hdl;

    /**
     * 创建一个机器人对应的TgDeal实例
     *
     * @param string $hdl
     * @return TgDeal
     */
    static function inst($hdl){
        static $objs = [];
        if (! isset($objs[$hdl])) {
            $objs[$hdl] = new self($hdl);
        }
        return $objs[$hdl];
    }


    function __construct($hdl){
        $this->hdl = $hdl;
    }


    function onReply(array $update){
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $from = $message['from'];
        $tg = Tg::inst($this->hdl);
        $me = $tg->getMe();
        $tg->log("get reply_to_message.from.id: {$message['reply_to_message']['from']['id']}");
        $tg->log("get me.id: {$me['id']}");
        if (@$message['reply_to_message']['from']['id'] == $me['id']) {
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => TgTest::sample($chat['id'], $from['id'], $text),
                'reply_to_message_id' => $message['message_id'],
            ]);
        }
    }


    function onCmd(array $update){
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $tg = Tg::inst($this->hdl);
        $me = $tg->getMe();
        $responseText = null;
        if (preg_match('/^\/(\w+)(@'.$me['username'].')?/', $text, $matches)) {
            switch ($matches[1]) {
                case 'jj':
                    $list = ['轻点，疼，对，就这样，嗯.. 嗯.. 啊~~ 昂~~~', '快进来~~', '叫你妹啊!'];
                    $responseText = $list[rand(0, count($list)-1)];
                    break;
            }
        }
        if ($responseText) {
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => $responseText,
                'reply_to_message_id' => $message['message_id'],
            ]);
        }
    }


    function onNewChatMember(array $update){
        $message = $update['message'];
        $chat = $message['chat'];
        $member = $message['new_chat_member'];
        $tg = Tg::inst($this->hdl);
        if ($this->hdl == 'pinkjj' && $chat['id'] == '-1001377141307') {
            @$name = TgUtil::specialTextFilter($member['first_name'].$member['last_name'], 'Markdown');
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => "`` 欢迎新傻逼: [{$name}]((tg://user?id={$member['id']}).\n欢迎费用：*10傻币/次*（代扣100智商，自动兑换傻币支付）",
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
        }
    }
    
}