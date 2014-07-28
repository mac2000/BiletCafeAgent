<?php
namespace BiletCafe\Entities;

use DateTime;
use mac\bit\Bit;

class AgentModel
{
    use Bit;

    use Classes;
    use Seats;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;

    /**
     * @var DateTime
     */
    public $departAfter;

    /**
     * @var DateTime
     */
    public $arriveBefore;

    public $concreteTrain;
    public $exceptTrains;




}