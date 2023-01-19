<?php

namespace reventz\Classes;
use reventz\Classes\Models\TicketType;
defined( 'ABSPATH' ) || exit();
use reventz\Classes\Models\Event;

class AdminAjaxHandler
{
    public function registerEndpoints()
    {
        add_action('wp_ajax_reventz_admin_ajax', array($this, 'handleEndPoint'));
    }
    public function handleEndPoint()
    {
        $route = sanitize_text_field($_REQUEST['route']);

        $validRoutes = array(
            'get_data' => 'getData',
            'create_event' => 'createEvent',
        );

        if (isset($validRoutes[$route])) {
            do_action('reventz/doing_ajax_forms_' . $route);
            return $this->{$validRoutes[$route]}();
        }
        do_action('reventz/admin_ajax_handler_catch', $route);
    }

    protected function getData()
    {
        $data = [
            'data' => "Success"
        ];
        // write your sql queries here or another class, then send by a success response
        wp_send_json_success($data);
    }

    protected function createEvent()
    {
        try {
            $data = [
                'name' => 'TEDx SUST',
                'is_online' => 1,
                'slug' => 'tedx-sust',
                'description' => 'This is description',
                'social_media' => '[{"Test1": {"Val1": "37", "Val2": "25"}}, {"Test2": {"Val1": "25", "Val2": "27"}}]',
                'form_fields' => '[{"Test1": {"Val1": "37", "Val2": "25"}}, {"Test2": {"Val1": "25", "Val2": "27"}}]',
                'ticket_types' => [                    
                    
                    [
                        "name" => "V.I.P Seats",
                        "price" => 1500,
                        "limit" => 50,
                        "Descroption" => "Thisi si akha description"
                        
                    ],
                    [
                        "name" => "Second Row",
                        "price" => 500,
                        "limit" => 420,
                        "Descroption" => "Thisi si akha description"
                    ]
                                        
                ]
            ];

            $eventTableData = $data;
            unset($eventTableData['ticket_types']);

            $event = new Event();
            $event_id = $event->create($eventTableData);

            $ticketTypeIdArray = [];
            foreach ($data['ticket_types'] as $ticketTypekey => $ticketTypeData) {

                $ticketType = new TicketType();
                $ticketTypeData['event_id'] = $event_id;
                $ticketTypeIdArray[] = $ticketType->create($ticketTypeData);

            }
    
            $data = [
                'data' => "Event Created Succesfully"
            ];

            wp_send_json_success($data);
        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 423);
        }

    }
}

// INSERT INTO `authlabtest`.`wp_re_events` (`name`, `is_online`, `slug`, `description`, `social_media`, `form_fields`) VALUES ('TEDx SUST', '1', '/tedx-sust', 'this is a description', '[{\r\n  "Test1": {\r\n    "Val1": "37",\r\n    "Val2": "25"\r\n  }\r\n}, {\r\n  "Test2": {\r\n    "Val1": "25",\r\n    "Val2": "27"\r\n  }\r\n}]', '[{\r\n  "Test1": {\r\n    "Val1": "37",\r\n    "Val2": "25"\r\n  }\r\n}, {\r\n  "Test2": {\r\n    "Val1": "25",\r\n    "Val2": "27"\r\n  }\r\n}]');