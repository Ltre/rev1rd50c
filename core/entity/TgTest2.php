<?php
/**
 * wechat 消息相关 - 英文版
 */
class TgTest2 extends DIEntity {
    
    static function sample($chatId, $fromId, $msg){
        $initKey = 'isinit'.sha1($chatId);
        $isInit = self::store($initKey) ?: 'yes';
        if ($isInit == 'yes') {
            self::store($initKey, 'no');
            return "This robot has just been born and is not very able to speak. If there is anything wrong with it, you can hit me...";
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
            return "Got it！";
        } elseif ($mode === 'chat') {
            if (isset($answers[$msg])) {
                return $answers[$msg];
            } else {
                self::store($modeKey, 'learn');//遇到不懂的，改为学习模式
                return "What are you talking about? Please teach me with reply mode!";
            }
        }
    }


    private static function store($key, $content = null){
        $file = DI_CACHE_PATH."wechat.samplemsg.{$key}";
        if (null === $content) return @unserialize(file_get_contents($file)) ?: null;
        else file_put_contents($file, serialize($content));
    }
    
}