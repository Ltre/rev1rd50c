<?php
/**
 * wechat 消息相关
 */
class TgTest extends DIEntity {
    
    static function sample($fromId, $msg){
        $msgList = self::_sampleStore('msgList') ?: array();
        $answers = self::_sampleStore('answers') ?: array();
        $modeKey = "mode.".sha1($fromId);
        $mode = self::_sampleStore($modeKey) ?: 'wait';//默认为等待话题模式
        if ($msg !== NULL) {
            $msgList[] = $msg;
            self::_sampleStore('msgList', $msgList);
        }
        if ($mode === 'wait') {
            self::_sampleStore($modeKey, 'chat');
            return "I'm a bot!";
        } elseif ($mode === 'learn') {
            $lastMsg = @$msgList[count($msgList) - 2];
            if ($lastMsg != '') {
                $answers[$lastMsg] = $msg;//学习答案
                self::_sampleStore('answers', $answers);
            }
            self::_sampleStore($modeKey, 'chat');
            return "已学习！";
        } elseif ($mode === 'chat') {
            if (isset($answers[$msg])) {
                return $answers[$msg];
            } else {
                self::_sampleStore($modeKey, 'learn');//遇到不懂的，改为学习模式
                return "纳尼索类意米挖干奶（快教我回答）?";
            }
        }
    }
    
    private static function _sampleStore($key, $content = null){
        $file = DI_CACHE_PATH."wechat.samplemsg.{$key}";
        if (null === $content) return @unserialize(file_get_contents($file)) ?: null;
        else file_put_contents($file, serialize($content));
    }
    
}