<?php
namespace BiletCafe\Repository;

use BiletCafe\Entities\AgentRow;

interface AgentRowRepositoryInterface
{
    public function save(AgentRow $agent);

    public function delete(AgentRow $agent);

    public function deleteById($agentId);

    public function loadById($agentId);

    public function all();
}