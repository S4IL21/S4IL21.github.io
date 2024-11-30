<?php

class YoutubeDownloader
{
    protected $url;
    protected $fullInfo;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getFullInfo()
    {
        if (is_null($this->fullInfo)) {
            preg_match("/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/i", $this->url, $matches);
            $videoId = $matches[7];
            $clearContent = file_get_contents("https://www.youtube.com/watch?v=$videoId");
            preg_match('/ytplayer.config = (\{.+});ytplayer/', $clearContent, $mathes);
            $configData = json_decode($mathes[1], true);
            $streamList = explode(',', $configData['args']['url_encoded_fmt_stream_map']);
            $configData['args']['url_encoded_fmt_stream_map'] = array();
            foreach ($streamList AS $stream) {
                parse_str($stream, $res);
                $configData['args']['url_encoded_fmt_stream_map'][] = $res;
            }
            $streamList = explode(',', $configData['args']['adaptive_fmts']);
            $configData['args']['adaptive_fmts'] = array();
            foreach ($streamList AS $stream) {
                parse_str($stream, $res);
                $configData['args']['adaptive_fmts'][] = $res;
            }

            $this->fullInfo = $configData['args'];
            $this->fullInfo['html'] = $clearContent;
            $this->fullInfo['video_id'] = $videoId;
            $jsPath = $configData['assets']['js'];
            $jsPlayerClear = file_get_contents('https://www.youtube.com' . $jsPath);
            preg_match('/set\("signature",\s*(?:([^(]*).*)\)/', $jsPlayerClear, $mathes);
            $signParserFnName = $mathes[1];
            preg_match("/$signParserFnName=(function\(.+;)/", $jsPlayerClear, $mathes);
            $signFnBody = $mathes[1];
            preg_match("/(\w+)\.\w+\([\w\d,]+\)/", $signFnBody, $mathes);
            $helperObjName = $mathes[1];
            preg_match_all("/$helperObjName\.(\w+)\(\w+,(\d+)\);/", $signFnBody, $matches);
            $startPos = strpos($jsPlayerClear, "$helperObjName={");
            $calcRule = array();
            foreach ($matches[1] AS $i => $fnName) {
                $calcRule[] = array(
                    'function' => $fnName,
                    'param' => $matches[2][$i],
                );
            }
            $endPos = $startPos + strlen($helperObjName) + 2;
            $startPos += strlen($helperObjName) + 1;
            $countOpenBraces = 1;
            while ($countOpenBraces > 0) {
                $char = substr($jsPlayerClear, $endPos, 1);
                $endPos++;
                if ($char == '{') {
                    $countOpenBraces++;
                } elseif ($char == '}') {
                    $countOpenBraces--;
                }
            }
            $helperObjBody = substr($jsPlayerClear, $startPos, $endPos - $startPos);
            preg_match_all('/(\w+):function\([\w,]+\){([^}]+)}/', $helperObjBody, $matches);
            $calcFunctions = array();
            foreach ($matches[1] AS $i => $fnName) {
                $fnBody = $matches[2][$i];
                if (strpos($fnBody, 'reverse') !== false) {
                    $calcFunctions[$fnName] = 'reverse';
                    continue;
                }
                if (strpos($fnBody, 'splice') !== false) {
                    $calcFunctions[$fnName] = 'splice';
                    continue;
                }
                $calcFunctions[$fnName] = 'swap';
            }
            foreach ($calcRule AS &$calcItem) {
                $calcItem['function'] = $calcFunctions[$calcItem['function']];
            }
            $this->fullInfo['calcSignatureSteps'] = $calcRule;
            $calcSignExpression = <<<JS
    function calculateSignature(sign){
        var __HELPER_OBJ_NAME = __HELPER_OBJ_BODY__;
        var calcSign = __SIGN_FN_BODY__;
        return calcSign(sign);
    }
JS;
            $calcSignExpression = str_replace('__HELPER_OBJ_NAME', $helperObjName, $calcSignExpression);
            $calcSignExpression = str_replace('__HELPER_OBJ_BODY__', $helperObjBody, $calcSignExpression);
            $calcSignExpression = str_replace('__SIGN_FN_BODY__', $signFnBody, $calcSignExpression);

            $this->fullInfo['jsSignatureGen'] = $calcSignExpression;

        }
        return $this->fullInfo;
    }

    public function getBaseInfo()
    {
        $fullInfo = $this->getFullInfo();
        $baseInfo = array();
        $baseInfo['name'] = $fullInfo['title'];
        $videoId = $fullInfo['video_id'];
        $baseInfo['previewUrl'] = "https://img.youtube.com/vi/$videoId/hqdefault.jpg";
        $html = $fullInfo['html'];
        if (preg_match('/<p id="eow-description"[^>]+>(.+)<\/p>/', $html, $matches)) {
            $baseInfo['description'] = strip_tags(str_replace('<br />', "\n", $matches[1]));
        }
        return $baseInfo;
    }

    public static function getItagInfo()
    {
        return array(
            5 => array('format' => 'flv', 'withVideo' => true, 'withAudio' => true),
            6 => array('format' => 'flv', 'withVideo' => true, 'withAudio' => true),
            13 => array('format' => '3gp', 'withVideo' => true, 'withAudio' => true),
            17 => array('format' => '3gp', 'withVideo' => true, 'withAudio' => true),
            18 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            22 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            34 => array('format' => 'flv', 'withVideo' => true, 'withAudio' => true),
            35 => array('format' => 'flv', 'withVideo' => true, 'withAudio' => true),
            36 => array('format' => '3gp', 'withVideo' => true, 'withAudio' => true),
            37 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            38 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            43 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            44 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            45 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            46 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            82 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            83 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            84 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            85 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            100 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            101 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            102 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => true),
            92 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            93 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            94 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            95 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            96 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            132 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            151 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => true),
            133 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            134 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            135 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            136 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            137 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            138 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            160 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            264 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            298 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            299 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            266 => array('format' => 'mp4', 'withVideo' => true, 'withAudio' => false),
            139 => array('format' => 'm4a', 'withVideo' => false, 'withAudio' => true),
            140 => array('format' => 'm4a', 'withVideo' => false, 'withAudio' => true),
            141 => array('format' => 'm4a', 'withVideo' => false, 'withAudio' => true),
            167 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            168 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            169 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            170 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            218 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            219 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            242 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            243 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            244 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            245 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            246 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            247 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            248 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            271 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            272 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            302 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            303 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            308 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            313 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            315 => array('format' => 'webm', 'withVideo' => true, 'withAudio' => false),
            171 => array('format' => 'webm', 'withVideo' => false, 'withAudio' => true),
            172 => array('format' => 'webm', 'withVideo' => false, 'withAudio' => true),
        );
    }

    static protected function calcSignature($signatureEncoded, $steps)
    {
        $sign = str_split($signatureEncoded);
        foreach ($steps AS $step) {
            $fn = $step['function'];
            $param = (int)$step['param'];

            switch ($fn) {
                case 'reverse':
                    $sign = array_reverse($sign);
                    break;
                case 'splice':
                    $sign = array_slice($sign, $param);
                    break;
                case 'swap':
                    $c = $sign[0];
                    $sign[0] = $sign[$param % count($sign)];
                    $sign[$param] = $c;
                    break;
            }
        }
        return implode('', $sign);
    }

    public static function getResponseHeaders($url)
    {
        $clearHeaders = get_headers($url);
        $headers = array();
        foreach ($clearHeaders AS $header) {
            $header = explode(':', $header);
            if (count($header) === 2) {
                $headers[$header[0]] = trim($header[1]);
            }
        }
        return $headers;
    }

    public static function getFileSizeHuman($size){
        if (!$size) {
            return '-';
        }

        return round($size / 1024 / 1024, 2) . ' MB';
    }

    public function getDownloadInfoOne($fmtsItem)
    {
        $fullInfo = $this->getFullInfo();
        $title = $fullInfo['title'];
        $url = $fmtsItem['url'] . '&title=' . urlencode($title);
        $signature = false;
        if (isset($fmtsItem['signature'])) {
            $signature = $fmtsItem['signature'];
        }

        if (isset($fmtsItem['sig'])) {
            $signature = self::calcSignature($fmtsItem['sig'], $fullInfo['calcSignatureSteps']);
        }

        if (isset($fmtsItem['s'])) {
            $signature = self::calcSignature($fmtsItem['s'], $fullInfo['calcSignatureSteps']);
        }

        if ($signature) {
            $url .= '&signature=' . $signature;
        }

        $headers = self::getResponseHeaders($url);
        $downloadInfo = array();
        $downloadInfo['fileSize'] = (int)$headers['Content-Length'];
        $downloadInfo['fileSizeHuman'] = self::getFileSizeHuman($downloadInfo['fileSize']);
        $downloadInfo['url'] = $url;
        $downloadInfo['youtubeItag'] = $fmtsItem['itag'];
        $downloadInfo['fileType'] = explode(';', $fmtsItem['type']);
        $downloadInfo['fileType'] = $downloadInfo['fileType'][0];
        $ext = explode('/', $downloadInfo['fileType']);
        $ext = $ext[1];
        $downloadInfo['name'] = $title . '.' . $ext;
        $downloadInfo['itagInfo'] = self::getItagInfo();
        if (isset($downloadInfo['itagInfo'][$fmtsItem['itag']])) {
            $downloadInfo['itagInfo'] = $downloadInfo['itagInfo'][$fmtsItem['itag']];
        } else {
            $downloadInfo['itagInfo'] = null;
        }
        $downloadInfo[] = $downloadInfo;
        return $downloadInfo;
    }

    /**
     * Return array of download information for video
     *
     * @return VideoDownloadInfo[]
     * @throws VideoDownloaderDownloadException
     */
    public function getDownloadsInfo()
    {
        $fullInfo = $this->getFullInfo();
        $fmts = $fullInfo['url_encoded_fmt_stream_map'];
        $fmts = array_merge($fmts, $fullInfo['adaptive_fmts']);
        $downloadsInfo = array();
        foreach ($fmts AS $item) {
            $downloadsInfo[] = $this->getDownloadInfoOne($item);
        }
        return $downloadsInfo;
    }

    public function downloadForItag($itag)
    {
        $fullInfo = $this->getFullInfo();
        $fmts = $fullInfo['url_encoded_fmt_stream_map'];
        $fmts = array_merge($fmts, $fullInfo['adaptive_fmts']);
        foreach ($fmts AS $item) {
            if ($item['itag'] == $itag) {
                $dlInfo = $this->getDownloadInfoOne($item);
                set_time_limit(0);
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                $qwoteName = str_replace('"', '', $dlInfo['name']);
                header('Content-Disposition: attachment; filename="' . $qwoteName . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . $dlInfo['fileSize']);
                ob_clean();
                flush();
                readfile($dlInfo['url']);
                die();
            }
        }
        header('Not found', true, 404);
        echo "404 Not found";
        die();
    }
}