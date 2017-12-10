<?php

function base_dir($path = '') {
    return __DIR__ . '/' . $path;
}

function app_dir($path = '') {
    return __DIR__ . '/app/' . $path;
}

function storage_dir($path = '') {
    return __DIR__ . '/storage/' . $path;
}