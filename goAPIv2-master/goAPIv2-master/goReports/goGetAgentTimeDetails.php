<?php
/**
 * @file        goGetAgentTimeDetails.php
 * @brief       API for Agent Time Details Reports
 * @copyright   Copyright (c) 2020 GOautodial Inc.
 * @author		Demian Lizandro A. Biscocho
 * @author      Alexander Jim Abenoja 
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
	
	$pageTitle 										= strtolower($astDB->escape($_REQUEST['pageTitle']));
	$fromDate 										= (empty($_REQUEST['fromDate']) ? date("Y-m-d")." 00:00:00" : $astDB->escape($_REQUEST['fromDate']));
	$toDate 										= (empty($_REQUEST['toDate']) ? date("Y-m-d")." 23:59:59" : $astDB->escape($_REQUEST['toDate']));
	$campaign_id 									= $astDB->escape($_REQUEST['campaignID']);
	$request 										= $astDB->escape($_REQUEST['request']);
	$limit											= 1000;
	$defPage 										= "agent_detail";

    // Error Checking
	if (empty($goUser) || is_null($goUser)) {
		$apiresults 								= array(
			"result" 									=> "Error: goAPI User Not Defined."
		);
	} elseif (empty($goPass) || is_null($goPass)) {
		$apiresults 								= array(
			"result" 									=> "Error: goAPI Password Not Defined."
		);
	} elseif (empty($log_user) || is_null($log_user)) {
		$apiresults 								= array(
			"result" 									=> "Error: Session User Not Defined."
		);
	} elseif (empty($campaign_id) || is_null($campaign_id)) {
		$err_msg 									= error_handle("40001");
        $apiresults 								= array(
			"code" 										=> "40001",
			"result" 									=> $err_msg
		);
	} else {            
		// check if goUser and goPass are valid
		$fresults 									= $astDB
			->where("user", $goUser)
			->where("pass_hash", $goPass)
			->getOne("vicidial_users", "user,user_level");
		
		$goapiaccess 								= $astDB->getRowCount();
		$userlevel 									= $fresults["user_level"];
		
		if ($goapiaccess > 0 && $userlevel > 7) {
			// Agent Time Detail
			if ($pageTitle == "agent_detail") {
				// set tenant value to 1 if tenant - saves on calling the checkIfTenantf function
				// every time we need to filter out requests
				//$tenant	= (checkIfTenant($log_group, $goDB)) ? 1 : 0;
                $tenant     = ($userlevel < 9 && $log_group !== "ADMIN") ? 1 : 0;
				
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
				
				if ($tenant) {
					$astDB->where("user_group", $log_group);
				} else {
					if (strtoupper($log_group) != 'ADMIN') {
						if ($userlevel < 9) {
							$astDB->where("user_group", $log_group);
						}
					}					
				}
				
				$TOTtimeTC = array();
				
				$timeclock_ct = $astDB
					->where("event", array("LOGIN", "START"), "IN")
					->where("date_format(event_date, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
					->groupBy("user")
					->get("vicidial_timeclock_log", "user, SUM(login_sec) as login_sec");
				
				if ($astDB->count > 0) {
					foreach ($timeclock_ct as $row) {
						$TCuser 					= $row['user'];
						$TCtime 					= $row['login_sec'];
						
						array_push($TOTtimeTC, $TCtime);
					}
				}
				
				$sub_statuses 						= '-';
				$sub_statusesTXT 					= '';
				$sub_statusesHEAD 					= '';
				$sub_statusesHTML 					= '';
				$sub_statusesFILE 					= '';
				$sub_statusesTOP 					= array();
				$sub_statusesARY 					= array();
				
				$PCusers 							= '-';
				$PCuser_namesARY					= array();
				$PCusersARY 						= array();
				$PCpause_secsARY					= array();
				
				if ($tenant) {
					$astDB->where("user_group", $log_group);
				} else {
					if (strtoupper($log_group) != 'ADMIN') {
						if ($user_level < 9) {
							$astDB->where("user_group", $log_group);
						}
					}					
				}
				
				if ("ALL" === strtoupper($campaign_id)) {
                    			$SELECTQuery = $astDB->get("vicidial_campaigns", NULL, "campaign_id");
                    			
					foreach($SELECTQuery as $camp_val){
                        			$array_camp[] = $camp_val["campaign_id"];
                    			}
					
                		}else{
                			$array_camp[] = $campaign_id;
                		}
				//$imploded_camp = "'".implode("','", $array_camp)."'";
	
				$cols = array(
					"vu.full_name",
					"val.user",
					"SUM(pause_sec) as pause_sec",
					"sub_status"
				);
				
				$pcs_data = $astDB
					->join("vicidial_users as vu", "val.user = vu.user", "LEFT")
					->where("date_format(event_time, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
					//->where("pause_sec", 0, ">")
					//->where("pause_sec", 65000, "<")
                    //->where("pause_sec", array(0, 65000), "BETWEEN")
					->where("campaign_id", $array_camp, "IN")
					->where("sub_status", array("LAGGED", "LOGIN"), "NOT IN")
					->groupBy("vu.user,sub_status")
					->orderBy("vu.user,sub_status")
					->get("vicidial_agent_log as val", $limit, $cols);
		
				if ($astDB->count > 0) {
					foreach ($pcs_data as $pc_data) {					
						$PCfull_name[]	= $pc_data['full_name'];
						$PCuser[] 	= $pc_data['user'];
						$PCpause_sec[] 	= $pc_data['pause_sec'];
						$sub_status[] 	= $pc_data['sub_status'];
					}
					
					$Boutput = array(
						"full_name" 	=> $PCfull_name, 
						"user" 		=> $PCuser, 
						"pause_sec" 	=> $PCpause_sec,
						"sub_status"	=> $sub_status
					);
					
					$SUMstatuses = array_sum($PCpause_secsARY);
					
					$BoutputFile = array(
						"statuses" => $PCpause_secsARY
					);				
				}
				
				if ($tenant) {
					$astDB->where("user_group", $log_group);
				} else {
					if (strtoupper($log_group) != 'ADMIN') {
						if ($user_level < 9) {
							$astDB->where("user_group", $log_group);
						}
					}					
				}
				
				/*$cols = array(
					"vu.full_name",
					"val.user",
					"sum(wait_sec) as wait_sec",
					"sum(talk_sec) as talk_sec",
					"sum(dispo_sec) as dispo_sec",
					"sum(pause_sec) as pause_sec_tot",
					"count(lead_id) as calls",
					"status",
					"sum(dead_sec) as dead_sec",
					"(sum(talk_sec) - sum(dead_sec)) as customer"
				);
				
				$agenttd = $astDB
					->join("vicidial_users vu", "val.user = vu.user", "LEFT")
					->where("date_format(event_time, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
					->where("campaign_id", $array_camp, "IN")
					//->where("pause_sec", array(0, 65000), "BETWEEN")
					->where("status", array('NULL', 'LAGGED'), "NOT IN")
					->groupBy("val.user")
					->get("vicidial_agent_log val", $limit, $cols);
				$query_td = $astDB->getLastQuery();		
				$usercount = $astDB->getRowCount();
				*/

				$cols = array(
				      "vu.full_name",
			              "val.user",
			              "SUM(wait_sec) as wait_sec",
			              "SUM(talk_sec) as talk_sec",
			              "SUM(dispo_sec) as dispo_sec",
			              "SUM(pause_sec) as pause_sec",
			              "status",
			              "SUM(dead_sec) as dead_sec",
				);
        
				
			        $agenttd = $astDB
			            ->join("vicidial_users vu", "val.user = vu.user", "LEFT")
			            ->where("date_format(event_time, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
			            ->where("campaign_id", $array_camp, "IN")
			            //->where("status != 'LAGGED'")
				    ->where("(pause_sec > 0 OR wait_sec > 0 OR talk_sec > 0 OR dispo_sec > 0 OR dead_sec > 0)")
				    ->groupBy("user")
				    ->orderBy("user", "DESC")
			            ->get("vicidial_agent_log val", 10000000, $cols);
           			
				//$agenttd = $astDB->rawQuery(""); 
				$query_td = $astDB->getLastQuery();
			        $usercount = $astDB->getRowCount();
				
				/*
				$agenttotalcalls = $astDB
					->where("date_format(vl.call_date, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
					->where("campaign_id", $array_camp, "IN")
					->where("vu.user = vl.user")
					->groupBy("vl.user")
					->get("vicidial_users vu, vicidial_log vl", $limit, "vl.user, count(vl.lead_id) as calls");
				*/
				$agenttotalcalls = $astDB
					->join("vicidial_users vu", "val.user = vu.user", "LEFT")
                                        ->where("date_format(event_time, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
                                        ->where("campaign_id", $array_camp, "IN")
					//->where("sub_status != 'LAGGED'")
                                	->where("(pause_sec > 0 OR wait_sec > 0 OR talk_sec > 0 OR dispo_sec > 0 OR dead_sec > 0)")
				        ->groupBy("user")
                                        ->get("vicidial_agent_log val", $limit, "val.user, count(val.lead_id) as calls");
				$query_ttc = $astDB->getLastQuery();
	
				if ($astDB->count >0) {	
					/*$TOTwait 	= array();
					$TOTtalk 	= array();
					$TOTdispo 	= array();
					$TOTpause 	= array();
					$TOTdead 	= array();
					$TOTcustomer 	= array();
					$TOTALtime 	= array();
					$TOT_AGENTS	= $usercount;
					$TOTcalls	= array();
					
					$nameARY	= array();
					$userARY	= array();
					$wait_secARY	= array();
					$talk_secARY	= array();
					$dispo_secARY	= array();
					$pause_secARY	= array();
					$dead_secARY	= array();
					$customerARY	= array();
					$agent_timeARY	= array();
					$callsARY	= array();
					
					foreach ($agenttd as $row) {
						$name		= $row['full_name'];
						$user		= $row['user'];

						foreach ($agenttotalcalls as $call){
							if($call['user'] == $user){
									$calls = $call['calls'];
							}
						}

						$wait		= convert($row['wait_sec']);
						$talk		= convert($row['talk_sec']);
						$dispo		= convert($row['dispo_sec']);
						$pause		= convert($row['pause_sec_tot']);
						//$calls	 	= $row['calls'];
						$status 	= $row['status'];
						$dead_sec	= convert($row['dead_sec']);
						$customer	= convert($row['customer']);
						$time		= convert(($row['wait_sec'] + $row['talk_sec'] + $row['dispo_sec'] + $row['pause_sec_tot']));
						//$time		= $time;
						
						array_push($userARY, $user);
						array_push($wait_secARY, $wait);
						array_push($talk_secARY, $talk);
						array_push($dispo_secARY, $dispo);
						array_push($pause_secARY, $pause);
						array_push($dead_secARY, $dead_sec);
						array_push($customerARY, $customer);
						array_push($agent_timeARY, $time);
						array_push($callsARY, $calls);

						$totwait_sec           = $row['wait_sec'];
                                                $tottalk_sec           = $row['talk_sec'];
                                                $totdispo_sec          = $row['dispo_sec'];
                                                $totpause_sec          = $row['pause_sec_tot'];
                                                $totdead_sec	       = $row['dead_sec'];
                                                $totcustomer_sec       = $row['customer'];
                                                $tottime_sec           = ($totwait_sec + $tottalk_sec + $totdispo_sec + $totpause_sec);
						
						array_push($TOTwait, $totwait_sec);
                                                array_push($TOTtalk, $tottalk_sec);
                                                array_push($TOTdispo, $totdispo_sec);
                                                array_push($TOTpause, $totpause_sec);
                                                array_push($TOTdead, $totdead_sec);
                                                array_push($TOTcustomer, $totcustomer_sec);
                                                array_push($TOTALtime, $tottime_sec);
                                                array_push($TOTcalls, $calls);

					}
					
					$TOTwait 	= convert(array_sum($TOTwait));
					$TOTtalk 	= convert(array_sum($TOTtalk));
					$TOTdispo 	= convert(array_sum($TOTdispo));
					$TOTpause 	= convert(array_sum($TOTpause));
					$TOTdead 	= convert(array_sum($TOTdead));
					$TOTcustomer 	= convert(array_sum($TOTcustomer));
					$TOTALtime 	= convert(array_sum($TOTALtime));
					$TOTtimeTC 	= convert(array_sum($TOTtimeTC));
					$TOT_AGENTS 	= 'AGENTS: '.$usercount;
					$TOTcalls	= array_sum($TOTcalls);
					*/

			    $nameARY	= array();
                            $userARY	= array();
                            $wait_secARY	= array();
                            $talk_secARY	= array();
                            $dispo_secARY	= array();
                            $pause_secARY	= array();
                            $dead_secARY	= array();
                            $customerARY	= array();
                            $agent_timeARY	= array();
                            $callsARY	= array();

                            $i=0;

                            foreach ($agenttd as $row) {
                                $user		= $row['user'];
                                $name		= $row['full_name'];
                       
				if(!in_array($user, $userARY) && isset($name)){
					array_push($userARY, $user);
					array_push($nameARY, $name);
					foreach ($agenttotalcalls as $call){
                                           if($call['user'] == $user){
                                           	array_push($callsARY, $call['calls']);
                                           }
                                    	}	
				}
 
                                $wait = $row['wait_sec'];
                                $talk = $row['talk_sec'];
                                $dispo = $row['dispo_sec'];
                                $pause = $row['pause_sec'];
                                $dead = $row['dead_sec'];
                                $customer = $row['talk_sec'] - $row['dead_sec'];
			
                                //if ($wait > 65000) {$wait=0;}
				if ($wait > (strtotime($toDate) - strtotime($fromDate))) {$wait = 0;}
                                //if ($talk > 65000) {$talk=0;}
				if ($talk > (strtotime($toDate) - strtotime($fromDate))) {$talk = 0;}
                                //if ($dispo > 65000) {$dispo=0;}
				if ($dispo > (strtotime($toDate) - strtotime($fromDate))) {$dispo = 0;}
                                //if ($pause > 65000) {$pause=0;}
				if ($pause > (strtotime($toDate) - strtotime($fromDate))) {$pause = 0;}
                                //if ($dead > 65000) {$dead=0;}
				if ($dead > (strtotime($toDate) - strtotime($fromDate))) {$dead = 0;}			
	
                                if ($customer < 1) {$customer=0;}

                                $TOTwait =      ($TOTwait + $wait);
                                $TOTtalk =      ($TOTtalk + $talk);
                                $TOTdispo =     ($TOTdispo + $dispo);
                                $TOTpause =     ($TOTpause + $pause);
                                $TOTdead =      ($TOTdead + $dead);
                                $TOTcustomer =  ($TOTcustomer + $customer);
                                $TOTALtime = ($TOTALtime + $pause + $dispo + $talk + $wait);
                                
                                $user_found=0;

                                $m=0;
				foreach($userARY as $users){
                                    if ($user == $userARY[$m]){
                                        $user_found++;

                                        $wait_secARY[$m] =    ($wait_secARY[$m] + $wait);
                                        $talk_secARY[$m] =    ($talk_secARY[$m] + $talk);
                                        $dispo_secARY[$m] =   ($dispo_secARY[$m] + $dispo);
                                        $pause_secARY[$m] =   ($pause_secARY[$m] + $pause);
                                        $dead_secARY[$m] =    ($dead_secARY[$m] + $dead);
                                        $customerARY[$m] =    ($customerARY[$m] + $customer);
					$agent_timeARY[$m] =  ($agent_timeARY[$m] + $wait + $talk + $dispo + $pause);
                                    }
                                    $m++;
                                }

                                $i++;
                            } //end of while
					$d_fromDate = strtotime($fromDate);
					$d_toDate = strtotime($toDate);
					$difference = $d_toDate - $d_fromDate;
					//$no_days = $difference->days;

					$j=0;
					foreach($userARY as $users){
						if($agent_timeARY[$j] >= $difference){
							$pause_secARY[$j] = ($difference - $wait_secARY[$j] - $talk_secARY[$j] - $dispo_secARY[$j]);
							$agent_timeARY[$j] = ($pause_secARY[$j] + $wait_secARY[$j] + $talk_secARY[$j] + $dispo_secARY[$j]);
						}
						$j++;
					}

					$TOTwait        = convert(array_sum($wait_secARY));
                                        $TOTtalk        = convert(array_sum($talk_secARY));
                                        $TOTdispo       = convert(array_sum($dispo_secARY));
                                        $TOTpause       = convert(array_sum($pause_secARY));
                                        $TOTdead        = convert(array_sum($dead_secARY));
                                        $TOTcustomer    = convert(array_sum($customerARY));
                                        $TOTALtime      = convert(array_sum($agent_timeARY));
                                        $TOTtimeTC      = convert(array_sum($TOTtimeTC));
                                        $TOT_AGENTS     = 'AGENTS: '.count($userARY);
                                        $TOTcalls       = array_sum($callsARY);

					$j = 0;
					foreach($userARY as $users){
						$wait_secARY[$j] = convert($wait_secARY[$j]);
                                                $talk_secARY[$j] = convert($talk_secARY[$j]);
                                                $dispo_secARY[$j] = convert($dispo_secARY[$j]);
                                                $pause_secARY[$j] = convert($pause_secARY[$j]);
                                                $dead_secARY[$j] = convert($dead_secARY[$j]);
                                                $customerARY[$j] = convert($customerARY[$j]);
                                                $agent_timeARY[$j] = convert($agent_timeARY[$j]);
						$j++;
					}
					
				}
						
				// Check if the user had an AUTOLOGOUT timeclock event during the time period
				$TCuserAUTOLOGOUT = ' ';
								
				$timeclock_ct = $astDB						
					->where("event", "AUTOLOGOUT")
					->where("user", $user)
					->where("date_format(event_date, '%Y-%m-%d %H:%i:%s')", array($fromDate, $toDate), "BETWEEN")
					->getValue("vicidial_timeclock_log", "count(*)");
					
				if ($timeclock_ct > 0) {
					$TCuserAUTOLOGOUT = '*';
				}				

				$Toutput = array(
					"name" 			=> $nameARY, 
					"user" 			=> $userARY, 
					"number_of_calls" 	=> $callsARY, 
					"agent_time" 		=> $agent_timeARY, 
					"wait_time" 		=> $wait_secARY, 
					"talk_time" 		=> $talk_secARY, 
					"dispo_time" 		=> $dispo_secARY, 
					"pause_time" 		=> $pause_secARY, 
					"wrap_up" 		=> $dead_secARY, 
					"customer_time" 	=> $customerARY,
					//"usercount" 		=> $usercount,
					"no_days"		=> $difference
				);						

				$TOPsorted_output = $Toutput;
		
				if (!preg_match("/NAME|ID|TIME|LEADS|TCLOCK/",$stage)) {
					if ($file_download > 0) {
						$file_output .= "$fileToutput";
					}
				}
				
				if ($TOPsortMAX < $TOPsortTALLY) {
					$TOPsortMAX = $TOPsortTALLY;
				}
				

			//------ MIDDLE TABLE -------
				/*$usersARY[0] = "";
				$statusesARY[0] = "";
				$user_namesARY[0] = "";

				if ($log_group !== "ADMIN") {
                                        $log_groupSQL = "AND vicidial_users.user_group='$log_group'";
                                }

				$perfdetails_sql = "SELECT count(*) as calls,full_name,vicidial_users.user as user,status FROM vicidial_users,vicidial_agent_log WHERE date_format(event_time, '%Y-%m-%d %H:%i:%s') BETWEEN '$fromDate' AND '$toDate' AND vicidial_users.user=vicidial_agent_log.user $log_groupSQL AND campaign_id = '$campaign_id' GROUP BY user,full_name,status order by full_name,user,status desc limit 500000";
				$rows_to_print = $astDB->rawQuery($perfdetails_sql);
						
				$i = 0; $j = 0;
				while ($row = $astDB->rawQuery($perfdetails_sql, MYSQLI_ASSOC)) {
					/*$calls[$i]        = $row['calls'];
                                        $full_name[$i]    = $row['full_name'];
                                        $user[$i]         = $row['user'];
                                        $status[$i]       = $row['status'];
						
                                        if ( (!preg_match("/-$status[$i]-/", $statuses)) AND (strlen($status[$i])>0) ) {
                                                $statuses     .= "$status[$i]-";
                                                $SUMstatuses  .= "$status[$i] ";
                                                $statusesARY[$j] = $status[$i];

                                                ## getting status name
                                                $var_status = $status[$i];

                                                # in default statuses
                                                $query = "
                                                    SELECT status_name FROM vicidial_statuses
                                                    WHERE status = '$var_status' LIMIT 1;
                                                ";
                                                if ($query) {
                                                    $fetch_statusname = $astDB->rawQuery($query);
                                                }

                                                if (!isset($fetch_statusname) || $fetch_statusname == NULL) {
                                                    # in custom statuses
                                                    $query = "
                                                        SELECT status_name FROM vicidial_campaign_statuses
                                                        WHERE status = '$var_status' LIMIT 1;
                                                    ";
                                                    $fetch_statusname = $astDB->rawQuery($query);
                                                }

                                                $legend[] = $status[$i]." = ".$fetch_statusname['status_name'];

                                                ## end of getting status name
                                                $SstatusesTOP .= "<th> $status[$i] </th>";
                                                $j++;
					}

                                        if (!preg_match("/-$user[$i]-/", $users)) {
                                                $users              .= "$user[$i]-";
                                                $usersARY[$k]       = $user[$i];
                                                $user_namesARY[$k]  = $full_name[$i];
                                                $k++;
                                        }
					
                                        $i++;
				}// end while
				/*
				 // BEGIN loop through each user //
                                $m = 0; $k = 0;
                                while ($m < $k) {
                                        $Suser          = $usersARY[$m];
                                        $Sfull_name     = $user_namesARY[$m];
					$Scalls         = 0;

                                        // BEGIN loop through each status //
                                        $n = 0;
                                        while ($n < $j) {
                                                $Sstatus = $statusesARY[$n];
                                                // BEGIN loop through each stat line //
                                                $i = 0;
                                                $status_found = 0;
                                                     while ($i < $perfdetails_sql) {
                                                        if ( ($Suser=="$user[$i]") AND ($Sstatus == "$status[$i]") ) {
                                                            $SstatusesMID[$m] .= "<td> $calls[$i] </td>";
                                                            $status_found++;
                                                        }
                                                        $i++;
                                                     }
                                                // END loop through each stat line //
                                                $n++;
                                        }
					// END loop through each status //

					$Moutput = "<tr>
                                                <td> $Sfull_name </td>
                                                $SstatusesMID[$m]
                                                </tr>";
					$MIDsorted_output[$m] = $Moutput;

					$m++			
				}*/

				$apiresults = array(
					"result" 		=> "success", 
					"TOPsorted_output" 	=> $TOPsorted_output, 
					"PC_statuses"		=> $pcs_data, //Pause Code data
					"TOTwait" 		=> $TOTwait, 
					"TOTtalk" 		=> $TOTtalk, 
					"TOTdispo" 		=> $TOTdispo, 
					"TOTpause" 		=> $TOTpause, 
					"TOTdead" 		=> $TOTdead, 
					"TOTcustomer" 		=> $TOTcustomer, 
					"TOTALtime" 		=> $TOTALtime, 
					"TOTtimeTC" 		=> $TOTtimeTC, 
					"TOT_AGENTS" 		=> $TOT_AGENTS, 
					"TOTcalls" 		=> $TOTcalls,
					"campaigns"		=> $array_camp,
					"query" => $query_td,
					"QUERY2" => $query_ttc
					//"SstatusesBSUM"         => $SstatusesBSUM,
					//"MIDsorted_output"	=> $MIDsorted_output,
					//"legend"		=> $legend
				);
				
				return $apiresults;				
			}
		} else {
			$err_msg 									= error_handle("10001");
			$apiresults 								= array(
				"code" 										=> "10001", 
				"result" 									=> $err_msg
			);		
		}
	}

?>
