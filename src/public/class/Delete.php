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
}