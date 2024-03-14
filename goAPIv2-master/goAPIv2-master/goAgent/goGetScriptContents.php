<?php
 /**
 * @file 		goGetScriptContents.php
 * @brief 		API for Agent UI
 * @copyright 	Copyright (C) GOautodial Inc.
 * @author     	Chris Lomuntad <chris@goautodial.com>
 *
 * @par <b>License</b>:
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$is_logged_in = check_agent_login($astDB, $goUser);

$agent = get_settings('user', $astDB, $goUser);

if (isset($_GET['goServerIP'])) { $server_ip = $astDB->escape($_GET['goServerIP']); }
    else if (isset($_POST['goServerIP'])) { $server_ip = $astDB->escape($_POST['goServerIP']); }
if (isset($_GET['goSessionName'])) { $session_name = $astDB->escape($_GET['goSessionName']); }
    else if (isset($_POST['goSessionName'])) { $session_name = $astDB->escape($_POST['goSessionName']); }
if (isset($_GET['goScrollDIV'])) { $ScrollDIV = $astDB->escape($_GET['goScrollDIV']); }
    else if (isset($_POST['goScrollDIV'])) { $ScrollDIV = $astDB->escape($_POST['goScrollDIV']); }
if (isset($_GET['goCampScript'])) { $go_camp_script = $astDB->escape($_GET['goCampScript']); }
    else if (isset($_POST['goCampScript'])) { $go_camp_script = $astDB->escape($_POST['goCampScript']); }
if (isset($_GET['lead_id'])) { $lead_id = $astDB->escape($_GET['lead_id']); }
    else if (isset($_POST['lead_id'])) { $lead_id = $astDB->escape($_POST['lead_id']); }
if (isset($_GET['vendor_id'])) { $vendor_id = $astDB->escape($_GET['vendor_id']); }
    else if (isset($_POST['vendor_id'])) { $vendor_id = $astDB->escape($_POST['vendor_id']); }
if (isset($_GET['list_id'])) { $list_id = $astDB->escape($_GET['list_id']); }
    else if (isset($_POST['list_id'])) { $list_id = $astDB->escape($_POST['list_id']); }
if (isset($_GET['gmt_offset_now'])) { $gmt_offset_now = $astDB->escape($_GET['gmt_offset_now']); }
    else if (isset($_POST['gmt_offset_now'])) { $gmt_offset_now = $astDB->escape($_POST['gmt_offset_now']); }
if (isset($_GET['phone_code'])) { $phone_code = $astDB->escape($_GET['phone_code']); }
    else if (isset($_POST['phone_code'])) { $phone_code = $astDB->escape($_POST['phone_code']); }
if (isset($_GET['phone_number'])) { $phone_number = $astDB->escape($_GET['phone_number']); }
    else if (isset($_POST['phone_number'])) { $phone_number = $astDB->escape($_POST['phone_number']); }
if (isset($_GET['title'])) { $title = $astDB->escape($_GET['title']); }
    else if (isset($_POST['title'])) { $title = $astDB->escape($_POST['title']); }
if (isset($_GET['first_name'])) { $first_name = $astDB->escape($_GET['first_name']); }
    else if (isset($_POST['first_name'])) { $first_name = $astDB->escape($_POST['first_name']); }
if (isset($_GET['middle_initial'])) { $middle_initial = $astDB->escape($_GET['middle_initial']); }
    else if (isset($_POST['middle_initial'])) { $middle_initial = $astDB->escape($_POST['middle_initial']); }
if (isset($_GET['last_name'])) { $last_name = $astDB->escape($_GET['last_name']); }
    else if (isset($_POST['last_name'])) { $last_name = $astDB->escape($_POST['last_name']); }
if (isset($_GET['address1'])) { $address1 = $astDB->escape($_GET['address1']); }
    else if (isset($_POST['address1'])) { $address1 = $astDB->escape($_POST['address1']); }
if (isset($_GET['address2'])) { $address2 = $astDB->escape($_GET['address2']); }
    else if (isset($_POST['address2'])) { $address2 = $astDB->escape($_POST['address2']); }
if (isset($_GET['address3'])) { $address3 = $astDB->escape($_GET['address3']); }
    else if (isset($_POST['address3'])) { $address3 = $astDB->escape($_POST['address3']); }
if (isset($_GET['city'])) { $city = $astDB->escape($_GET['city']); }
    else if (isset($_POST['city'])) { $city = $astDB->escape($_POST['city']); }
if (isset($_GET['state'])) { $state = $astDB->escape($_GET['state']); }
    else if (isset($_POST['state'])) { $state = $astDB->escape($_POST['state']); }
if (isset($_GET['province'])) { $province = $astDB->escape($_GET['province']); }
    else if (isset($_POST['province'])) { $province = $astDB->escape($_POST['province']); }
if (isset($_GET['postal_code'])) { $postal_code = $astDB->escape($_GET['postal_code']); }
    else if (isset($_POST['postal_code'])) { $postal_code = $astDB->escape($_POST['postal_code']); }
if (isset($_GET['country_code'])) { $country_code = $astDB->escape($_GET['country_code']); }
    else if (isset($_POST['country_code'])) { $country_code = $astDB->escape($_POST['country_code']); }
if (isset($_GET['gender'])) { $gender = $astDB->escape($_GET['gender']); }
    else if (isset($_POST['gender'])) { $gender = $astDB->escape($_POST['gender']); }
if (isset($_GET['date_of_birth'])) { $date_of_birth = $astDB->escape($_GET['date_of_birth']); }
    else if (isset($_POST['date_of_birth'])) { $date_of_birth = $astDB->escape($_POST['date_of_birth']); }
if (isset($_GET['alt_phone'])) { $alt_phone = $astDB->escape($_GET['alt_phone']); }
    else if (isset($_POST['alt_phone'])) { $alt_phone = $astDB->escape($_POST['alt_phone']); }
if (isset($_GET['email'])) { $email = $astDB->escape($_GET['email']); }
    else if (isset($_POST['email'])) { $email = $astDB->escape($_POST['email']); }
if (isset($_GET['security_phrase'])) { $security_phrase = $astDB->escape($_GET['security_phrase']); }
    else if (isset($_POST['security_phrase'])) { $security_phrase = $astDB->escape($_POST['security_phrase']); }
if (isset($_GET['comments'])) { $comments = $astDB->escape($_GET['comments']); }
    else if (isset($_POST['comments'])) { $comments = $astDB->escape($_POST['comments']); }
if (isset($_GET['user'])) { $user = $astDB->escape($_GET['user']); }
    else if (isset($_POST['user'])) { $user = $astDB->escape($_POST['user']); }
if (isset($_GET['pass'])) { $pass = $astDB->escape($_GET['pass']); }
    else if (isset($_POST['pass'])) { $pass = $astDB->escape($_POST['pass']); }
if (isset($_GET['campaign'])) { $campaign = $astDB->escape($_GET['campaign']); }
    else if (isset($_POST['campaign'])) { $campaign = $astDB->escape($_POST['campaign']); }
if (isset($_GET['phone_login'])) { $phone_login = $astDB->escape($_GET['phone_login']); }
    else if (isset($_POST['phone_login'])) { $phone_login = $astDB->escape($_POST['phone_login']); }
if (isset($_GET['original_phone_login'])) { $original_phone_login = $astDB->escape($_GET['original_phone_login']); }
    else if (isset($_POST['original_phone_login'])) { $original_phone_login = $astDB->escape($_POST['original_phone_login']); }
if (isset($_GET['phone_pass'])) { $phone_pass = $astDB->escape($_GET['phone_pass']); }
    else if (isset($_POST['phone_pass'])) { $phone_pass = $astDB->escape($_POST['phone_pass']); }
if (isset($_GET['fronter'])) { $fronter = $astDB->escape($_GET['fronter']); }
    else if (isset($_POST['fronter'])) { $fronter = $astDB->escape($_POST['fronter']); }
if (isset($_GET['closer'])) { $closer = $astDB->escape($_GET['closer']); }
    else if (isset($_POST['closer'])) { $closer = $astDB->escape($_POST['closer']); }
if (isset($_GET['group'])) { $group = $astDB->escape($_GET['group']); }
    else if (isset($_POST['group'])) { $group = $astDB->escape($_POST['group']); }
if (isset($_GET['channel_group'])) { $channel_group = $astDB->escape($_GET['channel_group']); }
    else if (isset($_POST['channel_group'])) { $channel_group = $astDB->escape($_POST['channel_group']); }
if (isset($_GET['SQLdate'])) { $SQLdate = $astDB->escape($_GET['SQLdate']); }
    else if (isset($_POST['SQLdate'])) { $SQLdate = $astDB->escape($_POST['SQLdate']); }
if (isset($_GET['epoch'])) { $epoch = $astDB->escape($_GET['epoch']); }
    else if (isset($_POST['epoch'])) { $epoch = $astDB->escape($_POST['epoch']); }
if (isset($_GET['uniqueid'])) { $uniqueid = $astDB->escape($_GET['uniqueid']); }
    else if (isset($_POST['uniqueid'])) { $uniqueid = $astDB->escape($_POST['uniqueid']); }
if (isset($_GET['customer_zap_channel'])) { $customer_zap_channel = $astDB->escape($_GET['customer_zap_channel']); }
    else if (isset($_POST['customer_zap_channel'])) { $customer_zap_channel = $astDB->escape($_POST['customer_zap_channel']); }
if (isset($_GET['customer_server_ip'])) { $customer_server_ip = $astDB->escape($_GET['customer_server_ip']); }
    else if (isset($_POST['customer_server_ip'])) { $customer_server_ip = $astDB->escape($_POST['customer_server_ip']); }
if (isset($_GET['server_ip'])) { $server_ip = $astDB->escape($_GET['server_ip']); }
    else if (isset($_POST['server_ip'])) { $server_ip = $astDB->escape($_POST['server_ip']); }
if (isset($_GET['SIPexten'])) { $SIPexten = $astDB->escape($_GET['SIPexten']); }
    else if (isset($_POST['SIPexten'])) { $SIPexten = $astDB->escape($_POST['SIPexten']); }
if (isset($_GET['session_id'])) { $session_id = $astDB->escape($_GET['session_id']); }
    else if (isset($_POST['session_id'])) { $session_id = $astDB->escape($_POST['session_id']); }
if (isset($_GET['phone'])) { $phone = $astDB->escape($_GET['phone']); }
    else if (isset($_POST['phone'])) { $phone = $astDB->escape($_POST['phone']); }
if (isset($_GET['parked_by'])) { $parked_by = $astDB->escape($_GET['parked_by']); }
    else if (isset($_POST['parked_by'])) { $parked_by = $astDB->escape($_POST['parked_by']); }
if (isset($_GET['dispo'])) { $dispo = $astDB->escape($_GET['dispo']); }
    else if (isset($_POST['dispo'])) { $dispo = $astDB->escape($_POST['dispo']); }
if (isset($_GET['dialed_number'])) { $dialed_number = $astDB->escape($_GET['dialed_number']); }
    else if (isset($_POST['dialed_number'])) { $dialed_number = $astDB->escape($_POST['dialed_number']); }
if (isset($_GET['dialed_label'])) { $dialed_label = $astDB->escape($_GET['dialed_label']); }
    else if (isset($_POST['dialed_label'])) { $dialed_label = $astDB->escape($_POST['dialed_label']); }
if (isset($_GET['source_id'])) { $source_id = $astDB->escape($_GET['source_id']); }
    else if (isset($_POST['source_id'])) { $source_id = $astDB->escape($_POST['source_id']); }
if (isset($_GET['rank'])) { $rank = $astDB->escape($_GET['rank']); }
    else if (isset($_POST['rank'])) { $rank = $astDB->escape($_POST['rank']); }
if (isset($_GET['owner'])) { $owner = $astDB->escape($_GET['owner']); }
    else if (isset($_POST['owner'])) { $owner = $astDB->escape($_POST['owner']); }
if (isset($_GET['camp_script'])) { $camp_script = $astDB->escape($_GET['camp_script']); }
    else if (isset($_POST['camp_script'])) { $camp_script = $astDB->escape($_POST['camp_script']); }
if (isset($_GET['in_script'])) { $in_script = $astDB->escape($_GET['in_script']); }
    else if (isset($_POST['in_script'])) { $in_script = $astDB->escape($_POST['in_script']); }
if (isset($_GET['fullname'])) { $fullname = $astDB->escape($_GET['fullname']); }
    else if (isset($_POST['fullname'])) { $fullname = $astDB->escape($_POST['fullname']); }
if (isset($_GET['recording_filename'])) { $recording_filename = $astDB->escape($_GET['recording_filename']); }
    else if (isset($_POST['recording_filename'])) { $recording_filename = $astDB->escape($_POST['recording_filename']); }
if (isset($_GET['recording_id'])) { $recording_id = $astDB->escape($_GET['recording_id']); }
    else if (isset($_POST['recording_id'])) { $recording_id = $astDB->escape($_POST['recording_id']); }
if (isset($_GET['user_custom_one'])) { $user_custom_one = $astDB->escape($_GET['user_custom_one']); }
    else if (isset($_POST['user_custom_one'])) { $user_custom_one = $astDB->escape($_POST['user_custom_one']); }
if (isset($_GET['user_custom_two'])) { $user_custom_two = $astDB->escape($_GET['user_custom_two']); }
    else if (isset($_POST['user_custom_two'])) { $user_custom_two = $astDB->escape($_POST['user_custom_two']); }
if (isset($_GET['user_custom_three'])) { $user_custom_three = $astDB->escape($_GET['user_custom_three']); }
    else if (isset($_POST['user_custom_three'])) { $user_custom_three = $astDB->escape($_POST['user_custom_three']); }
if (isset($_GET['user_custom_four'])) { $user_custom_four = $astDB->escape($_GET['user_custom_four']); }
    else if (isset($_POST['user_custom_four'])) { $user_custom_four = $astDB->escape($_POST['user_custom_four']); }
if (isset($_GET['user_custom_five'])) { $user_custom_five = $astDB->escape($_GET['user_custom_five']); }
    else if (isset($_POST['user_custom_five'])) { $user_custom_five = $astDB->escape($_POST['user_custom_five']); }
if (isset($_GET['preset_number_a'])) { $preset_number_a = $astDB->escape($_GET['preset_number_a']); }
    else if (isset($_POST['preset_number_a'])) { $preset_number_a = $astDB->escape($_POST['preset_number_a']); }
if (isset($_GET['preset_number_b'])) { $preset_number_b = $astDB->escape($_GET['preset_number_b']); }
    else if (isset($_POST['preset_number_b'])) { $preset_number_b = $astDB->escape($_POST['preset_number_b']); }
if (isset($_GET['preset_number_c'])) { $preset_number_c = $astDB->escape($_GET['preset_number_c']); }
    else if (isset($_POST['preset_number_c'])) { $preset_number_c = $astDB->escape($_POST['preset_number_c']); }
if (isset($_GET['preset_number_d'])) { $preset_number_d = $astDB->escape($_GET['preset_number_d']); }
    else if (isset($_POST['preset_number_d'])) { $preset_number_d = $astDB->escape($_POST['preset_number_d']); }
if (isset($_GET['preset_number_e'])) { $preset_number_e = $astDB->escape($_GET['preset_number_e']); }
    else if (isset($_POST['preset_number_e'])) { $preset_number_e = $astDB->escape($_POST['preset_number_e']); }
if (isset($_GET['preset_dtmf_a'])) { $preset_dtmf_a = $astDB->escape($_GET['preset_dtmf_a']); }
    else if (isset($_POST['preset_dtmf_a'])) { $preset_dtmf_a = $astDB->escape($_POST['preset_dtmf_a']); }
if (isset($_GET['preset_dtmf_b'])) { $preset_dtmf_b = $astDB->escape($_GET['preset_dtmf_b']); }
    else if (isset($_POST['preset_dtmf_b'])) { $preset_dtmf_b = $astDB->escape($_POST['preset_dtmf_b']); }
if (isset($_GET['did_id'])) { $did_id = $astDB->escape($_GET['did_id']); }
    else if (isset($_POST['did_id'])) { $did_id = $astDB->escape($_POST['did_id']); }
if (isset($_GET['did_extension'])) { $did_extension = $astDB->escape($_GET['did_extension']); }
    else if (isset($_POST['did_extension'])) { $did_extension = $astDB->escape($_POST['did_extension']); }
if (isset($_GET['did_pattern'])) { $did_pattern = $astDB->escape($_GET['did_pattern']); }
    else if (isset($_POST['did_pattern'])) { $did_pattern = $astDB->escape($_POST['did_pattern']); }
if (isset($_GET['did_description'])) { $did_description = $astDB->escape($_GET['did_description']); }
    else if (isset($_POST['did_description'])) { $did_description = $astDB->escape($_POST['did_description']); }
if (isset($_GET['closecallid'])) { $closecallid = $astDB->escape($_GET['closecallid']); }
    else if (isset($_POST['closecallid'])) { $closecallid = $astDB->escape($_POST['closecallid']); }
if (isset($_GET['xfercallid'])) { $xfercallid = $astDB->escape($_GET['xfercallid']); }
    else if (isset($_POST['xfercallid'])) { $xfercallid = $astDB->escape($_POST['xfercallid']); }
if (isset($_GET['agent_log_id'])) { $agent_log_id = $astDB->escape($_GET['agent_log_id']); }
    else if (isset($_POST['agent_log_id'])) { $agent_log_id = $astDB->escape($_POST['agent_log_id']); }
if (isset($_GET['entry_list_id'])) { $entry_list_id = $astDB->escape($_GET['entry_list_id']); }
    else if (isset($_POST['entry_list_id'])) { $entry_list_id = $astDB->escape($_POST['entry_list_id']); }
if (isset($_GET['call_id'])) { $call_id = $astDB->escape($_GET['call_id']); }
    else if (isset($_POST['call_id'])) { $call_id = $astDB->escape($_POST['call_id']); }
if (isset($_GET['user_group'])) { $user_group = $astDB->escape($_GET['user_group']); }
    else if (isset($_POST['user_group'])) { $user_group = $astDB->escape($_POST['user_group']); }
if (isset($_GET['web_vars'])) { $web_vars = $astDB->escape($_GET['web_vars']); }
    else if (isset($_POST['web_vars'])) { $web_vars = $astDB->escape($_POST['web_vars']); }
if (isset($_GET['session_name'])) { $session_name = $astDB->escape($_GET['session_name']); }
    else if (isset($_POST['session_name'])) { $session_name = $astDB->escape($_POST['session_name']); }
if (isset($_GET['goUserCustomFields'])) { $CF_uses_custom_fields = $astDB->escape($_GET['goUserCustomFields']); }
    else if (isset($_POST['goUserCustomFields'])) { $CF_uses_custom_fields = $astDB->escape($_POST['goUserCustomFields']); }
    
if (isset($_GET['isPBP'])) { $isPBP = $astDB->escape($_GET['isPBP']); }
    else if (isset($_POST['isPBP'])) { $isPBP = $astDB->escape($_POST['isPBP']); }


if ($is_logged_in) {
    if (strlen($in_script) < 1) {
        if (isset($camp_script) && $camp_script !== '') {
            $call_script = $camp_script;
        } else {
            $call_script = $go_camp_script;
        }
    }
    else
        {$call_script = $in_script;}
    
    $ignore_list_script_override = 'N';
    //$stmt = "SELECT ignore_list_script_override FROM vicidial_inbound_groups where group_id='$group';";
    $astDB->where('group_id', $group);
    $rslt = $astDB->getOne('vicidial_inbound_groups', 'ignore_list_script_override');
    $ilso_ct = $astDB->getRowCount();
    if ($ilso_ct > 0) {
        $ignore_list_script_override = $rslt['ignore_list_script_override'];
    }
    if ($ignore_list_script_override == 'Y')
        {$ignore_list_script = 1;}
    
    if ($ignore_list_script < 1) {
        //$stmt="SELECT agent_script_override from vicidial_lists where list_id='$list_id';";
        $astDB->where('list_id', $list_id);
        $rslt = $astDB->getOne('vicidial_lists', 'agent_script_override');
        $agent_script_override = $rslt['agent_script_override'];
        if (strlen($agent_script_override) > 0)
            {$call_script = $agent_script_override;}
    }
    
    //$stmt="SELECT list_name,list_description from vicidial_lists where list_id='$list_id';";
    $astDB->where('list_id', $list_id);
    $rslt = $astDB->getOne('vicidial_lists', 'list_name,list_description');
    $list_name =			$rslt['list_name'];
    $list_description =		$rslt['list_description'];
    
    //$stmt="SELECT script_name,script_text from vicidial_scripts where script_id='$call_script';";
    $astDB->where('script_id', $call_script);
    $rslt = $astDB->getOne('vicidial_scripts', 'script_name,script_text');
    $script_name =		$rslt['script_name'];
    $script_text =		stripslashes($rslt['script_text']);
    
    if (preg_match("/iframe\ssrc/i", $script_text)) {
        $IFRAME = 1;
        $lead_id = preg_replace('/\s/i', '+', $lead_id);
        $vendor_id = preg_replace('/\s/i', '+', $vendor_id);
        $vendor_lead_code = preg_replace('/\s/i', '+', $vendor_lead_code);
        $list_id = preg_replace('/\s/i', '+', $list_id);
        $list_name = preg_replace('/\s/i', '+', $list_name);
        $list_description = preg_replace('/\s/i', '+', $list_description);
        $gmt_offset_now = preg_replace('/\s/i', '+', $gmt_offset_now);
        $phone_code = preg_replace('/\s/i', '+', $phone_code);
        $phone_number = preg_replace('/\s/i', '+', $phone_number);
        $title = preg_replace('/\s/i', '+', $title);
        $first_name = preg_replace('/\s/i', '+', $first_name);
        $middle_initial = preg_replace('/\s/i', '+', $middle_initial);
        $last_name = preg_replace('/\s/i', '+', $last_name);
        $address1 = preg_replace('/\s/i', '+', $address1);
        $address2 = preg_replace('/\s/i', '+', $address2);
        $address3 = preg_replace('/\s/i', '+', $address3);
        $city = preg_replace('/\s/i', '+', $city);
        $state = preg_replace('/\s/i', '+', $state);
        $province = preg_replace('/\s/i', '+', $province);
        $postal_code = preg_replace('/\s/i', '+', $postal_code);
        $country_code = preg_replace('/\s/i', '+', $country_code);
        $gender = preg_replace('/\s/i', '+', $gender);
        $date_of_birth = preg_replace('/\s/i', '+', $date_of_birth);
        $alt_phone = preg_replace('/\s/i', '+', $alt_phone);
        $email = preg_replace('/\s/i', '+', $email);
        $security_phrase = preg_replace('/\s/i', '+', $security_phrase);
        $comments = preg_replace('/\s/i', '+', $comments);
        $user = preg_replace('/\s/i', '+', $user);
        $pass = preg_replace('/\s/i', '+', $pass);
        $campaign = preg_replace('/\s/i', '+', $campaign);
        $phone_login = preg_replace('/\s/i', '+', $phone_login);
        $original_phone_login = preg_replace('/\s/i', '+', $original_phone_login);
        $phone_pass = preg_replace('/\s/i', '+', $phone_pass);
        $fronter = preg_replace('/\s/i', '+', $fronter);
        $closer = preg_replace('/\s/i', '+', $closer);
        $group = preg_replace('/\s/i', '+', $group);
        $channel_group = preg_replace('/\s/i', '+', $channel_group);
        $SQLdate = preg_replace('/\s/i', '+', $SQLdate);
        $epoch = preg_replace('/\s/i', '+', $epoch);
        $uniqueid = preg_replace('/\s/i', '+', $uniqueid);
        $customer_zap_channel = preg_replace('/\s/i', '+', $customer_zap_channel);
        $customer_server_ip = preg_replace('/\s/i', '+', $customer_server_ip);
        $server_ip = preg_replace('/\s/i', '+', $server_ip);
        $SIPexten = preg_replace('/\s/i', '+', $SIPexten);
        $session_id = preg_replace('/\s/i', '+', $session_id);
        $phone = preg_replace('/\s/i', '+', $phone);
        $parked_by = preg_replace('/\s/i', '+', $parked_by);
        $dispo = preg_replace('/\s/i', '+', $dispo);
        $dialed_number = preg_replace('/\s/i', '+', $dialed_number);
        $dialed_label = preg_replace('/\s/i', '+', $dialed_label);
        $source_id = preg_replace('/\s/i', '+', $source_id);
        $rank = preg_replace('/\s/i', '+', $rank);
        $owner = preg_replace('/\s/i', '+', $owner);
        $camp_script = preg_replace('/\s/i', '+', $camp_script);
        $in_script = preg_replace('/\s/i', '+', $in_script);
        $script_width = preg_replace('/\s/i', '+', $script_width);
        $script_height = preg_replace('/\s/i', '+', $script_height);
        $fullname = preg_replace('/\s/i', '+', $fullname);
        $recording_filename = preg_replace('/\s/i', '+', $recording_filename);
        $recording_id = preg_replace('/\s/i', '+', $recording_id);
        $user_custom_one = preg_replace('/\s/i', '+', $user_custom_one);
        $user_custom_two = preg_replace('/\s/i', '+', $user_custom_two);
        $user_custom_three = preg_replace('/\s/i', '+', $user_custom_three);
        $user_custom_four = preg_replace('/\s/i', '+', $user_custom_four);
        $user_custom_five = preg_replace('/\s/i', '+', $user_custom_five);
        $preset_number_a = preg_replace('/\s/i', '+', $preset_number_a);
        $preset_number_b = preg_replace('/\s/i', '+', $preset_number_b);
        $preset_number_c = preg_replace('/\s/i', '+', $preset_number_c);
        $preset_number_d = preg_replace('/\s/i', '+', $preset_number_d);
        $preset_number_e = preg_replace('/\s/i', '+', $preset_number_e);
        $preset_number_f = preg_replace('/\s/i', '+', $preset_number_f);
        $preset_dtmf_a = preg_replace('/\s/i', '+', $preset_dtmf_a);
        $preset_dtmf_b = preg_replace('/\s/i', '+', $preset_dtmf_b);
        $did_id = preg_replace('/\s/i', '+', $did_id);
        $did_extension = preg_replace('/\s/i', '+', $did_extension);
        $did_pattern = preg_replace('/\s/i', '+', $did_pattern);
        $did_description = preg_replace('/\s/i', '+', $did_description);
        $web_vars = preg_replace('/\s/i', '+', $web_vars);
    }
    
    $script_text = preg_replace('/--A--lead_id--B--/i', "$lead_id", $script_text);
    $script_text = preg_replace('/--A--vendor_id--B--/i', "$vendor_id", $script_text);
    $script_text = preg_replace('/--A--vendor_lead_code--B--/i', "$vendor_lead_code", $script_text);
    $script_text = preg_replace('/--A--list_id--B--/i', "$list_id", $script_text);
    $script_text = preg_replace('/--A--list_name--B--/i', "$list_name", $script_text);
    $script_text = preg_replace('/--A--list_description--B--/i', "$list_description", $script_text);
    $script_text = preg_replace('/--A--gmt_offset_now--B--/i', "$gmt_offset_now", $script_text);
    $script_text = preg_replace('/--A--phone_code--B--/i', "$phone_code", $script_text);
    $script_text = preg_replace('/--A--phone_number--B--/i', "$phone_number", $script_text);
    $script_text = preg_replace('/--A--title--B--/i', "$title", $script_text);
    $script_text = preg_replace('/--A--first_name--B--/i', "$first_name", $script_text);
    $script_text = preg_replace('/--A--middle_initial--B--/i', "$middle_initial", $script_text);
    $script_text = preg_replace('/--A--last_name--B--/i', "$last_name", $script_text);
    $script_text = preg_replace('/--A--address1--B--/i', "$address1", $script_text);
    $script_text = preg_replace('/--A--address2--B--/i', "$address2", $script_text);
    $script_text = preg_replace('/--A--address3--B--/i', "$address3", $script_text);
    $script_text = preg_replace('/--A--city--B--/i', "$city", $script_text);
    $script_text = preg_replace('/--A--state--B--/i', "$state", $script_text);
    $script_text = preg_replace('/--A--province--B--/i', "$province", $script_text);
    $script_text = preg_replace('/--A--postal_code--B--/i', "$postal_code", $script_text);
    $script_text = preg_replace('/--A--country_code--B--/i', "$country_code", $script_text);
    $script_text = preg_replace('/--A--gender--B--/i', "$gender", $script_text);
    $script_text = preg_replace('/--A--date_of_birth--B--/i', "$date_of_birth", $script_text);
    $script_text = preg_replace('/--A--alt_phone--B--/i', "$alt_phone", $script_text);
    $script_text = preg_replace('/--A--email--B--/i', "$email", $script_text);
    $script_text = preg_replace('/--A--security_phrase--B--/i', "$security_phrase", $script_text);
    $script_text = preg_replace('/--A--comments--B--/i', "$comments", $script_text);
    $script_text = preg_replace('/--A--user--B--/i', "$user", $script_text);
    $script_text = preg_replace('/--A--pass--B--/i', "$pass", $script_text);
    $script_text = preg_replace('/--A--campaign--B--/i', "$campaign", $script_text);
    $script_text = preg_replace('/--A--phone_login--B--/i', "$phone_login", $script_text);
    $script_text = preg_replace('/--A--original_phone_login--B--/i', "$original_phone_login", $script_text);
    $script_text = preg_replace('/--A--phone_pass--B--/i', "$phone_pass", $script_text);
    $script_text = preg_replace('/--A--fronter--B--/i', "$fronter", $script_text);
    $script_text = preg_replace('/--A--closer--B--/i', "$closer", $script_text);
    $script_text = preg_replace('/--A--group--B--/i', "$group", $script_text);
    $script_text = preg_replace('/--A--channel_group--B--/i', "$channel_group", $script_text);
    $script_text = preg_replace('/--A--SQLdate--B--/i', "$SQLdate", $script_text);
    $script_text = preg_replace('/--A--epoch--B--/i', "$epoch", $script_text);
    $script_text = preg_replace('/--A--uniqueid--B--/i', "$uniqueid", $script_text);
    $script_text = preg_replace('/--A--customer_zap_channel--B--/i', "$customer_zap_channel", $script_text);
    $script_text = preg_replace('/--A--customer_server_ip--B--/i', "$customer_server_ip", $script_text);
    $script_text = preg_replace('/--A--server_ip--B--/i', "$server_ip", $script_text);
    $script_text = preg_replace('/--A--SIPexten--B--/i', "$SIPexten", $script_text);
    $script_text = preg_replace('/--A--session_id--B--/i', "$session_id", $script_text);
    $script_text = preg_replace('/--A--phone--B--/i', "$phone", $script_text);
    $script_text = preg_replace('/--A--parked_by--B--/i', "$parked_by", $script_text);
    $script_text = preg_replace('/--A--dispo--B--/i', "$dispo", $script_text);
    $script_text = preg_replace('/--A--dialed_number--B--/i', "$dialed_number", $script_text);
    $script_text = preg_replace('/--A--dialed_label--B--/i', "$dialed_label", $script_text);
    $script_text = preg_replace('/--A--source_id--B--/i', "$source_id", $script_text);
    $script_text = preg_replace('/--A--rank--B--/i', "$rank", $script_text);
    $script_text = preg_replace('/--A--owner--B--/i', "$owner", $script_text);
    $script_text = preg_replace('/--A--camp_script--B--/i', "$camp_script", $script_text);
    $script_text = preg_replace('/--A--in_script--B--/i', "$in_script", $script_text);
    $script_text = preg_replace('/--A--script_width--B--/i', "$script_width", $script_text);
    $script_text = preg_replace('/--A--script_height--B--/i', "$script_height", $script_text);
    $script_text = preg_replace('/--A--fullname--B--/i', "$fullname", $script_text);
    $script_text = preg_replace('/--A--recording_filename--B--/i', "$recording_filename", $script_text);
    $script_text = preg_replace('/--A--recording_id--B--/i', "$recording_id", $script_text);
    $script_text = preg_replace('/--A--user_custom_one--B--/i', "$user_custom_one", $script_text);
    $script_text = preg_replace('/--A--user_custom_two--B--/i', "$user_custom_two", $script_text);
    $script_text = preg_replace('/--A--user_custom_three--B--/i', "$user_custom_three", $script_text);
    $script_text = preg_replace('/--A--user_custom_four--B--/i', "$user_custom_four", $script_text);
    $script_text = preg_replace('/--A--user_custom_five--B--/i', "$user_custom_five", $script_text);
    $script_text = preg_replace('/--A--preset_number_a--B--/i', "$preset_number_a", $script_text);
    $script_text = preg_replace('/--A--preset_number_b--B--/i', "$preset_number_b", $script_text);
    $script_text = preg_replace('/--A--preset_number_c--B--/i', "$preset_number_c", $script_text);
    $script_text = preg_replace('/--A--preset_number_d--B--/i', "$preset_number_d", $script_text);
    $script_text = preg_replace('/--A--preset_number_e--B--/i', "$preset_number_e", $script_text);
    $script_text = preg_replace('/--A--preset_number_f--B--/i', "$preset_number_f", $script_text);
    $script_text = preg_replace('/--A--preset_dtmf_a--B--/i', "$preset_dtmf_a", $script_text);
    $script_text = preg_replace('/--A--preset_dtmf_b--B--/i', "$preset_dtmf_b", $script_text);
    $script_text = preg_replace('/--A--did_id--B--/i', "$did_id", $script_text);
    $script_text = preg_replace('/--A--did_extension--B--/i', "$did_extension", $script_text);
    $script_text = preg_replace('/--A--did_pattern--B--/i', "$did_pattern", $script_text);
    $script_text = preg_replace('/--A--did_description--B--/i', "$did_description", $script_text);
    $script_text = preg_replace('/--A--closecallid--B--/i', "$closecallid", $script_text);
    $script_text = preg_replace('/--A--xfercallid--B--/i', "$xfercallid", $script_text);
    $script_text = preg_replace('/--A--agent_log_id--B--/i', "$agent_log_id", $script_text);
    $script_text = preg_replace('/--A--entry_list_id--B--/i', "$entry_list_id", $script_text);
    $script_text = preg_replace('/--A--call_id--B--/i', "$call_id", $script_text);
    $script_text = preg_replace('/--A--user_group--B--/i', "$user_group", $script_text);
    $script_text = preg_replace('/--A--web_vars--B--/i', "$web_vars", $script_text);
    
    if ($CF_uses_custom_fields == 'Y') {
        ### find the names of all custom fields, if any
        //$stmt = "SELECT field_label,field_type FROM vicidial_lists_fields where list_id='$entry_list_id' and field_type NOT IN('SCRIPT','DISPLAY') and field_label NOT IN('vendor_lead_code','source_id','list_id','gmt_offset_now','called_since_last_reset','phone_code','phone_number','title','first_name','middle_initial','last_name','address1','address2','address3','city','state','province','postal_code','country_code','gender','date_of_birth','alt_phone','email','security_phrase','comments','called_count','last_local_call_time','rank','owner');";
        $astDB->where('list_id', $entry_list_id);
        $astDB->where('field_type', array('SCRIPT','DISPLAY'), 'not in');
        $astDB->where('field_label', array('vendor_lead_code','source_id','list_id','gmt_offset_now','called_since_last_reset','phone_code','phone_number','title','first_name','middle_initial','last_name','address1','address2','address3','city','state','province','postal_code','country_code','gender','date_of_birth','alt_phone','email','security_phrase','comments','called_count','last_local_call_time','rank','owner'), 'not in');
        $rslt = $astDB->get('vicidial_lists_fields', null, 'field_label,field_type');
        $cffn_ct = $astDB->getRowCount();
        $d = 0;
        while ($cffn_ct > $d) {
            $row = $rslt[$d];
            $field_name_id = $row['field_label'];
            $field_name_tag = "--A--" . $field_name_id . "--B--";
            
            $astDB->where('lead_id', $lead_id);
            $cRow = $astDB->getOne("custom_{$entry_list_id}");
            $form_field_value = $cRow["$field_name_id"];
            
            //if (isset($_GET["$field_name_id"]))				{$form_field_value = $_GET["$field_name_id"];}
            //    else if (isset($_POST["$field_name_id"]))	{$form_field_value = $_POST["$field_name_id"];}
            $script_text = preg_replace("/$field_name_tag/i", "$form_field_value", $script_text);
            //if ($DB) {echo "$d|$field_name_id|$field_name_tag|$form_field_value|<br>\n";}
            $d++;
        }
    }
    
    if ($isPBP !== 'Y') {
        $script_text = preg_replace("/\n/i", "<BR style='clear: both;'>", $script_text);
    }
    $script_text = stripslashes($script_text);
    
    $scriptHtml  = '';
    $scriptHtml .= "<!-- IFRAME$IFRAME -->\n";
    $scriptHtml .= "<!-- $script_id -->\n";
    $scriptHtml .= "<TABLE WIDTH=100%><TR><TD>\n";
    if ( ( ($IFRAME < 1) and ($ScrollDIV > 0) ) or (preg_match("/IGNORENOSCROLL/i", $script_text)) )
        {$scriptHtml .= "<div id=\"NewScriptContents\">";}
    if ($isPBP !== 'Y') {
        $scriptHtml .= "<center><B>$script_name</B></center><BR>\n";
    }
    $scriptHtml .= "$script_text\n";
    /*if ($isPBP !== 'Y') {
        $scriptHtml .= "</center>";
    }*/
    if ( ( ($IFRAME < 1) and ($ScrollDIV > 0) ) or (preg_match("/IGNORENOSCROLL/i", $script_text)) )
        {$scriptHtml .= "</div>";}
    $scriptHtml .= "</TD></TR></TABLE>\n";
    
    $APIResult = array( "result" => "success", "content" => $scriptHtml );
} else {
    $APIResult = array( "result" => "error", "message" => "Agent '$goUser' is currently NOT logged in" );
}
?>
