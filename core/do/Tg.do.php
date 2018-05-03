<?php
//未测试
class TgDo extends DIDo {

    //This is an webhook.
    function hk($secret){
        die;
        $tg = new Tg;
        $update = file_get_contents( "php://input");
// $update = '{"update_id":812401070,"message":{"message_id":19,"from":{"id":462394947,"is_bot":false,"first_name":"\u6a39\u9928\u9577","username":"sgz134","language_code":"zh-cn"},"chat":{"id":-275439610,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"group","all_members_are_administrators":true},"date":1525248932,"text":"/jj@pinkjjbot","entities":[{"offset":0,"length":13,"type":"bot_command"}]}}';
// $update = '{"update_id":812401078,"message":{"message_id":8,"from":{"id":533702151,"is_bot":false,"first_name":"\u63d2\u732a\u996d","username":"dd5766"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262460,"reply_to_message":{"message_id":7,"from":{"id":580288862,"is_bot":true,"first_name":"\u7c89\u8272\u7684\u5927\u6bcd\u9e21","username":"pinkjjbot"},"chat":{"id":-1001389039341,"title":"\u673a\u5668\u4eba\u6d4b\u8bd5","type":"supergroup"},"date":1525262316,"text":"\u732a\u5c4c\u5305\u5df2\u4e0a\u684c\uff0c\u8bf7\u6162\u7528\uff01"},"text":"\u597d\u7684\uff0c\u64cd\u4f60\u5988\u903c\uff01"}}';
        $update = json_decode($update?:'{}', 1);
        $tg->hk($secret, $update);
    }

    //To set webhook
    function setHk(){
        $tg = new Tg;
        $tg->setHk();
    }

    function callMethod(){
        $tg = new Tg;
        dump($tg->callMethod(arg('method'), arg('params', [])));
    }

    function getMe(){
        $tg = new Tg;
        dump($tg->getMe());
    }

}