<?php
namespace BiletCafe\Repository;

use BiletCafe\Entities\AgentRow;

class ArrayAgentRowRepository implements AgentRowRepositoryInterface
{
    private $items = array();

    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    public function save(AgentRow $agent)
    {
        $this->items[$agent->id] = $agent;
    }

    public function delete(AgentRow $agent)
    {
        unset($this->items[$agent->id]);
    }

    public function deleteById($agentId)
    {
        unset($this->items[$agentId]);
    }

    public function loadById($agentId)
    {
        return $this->items[$agentId];
    }

    public function all()
    {
        return $this->items;
    }
}
 