<?php

class TgUtil extends DIEntity {

    static function specialTextFilter($text, $parse_mode = ''){
        if ($parse_mode == 'Markdown') {
            $text = str_replace(
                ['(', ')', '`', '*', '[', ']', '"', "'", ':', ';', '!', '~', '@', '#', '$', '%', '^', '&', '-', '=', '{', '}', '|', '/', ',', '.', '?', '\\', '_', '+'],
                ['（', '）', '·', '＊', '［', '］', '＂', "＇", '：', '；', '！', '～', '＠', '＃', '＄', '％', '＾', '＆', '－', '＝', '｛', '｝', '｜', '／', '，', '．', '？', '＼', '＿', '＋'],
                $text
            );
        } elseif ($parse_mode == 'HTML') {
            $text = htmlentities($text);
        }
        return $text;
    }

}