<?php

namespace local_collector_alert_bun;

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use external_api;
use stdClass;
class external_modules extends external_api {
    public static function get_modules_data_parameters(){
        return new external_function_parameters(
            array(
                'page' => new external_value(PARAM_INT),
                'type' => new external_value(PARAM_TEXT)
            )
        );
    }
    public static function get_modules_data($page, $type){
        global $DB,$CFG;

        $limit = 10;
        $offset = $limit * $page;
        $totalpages = ceil($DB->get_record_sql('SELECT count(1) as total_exist
                                                                    FROM mdl_course_modules AS cm 
                                                                        INNER JOIN mdl_modules AS m ON cm.module = m.id 
                                                                    where m.name = "'.$type.'"
                                                                    order by cm.id asc')->total_exist);

        $courseModule = array_values($DB->get_records_sql('SELECT cm.*, m.name as type, module.name as name
                                                                    FROM mdl_course_modules AS cm 
                                                                        INNER JOIN mdl_modules AS m ON cm.module = m.id
                                                                        INNER JOIN mdl_'.$type.' AS module ON module.id = cm.instance
                                                                    where m.name = "'.$type.'"
                                                                    order by cm.id asc
                                                                    LIMIT 10 OFFSET '.$offset));

        //limpiar la data que no usare y retornar lo que si usare esto esta en cierto modo alineado para evitar trabajo extra en el servicio
        $modules = array();
        for ($i = 0; $i < count($courseModule); $i++) {
            $element = $courseModule[$i];
            $courseObject = new stdClass();
            $courseObject->id = $element->id;
            $courseObject->courseId = $element->course;
            $courseObject->name = $element->name;
            $courseObject->type = $element->type;
            $courseObject->url = (new \moodle_url($CFG->wwwroot.'/mod/view.php?id='.$element->id))->out();
            $courseObject->startDate = self::getStartDateModule($element);
            $courseObject->endDate = self::getEndDateModule($element);
            $modules[] = $courseObject;
        }
        return $modules;
    }
    public static function get_modules_data_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id'=>new external_value(PARAM_TEXT),
                    'courseId'=>new external_value(PARAM_TEXT),
                    'name'=>new external_value(PARAM_RAW),
                    'type'=>new external_value(PARAM_TEXT),
                    'url'=>new external_value(PARAM_TEXT),
                    'startDate'=>new external_value(PARAM_TEXT),
                    'endDate'=>new external_value(PARAM_TEXT)
                )
            )
        );
    }

    public static function get_modules_pages_counter_parameters(){
        return new external_function_parameters(
            array()
        );
    }
    public static function get_modules_pages_counter(){
        global $DB;

        $existingModules = array_values($DB->get_records_sql("SELECT * FROM mdl_modules"));

        $modules = array();

        for ($i = 0; $i < count($existingModules); $i++) {
            $element = $existingModules[$i];

            $courseModules = $DB->get_record_sql('SELECT count(1) as total_exist
                                                                    FROM mdl_course_modules AS cm 
                                                                        INNER JOIN mdl_modules AS m ON cm.module = m.id 
                                                                    where m.name = "'.$element->name.'"
                                                                    order by cm.id asc');

            $moduleCounter = new stdClass();
            $moduleCounter->type = $element->name;
            $moduleCounter->totalexist = $courseModules->total_exist;
            $modules[] = $moduleCounter;
        }

        return $modules;
    }
    public static function get_modules_pages_counter_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    "type"=>new external_value(PARAM_TEXT),
                    "totalexist"=>new external_value(PARAM_INT)
                )
            )
        );
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
}