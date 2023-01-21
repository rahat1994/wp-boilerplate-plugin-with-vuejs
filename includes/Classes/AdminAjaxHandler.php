<?php

namespace reventz\Classes;

defined( 'ABSPATH' ) || exit();
use reventz\Classes\Models\Event;
use reventz\Classes\Models\TicketType;
use reventz\Classes\Controllers\EventController;

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
            'events' => 'getEvents',
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

        $eventController = new EventController();

        try {

            $data = $_REQUEST['data'];
            $result = $eventController->create($data);
            wp_send_json_success($result);
        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 423);
        }

    }

    protected function getEvents()
    {

        try {
            wp_send_json_success(Event::allWithBookings());            
        } catch (\Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage()
            ], 423);
        }
    }
}

// INSERT INTO `authlabtest`.`wp_re_events` (`name`, `is_online`, `slug`, `description`, `social_media`, `form_fields`) VALUES ('TEDx SUST', '1', '/tedx-sust', 'this is a description', '[{\r\n  "Test1": {\r\n    "Val1": "37",\r\n    "Val2": "25"\r\n  }\r\n}, {\r\n  "Test2": {\r\n    "Val1": "25",\r\n    "Val2": "27"\r\n  }\r\n}]', '[{\r\n  "Test1": {\r\n    "Val1": "37",\r\n    "Val2": "25"\r\n  }\r\n}, {\r\n  "Test2": {\r\n    "Val1": "25",\r\n    "Val2": "27"\r\n  }\r\n}]');