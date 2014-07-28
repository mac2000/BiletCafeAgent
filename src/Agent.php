<?php
namespace BiletCafe;

use BiletCafe\Tickets\Seat;
use BiletCafe\Tickets\Train;
use DateTime;

class Agent
{
    /**
     * @var Tickets
     */
    protected $tickets;

    /**
     * @var array
     */
    protected $trains;

    public function __construct(Tickets $tickets)
    {
        $this->tickets = $tickets;
        $this->trains = array();
    }

    protected function filterToConcreteTrain(array $trains, $concreteTrain = null)
    {
        if (!$concreteTrain || empty($concreteTrain)) {
            return $trains;
        }

        return array_filter(
            $trains,
            function ($train) use ($concreteTrain) {
                /** @var Train $train */
                return $train->numberMatch($concreteTrain);
            }
        );
    }

    protected function filterExceptTrains(array $trains, array $exceptTrains = array())
    {
        if (!$exceptTrains || empty($exceptTrains)) {
            return $trains;
        }

        return array_filter(
            $trains,
            function ($train) use ($exceptTrains) {
                /** @var Train $train */
                return !$train->numberInArray($exceptTrains);
            }
        );
    }

    protected function filterByDepartureDate(array $trains, DateTime $date)
    {
        return array_filter(
            $trains,
            function ($train) use ($date) {
                /** @var Train $train */
                return $train->isDepartureAfter($date);
            }
        );
    }

    protected function filterByArriveDate(array $trains, DateTime $date = null)
    {
        if (!$date) {
            return $trains;
        }

        return array_filter(
            $trains,
            function ($train) use ($date) {
                /** @var Train $train */
                return $train->isArriveBefore($date);
            }
        );
    }

    protected function search($stationFrom, $stationTo, DateTime $date, $lang = 'ru')
    {
        $this->trains = $this->tickets->search($stationFrom, $stationTo, $date, $lang);
    }

    protected function filter(DateTime $date, DateTime $dateBy = null, $concreteTrain = null, $exceptTrains = array())
    {
        // skip trains by number
        $this->trains = $this->filterExceptTrains($this->trains, $exceptTrains);
        $this->trains = $this->filterToConcreteTrain($this->trains, $concreteTrain);

        // skip trains by dates
        $this->trains = $this->filterByDepartureDate($this->trains, $date);
        $this->trains = $this->filterByArriveDate($this->trains, $dateBy);
    }

    protected function calculateTotalSeats($classFlags = Train::ALL, $seatFlags = Seat::ALL, $subclass = null)
    {
        $seats = 0;
        foreach ($this->trains as $train) {
            /** @var Train $train */
            $seats += $train->calculateTotalSeats($classFlags, $seatFlags, $subclass);
        }

        return $seats;
    }

    public function check(
        $stationFrom,
        $stationTo,
        DateTime $date,
        DateTime $dateBy = null,
        $concreteTrain = null,
        $exceptTrains = array(),
        $classFlags = Train::ALL,
        $seatFlags = Seat::ALL,
        $subclass = null,
        $lang = 'ru'
    ) {
        $this->search($stationFrom, $stationTo, $date, $lang);

        $this->filter($date, $dateBy, $concreteTrain, $exceptTrains);

        return $this->calculateTotalSeats($classFlags, $seatFlags, $subclass);
    }
}