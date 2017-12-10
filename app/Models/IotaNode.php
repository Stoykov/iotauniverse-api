<?php
namespace Iota\Models;

class IotaNode
{
    private $name;
    private $ip;
    private $apiPort;
    private $address;
    private $milestones;
    private $countryName;
    private $countryISO;
    private $uptime;
    private $tips;
    private $ttr; //Transactions To Request
    private $neighbours;
    private $appName;
    private $version;
    private $cpu;

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
        $this->neighbours   = $data->neighbours;
        $this->appName      = $data->appName;
        $this->version      = $data->appVersion;
        $this->cpu          = $data->cpu_load;

        $this->lastUpdate   = $data->last_update;
    }

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

    public function setMilestones($value)
    {
        $this->milestones = $value;
    }

    public function setUptime($value)
    {
        $this->uptime = $value;
    }

    public function setTips($value)
    {
        $this->tips = $value;
    }

    public function setTtr($value)
    {
        $this->ttr = $value;
    }

    public function setNeighbours($value)
    {
        $this->neighbours = $value;
    }

    public function setVersion($value)
    {
        $this->version = $value;
    }

    public function setCpu($value)
    {
        $this->cpu = $value;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getPort()
    {
        return $this->apiPort;
    }

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

    public function _toJSON()
    {
        return json_encode($this->_toArray());
    }

    public function _toString()
    {
        return 'This object cannot be converted to string.';
    }
}