<?php
/**
 * @file        API_getTotalAgentsCall.php
 * @brief       Displays the total number of talking agents
 * @copyright   Copyright (c) 2020 GOautodial Inc.
 * @author		Demian Lizandro A. Biscocho 
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

	require_once('APIHandler.php');
	
	$api 										= \creamy\APIHandler::getInstance();
	$output 									= $api->API_getTotalAgentsCall();        
    $agent 										= $output->data;
        
    if (empty($agent) || is_null($agent)){
        $agent									= 0;
    }
        
    echo json_encode($agent); 
    
?>
