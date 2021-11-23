<?php

return [
    //''                         => ['HomeController', 'index',],
    //'items'                    => ['ItemController', 'index',],
    //'items/edit'               => ['ItemController', 'edit', ['id']],
    //'items/show'               => ['ItemController', 'show', ['id']],
    //'items/add'                => ['ItemController', 'add',],
    //'items/delete'             => ['ItemController', 'delete',],
    ''                           => ['RegisterController', 'connect'],
    'activite/ajout-activite'    => ['ActivityController', 'add',],
    'activite/ajout-comment'     => ['ActivityController', 'addCommentByActivity', ['id']],
    'activite/ajout-proposition' => ['ProposeController', 'addPropose',],
    'activite/afficher'          => ['ActivityController', 'show', ['id'],],
    'activite/modif-active'      => ['ActivityController', 'updateActivityByIsActive', ['id']],
    'activite/tout-afficher'     => ['ActivityController', 'showAll', ['id'],],
    'user/inscription'           => ['RegisterController', 'add',],
    'deconnecter'                => ['RegisterController', 'logout'],
    'user/profil'                => ['RegisterController', 'profil', ['id']],
    'communauté'                 => ['CommunityController', 'showUser'],
    ];
