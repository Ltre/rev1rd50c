<?php

class StartDo extends DIDo {

    function start(){
        $p = arg('p') ?: 1;
        $limit = arg('limit') ?: 10;
        $scope = arg('scope') ?: 10;
        $ret = supertable('article')->seniorSelect([
            'limitBy' => [$p, $limit, $scope],
            'listable' => true,
            'pageable' => true,
        ]);
        $this->list = $ret['list'];
        $this->pages = $ret['pages'];
        $this->stpl();
    }

    function article($articleId){
        $article = supertable('article')->find(['article_id' => $articleId]);
        if (empty($article)) {
            dispatch(DI_PAGE_404);
        }
        $article['images'] = empty($article['images']) ? [] : unserialize($article['images']);
        $this->article = $article;
        $this->stpl();
    }

}