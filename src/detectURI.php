<?php

function detectURI()
{
    /**
     * 如果PHP_SELF最后包含/，直接取QUERY_STRING
     * 如有PATH_INFO直接使用
     * 如有REDIRECT_URL且DOCUMENT_ROOT + REDIRECT_URL不是有效的文件名则使用REDIRECT_URL
     * 如果QUERY_STRING带了问号，则取第一段使用
     * 如果DOCUMENT_ROOT + SCRIPT_NAME不是有效的文件名则使用SCRIPT_NAME
     */
    $uri = '';
    if (isset($_SERVER['PHP_SELF']) && substr($_SERVER['PHP_SELF'], -1) === '/') {
        $uri = $_SERVER['QUERY_STRING'];
    } else if (isset($_SERVER['PATH_INFO'])) {
        $uri = $_SERVER['PATH_INFO'];
    } else if (isset($_SERVER['REDIRECT_URL']) && !file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_URL'])) {
        $uri = $_SERVER['REDIRECT_URL'];
    } else if (isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], '?') !== false) {
        $uri = explode('?', $_SERVER['QUERY_STRING'])[0];
    } else if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'])) {
        $uri = $_SERVER['SCRIPT_NAME'];
    }
    if ($uri == '/' || empty($uri)) {
        return '/';
    }
    $uri = parse_url($uri, PHP_URL_PATH);
    return str_replace(array('//', '../'), '/', trim($uri, '/'));
}
