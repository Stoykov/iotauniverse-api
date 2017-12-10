<?php
namespace Iota\Components;

use Iota\Http\Request;
use Iota\Http\Response;

class Component
{
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }
}