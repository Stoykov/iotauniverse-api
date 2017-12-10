<?php
namespace Iota\Http;

/**
 * Headers
 *
 * This class represents a collection of HTTP headers
 * that is used in the HTTP response object.
 * It also enables header name case-insensitivity when
 * getting or setting a header value.
 */
class Headers
{
    /**
     * Special HTTP headers that do not have the "HTTP_" prefix
     *
     * @var array
     */
    protected static $special = [
        'CONTENT_TYPE' => 1,
        'CONTENT_LENGTH' => 1,
        'PHP_AUTH_USER' => 1,
        'PHP_AUTH_PW' => 1,
        'PHP_AUTH_DIGEST' => 1,
        'AUTH_TYPE' => 1,
    ];

    /**
     * All headers array
     *
     * @var array
     */
    private $data = [];

    /**
     * Return array of HTTP header names and values.
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }
    /**
     * Set HTTP header value
     *
     * This method sets a header value. It replaces
     * any values that may already exist for the header name.
     *
     * @param string $key   The case-insensitive header name
     * @param string $value The header value
     */
    public function set($key, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $key = $this->normalizeKey($key);
        $this->data[$key] = $value;
    }
    /**
     * Get HTTP header value
     *
     * @param  string  $key     The case-insensitive header name
     * @param  mixed   $default The default value if key does not exist
     *
     * @return string[]
     */
    public function get($key, $default = null)
    {
        $key = $this->normalizeKey($key);
        return $this->has($key) ? $this->data[$key] : $default;
    }
    /**
     * Does this collection have a given header?
     *
     * @param  string $key The case-insensitive header name
     *
     * @return bool
     */
    public function has($key)
    {
        $key = $this->normalizeKey($key);
        return array_key_exists($key, $this->data);
    }
    /**
     * Remove header from collection
     *
     * @param  string $key The case-insensitive header name
     */
    public function remove($key)
    {
        $key = $this->normalizeKey($key);
        unset($this->data[$key]);
    }
    /**
     * Normalize header name
     *
     * This method transforms header names into a
     * normalized form. This is how we enable case-insensitive
     * header names in the other methods in this class.
     *
     * @param  string $key The case-insensitive header name
     *
     * @return string Normalized header name
     */
    public function normalizeKey($key)
    {
        $key = strtr(strtolower($key), '_', '-');
        if (strpos($key, 'http-') === 0) {
            $key = substr($key, 5);
        }
        return $key;
    }
}