<?php

/**
 * tg分派后的具体处理逻辑存放
 */
class TgDeal extends DIEntity {

    function onReply(array $update){
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $from = $message['from'];
        $tg = new Tg;
        $me = $tg->getMe();
        $tg->log("get reply_to_message.from.id: {$message['reply_to_message']['from']['id']}");
        $tg->log("get me.id: {$me['id']}");
        if (@$message['reply_to_message']['from']['id'] == $me['id']) {
            $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => TgTest::sample($chat['id'], $from['id'], $text),
                'reply_to_message_id' => $message['message_id'],
            ]);
        }
    }
    
}