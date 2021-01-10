<?php
return [
    //default connect
    'default'=>'default',
    'connections'=>[
        'default'=>[
            'dsn'=>'mysql:host=127.0.0.1;dbname=database_name',
            'username'=>'mysql_user_name',
            'password'=>'mysql_user_password',
            'prefix'=>'',
            'option'=>[
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
                PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',
            ],
        ],
    ],
];