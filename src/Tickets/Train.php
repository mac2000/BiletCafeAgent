<?php
namespace BiletCafe\Tickets;

use BiletCafe\Tickets\Seat\ComfortableSeat;
use BiletCafe\Tickets\Seat\FirstClassSeat;
use BiletCafe\Tickets\Seat\NonReservedSeat;
use BiletCafe\Tickets\Seat\ReservedSeat;
use BiletCafe\Tickets\Seat\SecondClassSeat;
use BiletCafe\Tickets\Seat\ThirdClassSeat;
use DateTime;
use mac\bit\BitParam;

class Train
{
    use BitParam;

    const NONE = 0;
    const FIRST = 1;
    const SECOND = 2;
    const THIRD = 4;
    const RESERVED = 8;
    const NON_RESERVED = 16;
    const COMFORTABLE = 32;
    const ALL = 63;

    /**
     * @var string
     */
    public $number;

    /**
     * @var Station
     */
    public $stationFrom;

    /**
     * @var Station
     */
    public $stationTo;

    /**
     * @var DateTime
     */
    public $departure;

    /**
     * @var DateTime
     */
    public $arrival;

    /**
     * @var array
     */
    public $seats;

    public function __construct(
        $number,
        Station $stationFrom,
        Station $stationTo,
        DateTime $departure,
        DateTime $arrival,
        array $seats
    ) {
        $this->number = $number;

        $this->stationTo = $stationTo;
        $this->stationFrom = $stationFrom;

        $this->departure = $departure;
        $this->arrival = $arrival;

        $this->seats = $seats;
    }

    public function calculateTotalSeats($classFlags = Train::ALL, $seatFlags = Seat::ALL, $subclass = null)
    {
        $sum = 0;

        foreach ($this->seats as $seat) {
            if ($this->isBitSet(self::FIRST, $classFlags) && $seat instanceof FirstClassSeat) {
                $sum += $seat->calculateTotalSeats($seatFlags);
            }

            if ($this->isBitSet(self::SECOND, $classFlags) && $seat instanceof SecondClassSeat) {
                $sum += $seat->calculateTotalSeats($seatFlags);
            }

            if ($this->isBitSet(self::THIRD, $classFlags) && $seat instanceof ThirdClassSeat) {
                $sum += $seat->calculateTotalSeats($seatFlags);
            }

            if ($this->isBitSet(self::RESERVED, $classFlags) && $seat instanceof ReservedSeat) {
                $sum += $seat->calculateTotalSeats($seatFlags, $subclass);
            }

            if ($this->isBitSet(self::NON_RESERVED, $classFlags) && $seat instanceof NonReservedSeat) {
                $sum += $seat->calculateTotalSeats($seatFlags);
            }

            if ($this->isBitSet(self::COMFORTABLE, $classFlags) && $seat instanceof ComfortableSeat) {
                $sum += $seat->calculateTotalSeats($seatFlags);
            }
        }

        return $sum;
    }

    public function numberMatch($number)
    {
        return strpos($this->number, $number) !== false;
    }

    public function numberInArray(array $numbers)
    {
        foreach ($numbers as $number) {
            if ($this->numberMatch($number)) {
                return true;
            }
        }
        return false;
    }

    public function isDepartureAfter(DateTime $date)
    {
        return $date < $this->departure;
    }

    public function isArriveBefore(DateTime $date)
    {
        return $this->arrival < $date;
    }
}
