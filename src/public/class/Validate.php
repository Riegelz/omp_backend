<?php

namespace src\omp;

use Respect\Validation\Validator as v;

class Validate extends General
{

	public function __construct() {
		## JSON Name ##
		define("JSONOMPID", "omp_id");
		define("JSONSTATUS", "status");
		define("JSONACCOUNTNAME", "account_name");
		define("JSONUSERNAME", "username");
		define("JSONPASSWORD", "password");
		define("JSONACCOUNTROLE", "account_role");
		define("JSONGROUPNAME", "group_name");
		define("JSONID", "id");
    }

    public static function exec($request, $response)
	{
		if($request->getAttribute('has_errors')) {
			return $errors = $request->getAttribute('errors');
		}
		return null;
	}

	public static function validateCreateAccount()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONACCOUNTNAME => v::stringType()->length(5, 100)->notBlank(),
			JSONUSERNAME => v::stringType()->length(5, 20)->noWhitespace()->notBlank(),
			JSONPASSWORD => v::stringType()->length(5, 50)->noWhitespace()->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
			JSONACCOUNTROLE => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditAccount()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONACCOUNTNAME => v::stringType()->length(5, 100)->notBlank(),
			JSONUSERNAME => v::stringType()->length(5, 20)->noWhitespace()->notBlank(),
			JSONPASSWORD => v::stringType()->length(5, 50)->noWhitespace()->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
			JSONACCOUNTROLE => v::intVal()->between('0', '1'),
		);
	}

	public static function validateCreateGroup()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONGROUPNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditGroup()
	{
		$self = New Self();
		return array(
			JSONOMPID => v::intVal()->notBlank(),
			JSONID => v::intVal()->notBlank(),
			JSONGROUPNAME => v::stringType()->length(3, 100)->notBlank(),
			JSONSTATUS => v::intVal()->between('0', '1'),
		);
	}
}