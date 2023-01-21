<?php

namespace reventz\Classes\Controllers;

use Exception;
use reventz\Classes\Models\Event;
use reventz\Classes\Models\TicketType;
defined( 'ABSPATH' ) || exit();
class EventController
{

    public function getEvents()
    {
        $events = Event::all();
        return $events;
    }
    public function create($data)
    {
        try {
            global $wpdb;

            $eventTableData = $data;
            unset($eventTableData['ticket_types']);
    
            $wpdb->query('START TRANSACTION');
    
            $event = new Event();
            $event_id = $event->create($eventTableData);
    
            $ticketTypeIdArray = [];
            foreach ($data['ticket_types'] as $ticketTypekey => $ticketTypeData) {

                $ticketTypeData['event_id'] = $event_id;
                $ticketTypeIdArray[] = $this->createTicketType($ticketTypeData);
            }
    
            $data = [
                'data' => "Event Created Succesfully"
            ];
            $wpdb->query('COMMIT');
            return $data;
        } catch (\Exception $e) {
            $wpdb->query('ROLLBACK');
            throw $e;
        }

    }

    public function createTicketType($ticketTypeData) : int
    {
        $ticketType = new TicketType();
        return $ticketType->create($ticketTypeData);
    }
}