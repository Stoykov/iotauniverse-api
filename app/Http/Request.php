<?php
namespace Iota\Http;

class Request
{

    /**
     * Get an input parameter from the request
     * @param  string   $key     the input parameter / key
     * @param  mixed    $default default value returned if the key doesn't exist
     * @return mixed|bool
     */
    public function input($key = null, $default = null)
    {
        if (!isset($_REQUEST[$key]) && $default) {
            return $default;
        } else if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        } else {
            return false;
        }
    }

    /**
     * Get the real IP of the client
     * @return string
     */
    public function getIP()
    {
        return getenv('HTTP_CLIENT_IP')?:
        getenv('HTTP_X_FORWARDED_FOR')?:
        getenv('HTTP_X_FORWARDED')?:
        getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
        getenv('REMOTE_ADDR');
    }
}