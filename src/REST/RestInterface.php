<?php
namespace BiletCafe\REST;

interface RestInterface
{
    public function create(AgentModel $agent);

    public function read($id);

    public function update($id, AgentModel $agent);

    public function delete($id);
}