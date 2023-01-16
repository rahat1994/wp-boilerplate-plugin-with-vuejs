<?php

namespace reventz\Classes;

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
}
