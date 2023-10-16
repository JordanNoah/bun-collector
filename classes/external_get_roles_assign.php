<?php

namespace local_collector_alert_bun;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use external_api;
use stdClass;

class external_get_roles_assign extends external_api {

    public static function get_roles_assigned_parameters(){
        return new external_function_parameters(
            array(
                'page' => new external_value(PARAM_INT)
            )
        );
    }

    public static function get_roles_assigned($page){
        global $DB;

        $limit = 10;
        $offset = $limit * $page;

        $context = \context_course::instance();
        role_assign();

        $enrollments = array_values($DB->get_records_sql("
            SELECT * FROM mdl_role_assigments LIMIT 10 OFFSET ".$offset
        ));

        $enrolmentsReturn = array();

        for ($i = 0; $i < count($enrollments); $i++) {
            $enrolmentObject = new stdClass();
            $roleAssigned = role
        }

    }

    public static function get_roles_assigned_returns(){}
}