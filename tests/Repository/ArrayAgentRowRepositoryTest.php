<?php
namespace BiletCafe\Tests\Repository;

use BiletCafe\Repository\ArrayAgentRowRepository;
use PHPUnit_Framework_TestCase;

class ArrayAgentRowRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayAgentRowRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = new ArrayAgentRowRepository();
    }

    public function testCanAddAgentRow()
    {
        $this->assertCount(0, $this->repository->all());
    }
}