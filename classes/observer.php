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
            $user = $DB->get_record_sql('SELECT * FROM mdl_user where id = '.$event_data["relateduserid"]);
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
            $rabbit_object["other"]["message_config"]["eventname"] = 'user_deleted';
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
            global $DB;
            $event_data = $event->get_data();

            $course = $DB->get_record_sql('select * from mdl_course where id = '.$event_data["courseid"]);
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

            //self::send_rabbit($rabbit_object);
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

    //role assigned
    public static function role_assigned(\core\event\role_assigned $event){
        try {
            $event_data = $event->get_data();
            $context = context_course::instance($event_data["courseid"]);
            $roles = array_values(get_user_roles($context, $event_data["relateduserid"], true));

            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'role_assigned';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["roles"] = $roles;

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function role_unassigned(\core\event\role_unassigned $event){
        try {
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'role_unassigned';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["role"] = $event_data["objectid"];
            $rabbit_object["other"]["message_data"]["courseid"] = $event_data["courseid"];
            $rabbit_object["other"]["message_data"]["userid"] = $event_data["relateduserid"];

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    //message
    public static function message_deleted(\core\event\message_deleted $event){
        try {
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'message_deleted';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["messageId"] = $event_data["other"]["messageid"];

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function message_viewed(\core\event\message_viewed $event){
        try {
            $event_data = $event->get_data();
            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'message_viewed';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["messageId"] = $event_data["other"]["messageid"];

            self::send_rabbit($rabbit_object);
        }catch (Exception $e){
            error_log($e);
        }
    }

    public static function message_sent(\core\event\message_sent $event){
        global $DB;
        $event_data = $event->get_data();

        $message = $DB->get_record("messages",array('id'=>$event_data["objectid"]));

        $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
        $rabbit_object["contextid"] = $event_data["contextid"];
        $rabbit_object["other"]["message_config"]["eventname"] = 'message_sent';
        $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
        $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
        $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
        $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
        $rabbit_object["other"]["message_data"]["message"] = $message;

        self::send_rabbit($rabbit_object);
    }
    //course module

    /**
     * @throws coding_exception
     * @throws moodle_exception
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event){
        // Obtén el objeto que representa al evento
        $event_data = $event->get_data();
        $userid = $event->userid;
        $courseid = $event->courseid;
        $cmid = $event->contextinstanceid;
        // Verifica si los datos incluyen el cm_info

            // Obtén más información sobre el módulo del curso si es necesario
            $course_module = get_coursemodule_from_id('', $cmid, $courseid, false, MUST_EXIST);

            // Obtén el cm_info desde el contexto
            $cm_info = get_fast_modinfo($course_module->course)->get_cm($course_module->id);

            $completionRules = array_values( \core_completion\cm_completion_details::get_instance($cm_info,$userid,true)->get_details());

            $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
            $rabbit_object["contextid"] = $event_data["contextid"];
            $rabbit_object["other"]["message_config"]["eventname"] = 'course_module_completion_updated';
            $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
            $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
            $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
            $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
            $rabbit_object["other"]["message_data"]["courseId"] = $courseid;
            $rabbit_object["other"]["message_data"]["userId"] = $userid;
            $rabbit_object["other"]["message_data"]["moduleId"] = $cmid;
            $rabbit_object["other"]["message_data"]["completionRules"] = $completionRules;

            self::send_rabbit($rabbit_object);
    }

    public static function course_module_created(\core\event\course_module_created $event){
        $event_data = $event->get_data();

        $moduleCore = self::getModule(
            $event_data["other"]["instanceid"],
            $event_data["courseid"],
            $event_data["other"]["modulename"]
        );

        $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
        $rabbit_object["contextid"] = $event_data["contextid"];
        $rabbit_object["other"]["message_config"]["eventname"] = 'course_module_created';
        $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
        $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
        $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
        $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
        $rabbit_object["other"]["message_data"]["courseId"] = $event->courseid;
        $rabbit_object["other"]["message_data"]["module"] = self::courseModule($moduleCore,$event_data["other"]["modulename"]);

        self::send_rabbit($rabbit_object);
    }

    public static function course_module_deleted(\core\event\course_module_deleted $event){
        $event_data = $event->get_data();

        $moduleCore = self::getModule(
            $event_data["other"]["instanceid"],
            $event_data["courseid"],
            $event_data["other"]["modulename"]
        );

        $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
        $rabbit_object["contextid"] = $event_data["contextid"];
        $rabbit_object["other"]["message_config"]["eventname"] = 'course_module_deleted';
        $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
        $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
        $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
        $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
        $rabbit_object["other"]["message_data"]["courseId"] = $event->courseid;
        $rabbit_object["other"]["message_data"]["module"] = self::courseModule($moduleCore,$event_data["other"]["modulename"]);

        self::send_rabbit($rabbit_object);
    }

    public static function course_module_updated(\core\event\course_module_updated $event){
        $event_data = $event->get_data();

        $moduleCore = self::getModule(
            $event_data["other"]["instanceid"],
            $event_data["courseid"],
            $event_data["other"]["modulename"]
        );

        error_log(json_encode($moduleCore));

        $fired_at = gmdate('Y-m-d\TH:i:s.000\Z',$event_data["timecreated"]);
        $rabbit_object["contextid"] = $event_data["contextid"];
        $rabbit_object["other"]["message_config"]["eventname"] = 'course_module_updated';
        $rabbit_object["other"]["message_data"]['institution']["institution_abbreviation"] = get_config('local_message_broker', 'institutionname');
        $rabbit_object["other"]["message_data"]['institution']["modality"]= get_config('local_message_broker', 'modality');
        $rabbit_object["other"]["message_data"]['fired_at']= $fired_at;
        $rabbit_object["other"]["message_data"]["uuid"] = \core\uuid::generate();
        $rabbit_object["other"]["message_data"]["courseId"] = $event->courseid;
        //$rabbit_object["other"]["message_data"]["module"] = $course_module;

        //self::send_rabbit($rabbit_object);
    }
    public static function send_rabbit($rabbit_object){
        error_log(json_encode($rabbit_object));
    }

    public static function getModule($instanceid,$courseid,$modname){
        global $DB;
        return $DB->get_record($modname,array('id'=>$instanceid, 'course'=>$courseid));
    }

    public static function getEndDateModule(stdClass $module){
        $endDate = 0;
        if (isset($module->timeavailableto)) {
            $endDate = $module->timeavailableto;
        } elseif (isset($module->cutoffdate)) {
            $endDate = $module->cutoffdate;
        } elseif (isset($module->timeclose)) {
            $endDate = $module->timeclose;
        } elseif (isset($module->deadline)) {
            $endDate = $module->deadline;
        } elseif (isset($module->submissionend)) {
            $endDate = $module->submissionend;
        }

        return $endDate;
    }

    public static function getStartDateModule(stdClass $module){
        $startDate = 0;
        if(isset($module->allowsubmissionsfromdate)){
            $startDate = $module->allowsubmissionsfromdate;
        }
        return $startDate;
    }

    public static function courseModule($module,$typemod){
        $courseModule = new stdClass();
        $courseModule->id = $module->id;
        $courseModule->courseId = $module->course;
        $courseModule->name = $module->name;
        $courseModule->type = $typemod;
        $courseModule->url = 'por hacer';
        $courseModule->startDate = self::getStartDateModule($module);
        $courseModule->endDate = self::getEndDateModule($module);
        return $courseModule;
    }
}