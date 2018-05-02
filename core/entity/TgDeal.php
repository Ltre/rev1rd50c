<?php

/**
 * tg分派后的具体处理逻辑存放
 */
class TgDeal extends DIEntity {

    function onReply(array $update){
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $tg = new Tg;
        return $tg->callMethod('sendMessage', [
            'chat_id' => $chat['id'],
            'text' => '下一个TEXT',
            'reply_to_message_id' => $message['message_id'],
        ]);
    }
    
}