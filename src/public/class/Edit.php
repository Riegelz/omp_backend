<?php

namespace src\omp;

use src\omp\model\Cost as Cost;
use src\omp\model\Order as Order;
use src\omp\model\Group as Group;
use src\omp\model\Account as Account;
use src\omp\model\Product as Product;
use src\omp\model\Promotion as Promotion;

class Edit extends General
{

        public function __construct() {
                ## String Name ##
                define("STRGROUPID", "group_id");
                define("STRPRODUCTID", "product_id");
        }

	public static function editAccount($request,$response)
	{
                $model = New Account();
                $reqbody = $request->getParsedBody();
                ## Edit account ##
                $editAccount = $model->editAccount($reqbody);
                if(isset($editAccount)) { return $response->withJson(General::responseFormat()); }
        }
        
        public static function editGroup($request,$response)
	{
                $model = New Group();
                $reqbody = $request->getParsedBody();
                ## Edit group ##
                $editGroup = $model->editGroup($reqbody);
                if(isset($editGroup)) { return $response->withJson(General::responseFormat()); }
        }
        
        public static function editProduct($request,$response)
	{
                $model = New Product();
                $reqbody = $request->getParsedBody();
                ## Edit product ##
                $editProduct = $model->editProduct($reqbody);
                if(isset($editProduct)) { return $response->withJson(General::responseFormat()); }
        }

        public static function editPromotion($request,$response)
	{
                $model = New Promotion();
                $reqbody = $request->getParsedBody();
                ## Edit promotion ##
                $editPromotion = $model->editPromotion($reqbody);
                if(isset($editPromotion)) { return $response->withJson(General::responseFormat()); }
        }

        public static function EditLogisticsCost($request,$response)
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
                
                ## Edit logistics cost ##
                $EditLogisticsCost = $model->EditLogisticsCost($reqbody);
                if(isset($EditLogisticsCost)) { return $response->withJson(General::responseFormat()); }
        }

        public static function EditAdsCost($request,$response)
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
                ## Check ads have in DB ##
                $checkExistAds = $model->checkExistAds($reqbody); 
                if($checkExistAds !== true) { return $response->withJson(General::responseFormat($checkExistAds)); }

                ## Check User have in group Ads for add Cost ##
                $checkExistUserInGroup = $model->checkExistUserInGroup($reqbody); 
                if($checkExistUserInGroup !== true) { return $response->withJson(General::responseFormat($checkExistUserInGroup)); }
                
                ## Edit logistics cost ##
                $EditAdsCost = $model->EditAdsCost($reqbody);
                if(isset($EditAdsCost)) { return $response->withJson(General::responseFormat()); }
        }

        public static function EditOrder($request,$response)
	{
                $self = New Self();
                $model = New Order();
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

                ## Check User have in group Ads for add Cost ##
                $checkExistUserInGroup = $model->checkExistUserInGroup($reqbody); 
                if($checkExistUserInGroup !== true) { return $response->withJson(General::responseFormat($checkExistUserInGroup)); }
                
                ## Edit Order ##
                $EditOrder = $model->EditOrder($reqbody);
                if(isset($EditOrder)) { return $response->withJson(General::responseFormat()); }
        }
}