<?php
//Bootstraping
date_default_timezone_set('UTC');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

require_once(__DIR__.'/vendor/autoload.php');

// Load environment variables
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Bootstrap middlewares
//$middlewares = new \Iota\Middleware\MiddlewareRegister();

//$middlewares->register( new \Iota\Middleware\SomeMiddleware() ); // Removes all sensitive data from response

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

// Process middlewears before sending the response to the client
//$response = $middlewares->process($response, $action);

// Add some needed headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('P3P: CP="NID DSP ALL COR"');

// Echo the response back to the client
echo json_encode($response->getBody());