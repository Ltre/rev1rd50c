<?php

class RemoteFile {

    protected $mimeMap = [
        'application/msword' => 'doc',
        'application/pdf' => 'pdf',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.ms-works' => 'wps',
        'application/x-compressed' => 'tgz',
        'application/x-gzip' => 'gz',
        'application/x-javascript' => 'js',
        'application/x-shockwave-flash' => 'swf',
        'application/x-tar' => 'tar',
        'application/zip' => 'zip',
        'application/xml' => 'xml',
        'application/xhtml+xml' => 'xhtml',
        'application/x-rar-compressed' => 'rar',
        'application/octet-stream' => 'file',//...
        'audio/mpeg' => 'mp3',
        'audio/mid' => 'mid',
        'audio/x-wav' => 'wav',
        'audio/x-mpegurl' => 'm3u',
        'image/bmp' => 'bmp',
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/svg+xml' => 'svg',
        'image/webp' => 'webp',
        'image/png' => 'png',
        'image/x-icon' => 'ico',
        'image/vnd.microsoft.icon' => 'ico',
        'message/rfc822' => 'mhtml',
        'text/css' => 'css',
        'text/html' => 'html',
        'text/plain' => 'txt',
        'text/richtext' => 'rtx',
        'video/mp4' => 'mp4',
        'video/quicktime' => 'mov',
        'video/x-msvideo' => 'avi',
        'video/webm' => 'webm',
        'video/ogg' => 'ogg',
    ];


    protected function _mimetype2ext($mimetype, $basename = ''){
        if (! isset($this->mimeMap[$mimetype])) {
            return $this->_getExt($basename);
        } else {
            return $this->mimeMap[$mimetype];
        }
    }


    //通过文件名获取扩展名
    protected function _getExt($filename){
        if (preg_match('/\.(\w+)$/', $filename, $matches)) {
            return $matches[1];
        } else {
            return '';
        }
    }


	//远程中转下载
	public function download($url, $forceContentType = ''){
        if ($forceContentType && ! preg_match('/^\w+\/\w+$/', $forceContentType)) {
            exit('wtf of contentType!');//恶意媒体类型参数
        }

        if (! preg_match('/^(https?\:)?\/\//', $url)) {
            exit('url format error!');//URL格式错误
        }

        if (preg_match('/^\/\//', $url)) {//遇到 // 开头的链接
            $urls = ["https:{$url}", "http:{$url}"];
        } else {
            $urls = [$url];
        }
        foreach ($urls as $url) {
            $h = get_headers($url, 1);
            if (false === $h) continue;
        }

        if (false === $h) {
            exit('remote http header error!');//URL加载信息失败
        }

		$contentLength = is_array($h['Content-Length']) ? $h['Content-Length'][count($h['Content-Length'])-1] : $h['Content-Length'];//考虑链接跳转的情况下，会变成数组
		if ($contentLength > 1024*1024*1024) {
            exit('remote file size > 1G!');//文件大小超过1G
        }

        //获取合适的文件扩展名
        $contentType = is_array($h['Content-Type']) ? $h['Content-Type'][count($h['Content-Type'])-1] : $h['Content-Type'];
        $ext = $this->_mimetype2ext(
            trim(array_shift(explode(';', $contentType))),
            array_shift(explode('?', basename($url)))
        );
        if (empty($ext)) {
            exit("Forbiden Content-Type: {$contentType}");//文件类型被禁止
        }

		$fp = fopen($url, 'rb');
        $forceContentType ? header("Content-Type: {$contentType}") : header("Content-Type: {$forceContentType}");
        header("Accept-Range: bytes");
        header("Accept-length: {$contentLength}");
        header("Content-Disposition: attachment; filename=".uniqid().".{$ext}");
        ob_clean();
        flush();

        for ($count = 0; ! feof($fp) && $count < $contentLength; ) {
            $data = fread($fp, 1024);
            $count += mb_strlen($data);
            echo $data;
        }

        fclose($fp);
        exit;
	}

}