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
        return $tg->callMethod('sendMessage', [
            'chat_id' => $chat['id'],
            'text' => TgTest::sample($from['id'], $text),
            'reply_to_message_id' => $message['message_id'],
        ]);
    }
    
}