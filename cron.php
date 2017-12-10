<?php
use Iota\Repositories\NodesRepository;

date_default_timezone_set('UTC');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

require_once(__DIR__.'/vendor/autoload.php');
// Load file with helpers
require_once(__DIR__ . '/helpers.php');

function make_request($ip, $port) {
    $curl = curl_init('http://' . $ip . ':' . $port);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, '{"command": "getNodeInfo"}');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-IOTA-API-Version: 1',
        'Content-Type: application/json')
    );

    $curl_result = curl_exec($curl);
    if($curl_result){
        $data = json_decode($curl_result);

        curl_close($curl);
        return $data;
    }
    $error = curl_error($curl);
    curl_close($curl);

    return false;
}

$nodes = new NodesRepository('nodes.json');
$nodes->loadNodes();

foreach ($nodes->getNodes() as $node) {
    if ($info = make_request($node->getIp(), $node->getPort())) {
        $node->saveData($info);
    }
}

$nodes->save();