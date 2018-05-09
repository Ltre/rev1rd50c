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
        $tg = new Tg;
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
    
}