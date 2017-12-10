<?php
namespace Iota\Components;

use Iota\Repositories\NodesRepository;

class IndexComponent extends Component
{
    public function index()
    {
        $nodes = new NodesRepository('nodes.json');
        $nodes->loadNodes();

        $this->response->setStatusCode(200);
        $this->response->setBody($nodes->nodesToArray());
        return $this->response;
    }
}