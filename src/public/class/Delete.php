<?php

namespace src\omp;

use src\omp\model\Group as Group;
use src\omp\model\Account as Account;
use src\omp\model\Product as Product;

class Delete extends General
{   
    public function __construct() {
        ## String Name ##
        define("STROMPID", "ompID");
    }

    public static function deleteAccountID($request,$response,$args)
	{
        $self = New Self();
        $model = New Account();
        $ompID = $args[STROMPID];
        $accountID = $args['accountID'];
        ## Search account ##
        $deleteAccountID = $model->deleteAccountID($ompID,$accountID);
        if($deleteAccountID === 200) { $model->deleteAccountAllGroup($accountID); }
        if(isset($deleteAccountID)) { return $response->withJson(General::responseFormat($deleteAccountID)); }
    }
    
    public static function deleteGroupID($request,$response,$args)
	{
        $self = New Self();
        $model = New Group();
        $ompID = $args[STROMPID];
        $groupID = $args['groupID'];
        ## Search account ##
        $deleteGroupID = $model->deleteGroupID($ompID,$groupID);
        if(isset($deleteGroupID)) { return $response->withJson(General::responseFormat($deleteGroupID)); }
    }
    
    public static function delGroupMember($request,$response)
    {
        $model = New Group();
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

    public static function deleteProductID($request,$response,$args)
	{
        $self = New Self();
        $model = New Product();
        $ompID = $args[STROMPID];
        $productID = $args['productID'];
        ## Search account ##
        $deleteProductID = $model->deleteProductID($ompID,$productID);
        if(isset($deleteProductID)) { return $response->withJson(General::responseFormat($deleteProductID)); }
    }
}