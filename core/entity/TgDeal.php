<?php

/**
 * tg分派后的具体处理逻辑存放
 */
class TgDeal extends DIEntity {

    protected $hdl;

    /**
     * 创建一个机器人对应的TgDeal实例
     *
     * @param string $hdl
     * @return TgDeal
     */
    static function inst($hdl){
        static $objs = [];
        if (! isset($objs[$hdl])) {
            $objs[$hdl] = new self($hdl);
        }
        return $objs[$hdl];
    }


    function __construct($hdl){
        $this->hdl = $hdl;
    }


    function onReply(array $update){
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $from = $message['from'];
        $tg = Tg::inst($this->hdl);
        $me = $tg->getMe();
        $tg->log("get reply_to_message.from.id: {$message['reply_to_message']['from']['id']}");
        $tg->log("get me.id: {$me['id']}");
        if (@$message['reply_to_message']['from']['id'] == $me['id']) {
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => TgTest::sample($chat['id'], $from['id'], $text),
                'reply_to_message_id' => $message['message_id'],
            ]);
        }
    }


    function onCmd(array $update){
        $message = $update['message'];
        $text = $message['text'];
        $chat = $message['chat'];
        $from = $message['from'];
        $tg = Tg::inst($this->hdl);
        $me = $tg->getMe();
        $responseText = null;
        if (preg_match('/^\/(\w+)(@'.$me['username'].')?/', $text, $matches)) {
            if ($this->hdl == 'pinkjj') {
                switch ($matches[1]) {
                    case 'jj':
                        $list = ['轻点，疼，对，就这样，嗯.. 嗯.. 啊~~ 昂~~~', '快进来~~', '叫你妹啊!'];
                        $responseText = $list[rand(0, count($list)-1)];
                        break;
                    case 'iq':
                        @$name = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');
                        $mention = "[{$name}](tg://user?id={$from['id']})";
                        $responseText = "`` {$mention} 的当前智商是：".intval(rand(0, 200));
                        break;
                }
            } elseif ($this->hdl == 'shabisb') {
                switch ($matches[1]) {
                    case 'imsb': 
                        @$name = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');
                        $mention = "[{$name}](tg://user?id={$from['id']})";
                        $list = [
                            "发出了“我是傻逼”的声音！", "说：我是傻逼.", "叫道：我是傻大逼！", "辩称：和我比傻逼，你输定了！", 
                            "反问道：你有我傻逼？", "小声逼逼道：我是新来的傻逼", "说：我从来没见过比我更傻逼的..", 
                            "喊道：狗群主，出来骂人了！", "祝福道：傻逼群友们，我爱你们", "号召：让我们实现共同傻逼！",
                            "说：我是善于发现高级傻逼的伯乐", "强调：一定不要忘记傻逼斗争！", "明确指出：目前我们还处于傻逼主义初级阶段，并将长期处于该阶段.",
                            "说：大家都是傻逼，逼逼平等", "重申：世界没有最傻逼的傻逼，所有的傻逼的傻逼程度是一样的",
                            "这样强调：一定不要忘记吃屎斗争，敢于把第一个说吃屎的往屎里打压", "说：进来就要当一名好傻逼，做人民的好逼志",
                            "嚎叫道：我已修成正果，进入傻逼高级形态，能在粪池倒立一晚上", "说过：一日为傻逼，终身为傻逼",
                            "一把抢下送屎员的大便，说：你们谁都别跟我抢！", "听闻有人喊有新屎刚上，热腾腾的，便叫道：给我留点儿！",
                            "强调：操逼不分男女，不管男逼还是女逼，只要是傻逼，就是好逼", "指出：践行特色傻逼主义，深化傻逼思想建设",
                            "说：你根本不是傻逼！", "说：来傻逼群，一条发傻致穷的捷径！",
                        ];
                        $index = rand(0, count($list) - 1);
                        $responseText = "`` {$mention} {$list[$index]}";
                        break;
                }
            } elseif ($this->hdl == 'sbsww') {
                switch ($matches[1]) {
                    case 'sbsww':
                        $responseText = "`` [灵堂追悼](https://t.me/yangwei_club) ！";
                        break;
                    case 'photo':
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgADBQAD6acxGxOz6FdsrpVaesMTfAgp1TIABMVuVmZyKLoqv6AAAgI',
                            'caption' => "`` 孙伟伟(萎萎)近照\n [孙萎萎大号](tg://user?id=515656720)\n [孙萎萎小号1](tg://user?id=524008226)\n [孙萎萎小号2](tg://user?id=476290631)\n [孙萎萎小号3](tg://user?id=574470817)\n [孙萎萎小号4](tg://user?id=485652193)\n [孙萎萎小号5](tg://user?id=597554665)",
                            'parse_mode' => 'Markdown',
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                }
            } elseif ($this->hdl == 'ganmom') {
                switch ($matches[1]) {
                    case 'gan':
                        $responseText = "`` 这个是脏话，[小孩子](tg://user?id={$chat['id']})不可以乱讲！";
                        break;
                    case 'jby':
                        $responseText = "`` 好了，[你](tg://user?id={$chat['id']})不要再讲了！";
                        break;
                    case 'fuckstop':
                        return $tg->callMethod('sendVideo', [
                            'chat_id' => $chat['id'],
                            'video' => 'BAADBQADSAADoq0ZVetD36V-kGSEAg',//数据摘自：[{"update_id":812467065,"message":{"message_id":724,"from":{"id":566169252,"is_bot":false,"first_name":"\u57fa\u4f6c\u592b\u98de\u8247"},"chat":{"id":566169252,"first_name":"\u57fa\u4f6c\u592b\u98de\u8247","type":"private"},"date":1529066936,"video":{"duration":10,"width":304,"height":240,"mime_type":"video/mp4","thumb":{"file_id":"AAQBABNaSAwwAASfaYrcanJhJg2BAAIC","file_size":1117,"width":90,"height":71},"file_id":"BAADAQADSAADM_chRQ9Wjqelm51CAg","file_size":329360}}}]
                            'caption' => "`` 你就是个[鸡掰](tg://user?id={$chat['id']})！",
                            'parse_mode' => 'Markdown',
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                    case 'dream': 
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgADBQAD6acxGxOz6FdsrpVaesMTfAgp1TIABMVuVmZyKLoqv6AAAgI',
                            'caption' => "恶臭来袭！",
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                        break;
                }
            } elseif ($this->hdl == 'kowaii') {
                switch ($matches[1]) {
                    case 'song':
                        @$name = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');
                        $mention = "[{$name}](tg://user?id={$from['id']})";
                        $responseText = "`` {$mention}";
                        $responseText .= "、あなたが選ばれたので、準備をしてください！";
                    break;
                }
            }
        }
        if ($responseText) {
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => $responseText,
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
        }
    }


    function onNewChatMember(array $update){
        $message = $update['message'];
        $chat = $message['chat'];
        $member = $message['new_chat_member'];
        $tg = Tg::inst($this->hdl);
        if ($this->hdl == 'pinkjj' && $chat['id'] == '-1001377141307') {//傻逼群
            @$name = TgUtil::specialTextFilter($member['first_name'].$member['last_name'], 'Markdown');
            return $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => "`` 欢迎新傻逼: [{$name}](tg://user?id={$member['id']}).\n欢迎费用：*10傻币/次*（代扣100智商，自动兑换傻币支付）",
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
        }
    }


    function onPrivateForwardFrom(array $update){
        $message = $update['message'];
        $ffw = $message['forward_from'];
        $tg = Tg::inst($this->hdl);
        if ($this->hdl == 'pinkjj') {
            @$name = TgUtil::specialTextFilter($ffw['first_name'].$ffw['last_name']);
            // @$response1 = "`` id: {$ffw['id']}\n first: ".TgUtil::specialTextFilter($ffw['first_name'])."\n last: ".TgUtil::specialTextFilter($ffw['last_name'])."\n username: {$ffw['username']}\n is_bot: {$ffw['is_bot']}\n [{$name}](tg://user?id={$ffw['id']})";
            @$response1 = "id: {$ffw['id']}\nfirst: {$ffw['first_name']}\nlast: {$ffw['last_name[']}\nusername: {$ffw['username']}\nis_bot: {$ffw['is_bot']}";
            @$response2 = "``[{$name}](tg://user?id={$ffw['id']})";
            //返回多行详细信息
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => $response1,
                'reply_to_message_id' => $message['message_id'],
                // 'parse_mode' => 'Markdown',
            ]);
            usleep(500);
            //返回mention
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => $response2,
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
            usleep(500);
            //返回mention前的字符串
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => $response2,
                'reply_to_message_id' => $message['message_id'],
            ]);
        }
    }


    function onLeftChatMember(array $update){
        $message = $update['message'];
        $member = $message['left_chat_member'];
        $chat = $message['chat'];
        $tg = Tg::inst($this->hdl);
        if (in_array($this->hdl, ['shabisb', 'pinkjj']) && $chat['id'] == '-1001377141307') {//傻逼群
            @$name = TgUtil::specialTextFilter($member['first_name'].$member['last_name'], 'Markdown');
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => "有一个傻逼：[{$name}](tg://user?id={$member['id']}) 已滚出，扣除100傻币所得（代扣1000智商），祝ta不能融入非傻逼界！",
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
        }
    }
    
}