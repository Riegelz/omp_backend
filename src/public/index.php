<?php
date_default_timezone_set("Asia/Bangkok");
require __DIR__ . '/../../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DavidePastore\Slim\Validation\Validation as Validation;
use src\omp\Edit as Edit;
use src\omp\Model as Model;
use src\omp\Oauth as Oauth;
use src\omp\Search as Search;
use src\omp\Create as Create;
use src\omp\General as General;
use src\omp\Validate as Validate;

$oauth = src\omp\Oauth::oauthConfig();
$app = new \Slim\App(['settings' => ['displayErrorDetails' => getenv('DEBUG_MODE')]]);
$authen = src\omp\Authen::middleware($app, $oauth);

$app->post('/token', function (Request $request, Response $response) use ($oauth) {
	$tokens = $oauth->handleTokenRequest(OAuth2\Request::createFromGlobals())->getResponseBody();
	return $response->withJson(json_decode($tokens));
});

$app->group('/api/v1', function () use ($app) {

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
    
    $app->get('/account/omp/{ompID}/list', function(Request $request, Response $response, $args) {
		return Search::searchAccount($request,$response,$args);
    });
    
    $app->get('/account/omp/{ompID}/id/{accountID}', function(Request $request, Response $response, $args) {
		return Search::searchAccountID($request,$response,$args);
	});

})->add($authen);

$app->run();