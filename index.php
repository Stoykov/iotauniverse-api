<?php
//Bootstraping
date_default_timezone_set('UTC');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

require_once(__DIR__.'/vendor/autoload.php');

// Load environment variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Bootstrap middlewares
//$middlewares = new \Api\Middleware\MiddlewareRegister();

//$middlewares->register( new \Api\Middleware\SensitiveMiddleware() ); // Removes all sensitive data from response
//$middlewares->register( new \Api\Middleware\LogMiddleware() ); // Logs actions and responses
//$middlewares->register( new \Api\Middleware\AlertMiddleware() ); // Checks if there is something fishy with response and alerts support staff

$domain = $_SERVER['HTTP_HOST'];
$action = $_GET['action'];
$uri = $_SERVER['REQUEST_URI'];

// Load file with helpers
require_once(__DIR__ . '/helpers.php');

//End of bootstrap

// Process the request
switch ($uri) {
    case '/':
    case '/index.php':
        $component = new Iota\Components\IndexComponent();
        $response = $component->index();
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        $response = new Iota\Http\Response();
        $response->setStatusCode(404);
        $response->setBody(["message" => "Not Found"]);
        break;
}

// Test alert system
/*
$response = new \Api\Http\Response();
$response->setStatusCode(400);
$response->setBody([
        "test" => "Testing alert system",
        "token" => "uga buga",
        "call_url" => "https://yourass",
        "call_data" => "i'm fucking salty today"
    ]);
$response->setError('MOAR ERRORS');

$action = "placebet";
*/
// Process middlewears before sending the response to the client
//$response = $middlewares->process($response, $action);

// Add some needed headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('P3P: CP="NID DSP ALL COR"');

// Echo the response back to the client
echo json_encode($response->getBody());