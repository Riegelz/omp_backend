<?php

namespace src\omp;

use src\omp\Model as Model;

class Edit extends General
{
	public static function editAccount($request,$response)
	{
        $model = New Model();
        $reqbody = $request->getParsedBody();
        ## Edit account ##
        $editAccount = $model->editAccount($reqbody);
        if(isset($editAccount)) { return $response->withJson(General::responseFormat()); }
	}
}