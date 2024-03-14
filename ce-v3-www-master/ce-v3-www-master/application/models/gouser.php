<?php
########################################################################################################
####  Name:             	gouser.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Franco Hora                                             	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Gouser extends Model {
     /*public variable*/
     var $heirarchy_container=array(); 
     var $ctr=0; 
     var $tempgroupcreated = null; # puts value after clicking create group contains the created id returns to null if save 


     function __construct(){
         parent::Model();
         $this->asteriskDB = $this->load->database('dialerdb',TRUE);
         $this->goautodialDB = $this->load->database('goautodialdb',TRUE);
         $this->lang->load("goUser_spanish","spanish");
     }


     /*
      * collectusers 
      * collect all data(users) from vicidial_users
      * display all stats active or not
      * @author : Franco Hora <info@goautodial.com>
      * @param : $account > use as filter if not null
      */
     function collectusers($account=null,$page=null,$search=null){

          $level = $this->session->userdata('users_level');
          $username = $this->session->userdata('user_name');
          $query = $this->asteriskDB->query("SELECT modify_same_user_level FROM vicidial_users WHERE user='$username'");
          $modify_same_level = $query->row()->modify_same_user_level;
          if(!is_null($account)){
              $limit = ($page=='ALL') ? 10000 : 25;
              $offset = (($page-1) * $limit);
              
              if ($modify_same_level) {
                  $levelSQL = "AND user_level <= '$level'";
              } else {
                  $levelSQL = "AND (user_level < '$level' OR (user_level = '$level' AND user = '$username'))";
              }
              
              if (!is_null($search) && strlen($search) > 0) {
                  $searchSQL = "AND (user RLIKE '$search' OR full_name RLIKE '$search')";
              }
              
              if ($this->commonhelper->checkIfTenant($account) & $level < 9) {
                  $userGroupSQL = "AND user_group = '$account'";
              }
              
              if ($account != 'ADMIN') {
                  $notAdminSQL = "AND user_group != 'ADMIN'";
              }

              $xstmt = "SELECT * FROM vicidial_users WHERE user NOT IN ('VDAD','VDCL') AND full_name NOT LIKE 'Survey%' $levelSQL $searchSQL $userGroupSQL $notAdminSQL";
              

              $stmt = "SELECT * FROM vicidial_users WHERE user NOT IN ('VDAD','VDCL') AND full_name NOT LIKE 'Survey%' $levelSQL $searchSQL $userGroupSQL $notAdminSQL ORDER BY user ASC LIMIT $offset,$limit";
              $users = $this->asteriskDB->query($stmt);
              $collectedusers = array(); 
              $ctr = 0;
              foreach($users->result() as $info){
                  $collectedusers[$ctr] = $info;
                  $ctr++;
              }
          }else{
              //$groups = $this->asteriskDB->get('vicidial_users');
              $collectedusers = array(); 
              $ctr = 0;
              //foreach($groups->result() as $group){
                    $this->asteriskDB->where('user !=','1000');
                    $this->asteriskDB->where('user !=','VDAD');
                    $this->asteriskDB->where("user !=","VDCL");
                    if (!is_null($search)) {
                        $this->asteriskDB->where("vicidial_users.user RLIKE",$search);
                    }
                    $this->asteriskDB->order_by('user_group');
                    $this->asteriskDB->order_by('user');
                    $users = $this->asteriskDB->get('vicidial_users');  
                    foreach($users->result() as $info){
                        $collectedusers[$ctr] = $info;
                        $ctr++;
                    }
              //}
          }
         
          return $collectedusers;
     }


     /*
      * getallusergroup 
      * collect all usergroup from vicidial_users_group
      * display all stats active or not
      * @author : Franco Hora <info@goautodial.com>
      * @param : $account > use as filter if not null
      */
     function getallusergroup($account=null){

         if(!is_null($account)){
              $usergroups = $this->asteriskDB->get_where('vicidial_user_groups','1');
              $collectedusergroups = array(); 
              $ctr = 0;
              foreach($usergroups->result() as $info){
                  $collectedusergroups[$ctr] = $info;
                  $ctr++;
              }            
         }else{
            $this->asteriskDB->select('user_group,group_name');
            $usergroups = $this->asteriskDB->get('vicidial_user_groups');
            //$usergroups = $this->asteriskDB->get('a2billing_wizard');
            $collectedusergroups = array(); 
            $ctr = 0;
            foreach($usergroups->result() as $info){
                $collectedusergroups[$ctr] = $info;
                $ctr++;
            }            
         }

         return $collectedusergroups;
     }    


     /*
      * insertuser 
      * insert data to tables
      * @author : Franco Hora <info@goautodial.com>
      * @param : $table > table to be use
      *          $data > data columns 
      */
     function insertuser($table=null,$data=null,$condition=null){
          $goSomethingWentWrongContactYourSupport = $this->lang->line('goUsers_somethingWentWrongContactYourSupport');
          if(!is_null($table) && !is_null($data) ){
               if($this->errortrapping($data)){
                   if($this->checkduplicate($table,array($condition))){
                       $result = $this->asteriskDB->insert($table,$data);
                       $this->commonhelper->auditadmin("ADD","Add new user(s) ".implode(",",$data));
                       return $result;
                   }
               }
          }else{
               die($goSomethingWentWrongContactYourSupport);
          }
     }

     /*
      * errortrapping 
      * check data to be inserted if allowed
      * @author : Franco Hora <info@goautodial.com>
      * @param : $data > data columns to be checked
      */
     function errortrapping($data){
           $goInvalidUserFormat = $this->lang->line('goUsers_invalidUserFormat');
           $goInvalidPassword = $this->lang->line('goUsers_invalidPassword');
           $goErrorUserGroupMustNotBeEmpty = $this->lang->line('goUsers_errorUserGroupMustNotBeEmpty');         
           # array of fields to be check
           $fields  = array('pass'); 
           $valid = false;
           
           foreach($data as $col => $value){
               if(in_array($col,$fields)){
                   switch($col){
                       case 'user':
                            if(strlen($value) >= 2){
                                if(preg_match('/^[a-z0-9\_]+$/i',$value)){
                                    $valid = true;
                                }else{
                                    die($goInvalidUserFormat);
                                }
                            }else{
                                die($goInvalidUserFormat);
                            }
                       break;
                       case 'pass':
                            if(strlen($value) >= 2){
                                if(preg_match('/^[a-z0-9\_\'\-\@\#\$\%\^\&\*\!\.\,\"\(\)]+$/i',$value)){
                                    $valid = true;
                                }else{
                                    die($goInvalidPassword);
                                }
                            }else{
                                die($goInvalidUserFormat);
                            }
                       break;
                       case 'user_group':
                             if(strlen($value) > 0){
                                 $valid = true;
                             }else{
                                 die($goErrorUserGroupMustNotBeEmpty);
                             }
                       break;
                   }
               }else{
                   $valid = true;
               }
           }

           return $valid;
            
     }

     /*
      * checkduplicate 
      * check data to be inserted if unique
      * @author : Franco Hora <info@goautodial.com>
      * @param : $table > table to be checked
      *          $queryconditions > conditions to check an array containing of all array conditions
      *                             format $queryconditions(array('field'=>'value'),array('field2'=>'value2'));
      *                                                     to check data entry    ,id to check
      *          $self > array containing condition to check if editing a user account
      */
     function checkduplicate($table=null,$queryconditions=array(),$self=array()){
         $goErrorConditionsAreEmptyOrNotAnArray = $this->lang->line('goUsers_errorConditionsAreEmptyOrNotAnArray');
         $goErrorTableNotExistOrFieldsetEmpty = $this->lang->line('goUsers_errorTableNotExistOrFieldsetEmpty');
         # check fields info if allow null don't check null
         $fieldsinfo=$this->asteriskDB->query("show columns from $table");
         if($fieldsinfo->num_rows > 0){ 
             $ctr=0;
             foreach($fieldsinfo->result() as $info){ # loop of table fields
                     # check if its array
                     if(is_array($queryconditions)){
                         # loop lets check if all conditions are not empty
                         foreach($queryconditions as $conditions){ 
                             if(is_array($conditions)){ # check if supplied condition is an array
                                 if(key($conditions) == $info->Field){
                                     if($info->Null === "YES" && (is_null($conditions[key($conditions)]) || empty($conditions[key($conditions)])) ){ 
                                         # meaning the column is allowed to have null value and its empty so nothing to check 
                                         $resultsofcheck["YES"] = $conditions;
                                     }elseif($info->Null === "NO" && (is_null($conditions[key($conditions)]) || empty($conditions[key($conditions)])) ){
                                         # meaning the column is not allowed to have null value and its empty too so return error
                                         $resultsofcheck["NO"] = "Error: `$info->Field` must not be empty";
                                     }else{
                                         # meaning the column is either YES or NO and has array condition to check return conditions
                                         $resultsofcheck[$ctr] = $conditions;
                                     }
                                     $ctr++;
                                 }
                             }else{ # else report error 
                                 die($goErrorConditionsAreEmptyOrNotAnArray);
                             }
                         }
                         
                     }else{
                         die($goErrorConditionsAreEmptyOrNotAnArray);
                     }
             }
             if(array_key_exists("NO",$resultsofcheck)){
                  # meaning error on process
                  die($resultsofcheck["NO"]);
             }else{
                  # meaning conditions accepted it has YES and has conditions
                      # now lets create our conditions
                      foreach($resultsofcheck as $index => $createcondition){
                          # if index NOT YES meaning escape the column
                          if(preg_match('/^[0-9]+$/i',$index)){ # check if index is number it means this are conditions
                               $this->asteriskDB->where($createcondition);
                          }
                      }
                      if(!empty($self)){ # add this condition to check in editing a user
                          $this->asteriskDB->where(key($self),reset($self));
                      }
                      $result = $this->asteriskDB->get($table);
                      if($result->num_rows() > 0){
                           die('Error: Duplicate entry on `'.key($createcondition)."` at table: $table");
                      }else{
                           return true;
                      }
             }

         }else{
             die($goErrorTableNotExistOrFieldsetEmpty);
         }
 
     }


     /*
      * bygroup
      * collect all users in array bygroup
      * @author : Franco Hora <info@goautodial.com>
      */
     function bygroup($username){

          #$username = $this->session->userdata('user_name');
          $groupings = array();
          $collector = $this->retrievedata('go_users',array('users_id'=>$username));
          # collect
          $ctr =0;
          $firstlevel = $this->retrievedata('go_users',array('users_group'=>$collector[0]->id));
          if(count($firstlevel) > 0){ # has underlings
              foreach($firstlevel as $firstlevelgroup){
                   if(in_array($firstlevelgroup->users_level,array(1,2,3,4,5,6,7))){ # users currently logged is in level 8
                       $agents[$ctr]=array($firstlevelgroup);
                   }else{
                       if($firstlevelgroup->users_level == 8){ # user currently logged is in level 9
                           $ctr1=0;
                           $firstgroup=array();
                           $secondlevel = $this->retrievedata('go_users',array('users_group'=>$firstlevelgroup->id));
                           foreach($secondlevel as $secondlevelgroup){
                                $firstgroup[$ctr1] = $secondlevelgroup;
                                $ctr1++;
                           }
                           $agents[$ctr] = array($firstlevelgroup,$firstgroup);
                       }else{
                           # this part means you are in level 10
                           $ctr2=0;
                           $secondgroup = array();
                           $secondlevel = $this->retrievedata('go_users',array('users_group'=>$firstlevelgroup->id));
                           foreach($secondlevel as $secondlevelgroup){
                                $thirdlevel = $this->retrievedata('go_users',array('users_group'=>$secondlevelgroup->id));
                                $secondgroup[$ctr2] = array($secondlevelgroup,$thirdlevel);
                                $ctr2++;
                           }
                           $agents[$ctr]=array($firstlevelgroup,$secondgroup);
                       }
                   }
                   $ctr++;
              }
              $groupings = array($collector[0],$agents);
          }elseif($collector[0]->users_level == 7){ # user currently logged is in level 7 or the IT/SUP/TL
              $groupings = array($collector,$this->retrievedata('go_users',array('users_group'=>$collector[0]->users_group),array(),array(array('id !='=>$collector[0]->id)))); 
          }else{
              $groupings = array();
          }
          return $groupings;
     }


     /*
      * retrievedata
      * function to collect data use for simple retrieving of data in asterisk cant use "or" statement here
      * @author: Franco Hora <info@goautodial.com>
      * @param: $table > <string> name of table 
      *         $conditions > <array> array condition if null then select all otherwise use condition
      *         $fetchthis > array containing specified fields to query
      *         $addconditions > array containing arrays of secondary to nth db condition
      *         $orderby > array containing order 
      */
     function retrievedata($table=null,$conditions=array(),$fetchthis=array(),$addconditions=array(),$orderby=array()){
         if(!is_null($table)){

             if(is_array($addconditions) && !empty($addconditions)){
                 foreach($addconditions as $additional){
                     if(is_array($additional)){
                         $this->asteriskDB->where($additional);
                     }else{
                         die('Conditions must be an array');
                     }
                 }
             }

             if(!is_null($orderby) && !empty($orderby)){
                  foreach($orderby as $order){
                      if(is_array($order)){
                          foreach($order as $field => $byorder){
                               $this->asteriskDB->order_by($field,$byorder);
                          }
                      }else{
                          $this->asteriskDB->order_by($order);
                      }
                  }
             }



             if(count($conditions)==0){

                 if(!empty($fetchthis)){
                     $this->asteriskDB->select($fetchthis);
                     $retrievingdata = $this->asteriskDB->get($table);
                 }else{
                     $retrievingdata = $this->asteriskDB->get($table);
                 }
                 if($retrievingdata->num_rows > 0){
                      return $retrievingdata->result();
                 }else{
                      return array();
                 }
             }else{
                 if(!empty($fetchthis)){
                     $this->asteriskDB->select($fetchthis);
                     $retrievingdata = $this->asteriskDB->get_where($table,$conditions);
                 }else{
                     $retrievingdata = $this->asteriskDB->get_where($table,$conditions);
                 }
                 if($retrievingdata->num_rows > 0){
                     return $retrievingdata->result();   
                 }else{
                     return array();
                 }
             }
         }else{
            die('Something went wrong sorry');
         }
     }


     /*
      * updateuser
      * function to update user
      * @author : Franco Hora <info@goautodial.com>
      * $param : $fields > array of fields to update
      */
     function updateuser($fields=array(),$updatestat='quick'){
         $goErrorOnUpdatingUser = $this->lang->line('goUsers_errorOnUpdatingUser');
         $goUpdateSuccessful = $this->lang->line('goUsers_updateSuccessful');
         # set permission here not yer applied
         # if(in_array(MODIFYUSER,$permissionarray)){ user allowed to update this is just a sample
               foreach($fields as $userid => $fieldsarray){
                   # create data for go_users and vicidial_users 
                   foreach($fieldsarray as $cols => $fieldsval){
                       $columnInfo = $this->asteriskDB->query("show columns from vicidial_users where field = '$cols'");
                       if($columnInfo->num_rows > 0){
                            $vicidialusers[$cols] = $fieldsval;
                            if ($cols=='phone_login')
                                $phone_login = $fieldsval;
                            if ($cols=='phone_pass')
                                $phone_pass = $fieldsval;
                       }
                   } 
                   #if($this->checkduplicate('vicidial_users',array(array('phone_login'=>$fields[key($fields)]['phone_login'])),array('user_id !='=>key($fields)))){
                       # set transaction for rollback if ever fail entry occurs
                       $this->asteriskDB->trans_start(); 
                           # save data in go_users
                           #$this->asteriskDB->where('vicidial_user_id',$userid);
                           #$this->asteriskDB->update('go_users',$gousers);
                           # save data in vicidial_users
                           $this->asteriskDB->where('user_id',$userid);
                           $this->asteriskDB->update('vicidial_users',$vicidialusers);

                           #$this->asteriskDB->where('login',$vicidialusers['phone_login']);
                           #$this->asteriskDB->update('phones',array('pass'=>$vicidialusers['phone_pass']));
                       $this->asteriskDB->trans_complete();
                       if ($this->asteriskDB->trans_status() === FALSE){
                           $toreturn = $goErrorOnUpdatingUser;
                       }else{
                           $toreturn = $goUpdateSuccessful;
                       }
                       
                       // update phone login and pass on phones table
                       $this->asteriskDB->trans_start();
                       $this->asteriskDB->where('login',$phone_login);
                       $this->asteriskDB->update('phones',array('pass'=>$phone_pass,'conf_secret'=>$phone_pass));
                       $this->asteriskDB->trans_complete();
                       
                       
                       /*$vici_user = $this->commonhelper
                                             ->simpleretrievedata('vicidial_users',
                                                                  null,
                                                                  array(),
                                                                  array(array("user_id"=>$userid)));
                       if($vici_user->num_rows() > 0){
                            $userinfo = $vici_user->result();
                       }

                       $vici_phone = $this->commonhelper
                                             ->simpleretrievedata('phones',
                                                                  null,
                                                                  array(),
                                                                  array(array("phone_pass"=>$vicidialusers['phone_pass'])));
                       if($vici_user->num_rows() > 0){
                            $phones = $vici_phone->result();
                       }*/
                       
                       $details =  "Modify vicidial_user user_id = ".$userid." and vicidal_phones phone_login = ".$vicidialusers['phone_pass'];
                       $query = null;
                       foreach($this->asteriskDB->queries as $queries){
                           $query .= "$queries \n";
                       }
 
                       $this->commonhelper->auditadmin('MODIFY',$details,$query);
 
                       return $toreturn;
 
                   #}else{
                   #    return "Duplicate Phone Login";
                   #}
               }
        # }else{
              # die ("You are not allowed to update user");
         #}
     }


     /*
      * createusergroup
      * function to create a new user group
      * @author: Franco Hora <info@goautodial.com>
      * @param: id of the current user logged in
      */
     function createusergroup($currentuser=null){
         $goGroupConfiguration = $this->lang->line('goUsers_groupConfiguration');
         $goGroupName = $this->lang->line('goUsers_groupName');
         $goGroupLevel = $this->lang->line('goUsers_groupLevel');
         $goAddAccess = $this->lang->line('goUsers_addAccess');
         $goRemoveAccess = $this->lang->line('goUsers_removeAccess');
         $goSomethingWentWrongOnCreatingNewGroup = $this->lang->line('goUsers_somethingWentWrongOnCreatingNewGroup');
         $goSomethingWentWrongOnSavingNewGroup = $this->lang->line('goUsers_somethingWentWrongOnSavingNewGroup');
         if(!is_null($currentuser)){

             #logged in user level get from session
             $groupLevel = $this->session->userdata('group_level');
     

             #let's get information of heirarchy to create owner_group where our format would be ownerid[_rellseruserid[_signupid]]
             $heirarchy = $this->getheirarchy($currentuser); 
             $currentuser = $this->retrievedata('go_users',array('vicidial_user_id'=>$currentuser));
             $heirarchy[$this->ctr++] = $currentuser;
             #create group owner 
             $createdowner_group=null;
             foreach($heirarchy as $users){
                 $createdowner_group .= $users[0]->users_id."_";
             }
             # remove trailing underscore
             $createdowner_group = substr($createdowner_group,0,strlen($createdowner_group)-1);
             # set transaction if ever interference occur CI rollback data
             $this->asteriskDB->trans_start(TRUE);
                 $result = $this->asteriskDB->insert('go_groupaccess',array('group_owner'=>$createdowner_group)); 
                 # we put value to $this->tempgroupcreated
                 $this->tempgroupcreated = $this->asteriskDB->insert_id() ;
             $this->asteriskDB->trans_complete();
             if($result){

                 # switch to interchange the options
                 $options =array(4=>'Administrator',3=>'Manager',2=>'IT/Sup/TL',1=>'Agents');
                 # remove the must not see levels
                 if(!empty($currentuser)){
                     foreach($options as $key => $titles){
                         if($groupLevel == 10){
                             # do nothing
                         }else{
                             if($key >= $groupLevel){
                                 unset($options[$key]);
                             }
                         }
                     } 
                 }

                 echo "<div id='user-groups-".$this->tempgroupcreated."--$createdowner_group' class='new-user-groups'>
                           <h3>$goGroupConfiguration</h3>
                           <div class='adv-allowed-access user-corners new-allowed-access'>
                                <form id='new-group-".$this->tempgroupcreated."'>
                                <div class='leftcol'>$goGroupName</div>
                                <div class='rightcol'><input type='text' name='groupaccess_name-".$this->tempgroupcreated."' id='group_name-".$this->tempgroupcreated."' size='16'></div>
                                <div class='leftcol'>$goGroupLevel</div>
                                <div class='rightcol'>".form_dropdown('group_level-'.$this->tempgroupcreated,$options,null,"id='group_level-".$this->tempgroupcreated."'")."</div>
                                <br class='clear'/>
                                <div id='new-allowed-access' class='new-access'></div>
                                <br class='clear'/>
                                <div class='group-action'>
                                     <a href='javascript:void(0);'>$goAddAccess</a> |
                                     <a href='javascript:void(0);'>$goRemoveAccess</a>
                                </div>
                                </form>
                           </div>
                       </div>"; 
             }else{
                 die($goSomethingWentWrongOnCreatingNewGroup);
             }
         }else{
             die($goSomethingWentWrongOnSavingNewGroup);
         }
     }


     /*
      * getheirarchy
      * get the upper hand of the heirarchy
      * @author: Franco Hora <info@goautodial.com>
      * @param : $userid > id of the user
      */
     function getheirarchy($userid){
         $result = $this->retrievedata('go_users',array('vicidial_user_id'=>$userid));
         if(!empty($result)){
             #get the higher level 
             if($result[0]->users_level < 10){ # get until reseller user_id
                  $higherowner = $this->retrievedata('go_users',array('id'=>$result[0]->users_group));
                  $this->heirarchy_container[$this->ctr] = $higherowner;
                  $this->ctr++;
                  $this->getheirarchy($higherowner[0]->vicidial_user_id);
             } 
         }else{
            $this->heirarchy_container;
         }
         return array_reverse($this->heirarchy_container);
     }


     /*
      * saveusergroup
      * function to save user group
      * @author: Franco Hora <info@goautodial.com>
      * @param : $postfields > array containing the post variables
      *          $groupid > id to be update
      */
     function saveusergroup($postfields=array(),$groupid){
        $goSomethingWentWrongContactYourSupport = $this->lang->line('goUsers_somethingWentWrongContactYourSupport');
        $goSaved = $this->lang->line('goUsers_saved');
        $goFieldsAreEmpty = $this->lang->line('goUsers_fieldsAreEmpty');
        if(!empty($postfields)){
            # prepare of data entry
            $vicidial_user_id = null;
            foreach($postfields as $carier => $postfield){
                $vicidial_user_id = $carier;
                if(array_key_exists('useraccess_id',$postfield)){
                    if(is_array($postfield['useraccess_id'])){
                        $postfields[$carier]['useraccess_id'] = serialize($postfield['useraccess_id']);
                    }
                }
            }
            # begin saving data 
            $this->asteriskDB->trans_start();
                $this->asteriskDB->where(array('id'=>$groupid));
                $this->asteriskDB->update('go_groupaccess',$postfields[$vicidial_user_id]);
            $this->asteriskDB->trans_complete();

            if ($this->asteriskDB->trans_status() === FALSE){
                die($goSomethingWentWrongContactYourSupport);
            }else{
                echo $goSaved;
            }

        }else{
            die($goFieldsAreEmpty);
        }
     }


     /*
      * retrievegroup
      * function to retrieve groups 
      * @author : Franco Hora <info@goautodial.com>
      * @param : $userid > id of the group owner
      *          $conditions  > condition filters
      *          $orders  > order to display
      */
     function retrievegroup($userid,$conditions=array(),$orders=null){
         if(is_array($conditions)){
             foreach($conditions as $condition){
                  $this->asteriskDB->where($condition);
             }
         }

         if(!is_null($orders)){
             if(is_array($orders)){
                 foreach($orders as $orderkey => $ordervalue){
                     $this->asteriskDB->order_by($orderkey,$ordervalue);
                 }
             }
         }

         return $this->asteriskDB->get('go_groupaccess')->result();
     }

     /*
      * deleteitem
      * funciton to permanently delete item
      * @author: Franco Hora <info@goautodial.com>
      * @param : $condition what condition needed to delete the item must be array
      *          $table table where to delete the item
      *          $msg = true if you want a echo message
      */
     function deleteitem($table,$condition,$msg=false){
         $goWrongConditionFormat = $this->lang->line('goUsers_wrongConditionFormat');
         $goItemDeleted = $this->lang->line('goUsers_itemDeleted');
         $goMustHaveConditionToDeleteAnItem = $this->lang->line('goUsers_mustHaveConditionToDeleteAnItem');
         if(!empty($condtion) || !is_null($condition)){
             if(is_array($condition)){
                 foreach($condition as $conditions){
                     if(is_array($conditions)){
                         $this->gouser->asteriskDB->where($conditions);
                     }else{
                         die($goWrongConditionFormat);
                     }
                 }
             }else{
                 $this->gouser->asteriskDB->where($condition);
             }
             $this->gouser->asteriskDB->delete($table);
             if($msg){
                 echo $goItemDeleted;
             }
         }else{
             die($goMustHaveConditionToDeleteAnItem);
         }
     }

     /*
      * getallowedcampaign
      * function to get all allowed campaigns in vicidial_user_groups
      * used in vicidial process usually
      * @author: Franco Hora <info@goautodial.com> 
      * @param : $usergroup > account/user_group
      *          $isSlave > check what to use 
      */
     function getallowedcampaign($usergroup=null,$isSlave=false){
         $goErrorOnPassingUserAccountOrUserGroup = $this->lang->line('goUsers_errorOnPassingUserAccountOrUserGroup');
         if($usergroup){
             if(!$isSlave){
                 $this->asteriskDB->where(array('user_group'=>$usergroup));   
                 $viciUsergroup = $this->asteriskDB->get('vicidial_user_groups')->result();
                 $campaigns = explode(" ",trim(str_replace("-","",$viciUsergroup[0]->allowed_campaigns)));
             }else{
                 $this->slaveDB->where(array('user_group'=>$usergroup));   
                 $viciUsergroup = $this->slaveDB->get('vicidial_user_groups')->result();
                 $campaigns = explode(" ",trim(str_replace("-","",$viciUsergroup[0]->allowed_campaigns)));
             }
             return $campaigns;
         }else{
             die($goErrorOnPassingUserAccountOrUserGroup);
         }
     }

}

?>
