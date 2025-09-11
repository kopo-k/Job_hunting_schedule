<?php
class Controller_Debug extends \Controller
{
    public function action_config()
    {
        $v = function($x){
            if (is_bool($x)) return $x ? 'true' : 'false';
            if (is_array($x)) return json_encode($x, JSON_UNESCAPED_UNICODE);
            return (string)$x;
        };

        $lines = [
            'base_url: '          . $v(\Config::get('base_url')),
            'index_file: '        . $v(\Config::get('index_file')),
            'csrf_autoload: '     . $v(\Config::get('security.csrf_autoload')),
            'cookie.http_only: '  . $v(\Config::get('cookie.http_only')),
        ];

        return \Response::forge(
            "<pre>".implode("\n", $lines)."</pre>",
            200,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }
}
