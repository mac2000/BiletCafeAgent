<?php
namespace BiletCafe\Tickets;

use BiletCafe\Tickets\Seat\ComfortableSeat;
use BiletCafe\Tickets\Seat\FirstClassSeat;
use BiletCafe\Tickets\Seat\NonReservedSeat;
use BiletCafe\Tickets\Seat\ReservedSeat;
use BiletCafe\Tickets\Seat\SeatException;
use BiletCafe\Tickets\Seat\SecondClassSeat;
use BiletCafe\Tickets\Seat\ThirdClassSeat;

abstract class Seat
{
    const NONE = 0;
    const LOWER = 1;
    const UPPER = 2;
    const SIDE_LOWER = 4;
    const SIDE_UPPER = 8;
    const ALL = 15;

    /**
     * @var int
     */
    public $upper;

    /**
     * @var int
     */
    public $lower;

    /**
     * @var int
     */
    public $side_upper;

    /**
     * @var int
     */
    public $side_lower;

    public function __construct($lower = 0, $upper = 0, $side_lower = 0, $side_upper = 0)
    {
        $this->lower = $lower;
        $this->upper = $upper;
        $this->side_lower = $side_lower;
        $this->side_upper = $side_upper;
    }

    public static function createFromTicketsTrain(array $data)
    {
        if ($data['name'] === 'first') {
            return new FirstClassSeat($data['seats']['lower'], $data['seats']['upper'], $data['seats']['side_lower'], $data['seats']['side_upper']);
        } elseif ($data['name'] === 'second') {
            return new SecondClassSeat($data['seats']['lower'], $data['seats']['upper'], $data['seats']['side_lower'], $data['seats']['side_upper']);
        } elseif ($data['name'] === 'third') {
            return new ThirdClassSeat($data['seats']['lower'], $data['seats']['upper'], $data['seats']['side_lower'], $data['seats']['side_upper']);
        } elseif ($data['name'] === 'reserved') {
            return new ReservedSeat($data['seats']['lower'], $data['seats']['upper'], $data['seats']['side_lower'], $data['seats']['side_upper'], $data['subclass']);
        } elseif ($data['name'] === 'non_reserved') {
            return new NonReservedSeat($data['seats']['lower'], $data['seats']['upper'], $data['seats']['side_lower'], $data['seats']['side_upper']);
        } elseif ($data['name'] === 'comfortable') {
            return new ComfortableSeat($data['seats']['lower'], $data['seats']['upper'], $data['seats']['side_lower'], $data['seats']['side_upper']);
        }

        throw new SeatException('Invalid seat class data given');
    }

    public function calculateTotalSeats($flags = Seat::ALL)
    {
        $sum = 0;

        if((self::LOWER & $flags) > 0) {
            $sum += $this->lower;
        }

        if((self::UPPER & $flags) > 0) {
            $sum += $this->upper;
        }

        if((self::SIDE_LOWER & $flags) > 0) {
            $sum += $this->side_lower;
        }

        if((self::SIDE_UPPER & $flags) > 0) {
            $sum += $this->side_upper;
        }

        return $sum;
    }
}