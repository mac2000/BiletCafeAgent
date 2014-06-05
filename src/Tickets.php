<?php
namespace BiletCafe;

use BiletCafe\Tickets\Seat;
use BiletCafe\Tickets\Station;
use BiletCafe\Tickets\TicketsException;
use BiletCafe\Tickets\Train;
use DateTime;
use GuzzleHttp\ClientInterface;

class Tickets
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $name
     * @param integer $limit
     * @param string $lang
     * @return array
     */
    public function station($name, $limit = 10, $lang = 'ru')
    {
        $query = array(
            'name' => $name,
            'limit' => $limit,
            'lang' => $lang
        );

        $response = $this->call('/rail/search.json', array_filter($query));

        return array_map(function ($station) {
            return new Station($station['code'], $station['name']);
        }, $response['stations']);
    }

    /**
     * @param string $from station code
     * @param string $to station code
     * @param DateTime $date departure date d-m-Y
     * @param string $lang to use (ru, en, uk)
     * @return array
     */
    public function search($from, $to, DateTime $date, $lang = 'ru')
    {
        $query = array(
            'from' => $from,
            'to' => $to,
            'date' => $date->format('d-m-Y'),
            'lang' => $lang
        );

        $response = $this->call('/rail/search.json', array_filter($query));

        return array_map(function ($train) {
            return new Train(
                $train['number'],
                new Station($train['passenger_departure_code'], $train['passenger_departure_name']),
                new Station($train['passenger_arrival_code'], $train['passenger_arrival_name']),
                DateTime::createFromFormat('d-m-Y H:i', $train['departure_date'] . ' ' . $train['departure_time']),
                DateTime::createFromFormat('d-m-Y H:i', $train['arrival_date'] . ' ' . $train['arrival_time']),
                array_map(function($seat){
                    return Seat::createFromTicketsTrain($seat);
                }, $train['classes'])
            );
        }, $response['trains']);
    }

    /**
     * @param string $url
     * @param array $query
     * @return array
     * @throws TicketsException
     */
    protected function call($url, array $query = array())
    {
        $response = $this->client->get($url, array(
            'query' => $query
        ))->json();

        if ($response['response']['result']['code'] != '0') {
            throw new TicketsException($response['response']['result']['description'], $response['response']['result']['code']);
        }

        return $response['response'];
    }
}