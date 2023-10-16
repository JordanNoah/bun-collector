<?php

namespace local_collector_alert_bun;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use external_api;
use Matrix\Exception;
use stdClass;
use tool_lp\external;

class external_general_modules extends external_api {
    public static function get_general_modules_data_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_general_modules_data(){
            global $DB;
            $modules = array_values($DB->get_records('modules'));
            $arraytoreturn = array();
            foreach ($modules as $module){
                $obj = new stdClass();
                $obj->id = $module->id;
                $obj->name = $module->name;
                $obj->cron = $module->cron;
                $obj->lastcron = $module->lastcron;
                $obj->search = $module->search;
                $obj->visible = $module->visible;
                $arraytoreturn[] = $obj;
            }
            return $arraytoreturn;

    }

    public static function get_general_modules_data_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT),
                    'name' => new external_value(PARAM_TEXT),
                    'cron' => new external_value(PARAM_INT),
                    'lastcron' => new external_value(PARAM_INT),
                    'search' => new external_value(PARAM_TEXT),
                    'visible' => new external_value(PARAM_INT)
                )
            )
        );
    }
}