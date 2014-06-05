<?php
namespace BiletCafe\Tests;

use BiletCafe\Agent;
use BiletCafe\Tickets\Seat\ComfortableSeat;
use BiletCafe\Tickets\Seat\FirstClassSeat;
use BiletCafe\Tickets\Seat\NonReservedSeat;
use BiletCafe\Tickets\Seat\ReservedSeat;
use BiletCafe\Tickets\Seat\SecondClassSeat;
use BiletCafe\Tickets\Seat\ThirdClassSeat;
use BiletCafe\Tickets\Seat;
use BiletCafe\Tickets\Station;
use BiletCafe\Tickets\Train;
use BiletCafe\Tickets;
use DateTime;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class AgentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $trains;

    /**
     * @var Agent
     */
    protected $agent;

    /**
     * @var Tickets
     */
    protected $tickets;

    protected function createTrain($number, $date)
    {
        $station = new Station('1', 'one');
        $date = new DateTime($date);
        $seats = [
            new FirstClassSeat(1, 1, 1, 1),
            new SecondClassSeat(1, 1, 1, 1),
            new ThirdClassSeat(1, 1, 1, 1),
            new ReservedSeat(1, 1, 1, 1, 1),
            new ReservedSeat(1, 1, 1, 1, 2),
            new NonReservedSeat(1, 1, 1, 1),
            new ComfortableSeat(1, 1, 1, 1),
        ];
        return new Train($number, $station, $station, $date, $date, $seats);
    }

    protected function getMethod($name)
    {
        $class = new ReflectionClass('BiletCafe\Agent');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function setUp()
    {
        $this->trains = array(
            $this->createTrain('one', '-1 day'),
            $this->createTrain('two', '-1 hour'),
            $this->createTrain('two', '+1 hour'),
            $this->createTrain('three', '+1 day')
        );

        $this->tickets = $this->getMockBuilder('BiletCafe\Tickets')->disableOriginalConstructor()->getMock();

        $this->tickets->expects($this->any())
            ->method('search')
            ->willReturn($this->trains);

        $this->agent = new Agent($this->tickets);
    }

    public function testFilterToConcreteTrain()
    {
        $method = self::getMethod('filterToConcreteTrain');

        $this->assertCount(2, $method->invokeArgs($this->agent, array($this->trains, 'two')));
        $this->assertCount(1, $method->invokeArgs($this->agent, array($this->trains, 'one')));
        $this->assertCount(0, $method->invokeArgs($this->agent, array($this->trains, 'four')));
    }

    public function testFilterToConcreteTrainWillDoNothingIfThereIsNoConcreteTrain()
    {
        $method = self::getMethod('filterToConcreteTrain');

        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains)));
        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains, '')));
        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains, null)));
    }

    public function testFilterExceptTrains()
    {
        $method = self::getMethod('filterExceptTrains');

        $this->assertCount(2, $method->invokeArgs($this->agent, array($this->trains, array('two'))));
        $this->assertCount(1, $method->invokeArgs($this->agent, array($this->trains, array('one', 'two'))));
        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains, array('four'))));
    }

    public function testFilterExceptTrainsWillDoNothingIfThereIsNoExceptTrains()
    {
        $method = self::getMethod('filterExceptTrains');

        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains)));
        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains, array())));
    }

    public function testFilterByDepartureDate()
    {
        $method = self::getMethod('filterByDepartureDate');

        $this->assertCount(2, $method->invokeArgs($this->agent, array($this->trains, new DateTime())));
        $this->assertCount(1, $method->invokeArgs($this->agent, array($this->trains, new DateTime('+2 hour'))));
        $this->assertCount(0, $method->invokeArgs($this->agent, array($this->trains, new DateTime('+2 day'))));
        $this->assertCount(3, $method->invokeArgs($this->agent, array($this->trains, new DateTime('-2 hour'))));
        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains, new DateTime('-2 day'))));
    }

    public function testFilterByArriveDate()
    {
        $method = self::getMethod('filterByArriveDate');

        $this->assertCount(2, $method->invokeArgs($this->agent, array($this->trains, new DateTime())));
        $this->assertCount(3, $method->invokeArgs($this->agent, array($this->trains, new DateTime('+2 hour'))));
        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains, new DateTime('+2 day'))));
        $this->assertCount(1, $method->invokeArgs($this->agent, array($this->trains, new DateTime('-2 hour'))));
        $this->assertCount(0, $method->invokeArgs($this->agent, array($this->trains, new DateTime('-2 day'))));

        $this->assertCount(4, $method->invokeArgs($this->agent, array($this->trains)));
    }

    public function testCalculateTotalSeats()
    {
        $seats = $this->agent->check('1', '2', new DateTime('-2 day'), new DateTime('+2 day'), null, array(), Train::ALL, Seat::ALL);
        $this->assertEquals(112, $seats);

        $seats = $this->agent->check('1', '2', new DateTime('+1 hour'), new DateTime('+2 day'), null, array(), Train::ALL, Seat::ALL);
        $this->assertEquals(28, $seats);

        $seats = $this->agent->check('1', '2', new DateTime('+1 hour'), new DateTime('+2 day'), null, array(), Train::RESERVED, Seat::ALL, 1);
        $this->assertEquals(4, $seats);

        $seats = $this->agent->check('1', '2', new DateTime('+1 hour'), new DateTime('+2 day'), null, array(), Train::FIRST | Train::SECOND, Seat::SIDE_UPPER | Seat::UPPER, 1);
        $this->assertEquals(4, $seats);
    }
}