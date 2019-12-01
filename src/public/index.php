<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__ . '/../../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DavidePastore\Slim\Validation\Validation as Validation;
use src\omp\Add as Add;
use src\omp\Edit as Edit;
use src\omp\Model as Model;
use src\omp\Oauth as Oauth;
use src\omp\Search as Search;
use src\omp\Create as Create;
use src\omp\Delete as Delete;
use src\omp\General as General;
use src\omp\Validate as Validate;

$oauth = src\omp\Oauth::oauthConfig();
$app = new \Slim\App(['settings' => ['displayErrorDetails' => getenv('DEBUG_MODE')]]);
$authen = src\omp\Authen::middleware($app, $oauth);

$app->post('/token', function (Request $request, Response $response) use ($oauth) {
	$tokens = $oauth->handleTokenRequest(OAuth2\Request::createFromGlobals())->getResponseBody();$request;
	return $response->withJson(json_decode($tokens));
});

$app->group('/api/v1', function () use ($app) {

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

    $app->delete('/account/omp/{ompID}/id/{accountID}', function(Request $request, Response $response, $args) {
		return Delete::deleteAccountID($request,$response,$args);
	});
    
    $app->get('/account/omp/{ompID}/list', function(Request $request, Response $response, $args) {
		return Search::searchAccount($request,$response,$args);
    });
    
    $app->get('/account/omp/{ompID}/id/{accountID}', function(Request $request, Response $response, $args) {
		return Search::searchAccountID($request,$response,$args);
    });
    
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

    $app->delete('/group/omp/{ompID}/id/{groupID}', function(Request $request, Response $response, $args) {
		return Delete::deleteGroupID($request,$response,$args);
    });
    
    $app->get('/group/omp/{ompID}/list', function(Request $request, Response $response, $args) {
		return Search::searchGroup($request,$response,$args);
    });

    $app->get('/group/omp/{ompID}/id/{groupID}', function(Request $request, Response $response, $args) {
		return Search::searchGroupID($request,$response,$args);
    });

    $app->get('/group/omp/{ompID}/id/{groupID}/member', function(Request $request, Response $response, $args) {
		return Search::searchGroupMember($request,$response,$args);
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

$app->run();