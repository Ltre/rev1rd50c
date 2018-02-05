<?php

import('net/netUtils');

class RootDo extends DIDo {

    function post(){
        if (isPost()) {
            $d = [
                'article_id' => sha1(microtime(1).rand(0, 999)),
                'title' => arg('title') ?: '',
                'cover' => arg('cover') ?: '',
                'images' => arg('images') ?: [],
                'digest' => arg('digest') ?: '',
                'contents' => arg('contents') ?: '',
                'rooter_id' => 0,//@todo
                'editor' => arg('editor') ?: '',//A shy old driver
                'create_time' => time(),
                'update_time' => time(),
            ];
            if (empty($d['title']) || empty($d['cover']) || empty($d['digest']) || empty($d['contents']) || empty($d['editor'])) {
                die('fk!');
            }
            $ret = supertable('article')->insert($d);
            putjson($ret);
        } else {
            $this->stpl();
        }
    }



}