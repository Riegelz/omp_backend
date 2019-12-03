<?php

namespace src\omp;

use src\omp\model\Group as Group;

class Add extends General
{
        public function __construct() {
            ## String Name ##
        }

        public static function addGroupMember($request,$response)
	    {
            $model = New Group();
            $reqbody = $request->getParsedBody();
            ## Check account have in DB ##
            $checkExistAccount = $model->checkExistAccount($reqbody); 
            if($checkExistAccount !== true) { return $response->withJson(General::responseFormat($checkExistAccount)); }
            ## Check group have in DB ##
            $checkExistGroup = $model->checkExistGroup($reqbody); 
            if($checkExistGroup !== true) { return $response->withJson(General::responseFormat($checkExistGroup)); }
            ## Check duplicate account member in group ##
            $checkDuplicateGroupMember = $model->checkDuplicateGroupMember($reqbody); 
            if($checkDuplicateGroupMember !== true) { return $response->withJson(General::responseFormat($checkDuplicateGroupMember)); }
            ## Add Group member ##
            $addGroupMember = $model->addGroupMember($reqbody);
            if(isset($addGroupMember)) { return $response->withJson(General::responseFormat(200)); }
        }
}