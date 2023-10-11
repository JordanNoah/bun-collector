<?php

$functions = [
    'local_collector_alert_bun_get_all_roles'=>[
        'classname' => 'local_collector_alert_bun\external_roles',
        'methodname' => 'get_all_roles',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_users_data' => [
        'classname' => 'local_collector_alert_bun\external_users',
        'methodname' => 'get_users_data',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_users_counter' => [
        'classname' => 'local_collector_alert_bun\external_users',
        'methodname' => 'get_users_pages_counter',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_courses_counter' => [
        'classname' => 'local_collector_alert_bun\external_courses',
        'methodname' => 'get_courses_pages_counter',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_courses_data'=> [
        'classname' => 'local_collector_alert_bun\external_courses',
        'methodname' => 'get_courses_data',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_modules_counter' => [
        'classname' => 'local_collector_alert_bun\external_modules',
        'methodname' => 'get_modules_pages_counter',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_modules_data' => [
        'classname' => 'local_collector_alert_bun\external_modules',
        'methodname' => 'get_modules_data',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_enrolments_data' => [
        'classname' => 'local_collector_alert_bun\external_enrolment',
        'methodname' => 'get_enrolments_data',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ],
    'local_collector_alert_bun_get_enrolments_counter' => [
        'classname' => 'local_collector_alert_bun\external_enrolment',
        'methodname' => 'get_enrolments_pages_counter',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true
    ]
];