<?php
/**
 * wechat 消息相关
 */
class TgTest extends DIEntity {
    
    static function sample($chatId, $fromId, $msg){
        $initKey = 'isinit'.sha1($chatId);
        $isInit = self::_sampleStore($initKey) ?: 'yes';
        if ($isInit == 'yes') {
            self::_sampleStore($initKey, 'no');
            return "本母鸡刚出生，还不太会说话，如果有什么得罪的，你来打我啊...(用回复我的方式来跟我交流，其它方式本大鸡暂时不想理你..要是你们多人一起跟我说话，我会精神错乱的...)";
        }
        $msgList = self::_sampleStore('msgList') ?: array();
        $answers = self::_sampleStore('answers') ?: array();
        $modeKey = "mode.".sha1($fromId);
        $mode = self::_sampleStore($modeKey) ?: 'wait';//默认为等待话题模式
        if ($msg !== NULL) {
            $msgList[] = $msg;
            self::_sampleStore('msgList', $msgList);
        }
        if ($mode === 'learn') {
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