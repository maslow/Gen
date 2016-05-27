<?php
/**
 * Template config file of database.
 */
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=gen',
    'username' => 'root',
    'password' => '###Please enter your password here.###',
    'charset' => 'utf8',
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    'tablePrefix'=>'gen_',
];
