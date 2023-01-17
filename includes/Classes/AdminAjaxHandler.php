<?php

namespace reventz\Classes;

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
        $data = [
            'name' => 'TEDx SUST',
            'is_online' => 1,
            'slug' => 'tedx-sust',
            'description' => 'This is description',
            'social_media' => '[{"Test1": {"Val1": "37", "Val2": "25"}}, {"Test2": {"Val1": "25", "Val2": "27"}}]',
            'form_fields' => '[{"Test1": {"Val1": "37", "Val2": "25"}}, {"Test2": {"Val1": "25", "Val2": "27"}}]',
        ];

        $event = new Event();
        $result = $event->create($data);

        $data = [
            'data' => $result
        ];
        // write your sql queries here or another class, then send by a success response
        wp_send_json_success($data);
    }
}

// INSERT INTO `authlabtest`.`wp_re_events` (`name`, `is_online`, `slug`, `description`, `social_media`, `form_fields`) VALUES ('TEDx SUST', '1', '/tedx-sust', 'this is a description', '[{\r\n  "Test1": {\r\n    "Val1": "37",\r\n    "Val2": "25"\r\n  }\r\n}, {\r\n  "Test2": {\r\n    "Val1": "25",\r\n    "Val2": "27"\r\n  }\r\n}]', '[{\r\n  "Test1": {\r\n    "Val1": "37",\r\n    "Val2": "25"\r\n  }\r\n}, {\r\n  "Test2": {\r\n    "Val1": "25",\r\n    "Val2": "27"\r\n  }\r\n}]');