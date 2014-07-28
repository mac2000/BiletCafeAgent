<?php
namespace BiletCafe\Tests\Repository;

use BiletCafe\Entities\AgentRow;
use DateTime;

trait FakeAgentRow
{
    protected function getFakeAgentRow()
    {
        $agent = new AgentRow();

        $this->id = 1;

        $this->phone_id = 1;

        $this->active = 1;

        $this->from = '1';
        $this->to = '2';

        $this->train = null;
        $this->exclude = null;
        $this->exclude_hyndai = null;

        $this->departure = (new DateTime('+1 days'))->getTimestamp();
        $this->arrive = (new DateTime('+2 days'))->getTimestamp();

        $this->first = 1;
        $this->second = 1;
        $this->third = 1;
        $this->reserved = 1;
        $this->non_reserved = 1;
        $this->comfortable = 1;

        $this->upper = 1;
        $this->lower = 1;
        $this->side_upper = 1;
        $this->side_lower = 1;

        $this->subclass = 1;

        $this->created = (new DateTime())->getTimestamp();
        $this->edited = (new DateTime())->getTimestamp();
        $this->checked = null;

        $this->response_code = null;
        $this->response = null;

        $this->seats = 0;

        $this->prev_active = 1;

        $this->mail_sent = 0;
        $this->mail_sent_result = null;

        $this->push_sent = 0;
        $this->push_sent_result = null;
        
        return $agent;
    }
}
