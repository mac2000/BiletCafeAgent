<?php
namespace BiletCafe\Tests;

use BiletCafe\Tickets\Seat\ComfortableSeat;
use BiletCafe\Tickets\Seat\FirstClassSeat;
use BiletCafe\Tickets\Seat\NonReservedSeat;
use BiletCafe\Tickets\Seat\ReservedSeat;
use BiletCafe\Tickets\Seat\SecondClassSeat;
use BiletCafe\Tickets\Seat\ThirdClassSeat;
use BiletCafe\Tickets\Seat;
use BiletCafe\Tickets\Station;
use BiletCafe\Tickets\Train;
use DateTime;
use PHPUnit_Framework_TestCase;

class TrainTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Train
     */
    protected $train;

    public function setUp()
    {
        $station = new Station('1', 'one');
        $date = new DateTime();
        $seats = [
            new FirstClassSeat(1, 1, 1, 1),
            new SecondClassSeat(1, 1, 1, 1),
            new ThirdClassSeat(1, 1, 1, 1),
            new ReservedSeat(1, 1, 1, 1, 1),
            new ReservedSeat(1, 1, 1, 1, 2),
            new NonReservedSeat(1, 1, 1, 1),
            new ComfortableSeat(1, 1, 1, 1),
        ];
        $this->train = new Train('one', $station, $station, $date, $date, $seats);
    }

    public function testNumberMatch()
    {
        $this->assertTrue($this->train->numberMatch('one'));
        $this->assertTrue($this->train->numberMatch('o'));
        $this->assertFalse($this->train->numberMatch('two'));
    }

    public function testNumberInArray()
    {
        $this->assertTrue($this->train->numberInArray(array('foo', 'bar', 'one')));
        $this->assertTrue($this->train->numberInArray(array('foo', 'bar', 'o')));
        $this->assertFalse($this->train->numberInArray(array('foo', 'bar', 'two')));
    }

    public function testCalculateTotal()
    {
        $this->assertEquals(28, $this->train->calculateTotalSeats());
        $this->assertEquals(0, $this->train->calculateTotalSeats(Train::NONE, Seat::NONE));
        $this->assertEquals(20, $this->train->calculateTotalSeats(Train::ALL & ~Train::RESERVED));
        $this->assertEquals(1, $this->train->calculateTotalSeats(Train::FIRST, Seat::UPPER));
        $this->assertEquals(1, $this->train->calculateTotalSeats(Train::RESERVED, Seat::UPPER, 2));
        $this->assertEquals(1, $this->train->calculateTotalSeats(Train::RESERVED, Seat::UPPER, 1));
        $this->assertEquals(24, $this->train->calculateTotalSeats(Train::ALL, Seat::ALL, 1));
        $this->assertEquals(24, $this->train->calculateTotalSeats(Train::ALL, Seat::ALL, 2));
        $this->assertEquals(20, $this->train->calculateTotalSeats(Train::ALL, Seat::ALL, 3));
    }

    public function testIsDepartureAfter()
    {
        $this->assertFalse($this->train->isDepartureAfter(new DateTime('+1 hour')));
        $this->assertFalse($this->train->isDepartureAfter($this->train->departure));
        $this->assertTrue($this->train->isDepartureAfter(new DateTime('-1 hour')));
    }

    public function testIsArrivalBefore()
    {
        $this->assertFalse($this->train->isArriveBefore(new DateTime('-1 hour')));
        $this->assertFalse($this->train->isArriveBefore($this->train->arrival));
        $this->assertTrue($this->train->isArriveBefore(new DateTime('+1 hour')));
    }
}