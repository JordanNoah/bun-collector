<?php

defined('MOODLE_INTERNAL') || die();

class local_collector_alert_bun_observer
{
    //user crud events here
    public static function user_created(\core\event\user_created $event)
    {
        try {
            global $DB;
            $event_data = $event->get_data();
            $user = $DB->get_record('user',array('id'=>$event_data["relateduserid"]));
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]); //@todo: revisar que funcione en UTC

            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'user_created';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["user"] = $user;
            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function user_deleted(\core\event\user_deleted $event){
        try {
            global $DB;
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]); //@todo: revisar que funcione en UTC

            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'user_created';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["userid"] = $event_data["relateduserid"];

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function user_updated(core\event\user_updated $event){
        try {
            global $DB;
            $event_data = $event->get_data();
            $user = $DB->get_record('user',array('id'=>$event_data["relateduserid"]));
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]); //@todo: revisar que funcione en UTC

            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'user_updated';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["user"] = $user;
            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    //course crud events here
    public static function course_created(\core\event\course_created $event){
        try {
            $event_data = $event->get_data();
            
            $course = get_course($event_data["courseid"]);
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);

            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'course_created';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["course"] = $course;

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function course_delete(\core\event\course_deleted $event){
        try {
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);

            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'course_deleted';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["course_id"] = $event_data["courseid"];

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function course_updated(\core\event\course_updated $event){
        try {
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'course_updated';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["course"] = get_course($event_data["courseid"]);
            self::send_rabbit($rabbit_object);;
        }catch (Exception $e){
            error_log($e);
        }
    }

    //enrollment
    public static function enrolment_created(\core\event\user_enrolment_created $event){
        try {
            global $DB;

            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);

            $rabbit_object["contextid"] = $event_data["contextid"];
            $enrolment = $DB->get_record_sql('SELECT * from  mdl_user_enrolments as mue inner join mdl_enrol as me on me.id = mue.enrolid where mue.id = '.$event_data["objectid"].';');

            $rabbit_object["other"]["message_config"]["eventname"] = 'enrolment_created';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["enrolment"] = $enrolment;

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function enrolment_deleted(\core\event\user_enrolment_deleted $event){
        try {
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);

            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'enrolment_deleted';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["enrolmentId"] = $event_data["objectid"];

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function enrolment_updated(\core\event\user_enrolment_updated $event){
        try {
            global $DB;
            $event_data = $event->get_data();
            $enrolment = $DB->get_record_sql('SELECT * from  mdl_user_enrolments as mue inner join mdl_enrol as me on me.id = mue.enrolid where mue.id = '.$event_data["objectid"].';');

            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'enrolment_updated';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["enrolment"] = $enrolment;
            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function role_assigned(\core\event\role_assigned $event){
        try {
            $event_data = $event->get_data();
            $context = context_course::instance($event_data["courseid"]);
            $roles = array_values(get_user_roles($context, $event_data["relateduserid"], true));

            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'enrolment_updated';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();

            error_log(json_encode($roles));
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function send_rabbit($rabbit_object){
        $event_to_send = \local_message_broker\event\published_message::create_from_object($rabbit_object);
        $event_to_send->trigger();
    }

}