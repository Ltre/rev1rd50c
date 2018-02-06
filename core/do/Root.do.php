<?php

import('net/netUtils');

class RootDo extends DIDo {

    function post(){
        if (isPost()) {
            $d = [
                'article_id' => sha1(microtime(1).rand(0, 999)),
                'title' => arg('title') ?: '',
                'cover' => arg('cover') ?: '',
                'images' => arg('images') ?: '',
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
            //$images = str_split("\n", $d['images']);
            $images = explode("\r\n", $d['images']);
            $imagesArr = [];
            foreach ($images as $v) $imagesArr[] = trim($v);
            $d['images'] = serialize($imagesArr);
            $ret = supertable('article')->insert($d);
            putjson($ret);
        } else {
            $this->stpl();
        }
    }


    function get($articleId){
        $one = supertable('article')->find(['article_id' => $articleId]);
        putjson($one);
    }


    function showList($p = 1, $limit = 10){
        $scope = 10;
        $list = supertable('article')->select([], '*', 'create_time DESC', [$p, $limit, $scope]);
        $this->list = $list;
        $this->pages = supertable('article')->page;
        $this->p = $p;
        $this->limit = $limit;
        $this->stpl();
    }


}