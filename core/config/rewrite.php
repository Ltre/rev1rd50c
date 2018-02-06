<?php
class DIRouteRewrite {
    
    /**
     * 自定义路由重写规则
     * 书写原则，特殊在前，通用在后
     * 详见：
     *      DIRoute::rewrite() @ __route.php
     */
    static $rulesMap = array(
        '://danmucopy.me' => 'danmu/search', //含有域名的配置最好放在最前面(经验之谈)
        //域名需要隐蔽配置，用加密字符串
        
        //文章详细页
        'article' => 'start/article',
        'article/<A>' => 'start/article/<A>',

        //通用入口
        '<D>' => '<D>/start',
        '<D>.htm' => '<D>/start',
        '<D>.html' => '<D>/start',
    );
    
    /**
     * 不需要重写的
     * 左侧为相对于脚本目录的URI
     * 右侧表示重写失败时是否终止程序
     * 这些规则不受常量DI_KILL_ON_FAIL_REWRITE影响
     */
    static $withoutMap = array(
        'index.php' => false,
        'index.html' => false,
        'index.htm' => false,
        'favicon.ico' => true,
    	'robots.txt' => true,
    );
    
}