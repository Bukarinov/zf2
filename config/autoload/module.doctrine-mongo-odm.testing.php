<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'odm_default' => array(
                'server'           => 'localhost',
                'port'             => '27017',
                'connectionString' => null,
                'user'             => null,
                'password'         => null,
                'dbname'           => 'veeam_test',
                'options'          => array(),
            ),
        ),
        'configuration' => array(
            'odm_default' => array(
                'default_db'         => 'veeam_test',
            )
        ),
    ),
);
