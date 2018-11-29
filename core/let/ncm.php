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

$dir = DI_CACHE_PATH.'ncmdump';
@mkdir($dir, 0777);
$bin = "/root/mydir/uncompress/ncmdump/ncmdump";
if (isset($_FILES['f'])) {
    $f = $_FILES['f'];
    var_dump($f);
    if (preg_match('/\.ncm$/', $f['name'])) {
        echo 'A:<br>';
        if (is_uploaded_file($f['tmp_name'])) {
            echo 'B:<br>';
            $tmpdir = $dir.'/'.intval(microtime(1)*1000);
            var_dump($tmpdir);
            mkdir($tmpdir);
            $dlname = $tmpdir.'/1.ncm';
            echo 'C:<br>';
            var_dump(move_uploaded_file($f['tmp_name'], $dlname));
            echo 'D:<br>';
            var_dump(shell_exec("{$bin} {$dlname}"));
            $resultFile = null;
            echo 'E:<br>';
            var_dump(glob($tmpdir));
            foreach (glob($tmpdir.'/*') as $v) {
                echo 'F:<br>';
                var_dump($v);
                if (in_array($v, ['.', '..', '1.ncm'])) continue;
                $resultFile = $tmpdir.'/'.$v;
                echo 'G:<br>';
                var_dump($resultFile);
            }
            if ($resultFile) {
                echo 'H:<br>';
                download($resultFile);
            }
        }
    }
    die('fail!');
} else {
    echo "<meta charset='utf-8'><form target='hehe' action='/?ncm' method='post' enctype='multipart/form-data'> <input type='file' name='f'> <input type='submit' value='up'> </form> <iframe name='hehe' width='960', height='480'></iframe>";
}