<?php

function asset(string $url): string
{
    return PATH . '/assets/' . $url;
}


function upload(string $url): string
{
    return PATH . '/uploads/' . $url;
}



function debug($data, $die = false)
{
    echo '<pre>' . print_r($data, 1) . '</pre>';
    if ($die) {
        die;
    }
}

function h($str)
{
    return htmlspecialchars($str);
}
