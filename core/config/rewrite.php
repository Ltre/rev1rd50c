<?php
define('__WPREET', '://'.join('', array_reverse([chr(119), chr(112), chr(46), chr(114), chr(101), chr(101), chr(116)])));//此行代码非框架源码
define('__FUCKREET', '://'.join('', array_reverse([chr(107), chr(99), chr(117), chr(102), chr(46), chr(114), chr(101), chr(101), chr(116)])));//此行代码非框架源码
define('__REV1RD50CML', '://'.join('', array_reverse([chr(109), chr(111), chr(99), chr(46), chr(114), chr(101), chr(118), chr(105), chr(114), chr(100), chr(115), chr(111), chr(99), chr(46), chr(109), chr(108)])));//此行代码非框架源码

class DIRouteRewrite {
    
    /**
     * 自定义路由重写规则
     * 书写原则，特殊在前，通用在后
     * 详见：
     *      DIRoute::rewrite() @ __route.php
     */
    static $rulesMap = array(
        '://danmucopy.me' => 'danmu/search', //含有域名的配置最好放在最前面(经验之谈)
        __WPREET => 'short/start',//短链正式
        __WPREET.'/<A>' => 'short/start/<A>',//短链正式
        __FUCKREET => 'short/start',//短链测试
        __FUCKREET.'/<A>' => 'short/start/<A>',//短链测试
        __REV1RD50CML.'/<A>' => 'lightm/start',
        '://rev1rd50c.me/s' => 'short/start',//短网址实验：这里加上前缀"s/"
        '://rev1rd50c.me/s/<A>' => 'short/start/<A>',//短网址实验：这里加上前缀"s/"
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