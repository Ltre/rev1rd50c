<?php
/**
 * wechat 消息相关 - 日文版
 */
class TgTest3 extends DIEntity {
    
    static function sample($chatId, $fromId, $msg){
        $initKey = 'isinit'.sha1($chatId);
        $isInit = self::store($initKey) ?: 'yes';
        if ($isInit == 'yes') {
            self::store($initKey, 'no');
            return "このロボットは生まれたばかりで、話せない。何か間違っている場合は、私を打つことができます...";
        }
        $msgList = self::store('msgList') ?: array();
        $answers = self::store('answers') ?: array();
        $modeKey = "mode.".sha1($fromId);
        $mode = self::store($modeKey) ?: 'chat';//默认为chat模式
        if ($msg !== NULL) {
            $msgList[] = $msg;
            self::store('msgList', $msgList);
        }
        if ($mode === 'learn') {
            $lastMsg = @$msgList[count($msgList) - 2];
            if ($lastMsg != '') {
                $answers[$lastMsg] = $msg;//学习答案
                self::store('answers', $answers);
            }
            self::store($modeKey, 'chat');
            return "わかりました！";
        } elseif ($mode === 'chat') {
            if (isset($answers[$msg])) {
                return $answers[$msg];
            } else {
                self::store($modeKey, 'learn');//遇到不懂的，改为学习模式
                return "何を話しているのですか。返信モードで教えてください！";
            }
        }
    }


    private static function store($key, $content = null){
        $file = DI_CACHE_PATH."wechat.samplemsg.{$key}";
        if (null === $content) return @unserialize(file_get_contents($file)) ?: null;
        else file_put_contents($file, serialize($content));
    }
    
}