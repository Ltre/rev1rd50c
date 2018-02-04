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

}