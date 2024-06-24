<?php

function error($code = 404)
{
    switch ($code) {
        case 404:
            require_once __DIR__ . '../../views/errors/404.php';
            http_response_code(404);
            break;
        case 405:
            require_once __DIR__ . '../../views/errors/405.php';
            http_response_code(405);
            break;
        default:
            require_once __DIR__ . '../../views/errors/500.php';
            http_response_code(500);
            break;
    }
    exit();
}
