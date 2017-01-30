<?php
return array(
    'bjyauthorize' => array(
        'unauthorized_strategy' => 'BjyAuthorize\View\RedirectionStrategy',
        'template' => 'error/403_custom',
        'guards' => array(
            \BjyAuthorize\Guard\Controller::class => array(
                ['controller' => 'Application\Controller\Index', 'roles' => ['guest', 'user']],
                ['controller' => 'Application\Controller\Message', 'roles' => ['guest', 'user']],
                ['controller' => 'Application\Controller\ImageUpload', 'roles' => ['guest', 'user']],
                ['controller' => 'zfcuser', 'roles' => []],
            ),

            \BjyAuthorize\Guard\Route::class => array(
                ['route' => 'zfcuser', 'roles' => ['user']],
                ['route' => 'zfcuser/logout', 'roles' => ['user']],
                ['route' => 'zfcuser/login', 'roles' => ['guest']],
                ['route' => 'zfcuser/register', 'roles' => ['guest']],

                ['route' => 'home', 'roles' => ['guest', 'user']],
                ['route' => 'home/thankyou', 'roles' => ['guest', 'user']],

                ['route' => 'message', 'roles' => ['user']],
                ['route' => 'message/default', 'roles' => ['user']],
                ['route' => 'message/paginator', 'roles' => ['user']],
                ['route' => 'message/list', 'roles' => ['guest', 'user']],

                ['route' => 'upload', 'roles' => ['guest', 'user']],
            )
        )
    )
);