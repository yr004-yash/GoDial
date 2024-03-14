<?php
 /**
 * @file 		goGetTotalActiveLeads.php
 * @brief 		API for Dashboard
 * @copyright 	Copyright (C) GOautodial Inc.
 * @author     	Jeremiah Sebastian Samatra  <jeremiah@goautodial.com>
 * @author     	Chris Lomuntad  <chris@goautodial.com>
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

    $groupId = go_get_groupid($session_user, $astDB);
    
    if (checkIfTenant($groupId, $goDB)) {
        $ul='';
    } else { 
        $stringv = go_getall_allowed_campaigns($groupId, $astDB);
		if($stringv !== "'ALLCAMPAIGNS'")
			$ul = " and vls.campaign_id IN ($stringv)";
		else
			$ul = "";
    }
	
    $query = "SELECT count(*) as getTotalActiveLeads from vicidial_lists as vls,vicidial_list as vl where vl.list_id=vls.list_id and active='Y' $ul"; 
    $fresults = $astDB->rawQuery($query);
    //$fresults = mysqli_fetch_assoc($rsltv);
    $apiresults = array_merge( array( "result" => "success" ), $fresults );
?>
