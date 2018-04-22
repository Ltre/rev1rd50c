<?php
/**
 * 参照__env.php建议，按己所需，重新定制特性
 */

define('DI_IO_RWFUNC_ENABLE', true);
define('DI_ROUTE_REWRITE', true);
define('DI_SMARTY_DEFAULT', false);//暂时所有环境不默认采用smarty
define('DI_PDO_FETCH_TYPE', PDO::FETCH_ASSOC);//使用PDO获取查询结果时，本项目选择返回的数据格式
define('DI_SMARTY_LEFT_DELIMITER', '{~');
define('DI_SMARTY_RIGHT_DELIMITER', '~}');
define('DI_SESSION_PREFIX', 'csdr_');

function __never_used_name_in_define(){
    $isLinux = !! preg_match('/linux/i', php_uname('s'), $matches);
    $setups = [];
    if ($isLinux) {
		define('DI_DEBUG_MODE', true);
    } else {
		define('DI_DEBUG_MODE', true);
    }
}

__never_used_name_in_define();
