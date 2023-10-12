<?php

namespace local_collector_alert_bun;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use external_api;

class external_users extends external_api {
    public static function get_users_data_parameters(){
        return new external_function_parameters(
            array(
                'page' => new external_value(PARAM_INT)
            )
        );
    }

    public static function get_users_data($page){
        global $DB;

        $limit = 10;
        $offset = $limit * $page;
        $totalpages = ceil($DB->get_record_sql("select count(1) as 'total_rows' from mdl_user")->total_rows / $limit);

        return array(
            "offset"=>$offset,
            "pagesmissing"=> $totalpages - ($page + 1),
            "totalpages" => $totalpages,
            "users"=>array_values($DB->get_records_sql('SELECT * from mdl_user LIMIT 10 OFFSET '.$offset))
        );
    }

    public static function get_users_data_returns(){
        return new external_single_structure(
            array(
                "offset" => new external_value(PARAM_INT),
                "pagesmissing" => new external_value(PARAM_INT),
                "totalpages" => new external_value(PARAM_INT),
                "users" => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_TEXT),
                            'auth' => new external_value(PARAM_TEXT),
                            'confirmed' => new external_value(PARAM_TEXT),
                            'policyagreed' => new external_value(PARAM_TEXT),
                            'deleted' => new external_value(PARAM_TEXT),
                            'suspended' => new external_value(PARAM_TEXT),
                            'mnethostid' => new external_value(PARAM_TEXT),
                            'username' => new external_value(PARAM_TEXT),
                            'password' => new external_value(PARAM_TEXT),
                            'idnumber' => new external_value(PARAM_TEXT),
                            'firstname' => new external_value(PARAM_TEXT),
                            'lastname' => new external_value(PARAM_TEXT),
                            'email' => new external_value(PARAM_TEXT),
                            'emailstop' => new external_value(PARAM_TEXT),
                            'phone1' => new external_value(PARAM_TEXT),
                            'phone2' => new external_value(PARAM_TEXT),
                            'institution' => new external_value(PARAM_TEXT),
                            'department' => new external_value(PARAM_TEXT),
                            'address' => new external_value(PARAM_TEXT),
                            'city' => new external_value(PARAM_TEXT),
                            'country' => new external_value(PARAM_TEXT),
                            'lang' => new external_value(PARAM_TEXT),
                            'calendartype' => new external_value(PARAM_TEXT),
                            'theme' => new external_value(PARAM_TEXT),
                            'timezone' => new external_value(PARAM_TEXT),
                            'firstaccess' => new external_value(PARAM_TEXT),
                            'lastaccess' => new external_value(PARAM_TEXT),
                            'lastlogin' => new external_value(PARAM_TEXT),
                            'currentlogin' => new external_value(PARAM_TEXT),
                            'lastip' => new external_value(PARAM_TEXT),
                            'secret' => new external_value(PARAM_TEXT),
                            'picture' => new external_value(PARAM_TEXT),
                            'description' => new external_value(PARAM_TEXT),
                            'descriptionformat' => new external_value(PARAM_TEXT),
                            'mailformat' => new external_value(PARAM_TEXT),
                            'maildigest' => new external_value(PARAM_TEXT),
                            'maildisplay' => new external_value(PARAM_TEXT),
                            'autosubscribe' => new external_value(PARAM_TEXT),
                            'trackforums' => new external_value(PARAM_TEXT),
                            'timecreated' => new external_value(PARAM_TEXT),
                            'timemodified' => new external_value(PARAM_TEXT),
                            'trustbitmask' => new external_value(PARAM_TEXT),
                            'imagealt' => new external_value(PARAM_TEXT),
                            'lastnamephonetic' => new external_value(PARAM_TEXT),
                            'firstnamephonetic' => new external_value(PARAM_TEXT),
                            'middlename' => new external_value(PARAM_TEXT),
                            'alternatename' => new external_value(PARAM_TEXT),
                            'moodlenetprofile' => new external_value(PARAM_TEXT)
                        )
                    )
                )
            )
        );
    }

    public static function get_users_pages_counter_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_users_pages_counter() {
        global $DB;
        $limit = 10 ;

        return array(
            "totalusers" => ceil($DB->get_record_sql("select count(1) as 'total_rows' from mdl_user")->total_rows / $limit)
        );
    }

    public static function get_users_pages_counter_returns(){
        return new external_single_structure(
            array(
                'totalusers'=> new external_value(PARAM_INT)
            )
        );
    }
}