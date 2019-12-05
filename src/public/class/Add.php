<?php

namespace src\omp;

use src\omp\model\Group as Group;
use src\omp\model\Cost as Cost;

class Add extends General
{
        public function __construct() {
            ## String Name ##
            define("STRGROUPID", "group_id");
            define("STRPRODUCTID", "product_id");
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

        public static function AddLogisticsCost($request,$response)
	    {
            $self = New Self();
            $model = New Cost();
            $reqbody = $request->getParsedBody();
            ## Check group have in DB ##
            if ($reqbody[STRGROUPID] !== "0") {
                $checkExistGroup = $model->checkExistGroup($reqbody); 
                if($checkExistGroup !== true) { return $response->withJson(General::responseFormat($checkExistGroup)); }
            }
            ## Check product have in DB ##
            if ($reqbody[STRPRODUCTID] !== "0") {
                $checkExistProduct = $model->checkExistProduct($reqbody); 
                if($checkExistProduct !== true) { return $response->withJson(General::responseFormat($checkExistProduct)); }
            }
            ## Check logistics have in DB ##
            $checkExistLogistics = $model->checkExistLogistics($reqbody); 
            if($checkExistLogistics !== true) { return $response->withJson(General::responseFormat($checkExistLogistics)); }

            ## Check User have in group Logistics for add Cost ##
            $checkExistUserInGroup = $model->checkExistUserInGroup($reqbody); 
            if($checkExistUserInGroup !== true) { return $response->withJson(General::responseFormat($checkExistUserInGroup)); }

            ## Add Cost in DB ##
            $AddLogisticsCost = $model->AddLogisticsCost($reqbody);
            if(isset($AddLogisticsCost)) { return $response->withJson(General::responseFormat(200)); }
        }
}