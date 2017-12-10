<?php
namespace Iota\Middleware;

class MiddlewareRegister
{
    private $tip;
    private $middlewareLock = false;

    public function __construct()
    {
    }

    /**
     * Add a middleware to the stack
     * @param  callable $middleware Middleware callable accepting a response array and an action string
     *
     * @return MiddlewareRegister
     */
    public function register(callable $middleware)
    {
        if ($this->middlewareLock) {
            throw new \Exception('Middleware can’t be added once the stack is dequeuing', 1);
        }
        if (is_null($this->tip)) {
            $this->seedStack();
        }
        $next = $this->tip;
        $this->tip = function ($response, $action) use (
            $middleware,
            $next
        ) {
            return call_user_func($middleware, $response, $action, $next);
        };

        return $this;
    }

    /**
     * Seed middleware stack with first callable
     *
     * @param callable $kernel The last item to run as middleware
     */
    protected function seedStack(callable $kernel = null)
    {
        if (!is_null($this->tip)) {
            throw new \Exception('MiddlewareStack can only be seeded once.');
        }
        if ($kernel === null) {
            $kernel = function ($response, $action) {
                return $response;
            };
        }
        $this->tip = $kernel;
    }

    /**
     * Process middleware stack
     *
     * @param  array      $response A response array
     *
     * @return array        Modified response array
     */
    public function process(\Api\Http\Response $response, $action)
    {
        if (is_null($this->tip)) {
            $this->seedStack();
        }
        /** @var callable $start */
        $start = $this->tip;
        $this->middlewareLock = true;
        $response = $start($response, $action);
        $this->middlewareLock = false;
        return $response;
    }
}