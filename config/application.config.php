<?php
return array(
    'modules' => array(
        'Application',
        //'Album',
        //'Livraria',
        //'Login',
        'DoctrineModule',
        'DoctrineORMModule',
        'Aws'
        //'ZfcBase',
        //'ZfcUser',
        //'ScnSocialAuth'
        //'Livraria',
        
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
