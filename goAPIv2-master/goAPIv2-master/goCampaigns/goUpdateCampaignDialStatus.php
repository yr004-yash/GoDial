<?php
/**
 * @file    	goUpdateCampaignDialStatus.php
 * @brief     	API to update campaign dial status
 * @copyright   Copyright (c) 2019 GOautodial Inc.
 * @author      Noel Umandap
 * @author      Alexander Jim Abenoja
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

	include_once ( "goAPI.php" );
	
    $campaigns 											= allowed_campaigns( $log_group, $goDB, $astDB );	  
    $campaign_id  										= $astDB->escape( $_REQUEST['campaign_id'] );
    $dial_statuses  									= $astDB->escape( $_REQUEST['dial_statuses'] );
    
    // Check campaign_id if its null or empty
	if (empty ($goUser) || is_null ($goUser)) {
		$apiresults 									= array(
			"result" 										=> "Error: goAPI User Not Defined."
		);
	} elseif (empty ($goPass) || is_null ($goPass)) {
		$apiresults 									= array(
			"result" 										=> "Error: goAPI Password Not Defined."
		);
	} elseif (empty ($log_user) || is_null ($log_user)) {
		$apiresults 									= array(
			"result" 										=> "Error: Session User Not Defined."
		);
	} elseif ( empty($campaign_id) || is_null($campaign_id) ) {
		$err_msg 										= error_handle("40001");
        $apiresults 									= array(
			"code" 											=> "40001",
			"result" 										=> $err_msg
		);
    } elseif ( in_array( $campaign_id, $campaigns ) ) {
		// check if goUser and goPass are valid
		$fresults										= $astDB
			->where("user", $goUser)
			->where("pass_hash", $goPass)
			->getOne("vicidial_users", "user,user_level");
		
		$goapiaccess									= $astDB->getRowCount();
		$userlevel										= $fresults["user_level"];
		
		if ($goapiaccess > 0 && $userlevel > 7) {    
			$astDB->where( 'campaign_id', $campaign_id );
			$astDB->update( 'vicidial_campaigns', array( 'dial_statuses' => $dial_statuses ) );

			$log_id 									= log_action( $goDB, 'MODIFY', $log_user, $log_ip, "Updated Dial Statuses for Campaign ID: $campaign_id", $log_group, $astDB->getLastQuery() );
			
			$apiresults 								= array(
				"result" 									=> "success"
			);
		} else {
			$err_msg 									= error_handle("10001");
			$apiresults 								= array(
				"code" 										=> "10001", 
				"result" 									=> $err_msg
			);		
		}		
	} else {
		$err_msg 										= error_handle( "10001", "Insufficient permision" );
		$apiresults 									= array(
			"code" 											=> "10001", 
			"result" 										=> $err_msg
		);			
	}
    
?>
