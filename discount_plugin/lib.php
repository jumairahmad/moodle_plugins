<?php
defined('MOODLE_INTERNAL') || die();

function local_discount_plugin_extend_before_user_enrolment_updated($courseid) {
    global $DB, $USER;

    // Check if user is a member
    $userId = $USER->id; // Assuming you have access to the current user
    $isMember = local_discount_plugin_isMember($userId); // Implement isMember() function as per your logic
  //enrol $instance = $DB->get_record('enrol', ['enrol' => 'fee', 'id' => $instanceid], '*', MUST_EXIST);
    // Get original price of the course
    $enrolPrice = $DB->get_record('enrol', ['enrol' => 'fee', 'courseid' => $courseid], '*', MUST_EXIST);//$DB->get_record('enrol', array('courseid' => $courseid), '*', MUST_EXIST);
    $originalPrice = $enrolPrice->cost;

    // Calculate discounted price if user is a member
    $discount = $isMember ? 0.2 : 0; // 20% discount for members
    $discountedPrice = $originalPrice - ($originalPrice * $discount);

    // Debugging: Output the original and discounted prices
    echo "Original Price: $originalPrice<br>";
    echo "Discounted Price: $discountedPrice<br>";

    return $discountedPrice;
}

function local_discount_plugin_isMember($userId) {
    // Implement your logic to check if user is a member
    // Return true if user is a member, false otherwise

   // enrol_fee($userId,"");
    return true;
}

// Hook into enroll price calculation
function local_discount_plugin_before_user_enrolment_updated($courseid) {
    $discountedPrice = local_discount_plugin_extend_before_user_enrolment_updated($courseid);
    return $discountedPrice;
}

// Register the course price calculation function
function local_discount_plugin_extend_navigation(global_navigation $navigation) {
    $navigation->add('discount_plugin', new moodle_url('/local/discount_plugin/index.php'), 'misc', 'Discount Plugin');
}
