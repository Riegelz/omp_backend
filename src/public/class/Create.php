<?php

namespace src\omp;

use src\omp\Model as Model;

class Create extends General
{
	public static function createAccount($request,$response)
	{
        $model = New Model();
        $reqbody = $request->getParsedBody();
        ## check duplicate account in db ##
        $checkDuplicateAccount = $model->checkDuplicateAccount($reqbody); 
        if($checkDuplicateAccount !== true) { return $response->withJson(General::responseFormat($checkDuplicateAccount)); }
        ## Create account ##
        $createNewAccount = $model->createNewAccount($reqbody);
        if(isset($createNewAccount)) { return $response->withJson(General::responseFormat(200,["id" => $createNewAccount])); }
	}
}