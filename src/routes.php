<?php

return [
    ''                       => ['HomeController', 'index',],
    'userData/inscription'   => ['RegisterController', 'add',],
    'logout'                 => ['RegisterController', 'logout'],
    'userData/profil'        => ['RegisterController', 'profil', ['id']],
    'items'                  => ['ItemController', 'index',],
    'items/edit'             => ['ItemController', 'edit', ['id']],
    'items/show'             => ['ItemController', 'show', ['id']],
    'items/add'              => ['ItemController', 'add',],
    'items/delete'          => ['ItemController', 'delete',],
];
