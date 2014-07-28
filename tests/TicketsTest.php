<?php
namespace BiletCafe\Tests;

use BiletCafe\Tickets\Seat;
use BiletCafe\Tickets\Seat\ThirdClassSeat;
use BiletCafe\Tickets\Station;
use BiletCafe\Tickets\Train;
use DateTime;
use PHPUnit_Framework_TestCase;
use BiletCafe\Tickets;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class TicketsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException GuzzleHttp\Exception\ServerException
     */
    public function testThrowsExceptionOnBadStatusCode()
    {
        $this->getTickets(500)->search('2200000', '2218999', new DateTime());
    }

    protected function getTickets($statusCode = 200, $json = null)
    {
        $response = $json
            ? new Response($statusCode, array(), Stream::factory(json_encode($json)))
            : new Response($statusCode);

        $client = new Client();
        $mock = new Mock(array(
            $response
        ));
        $client->getEmitter()->attach($mock);
        return new Tickets($client);
    }

    /**
     * @expectedException BiletCafe\Tickets\TicketsException
     */
    public function testThrowsExceptionOnBadResultCode()
    {
        $this->getTickets(
            200,
            array(
                'response' => array(
                    'result' => array(
                        'code' => '1',
                        'description' => 'some error'
                    )
                )
            )
        )->search('2200000', '2218999', new DateTime());
    }

    public function testStations()
    {
        $stations = $this->getTickets(
            200,
            array(
                'response' => array(
                    'result' => array(
                        'code' => '0'
                    ),
                    'stations' => array(
                        array('code' => '1', 'name' => 'one')
                    )
                )
            )
        )->station('hello');

        $this->assertTrue($stations[0] instanceof Station);

        $this->assertEquals($stations[0]->code, '1');
        $this->assertEquals($stations[0]->name, 'one');
    }

    public function testSearch()
    {
        $trains = $this->getTickets(
            200,
            array(
                'response' => array(
                    'result' => array(
                        'code' => '0'
                    ),
                    'trains' => array(
                        array(
                            'number' => 'one',
                            'passenger_departure_code' => '1',
                            'passenger_departure_name' => 'one',
                            'passenger_arrival_code' => '2',
                            'passenger_arrival_name' => 'two',
                            'departure_date' => '01-01-2010',
                            'departure_time' => '00:00',
                            'arrival_date' => '31-12-2010',
                            'arrival_time' => '23:59',
                            'classes' => array(
                                array(
                                    'name' => 'third',
                                    'subclass' => 0,
                                    'seats' => array(
                                        'lower' => 1,
                                        'upper' => 2,
                                        'side_lower' => 3,
                                        'side_upper' => 4
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )->search('1', '2', new DateTime());

        /** @var Train $train */
        $train = $trains[0];

        $this->assertTrue($train instanceof Train);

        $this->assertEquals('one', $train->number);

        $this->assertEquals('1', $train->stationFrom->code);
        $this->assertEquals('one', $train->stationFrom->name);

        $this->assertEquals('2', $train->stationTo->code);
        $this->assertEquals('two', $train->stationTo->name);

        $this->assertEquals('01-01-2010 00:00', $train->departure->format('d-m-Y H:i'));
        $this->assertEquals('31-12-2010 23:59', $train->arrival->format('d-m-Y H:i'));

        /** @var Seat $seat */
        $seat = $train->seats[0];

        $this->assertTrue($seat instanceof ThirdClassSeat);

        $this->assertEquals(1, $seat->lower);
        $this->assertEquals(2, $seat->upper);
        $this->assertEquals(3, $seat->sideLower);
        $this->assertEquals(4, $seat->sideUpper);
    }
}