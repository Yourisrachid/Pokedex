<?php

function index()
{
    // This could be placed in a folder called "models" - from here
    $user = [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com'
    ];
    // to here -

    require_once __DIR__ . '/../views/pages/index.php';

    return $user;
}

function show()
{
    require_once __DIR__ . '/../views/pages/show.php';
}
