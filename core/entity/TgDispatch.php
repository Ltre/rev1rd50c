<?php
/**
 * tg根据消息分派
 */
class TgDispatch extends DIEntity {

    protected $hdl;

    /**
     * 创建一个机器人对应的TgDispatch实例
     *
     * @param string $hdl
     * @return TgDispatch
     */
    static function inst($hdl){
        static $objs = [];
        if (! isset($objs[$hdl])) {
            $objs[$hdl] = new self($hdl);
        }
        return $objs[$hdl];
    }


    function __construct($hdl){
        $this->hdl = $hdl;
    }


    //分析机器人收到的数据，@todo 根据$this->hdl区分分析过程，以便于机器人行为多样化的实现
    function analyze(array $update){
        if (isset($update['message'])) {
            $message = $update['message'];
            if (isset($message['reply_to_message']) && isset($message['text'])) {
                return 1;
            } elseif (isset($message['entities'][0]) && $message['entities'][0]['type'] == 'bot_command' && isset($message['text'])) {
                return 2;
            } elseif (isset($message['new_chat_member'])) {
                return 3;
            } elseif ($message['chat']['type'] == 'private' && isset($message['forward_from'])) {
                return 4;
            } elseif (isset($message['left_chat_member'])) {
                return 5;
            }
        }
    }


    //分派到具体的处理逻辑中，@todo 根据$this->hdl区分分派路由，以便机器人行为多样化的实现
    function dispatch($analyzeFeed, $update){
        $deal = TgDeal::inst($this->hdl);
        switch ($analyzeFeed) {
            case 1:
                @$result = $deal->onReply($update);
                break;
            case 2:
                $result = $deal->onCmd($update);
                break;
            case 3:
                $result = $deal->onNewChatMember($update);
                break;
            case 4:
                $result = $deal->onPrivateForwardFrom($update);
                break;
            case 5:
                $result = $deal->onLeftChatMember($update);
                break;
        }
        if (@$result) {
            $this->log(__CLASS__.__FUNCTION__, print_r($result, 1));
        }
    }


    protected function log($name, $content){
        $file = DI_LOG_PATH . $name . date('Y-m-d') . '.log';
        file_put_contents($file, $content, FILE_APPEND);
    }

}


//convert to super group
//{"update_id":812401076,"message":{"message_id":1,"from":{"id":533702151,"is_bot":false,"first_name":"\u63d2\u732a\u996d","username":"dd5766"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525261591,"migrate_from_chat_id":-275439610}}

//join in super group
//{"update_id":812401077,"message":{"message_id":6,"from":{"id":533702151,"is_bot":false,"first_name":"\u63d2\u732a\u996d","username":"dd5766"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262210,"new_chat_participant":{"id":580288862,"is_bot":true,"first_name":"\u7c89\u8272\u7684\u5927\u6bcd\u9e21","username":"pinkjjbot"},"new_chat_member":{"id":580288862,"is_bot":true,"first_name":"\u7c89\u8272\u7684\u5927\u6bcd\u9e21","username":"pinkjjbot"},"new_chat_members":[{"id":580288862,"is_bot":true,"first_name":"\u7c89\u8272\u7684\u5927\u6bcd\u9e21","username":"pinkjjbot"}]}}

//被回复
//{"update_id":812401078,"message":{"message_id":8,"from":{"id":533702151,"is_bot":false,"first_name":"\u63d2\u732a\u996d","username":"dd5766"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262460,"reply_to_message":{"message_id":7,"from":{"id":580288862,"is_bot":true,"first_name":"\u7c89\u8272\u7684\u5927\u6bcd\u9e21","username":"pinkjjbot"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262316,"text":"\u732a\u5c4c\u5305\u5df2\u4e0a\u684c\uff0c\u8bf7\u6162\u7528\uff01"},"text":"\u597d\u7684\uff0c\u64cd\u4f60\u5988\u903c\uff01"}}