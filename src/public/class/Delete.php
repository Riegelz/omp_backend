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
}