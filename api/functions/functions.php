<?php
function ValidateUrl($urlFull = null, $position = 0)
{
    $url = array_filter(explode('/', $urlFull));
    if (preg_match('/^[a-zA-Z0-9-_]{0,48}$/', trim($url[$position]))) {
        return $url[$position];
    }
}

function RandString($size)
{
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $return = "";
    for ($count = 0; $size > $count; $count++) {
        $return .= $basic[rand(0, strlen($basic) - 1)];
    }
    return $return;
}