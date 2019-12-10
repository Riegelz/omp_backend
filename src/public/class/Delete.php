<?php

namespace src\omp;

use src\omp\Rule as Rule;
use src\omp\model\Cost as Cost;
use src\omp\model\Order as Order;
use src\omp\model\Group as Group;
use src\omp\model\Account as Account;
use src\omp\model\Product as Product;
use src\omp\model\Promotion as Promotion;

class Delete extends General
{   
    public function __construct() {
        ## String Name ##
        define("STROMPID", "ompID");
        define("STRACCOUNTID", "accountID");
        define("STRGROUPID", "groupID");
        define("STRGROUPROLE", "group_role");
        define("OMP_ADMIN", getenv('OMP_ADMIN'));
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
        ## Delete account ##
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
        $rule = New Rule();
        $model = New Product();
        $ompID = $args[STROMPID];
        $accountID = $args[STRACCOUNTID];
        $groupID = $args[STRGROUPID];
        $productID = $args['productID'];

        if ($ompID !== OMP_ADMIN) {
            ## Get Role in Group ##
            $getRoleInGroup = $model->getRoleInGroup($accountID,$groupID);
            if(!is_array($getRoleInGroup)) { return $response->withJson(General::responseFormat($getRoleInGroup)); }

            ## Check Account Permission Rules ##
            $PermissionDeleteProduct = $rule->PermissionMedium($getRoleInGroup[STRGROUPROLE]);
            if($PermissionDeleteProduct !== true) { return $response->withJson(General::responseFormat($PermissionDeleteProduct)); }
        }

        ## Delete product ##
        $deleteProductID = $model->deleteProductID($ompID,$productID);
        if(isset($deleteProductID)) { return $response->withJson(General::responseFormat($deleteProductID)); }
    }

    public static function deletePromotionID($request,$response,$args)
	{
        $self = New Self();
        $rule = New Rule();
        $model = New Promotion();
        $ompID = $args[STROMPID];
        $accountID = $args[STRACCOUNTID];
        $groupID = $args[STRGROUPID];
        $promotionID = $args['promotionID'];

        if ($ompID !== OMP_ADMIN) {
            ## Get Role in Group ##
            $getRoleInGroup = $model->getRoleInGroup($accountID,$groupID);
            if(!is_array($getRoleInGroup)) { return $response->withJson(General::responseFormat($getRoleInGroup)); }

            ## Check Account Permission Rules ##
            $PermissionDeletePromotion = $rule->PermissionMedium($getRoleInGroup[STRGROUPROLE]);
            if($PermissionDeletePromotion !== true) { return $response->withJson(General::responseFormat($PermissionDeletePromotion)); }
        }

        ## Delete promotion ##
        $deletePromotionID = $model->deletePromotionID($ompID,$promotionID);
        if(isset($deletePromotionID)) { return $response->withJson(General::responseFormat($deletePromotionID)); }
    }

    public static function deleteLogisticsCostID($request,$response,$args)
	{
        $self = New Self();
        $rule = New Rule();
        $model = New Cost();
        $ompID = $args[STROMPID];
        $accountID = $args[STRACCOUNTID];
        $groupID = $args[STRGROUPID];
        $logisticsCostID = $args['logisticsCostID'];

        if ($ompID !== OMP_ADMIN) {
            ## Get Role in Group ##
            $getRoleInGroup = $model->getRoleInGroup($accountID,$groupID);
            if(!is_array($getRoleInGroup)) { return $response->withJson(General::responseFormat($getRoleInGroup)); }

            ## Check Account Permission Rules ##
            $PermissionDeleteLogisticsCost = $rule->PermissionAll($getRoleInGroup[STRGROUPROLE]);
            if($PermissionDeleteLogisticsCost !== true) { return $response->withJson(General::responseFormat($PermissionDeleteLogisticsCost)); }
        }

        ## Delete Logistics cost ##
        $deleteLogisticsCostID = $model->deleteLogisticsCostID($ompID,$logisticsCostID);
        if(isset($deleteLogisticsCostID)) { return $response->withJson(General::responseFormat($deleteLogisticsCostID)); }
    }

    public static function deleteAdsID($request,$response,$args)
	{
        $self = New Self();
        $rule = New Rule();
        $model = New Cost();
        $ompID = $args[STROMPID];
        $accountID = $args[STRACCOUNTID];
        $groupID = $args[STRGROUPID];
        $adsID = $args['adsID'];

        if ($ompID !== OMP_ADMIN) {
            ## Get Role in Group ##
            $getRoleInGroup = $model->getRoleInGroup($accountID,$groupID);
            if(!is_array($getRoleInGroup)) { return $response->withJson(General::responseFormat($getRoleInGroup)); }

            ## Check Account Permission Rules ##
            $PermissionDeleteAdsCost = $rule->PermissionAll($getRoleInGroup[STRGROUPROLE]);
            if($PermissionDeleteAdsCost !== true) { return $response->withJson(General::responseFormat($PermissionDeleteAdsCost)); }
        }

        ## Delete Logistics cost ##
        $deleteAdsID = $model->deleteAdsID($ompID,$adsID);
        if(isset($deleteAdsID)) { return $response->withJson(General::responseFormat($deleteAdsID)); }
    }

    public static function deleteOrderID($request,$response,$args)
	{
        $self = New Self();
        $rule = New Rule();
        $model = New Order();
        $ompID = $args[STROMPID];
        $accountID = $args[STRACCOUNTID];
        $groupID = $args[STRGROUPID];
        $orderID = $args['orderID'];

        if ($ompID !== OMP_ADMIN) {
            ## Get Role in Group ##
            $getRoleInGroup = $model->getRoleInGroup($accountID,$groupID);
            if(!is_array($getRoleInGroup)) { return $response->withJson(General::responseFormat($getRoleInGroup)); }

            ## Check Account Permission Rules ##
            $PermissionDeleteAdsCost = $rule->PermissionMedium($getRoleInGroup[STRGROUPROLE]);
            if($PermissionDeleteAdsCost !== true) { return $response->withJson(General::responseFormat($PermissionDeleteAdsCost)); }
        }

        ## Delete Logistics cost ##
        $deleteOrderID = $model->deleteOrderID($ompID,$orderID);
        if(isset($deleteOrderID)) { return $response->withJson(General::responseFormat($deleteOrderID)); }
    }
}