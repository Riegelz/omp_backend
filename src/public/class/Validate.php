<?php

namespace src\omp;

use Respect\Validation\Validator as v;

class Validate extends General
{

    public static function exec($request, $response)
	{
		if($request->getAttribute('has_errors')) {
			return $errors = $request->getAttribute('errors');
		}
		return null;
	}

	public static function validateCreateAccount()
	{
		return array(
			'omp_id' => v::intVal()->notBlank(),
			'account_name' => v::stringType()->length(5, 100)->notBlank(),
			'username' => v::stringType()->length(5, 20)->noWhitespace()->notBlank(),
			'password' => v::stringType()->length(5, 50)->noWhitespace()->notBlank(),
			'status' => v::intVal()->between('0', '1'),
			'account_role' => v::intVal()->between('0', '1'),
		);
	}

	public static function validateEditAccount()
	{
		return array(
			'omp_id' => v::intVal()->notBlank(),
			'id' => v::intVal()->notBlank(),
			'account_name' => v::stringType()->length(5, 100)->notBlank(),
			'username' => v::stringType()->length(5, 20)->noWhitespace()->notBlank(),
			'password' => v::stringType()->length(5, 50)->noWhitespace()->notBlank(),
			'status' => v::intVal()->between('0', '1'),
			'account_role' => v::intVal()->between('0', '1'),
		);
	}
}