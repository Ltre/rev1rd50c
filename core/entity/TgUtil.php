<?php

class TgUtil extends DIEntity {

    static function specialTextFilter($text, $parse_mode = ''){
        if ($parse_mode == 'Markdown') {
            $text = str_replace(
                ['(', ')', '`', '*', '[', ']', '"', "'", ':', ';', '!', '~', '@', '#', '$', '%', '^', '&', '-', '=', '{', '}', '|', '/', ',', '.', '?', '\\', '_', '+'],
                ['（', '）', '·', '＊', '［', '］', '＂', "＇", '：', '；', '！', '～', '＠', '＃', '＄', '％', '＾', '＆', '－', '＝', '｛', '｝', '｜', '／', '，', '．', '？', '＼', '＿', '＋'],
                $text
            );
        } elseif ($parse_mode == 'HTML') {
            $text = htmlentities($text);
        }
        return $text;
    }


    //发送自建图库的图片或动态图
    static function sendImageOrAnimateByTuku($tg, array $chat, array $tuData, array $otherArgs){
        // $tg->log("sendImageOrAnimateByTuku.args: ".print_r(func_get_args(), 1)."\r\n");//debug
        $caption = "tuId={$tuData['tuId']}\nTags: " . join('; ', $tuData['tags']);
        $headers = get_headers($tuData['url'], 1);
        // $tg->log("sendImageOrAnimateByTuku.caption: {$caption}");//debug
        // $tg->log("sendImageOrAnimateByTuku.args: ".print_r($headers, 1)."\r\n");//debug
        if (in_array($headers['Content-Type'], ['image/gif', 'video/mp4'])) {
            return $tg->callMethod('sendAnimation', [
                'chat_id' => $chat['id'],
                'animation' => $tuData['url'],
                'caption' => 'gif|mp4: '.$caption,
                'reply_to_message_id' => $otherArgs['reply_to_message_id'],
            ]);
        } elseif (preg_match('/^image\//i', $headers['Content-Type'])) {
            return $tg->callMethod('sendPhoto', [
                'chat_id' => $chat['id'],
                'photo' => $tuData['url'],
                'caption' => $caption,
                'reply_to_message_id' => $otherArgs['reply_to_message_id'],
            ]);
        } else {
            $responseText = "Unsupported MIMETYPE：{$headers['Content-Type']}";
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => $responseText,
                'reply_to_message_id' => $otherArgs['reply_to_message_id'],
                'parse_mode' => 'Markdown',
            ]);
        }
    }

}