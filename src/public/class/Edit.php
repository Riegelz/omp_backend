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
        
        public static function editGroup($request,$response)
	{
                $model = New Model();
                $reqbody = $request->getParsedBody();
                ## Edit account ##
                $editGroup = $model->editGroup($reqbody);
                if(isset($editGroup)) { return $response->withJson(General::responseFormat()); }
        }
        
        public static function editProduct($request,$response)
	{
                $model = New Model();
                $reqbody = $request->getParsedBody();
                ## Edit account ##
                $editProduct = $model->editProduct($reqbody);
                if(isset($editProduct)) { return $response->withJson(General::responseFormat()); }
        }
}