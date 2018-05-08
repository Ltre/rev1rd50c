<?php

import('madelineproto/lib-dl');

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$me = $MadelineProto->get_self();

var_dump($me);die;

\danog\MadelineProto\Logger::log($me);

if (!$me['bot']) {
    $MadelineProto->messages->sendMessage(['peer' => '@danogentili', 'message' => "Hi!\nThanks for creating MadelineProto! <3"]);
    $MadelineProto->channels->joinChannel(['channel' => '@MadelineProto']);

    try {
        $MadelineProto->messages->importChatInvite(['hash' => 'https://t.me/joinchat/Bgrajz6K-aJKu0IpGsLpBg']);
    } catch (\danog\MadelineProto\RPCErrorException $e) {
    }

    $MadelineProto->messages->sendMessage(['peer' => 'https://t.me/joinchat/Bgrajz6K-aJKu0IpGsLpBg', 'message' => 'Testing MadelineProto!']);
}
echo 'OK, done!'.PHP_EOL;