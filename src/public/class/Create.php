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
    
        public static function createGroup($request,$response)
	{
                $model = New Model();
                $reqbody = $request->getParsedBody();
                ## check duplicate group in db ##
                $checkDuplicateGroup = $model->checkDuplicateGroup($reqbody); 
                if($checkDuplicateGroup !== true) { return $response->withJson(General::responseFormat($checkDuplicateGroup)); }
                // ## Create group ##
                $createNewGroup = $model->createNewGroup($reqbody);
                if(isset($createNewGroup)) { return $response->withJson(General::responseFormat(200,["id" => $createNewGroup])); }
        }
        
        public static function createProduct($request,$response)
	{
                $model = New Model();
                $reqbody = $request->getParsedBody();
                ## check duplicate product in db ##
                $checkDuplicateProduct = $model->checkDuplicateProduct($reqbody); 
                if($checkDuplicateProduct !== true) { return $response->withJson(General::responseFormat($checkDuplicateProduct)); }
                ## Create product ##
                $createNewProduct = $model->createNewProduct($reqbody);
                if(isset($createNewProduct)) { return $response->withJson(General::responseFormat(200,["id" => $createNewProduct])); }
        }
}