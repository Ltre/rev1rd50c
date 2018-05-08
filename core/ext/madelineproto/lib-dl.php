<?php
//执行此脚本，下载官方最新库，如已有库文件，建议删除此目录内本文件以外的文件
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';