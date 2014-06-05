<?php
namespace BiletCafe\Tests;

use BiletCafe\Tickets\Seat;
use BiletCafe\Tickets\Seat\ComfortableSeat;
use BiletCafe\Tickets\Seat\FirstClassSeat;
use BiletCafe\Tickets\Seat\NonReservedSeat;
use BiletCafe\Tickets\Seat\ReservedSeat;
use BiletCafe\Tickets\Seat\SecondClassSeat;
use BiletCafe\Tickets\Seat\ThirdClassSeat;
use PHPUnit_Framework_TestCase;

class SeatTest extends PHPUnit_Framework_TestCase
{
    protected $data = array(
        'name' => 'first',
        'subclass' => 1,
        'seats' => array(
            'lower' => 1,
            'upper' => 2,
            'side_lower' => 3,
            'side_upper' => 4
        )
    );

    public function testCreation()
    {
        $this->data['name'] = 'first';
        $seat = Seat::createFromTicketsTrain($this->data);
        $this->assertTrue($seat instanceof FirstClassSeat);

        $this->data['name'] = 'second';
        $seat = Seat::createFromTicketsTrain($this->data);
        $this->assertTrue($seat instanceof SecondClassSeat);

        $this->data['name'] = 'third';
        $seat = Seat::createFromTicketsTrain($this->data);
        $this->assertTrue($seat instanceof ThirdClassSeat);

        $this->data['name'] = 'reserved';
        $seat = Seat::createFromTicketsTrain($this->data);
        $this->assertTrue($seat instanceof ReservedSeat);

        $this->data['name'] = 'non_reserved';
        $seat = Seat::createFromTicketsTrain($this->data);
        $this->assertTrue($seat instanceof NonReservedSeat);

        $this->data['name'] = 'comfortable';
        $seat = Seat::createFromTicketsTrain($this->data);
        $this->assertTrue($seat instanceof ComfortableSeat);

        $this->assertEquals(1, $seat->lower);
        $this->assertEquals(2, $seat->upper);
        $this->assertEquals(3, $seat->side_lower);
        $this->assertEquals(4, $seat->side_upper);
    }

    /**
     * @expectedException BiletCafe\Tickets\Seat\SeatException
     */
    public function testInvalidData()
    {
        $this->data['name'] = 'foo';
        $seat = Seat::createFromTicketsTrain($this->data);
    }

    public function testCalculateTotal()
    {
        $seat = new FirstClassSeat(1, 1, 1, 1);

        $this->assertEquals(4, $seat->calculateTotalSeats(Seat::ALL));
        $this->assertEquals(3, $seat->calculateTotalSeats(Seat::ALL & ~Seat::SIDE_UPPER));
        $this->assertEquals(2, $seat->calculateTotalSeats(Seat::UPPER | Seat::LOWER));
        $this->assertEquals(1, $seat->calculateTotalSeats(Seat::SIDE_LOWER));
        $this->assertEquals(0, $seat->calculateTotalSeats(Seat::NONE));
    }

    public function testReservedCalculateTotal()
    {
        $seat = new ReservedSeat(1, 1, 1, 1, 1);

        $this->assertEquals(4, $seat->calculateTotalSeats(Seat::ALL, 1));
        $this->assertEquals(3, $seat->calculateTotalSeats(Seat::ALL & ~Seat::SIDE_UPPER, 1));
        $this->assertEquals(2, $seat->calculateTotalSeats(Seat::UPPER | Seat::LOWER, 1));
        $this->assertEquals(1, $seat->calculateTotalSeats(Seat::SIDE_LOWER, 1));
        $this->assertEquals(0, $seat->calculateTotalSeats(Seat::NONE, 1));

        $this->assertEquals(0, $seat->calculateTotalSeats(Seat::ALL, 2));
        $this->assertEquals(0, $seat->calculateTotalSeats(Seat::NONE, 1));
    }
}