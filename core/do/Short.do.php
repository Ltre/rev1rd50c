<?php
/**
 * 短网址相关
 */
class ShortDo extends DIDo {

    var $poolFile;

    function _init(){
        $this->poolFile = DI_DATA_PATH.'cache/shortUrl.pool';
    }

    function start($key = ''){
        if (empty($key)) {
            $this->stpl();
        } else {
            $url = $this->readDst($key);
            if (false === $url) die('wrong!');
            echo "<script>location.href='$url'</script>";
        }
    }


    function set() {
        $url = arg('url');
        if (empty($url)) {
            die('param err');
        }
        list($succ, $key) = $this->writeDst($url);
        putjson(['rs' => $succ, 'key' => $key]);
        exit($succ?'done':'fail');
    }


    function pool(){
        putjson($this->readPool());
    }


    protected function writeDst($url){
        if (! preg_match('/^https?\:\/\//', $url)) {
            return [false, 'url err'];
        }
        list($k2u, $u2k) = $this->readPool();
        $pool = compact('k2u', 'u2k');
        if (isset($u2k[$url])) {
            return [true, $u2k[$url]];
        }
        $key = $this->getNewKey($k2u);
        if (false === $key) return [false, null];
        if (! isset($k2u[$key])) {
            $pool['k2u'][$key] = $url;
            $pool['u2k'][$url] = $key;
            file_put_contents($this->poolFile, json_encode($pool, 1));
        }
        return [true, $key];
    }


    protected function readDst($key) {
        list($k2u, $u2k) = $this->readPool();
        if (empty($k2u[$key])) return false;
        return $k2u[$key];
    }


    protected function readPool(){
        $pool = @json_decode(file_get_contents($this->poolFile)?:'{"k2u":{}, "u2k":{}}', 1);
        return [$pool['k2u'], $pool['u2k']];
    }


    protected function getNewKey(array $k2u){
        $key = false;
        for ($i=0; $i<3 && false === $key; $i++) {
            $key = $this->randKey();
            if (isset($k2u[$key])) {
                $key = false;
            }
        }
        return $key;
    }


    protected function randKey(){
        $table = array_merge(
            range(ord('0'), ord('9')),
            range(ord('A'), ord('Z')),
            range(ord('a'), ord('z'))
        );
        // for($i=33;$i<127;$i++) {
        //     if (in_array($i, [35,37,38,47,58,63,64,92,95])) {
        //         continue;
        //     }
        //     $table .= chr($i);
        // }
        $tLen = count($table);
        //1~6 letter
        $key = '';
        $width = mt_rand(1, 6);
        for ($j = 0; $j < $width; $j++) {
            $key .= chr($table[mt_rand(0, $tLen-1)]);
        }
        return $key;
    }

}