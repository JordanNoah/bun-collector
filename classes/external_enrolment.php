<?php

namespace local_collector_alert_bun;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use external_api;
use stdClass;

class external_enrolment extends external_api {
    public static function get_enrolments_pages_counter_parameters(){
        return new external_function_parameters(
            array()
        );
    }

    public static function get_enrolments_pages_counter(){
        global $DB;
        return array(
            "totalenrolments" => $DB->get_record_sql("SELECT count(1) as total FROM mdl_user_enrolments")->total
        );
    }

    public static function get_enrolments_pages_counter_returns(){
        return new external_single_structure(
            array("totalenrolments" => new external_value(PARAM_INT))
        );
    }

    public static function get_enrolments_data_parameters(){
        return new external_function_parameters(
            array(
                'page' => new external_value(PARAM_INT)
            )
        );
    }

    public static function get_enrolments_data($page){
        global $DB;

        $limit = 10;
        $offset = $limit * $page;
        $totalpages = ceil($DB->get_record_sql("SELECT count(1) as 'total_rows' from mdl_user_enrolments")->total_rows / $limit);

        //limpiamos la data para facilitar lo necesario en el backend

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


        $enrolmentsReturn = array();

        for ($i = 0; $i < count($enrolments); $i++) {
            $element = $enrolments[$i];
            $enrolmentObject = new stdClass();
            $enrolmentObject->id = $element->id;
            $enrolmentObject->status = $element->status;
            $enrolmentObject->enrolid = $element->enrolid;
            $enrolmentObject->userid = $element->userid;
            $enrolmentObject->timestart = $element->timestart;
            $enrolmentObject->timeend = $element->timeend;
            $enrolmentObject->modifierid = $element->modifierid;
            $enrolmentObject->timecreated = $element->timecreated;
            $enrolmentObject->timemodified = $element->timemodified;
            $enrolmentObject->enrol = $element->enrol;
            $enrolmentObject->courseid = $element->courseid;
            $enrolmentObject->sortorder = $element->sortorder;
            $enrolmentObject->name = $element->name;
            $enrolmentObject->enrolperiod = $element->enrolperiod;
            $enrolmentObject->enrolstartdate = $element->enrolstartdate;
            $enrolmentObject->enrolenddate = $element->enrolenddate;
            $enrolmentObject->expirynotify = $element->expirynotify;
            $enrolmentObject->expirythreshold = $element->expirythreshold;
            $enrolmentObject->notifyall = $element->notifyall;
            $enrolmentObject->password = $element->password;
            $enrolmentObject->cost = $element->cost;
            $enrolmentObject->currency = $element->currency;
            $enrolmentObject->roleid = $element->roleid;
            $enrolmentObject->customint1 = $element->customint1;
            $enrolmentObject->customint2 = $element->customint2;
            $enrolmentObject->customint3 = $element->customint3;
            $enrolmentObject->customint4 = $element->customint4;
            $enrolmentObject->customint5 = $element->customint5;
            $enrolmentObject->customint6 = $element->customint6;
            $enrolmentObject->customint7 = $element->customint7;
            $enrolmentObject->customint8 = $element->customint8;
            $enrolmentObject->customchar1 = $element->customchar1;
            $enrolmentObject->customchar2 = $element->customchar2;
            $enrolmentObject->customchar3 = $element->customchar3;
            $enrolmentObject->customdec1 = $element->customdec1;
            $enrolmentObject->customdec2 = $element->customdec2;
            $enrolmentObject->customtext1 = $element->customtext1;
            $enrolmentObject->customtext2 = $element->customtext2;
            $enrolmentObject->customtext3 = $element->customtext3;
            $enrolmentObject->customtext4 = $element->customtext4;

            $enrolmentsReturn[] = $enrolmentObject;
        }

        error_log(json_encode($enrolmentsReturn));

        return $enrolmentsReturn;
    }

    public static function get_enrolments_data_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    "id" => new external_value(PARAM_RAW),
                    "status" => new external_value(PARAM_RAW),
                    "enrolid" => new external_value(PARAM_RAW),
                    "userid" => new external_value(PARAM_RAW),
                    "timestart" => new external_value(PARAM_RAW),
                    "timeend" => new external_value(PARAM_RAW),
                    "modifierid" => new external_value(PARAM_RAW),
                    "timecreated" => new external_value(PARAM_RAW),
                    "timemodified" => new external_value(PARAM_RAW),
                    "enrol" => new external_value(PARAM_RAW),
                    "courseid" => new external_value(PARAM_RAW),
                    "sortorder" => new external_value(PARAM_RAW),
                    "name" => new external_value(PARAM_RAW),
                    "enrolperiod" => new external_value(PARAM_RAW),
                    "enrolstartdate" => new external_value(PARAM_RAW),
                    "enrolenddate" => new external_value(PARAM_RAW),
                    "expirynotify" => new external_value(PARAM_RAW),
                    "expirythreshold" => new external_value(PARAM_RAW),
                    "notifyall" => new external_value(PARAM_RAW),
                    "password" => new external_value(PARAM_RAW),
                    "cost" => new external_value(PARAM_RAW),
                    "currency" => new external_value(PARAM_RAW),
                    "roleid" => new external_value(PARAM_RAW),
                    "customint1" => new external_value(PARAM_RAW),
                    "customint2" => new external_value(PARAM_RAW),
                    "customint3" => new external_value(PARAM_RAW),
                    "customint4" => new external_value(PARAM_RAW),
                    "customint5" => new external_value(PARAM_RAW),
                    "customint6" => new external_value(PARAM_RAW),
                    "customint7" => new external_value(PARAM_RAW),
                    "customint8" => new external_value(PARAM_RAW),
                    "customchar1" => new external_value(PARAM_RAW),
                    "customchar2" => new external_value(PARAM_RAW),
                    "customchar3" => new external_value(PARAM_RAW),
                    "customdec1" => new external_value(PARAM_RAW),
                    "customdec2" => new external_value(PARAM_RAW),
                    "customtext1" => new external_value(PARAM_RAW),
                    "customtext2" => new external_value(PARAM_RAW),
                    "customtext3" => new external_value(PARAM_RAW),
                    "customtext4" => new external_value(PARAM_RAW)
                )
            )
        );
    }
}