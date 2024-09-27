<?php

function asset(string $url): string
{
    return PATH . '/assets/' . $url;
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
