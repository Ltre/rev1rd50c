<?php
//未测试
class TgDo extends DIDo {

    //This is an webhook.
    function hk($secret){
        $tg = new Tg;
        $tg->hk($secret);
    }

    //To set webhook
    function setHk(){
        $tg = new Tg;
        $tg->setHk();
    }

}