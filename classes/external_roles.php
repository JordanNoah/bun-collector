<?php

namespace local_collector_alert_bun;

use external_function_parameters;

defined('MOODLE_INTERNAL') || die;

class external_roles extends \external_api {
    public static function get_all_roles_parameters()
    {
        return new external_function_parameters(
            array()
        );
    }
    public static function get_all_roles()
    {
        global $DB;
        $roles = $DB->get_records('role');
        return array_values($roles);
    }
    public static function get_all_roles_returns()
    {
        return new \external_multiple_structure(
            new \external_single_structure(
                array(
                    'id' => new \external_value(PARAM_TEXT),
                    'name' => new \external_value(PARAM_TEXT),
                    'shortname' => new \external_value(PARAM_TEXT),
                    'description' => new \external_value(PARAM_TEXT),
                    'sortorder' => new \external_value(PARAM_TEXT),
                    'archetype' => new \external_value(PARAM_TEXT)
                )
            )
        );
    }
}