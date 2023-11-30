<?php

namespace local_collector_alert_bun;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use external_api;
use stdClass;

class external_role_assigned extends external_api {
    public static function get_role_assigned_enrolments_parameters(){
        return new external_function_parameters(
            array(
                'page' => new external_value(PARAM_INT)
            )
        );
    }

    public static function get_role_assigned_enrolments($page){
        global $DB;

        $limit = 10;
        $offset = $limit * $page;
        $totalpages = ceil($DB->get_record_sql("SELECT count(1) as 'total_rows' from mdl_user_enrolments")->total_rows / $limit);

        $enrolments = array_values($DB->get_records_sql(
            'SELECT mue.*,
    me.enrol,
    me.courseid,
    me.sortorder,
    me.name,
    me.enrolperiod,
    me.enrolstartdate,
    me.enrolenddate,
    me.expirynotify,
    me.expirythreshold,
    me.notifyall,
    me.password,
    me.cost,
    me.currency,
    me.roleid,
    me.customint1,
    me.customint2,
    me.customint3,
    me.customint4,
    me.customint5,
    me.customint6,
    me.customint7,
    me.customint8,
    me.customchar1,
    me.customchar2,
    me.customchar3,
    me.customdec1,
    me.customdec2,
    me.customtext1,
    me.customtext2,
    me.customtext3,
    me.customtext4
from  mdl_user_enrolments as mue inner join mdl_enrol as me on me.id = mue.enrolid LIMIT 10 OFFSET '.$offset
        ));

        $enrolmentsEnrol = array();

        for ($i = 0; $i < count($enrolments); $i++) {
            $element = $enrolments[$i];
            $courseContext = \context_course::instance($element->courseid);
            $rolesAssigned = array_values($DB->get_records_sql('SELECT * FROM mdl_role_assignments WHERE contextid = '.$courseContext->id.' AND userid = '.$element->userid));

            array_walk($rolesAssigned,function ($e) use ($element){
                $e->courseid = $element->courseid;
                $e->enrolmentId = $element->id;
            });

            $enrolmentObject = new stdClass();
            $enrolmentObject->id = $element->id;
            $enrolmentObject->userid = $element->userid;
            $enrolmentObject->courseid = $element->courseid;
            $enrolmentObject->rolesAssigned = $rolesAssigned;
            $enrolmentsEnrol[] = $enrolmentObject;
        }

        return $enrolmentsEnrol;
    }

    public static function get_role_assigned_enrolments_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    "id" => new external_value(PARAM_TEXT),
                    "userid" => new external_value(PARAM_TEXT),
                    "courseid" => new external_value(PARAM_TEXT),
                    "rolesAssigned" => new external_multiple_structure(
                        new external_single_structure(
                            array(
                                'id' => new external_value(PARAM_TEXT),
                                'roleid' => new external_value(PARAM_TEXT),
                                'contextid' => new external_value(PARAM_TEXT),
                                'userid' => new external_value(PARAM_TEXT),
                                'timemodified' => new external_value(PARAM_TEXT),
                                'modifierid' => new external_value(PARAM_TEXT),
                                'component' => new external_value(PARAM_TEXT),
                                'itemid' => new external_value(PARAM_TEXT),
                                'sortorder' => new external_value(PARAM_TEXT),
                                'courseid' => new external_value(PARAM_INT),
                                'enrolmentId' => new external_value(PARAM_INT)
                            )
                        )
                    )
                )
            )
        );
    }
}