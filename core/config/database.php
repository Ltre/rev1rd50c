<?php

function __never_used_name_in_database(){
    $isLinux = !! preg_match('/linux/i', php_uname('s'), $matches);
    $setups = [];
    if ($isLinux) {
        class DIDBConfig {
            static $driver = 'DIMySQL';//驱动类
            static $host = '127.0.0.1';
            static $port = 3306;
            static $db = 'rev1rd50c';
            static $user = 'rev1rd50c';
            static $pwd = 'O27Ko4PHUF0OnHvo';
            static $table_prefix = 'csdr_';//表前缀
        }
        class DIMMCConfig {
            static $domain = 'rev1rd50c';
            static $host = '127.0.0.1';
            static $port = 11211;
        }
    } else {
        class DIDBConfig {
            static $driver = 'DIMySQL';//驱动类
            static $host = '127.0.0.1';
            static $port = 3306;
            static $db = 'rev1rd50c';
            static $user = 'root';
            static $pwd = 'ltre';
            static $table_prefix = 'csdr_';//表前缀
        }
        class DIMMCConfig {
            static $domain = 'rev1rd50c';
            static $host = '127.0.0.1';
            static $port = 11211;
        }
    }
}

__never_used_name_in_database();
