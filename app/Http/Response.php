<?php
namespace Iota\Http;

/**
 * Response
 *
 * This class represents an HTTP response. It manages
 * the response status, headers, and body.
 */
class Response
{
    /**
     * Status code
     *
     * @var int
     */
    protected $status = 200;
    /**
     * Should the response be logged
     *
     * @var bool
     */
    protected $log = true;
    /**
     * cURL Error String
     *
     * @var string
     */
    private $error = '';
    /**
     * Status codes and reason phrases
     *
     * @var array
     */
    protected static $messages = [
        //Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        //Successful 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        //Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        //Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        //Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];
    /**
     * EOL characters used for HTTP response.
     *
     * @var string
     */
     const EOL = "\r\n";
    /**
     * Create new HTTP response.
     *
     * @param int                   $status  The response status code.
     * @param Headers|null          $headers The response headers.
     * @param array|null            $body    The response body.
     */
    public function __construct($status = 200, Headers $headers = null, array $body = null)
    {
        $this->status = $this->filterStatus($status);
        $this->headers = $headers ? $headers : new Headers();
        $this->body = $body ? $body : [];
    }
    /*******************************************************************************
     * Status
     ******************************************************************************/
    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->status;
    }
    /**
     * Sets the response status code.
     *
     * @return void
     */
    public function setStatusCode($status)
    {
        $this->status = $this->filterStatus($status);
    }
    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name)
    {
        return $this->headers->get($name, []);
    }
    /**
     * Sets the headers of the response
     *
     * @param Api\Http\Headers $headers A collection of all response headers
     * @return void
     */
    public function setHeaders(Headers $headers)
    {
        $this->headers = $headers ? $headers : new Headers();
    }
    /*******************************************************************************
     * Body
     ******************************************************************************/
    /**
     * Gets the body of the response.
     *
     * @return array Returns the body as an array.
     */
    public function getBody()
    {
        return $this->body;
    }
    /**
     * Sets the body of the response.
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
    /*******************************************************************************
     * Log
     ******************************************************************************/
    /**
     * Gets the log var of the response.
     *
     * @return bool Returns the log var.
     */
    public function getLog()
    {
        return $this->log;
    }
    /**
     * Sets the log variable which indicates whether a response should be logged or not.
     *
     * @return void
     */
    public function setLog($log)
    {
        $this->log = $log;
    }
    /*******************************************************************************
     * Error
     ******************************************************************************/
    /**
     * Gets the cURL error string.
     *
     * @return string Returns the cURL error string.
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * Sets the cURL error string.
     *
     * @return void
     */
    public function setError($error)
    {
        $this->error = $error;
    }
    /**
     * Filter HTTP status code.
     *
     * @param  int $status HTTP status code.
     * @return int
     * @throws \InvalidArgumentException If an invalid HTTP status code is provided.
     */
    protected function filterStatus($status)
    {
        if (!is_integer($status) || $status<100 || $status>599) {
            throw new \Exception('Invalid HTTP status code');
        }
        return $status;
    }
    /**
     * Convert response to string.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return string
     */
    public function __toString()
    {
        $output = sprintf(
            'HTTP/%s %s %s',
            $this->getProtocolVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        );
        $output .= Response::EOL;
        foreach ($this->getHeaders() as $name => $values) {
            $output .= sprintf('%s: %s', $name, $this->getHeaderLine($name)) . Response::EOL;
        }
        $output .= Response::EOL;
        $output .= (string)$this->getBody();
        return $output;
    }
}