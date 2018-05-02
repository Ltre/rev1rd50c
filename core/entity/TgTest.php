<?php
/**
 * wechat 消息相关
 */
class TgTest extends DIEntity {
    
    static function sample($chatId, $fromId, $msg){
        $initKey = 'isinit'.sha1($chatId);
        $isInit = self::_sampleStore($initKey) ?: 'yes';
file_put_contents(DI_DATA_PATH.'cache/tg.log', "A", FILE_APPEND);//DEBUG
        if ($isInit == 'yes') {
file_put_contents(DI_DATA_PATH.'cache/tg.log', "B", FILE_APPEND);//DEBUG
            self::_sampleStore($initKey, 'no');
            return "本母鸡刚出生，还不太会说话，如果有什么得罪的，你来打我啊...(用回复我的方式来跟我交流，其它方式本大鸡暂时不想理你..要是你们多人一起跟我说话，我会精神错乱的...)";
        }
file_put_contents(DI_DATA_PATH.'cache/tg.log', "C", FILE_APPEND);//DEBUG
        $msgList = self::_sampleStore('msgList') ?: array();
        $answers = self::_sampleStore('answers') ?: array();
        $modeKey = "mode.".sha1($fromId);
        $mode = self::_sampleStore($modeKey) ?: 'chat';//默认为chat模式
        if ($msg !== NULL) {
file_put_contents(DI_DATA_PATH.'cache/tg.log', "D", FILE_APPEND);//DEBUG
            $msgList[] = $msg;
            self::_sampleStore('msgList', $msgList);
        }
        if ($mode === 'learn') {
file_put_contents(DI_DATA_PATH.'cache/tg.log', "E", FILE_APPEND);//DEBUG
            $lastMsg = @$msgList[count($msgList) - 2];
            if ($lastMsg != '') {
file_put_contents(DI_DATA_PATH.'cache/tg.log', "F", FILE_APPEND);//DEBUG
                $answers[$lastMsg] = $msg;//学习答案
                self::_sampleStore('answers', $answers);
            }
            self::_sampleStore($modeKey, 'chat');
            return "已学习！";
        } elseif ($mode === 'chat') {
file_put_contents(DI_DATA_PATH.'cache/tg.log', "G", FILE_APPEND);//DEBUG
            if (isset($answers[$msg])) {
                return $answers[$msg];
            } else {
                self::_sampleStore($modeKey, 'learn');//遇到不懂的，改为学习模式
                return "纳尼索类意米挖干奶（快教我回答）?";
            }
        }
    }
    
    private static function _sampleStore($key, $content = null){
file_put_contents(DI_DATA_PATH.'cache/tg.log', "H", FILE_APPEND);//DEBUG
        $file = DI_CACHE_PATH."wechat.samplemsg.{$key}";
        if (null === $content) return @unserialize(file_get_contents($file)) ?: null;
        else file_put_contents($file, serialize($content));
    }
    
}