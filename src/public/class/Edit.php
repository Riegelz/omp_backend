<?php

namespace src\omp;

use src\omp\model\Group as Group;
use src\omp\model\Account as Account;
use src\omp\model\Product as Product;

class Edit extends General
{
	public static function editAccount($request,$response)
	{
                $model = New Account();
                $reqbody = $request->getParsedBody();
                ## Edit account ##
                $editAccount = $model->editAccount($reqbody);
                if(isset($editAccount)) { return $response->withJson(General::responseFormat()); }
        }
        
        public static function editGroup($request,$response)
	{
                $model = New Group();
                $reqbody = $request->getParsedBody();
                ## Edit account ##
                $editGroup = $model->editGroup($reqbody);
                if(isset($editGroup)) { return $response->withJson(General::responseFormat()); }
        }
        
        public static function editProduct($request,$response)
	{
                $model = New Product();
                $reqbody = $request->getParsedBody();
                ## Edit account ##
                $editProduct = $model->editProduct($reqbody);
                if(isset($editProduct)) { return $response->withJson(General::responseFormat()); }
        }
}