<?php
namespace Iota\Middleware;

interface IMiddleware
{
    public function __invoke(\Api\Http\Response $response, $action, callable $next);
}