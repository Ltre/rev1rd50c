<?php

class Tg extends DIInject {
	
	function onHk($bridge = []){
        echo 'onHk<br>';
        $time = date('Y-m-d H:i:s');
        $file = DI_LOG_PATH . 'tg_hk_log_' . date('Y-m-d') . '.txt';
        $link = fopen($file, 'a+');
        $msg = "=========================={$time}==========================\r\n";
        $postContent = print_r($_REQUEST, 1);
        @$msg .= "    {$_SERVER['SERVER_PROTOCOL']}    {$_SERVER['SERVER_NAME']}" . (80 == $_SERVER['SERVER_PORT'] ? '' : ':' . $_SERVER['SERVER_PORT']) . "{$_SERVER['REQUEST_URI']}    REFERER[{$_SERVER['HTTP_REFERER']}]    REMOTE_ADDR[{$_SERVER['REMOTE_ADDR']}]    REQUEST_METHOD[{$_SERVER['REQUEST_METHOD']}]      POST_CONTENT[{$postContent}]    $message\r\n";
        fwrite($link, $msg);
	}
	
}