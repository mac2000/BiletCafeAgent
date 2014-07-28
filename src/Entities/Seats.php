<?php
namespace BiletCafe\Entities;

use BiletCafe\Tickets\Seat;
use mac\bit\Bit;

trait Seats
{
    use Bit;

    /**
     * @var int
     */
    public $seats;

    protected function isSeatIncluded($class = Seat::ALL)
    {
        return $this->isFlagSet($class, $this->seats);
    }

    protected function includeSeat($class = Seat::ALL)
    {
        $this->setFlag($class, $this->seats);
    }

    protected function excludeSeat($class = Seat::ALL)
    {
        $this->unsetFlag($class, $this->seats);
    }

    protected function toggleSeat($class = Seat::ALL)
    {
        $this->toggleFlag($class, $this->seats);
    }



    public function isLowerSeatsIncluded()
    {
        return $this->isSeatIncluded(Seat::LOWER);
    }

    public function isUpperSeatsIncluded()
    {
        return $this->isSeatIncluded(Seat::UPPER);
    }

    public function isSideLowerSeatsIncluded()
    {
        return $this->isSeatIncluded(Seat::SIDE_LOWER);
    }

    public function isSideUpperSeatsIncluded()
    {
        return $this->isSeatIncluded(Seat::SIDE_UPPER);
    }

    public function includeLowerSeats()
    {
        $this->includeSeat(Seat::LOWER);
    }

    public function includeUpperSeats()
    {
        $this->includeSeat(Seat::UPPER);
    }

    public function includeSideLowerSeats()
    {
        $this->includeSeat(Seat::SIDE_LOWER);
    }

    public function includeSideUpperSeats()
    {
        $this->includeSeat(Seat::SIDE_UPPER);
    }

    public function excludeLowerSeats()
    {
        $this->excludeSeat(Seat::LOWER);
    }

    public function excludeUpperSeats()
    {
        $this->excludeSeat(Seat::UPPER);
    }

    public function excludeSideLowerSeats()
    {
        $this->excludeSeat(Seat::SIDE_LOWER);
    }

    public function excludeSideUpperSeats()
    {
        $this->excludeSeat(Seat::SIDE_UPPER);
    }

    public function toggleLowerSeats()
    {
        $this->toggleSeat(Seat::LOWER);
    }

    public function toggleUpperSeats()
    {
        $this->toggleSeat(Seat::UPPER);
    }

    public function toggleSideLowerSeats()
    {
        $this->toggleSeat(Seat::SIDE_LOWER);
    }

    public function toggleSideUpperSeats()
    {
        $this->toggleSeat(Seat::SIDE_UPPER);
    }
}