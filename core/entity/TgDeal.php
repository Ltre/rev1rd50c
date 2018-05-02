<?php

/**
 * tg分派后的具体处理逻辑存放
 */
class TgDeal extends DIEntity {

    function onReply(array $update){
file_put_contents(DI_LOG_PATH . 'sb' . date('Y-m-d') . '.log', 'A');//debug
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $from = $message['from'];
        $tg = new Tg;
        return $tg->callMethod('sendMessage', [
            'chat_id' => $chat['id'],
            'text' => '下一个TEXT',
            'reply_to_message_id' => $from['id'],
        ]);
    }
    
}