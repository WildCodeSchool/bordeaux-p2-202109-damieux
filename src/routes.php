<?php

return [
    ''                      => ['HomeController', 'index',],
    //'items'               => ['ItemController', 'index',],
    //'items/edit'          => ['ItemController', 'edit', ['id']],
    //'items/show'          => ['ItemController', 'show', ['id']],
    //'items/add'           => ['ItemController', 'add',],
    //'items/delete'        => ['ItemController', 'delete',],
    'activity/addActivity'  => ['ActivityController', 'add',],
    'activity/addPropose'   => ['ProposeController', 'addPropose',],
    'activity/show'         => ['ActivityController', 'show', ['id'],],
    'userData/inscription'  => ['RegisterController', 'add',],
    'logout'                => ['RegisterController', 'logout'],
    'userData/profil'       => ['RegisterController', 'profil', ['id']],
];
