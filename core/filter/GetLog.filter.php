<?php
class GetLogFilter implements DIFilter {

    function doFilter() {
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'get')) {
            $msg = var_export($_REQUEST, true);
            $this->_log($msg);
        }
    }

    protected function _log($message) {
        $time = date('Y-m-d H:i:s');
        $file = DI_LOG_PATH . 'getlog_' . date('Y-m-d') . '.txt';
        $link = fopen($file, 'a+');
        $msg = "=========================={$time}==========================\r\n";
        @$msg .= "    {$_SERVER['SERVER_PROTOCOL']}    {$_SERVER['SERVER_NAME']}" . (80 == $_SERVER['SERVER_PORT'] ? '' : ':' . $_SERVER['SERVER_PORT']) . "{$_SERVER['REQUEST_URI']}    REFERER[{$_SERVER['HTTP_REFERER']}]    REMOTE_ADDR[{$_SERVER['REMOTE_ADDR']}]    REQUEST_METHOD[{$_SERVER['REQUEST_METHOD']}]    $message\r\n";
        fwrite($link, $msg);
    }

}