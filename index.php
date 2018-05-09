<?php
define('BASE_DIR', dirname(__FILE__).'/');
define('CLI_DEBUG', 0);
CLI_DEBUG && require 'cli_debug.php';//启用CLI调试
require 'core/base/__include.php';