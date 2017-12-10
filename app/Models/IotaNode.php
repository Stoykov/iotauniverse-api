<?php
namespace Iota\Models;

class IotaNode
{
    /**
     * The name of the node
     * @var string
     */
    private $name;

    /**
     * IP Address of node
     * @var string
     */
    private $ip;

    /**
     * Port for Node API
     * @var int
     */
    private $apiPort;

    /**
     * A hostname for the Node (if available)
     * @var string
     */
    private $address;

    /**
     * Number of milestones of the node
     * @var int
     */
    private $milestones;

    /**
     * Country name where node is located
     * @var string
     */
    private $countryName;

    /**
     * ISO code of the country ( for flag display, etc. )
     * @var string
     */
    private $countryISO;

    /**
     * UPtime of the node
     * @var float
     */
    private $uptime;

    /**
     * Tips of node
     * @var int
     */
    private $tips;

    /**
     * Number of transactions to request
     * @var int
     */
    private $ttr; //Transactions To Request

    /**
     * Number of neighbour nodes
     * @var int
     */
    private $neighbours;

    /**
     * The name of the running node app
     * @var string
     */
    private $appName;

    /**
     * Version of node
     * @var string
     */
    private $version;

    /**
     * CPU load of node
     * @var int
     */
    private $cpu;

    /**
     * When was this node updated last ( UNIX Timestamp )
     * @var int
     */
    private $lastUpdate;

    public function __construct($data)
    {
        $this->name         = $data->name;
        $this->ip           = $data->ip;
        $this->apiPort      = $data->api_port;
        $this->address      = $data->address;
        $this->countryName  = $data->country->name;
        $this->countryISO   = $data->country->iso;

        $this->milestones   = $data->milestones;
        $this->tips         = $data->tips;
        $this->ttr          = $data->transactionsToRequest;
        $this->neighbours   = $data->neighbors;
        $this->appName      = $data->app_name;
        $this->version      = $data->version;
        $this->cpu          = $data->cpu_load;

        $this->lastUpdate   = $data->last_update;
    }

    /**
     * Update some datapoints of the node
     * @param  stdClass $data
     */
    public function saveData($data)
    {
        $this->milestones   = $data->latestMilestoneIndex;
        $this->tips         = $data->tips;
        $this->ttr          = $data->transactionsToRequest;
        $this->neighbours   = $data->neighbors;
        $this->appName      = $data->appName;
        $this->version      = $data->appVersion;

        $this->lastUpdate   = time();
    }

    /**
     * Set Milestones
     * @param int $value
     */
    public function setMilestones($value)
    {
        $this->milestones = $value;
    }

    /**
     * Set uptime
     * @param float $value
     */
    public function setUptime($value)
    {
        $this->uptime = $value;
    }

    /**
     * Set tips
     * @param int $value
     */
    public function setTips($value)
    {
        $this->tips = $value;
    }

    /**
     * Set TTR
     * @param int $value
     */
    public function setTtr($value)
    {
        $this->ttr = $value;
    }

    /**
     * Set number of neigbours
     * @param int $value
     */
    public function setNeighbours($value)
    {
        $this->neighbours = $value;
    }

    /**
     * Set version
     * @param string $value
     */
    public function setVersion($value)
    {
        $this->version = $value;
    }

    /**
     * Set CPU Load
     * @param int $value
     */
    public function setCpu($value)
    {
        $this->cpu = $value;
    }

    /**
     * Returns the node ip address
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Returns the node port
     * @return int
     */
    public function getPort()
    {
        return $this->apiPort;
    }

    /**
     * Returns the node data as an array
     * @return array
     */
    public function _toArray()
    {
        return [
            "name" => $this->name,
            "ip" => $this->ip,
            "address" => $this->address,
            "api_port" => $this->apiPort,
            "milestones" => $this->milestones,
            "country" => [
                "name" => $this->countryName,
                "iso" => $this->countryISO
            ],
            "uptime" => $this->uptime,
            "tips" => $this->tips,
            "transactionsToRequest" => $this->ttr,
            "neighbors" => $this->neighbours,
            "app_name" => $this->appName,
            "version" => $this->version,
            "cpu_load" => $this->cpu,
            "last_update" => $this->lastUpdate
        ];
    }

    /**
     * Returns the node data as a JSON string
     * @return string
     */
    public function _toJSON()
    {
        return json_encode($this->_toArray());
    }

    /**
     * Tries to return node as string (unsuccessfully)
     * @return string
     */
    public function _toString()
    {
        return 'This object cannot be converted to string.';
    }
}