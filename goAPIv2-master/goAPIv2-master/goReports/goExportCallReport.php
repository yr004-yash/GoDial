<?php
/**
 * @file        goExportCallReport.php
 * @brief       API for Exporting Call Reports
 * @copyright   Copyright (c) 2020 GOautodial Inc.
 * @author		Demian Lizandro A. Biscocho
 * @author      Alexander Jim Abenoja 
 * @author      Thom Bernarth Patacsil
 *
 * @par <b>License</b>:
 *  This program is free software: you can redistribute it AND/or modify
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
    
	include_once("goAPI.php");
	
	$campaigns 		= $astDB->escape($_REQUEST['campaigns']);
	$inbounds 		= $astDB->escape($_REQUEST['inbounds']);
	$lists 			= $astDB->escape($_REQUEST['lists']);
	$dispo_stats            = $astDB->escape($_REQUEST['statuses']);
	$custom_fields 	= $astDB->escape($_REQUEST['custom_fields']);
	$per_call_notes = $astDB->escape($_REQUEST['per_call_notes']);
	$rec_location 	= $astDB->escape($_REQUEST['rec_location']);
	$log_group 		= go_get_groupid($session_user, $astDB);
	$fromDate = $astDB->escape($_REQUEST['fromDate']);        
	$toDate = $astDB->escape($_REQUEST['toDate']);
	
	$limit = $astDB->escape($_REQUEST['limit']);
	$offset = $astDB->escape($_REQUEST['offset']);

	if (empty($fromDate))
		$fromDate = date("Y-m-d")." 00:00:00";
	if (empty($toDate)) 
		$toDate = date("Y-m-d")." 23:59:59";
        
	if (!empty($campaigns) && $campaigns != NULL)
	    $campaigns = explode(",",$campaigns);
	if (!empty($inbounds))
	    $inbounds = explode(",",$inbounds);
	if (!empty($lists))	
	    $lists = explode(",",$lists);
	if (!empty($dispo_stats))	
	    $dispo_stats = explode(",",$dispo_stats);
	
	if($limit != NULL && $offset != NULL){
		$limit_SQL = "LIMIT $offset, $limit";
	} else {
		$limit_SQL = "";
	}

	$campaign_SQL = "";
	$group_SQL = "";
	$list_SQL = "";
	$status_SQL = "";

	$campaign_ct = count($campaigns);
	$group_ct = count($inbounds);
	$list_ct = count($lists);
	$status_ct = count($dispo_stats);

	$csv_row = "";

	// check if MariaDB slave server available
	$rslt								= $goDB
	->where('setting', 'slave_db_ip')
	->where('context', 'creamy')
	->getOne('settings', 'value');
	$slaveDBip 							= $rslt['value'];

	if (!empty($slaveDBip)) {
		$astDB = new MySQLiDB($slaveDBip, $VARDB_user, $VARDB_pass, $VARDB_database);

		if (!$astDB) {
			echo "Error: Unable to connect to MariaDB slave server." . PHP_EOL;
			echo "Debugging Error: " . $astDB->getLastError() . PHP_EOL;
			exit;
			//die('MySQL connect ERROR: ' . mysqli_error('mysqli'));
		}			
	}

	if (!empty($campaigns)) {
		$fresults = $astDB
			->where("user", $goUser)
			->where("pass_hash", $goPass)
			->getOne("vicidial_users", "user,user_level");
		
		$goapiaccess = $astDB->getRowCount();
		$userlevel = $fresults["user_level"];

		$i = 0;
		//$array_campaign = Array();

		while ($i < $campaign_ct) {
			//$campaign_SQL .= "'$campaigns[$i]',";
			$campaign_SQL .= "'$campaigns[$i]',";
			//array_push($array_campaign, $campaigns[$i]);
			$i++;
		}
		
		if (in_array("ALL", $campaigns)) {
			$campaign_SQL = "";
			$i = 0;
			$SELECTQuery = $astDB->get("vicidial_campaigns", NULL, "campaign_id");
			$campaign_ct = $astDB->count;
			foreach($SELECTQuery as $camp_val){
				$array_camp[] = $camp_val["campaign_id"];
			}
			$imp_camp = implode("','", $array_camp);
			if (strtoupper($log_group) !== 'ADMIN') {
				if ($log_group !== 'SUPERVISOR') {
					$campaign_SQL = "AND vl.campaign_id IN('$imp_camp')";
				}
			}
			//die("ALEX");	
                }else{
			$campaign_SQL = preg_replace("/,$/i",'',$campaign_SQL);
			$campaign_SQL = "AND vl.campaign_id IN($campaign_SQL)";
		}

		$RUNcampaign = 1;
		
	} else {
		$RUNcampaign = 0;
	}
	
	if (!empty($inbounds)) {
		$i=0;
		if (in_array("ALL", $inbounds)) {
			$group_SQL = go_getall_closer_campaigns("ALL", $astDB);
			$i=1;
		} else {
			$i = 0;
			//$array_inbound 							= Array();

			while ($i < $group_ct) {
				if (strlen($inbounds[$i]) > 0) {
				  //$group_SQL .= "'$inbounds[$i]',";
					$group_SQL .= "'$inbounds[$i]',";
					//array_push($array_inbound, $inbounds[$i]);
				}
				$i++;
			}
		
			$group_SQL 								= preg_replace("/,$/i",'',$group_SQL);
		}
		if ($group_ct > 0) {
			$group_SQL 							= "AND vcl.campaign_id IN($group_SQL)";
		}
		
		$RUNgroup								=$i;
	} else {
		$RUNgroup								= 0;
	}
	
	if (!empty($lists)) {
		//$list_SQL 								= "";
		$list_SQL								= implode("','", $lists);
		
		//$i										= 0;
		//$array_list 							= Array();
		//while ($i < $list_ct) {
		//	//$list_SQL .= "'$lists[$i]',";
		//	$list_SQL 							.= "'$lists[$i]',";
		//	//array_push($array_list, $lists[$i]);
		//	$i++;
		//}
		
		if (in_array("ALL", $lists)) {
			$list_SQL 							= "";
			
			if (in_array("ALL", $campaigns) || in_array('ALL', $inbounds)) {
				$SELECTQuery = $astDB->get("vicidial_lists", null, "list_id");
				$array_list = $SELECTQuery;
			} else {
				$i									= 0;
				while ($i < $campaign_ct) {
					$camp_id = $campaigns[$i];
					$astDB->WHERE("campaign_id", $camp_id);
					$SELECTQuery = $astDB->get("vicidial_lists", null, "list_id");
					//$query_list = mysqli_query($astDB,"SELECT list_id FROM vicidial_lists WHERE campaign_id = '$camp_id';");
					$array_list = $SELECTQuery;
					
					$i++;
				}
			}
		}
		else{
			//$list_SQL 							= preg_replace("/,$/i",'',$list_SQL);
			$list_SQL 							= "AND vi.list_id IN('$list_SQL')";
			$array_list							= $lists;
			//$i									= 0;
			//
			//while ($i < $list_ct) {
			//	$array_list[] 					= $lists[$i];
			//	$i++;
			//}
		}
	}
	
	if (!empty($dispo_stats)) {
		$i= 0;
		//$array_status 							= Array();

		while ($i < $status_ct) {
			//$status_SQL .= "'$dispo_stats[$i]',";
			$status_SQL 						.= "'$dispo_stats[$i]',";
			//array_push($array_status, $dispo_stats[$i]);
			$i++;
		}
		
		if ( (in_array("ALL", $dispo_stats)) ) {
			$status_SQL 						= "";
		} else {
			$status_SQL 						= preg_replace("/,$/i",'',$status_SQL);
			$status_SQL_vl 						= "AND vl.status IN ($status_SQL)";
			$status_SQL_vcl						= "AND vcl.status IN ($status_SQL)";

		}
	}
	
	if ($log_group !== "ADMIN") {
		if ($log_group !== 'SUPERVISOR') {
			$stringv 								= go_getall_allowed_users($log_group);
			$user_group_SQL 						= "AND vl.user IN ($stringv)";
		} else {
			$user_group_SQL                                                 = "";
		}
	}  else{
		$user_group_SQL 						= "";
	}
	
	$export_fields_SQL 							= "";
	
	if($rec_location === "Y"){
	$endepoch_sql = ",vl.end_epoch";
	$endepoch_sql2 = ",vcl.end_epoch";
	}else{
	$endepoch_sql = "";	
	$endepoch_sql2 = "";
	}
	$duration_sql = "vl.length_in_sec as call_duration, ";
        $duration_sql2 = "vcl.length_in_sec as call_duration, ";
	$location_sql = "";

	if ($RUNcampaign > 0 && $RUNgroup < 1) {
		$query = "SELECT vl.call_date, $duration_sql vl.phone_number,vl.status,vl.user,vu.full_name,vl.campaign_id,vi.vendor_lead_code,vi.source_id,vi.list_id,vi.gmt_offset_now,vi.phone_code,vi.title,vi.first_name,vi.middle_initial,vi.last_name,vi.address1,vi.address2,vi.address3,vi.city,vi.state,vi.province,vi.postal_code,vi.country_code,vi.gender,vi.date_of_birth,vi.alt_phone,vi.email,vi.security_phrase,vi.comments,vl.user_group,vl.alt_dial,vi.rank,vi.owner,vi.lead_id,vl.uniqueid,vi.entry_list_id $location_sql $endepoch_sql 
			FROM vicidial_users vu, vicidial_log vl,vicidial_list vi
			WHERE (date_format(vl.call_date, '%Y-%m-%d %H:%i:%s') BETWEEN '$fromDate' AND '$toDate') 
			AND vu.user=vl.user AND vi.lead_id=vl.lead_id
			# AND vl.length_in_sec > 0 
			$list_SQL $campaign_SQL 
			$user_group_SQL $status_SQL_vl 
			order by vl.call_date
			$limit_SQL";
	}
	
	if ($RUNgroup > 0 && $RUNcampaign < 1) {
		$query	= "SELECT vcl.call_date, $duration_sql2 vcl.phone_number,vcl.status,vcl.user,vu.full_name,vcl.campaign_id,vi.vendor_lead_code,vi.source_id,vi.list_id,vi.gmt_offset_now,vi.phone_code,vi.title,	vi.first_name,vi.middle_initial,vi.last_name,vi.address1,vi.address2,vi.address3,vi.city,vi.state,vi.province,vi.postal_code,vi.country_code,vi.gender,vi.date_of_birth,vi.alt_phone,vi.email,vi.security_phrase,vi.comments,vcl.user_group,vcl.queue_seconds,vi.rank,vi.owner,vi.lead_id,vcl.closecallid, vcl.uniqueid,vi.entry_list_id $location_sql $endepoch_sql2
			FROM vicidial_users vu, vicidial_closer_log vcl, vicidial_list vi
			WHERE (date_format(vcl.call_date, '%Y-%m-%d %H:%i:%s') BETWEEN '$fromDate' AND '$toDate') 
			AND vu.user=vcl.user AND vi.lead_id=vcl.lead_id
			#AND vcl.length_in_sec > 0
			$list_SQL $group_SQL 
			$user_group_SQL $status_SQL_vcl
			order by vcl.call_date
			$limit_SQL";
	}
	if ($RUNcampaign > 0 && $RUNgroup > 0) {
		$query = "(SELECT vl.call_date,
				$duration_sql
				vl.phone_number,
				vl.status,
				vl.user,
				vu.full_name,
				vl.campaign_id,
				vi.vendor_lead_code,
				vi.source_id,
				vi.list_id,
				vi.gmt_offset_now,
				vi.phone_code,
				vi.title,
				vi.first_name,
				vi.middle_initial,
				vi.last_name,
				vi.address1,
				vi.address2,
				vi.address3,
				vi.city,
				vi.state,
				vi.province,
				vi.postal_code,
				vi.country_code,
				vi.gender,
				vi.date_of_birth,
				vi.alt_phone,
				vi.email,
				vi.security_phrase,
				vi.comments,
				vl.user_group,
				vl.term_reason,
				vi.rank,
				vi.owner,
				vi.lead_id,
				vl.uniqueid, 
				vi.entry_list_id 
				$location_sql
				$endepoch_sql
				$export_fields_SQL 
			FROM vicidial_users vu, vicidial_log vl,vicidial_list vi
			WHERE (date_format(vl.call_date, '%Y-%m-%d %H:%i:%s') BETWEEN '$fromDate' AND '$toDate') 
			AND vu.user=vl.user AND vi.lead_id=vl.lead_id
			# AND vl.length_in_sec > 0
			$list_SQL 
			$campaign_SQL 
			$user_group_SQL 
			$status_SQL_vl 
			order by vl.call_date
		) UNION (
			SELECT vcl.call_date,
				$duration_sql2
				vcl.phone_number,
				vcl.status,
				vcl.user,
				vu.full_name,
				vcl.campaign_id,
				vi.vendor_lead_code,
				vi.source_id,
				vi.list_id,
				vi.gmt_offset_now,
				vi.phone_code,
				vi.title,
				vi.first_name,
				vi.middle_initial,
				vi.last_name,
				vi.address1,
				vi.address2,
				vi.address3,
				vi.city,
				vi.state,
				vi.province,
				vi.postal_code,
				vi.country_code,
				vi.gender,
				vi.date_of_birth,
				vi.alt_phone,
				vi.email,
				vi.security_phrase,
				vi.comments,
				vcl.user_group,
				vcl.term_reason,
				vi.rank,
				vi.owner,
				vi.lead_id, 
				vcl.closecallid, 
				vi.entry_list_id 
				$location_sql
				$endepoch_sql2
				$export_fields_SQL 
			FROM vicidial_users vu, vicidial_closer_log vcl,vicidial_list vi
			WHERE (date_format(vcl.call_date, '%Y-%m-%d %H:%i:%s') BETWEEN '$fromDate' AND '$toDate') 
			AND vu.user=vcl.user AND vi.lead_id=vcl.lead_id
			# AND vcl.length_in_sec > 0
			$list_SQL 
			$group_SQL 
			$user_group_SQL 
			$status_SQL_vcl
			order by vcl.call_date) 
			$limit_SQL;";
    }
	$result = $astDB->rawQuery($query);

	//$apiresults = array ( "QUERY" => $query, "EXECUTED LAST" => $astDB->getLastQuery(), "ANY DATA" => $result);

	// CONVERT RETURN OF rawQuery to Arrays
	$result = json_decode(json_encode($result), true);

	//OUTPUT DATA HEADER//
	$csv_header = array_keys($result[0]);

	//$apiresults = array ( "QUERY" => $query, "EXECUTED LAST" => $astDB->getLastQuery(), "ANY DATA" => $csv_header);
	
	if ($per_call_notes == "Y") {
		array_push($csv_header, "call_notes");
	}

	if ($rec_location == "Y") {
		array_push($csv_header, "recording_location");
		$ee_key = array_search("end_epoch",$csv_header);
		array_splice($csv_header, $ee_key, 1);
	}
	if ($custom_fields == "Y")	{
	//    for ($i = 0 ; $i < count($array_list); $i++) {
	//		$list_id = $array_list[$i];
		foreach ($array_list as $list) {
			$custom_list_id = "custom_" . (!empty($list['list_id']) ? $list['list_id'] : $list);
			//$query_CF_list = "DESC custom_$list_id;");
			$query_CF_list = $astDB->rawQuery("DESC {$custom_list_id};");
			if ($query_CF_list) {
				//$n = 0;
				//while ($field_list=$astDB->rawQuery($query_CF_list)) {
				foreach ($query_CF_list as $field_list) {
					$exec_query_CF_list = $field_list["Field"];

					if ($exec_query_CF_list != "lead_id") {
						$active_list_fields["$custom_list_id"][] = $exec_query_CF_list;
						//$n++;
					}
				}
			}
		}

		$header_CF 									= array();
		//$keys 										= array_keys($active_list_fields);
		
		//for ($i = 0; $i < count($keys); $i++) {
		foreach ($active_list_fields as $list_id => $fields) {
			//$list_id 								= $keys[$i];
			//for ($x = 0; $x < count($active_list_fields[$list_id]);$x++) {
			foreach ($fields as $field) {
				//$field 								= $active_list_fields[$list_id][$x];
				if (!in_array($field,$header_CF)) {
					$header_CF[] 					= $field;
				}
			}
		}
		
		$csv_header 								= array_merge($csv_header,$header_CF);
	}
	
	//OUTPUT DATA ROW//
	foreach ($result as $row) {
		$lead_id = $row["lead_id"];
		$uniqueid = $row["uniqueid"];
		$list_id_spec = $row["list_id"];
		$row["call_duration"] = gmdate("H:i:s",$row["call_duration"]);		

		if ($per_call_notes == "Y") {
			$astDB->WHERE("lead_id", $lead_id);
			$fetch_callnotes = $astDB->getOne("vicidial_call_notes", "call_notes");
			//$query_callnotes = "SELECT call_notes FROM vicidial_call_notes WHERE lead_id='$lead_id' LIMIT 1;");
			$notes_ct = $astDB->count;
			
			if ($notes_ct > 0) {
				$notes_data = $fetch_callnotes["call_notes"];
				$notes_data = rawurldecode($notes_data);
			} else {
				$notes_data = "";
			}
			$row["call_notes"] = $notes_data;
		}
		if ($rec_location == "Y") {
			$end_epoch = $row["end_epoch"];
			if(in_array($row["user"], array("VDAD", "VDCL"))){
				$rec_data = "";
			}else{
			//$recording_array = Array($lead_id);
			if (isset($uniqueid2) && !empty($uniqueid2)) {
				//$condition_SQL = "AND ((vicidial_id = '$uniqueid') OR (vicidial_id = '$uniqueid2')) ";
				$astDB->WHERE("vicidial_id", $uniqueid);
				$astDB->orWHERE("vicidial_id", $uniqueid2);
			} else {
				//$condition_SQL = "AND vicidial_id = '$uniqueid'";
				$astDB->WHERE("vicidial_id", $uniqueid);
			}
			$astDB->WHERE("lead_id", $lead_id);
			$fetch_recording = $astDB->getOne("recording_log", "location");	
			//$query_recordings = "SELECT location FROM recording_log WHERE lead_id='$lead_id' $condition_SQL LIMIT 1;");
			$rec_ct = $astDB->count;
			if ($rec_ct > 0) {
				$rec_data = $fetch_recording["location"];
				$rec_data = rawurldecode($rec_data);
			} else {
				// TRY 2nd using end_epoch
				$astDB->WHERE("end_epoch", $end_epoch);
				$astDB->WHERE("lead_id", $lead_id);
                        	$fetch_recording = $astDB->getOne("recording_log", "location");
				$rec_ct = $astDB->count;

                        	if ($rec_ct > 0) {
                                	$rec_data = $fetch_recording["location"];
                                	$rec_data = rawurldecode($rec_data);
                        	} else {
					// TRY 3rd if there's only 1 data with the lead_id
	                                $astDB->WHERE("length_in_sec", 0, ">");
        	                        $astDB->WHERE("lead_id", $lead_id);
                	                $fetch_recording = $astDB->getOne("recording_log", "location");
                        	        $rec_ct = $astDB->count;
					
					if ($rec_ct == 1) {
	                                        $rec_data = $fetch_recording["location"];
                                        	$rec_data = rawurldecode($rec_data);
                                	} else {
						$rec_data="";
					}
				}
			}
			}//.else/ automatically skip rec_location fetch if user is VDAD or VDCL 
			unset($row["end_epoch"]);
			$row["rec_location"] = $rec_data;
			
		}
		//$apiresults = array ( $row );

		// Replace special characters [,] with -
        	if (!empty($row["address1"])) {
            		$row["address1"] = preg_replace('/[,]+/', '-', trim($row["address1"]));
        	}
        	
        	if (!empty($row["address2"])) {
            		$row["address2"] = preg_replace('/[,]+/', '-', trim($row["address2"]));
        	}
       		
			if (!empty($row["address3"])) {
            		$row["address3"] = preg_replace('/[,]+/', '-', trim($row["address3"]));
        	}
        	if (!empty($row["city"])) {
				$row["city"] = preg_replace('/[,]+/', ' ', trim($row["city"]));
			}
			if (!empty($row["comments"])) {
				$row["comments"] = preg_replace('/[,]+/', '-', trim($row["comments"]));
			}
		if ($custom_fields == "Y")	{
			//$keys = array_keys($active_list_fields); // list of active custom lists
				
			//for ($i = 0 ; $i < count($keys); $i++) {
			foreach ($active_list_fields as $list_id => $fields) {
			    //$list_id = $keys[$i];
			    //$fields = implode(",", $active_list_fields[$list_id]);
				$fields = implode(",", $fields);
					
				if ("custom_".$list_id_spec === $list_id) {
					$astDB->WHERE("lead_id", $lead_id);
					$fetch_CF = $astDB->getOne($list_id, $fields);
	//				$test[] = $fetch_CF;
					
					//$query_row_sql = "SELECT $fields FROM $list_id WHERE lead_id ='$lead_id';";

					if ($fetch_CF) {
						//for ($x = 0;$x < count($header_CF);$x++) {
						foreach ($header_CF as $header) {
							//if (!empty($fetch_CF[$header_CF[$x]])) {
							if (!empty($fetch_CF[$header])) {
								//$fetch_row[] 		=  str_replace(",", " | ", $fetch_CF[$header_CF[$x]]);
								$row[$header] 		= str_replace(",", " | ", $fetch_CF[$header]);
							} else {
								//$fetch_row[] 		=  "";
								$row[$header]		= "";
							}
						}
					}
				}
				

				//for ($a=0;$a < count($fetch_row);$a++) {
				//	$row[$header_CF[$a]] = $fetch_row[$a];
				//}
				
				//$queries[] 							= $row;
				//unset($fetch_row);
				unset($fetch_CF);
		    }
		}

		$data_row = implode(',', $row);
		$csv_row .= $data_row . "\n";
	}
	//$apiresults = array ( $csv_row);

	// new
	// ----
	$campFilter = (strlen($campaigns) > 0) ? "Campaign(s): $campaigns" : "";
	$inbFilter = (strlen($inbounds) > 0) ? "Inbound Groups(s): $inbounds" : "";
	$listFilter = (strlen($lists) > 0) ? "List(s): $lists" : "";
	$log_id	= log_action($goDB, 'DOWNLOAD', $log_user, $log_ip, "Exported Call Reports starting FROM $fromDate to $toDate using the following filters, $campFilter $inbFilter $listFilter", $log_group);
	
	$apiresults = array(
		"result" => "success", 
		"header" => $csv_header, 
		"rows" 	=> $csv_row,
		"query" => $query
	);
?>

