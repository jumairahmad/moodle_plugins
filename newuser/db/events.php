<?php

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname'   => '\core\event\user_loggedin',
        'callback'    => 'userAddedEventFired',
        'includefile' => '/local/newuser/lib.php',
    ),
);