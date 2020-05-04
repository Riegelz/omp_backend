<?php
namespace src\omp;

class Authen
{
    public static function middleware($app, $server)
    {
        return function ($request, $response, $next) use ($server) {
            print_r(\OAuth2\Request::createFromGlobals());exit;
            if (!$server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
                http_response_code(401);
                header('Content-type:application/json');
                echo json_encode([
                    'error' => 401,
                    'error_description' => 'Unauthorized'
                ]);
                exit;
            } else {
                return $next($request, $response);
            }
        };
    }
}
