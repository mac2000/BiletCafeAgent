<?php
namespace BiletCafe\Tickets;

class Station
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    public function __construct($code, $name)
    {
        $this->code = $code;
        $this->name = $name;
    }
}
