<?php

function download($file, $del=false){
    header("Content-type:text/plain; charset=utf-8");
    header("Content-type: application/octet-stream");
    header("Accept-Ranges: bytes");
    header("Accept-Length: ".filesize($file));
    header("Content-Disposition: attachment; filename=" . basename($file));
    $f = fopen($file, 'r');
    $size = filesize($file);
    echo $size ? fread($f, $size) : '';
    unlink($file);
    fclose($f);
    exit;
}

$dir = '/root/mydir/uncompress/ncmdump/tmp';
$bin = "/root/mydir/uncompress/ncmdump/ncmdump";
if (isset($_FILES['f'])) {
    $f = $_FILES['f'];
    if (preg_match('/\.ncm$/', $f['name'])) {
        if (is_uploaded_file($f['tmp_name'])) {
            $tmpdir = $dir.'/'.intval(microtime(1)*1000);
            mkdir($tmpdir);
            $dlname = $tmpdir.'/1.ncm';
            move_uploaded_file($f['tmp_name'], $dlname);
            shell_exec("{$bin} {$dlname}");
            $resultFile = null;
            foreach (glob($tmpdir) as $v) {
                if (in_array($v, ['.', '..', '1.ncm'])) continue;
                $resultFile = $tmpdir.'/'.$v;
            }
            if ($resultFile) {
                download($resultFile);
            }
        }
    }
    die('fail!');
} else {
    echo "<meta charset='utf-8'><form target='hehe' action='/?ncm' method='post' enctype='multipart/form-data'> <input type='file' name='f'> <input type='submit' value='up'> </form> <iframe name='hehe' width='960', height='480'></iframe>";
}