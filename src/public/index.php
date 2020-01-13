<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__ . '/../../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DavidePastore\Slim\Validation\Validation as Validation;
use src\omp\Add as Add;
use src\omp\Edit as Edit;
use src\omp\Rule as Rule;
use src\omp\Oauth as Oauth;
use src\omp\Search as Search;
use src\omp\Create as Create;
use src\omp\Delete as Delete;
use src\omp\General as General;
use src\omp\Validate as Validate;
use src\omp\model\Group as Group;
use src\omp\model\Account as Account;
use src\omp\model\Product as Product;

$oauth = src\omp\Oauth::oauthConfig();
$app = new \Slim\App(['settings' => ['displayErrorDetails' => getenv('DEBUG_MODE')]]);
$authen = src\omp\Authen::middleware($app, $oauth);

$app->post('/token', function (Request $request, Response $response) use ($oauth) {
	$tokens = $oauth->handleTokenRequest(OAuth2\Request::createFromGlobals())->getResponseBody();$request;
	return $response->withJson(json_decode($tokens));
});

$app->group('/api/v1/account', function () use ($app) {

    #### Account API ####

    $app->post('/create_account', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Create::createAccount($request,$response);
        }
        
    })->add(new Validation(Validate::validateCreateAccount()));
    
    $app->post('/edit_account', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::editAccount($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditAccount()));

    $app->delete('/omp/{ompID}/id/{accountID}', function(Request $request, Response $response, $args) {
		return Delete::deleteAccountID($request,$response,$args);
	});
    
    $app->get('/omp/{ompID}/account_list', function(Request $request, Response $response, $args) {
		return Search::searchAccount($request,$response,$args);
    });
    
    $app->get('/omp/{ompID}/id/{accountID}', function(Request $request, Response $response, $args) {
		return Search::searchAccountID($request,$response,$args);
    });

})->add($authen);

$app->group('/api/v1/group', function () use ($app) {

    #### Group API ####

    $app->post('/create_group', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Create::createGroup($request,$response);
        }
        
    })->add(new Validation(Validate::validateCreateGroup()));

    $app->post('/edit_group', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::editGroup($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditGroup()));

    $app->delete('/omp/{ompID}/id/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deleteGroupID($request,$response,$args);
    });
    
    $app->get('/omp/{ompID}/group_list', function(Request $request, Response $response, $args) {
		return Search::searchGroup($request,$response,$args);
    });

    $app->get('/omp/{ompID}/group_list/aid/{accountID}', function(Request $request, Response $response, $args) {
		return Search::searchGroupByAccountID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/id/{groupID}', function(Request $request, Response $response, $args) {
		return Search::searchGroupID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/id/{groupID}/member', function(Request $request, Response $response, $args) {
		return Search::searchGroupMember($request,$response,$args);
    });

    $app->get('/omp/{ompID}/id/{groupID}/member/aid/{accountID}', function(Request $request, Response $response, $args) {
		return Search::searchGroupMemberByID($request,$response,$args);
    });

    $app->post('/add_group_member', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Add::addGroupMember($request,$response);
        }
        
    })->add(new Validation(Validate::validateAddGroupMember()));

    $app->post('/del_group_member', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Delete::delGroupMember($request,$response);
        }
        
    })->add(new Validation(Validate::validateDelGroupMember()));

})->add($authen);

$app->group('/api/v1/product', function () use ($app) {

    #### Product API ####

    $app->post('/create_product', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Create::createProduct($request,$response);
        }
        
    })->add(new Validation(Validate::validateCreateProduct()));

    $app->post('/edit_product', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::editProduct($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditProduct()));

    $app->delete('/omp/{ompID}/id/{productID}/aid/{accountID}/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deleteProductID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/product_list', function(Request $request, Response $response, $args) {
		return Search::searchProductList($request,$response,$args);
    });
    
    $app->get('/omp/{ompID}/product_list/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Search::searchProductListByGroupID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/product_list/pid/{productID}', function(Request $request, Response $response, $args) {
		return Search::searchProductListByProductID($request,$response,$args);
    });

})->add($authen);

$app->group('/api/v1/promotion', function () use ($app) {

    #### Promotion API ####

    $app->post('/create_promotion', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Create::createPromotion($request,$response);
        }
        
    })->add(new Validation(Validate::validateCreatePromotion()));

    $app->post('/edit_promotion', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::editPromotion($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditPromotion()));

    $app->delete('/omp/{ompID}/id/{promotionID}/aid/{accountID}/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deletePromotionID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/promotion_list', function(Request $request, Response $response, $args) {
		return Search::searchPromotionList($request,$response,$args);
    });

    $app->get('/omp/{ompID}/promotion_list/pid/{promotionID}', function(Request $request, Response $response, $args) {
		return Search::searchPromotionListByPromotionID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/promotion_list/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Search::searchPromotionListByGroupID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/promotion_list/productid/{productID}', function(Request $request, Response $response, $args) {
		return Search::searchPromotionListByProductID($request,$response,$args);
    });

})->add($authen);

$app->group('/api/v1/cost', function () use ($app) {

    #### Logistic Cost API ####
    $app->post('/add_logistics_cost', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Add::AddLogisticsCost($request,$response);
        }
        
    })->add(new Validation(Validate::validateAddLogisticsCost()));

    $app->post('/edit_logistics_cost', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::EditLogisticsCost($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditLogisticsCost()));

    $app->delete('/omp/{ompID}/id/{logisticsCostID}/aid/{accountID}/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deleteLogisticsCostID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/logisticcost_list', function(Request $request, Response $response, $args) {
		return Search::searchLogisticCostList($request,$response,$args);
    });

    $app->get('/omp/{ompID}/logisticcost_list/lid/{logisticcostID}', function(Request $request, Response $response, $args) {
		return Search::searchLogisticCostListByID($request,$response,$args);
    });

    #### Ads Cost API ####
    $app->post('/add_ads_cost', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Add::AddAdsCost($request,$response);
        }
        
    })->add(new Validation(Validate::validateAddAdsCost()));

    $app->post('/edit_ads_cost', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::EditAdsCost($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditAdsCost()));

    $app->delete('/omp/{ompID}/adsid/{adsID}/aid/{accountID}/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deleteAdsID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/adscost_list', function(Request $request, Response $response, $args) {
		return Search::searchAdsCostList($request,$response,$args);
    });

    $app->get('/omp/{ompID}/adscost_list/adsid/{adsID}', function(Request $request, Response $response, $args) {
		return Search::searchAdsCostListByID($request,$response,$args);
    });

})->add($authen);

$app->group('/api/v1/order', function () use ($app) {

    #### Order API ####
    $app->post('/add_order', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Add::AddOrder($request,$response);
        }
        
    })->add(new Validation(Validate::validateAddOrder()));

    $app->post('/edit_order', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Edit::EditOrder($request,$response);
        }
        
    })->add(new Validation(Validate::validateEditOrder()));

    $app->delete('/omp/{ompID}/id/{orderID}/aid/{accountID}/gid/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deleteOrderID($request,$response,$args);
    });

    $app->get('/omp/{ompID}/order_list', function(Request $request, Response $response, $args) {
		return Search::searchOrderList($request,$response,$args);
    });

    $app->get('/omp/{ompID}/order_list/oid/{orderID}', function(Request $request, Response $response, $args) {
		return Search::searchOrderListByID($request,$response,$args);
    });

})->add($authen);

$app->group('/api/v1/auth', function () use ($app) {

    #### Login API ####

    $app->post('/login', function (Request $request, Response $response) {
        
        $errors = Validate::exec($request, $response);
		if(!empty($errors)) {
            return $response->withJson(General::responseFormat(400, $errors));
        }else{
            return Search::Login($request,$response);
        }
        
    })->add(new Validation(Validate::validateLogin()));

    $app->get('/ompuser/{ompUser}/omptoken/{ompToken}', function(Request $request, Response $response, $args) {
		return Search::searchOmpID($request,$response,$args);
    });

})->add($authen);

$app->group('/api/v1/other', function () use ($app) {

    #### Other API ####

    $app->get('/paymentlists', function(Request $request, Response $response, $args) {
		return Search::searchPayment($request,$response,$args);
    });

    $app->get('/logisticlists', function(Request $request, Response $response, $args) {
		return Search::searchLogistic($request,$response,$args);
    });

    $app->get('/adslists', function(Request $request, Response $response, $args) {
		return Search::searchAds($request,$response,$args);
    });

    $app->get('/province', function(Request $request, Response $response, $args) {
		return Search::searchProvince($request,$response,$args);
    });

    $app->get('/districts/province/{provinceID}', function(Request $request, Response $response, $args) {
		return Search::searchDistricts($request,$response,$args);
    });

    $app->get('/subdistricts/districts/{districtsID}', function(Request $request, Response $response, $args) {
		return Search::searchSubdistricts($request,$response,$args);
    });

})->add($authen);

$app->run();