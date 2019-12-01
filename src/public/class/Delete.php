<?php

namespace src\omp;

use src\omp\Model as Model;

class Delete extends General
{   
    public static function deleteAccountID($request,$response,$args)
	{
        $model = New Model();
        $ompID = $args['ompID'];
        $accountID = $args['accountID'];
        ## Search account ##
        $deleteAccountID = $model->deleteAccountID($ompID,$accountID);
        if(isset($deleteAccountID)) { return $response->withJson(General::responseFormat($deleteAccountID)); }
    }
    
    public static function deleteGroupID($request,$response,$args)
	{
        $model = New Model();
        $ompID = $args['ompID'];
        $groupID = $args['groupID'];
        ## Search account ##
        $deleteGroupID = $model->deleteGroupID($ompID,$groupID);
        if(isset($deleteGroupID)) { return $response->withJson(General::responseFormat($deleteGroupID)); }
    }
    
    public static function delGroupMember($request,$response)
    {
        $model = New Model();
        $reqbody = $request->getParsedBody();
        ## Check account have in DB ##
        $checkExistAccount = $model->checkExistAccount($reqbody); 
        if($checkExistAccount !== true) { return $response->withJson(General::responseFormat($checkExistAccount)); }
        ## Check group have in DB ##
        $checkExistGroup = $model->checkExistGroup($reqbody); 
        if($checkExistGroup !== true) { return $response->withJson(General::responseFormat($checkExistGroup)); }
        ## Del Group member ##
        $delGroupMember = $model->delGroupMember($reqbody);
        if(isset($delGroupMember)) { return $response->withJson(General::responseFormat($delGroupMember)); }
    }
}