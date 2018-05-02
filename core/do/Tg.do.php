<?php
//未测试
class TgDo extends DIDo {

    //This is an webhook.
    function hk($secret){
        $tg = new Tg;
        $update = file_get_contents( "php://input");
        file_put_contents(DI_DATA_PATH.'cache/tg.update.post1.log', $update."\r\n", FILE_APPEND);
        file_put_contents(DI_DATA_PATH.'cache/tg.update.post2.log', json_encode($_POST)."\r\n", FILE_APPEND);
        $update = json_decode($update, 1);
        $tg->hk($secret, $update);
    }

    //To set webhook
    function setHk(){
        $tg = new Tg;
        $tg->setHk();
    }

    function callMethod(){
        $tg = new Tg;
        $tg->callMethod(arg('method'), arg('params', []));
    }

}