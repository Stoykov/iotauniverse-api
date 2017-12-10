<?php
namespace Iota\Repositories;

use Iota\Models\IotaNode;

class NodesRepository
{
    /**
     * Should we use cache?
     * @var boolean
     */
    private $cache = false;

    /**
     * Storage file name
     * @var string
     */
    private $file = '';

    /**
     * List of available nodes
     * @var array
     */
    private $nodes = [];

    public function __construct($file, $cache = false)
    {
        $this->cache = $cache;
        $this->file = storage_dir($file);
    }

    /**
     * Save the nodes to a persistant storage
     */
    public function save()
    {
        $this->saveToFile($this->file, $this->nodesToArray());
    }

    /**
     * Convert nodes list to array
     * @return array
     */
    public function nodesToArray()
    {
        $nodes = [];
        foreach ($this->nodes as $node) {
            $nodes[] = $node->_toArray();
        }

        return $nodes;
    }

    /**
     * Get all available nodes
     * @return array Array of IotaNodes
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Save data to a file
     * @param  string $file Path to storage file
     * @param  mixed  $data The data you want to save
     */
    public function saveToFile($file, $data)
    {
        if (is_file($file))
            file_put_contents($file, is_array($data) ? json_encode($data) : $data);
    }

    /**
     * Load Nodes
     */
    public function loadNodes()
    {
        if (!$this->cache)
            $this->fromFile();
    }

    /**
     * Load nodes from file
     */
    private function fromFile()
    {
        $nodes = $this->loadFromFile();
        if ($nodes) {
            foreach ($nodes as $node) {
                $this->nodes[] = new IotaNode($node);
            }
        }
    }

    /**
     * Loads nodes from file storage
     * @return bool|stdClass
     */
    private function loadFromFile()
    {
        if (is_file($this->file)) {
            return $this->parseFile();
        } else {
            return false;
        }
    }

    /**
     * Parses the storage file
     * @return stdClass The JSON string parsed as stdClass
     */
    private function parseFile()
    {
        return json_decode(file_get_contents($this->file));
    }
}