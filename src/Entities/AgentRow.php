<?php
namespace BiletCafe\Entities;

class AgentRow
{
    public $id;

    public $phone_id;

    public $active;

    public $from;
    public $to;

    public $train;
    public $exclude;
    public $exclude_hyndai;

    public $departure;
    public $arrive;

    public $first;
    public $second;
    public $third;
    public $reserved;
    public $non_reserved;
    public $comfortable;

    public $upper;
    public $lower;
    public $side_upper;
    public $side_lower;

    public $subclass;

    public $created;
    public $edited;
    public $checked;

    public $response_code;
    public $response;

    public $seats;

    public $prev_active;

    public $mail_sent;
    public $mail_sent_result;

    public $push_sent;
    public $push_sent_result;

}
