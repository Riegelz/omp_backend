<?php

namespace src\omp;

use src\omp\Model as Model;

class Search extends General
{
	public static function searchAccount($request,$response,$args)
	{
        $model = New Model();
        $ompID = $args['ompID'];
        ## Search account ##
        $searchAccount = $model->searchAccount($ompID);
        if(isset($searchAccount)) { return $response->withJson(General::responseFormat(200,$searchAccount)); }
    }
    
    public static function searchAccountID($request,$response,$args)
	{
        $model = New Model();
        $ompID = $args['ompID'];
        $accountID = $args['accountID'];
        ## Search account ##
        $searchAccountID = $model->searchAccountID($ompID,$accountID);
        if(isset($searchAccountID)) { return $response->withJson(General::responseFormat(200,$searchAccountID)); }
	}
}