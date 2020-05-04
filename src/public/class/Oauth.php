<?php

namespace src\omp;

class Oauth
{

    public static function oauthConfig()
	{
        $config = new \src\Configuration($_SERVER['ENVIRONMENT']);
        \OAuth2\Autoloader::register();
        $storage = new \OAuth2\Storage\Pdo([
            'dsn' => 'mysql:dbname=' . getenv('DB_DBNAME') . ';host=' . getenv('DB_HOST'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS')
        ]);
        $server = new \OAuth2\Server($storage);
        $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));

        return  $server;
    }
    
}