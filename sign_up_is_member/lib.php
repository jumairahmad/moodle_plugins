<?php
defined('MOODLE_INTERNAL') || die();


//$pluginsfunction = get_plugins_with_function('validate_extend_signup_form');
function local_sign_up_is_member_validate_extend_signup_form($data){
    if($data['profile_field_memberID'] !== "" && $data['profile_field_memberID'] !== null){
        $isMember = isUserAAAMember($data['profile_field_memberID']);
        if(!$isMember){
            $errors = array(
                "profile_field_memberID"=> "Invalid AAA Member ID",
            );
            return $errors;
        }
    }

    //else returns empty array

    return array();
   
}


function isUserAAAMember($memberId){
    $apiUrl = 'https://api.aaa-approved.com/VerifyMember/ByMemberNumber?memberNumber=' . $memberId;    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if(curl_errno($ch)){
        // Handle cURL error
        echo 'Curl error: ' . curl_error($ch);
        debugging('Curl error: ' . curl_error($ch));
    }
    $result = json_decode($response);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch); // Close the cURL session
    if($http_status == 200){
        // Use the $result variable if necessary
        return true;
    }
    if($http_status == 500){
        return false;
    }  
    return false;
}