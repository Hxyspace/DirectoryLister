<?php


ini_set('date.timezone','Asia/Shanghai');

require_once('Log.class.php');

$filename = "logs/log_" . date("Ymd", time()) . ".txt";

$msg = array(

'ip' => getip(),

'Location' => getLocation(),

'user' => getBrowser(),

'BrowserLang' => GetLang(),

'userOs' => GetOs(),

'from' => 'upload'
);

$Log = new Log();

$Log->writeLog($filename, $msg);

$loglist = $Log->readLog($filename);


//获取ip
function getip() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP") , "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR") , "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR") , "unknown")) {
        $ip = getenv("REMOTE_ADDR");
    } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "unknown";
    }
    return $ip;
}
//获取地址
function getLocation($ip = '') {
    empty($ip) && $ip = getip();
    if ($ip == "127.0.0.1") return "本机地址";
    $api = "http://ip.taobao.com/service/getIpInfo.php?ip=$ip";   //请求新浪ip地址库
    $json = @file_get_contents($api);
    $arr = json_decode($json, true);
    $country = $arr['country'];
    $province = $arr['region'];
    $city = $arr['city'];
    if ((string)$country == "中国") {
        if ((string)($province) != (string)$city) {
            $_location = $province . $city;
        } else {
            $_location = $country . $city;
        }
    } else {
        $_location = $country;
    }
    return $_location;
}

//获取浏览器语言
function GetLang() {
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $lang = substr($lang, 0, 5);
        if (preg_match("/zh-cn/i", $lang)) {
            $lang = "简体中文";
        } elseif (preg_match("/zh/i", $lang)) {
            $lang = "繁体中文";
        } else {
            $lang = "English";
        }
        return $lang;
    } else {
        return "获取浏览器语言失败！";
    }
}


//获取访客操作系统信息
function GetOs() {
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $OS = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i', $OS)) {
            $OS = 'Windows';
        } elseif (preg_match('/mac/i', $OS)) {
            $OS = 'MAC';
        } elseif (preg_match('/linux/i', $OS)) {
            $OS = 'Linux';
        } elseif (preg_match('/unix/i', $OS)) {
            $OS = 'Unix';
        } elseif (preg_match('/bsd/i', $OS)) {
            $OS = 'BSD';
        } else {
            $OS = 'Other';
        }
        return $OS;
    } else {
        return "获取访客操作系统信息失败！";
    }
}


//获取浏览器类型

function getBrowser() {

$user_OSagent = $_SERVER['HTTP_USER_AGENT'];

if (strpos($user_OSagent, "Maxthon") && strpos($user_OSagent, "MSIE")) {

$visitor_browser = "Maxthon(Microsoft IE)";

} elseif (strpos($user_OSagent, "Maxthon 2.0")) {

$visitor_browser = "Maxthon 2.0";

} elseif (strpos($user_OSagent, "Maxthon")) {

$visitor_browser = "Maxthon";

} elseif (strpos($user_OSagent, "Edge")) {

$visitor_browser = "Edge";

} elseif (strpos($user_OSagent, "Trident")) {

$visitor_browser = "IE";

} elseif (strpos($user_OSagent, "MSIE")) {

$visitor_browser = "IE";

} elseif (strpos($user_OSagent, "MSIE")) {

$visitor_browser = "MSIE 较高版本";

} elseif (strpos($user_OSagent, "NetCaptor")) {

$visitor_browser = "NetCaptor";

} elseif (strpos($user_OSagent, "Netscape")) {

$visitor_browser = "Netscape";

} elseif (strpos($user_OSagent, "Chrome")) {

$visitor_browser = "Chrome";

} elseif (strpos($user_OSagent, "Lynx")) {

$visitor_browser = "Lynx";

} elseif (strpos($user_OSagent, "Opera")) {

$visitor_browser = "Opera";

} elseif (strpos($user_OSagent, "MicroMessenger")) {

$visitor_browser = "微信浏览器";

} elseif (strpos($user_OSagent, "Konqueror")) {

$visitor_browser = "Konqueror";

} elseif (strpos($user_OSagent, "Mozilla/5.0")) {

$visitor_browser = "Mozilla";

} elseif (strpos($user_OSagent, "Firefox")) {

$visitor_browser = "Firefox";

} elseif (strpos($user_OSagent, "U")) {

$visitor_browser = "Firefox";

} else {

$visitor_browser = "其它";

}

return $visitor_browser;

}
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

#!! IMPORTANT:
#!! this file is just an example, it doesn't incorporate any security checks and
#!! is not recommended to be used in production environment as it is. Be sure to
#!! revise it and customize to your needs.


// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Support CORS
// header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // finish preflight CORS requests here
}


if ( !empty($_REQUEST[ 'debug' ]) ) {
    $random = rand(0, intval($_REQUEST[ 'debug' ]) );
    if ( $random === 0 ) {
        header("HTTP/1.0 500 Internal Server Error");
        exit;
    }
}

// header("HTTP/1.0 500 Internal Server Error");
// exit;


// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
usleep(5000);

// Settings
// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$targetDir = 'upload_tmp';
$uploadDir = 'upload';

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds


// Create target dir
if (!file_exists($targetDir)) {
    @mkdir($targetDir);
}

// Create target dir
if (!file_exists($uploadDir)) {
    @mkdir($uploadDir);
}

// Get a file name
if (isset($_REQUEST["name"])) {
    $fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
    $fileName = $_FILES["file"]["name"];
} else {
    $fileName = uniqid("file_");
}

//排除.php .html .jsp文件
if(strpos($fileName, '.php')!==false || strpos($fileName, '.html')!==false || strpos($fileName, '.jsp')!==false) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "文件不合法."}, "id" : "id"}');
}

$md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$md5File = $md5File ? $md5File : array();

if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
    die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


// Remove old temp files
if ($cleanupTargetDir) {
    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
    }

    while (($file = readdir($dir)) !== false) {
        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
            continue;
        }

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
            @unlink($tmpfilePath);
        }
    }
    closedir($dir);
}


// Open temp file
if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    }

    // Read binary input stream and append it to temp file
    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
} else {
    if (!$in = @fopen("php://input", "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
}

while ($buff = fread($in, 4096)) {
    fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

$index = 0;
$done = true;
for( $index = 0; $index < $chunks; $index++ ) {
    if ( !file_exists("{$filePath}_{$index}.part") ) {
        $done = false;
        break;
    }
}
if ( $done ) {
    if (!$out = @fopen($uploadPath, "wb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    }

    if ( flock($out, LOCK_EX) ) {
        for( $index = 0; $index < $chunks; $index++ ) {
            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                break;
            }

            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }

            @fclose($in);
            @unlink("{$filePath}_{$index}.part");
        }

        flock($out, LOCK_UN);
    }
    @fclose($out);
}

chmod($uploadPath,0660);

// Return Success JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
