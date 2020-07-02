<?php

/**
 * tg分派后的具体处理逻辑存放
 * @todo 涉及发图的代码，可以封装下，缓存tg的文件ID，文件ID失效时则重新发图，并再次缓存文件ID
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


    function onGroupMessage(array $update){
        $tg = Tg::inst($this->hdl);
        $message = $update['message'];
        $chat = $message['chat'];
        $from = $message['from'];
        @$fromText = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');
        $fromMention = "[{$fromText}](tg://user?id={$from['id']})";

        if ($this->hdl == 'pussy') {
            $saveKey = $from['id'];
            $saveFile = DI_DATA_PATH."group.{$chat['id']}.reCaptcha";
            @$data = json_decode(trim((file_get_contents($saveFile) ?: '{}')), 1);
            // $tg->log(json_encode(compact('saveKey', 'saveFile', 'data')));//debug
            if (@$data[$saveKey]) { //识别为入群校验模式，并删除验证消息
                $isOvertime = time() - $data[$saveKey]['time'] > 120;
                $isAnsError = trim($message['text']) != $data[$saveKey]['answer'];
                if ($isOvertime || $isAnsError) {
                    @$tg->callMethod('kickChatMember', [
                        'chat_id' => $chat['id'],
                        'user_id' => $from['id'],
                        'until_date' => 86400,//群员自己主动触发的被踢操作，将设置更长的封禁时间
                    ]);
                    $succ = false;
                    $tip = $isOvertime ? '超时' : '失败';
                } else {
                    $succ = true;
                    $tip = '通过';
                }
                @$tg->callMethod('deleteMessage', [
                    'chat_id' => $chat['id'],
                    'message_id' => $data[$saveKey]['msgId'],
                ]);
                unset($data[$saveKey]);
                file_put_contents($saveFile, json_encode($data));
                list ($ok, $resp) = $tg->callMethod('sendMessage', [
                    'chat_id' => $chat['id'],
                    'text' => "{$fromMention} 入群校验{$tip}",
                    'reply_to_message_id' => $message['message_id'],
                    'parse_mode' => 'Markdown',
                ]);
                //至此校验完毕，后续执行消息延时清理工作
                if ($ok) {
                    sleep(2);
                    @$tg->callMethod('deleteMessage', [//清理机器人发的校验结果通知
                        'chat_id' => $chat['id'],
                        'message_id' => $resp['result']['message_id'],
                    ]);
                    @$tg->callMethod('deleteMessage', [//清理入群人发的校验答案
                        'chat_id' => $chat['id'],
                        'message_id' => $message['message_id'],
                    ]);
                }
            } else {//其他情况（非入群验证模式）
                //... 
            }
        }
    }


    function onAnyWhere(array $update){
        $tg = Tg::inst($this->hdl);
        $message = $update['message'];
        $chat = $message['chat'];
        $from = $message['from'];
        @$fromText = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');

        @$tg->callMethod('sendMessage', [
            'chat_id' => '-195000192',//名称：消息收集筒
            'text' => print_r($update, 1),
        ]);

        // return @$tg->callMethod('sendMessage', [
        //     'chat_id' => '-195000192',//名称：消息收集筒
        //     'parse_mode' => 'Markdown',
        //     'text' => TgUtil::specialTextFilter(join("\n", [
        //         "update_id: {$update['update_id']}",
        //         "update_id: {$update['update_id']}",
        //         "from: {$fromText} " . ($from['is_bot'] ? '*机器人*' : '') . ", ID={$from['id']}",
        //         "chat: *『群组or频道』*{$chat['title']}, ID={$chat['id']}, TYPE={$chat['type']}",
        //         "date: UTC," . date('Y-m-d H:i:s', $message['date']),
        //         "text: {$message['text']}",
        //     ], 'Markdown')),
        // ]);

        //在逻辑最后，可延后删除bot命令
        if (preg_match('/^\/(\w+)(@[_\w]+)?/', $message['text'])) {
            sleep(1);
            @$tg->callMethod('deleteMessage', [
                'chat_id' => $chat['id'],
                'message_id' => $message['message_id'],
            ]);
        }
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
        if (@$message['reply_to_message']['from']['id'] == $me['id']) {//这个分支才是回复给机器人自己的消息
            if ($this->hdl == 'kowaii') {
                @$name = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');
                $mention = "[{$name}](tg://user?id={$from['id']})";
                return $tg->callMethod('sendMessage', [
                    'chat_id' => $chat['id'],
                    'text' => "`` {$mention}、私と話をするな、そうでないと結果が悪い.",
                    'reply_to_message_id' => $message['message_id'],
                    'parse_mode' => 'Markdown',
                ]);
            } elseif ($this->hdl == 'pussy') {
                return $tg->callMethod('sendMessage', [
                    'chat_id' => $chat['id'],
                    'text' => TgTest3::sample($chat['id'], $from['id'], $text),
                    'reply_to_message_id' => $message['message_id'],
                ]);
            } elseif ($this->hdl == 'eosgetdice') {
                return $tg->callMethod('sendMessage', [
                    'chat_id' => $chat['id'],
                    'text' => TgTest2::sample($chat['id'], $from['id'], $text),
                    'reply_to_message_id' => $message['message_id'],
                ]);
            } else {
                return $tg->callMethod('sendMessage', [
                    'chat_id' => $chat['id'],
                    'text' => TgTest::sample($chat['id'], $from['id'], $text),
                    'reply_to_message_id' => $message['message_id'],
                ]);
            }
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
                    case 'tagimg':
                        import('net/dwHttp');
                        $http = new dwHttp;
                        $api = 'http://'.ltreDeCrypt("ucu5)VYlwt_7_Tr2D5Z6Ll.U(D*oUR-6Lx!F.k*H@Cg3.LRH(dYv!Ol5M7hf(4-Ev1'1FbNtXFt7.0NjQn");
                        $tg->log('file:'.__FILE__.', line:'.__LINE__.', Prepare request api:'.$api);
                        $ret = $http->get($api, 20);
                        $feed = json_decode($ret?:'[]', 1);
                        $tg->log('file:'.__FILE__.', line:'.__LINE__.', After request api:'.$api.', ret is:'.$ret.', feed is:'.print_r($feed, 1));
                        if (isset($feed['code']) && $feed['code'] == 0) {
                            @$url = $feed['data']['url'] ?: null;//data部分可能为null，故url也可能取null
                            if ($url) {//@todo 此处将改造为 TgUtil::sendImageOrAnimateByTuku() 方式
                                $caption = "tuId={$feed['data']['tuId']}\nTags: " . join('; ', $feed['data']['tags']);
                                $headers = get_headers($url, 1);
                                if (in_array($headers['Content-Type'], ['image/gif', 'video/mp4'])) {
                                    return $tg->callMethod('sendVideo', [
                                        'chat_id' => $chat['id'],
                                        'video' => $url,
                                        'caption' => 'gif|mp4: '.$caption,
                                        'reply_to_message_id' => $message['message_id'],
                                    ]);
                                } elseif (preg_match('/^image\//i', $headers['Content-Type'])) {
                                    return $tg->callMethod('sendPhoto', [
                                        'chat_id' => $chat['id'],
                                        'photo' => $url,
                                        'caption' => $caption,
                                        'reply_to_message_id' => $message['message_id'],
                                    ]);
                                } else {
                                    $responseText = "Unsupport MIMETYPE：{$headers['Content-Type']}";
                                }
                            }
                        }
                        break;
                    case 'hideimg':
                        if ($chat['type'] != 'private' || $chat['id'] != '462394947') {
                            $responseText = '你没有权限';
                        } else {
                            $tg->log('file:'.__FILE__.', line:'.__LINE__.', /hideimg regex:'.'/^\/hideimg(@'.$me['username'].')?\s+(\d+)\s*$/, message:'.$message['text']);
                            if (preg_match('/^\/hideimg(@'.$me['username'].')?\s+(\d+)\s*$/', $message['text'], $argMatches)) {
                                $tuId = $argMatches[2];//获取命令里指定的图id
                                $tg->log('file:'.__FILE__.', line:'.__LINE__.', /hideimg regex matches:'.print_r($argMatches, 1));
                                $tg->log('file:'.__FILE__.', line:'.__LINE__.', /hideimg tuId:'.$tuId);
                                import('net/dwHttp');
                                $http = new dwHttp;
                                $ret = $http->get('http://'.ltreDeCrypt("O9QO(4.UTGSAyhWhtr.8Pn!y'1~xCi.QQu'1v1Ur").$tuId, 20);
                                $tg->log('file:'.__FILE__.', line:'.__LINE__.', /hideimg http_req_ret not false:'.(false!==$ret?'yes':'no'));
                                //@todo: 发消息告知执行完毕。。。
                                $responseText = 'tuId='.$tuId.', 执行完毕';
                            } else {
                                $responseText = 'tuId='.$tuId.', 参数错误';
                            }
                        }
                        break;
                    case 'tu':
                        if ($chat['type'] != 'private' || $chat['id'] != '462394947') {
                            $responseText = '你没有权限';
                        } else {
                            $tg->log('file:'.__FILE__.', line:'.__LINE__.', /tu regex:'.'/^\/tu(@'.$me['username'].')?\s+(\d+)\s*$/, message:'.$message['text']);
                            if (preg_match('/^\/tu(@'.$me['username'].')?\s+(\d+)(-\d+)?\s*$/', $message['text'], $argMatches)) {
                                $tuId = $argMatches[2];//获取命令里指定的图id
                                $limit = abs((int)$argMatches[3]);//可选的最大输出图个数, 限制20（从第一张图开始，按id递增尝试获取）
                                $limit = $limit ?: 1;
                                while ($limit -- && $limit <= 20) {
                                    $tg->log('file:'.__FILE__.', line:'.__LINE__.', /tu regex matches:'.print_r($argMatches, 1));
                                    $tg->log('file:'.__FILE__.', line:'.__LINE__.', /tu tuId:'.$tuId);
                                    import('net/dwHttp');
                                    $http = new dwHttp;
                                    $ret = $http->get('http://'.ltreDeCrypt("Rc*~@0obk2Ldmbx4JvGq!na8~1VrGd!nYW.8RpIe.0Lh!Itbs6.0-C@x").$tuId, 20);
                                    $tg->log('file:'.__FILE__.', line:'.__LINE__.', /tu http_req_ret not false:'.(false!==$ret?'yes':'no'));
                                    if (false !== $ret) {
                                        $feed = json_decode($ret, 1);
                                        if (isset($feed['data']['url'])) {
                                            $url = $feed['data']['url'];
                                            $headers = get_headers($url, 1);
                                            if (in_array($headers['Content-Type'], ['image/gif', 'video/mp4'])) {//@debug: 测试增加video/mp4的情况，如有错误，则回退至仅判断image/gif
                                                $tg->callMethod('sendVideo', [
                                                    'chat_id' => $chat['id'],
                                                    'video' => $url,
                                                    'reply_to_message_id' => $message['message_id'],
                                                ]);
                                            } elseif (preg_match('/^image\//i', $headers['Content-Type'])) {
                                                $tg->callMethod('sendPhoto', [
                                                    'chat_id' => $chat['id'],
                                                    'photo' => $url,
                                                    'reply_to_message_id' => $message['message_id'],
                                                ]);
                                            }
                                        }
                                    }
                                    //ID递增
                                    $tuId ++;
                                    usleep(200);
                                }
                                return;
                            }
                        }
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
                            'photo' => 'AgADBQADA6gxG-SemFYQq1o23FVywCmw1jIABLqe8C7iL-o1uKgAAgI',//备用：AgADBQADA6gxG-SemFYQq1o23FVywCmw1jIABDexokh0wUBfu6gAAgI， AgADBQADA6gxG-SemFYQq1o23FVywCmw1jIABETVZ7UbToTXuqgAAgI，AgADBQADA6gxG-SemFYQq1o23FVywCmw1jIABGFwtnyNuEz0uagAAgI
                            'caption' => "`` 孙伟伟(萎萎)近照\n [孙萎萎大号](tg://user?id=515656720)\n [孙萎萎小号1](tg://user?id=524008226)\n [孙萎萎小号2](tg://user?id=476290631)\n [孙萎萎小号3](tg://user?id=574470817)\n [孙萎萎小号4](tg://user?id=485652193)\n [孙萎萎小号5](tg://user?id=597554665)",
                            'parse_mode' => 'Markdown',
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                }
            } elseif ($this->hdl == 'ganmom') {
                switch ($matches[1]) {
                    case 'help':
                        $responseText = "我叫甘霖娘，学生，24岁。\nhelp - 我叫甘霖娘\ngan - 甘霖娘\njby - 鸡掰\nfuckstop - 好了，你不要再讲了\ndream - 做个好梦\nnimabi - 我爱你\neatmyjb - 拔屌吧兄嘚\nhungry - 我饿了\ncherry - 来盘车厘子\nshit - 野兽先辈";
                        break;
                    case 'gan':
                        $responseText = "`` 这个是脏话，[小孩子](tg://user?id={$from['id']})不可以乱讲！";
                        break;
                    case 'jby':
                        $responseText = "`` 好了，[你](tg://user?id={$from['id']})不要再讲了！";
                        break;
                    case 'fuckstop':
                        return $tg->callMethod('sendVideo', [
                            'chat_id' => $chat['id'],
                            'video' => 'BAADBQADSAADoq0ZVetD36V-kGSEAg',//数据摘自：[{"update_id":812467065,"message":{"message_id":724,"from":{"id":566169252,"is_bot":false,"first_name":"\u57fa\u4f6c\u592b\u98de\u8247"},"chat":{"id":566169252,"first_name":"\u57fa\u4f6c\u592b\u98de\u8247","type":"private"},"date":1529066936,"video":{"duration":10,"width":304,"height":240,"mime_type":"video/mp4","thumb":{"file_id":"AAQBABNaSAwwAASfaYrcanJhJg2BAAIC","file_size":1117,"width":90,"height":71},"file_id":"BAADAQADSAADM_chRQ9Wjqelm51CAg","file_size":329360}}}]
                            'caption' => "`` 你就是个[鸡掰](tg://user?id={$from['id']})！",
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
                    case 'nimabi':
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgADBQADvagxG9NA2FebtqyA5sBoIMN_3zIABCI5TEP5R0praSoAAgI',
                            'caption' => "爱我请吸我！",
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                    case 'eatmyjb':
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgADBQADQ6gxG84u2VfVyPs7PDTSBEVX2zIABJJJcgYGdZbceMIBAAEC',
                            'caption' => "骚年，我的大不大？",
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                    case 'hungry':
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgADBQADbKgxG5GZUVeNC4beZXhtvP5U9jIABC1OWVGp4wOH5SkBAAEC',
                            'caption' => '咖喱饭做好了！',
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                    case 'cherry':
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgADBQADfKgxG00_UFQMtJfBp3glZlZY9jIABFr3hGu8OSoHFMgBAAEC',
                            'caption' => '只剩下车厘子干了～',
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                    case 'shit'://取一张shit
                        import('net/dwHttp');
                        $api = 'http://'.ltreDeCrypt("RR99hhZZNM.vb8@0w3*L!G)Nt8ZTCA'9DaDlMvy6WjUR~1@yQAXN.FH2if)5qgC9VLM9)CB9k2M1w3Am~L!nYW.8RpYu'1@wn3JrTx'1w2Da");
                        $http = new dwHttp;
                        $ret = $http->get($api, 20);
                        // $tg->log("ganmom->shit >> api: {$api}");//debug
                        // $tg->log("shit->shit >> ret of api: {$ret}");//debug
                        @$ret = json_decode($ret?:'[]', 1);
                        if (@$ret['code'] == 0) {
                            $shitList = $ret['data'];
                            $shitLen = count($shitList);
                            $shit = $shitList[mt_rand(0, $shitLen)];
                            $tg->log("ganmom->shit >> shitLen: {$shitLen}");//debug
                            $tg->log("ganmom->shit >> shitIndex: ".mt_rand(0, $shitLen)."\r\n");//debug
                            $tg->log("ganmom->shit >> shit: ".print_r($shit, 1)."\r\n");//debug
                            $tg->log("ganmom->shit >> mt_rand: ".print_r([mt_rand(0, $shitLen), $shitLen/2], 1)."\r\n");//debug
                            if (mt_rand(0, $shitLen) > $shitLen/12) {
                                return TgUtil::sendImageOrAnimateByTuku($tg, $chat, $shit, ['reply_to_message_id' => $message['message_id']]);
                            }
                        }
                        $responseText = 'Can\'t get any shit!';
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
            } elseif ($this->hdl == 'eosgetdice') {
                switch ($matches[1]) {
                    case 'test':
                        @$name = TgUtil::specialTextFilter($from['first_name'].$from['last_name'], 'Markdown');
                        $mention = "[{$name}](tg://user?id={$from['id']})";
                        $responseText = "`` Hello, {$mention}.";
                        $responseText .= "This is a command for testing. Do you want to achieve your own robot? Please refer to: [this link](https://core.telegram.org/bots/api#sendmessage)";
                        break;
                }
            } elseif ($this->hdl == 'pussy') {
                switch ($matches[1]) {
                    case 'pussy'://取一张pussy
                        import('net/dwHttp');
                        $api = 'http://'.ltreDeCrypt("zzbbNNTTed!pWT-6z6TBxbDlAfDx-(!2Vnz7y6SoNoK7DA@0Om!MHxKh_sUR_7a0y5FvPc~yKij1.n!zEqCm(pYW)5-ED9'1!yEk!KYC.0*zx4");
                        $http = new dwHttp;
                        $ret = $http->get($api, 20);
                        // $tg->log("pussy->pussy >> api: {$api}");//debug
                        // $tg->log("pussy->pussy >> ret of api: {$ret}");//debug
                        @$ret = json_decode($ret?:'[]', 1);
                        if (@$ret['code'] == 0) {
                            $pussyList = $ret['data'];
                            $pussyLen = count($pussyList);
                            $pussy = $pussyList[mt_rand(0, $pussyLen)];
                            $tg->log("pussy->pussy >> pussyLen: {$pussyLen}");//debug
                            $tg->log("pussy->pussy >> pussyIndex: ".mt_rand(0, $pussyLen)."\r\n");//debug
                            $tg->log("pussy->pussy >> pussy: ".print_r($pussy, 1)."\r\n");//debug
                            $tg->log("pussy->pussy >> mt_rand: ".print_r([mt_rand(0, $pussyLen), $pussyLen/2], 1)."\r\n");//debug
                            if (mt_rand(0, $pussyLen) > $pussyLen/12) {
                                return TgUtil::sendImageOrAnimateByTuku($tg, $chat, $pussy, ['reply_to_message_id' => $message['message_id']]);
                            } else {
                                return $tg->callMethod('sendVideo', [
                                    'chat_id' => $chat['id'],
                                    'video' => 'BAADBQADPgAD366xVQgEF_q8PL0vAg',//数据摘自：[{"update_id":56826587,"message":{"message_id":726,"from":{"id":462394947,"is_bot":false,"first_name":"\u6a39\u9928\u9577","username":"rip_you_bot","language_code":"zh-hans"},"chat":{"id":462394947,"first_name":"\u6a39\u9928\u9577","username":"rip_you_bot","type":"private"},"date":1547022447,"video":{"duration":66,"width":480,"height":480,"mime_type":"video/mp4","thumb":{"file_id":"AAQFABPdp98yAASwZmk6EsOhq-8aAAIC","file_size":3394,"width":90,"height":90},"file_id":"BAADBQADPgAD366xVQgEF_q8PL0vAg","file_size":7447783}}}]
                                    'caption' => "`` Fuck [me](tg://user?id={$from['id']})！",
                                    'parse_mode' => 'Markdown',
                                    'reply_to_message_id' => $message['message_id'],
                                ]);
                            }
                        } else {
                            $responseText = 'Can\'t get any pussy!';
                        }
                        break;
                }
            } elseif ($this->hdl == 'cnmb') {
                switch ($matches[1]) {
                    case 'ppmtb':
                        if (preg_match('#^/ppmtb(@'.$me['username'].')?\s+([\w_]+)/(-?\d+|[_\w]+)\s+(.+)$#', $message['text'], $argMatches)) {
                            $botHdl = $argMatches[2];
                            $tgUserId = $argMatches[3];//支持群或用户，值可正负数ID、或字母数字下划线
                            $pushMsg =  $argMatches[4];
                            $tg = Tg::inst($botHdl);
                            return $tg->callMethod('sendMessage', [
                                'chat_id' => $tgUserId,
                                'text' => $pushMsg,
                            ]);
                        } else {
                            return Tg::inst($this->hdl)->callMethod('sendMessage', [
                                'chat_id' => $chat['id'],
                                'text' => 'param err!',
                                'reply_to_message_id' => $message['message_id'],
                            ]);
                        }
                        break;
                    case 'help':
                        return Tg::inst($this->hdl)->callMethod('sendMessage', [
                            'chat_id' => $chat['id'],
                            'text' => "ppmtb命令: 利用机器人向单个订阅者发送文字信息。\n -- 用法：/ppmtb yourbot_hdl/tgUserId textmsg\n -- 例如：/ppmtb pinkjj/123456 这是文字信息",
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                        break;
                }
            } elseif ($this->hdl == 'yui') {
                switch ($matches[1]) {
                    case 'wau': //wau
                        return $tg->callMethod('sendVideo', [
                            'chat_id' => $chat['id'],
                            //AQADpdeBanQAAzdXAAI
                            'video' => 'AAMCBQADGQEAAwZevmyAccg9yKQ3xoEsfo0zpNS8zAACmQADeXz4VVtlRJanpm1QpdeBanQAAwEAB20AAzdXAAIZBA',//数据摘自：[{"update_id":812467065,"message":{"message_id":724,"from":{"id":566169252,"is_bot":false,"first_name":"\u57fa\u4f6c\u592b\u98de\u8247"},"chat":{"id":566169252,"first_name":"\u57fa\u4f6c\u592b\u98de\u8247","type":"private"},"date":1529066936,"video":{"duration":10,"width":304,"height":240,"mime_type":"video/mp4","thumb":{"file_id":"AAQBABNaSAwwAASfaYrcanJhJg2BAAIC","file_size":1117,"width":90,"height":71},"file_id":"BAADAQADSAADM_chRQ9Wjqelm51CAg","file_size":329360}}}]
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                        break;
                    case 'doge': //美好的一天从扔柯基开始
                        import('net/dwHttp');
                        $api = 'http://'.ltreDeCrypt("LLiiXXAAts(rxu@0!zCkwaOwHmWQDB-6VVg8yta8_7-YD2ojMK(4vrE0c7zx.8kk!s-~42-6ecjbe9PN(4~VQcd8US'9SdNEup_)!2@mXPidYW-6ojJ5upIG*3NEGytob9@033ULXSUS.8Bw~n~WCA_7F2fc!2Cao8c2)CSd)!~1Brt0LB_uIfTr.Q!h)CRDYI@lnl-6Jh'F.0v1*J-Or5.0Sov2");
                        $http = new dwHttp;
                        $ret = $http->get($api, 20);
                        @$ret = json_decode($ret?:'[]', 1);
                        if (@$ret['code'] == 0) {
                            $dogeList = $ret['data'];
                            $dogeLen = count($dogeList);
                            $doge = $dogeList[mt_rand(0, $dogeLen-1)];
                            $tg->log("yui->doge >> dogeLen: {$dogeLen}");//debug
                            $tg->log("yui->doge >> dogeIndex: ".mt_rand(0, $dogeLen-1)."\r\n");//debug
                            $tg->log("yui->doge >> doge: ".print_r($doge, 1)."\r\n");//debug
                            $tg->log("yui->doge >> mt_rand: ".print_r([mt_rand(0, $dogeLen-1), $dogeLen/2], 1)."\r\n");//debug
                            if (mt_rand(0, $dogeLen-1) > $dogeLen/12) {
                                return TgUtil::sendImageOrAnimateByTuku($tg, $chat, $doge, ['reply_to_message_id' => $message['message_id']]);
                            }
                        }
                        $responseText = 'Can\'t get any doge!';
                        break;
                    case 'ayi'://阿姨先辈！
                        return $tg->callMethod('sendPhoto', [
                            'chat_id' => $chat['id'],
                            'photo' => 'AgACAgUAAxkBAAMIXr5te8IUPyjyPwE-FidTMLtSYnUAAoaqMRt5fPhVc_YvnX3x59Q8ZsBqdAADAQADAgADeQADYP0BAAEZBA', //AQADPGbAanQAA2D9AQAB
                            'caption' => '阿姨，18岁，是个UP！',
                            'reply_to_message_id' => $message['message_id'],
                        ]);
                        break;
                }
            } elseif ($this->hdl == 'cucu') {
                switch ($matches[1]) {
                    case 'vlog':
                        $responseText = "游戏地址：" . ltreDeCrypt("PaQO@0Okzf-ImcCdLhAoIei6'uAy*3MnAcWt.0RqUGGk!SVFIsFi)Rn1'1'VHl*Ti2-rwu~1Sd97(4NdWT)5~CLiy5p8");
                        $responseText .= "\n\n实况录制：" . ltreDeCrypt("U2RnjcGkdcrqLHrkjiIHX2F4)qTR-6)HZLZMk2UpYjig-6'NSuMA'1.Qt8DlpeWE-LWEk9.0(yYsw0K5LJ!2K5vt~1)vhe.8t4TqLii1");
                        $responseText .= "\n游戏原型：" . ltreDeCrypt("j1NBQJU5nmYXc8*pYQRQ.d)u@lDB*3IkHt@NPxLgH2lj*3-Kw8j7'1p7LqYGi7zhVARz*S.0MgYsOiXidb)5Pa_)~1Wmif'9x8'GRoQz");
                        $responseText .= "\n梗来源：" . ltreDeCrypt("A3qnDwFstsgfCytm70DCV0-vRcGE@0o0Nz!P'RE9WhHF_7r5-Iym'1WE'O.Qe3HpMr(Mnc.0w0Mgx1XiXV*3K5IG@0_xFC*3.JNk_E-P");
                        $responseText .= "\n游戏操作：\n上移：↑键 或 触摸上划\n下移：↓键 或 触摸下划\n跳跃：空格键 或 触摸\n开始游戏：确认键 或 点击按钮";
                        break;
                    case 'timi':
                        // return $tg->callMethod('sendAudio', [
                        //     'chat_id' => $chat['id'],
                        //     'audio' => '',
                        //     'reply_to_message_id' => $message['message_id'],
                        // ]);
                        $responseText = "正在找大兔兔本人录制重制版TIMI，稍安毋躁！";
                        break;
                    case 'cucu':
                        import('net/dwHttp');
                        $api = 'http://'.ltreDeCrypt("oo22SS..BA*qYV-6-DYGMqm4Pud731_7)uME-~DB)5ZTwoidb9!2ypOaMHlj-6Ti~Tc786!2icDvUPom~1tkE0MHig*3K7PM~1w4MwWMJgN8EB-6(UOlQGI5PmLjDlWbGdvhKu-rVT-6Ig!y'1JfuaTBAe.0MiUrO9Ay)5I3-(~1XnYV!2UvKhHeCl");
                        $http = new dwHttp;
                        $ret = $http->get($api, 20);
                        @$ret = json_decode($ret?:'[]', 1);
                        if (@$ret['code'] == 0) {
                            $cucuList = $ret['data'];
                            $cucuLen = count($cucuList);
                            $cucu = $cucuList[mt_rand(0, $cucuLen-1)];
                            $tg->log("cucu->cucu >> cucuLen: {$cucuLen}");//debug
                            $tg->log("cucu->cucu >> cucuIndex: ".mt_rand(0, $cucuLen-1)."\r\n");//debug
                            $tg->log("cucu->cucu >> cucu: ".print_r($cucu, 1)."\r\n");//debug
                            $tg->log("cucu->cucu >> mt_rand: ".print_r([mt_rand(0, $cucuLen-1), $cucuLen/2], 1)."\r\n");//debug
                            if (mt_rand(0, $cucuLen-1) > $cucuLen/12) {
                                return TgUtil::sendImageOrAnimateByTuku($tg, $chat, $cucu, ['reply_to_message_id' => $message['message_id']]);
                            }
                        }
                        $responseText = 'Can\'t get any cucu!';
                        break;
                    case 'ga':
                        $responseText = "大兔兔翻车视频集锦，绝赞收集中！";
                        break;
                    case 'help':
                        $responseText = "vlog - 给你个vlog挑战！";
                        $responseText .= "\ntimi - TIMI！";
                        $responseText .= "\ncucu - 大兔兔呢？";
                        $responseText .= "\nga - 我不知道为什么我这个人她就是很翻车";
                        $responseText .= "\nhelp - 大兔兔啥也帮不到你";
                        break;
                }
            } elseif ($this->hdl == 'fch') {
                $responseText = "暂无命令功能";
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
        } elseif ($this->hdl == 'pussy') { //发送入群校验问题，校验逻辑见 onGroupMessage()
            @$name = TgUtil::specialTextFilter($member['first_name'].$member['last_name'], 'Markdown');
            $t1 = ['','二','三','四','五'];
            $t2 = ['','一','二','三','四','五','六','七','八','九'];
            $rand = [mt_rand(10, 50), mt_rand(10, 50)];
            $qs = $t1[intval($rand[0]/10)-1] .'十'. $t2[intval($rand[0]%10)] .' 加 '. $t1[intval($rand[1]/10)-1] .'十'. $t2[intval($rand[1]%10)] . ' 等于多少？';
            //$qs = "{$rand[0]} + {$rand[1]} = ?";
            list ($ok, $resp) = $tg->callMethod('sendMessage', [
                'chat_id' => $chat['id'],
                'text' => "欢迎 [{$name}](tg://user?id={$member['id']}) , 请2分钟内完成入群校验（\n*输入阿拉伯数字*例如12\n*输入阿拉伯数字*例如34\n*输入阿拉伯数字*例如56\n重要的话说三遍！）：\n\n_{$qs}_\n\n如果不按时完成，你将在*以后的某个时机*起飞。",
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
            //取出历史校验集合文件
            $saveFile = DI_DATA_PATH."group.{$chat['id']}.reCaptcha";
            @$data = json_decode(file_get_contents($saveFile) ?: '{}', 1);
            //额外的操作：清理过期的校验信息，并仅踢掉没有及时发送答案的人(但确保以后还能加群)
            foreach ($data as $uid => $info) {
                if (time() - $info['time'] > 120) {
                    unset($data[$uid]);
                    @$tg->callMethod('deleteMessage', [
                        'chat_id' => $chat['id'],
                        'message_id' => $info['msgId'],
                    ]);
                    @$tg->callMethod('kickChatMember', [
                        'chat_id' => $chat['id'],
                        'user_id' => $uid,
                        'until_date' => 31,//31秒后解封（大于30秒且小于366天，将视为有效时间）
                    ]);
                }
            }
            //记录本次的校验信息
            if ($ok) {
                $newMsgId = $resp['result']['message_id'];
                $saveKey = $member['id'];//UID
                $saveData = ['msgId' => $newMsgId, 'answer' => array_sum($rand), 'time' => time()];
                $data[$saveKey] = $saveData;
            }
            file_put_contents($saveFile, json_encode($data));

            return [$ok, $resp];
        }
    }


    //用私聊的方式转发时
    function onPrivateForwardFrom(array $update){
        $message = $update['message'];
        $ffw = $message['forward_from'];
        $tg = Tg::inst($this->hdl);
        if ($this->hdl == 'pinkjj') {
            @$name = TgUtil::specialTextFilter($ffw['first_name'].$ffw['last_name']);
            // @$response1 = "`` id: {$ffw['id']}\n first: ".TgUtil::specialTextFilter($ffw['first_name'])."\n last: ".TgUtil::specialTextFilter($ffw['last_name'])."\n username: {$ffw['username']}\n is_bot: {$ffw['is_bot']}\n [{$name}](tg://user?id={$ffw['id']})";
            @$response1 = "id: {$ffw['id']}\nfirst: {$ffw['first_name']}\nlast: {$ffw['last_name']}\nusername: {$ffw['username']}\nis_bot: {$ffw['is_bot']}";
            @$response2 = "``[{$name}](tg://user?id={$ffw['id']})";
            //返回多行原发者的详细信息
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => $response1,
                'reply_to_message_id' => $message['message_id'],
                // 'parse_mode' => 'Markdown',
            ]);
            usleep(500);
            //返回原发者的mention
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => $response2,
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
            usleep(500);
            //返回原发者mention前的字符串
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => $response2,
                'reply_to_message_id' => $message['message_id'],
            ]);
            // usleep(500);
            //@todo //返回原发处的来源信息(尽可能包含调整URL) 这个方法是获取不到forward_from_chat的，所以根本不会执行到这里，需要在TgDispatch重新判断forward_from_chat字段是否存在，并在TgDeal多开一个on方法
            // $tg->callMethod('sendMessage', [
            //     'chat_id' => $message['chat']['id'],
            //     'text' => "``原发时间: {$meesage['forward_date']}\n原发消息ID: {$message['forward_from_message_id']}\n原发点标题: {$message['forward_from_chat']}\n",
            //     'reply_to_message_id' => $message['message_id'],
            //     'parse_mode' => 'Markdown',
            // ]);
        }
    }


    function onLeftChatMember(array $update){
        $message = $update['message'];
        $member = $message['left_chat_member'];
        $chat = $message['chat'];
        $tg = Tg::inst($this->hdl);
        if (in_array($this->hdl, ['shabisb', 'pinkjj']) && $chat['id'] == '-1001377141307') {//傻逼群
            @$name = TgUtil::specialTextFilter($member['first_name'].$member['last_name'], 'Markdown');
            $name = $name ?: '无名傻逼';
            $tg->callMethod('sendMessage', [
                'chat_id' => $message['chat']['id'],
                'text' => "有一个傻逼：[{$name}](tg://user?id={$member['id']}) 已滚出，扣除100傻币所得（代扣1000智商），祝ta不能融入非傻逼界！",
                'reply_to_message_id' => $message['message_id'],
                'parse_mode' => 'Markdown',
            ]);
        }
    }
    
}