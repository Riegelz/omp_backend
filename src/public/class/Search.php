<?php

namespace src\omp;

use src\omp\model\Cost as Cost;
use src\omp\model\Order as Order;
use src\omp\model\Group as Group;
use src\omp\model\Account as Account;
use src\omp\model\Product as Product;
use src\omp\model\Promotion as Promotion;

class Search extends General
{
        public function __construct() {
                ## String Name ##
                define("SEARCHOMPID", "ompID");
                define("SEARCHACCOUNTID", "accountID");
                define("SEARCHGROUPID", "groupID");
                define("SEARCHPRODUCTID", "productID");
                define("SEARCHPROMOTIONID", "promotionID");
                define("SEARCHLOGISTICID", "logisticcostID");
                define("SEARCHORDERID", "orderID");
                define("SEARCHADSID", "adsID");
                define("SEARCHOMPUSER", "ompUser");
                define("SEARCHOMPTOKEN", "ompToken");
        }

	public static function searchAccount($request,$response,$args)
	{
                $self = New Self();
                $model = New Account();
                $ompID = $args[SEARCHOMPID];
                ## Search account ##
                $searchAccount = $model->searchAccount($ompID);
                if(isset($searchAccount)) { return $response->withJson(General::responseFormat(200,$searchAccount)); }
        }
    
        public static function searchAccountID($request,$response,$args)
        {
                $self = New Self();
                $model = New Account();
                $ompID = $args[SEARCHOMPID];
                $accountID = $args[SEARCHACCOUNTID];
                ## Search account ID ##
                $searchAccountID = $model->searchAccountID($ompID,$accountID);
                if(isset($searchAccountID)) { return $response->withJson(General::responseFormat(200,$searchAccountID)); }
        }

        public static function searchGroup($request,$response,$args)
	{
                $self = New Self();
                $model = New Group();
                $ompID = $args[SEARCHOMPID];
                ## Search group ##
                $searchGroup = $model->searchGroup($ompID);
                if(isset($searchGroup)) { return $response->withJson(General::responseFormat(200,$searchGroup)); }
        }

        public static function searchGroupByAccountID($request,$response,$args)
	{
                $self = New Self();
                $model = New Group();
                $ompID = $args[SEARCHOMPID];
                $accountID = $args[SEARCHACCOUNTID];
                ## Search group ##
                $searchGroupByAccountID = $model->searchGroupByAccountID($ompID,$accountID);
                if(isset($searchGroupByAccountID)) { return $response->withJson(General::responseFormat(200,$searchGroupByAccountID)); }
        }

        public static function searchGroupID($request,$response,$args)
        {
                $self = New Self();
                $model = New Group();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                ## Search group ID ##
                $searchGroupID = $model->searchGroupID($ompID,$groupID);
                if(isset($searchGroupID)) { return $response->withJson(General::responseFormat(200,$searchGroupID)); }
        }

        public static function searchGroupMember($request,$response,$args)
        {
                $self = New Self();
                $model = New Group();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                ## Search group member ##
                $searchGroupMember = $model->searchGroupMember($ompID,$groupID);
                if(isset($searchGroupMember)) { return $response->withJson(General::responseFormat(200,$searchGroupMember)); }
        }

        public static function searchGroupMemberByID($request,$response,$args)
        {
                $self = New Self();
                $model = New Group();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                $accountID = $args[SEARCHACCOUNTID];
                ## Search group member ##
                $searchGroupMemberByID = $model->searchGroupMemberByID($ompID,$groupID,$accountID);
                if(isset($searchGroupMemberByID)) { return $response->withJson(General::responseFormat(200,$searchGroupMemberByID)); }
        }

        public static function searchProductList($request,$response,$args)
	{
                $self = New Self();
                $model = New Product();
                $ompID = $args[SEARCHOMPID];
                ## Search product ##
                $searchProductList = $model->searchProductList($ompID,$groupID);
                if(isset($searchProductList)) { return $response->withJson(General::responseFormat(200,$searchProductList)); }
        }

        public static function searchProductListByGroupID($request,$response,$args)
	{
                $self = New Self();
                $model = New Product();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                ## Search product ##
                $searchProductListByGroupID = $model->searchProductListByGroupID($ompID,$groupID);
                if(isset($searchProductListByGroupID)) { return $response->withJson(General::responseFormat(200,$searchProductListByGroupID)); }
        }

        public static function searchProductListByProductID($request,$response,$args)
	{
                $self = New Self();
                $model = New Product();
                $ompID = $args[SEARCHOMPID];
                $productID = $args[SEARCHPRODUCTID];
                ## Search product ##
                $searchProductListByProductID = $model->searchProductListByProductID($ompID,$productID);
                if(isset($searchProductListByProductID)) { return $response->withJson(General::responseFormat(200,$searchProductListByProductID)); }
        }

        public static function searchPromotionList($request,$response,$args)
	{
                $self = New Self();
                $model = New Promotion();
                $ompID = $args[SEARCHOMPID];
                ## Search promotion ##
                $searchPromotionList = $model->searchPromotionList($ompID,$groupID);
                if(isset($searchPromotionList)) { return $response->withJson(General::responseFormat(200,$searchPromotionList)); }
        }

        public static function searchPromotionListByPromotionID($request,$response,$args)
	{
                $self = New Self();
                $model = New Promotion();
                $ompID = $args[SEARCHOMPID];
                $promotionID = $args[SEARCHPROMOTIONID];
                ## Search promotion ##
                $searchPromotionListByPromotionID = $model->searchPromotionListByPromotionID($ompID,$promotionID);
                if(isset($searchPromotionListByPromotionID)) { return $response->withJson(General::responseFormat(200,$searchPromotionListByPromotionID)); }
        }

        public static function searchPromotionListByGroupID($request,$response,$args)
	{
                $self = New Self();
                $model = New Promotion();
                $ompID = $args[SEARCHOMPID];
                $groupID = $args[SEARCHGROUPID];
                ## Search promotion ##
                $searchPromotionListByGroupID = $model->searchPromotionListByGroupID($ompID,$groupID);
                if(isset($searchPromotionListByGroupID)) { return $response->withJson(General::responseFormat(200,$searchPromotionListByGroupID)); }
        }

        public static function searchLogisticCostList($request,$response,$args)
	{
                $self = New Self();
                $model = New Cost();
                $ompID = $args[SEARCHOMPID];
                ## Search cost ##
                $searchLogisticCostList = $model->searchLogisticCostList($ompID);
                if(isset($searchLogisticCostList)) { return $response->withJson(General::responseFormat(200,$searchLogisticCostList)); }
        }

        public static function searchLogisticCostListByID($request,$response,$args)
	{
                $self = New Self();
                $model = New Cost();
                $ompID = $args[SEARCHOMPID];
                $logisticcostID = $args[SEARCHLOGISTICID];
                ## Search cost ##
                $searchLogisticCostListByID = $model->searchLogisticCostListByID($ompID,$logisticcostID);
                if(isset($searchLogisticCostListByID)) { return $response->withJson(General::responseFormat(200,$searchLogisticCostListByID)); }
        }

        public static function searchAdsCostList($request,$response,$args)
	{
                $self = New Self();
                $model = New Cost();
                $ompID = $args[SEARCHOMPID];
                ## Search cost ##
                $searchAdsCostList = $model->searchAdsCostList($ompID);
                if(isset($searchAdsCostList)) { return $response->withJson(General::responseFormat(200,$searchAdsCostList)); }
        }

        public static function searchAdsCostListByID($request,$response,$args)
	{
                $self = New Self();
                $model = New Cost();
                $ompID = $args[SEARCHOMPID];
                $adsID = $args[SEARCHADSID];
                ## Search cost ##
                $searchAdsCostListByID = $model->searchAdsCostListByID($ompID,$adsID);
                if(isset($searchAdsCostListByID)) { return $response->withJson(General::responseFormat(200,$searchAdsCostListByID)); }
        }

        public static function searchOrderList($request,$response,$args)
	{
                $self = New Self();
                $model = New Order();
                $ompID = $args[SEARCHOMPID];
                ## Search cost ##
                $searchOrderList = $model->searchOrderList($ompID);
                if(isset($searchOrderList)) { return $response->withJson(General::responseFormat(200,$searchOrderList)); }
        }

        public static function searchOrderListByID($request,$response,$args)
	{
                $self = New Self();
                $model = New Order();
                $ompID = $args[SEARCHOMPID];
                $orderID = $args[SEARCHORDERID];
                ## Search cost ##
                $searchOrderListByID = $model->searchOrderListByID($ompID,$orderID);
                if(isset($searchOrderListByID)) { return $response->withJson(General::responseFormat(200,$searchOrderListByID)); }
        }

        public static function searchOmpID($request,$response,$args)
	{
                $self = New Self();
                $model = New Account();
                $ompuser = $args[SEARCHOMPUSER];
                $omptoken = $args[SEARCHOMPTOKEN];
                ## Search OMP ID ##
                $searchOmpID = $model->searchOmpID($ompuser,$omptoken);
                if(isset($searchOmpID)) { return $response->withJson(General::responseFormat(200,$searchOmpID)); }
        }

        public static function Login($request,$response)
	{
                $self = New Self();
                $model = New Account();
                $reqbody = $request->getParsedBody();
                ## Search Account login ##
                $Login = $model->Login($reqbody);
                if(isset($Login['accounts'])) { return $response->withJson(General::responseFormat(200,["id" => $Login])); }
        }
}