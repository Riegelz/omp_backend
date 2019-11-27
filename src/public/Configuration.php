<?php
namespace src;

class Configuration 
{
	public function __construct($env)
	{
		$env = (empty($env)) ? 'local' : $env;
		$envFile = '.env.' . $env;

		$dotenv = \Dotenv\Dotenv::create(dirname(dirname(__DIR__)), $envFile);
		$dotenv->load();
	}
}
