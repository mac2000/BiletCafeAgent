<?php
namespace BiletCafe\Tickets\Seat;

use BiletCafe\Tickets\Seat;

class ReservedSeat extends Seat
{
    protected $subclass;

    public function __construct($lower = 0, $upper = 0, $sideLower = 0, $sideUpper = 0, $subclass = null)
    {
        parent::__construct($lower, $upper, $sideLower, $sideUpper);
        $this->subclass = $subclass;
    }

    public function calculateTotalSeats($flags = Seat::ALL, $subclass = null)
    {
        $sum = parent::calculateTotalSeats($flags);

        return !$subclass || $this->subclass == $subclass ? $sum : 0;
    }
}