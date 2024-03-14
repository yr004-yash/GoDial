<?php
############################################################################################
####  Name:             go_list.php                                                     ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################

$base = base_url();
echo $lead_file;
?>
<script>
	$(document).ready(function(){

				var $tabs = $('#tabs').tabs();
				var $tabvalsel = $('#tabvalsel').val();				
				
				$( "#tabs" ).tabs();
			
				/* tabs navigation */				
				if($tabvalsel=="tabloadleads") {
			                $tabs.tabs('select', 2);
				}	

				if($tabvalsel=="customleads")	{
			        	$tabs.tabs('select', 1);						
				}
				
				$('#list_id').bind("keydown keypress", function(event)
				{
				 //console.log(event.type + " -- " + event.altKey + " -- " + event.which);
				 if (event.type == "keydown") {
				  // For normal key press
				  if (event.keyCode == 32 || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				   || event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				   || event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				   return false;
				  
				  if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				   return false;
				  
				  if (!event.shiftKey && event.keyCode == 173)
				   return false;
				 } else {
				  // For ASCII Key Codes
				  if ((event.which > 31 && event.which < 48) || (event.which > 57 ))
				   return false;
				 }
				 
				 $(this).css('border','solid 1px #999');
				});
				
				
				
                                   $("#list_id").keydown(function(event) {
                                   if(event.shiftKey)
                                        event.preventDefault();
                                   if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
                                   }
                                   else {
                                        if (event.keyCode < 95) {
                                          if (event.keyCode < 48 || event.keyCode > 57) {
                                                event.preventDefault();
                                          }
                                        }
                                        else {
                                              if (event.keyCode < 96 || event.keyCode > 105) {
                                                  event.preventDefault();
                                              }
                                        }
                                      }
                                   });

				$("#field_label").keydown(function(event) {
    			//		if (event.keyCode == 32) {
			//			event.preventDefault();
			//		}				
				});
				
				$("#field_options").keydown(function(event) {
    			//		if (event.keyCode == 32) {
			//			event.preventDefault();
			//		}
				});

		
		$("#advancedFieldLink").click(function()
		{
			if ($(".advancedFields").is(':hidden'))
			{
				$(".advancedFields").show();
				$(this).html("[ - <? echo lang('go_HIDEADVANCEFIELDS') ?> ]");
			} else {
				$(".advancedFields").hide();
				$(this).html("[ + <? echo lang('go_SHOWADVANCEFIELDS'); ?> ]");
			}
		});
		
	$( "#uploadform2" ).submit(function( event ) {
		// Stop form from submitting normally
		event.preventDefault();
		
		var $showResult = $( "#show_results" ).is(':checked');
		if ($showResult) {
			$( ".hideThisOne" ).hide();
			$( ".showResults" ).show();
		}
		$( ".hideLoading" ).show();
	       
		// Get some values from elements on the page:
		var $form = $( this ),
			term = $form.find( "input[name='s']" ).val(),
			url = $form.attr( "action" );
	       
		// Send the data using post
		var posting = $.post( url, $form.serialize() );
	       
		// Put the results in a div
		posting.done(function( data ) {
			$( ".hideLoading" ).hide();
			$( "#show_result" ).empty().append( data );
			if ($showResult) {
				$( ".showResults" ).show();
			} else {
				$( ".showResults" ).hide();
				window.location.href = '<?=base_url() ?>go_list';
			}
		});
	});
    });  
</script>


<script>
$(document).ready(function() 
    { 
        $("#listtableresult").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
        $("#cumstomtable").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
    } 
); 
</script>

<!--  Javascript section -->
<script language="javascript">


	function checklistadd() {
		var camp_iddetail = document["go_listfrm"]["campaign_id"].value;
		
		if (camp_iddetail=="") {
			alert("Campaign is empty."); return false;
		} else {
			document['go_listfrm'].submit();	
		}
		
	}

	function getselval() {
    		var account_num = document.getElementById('campaign_id').value;
	}

	function showaddlist(listid) {
		document.getElementById('addlist').style.display='block';
		document.getElementById('showlist').style.display='none';
		document.getElementById('list_id').value = listid;
	}


	function showRow() {
    		var autoGen = document.getElementById('auto_gen');

    		if (autoGen.checked) {
        		document.getElementById('list_id').readOnly = true;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('autogenlabel').innerHTML= "<font color='red' size='1'>(<? echo lang('go_autogenerated'); ?>) </font>";
										genListID();
    		} else {
      	 		document.getElementById('list_id').readOnly = false;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('autogenlabel').innerHTML= "<font color='red' size='1'>(<? echo lang('go_numericonly'); ?>) </font>";
    		}
	}


	function genListID() {
		document.getElementById('list_id').readOnly = true;
    		var account_num = document.getElementById('account_num');
    		var cntX=0;
		    		

        		var autoListID;
        			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            				xmlhttp=new XMLHttpRequest();
            			} else {// code for IE6, IE5
            				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        			}
        	
			xmlhttp.onreadystatechange=function()
            		{
            			if (xmlhttp.readyState==4 && xmlhttp.status==200)
                		{
                			var returnString=xmlhttp.responseText;
                			var returnArray=returnString.split("\n");
					var cnt=returnArray[1];
                			var camp_list=returnArray[2].split(",");
                			var camp_name=returnArray[3].split(",");
					var accnt_num = returnArray[3];
                			var i=0;
                			
                			cntX=accnt_num;

					
                			
							var currentTime = new Date();
							var month = currentTime.getMonth() + 1;
							var day = currentTime.getDate();
							var year = currentTime.getFullYear();
							var comdate = month+'-'+day+'-'+year;
							
							document.getElementById('list_name').value = 'ListID ' + cntX;
							document.getElementById('list_description').value = 'Auto-Generated ListID - '+comdate;
							document.getElementById('list_id').value = cntX;
									

					

                		}	
            		}
        		//xmlhttp.open("GET","go_list/go_list.php?stage=addLIST&accnt="+autoListID,true);
			xmlhttp.open("GET","",true);
        		xmlhttp.send();
	} //end function
	
	function ParseFileName() {
			
	if (!document.uploadform.OK_to_process) 
		{	
		
		var filename = document.getElementById("leadfile").value;

		var endstr = filename.lastIndexOf('');
			
		if (endstr>-1) 
			{
			endstr++;
			document.getElementById('leadfile_name').value=filename;
			}
		}
	}

	function ShowProgress(good, bad, total, dup, post) {
		parent.lead_count.document.open();
		parent.lead_count.document.write('<html><body><table border=0 width=200 cellpadding=10 cellspacing=0 align=center valign=top><tr bgcolor="#000000"><th colspan=2><font face="arial, helvetica" size=3 color=white>Current file status:</font></th></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Good:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+good+'</B></font></td></tr><tr bgcolor="#990000"><td align=right><font face="arial, helvetica" size=2 color=white><B>Bad:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+bad+'</B></font></td></tr><tr bgcolor="#000099"><td align=right><font face="arial, helvetica" size=2 color=white><B>Total:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+total+'</B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B> &nbsp; </B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B> &nbsp; </B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Duplicate:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+dup+'</B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Postal Match:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+post+'</B></font></td></tr></table><body></html>');
		parent.lead_count.document.close();
	}

	function copyview() {
                $('#overlaycopy').fadeIn('fast',function(){
                        $('#boxcopy').animate({'top':'70px'},500);
                });
                                         
                $('#boxclosecopy').click(function(){
                        $('#boxcopy').animate({'top':'-550px'},500,function(){
                         $('#overlaycopy').fadeOut('fast');
                        });
                });
                
                $('#btnclose').click(function(){
                        $('#boxcopy').animate({'top':'-550px'},500,function(){
                          $('#overlaycopy').fadeOut('fast');
                       });
                });     

	}	

	function viewadd() {

<?php
                             $permissions = $this->commonhelper->getPermissions("customfields",$this->session->userdata("user_group"));
                             if($permissions->customfields_create == "N"){
                                echo("alert('lang('go_Youdonthavepermissiontocreatethisrecords')')");
                                echo "return false;";
                             }
                ?>
		document.getElementById('hide_listid').value= "";
                document.getElementById('field_label').value= "";
                document.getElementById('field_rank').value= "";
                document.getElementById('field_order').value= "";
                document.getElementById('field_name').value= "";
                document.getElementById('name_position').value= "";
                document.getElementById('field_description').value= "";
                document.getElementById('field_type').value= "";
                document.getElementById('field_options').value= "";
                document.getElementById('multi_position').value= "";
                document.getElementById('field_size').value= "";
                document.getElementById('field_max').value= "";
                document.getElementById('field_default').value= "";
                document.getElementById('field_required').value= "";

                $('#overlayadd').fadeIn('fast',function(){
                        $('#boxaddcustom').animate({'top':'-50px'},500);
                });
                                         
                $('#boxclose').click(function(){
                        $('#boxaddcustom').animate({'top':'-3000px'},500,function(){
                                $('#overlayadd').fadeOut('fast');
                                
                        });
                        $('#spanaddcustom').show("fast");
						 																	$('#spancopycustom').hide("fast");
                });
                
                $('#btnclose').click(function(){
                        $('#boxaddcustom').animate({'top':'-3000px'},500,function(){
                                $('#overlayadd').fadeOut('fast');
                        });
                });     

	}


	function customviews(listid) {
		

			$.post("<?=$base?>index.php/go_list/editcustomview", { action: "customview", list_id: listid   },
				 function(data){
                          
                				var datas = data.split("||");
                				var i=0;
                				var count_listid = datas.length;
                				
                				document.getElementById('viewme').innerHTML = data;

 								$('#overlayview').fadeIn('fast',function(){
 								  	$('#boxview').show();
 								  	$('#overlayview').show();
	                  			$('#boxview').animate({'top':'70px'},500);
		             			});
	             				 
	             				$('#boxcloseview').click(function(){
	              					$('#boxview').animate({'top':'-550px'},500,function(){
			                  			$('#overlayview').fadeOut('fast');
			                  			$('#boxview').hide();
			                  			$('#overlayview').hide();
	              					});
								});
								 	
								$('#btncloseview').click(function(){
	              					$('#boxview').animate({'top':'-550px'},500,function(){
	                  					$('#overlayview').fadeOut('fast');
	              					});
								});	
				});	
	}	
	

	function addsubmit() {
                
		var hide_listid = document.getElementById('hide_listid').value;
                var field_label = document.getElementById('field_label').value;
                var field_rank = document.getElementById('field_rank').value;
                var field_order = document.getElementById('field_order').value;
                var field_name = document.getElementById('field_name').value;
                var name_position = document.getElementById('name_position').value;
                var field_description = document.getElementById('field_description').value;
                var field_type = document.getElementById('field_type').value;
                var field_options = document.getElementById('field_options').value;
                var multi_position = document.getElementById('multi_position').value;
                var field_size = document.getElementById('field_size').value;
                var field_max = document.getElementById('field_max').value;
                var field_default = document.getElementById('field_default').value;
                var field_required = document.getElementById('field_required').value;
              //var field_options = $("#field_options").val().replace("\n", "");
	      
  if(hide_listid == "") {
  	alert('<? echo lang('go_ListIDisarequiredfield'); ?>.');
			return false;
  }
  if(hide_listid == "copycustomselect") {
  	alert('Choose List I.D.');
			return false;
  }
		if(field_label == "") {
			alert('<? echo lang('go_Labelisarequiredfield'); ?>.');
			return false;
		}
		if(field_name == "") {
			alert('<? echo lang('go_Nameisarequiredfield'); ?>.');
			return false;
		}
		if(field_rank == "") {
			alert('<? echo lang('go_Rankisarequiredfield'); ?>.');
			return false;
		}
		if(field_order == "") {
			alert('<? echo lang('go_Orderisarequiredfield'); ?>.');
			return false;
		}
		if(field_max == "") {
                        alert('<? echo lang('go_Fieldmaxisarequiredfield'); ?>.');
                        return false;
                }
		if((field_size == "") || (field_size < 1)) {
			alert('<? echo lang('go_Fieldsizeisarequiredfield'); ?>.');
			return false;
		}
		
 
		 $.post("<?=$base?>index.php/go_list/editcustomview", { action: "customadd", listid: hide_listid, field_label: field_label, field_rank: field_rank, field_order: field_order, field_name: field_name, name_position: name_position, field_description: field_description, field_type: field_type, field_options: field_options, multi_position: multi_position, field_size: field_size, field_max: field_max, field_default: field_default, field_required: field_required },
                                         
                                 function(data){
					      alert("<? echo lang('go_SUCCESSCustomFieldAdded'); ?>");
                                              location.reload();
                                 });
		
	}

	function copysubmit() {
		var to_list_id = document.getElementById('to_list_id').value;
                var source_list_id = document.getElementById('source_list_id').value;
                var copy_option = document.getElementById('copy_option').value;
	
                 $.post("<?=$base?>index.php/go_list/copycustomview", { action: "copycustomlist", source_list_id: source_list_id, to_list_id: to_list_id, copy_option: copy_option },
                                         
                                 function(data){
                                                alert("<? echo lang('go_SUCCESS'); ?>: "+copy_option+ " <? lang('go_CustomField')?>");
                                                //alert(data);
                                                location.reload();
                                 }); 

	
	}

	function selectlistid() {

		var select_id = document.getElementById("hide_listid");
		var listID = select_id.options[select_id.selectedIndex].value;

		document.getElementById('copyselectlist').value= "";
		if(listID=="copycustomselect") {
						 $('#spanaddcustom').hide("fast");
						 $('#spancopycustom').show("fast");
		}else{		
			$('#countsd').load('<? echo $base; ?>index.php/go_list/oncheangeselect/'+listID);
		}
		

	}
	
	function selectlistidcopy() {
				
		var select_id2 = document.getElementById("copyselectlist");
		var listID2 = select_id2.options[select_id2.selectedIndex].value;
		document.getElementById('hide_listid').value= "";
		
				if(listID2=="createcustomselect") {
							$('#spancopycustom').hide("fast");
							$('#spanaddcustom').show("fast");
							$('#tbloverlaycopy').css("width", "105%");
							
		}
	}

        function selectlistidagain(again_listid) {
                var listID = again_listid;
                $('#countsd').load('<? echo $base; ?>index.php/go_list/oncheangeselect/'+listID);
        }	

	function postval(listID) {

   <?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_update == "N"){
                                echo("alert('lang('go_Youdonthavepermissiontoupdatethisrecords')')");
                                echo "return false;";
                             }
  ?>

		document.getElementById('showval').value=listID;
		document.getElementById('showvaledit').value=listID;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_list/editview", { items: items, action: "editlist" },
				 function(data){
				 	
                				var datas = data.split("--");
                				var data_statuses = data.split("##");
                				var i=0;
                				var j=0;
                				var count_listid = datas.length;
                				var count_status = data_statuses.length;
 						var stats = data_statuses[1];
						
						for (i=0;i<count_status;i++) {
							document.getElementById('stats').innerHTML=data_statuses[i];
						}
		
						for (i=0;i<count_listid;i++) {
											 											
 										document.getElementById('listname_edit').value=datas[2];
 										document.getElementById('listdesc_edit').value=datas[5];
 										document.getElementById('campid_edit').value=datas[3];
 										document.getElementById('reslist_edit').value='N';
 										document.getElementById('act_edit').value=datas[4];
 										document.getElementById('restime_edit').value=datas[8];
 										document.getElementById('agcscp_edit').value=datas[9];
 										document.getElementById('campcidover_edit').value=datas[10];
 										document.getElementById('drpinbovr_edit').value=datas[12];
 										document.getElementById('wbfrmadd_edit').value=datas[18];
 										document.getElementById('xfer1').value=datas[13];
 										document.getElementById('xfer2').value=datas[14];
 										document.getElementById('xfer3').value=datas[15];
 										document.getElementById('xfer4').value=datas[16];
 										document.getElementById('xfer5').value=datas[17];
										document.getElementById('cdates').innerHTML= "<i><? echo lang('go_Changedate'); ?>: "+datas[6]+"</i>";
										document.getElementById('lcdates').innerHTML= "<i><? echo lang('go_Lastcalldate');?>: "+datas[7]+"</i>";
										document.getElementById('listid_edit').innerHTML= "<b><? echo lang('go_ModifyListID'); ?> "+datas[1]+"</b>";
										//document.getElementById('oldcampaignid').innerHTML=datas[3];
										
										//document.getElementById('showval').innerHTML= datas[1];
										
 									} 
 											
 								  $('#overlay').fadeIn('fast',function(){
 								  		$('#box').show('fast');
	                  			$('#box').animate({'top':'-70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	
	}
	
	function editpost(listID) {

			document.getElementById('showval').value=listID;
			document.getElementById('showvaledit').value=listID;
			
			$('#box').append("<span style='position:absolute;top:200px;z-index:99;width:100%;'><center><img src='<? echo $base; ?>img/goloading.gif' style='background-color:white;' /></center></span>");

			var itemsumit = $('#edit_go_listfrm').serialize();
				$.post("<?=$base?>index.php/go_list/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
				function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;
                				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 										}
 								}
 								
 								if(datas[0]=="SUCCESS") {
 									alert(data);
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
				});	
	}
	
	function deletepost(listID) {
		
   <?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_delete == "N"){
				echo("alert('lang('go_Youdonthavepermissiontodeletethisrecords')')");
                                echo "return false;";
                             }
  ?>
	
				var confirmmessage=confirm("Confirm to delete the List "+listID+" and all of its leads?");
				if (confirmmessage==true){
					
					
					$.post("<?=$base?>index.php/go_list/deletesubmit", { listid_delete: listID, action: "deletelist" },
					function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;

 								if(datas[0]=="SUCCESS") {
 									alert(listID+" successfully deleted");
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
					});	
				} 
				
	}
	
	
	function deleteselectedlists(listID,actionmo) {

		var i=0;
		var count_listid = (listID.length);
		
		document.getElementById('loadingslist').innerHTML= "<img src=\"<? echo $base; ?>img/goloading.gif\" />";

			for (i=0;i<count_listid;i++) {
			 		$.post("<?=$base?>index.php/go_list/deletesubmit", { listid_delete: listID[i], action: actionmo },
						function(data){
							alert(data);	
						});
			}
			
			if(count_listid == i) {
				setTimeout(alertBlah,(i + 3)+"000");
			}
				
	}
	
	function alertBlah() {
		var url =window.location.href;
		var counturl = url.split("#");
		var countedurl = counturl.length;
		
		if(countedurl == 1){
			 $(location).attr('href', url);
		} else {
			$(location).attr('href', counturl[0]);
		}
	}

	var protocol = window.location.protocol;
        var host = window.location.host;
        var path_string = window.location.pathname.substr(1);
        var basepath = path_string.split("/")[0];

        //override value 0f basepath
        if(basepath === "index.php"){
            basepath = "";
        }else{
            basepath = "/"+basepath;
        }
	
	function viewpost(listID) {

		document.getElementById('showval').value=listID;
		document.getElementById('showvaledit').value=listID;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_list/editview", { items: items, action: "editlist" },
				 function(data){
				 	
                				var datas = data.split("--");
                				var i=0;
                				var count_listid = datas.length;
                				
                				for (i=0;i<count_listid;i++) {
                						if(datas[i]=="") {
												datas[i]=" ";
 											}
 										document.getElementById('viewlistid').innerHTML=datas[1];
										document.getElementById('viewlistdesc').innerHTML=datas[5];
										document.getElementById('viewliststatus').innerHTML=datas[4];
										document.getElementById('viewlistcalldate').innerHTML=datas[7];
                                                                                $("#download").attr("href",protocol+"//"+host+"/index.php/go_list/download/"+datas[1]);
 		                                  } 
 											
 								  $('#overlay').fadeIn('fast',function(){
 								  		$('#boxviewlist').show('fast');
	                  			$('#boxviewlist').animate({'top':'70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#boxviewlist').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	
	}
	
   function addlistoverlay() {
   <?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_create == "N"){
                                echo("alert('lang('go_Youdonthavepermissiontocreatethisrecords')')");
                                echo "return false;";
                             }
  ?>	
	 $('#overlay').fadeIn('fast',function(){
	                  			$('#boxaddlist').animate({'top':'70px'},500);
		             			});
  }

  function closeme() {
                    $('#box').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  			$('#box').hide('fast');
	                  			});
  }

  function closemeadd() {
	
     $('#boxaddlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  						
	              					});
  }

  function closemeview() {
	
     $('#boxviewlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  						
	              					});
  }
  
	

	
function delDNC(dnc_num)
{
	var what = confirm('Are you sure you want to delete the selected DNC number?');
	if (what)
	{
		var submit_msg = '';
		$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_dnc_number/'+dnc_num, function(data)
		{
			var pnum = dnc_num.split('-');
			$("#dnc_placeholder").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
			$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/start');

			if (data)
			{
				submit_msg = 'deleted';
			} else {
				submit_msg = 'not deleted';
			}
			alert('<? echo lang('go_Phonenumber'); ?> '+pnum[0]+' '+submit_msg+' <? echo lang('go_fromtheDNClist'); ?>.');
		});
	}
}
</script>

<script>
$(document).ready(function() 
    { 
        //$('#listtableresult').tablePagination();
        //$('#cumstomtable').tablePagination();
		
		//DNC START
		$('#search_dnc').val('');
		$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$("#dnc_placeholder").load("<?php echo $base;?>index.php/go_dnc_ce/go_search_dnc/start");
		
		$('.tab').click(function()
		{

			if ($(this).attr('id') == 'atab5')
			{
				$('#activator').hide();
				$('#submit_dnc').show();
				$('#submit_search').hide();
                                $('#newcustomfield').hide();
				$('#singrp').hide();
				$('#searchDNC').show();
			} else if ($(this).attr('id') == 'atab7') {
				$('#activator').hide();
				$('#submit_dnc').hide();
				$('#submit_search').show();
                                $('#newcustomfield').hide();
				$('#singrp').hide();
				$('#searchDNC').hide();
				
				if ($("#search_count").val() < 1) {
					$('#overlaySearch').fadeIn('fast');
					$('#boxSearch').css({'width': '600px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
					$('#boxSearch').animate({
						top: "-70px"
					}, 500);
				}
			} else {
				$('#activator').show();
                                $('#newcustomfield').show();
				$('#submit_dnc').hide();
				$('#submit_search').hide();
				$('#singrp').show();
				$('#searchDNC').hide();
			}
			
			if ($(this).attr('id') == 'atab3')
			{
			        $('#singrp').hide();
                        }

                        if ($(this).attr('id') == 'atab2')
                        {
				$('#activator').hide();
				$('#submit_dnc').hide();
				$('#submit_search').hide();
				$('#newcustomfield').show();
				$('#typeofsearch').val('custom');
                        } else {
				$('#newcustomfield').hide();
				$('#typeofsearch').val('lists');
			}


		});
		
		$('li.go_dnc_submenu').hover(function()
		{
			$(this).css('background-color','#ccc');
		},function()
		{
			$(this).css('background-color','#fff');
		});
	
		$('li.go_dnc_submenu').click(function () {
			var selectedNumber = [];
			$('input:checkbox[id="delDNC[]"]:checked').each(function()
			{
				selectedNumber.push($(this).val());
			});
	
			$('#go_dnc_menu').slideUp('fast');
			$('#go_dnc_menu').hide();
			toggleAction = $('#go_dnc_menu').css('display');
	
			var action = $(this).attr('id');
			if (selectedNumber.length<1)
			{
				alert('Please select a Phone Number.');
			}
			else
			{
				var s = '';
				if (selectedNumber.length>1)
					s = 's';
	
				if (action == 'delete')
				{
					var what = confirm('Are you sure you want to delete the selected Phone Number'+s+'?');
				if (what)
				{
					$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_mass_dnc_number/'+selectedNumber+'/', function(data)
					{
						$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
						$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/start');

						if (data)
						{
							submit_msg = 'deleted';
						} else {
							submit_msg = 'not deleted';
						}
						alert('Selected phone number(s) '+submit_msg+' from the DNC list.');
					});
				}
				}
	// 			else
	// 			{
	// 				$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_dnc_number/'+selectedNumber+'/');
	// 			}
			}
		});
	
		// Pagination
		$('#DNCTable').tablePagination({rowsPerPage: 15, optionsForRows: [15,25,50,100,"ALL"]});
	
		// Table Sorter
		$("#DNCTable").tablesorter({sortList:[[1,0],[0,0]], headers: { 2: { sorter: false}, 3: {sorter: false} }, widgets: ['zebra']});
	
	$("#showAllDNCLists").click(function()
	{
		$(this).hide();
		$("#search_dnc").val('');
                $("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
                $('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/start');
	});
	
	$("#submit_search_dnc").click(function()
	{
		var number = $("#search_dnc").val();
		number = number.replace(/\s/g,"%20");
		if (number.length > 2) {
		        $('#showAllDNCLists').show();
			$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/1/'+number);
		} else {
			alert("<? echo lang('go_Pleaseenteratleast3characterstosearch'); ?>.");
		}
	});
	
	$('#search_dnc').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 32 || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			//if (!event.shiftKey && event.keyCode == 173)
			//	return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 31 && event.which < 45) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		if (event.which == 13 && event.type == "keydown") {
			var number = $("#search_dnc").val();
			number = number.replace(/\s/g,"%20");
			if (number.length > 2) {
			        $('#showAllDNCLists').show();
				$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			        $('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/1/'+number);
			} else {
				alert("<? echo lang('go_Pleaseenteratleast3characterstosearch'); ?>.");
			}
		}
	});

		$('#submit_dnc').click(function()
		{
			$('#overlayDNC').fadeIn('fast');
			$('#boxDNC').css({'width': '600px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
			$('#boxDNC').animate({
				top: "-70px"
			}, 500);
	
			$("#overlayContentDNC").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#overlayContentDNC').fadeOut("slow").load('<? echo $base; ?>index.php/go_dnc_ce/go_submit_dnc/').fadeIn("slow");
		});

		$('#closeboxDNC').click(function()
		{
			$('#boxDNC').animate({'top':'-2550px'},500);
			$('#overlayDNC').fadeOut('slow');
			$('#campaign_idDNC').val('INTERNAL');
			$('#phone_numbers').val('');
		});
		//DNC END
		
		$('#closeboxSearch').click(function()
		{
			$('#boxSearch').animate({'top':'-2550px'},500);
			$('#overlaySearch').fadeOut('slow');
		});
		
		$('#closeboxLeadInfo').click(function()
		{
			$('#boxLeadInfo').animate({'top':'-2550px'},500);
			$('#overlayLeadInfo').fadeOut('slow');
 		});
		
		$('#closeboxRecordings').click(function()
		{
			$('#boxRecordings').animate({'top':'-2550px'},500);
			$('#overlayRecordings').fadeOut('slow');
 		});

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

                $('#uploadform').ajaxForm({
                beforeSend: function() {
                        status.empty();
                        var percentVal = '0%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                },
                complete: function(xhr) {
                        document.forms["uploadform"].submit();

                }
                
           
        });

        $('#selectAll').click(function()
        {
                if ($(this).is(':checked'))
                {
                        $('input:checkbox[id="delCampaign[]"]').each(function()
                        {
                                $(this).attr('checked',true);
                        });
                }
                else
                {
                        $('input:checkbox[id="delCampaign[]"]').each(function()
                        {
                                $(this).removeAttr('checked');
                        });
                }
        });
        
        $(document).mouseup(function (e)
        {
                var content = $('#go_action_menu, #go_status_menu, #go_camp_status_menu');
                if (content.has(e.target).length === 0 && (e.target.id != 'selectAction' && e.target.id != 'selectStatusAction'))
                {
                        content.slideUp('fast');
                        content.hide();
                        toggleAction = $('#go_action_menu').css('display');
                        toggleStatus = $('#go_status_menu').css('display');
                        toggleCampStatus = $('#go_camp_status_menu').css('display');
                }
        });
        
        
		  var toggleAction = $('#go_action_menu').css('display');
        $('#selectAction').click(function()
        {
                if (toggleAction == 'none')
                {
                        var position = $(this).offset();
                        $('#go_action_menu').css('left',position.left-110);
                        $('#go_action_menu').css('top',position.top-97);
                        $('#go_action_menu').slideDown('fast');
                        toggleAction = $('#go_action_menu').css('display');
                }
                else
                {
                        $('#go_action_menu').slideUp('fast');
                        $('#go_action_menu').hide();
                        toggleAction = $('#go_action_menu').css('display');
                }
        });

	$('li.go_action_submenu,li.go_status_submenu,li.go_camp_status_submenu').hover(function()
        {
                $(this).css('background-color','#ccc');
        },function()
        {
                $(this).css('background-color','#fff');
        });
        
        $('#selectAll').click(function()
        {
                if ($(this).is(':checked'))
                {
                        $('input:checkbox[id="delselectlist[]"]').each(function()
                        {
                                $(this).attr('checked',true);
                        });
                }
                else
                {
                        $('input:checkbox[id="delselectlist[]"]').each(function()
                        {
                                $(this).removeAttr('checked');
                        });
                }
        });
        
        
        $('li.go_action_submenu').click(function () {
                var selectedlists = [];
                $('input:checkbox[id="delselectlist[]"]:checked').each(function()
                {
                        selectedlists.push($(this).val());
                });
                
                $('#go_action_menu').slideUp('fast');
                $('#go_action_menu').hide();
                toggleAction = $('#go_action_menu').css('display');

                var action = $(this).attr('id');
                if (selectedlists.length<1)
                {
			alert("Please select a List.");
                        /* new $.msgbox({
                                type: 'alert',
                                showClose: false,
                                content: 'Please select a List.'
                        }).show(); */
                }
                else
                {
                        var s = '';
                        if (selectedlists.length>1)
                                s = 's';
                        
                        
                        if (action == 'delete')
                        {
<?php
                             $permissions = $this->commonhelper->getPermissions("list",$this->session->userdata("user_group"));
                             if($permissions->list_delete == "N"){
                                echo("alert('lang('go_Youdonthavepermissiontodeletethisrecords')')");
                                echo "return false;";
                             }
                ?>

                                /* new $.msgbox({
                                        type: 'confirm',
                                        showClose: false,
                                        content: 'Are you sure you want to delete the selected List'+s+'?',
                                        onClose: function()
                                        {
                                                if (this.getValue())
                                                {
                                                       deleteselectedlists(selectedlists,"deletelist");
                                                }
                                        }
                                }).show(); */
				var r=confirm("Are you sure you want to delete the selected List"+s);
				if (r==true) {
					deleteselectedlists(selectedlists,"deletelist");
				} else  {
					
				}
                        }
                        else if(action == 'deactivate')
                        {
                        	 deleteselectedlists(selectedlists,"deactivatelist");
                        }
                        else if(action == 'activate')
                        {
                        	 deleteselectedlists(selectedlists,"activatelist");
                        }
                }
        });

	$('#submit_search_list').click(function()
	{
	        var search = $('#search_list').val();
	        if (search.length > 2) {
		        $("#go_search_list").submit();
		} else {
		        alert("<? echo lang('go_Pleaseenteratleast3characterstosearch'); ?>.");
		}
	});
	
	$('#search_list').bind("keydown keypress", function(event)
	{
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.which == 13 && event.type == "keydown") {
			var search = $("#search_list").val();
			if (search.length > 2) {
		                $("#go_search_list").submit();
			} else {
				alert("<? echo lang('go_Pleaseenteratleast3characterstosearch'); ?>.");
			}
		}
	});
	
	$("#go_search_list").submit(function(e){
                var search = $("#search_list").val();
                if (search.length < 3) {
			return false;
                }
        });
	
	$('#showAllLists').click(function()
        {
                window.location.href = '<?=base_url() ?>go_list';
        });
	
	if ('<?=$search ?>'.length > 0) {
                $('#showAllLists').show();
	} else {
                $('#showAllLists').hide();
	}
	
	$("#showAdvance").click(function() {
		if ($(".adv").is(":hidden")) {
			$(this).html("Basic");
			$(".adv").show();
			$("#widgetCalendarLeadInfo").css({"top":"135px","z-index":"999","left":"26.7%"});
		} else {
			$(this).html("Advance");
			$(".adv").hide();
			$("#widgetCalendarLeadInfo").css("top","170px");
		}
	});
 	
	$("#showAdvanceLeadInfo").click(function() {
		if ($(".advLeadInfo").is(":hidden")) {
			$(this).html("Basic");
			$(".advLeadInfo").show();
		} else {
			$(this).html("Advance");
			$(".advLeadInfo").hide();
		}
	});
	
	$("#submitSearch").click(function() {
		var search_phone = $("#search_phone").val();
		var search_first = $("#search_first_name").val();
		var search_lastn = $("#search_last_name").val();
		var search_datef = $("#selected_from_date").text();
		var search_datet = $("#selected_to_date").text();
		var adv_settings = '';
		var adv_daterang = '';
		
		if ($(".adv").is(":visible")) {
			if ($("#searchByDate").is(":checked")) {
				adv_daterang = "&from_date=" + search_datef + "&to_date=" + search_datet;
			}
			adv_settings = $(".advanceSearch").serialize() + adv_daterang;
		}
		
		$("#lead_search_placeholder").empty().html("<center><br /><img src=\"<? echo $base; ?>img/goloading.gif\" /></center>");
		$('#boxSearch').animate({'top':'-2550px'},500);
		$('#overlaySearch').fadeOut('slow');
		
		$.post('<?=$base ?>/index.php/go_search_ce/search_lead/', { phone_number: search_phone, first_name: search_first, last_name: search_lastn, advance: adv_settings}, function(data) {
			var string_array = data.split("|||");
			$("#search_count").val(string_array[0]);
			$("#lead_search_placeholder").empty().html(string_array[1]);
		});
	});
	
	$(".basicSearch,.advanceSearch").blur(function() {
		var basicID = $(this).val();
		var newVal = '';
		if ($(this).attr("name") == "email") {
			newVal = basicID.replace(/[^a-zA-Z 0-9\@\.]+/g,'');
		} else {
			newVal = basicID.replace(/[^a-zA-Z 0-9]+/g,'');
		}
		$(this).val(newVal);
	});
	
	$("#search_phone,input[name=lead_id]").keydown(function(event) {
		// Allow: backspace, delete, tab, escape, and enter
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
			// Allow: Ctrl+A
		       (event.keyCode == 65 && event.ctrlKey === true) || 
			// Allow: home, end, left, right
		       (event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault(); 
			}   
		}
 	});
	
	$("#submit_search").click(function() {
		$('#overlaySearch').fadeIn('fast');
		$('#boxSearch').css({'width': '600px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
		$('#boxSearch').animate({
			top: "-70px"
		}, 500);
	});
	
	//Clear Search Count
	$("#search_count").val(0);
	
	$("#log-collapse").click(function() {
		if ($("#collapsible").is(":hidden")) {
			$("#collapsible").show();
			$(this).html("Other Info [-]");
		} else {
			$("#collapsible").hide();
			$(this).html("Other Info [+]");
		}
	});
	
	$("#submitLeadInfo").click(function() {
		var leadid = $("#leadinfo_lead_id").text();
		var basicLeadInfo = $(".basicLeadInfo").serialize();
		var advanceLeadInfo = '';
		if ($(".advLeadInfo").is(":visible")) {
			advanceLeadInfo = $(".advanceLeadInfo").serialize();
		}
		
		$.post('<?=$base ?>/index.php/go_list/update_lead', { leadid: leadid, basic: basicLeadInfo, advance: advanceLeadInfo }, function(data)
		{
			
		});
	});
        
    } 
); 
</script>
<script type="text/javascript" >

function checkmes(){
	var leadfile = document.getElementById('leadfile').value;	
	var leadfile2 = document.getElementById('leadfile').value;	
	
	var lead_file = $('#leadfile').val();
                var valid_extensions = /(\.xls|\.xlsx|\.csv|\.ods|\.sxc)$/i;
                
                if (lead_file.length < 1)
                {
                        alert('<? echo lang('go_Pleaseincludealeadfile'); ?>.');
                        return false;
                }
                else
                {
                        if (valid_extensions.test(lead_file))
                        {
                                $('.progressBar').show();
                                $('#uploadleads').submit();
                                $('#box').css('position','absolute');
                        }
                        else
                        {
                                alert('<? echo lang('go_Uploadedfileisinvalid'); ?>: '+lead_file+'<br /><br /><? echo lang('go_FilemustbeinExcelformatxlsxlsxorinCommaSeparatedValuescsv'); ?>.');
                                return false;
                        }
                }

	
}

function uploadimg() {
	document.getElementById('loadings').innerHTML= "<img src=\"<? echo $base; ?>img/goloading.gif\" />";	
}
 
function gotopage(page) {
	var search_phone = $("#search_phone").val();
	var search_first = $("#search_first_name").val();
	var search_lastn = $("#search_last_name").val();
	var search_datef = $("#selected_from_date").text();
	var search_datet = $("#selected_to_date").text();
	var adv_settings = '';
	var adv_daterang = '';
		
	if ($(".adv").is(":visible")) {
		if ($("#searchByDate").is(":checked")) {
			adv_daterang = "&from_date=" + search_datef + "&to_date=" + search_datet;
		}
		adv_settings = $(".advanceSearch").serialize() + adv_daterang;
	}
	
	$("#lead_search_placeholder").empty().html("<center><br /><img src=\"<? echo $base; ?>img/goloading.gif\" /></center>");
	
	$.post('<?=$base ?>/index.php/go_search_ce/search_lead/', { page: page, phone_number: search_phone, first_name: search_first, last_name: search_lastn, advance: adv_settings}, function(data) {
		var string_array = data.split("|||");
		$("#search_count").val(string_array[0]);
		$("#lead_search_placeholder").empty().html(string_array[1]);
	});
}

function get_leadInfo(leadid) {
	$("#advLeadInfo").hide();
	$("#collapsible").hide();
	$("#log-collapse").html("<? echo lang('go_OtherInfo'); ?> [+]");
	$('#overlayLeadInfo').fadeIn('fast');
	$('#boxLeadInfo').css({'width': '1024px', 'left': '3%', 'right': '5%', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#boxLeadInfo').animate({
		top: "-70px"
	}, 500);
	
	//$("#overlayContentLeadInfo").empty().html("<center><br /><img src=\"<? echo $base; ?>img/goloading.gif\" /><br /><br /></center>");
	$("#overlayLoadingLeadInfo").show();
	$("#overlayContentLeadInfo").hide();
	$.post('<?=$base ?>/index.php/go_search_ce/leadinfo/', { leadid: leadid}, function(data)
	{
		$("#overlayLoadingLeadInfo").hide();
		$("#overlayContentLeadInfo").show();
		if(data.indexOf("Error") === -1){
			var $result = JSON.parse(data);
	
			$("#leadinfo_lead_id").empty().append($result[0].lead_id);
			$("#leadinfo_list_id").empty().append($result[0].list_id);
			//$("#leadinfo_status").empty().append($result.status);
			$("#leadinfo_status").val($result[0].status).prop("selected",true);
			$("#leadinfo_fullname").empty().append($result[0].first_name+" "+$result[0].last_name);
			$("#leadinfo_first_name").empty().val($result[0].first_name);
			$("#leadinfo_last_name").empty().val($result[0].last_name);
			$("#leadinfo_phone_code").empty().val($result[0].phone_code);
			$("#leadinfo_phone_number").empty().val($result[0].phone_number);
			$("#leadinfo_address1").empty().val($result[0].address1);
			$("#leadinfo_city").empty().val($result[0].city);
			$("#leadinfo_state").empty().val($result[0].state);
			$("#leadinfo_postal_code").empty().val($result[0].postal_code);
			$("#leadinfo_comments").empty().val($result[0].comments);
			$("#leadinfo_alt_phone").empty().val($result[0].alt_phone);
			$("#leadinfo_email").empty().val($result[0].email);
			$("#leadinfo_user").empty().append($result[0].user);
			//$("#leadinfo_download").attr("href",protocol+'//'+host+'/index.php/go_search_ce/download/'+$result[0].lead_id+'/csv');
			leadinfo(leadid);
			//wizard($(".message-box2"));
			//
			//if($result[1]){
			//	$("#leadinfo_user").val($result[1].user).prop("selected",true);
			//	$schedDate = $result[1].callback_time.split(" ")[0].split("-");
			//	$time = $result[1].callback_time.split(" ")[1].split(":");
			//	$("#appointment_year").val($schedDate[0]).prop("selected",true);
			//	$("#appointment_month").val($schedDate[1]).prop("selected",true);
			//	$("#appointment_day").val($schedDate[2]).prop("selected",true);
			//	$("#appointment_hour").val($time[0]).prop("selected",true);
			//	$("#appointment_min").val($time[1]).prop("selected",true);
			//	$("#callbackid").empty().val($result[1].callback_id);
			//	if($result[1].recipient === "ANYONE"){
			//		$("#touser").attr("value","anytouser");
			//	}else{
			//		$("#touser").attr("value","usertouser");
			//	}
			//} else {
			//	$(".callback").css("display","none");
			//}
	
		} else {
		     alert(data);
		     return false;
		}
	
	});
}

function view_recording(leadid) {
	$('#overlayRecordings').fadeIn('fast');
	$('#boxRecordings').css({'width': '1024px', 'left': '3%', 'right': '5%', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#boxRecordings').animate({
		top: "-70px"
	}, 500);
	
	$("html, body").animate({ scrollTop: 0 }, "slow");
	$("#recording_output").empty();
	$("#rec_lead_id").empty();
	$("#overlayLoadingRecordings").show();
	
	$.post('<?=$base ?>index.php/go_search_ce/view_recordings/', { leadid: leadid }, function(data) {
		$("#overlayLoadingRecordings").hide();
		$("#recording_output").html(data);
		$("#rec_lead_id").html("<? echo lang('go_RecordingsforthisLeadID'); ?>: "+leadid);
	});
}
 
function leadinfo(leadid){
	$("#calls-to-this-lead").find("div.user-tbl-container").empty().load('<?=base_url() ?>index.php/go_search_ce/calls/'+leadid);
	$("#closer-records").find("div.user-tbl-container").empty().load('<?=base_url() ?>index.php/go_search_ce/closerlog/'+leadid);
	$("#agent-log").find("div.user-tbl-container").empty().load('<?=base_url() ?>index.php/go_search_ce/agentlog/'+leadid);
	$("#recording").find("div.user-tbl-container").empty().load('<?=base_url() ?>index.php/go_search_ce/leadrecord/'+leadid);
}
</script>
<!-- end Javascript section -->

		<!-- CSS section -->
<link href="<?=base_url()?>css/go_search/go_search_ce.css" rel="stylesheet" type="text/css">
<style type="text/css">
			
			a.back{
	            width:256px;
	            height:73px;
	            /*position:fixed;*/
	            bottom:15px;
	            right:15px;
/*	            background:#fff url(codrops_back.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
	        }
	        a.activator{
	            width:153px;
	            height:150px;
	          /*  position:absolute;
	            top:0px;
	            left:0px;
/*	            background:#fff url(clickme.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
			font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;            
	        }
	        /* Style for overlay and box */
	        .overlay{
	            background:transparent url(../../img/images/go_list/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
	        }

		.overlayadd{
                    background:transparent url(../../../img/images/go_list/overlay.png) repeat top left;
                    position:fixed;
                    top:0px; 
                    bottom:0px;
                    left:0px; 
                    right:0px;
                    z-index:100;
                }   
	        .box{
	            /*position:fixed;*/
	            position:absolute;
	            top:-650px;
/*	            top:-200px;*/
	            left:20%;
	            right:30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding:20px;
				display: none;
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 50%;
	            
	        }

                .boxcopylist{
                     position:fixed;
                    top:-550px;
/*                  top:-200px;*/
                    left:20%;
                    right:30%;
                    background-color: white;
                    color:#7F7F7F;
                    padding: 20px 20px 5px 20px;
                  /*  border:2px solid #ccc;
                    -moz-border-radius: 20px;
                    -webkit-border-radius:20px;
                    -khtml-border-radius:20px;
                    -moz-box-shadow: 0 1px 5px #333;
                    -webkit-box-shadow: 0 1px 5px #333;*/

                    -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
                    /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
                    z-index:101;
                    width: 50%;
                        }

	        .boxaddlist{
	             position:absolute;
	            top:-3000px;
/*	            top:-200px;*/
	            left:18%;
	            right:25%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 55%;
	        	}
	        	
	        	.boxviewlist{
	             position:absolute;
	            top:-550px;
/*	            top:-200px;*/
	            left:30%;
	            right:30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding:20px;
	          	/*  
	          	border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
	            z-index:101;
	            width: 40%;
	        	}
	        	
	        .box h1{
	            border-bottom: 1px dashed #7F7F7F;
	            margin:-20px -20px 0px -20px;
	            padding:50px;
	            background-color:#FFEFEF;
	            color:#EF7777;
	            -moz-border-radius:20px 20px 0px 0px;
	            -webkit-border-top-left-radius: 20px;
	            -webkit-border-top-right-radius: 20px;
	            -khtml-border-top-left-radius: 20px;
	            -khtml-border-top-right-radius: 20px;
	        }

                .boxview{
                    position:absolute;
                    top:-550px;
/*                  top:-200px;*/
                    left:10%;
                    right:10%;
/*                  background-color:#fff;
                    color:#7F7F7F;*/
                    background-color: white;
                    color:#7F7F7F;
                    padding:20px;
/*                  border:2px solid #ccc;
                    -moz-border-radius: 20px;
                    -webkit-border-radius:20px;
                    -khtml-border-radius:20px;
                    -moz-box-shadow: 0 1px 5px #333;
                    -webkit-box-shadow: 0 1px 5px #333;*/
                     -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
                    z-index:101;
                }

	        
	        a.boxclose{
	            float:right;
	            width:26px;
	            height:26px;
	            background:transparent url(<? echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	            margin-top:-30px;
	            margin-right:-30px;
	            cursor:pointer;
	        }
	        
			.nowrap { 
			   background: white;
 			   font-size: 12px;
			}
			table {
			    /*border-top: 1px dashed rgb(208,208,208);*/
			}
			/*td {
			    font-size : 10px;
			}*/

			.tabnoborder {
				border: none;
			}
			
			.title-header {
				color: #333;
				font-size: 16px;	
			}
			
			.modify-value {
				font-weight: bold;
				color: #7f7f7f;
			}
			.lblback {
				background:#E0F8E0;
			}
			
			.tableedit {
				border-top: 0px double; rgb(208,208,208);
			}
			

			.tablenodouble {
				border-top: 0px double; rgb(208,208,208);
			}
			
			td {
			font-family: Verdana,Arial,Helvetica,sans-serif; 
			font-size: 13px; 
			font-stretch: normal;		
			}
			
			.thheader {
        		background-repeat: no-repeat;
        		background-position: center right;
        		cursor: pointer;
			font-family: Verdana,Arial,Helvetica,sans-serif; 
			font-size: 13px; 
			font-stretch: normal;		
			
			}     
			
			.progress { position:relative; width:200px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
			.bar { background-color: #B4F5B4; width:0%; height:15px; border-radius: 3px; }
			.percent { position:absolute; display:inline-block; top:2px; left:45%; font-size:10px; }
  


			
			.tr1 td{ background:#E0F8E0; color:#000; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal; border-top: 1px dashed rgb(208,208,208); }
			.tr2 td{ background:#EFFBEF; color:#000; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;  border-top: 1px dashed rgb(208,208,208); }
			.tredit td{ background:#EFFBEF; color:#000; font-family: font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal; border-bottom: 1px dashed rgb(208,208,208); }   
			
/*			A:link {text-decoration: none; color: black;}
			A:visited {text-decoration: none; color: black;}
			A:active {text-decoration: none; color: black;}
			A:hover {text-decoration: underline overline; }
*/		
			A#searchcallhistory:link {text-decoration: none; color: black;}
			A#searchcallhistory:visited {text-decoration: none; color: black;}
			A#searchcallhistory:active {text-decoration: none; color: black;}
			A#searchcallhistory:hover {text-decoration: none; font-weight:bold;}
			
			A#listidlink:link {text-decoration: none; color: black;}
			A#listidlink:visited {text-decoration: none; color: black;}
			A#listidlink:active {text-decoration: none; color: black;}
			A#listidlink:hover {text-decoration: none; color: red}

			A#activator:link {text-decoration: none; color: #555555;}
			A#activator:visited {text-decoration: none; color: #555555;}
			A#activator:active {text-decoration: none; color: #555555;}
			A#activator:hover {text-decoration: none; color: #555555}

			A#newcustomfield:link {text-decoration: none; color: #555555;}
			A#newcustomfield:visited {text-decoration: none; color: #555555;}
			A#newcustomfield:active {text-decoration: none; color: #555555;}
			A#newcustomfield:hover {text-decoration: none; color: #555555}
			
			A#submit_dnc:link {text-decoration: none; color: #555555;}
			A#submit_dnc:visited {text-decoration: none; color: #555555;}
			A#submit_dnc:active {text-decoration: none; color: #555555;}
			A#submit_dnc:hover {text-decoration: none; color: #555555}
						
			
/*			.go_action_submenu, .go_status_submenu, .go_camp_status_submenu {
	        padding: 3px 10px 3px 5px;
	        margin: 0px;
			}

	
			#selectAction, #selectStatusAction, #selectCampStatusAction {
	        -webkit-touch-callout: none;
	        -webkit-user-select: none;
	        -khtml-user-select: none;
	        -moz-user-select: none;
	        -ms-user-select: none;
	        user-select: none;
        
			}*/
			
			.go_action_menu{
        z-index:999;
        position:absolute;
        top:188px;
        border:#CCC 1px solid;
        background-color:#FFF;
        display:none;
        cursor:pointer;
}

#go_action_menu ul, #go_status_menu ul, #go_camp_status_menu ul{
        list-style-type:none;
        padding: 1px;
        margin: 0px;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
}

#selectAction, #selectStatusAction, #selectCampStatusAction {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
}

.go_action_submenu, .go_status_submenu, .go_camp_status_submenu{
        padding: 3px 10px 3px 5px;
        margin: 0px;
}

html {overflow-y: scroll;}
			
			.button:hover{
	font-weight:bold;
}

img.desaturate{
    filter: grayscale(100%); /* Current draft standard */
    -webkit-filter: grayscale(100%); /* New WebKit */
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%); /* Not yet supported in Gecko, Opera or IE */
    filter: url(<?php echo $base;?>img/resources.svg#desaturate); /* Gecko */
    filter: gray; /* IE */
    -webkit-filter: grayscale(1); /* Old WebKit */
}

#overlayDNC,#overlaySearch,#overlayLeadInfo,#overlayRecordings{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#boxDNC,#boxSearch,#boxLeadInfo,#boxRecordings{
	position:absolute;
	top:-2550px;
	left:14%;
	right:14%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

#closeboxDNC,#closeboxSearch,#closeboxLeadInfo,#closeboxRecordings{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#overlayLoadingLeadInfo,#overlayLoadingRecordings{
	text-align:center;
	display:none;
}

.go_dnc_menu,.go_lead_search_menu{
	z-index:999;
	position:absolute;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

#go_dnc_menu ul,#go_lead_search_menu ul{
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_dnc_submenu,.go_lead_search_submenu{
	padding: 3px 10px 3px 5px;
	margin: 0px;
}

table.tablesorter .even {
	background-color: #EFFBEF;
}
table.tablesorter .odd {
	background-color: #E0F8E0;
}

.listdownload{cursor:pointer;text-align: right;}
.listdownload a{color:#7A9E22;}
.listdownload a:hover {font-weight:bold;}

.listinfo{
   width:100%;
}

.listinfolabel{width:30%;}

.advancedFields{
	display:none;
}

#advancedFieldLink{
	font-size:10px;
	color:#7A9E22;
	font-weight:bold;
	cursor:pointer;
}

#showAllLists, #showAllDNCLists {
	color: #F00;
	font-size: 10px;
	cursor: pointer;
}

#widgetField {
	width: 220px;
	cursor: pointer;
	height: 24px;
	top: 1px;
	right: -5px;
}

#widgetField span {
	line-height: 20px;
	position: relative;
	left: 0px;
}

.buttons {
	color: #7A9E22;
	cursor: pointer;
}

.buttons:hover {
	font-weight: bold;
}

.adv,.advLeadInfo {
	display: none;
	background-color: #E0F8E0;
}
</style>
<!-- end CSS section -->
<?php

$countthis = count($lists);
if($countthis > 0){
echo "<body onload='genListID()'>";
} else {	
?>	
<body onload='addlistoverlay(); genListID()'>
<?php
}
?>

<!-- begin body -->
<div id='outbody' class="wrap toolTip">
    <div id="icon-list" class="icon32 toolTip"></div>
    <h2><? echo lang('go_Lists'); ?> <img class="toolTip" title="<? echo lang('go_ListsTooltip'); ?>" style="cursor:default; width:15px;" src="/img/status_display_i.png"></h2>
    <!-- search -->
    <div id="singrp" align="right" style="float: right; width: 50%; margin-right:30px; margin-top: -35px; display: block;">
	<form  method="POST" id="go_search_list" name="go_search_list">
                <span id="showAllLists" style="display: none">[Clear Search]</span>
		<input type="hidden" id="action" name="action" value="action_search_list">
		<input type="hidden" id="typeofsearch" name="typeofsearch" value="lists">
		<input type="text" value="<?=$search ?>" name="search_list" id="search_list" size="20" maxlength="100" placeholder="<? echo lang('go_SearchLists'); ?>">
		&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="submit_search_list" style="cursor: pointer;" />
	</form>
    </div>
    <div id="searchDNC" align="right" style="position: absolute; float: left; width: 97%; margin-top: -35px;display:none;"><span id="showAllDNCLists" style="display: none">[<? echo lang('go_ClearSearch'); ?>]</span> <input type="text" id="search_dnc" placeholder="<? echo lang('go_SearchDNCNumbers'); ?>" size="20" maxlength="100" /> <img src="<?=base_url()."img/spotlight-black.png"; ?>" id="submit_search_dnc" style="cursor: pointer;" /></div>
    <!-- search -->
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width: 99%; min-width: 1200px;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox" >
                        <div>
                            
   <span><a id="activator" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer; font-family: Verdana,Arial,Helvetica,sans-serif;" onClick="addlistoverlay();" title="<? echo lang('go_CreateNewList'); ?>"><b><? echo lang('go_CreateNewList'); ?></b>  </a></span>

			<!--<span><a id="copycustomfield"  class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none; font-family: Verdana,Arial,Helvetica,sans-serif;" onClick="copyview();" title="Copy Custom Field" style="margin-left: 0px; font-family: Verdana,Arial,Helvetica,sans-serif;">Copy Custom Field</a></span> <span><a id="pipelink" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none; ">|</a></span>-->
			
			<span><a id="newcustomfield"  class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none;font-family: Verdana,Arial,Helvetica,sans-serif; " onClick="viewadd();" title="<? echo lang('go_CreateNewField'); ?>" style="margin-left: 0px;"><? echo lang('go_CreateCustomField'); ?></a></span>
			<span>				
                        <a id="submit_dnc" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none;font-family: Verdana,Arial,Helvetica,sans-serif;" title="<? echo lang('go_AddDeleteDNCNumbers'); ?>"><b><? echo lang('go_AddDeleteDNCNumbers'); ?></b>  </a>
			</span>
			
			<span>				
                        <a id="submit_search" class="rightdiv toolTip" style="text-decoration: none; cursor:pointer;display: none;font-family: Verdana,Arial,Helvetica,sans-serif;" title="<? echo lang('go_SearchForALead'); ?>"><b><? echo lang('go_SearchForALead'); ?></b>  </a>
			</span>
			
                        </div>
                        <div class="hndle" style="height:13px" onclick="return false;">
                                   <!-- <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">List Listings</span> -->
	                    	</div>
                        <div class="inside inside-tab">

<div id="tabs" class="tab-container" style="border: none;">
<ul style="background: transparent; border: none;">
		<li><a href="#tabs-1" id="atab1" title="<? echo lang('go_ShowListsTabTooltip'); ?>"  class="tab toolTip" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;"><? echo lang('go_ShowLists'); ?></a></li>
		<li><a href="#tabs-2" id="atab2" title="<? echo lang('go_CustomFieldsTabTooltip'); ?>" class="tab toolTip" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;"><? echo lang('go_CustomFields'); ?></a></li>
		<li><a href="#tabs-3" id="atab3" title="<? echo lang('go_LoadLeads'); ?>" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;"><? echo lang('go_LoadLeads'); ?></a></li>
		<li><a href="#tabs-5" id="atab5" title="<? echo lang('go_DNCNumbers'); ?>" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;"><? echo lang('go_DNCNumbers'); ?></a></li>
		<!--<li><a href="#tabs-6" id="atab6" title="Custom Fields Settings" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">Custom Fields Settings</a></li>-->
		<li><a href="#tabs-7" id="atab7" title="Lead Search" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;">Lead Search</a></li>
	</ul>
	

                <div class="overlay" id="overlaycopy" style="display:none;"></div>
                                <div class="boxcopylist" id="boxcopy">
                                        <a class="boxclose" id="boxclosecopy"></a>
                                	<div id="small_step_number" style="float:right; margin-top: -5px;">
                                        	<img src="<?=$base?>img/step1-nav-small.png">
                                	</div>
                                	<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
                                        	<font color="#333" style="font-size:16px;"><b><? echo lang('go_CustomFieldWizard__CopyCustomField'); ?></b></font>
                                	</div>

<!--                                        <form method="POST" name="formcopyfields" id="formcopyfields">
        				<input type=hidden name=action value=COPY_FIELDS_SUBMIT>
				
                                        <br>
                                                <table class="tableedit" width="100%" id="tbloverlaycopy">
                                                <tr>
                                                <td valign="top" style="width:20%">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">
        						<table class="tablenodouble" width="100%">
								<tr>
									<td>
        									<label class="modify-value">Copy Fields to Another List</label>
        								</td>
									<td>
        								<select name="source_list_id" id="source_list_id">
									<?php
										//foreach($lists as $listsInfo){
										//	echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name."</option>";
										//}
									?>
        								</select>
									</td>
								</tr>
								<tr>
									<td>
        									<label class="modify-value">List ID to Copy Fields From:</label>
									</td>
									<td>
        								<select name="to_list_id" id="to_list_id">
									<?php
										//foreach($lists as $listsInfo){
										//	echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name."</option>";
										//}
									?>
        								</select>
									</td>
								</tr>
								<tr>
									<td>
										<label class="modify-value">Copy Option:</label>
									</td>
									<td>
									<select name="copy_option" id="copy_option">
        								<option selected>APPEND</option>
        								<option>UPDATE</option>
        								<option>REPLACE</option>
        								</select>
									</td>
								</tr>
							</table>
						</td>
						</tr>
                                        	<tr>
                                                <td align="right" colspan="9">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
                                                    <a id="searchcallhistory" style="cursor: pointer;" onclick="copysubmit();"><font color="#7A9E22">Submit</font></a>
                                                </div>

                                                </td>
                                        	</tr>
                                        	</table>
					</form>-->

                                </div>

                <div class="overlay" id="overlayview" style="display:none;"></div>
                                <div class="boxview" id="boxview">
                                        <center>
                                                        <a class="boxclose" id="boxcloseview"></a>
                                                        <span id="viewme"></span>
                                        </center>
                                </div>
	
		<div class="overlay" id="overlayadd" style="display:none;"></div>
                                <div class="boxaddlist" id="boxaddcustom">

                                <a class="boxclose" id="boxclose"></a>
                                <div id="small_step_number" style="float:right; margin-top: -5px;">
                                        <img src="<?=$base?>img/step1-nav-small.png">
                                </div>
                                <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
                                        <font color="#333" style="font-size:16px;"><b><? echo lang('go_CustomFieldWizard__CreateCopyCustomField'); ?></b></font>

                                </div>

<!-- copy custom -->
									<span id="spancopycustom" style="display: none;">

                                 <form method="POST" name="formcopyfields" id="formcopyfields">
        																									<input type=hidden name=action value=COPY_FIELDS_SUBMIT>
				
                                        <br>
                                                <table class="tableedit" width="100%" id="tbloverlaycopy">
                                                <tr>
                                                <td valign="top" style="width:20%">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">
        																																								
        																																										<table class="tablenodouble" width="100%">
        						   																																			<tr><td> <label class="modify-value">Process: </label></td>
							    																																													<td> 
								                                                     <select name="copyselectlist" id="copyselectlist" Onchange="selectlistidcopy();">
									                                                      <option value="" selected><? echo lang('go_CopyCustomField'); ?></option>
									                                                      <option value="createcustomselect"><? echo lang('go_CreateCustomField'); ?></option>
																																																													</select> 		
							    																																													</td>
							    																																									</tr>
																																																				<tr>
																																																								<td style="white-space:nowrap;"><label class="modify-value">List ID to Copy Fields From:</label></td>
																																																								<td>
        																																																				<select name="source_list_id" id="source_list_id" style="width: 250px">
																																																												<?php
																																																												foreach($dropactivecustom as $droplistsInfo){
										                                                  	echo "<option value='$droplistsInfo->listids'>".$droplistsInfo->listids." - ".$droplistsInfo->list_name."</option>";
																																																												}
																																																												?>
        																																																				</select>
																																																								</td>
																																																				</tr>
																																																				<tr>
																																																								<td>
        																																																				<label class="modify-value">Copy Fields to Another List:</label>
																																																								</td>
																																																							<td>
        								<select name="to_list_id" id="to_list_id" style="width: 250px">
									<?php
										foreach($listIDs as $listsInfo){
											echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name."</option>";
										}
									?>
        								</select>
									</td>
								</tr>
								<tr>
									<td>
										<label class="modify-value"><? echo lang('go_CopyOption'); ?>:</label>
									</td>
									<td>
									<select name="copy_option" id="copy_option">
									<option selected><? echo lang('go_APPEND'); ?></option>
        								<option><? echo lang('go_UPDATE'); ?></option>
        								<option><? echo lang('go_REPLACE'); ?></option>
        								</select>
									</td>
								</tr>
							</table>
						</td>
						</tr>
                                        	<tr>
                                                <td align="right" colspan="20">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
                                                    <a id="searchcallhistory" style="cursor: pointer;" onclick="copysubmit();"><font color="#7A9E22"><? echo lang('go_Submit'); ?></font></a>
                                                </div>

                                                </td>
                                        	</tr>
                                        	</table>
					</form>
					</span>


<!-- end copy custom -->





     
     
     
                             
                                <span id="spanaddcustom" style="display: block;">

                                                
						<br>
                                        	<table class="tableedit" width="100%" id="tblboxaddcustom">
                                        	<tr>
                                                <td valign="top" style="width:20%">
                                                                        <div id="step_number" style="padding:0px 10px 0px 30px;">
                                                                <img src="<?=$base?>img/step1-trans.png">
                                                                </div>
                                                </td>
                                                <td style="padding-left:50px;" valign="top" colspan="2">

                                                <form method="POST" name="formfields" id="formfields">
                                                <input type="hidden" name="fakeselectlistidname" id="fakename">
                                                <input type="hidden" name="field_id" id="field_id">
                                                <table class="tablenodouble" width="100%">
                                                        <tr><td> <label class="modify-value"><? echo lang('go_ListID_'); ?></label></td>
							    <td> 
								<select title="<? echo lang('go_ListIDdefinesthelistIDthatwillcontaintheleadfile'); ?>"  name="hide_listid" id="hide_listid" Onchange="selectlistid();">
								 <option value="" selected></option>
									<option value="copycustomselect"><? echo lang('go_CopyCustomField'); ?></option>
									<?php
									foreach($listIDs as $listsInfo){
										echo "<option value='$listsInfo->list_id'>".$listsInfo->list_id." - ".$listsInfo->list_name." </option>";				
									}
									?>
								</select> 		
							    </td></tr>
                                                        <tr><td> <label class="modify-value"><? echo lang('go_Labels'); ?>:</label></td><td> <input type="text" name="field_label" id="field_label"> </td></tr>
                                                        <tr><td align="left">
                                                                        <label class="modify-value"><? echo lang('go_Rank'); ?>:</label>
														</td><td align="left">
									<span id="countsd">
										<select name='field_rank' id='field_rank'>
											<option value="1">1</option>
										</select> 
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                								<label class='modify-value'><? echo lang('go_Order'); ?>:</label>
                								<select name='field_order' id='field_order'>
											<option value="1">1</option>
										</select>
									</span>	
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value"><? echo lang('go_Name_'); ?>:</label></td><td> <input type="text" name="field_name" id="field_name"></td></tr>
                                                        <tr><td> <label class="modify-value"><? echo lang('go_Position'); ?>:</label> </td><td><select name="name_position" id="name_position">
                                                                                                                <option value="LEFT">LEFT</option>
                                                                                                                <option value="TOP">TOP</option>
                                                                                                        </select>
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value"><? echo lang('go_Description_'); ?>:</label> </td><td><input type="text" name="field_description" id="field_description"> </td></tr>
                                                        <tr><td> <label title="<? echo lang('go_TypeTooltip'); ?>"  class="modify-value"><? echo lang('go_Type'); ?></label></td><td> <select title="<? echo lang('go_TypeTooltip'); ?>"  name="field_type" id="field_type">
                                                                                <option value="TEXT">TEXT</option>
                                                                                <option value="AREA">AREA</option>
                                                                                <option value="SELECT">SELECT</option>
                                                                                <option value="MULTI">MULTI</option>
                                                                                <option value="RADIO">RADIO</option>
                                                                                <option value="CHECKBOX">CHECKBOX</option>
                                                                                <option value="DATE">DATE</option>
                                                                                <option value="TIME">TIME</option>
                                                                                <option value="DISPLAY">DISPLAY</option>
                                                                                <option value="SCRIPT">SCRIPT</option>
                                                                                </select>
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value"><? echo lang('go_Options'); ?>:</label></td><td> <textarea name="field_options" id="field_options" style="resize: none;" ROWS="2" COLS="40"></textarea>
                                                        </td></tr>
                                                        <tr><td> <label class="modify-value"><? echo lang('go_OptionPosition'); ?>:</label> </td><td><select name="multi_position" id="multi_position">
                                                                                <option value="HORIZONTAL">HORIZONTAL</option>
                                                                                <option value="VERTICAL">VERTICAL</option>
                                                                                </select>
                                                        </td></tr>
                                                         <tr><td><label title="<? echo lang('go_FieldSizeTooltip'); ?>"  class="modify-value"><? echo lang('go_FieldSize'); ?>:</label></td><td> <input title="<? echo lang('go_FieldSizeTooltip'); ?>"  type="text" name="field_size" id="field_size"> </td></tr>
                                                        <tr><td><label title="<? echo lang('go_FieldMaxTooltip'); ?>"  class="modify-value"><? echo lang('go_FieldMax'); ?>:</label></td><td> <input title="<? echo lang('go_FieldMaxTooltip'); ?>"  type="text" name="field_max" id="field_max"> </td></tr>
                                                        <tr><td><label class="modify-value"><? echo lang('go_FieldDefault'); ?>:</label></td><td> <input type="text" name="field_default" id="field_default"> </td></tr>
                                                        <tr><td><label class="modify-value"><? echo lang('go_FieldRequired'); ?>:</label></td><td> <select name="field_required" id="field_required">
                                                                                        <option value="Y"><? echo lang('go_YES');?></option>
                                                                                        <option value="N"><? echo lang('go_NO'); ?></option>
                                                                                </select>
                                                        </td></tr>
                                                        <!--<tr><td align="center" colspan="2">

                                                                <span id="btnsub"></span>
                                                                <input type="button" name="btnclose" id="btnclose" class="btnclose" value="close">
                                                        </td></tr>-->
                                        </table>
                                        </tr>
                                        <tr>
                                                <td align="right" colspan="9">
                                                <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
                                                    <!--<a id="searchcallhistory" style="cursor: pointer;" onclick="document['formfields'].submit()"><font color="#7A9E22">Submit</font></a>-->
                                                    <a id="searchcallhistory" style="cursor: pointer;" onclick="addsubmit();"><font color="#7A9E22">Submit</font></a>
                                                </div>

                                                </td>
                                        </tr>



                                        </table>

</form></span>
                                </div>	

<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxaddlist" id="boxaddlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeadd();"></a>
				<!-- start add -->
				<div id="small_step_number" style="float:right; margin-top: -5px;">
					<img src="<?=$base?>img/step1-nav-small.png">
				</div>
				<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
					<font color="#333" style="font-size:16px;"><b><? echo lang('go_ListWizard__CreateNewList'); ?></b></font>
					
				</div>
				
				<div id="addlist" style="display: block;">
					<form  method="POST" id="go_listfrm" name="go_listfrm">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	<input type="hidden" id="addSUBMIT" name="addSUBMIT" value="addSUBMIT">
				   	
				   	
				  <!-- <div align="left" class="title-header">Create new list</div>-->
					<br>
					<table class="tableedit" width="100%">
					<tr>
						<td valign="top" style="width:20%">
									<div id="step_number" style="padding:0px 10px 0px 30px;">
								<img src="<?=$base?>img/step1-trans.png">
								</div>
						</td>
						<td style="padding-left:50px;" valign="top" colspan="2">
							<table width="100%">
								
								<tr>
									<td align="right"><label class="modify-value"><? echo lang('go_AutoGenerated'); ?>:&nbsp;&nbsp;&nbsp;</label></td>
									<td><input type="checkbox" id="auto_gen" name="auto_gen" onclick="showRow();" checked="checked"></td>
								</tr>
	                  					<tr>
									<td align="right"><label class="modify-value"><? echo lang('go_ListID_'); ?>:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><input type="text" name="list_id" id="list_id" size="12" maxlength="15">
									<label id="autogenlabel"><font size="1" color="red">(<? echo lang('go_numericonly'); ?>)</font></label> </td>
								</tr>
								<tr>
									<td align="right"><label class="modify-value"><? echo lang('go_ListName'); ?>:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><input type="text" name="list_name" id="list_name" size="30" maxlength="22">
									<font color="red" size="1" style="display: none;">(<? echo lang('go_alphanumericonly'); ?>)</font></td>
								</tr>
								<tr>
									<td align="right"><label class="modify-value"><? echo lang('go_ListDescription'); ?>:&nbsp;&nbsp;&nbsp;</label> </td>
									<td><input type="text" name="list_description" id="list_description" size="30" maxlength="255">
									<font color="red" size="1" style="display: none;">(<? echo lang('go_alphanumericonly'); ?>)</font></td>
								</tr>
								<tr>
									<td align="right"><label class="modify-value"><? echo lang('go_Campaign_'); ?>&nbsp;&nbsp;&nbsp;</label> </td>
									<td><span id="campaign_list">
											<select name="campaign_id" id="campaign_id" style="width:300px;">
									        
									<?php
		   		               		foreach($campaigns as $campaignInfo){
											$cid = $campaignInfo->campaign_id;
											$cname = $campaignInfo->campaign_name;
		                        		echo '<option value="'.$cid.'">'.$cid.'-'.$cname.'</option>';
										}
									?>
											</select></span>
											<span id="campaign_list_hidden" style="display:none"></span>
											</td>
								</tr>
			                	<tr>
									<td align="right"><label class="modify-value"><? echo lang('go_Active_'); ?>&nbsp;&nbsp;&nbsp;</label> </td>
									<td><select size="1" name="active"><option>Y</option><option>N</option></select></td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
		                	</table>
						</td>
					</tr>
					<tr>
						<td align="right" colspan="9">
						<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
						<!--<a id="searchcallhistory" style="cursor: pointer;" onclick="document['go_listfrm'].submit()"><font color="#7A9E22">Submit</font></a>-->
						<a id="searchcallhistory" style="cursor: pointer;" onclick="checklistadd();"><font color="#7A9E22"><? echo lang('go_Submit'); ?></font></a>
						</div>		
											
						</td>			  
					</tr>
					
					
                  
					</table>	
					</form>
					</div>
				</div>


	<!-- edit -->
                                <div class="overlay" id="overlay" style="display:none;"></div>

                                <div class="box" id="box">
                                <center>

                                <a class="boxclose" id="boxclose" onclick="closeme();"></a>

                                <form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
                                        <input type="hidden" name="editlist" value="editlist">
                                        <input type="hidden" name="editval" id="editval">
                                        <input type="hidden" name="showvaledit" id="showvaledit" value="">
                                        <!--<input type="hidden" name="oldcampaignid" id="oldcampaignid" value="">-->


                                        <div id="listid_edit" align="left" class="title-header"> </div>
                                        <div align="left">
                                        <!--<label class="modify-value">Change Date:</label>-->
                                        <table width="100%">
                                                <tr><td align="left"><div id="cdates"></div></td><td align="right"><div id="lcdates"></div></td></tr>
                                        </table>


                                        </div>

                                        <table class="tableedit">
                                                        <tr>
                                                                <td><br><label class=""><? echo lang('go_Name_'); ?></label><div id="simula"></div> </td>
                                                                <td><input type="text" name="list_name" id="listname_edit" size="30" maxlength="30">
								<font color="red" size="1" style="display: none;">(<? echo lang('go_alphanumericonly'); ?>)</font></td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class=""><? echo lang('go_Description_'); ?></label></td>
                                                                <td><input type="text" name="list_description" id="listdesc_edit" size="30" maxlength="22">
								<font color="red" size="1" style="display: none;">(<? echo lang('go_alphanumericonly'); ?>)</font></td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class=""><? echo lang('go_Campaign_'); ?></label></td>
                                                                <td>
                                                                        <select size="1" name="campaign_id" id="campid_edit" style="width:300px;">
                                                                                <option disabled><? echo lang('go_SelectCampaign');  ?></option>
                                                                                <?php
                                                        foreach($campaigns as $campaignInfo){
                                                                                                $cid = $campaignInfo->campaign_id;
                                                                                                $cname = $campaignInfo->campaign_name;
                                                                echo '<option value="'.$cid.'">'.$cname.'</option>';
                                                                                }
                                                                                ?>
									</select>
                                                                </td>
                                                        </tr>

                                                        <tr>
                                                                <td><label class=""><? echo lang('go_ResetTimes'); ?>:</label> </td>
                                                                <td><input type="text" name="reset_time" id="restime_edit" size="30" maxlength="100"></td>
                                                        </tr>
                                                        <!--<tr>
                                                                <td><label class="modify-value">Change Date:</label> 
                                                                </td>
                                                                <td><div id="cdates"></div></td>
                                                        </tr>
                                                        <tr>
                                                                <td>
                                                        <label class="modify-value">Last Call Date: </label>
                                                        </td>
                                                        <td><div id="lcdates"></div></td>
                                                        </tr>-->
                                                        <tr>
                                                                <td><label class=""><? echo lang('go_ResetLeadCalledStatus'); ?>:</label></td>
                                                                <td>
                                                                        <select size="1" name="reset_list" id="reslist_edit">
                                                                                <option value="N">N</option>
                                                                                <option value="Y">Y</option>
                                                                        </select>
                                                                &nbsp;&nbsp;&nbsp;
                                                                 <label class=""><? echo lang('go_Active_'); ?>:</label>
                                                                        <select size="1" name="active" id="act_edit">
                                                                                <option value="Y">Y</option>
                                                                                <option value="N">N</option>
                                                                        </select>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class=""><? echo lang('go_AgentScriptOverride_'); ?></label> </td>
                                                                <td>
                                                                        <select size="1" name="agent_script_override" id="agcscp_edit">
                                                                                <?php
                                                                                         if($eagent_script_override!=null) {
                                                                                ?>
                                                                                         <option value="<?=$eagent_script_override?>"><?=$eagent_script_override?></option>                        
                                                                                <?
                                                                                         } else {
                                                                                ?>
											<option selected value=""> - </option>
                                                                                <?php
                                                                                         }
                                                                                ?>
                                                                                <option value="">NONE - INACTIVE</option>
                                                                        </select>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class=""><label class=""><? echo lang('go_CampaignCIDOverride_'); ?></label> </td>
                                                                <td><input type="text" name="campaign_cid_override" id="campcidover_edit" size="20" maxlength="20"></td>
                                                        </tr>
                                                        <!-- <tr>
                                                                <td>Answering Machine Message Override: </td>
                                                                <td><input type="text" name="am_message_exten_override" id="am_message_exten_override" size="50" maxlength="100" value="<?=$eam_message_exten_override?>"></td>
                                                        </tr> -->
                                                        <tr>
                                                                <td><label class=""><label class=""><? echo $DropInboundGroupOverride_; ?></label> </td>
                                                                <td>
                                                                        <select size="1" name="drop_inbound_group_override" id="drpinbovr_edit">
                                                                        <?php
                                                                                if($edrop_inbound_group_override!=null) {
                                                                        ?>
                                                                                <option value="<?=$edrop_inbound_group_override?>"><?=$edrop_inbound_group_override?></option>
                                                                        <?
                                                                                }
                                                                        ?>
                                                                                <option value="">NONE</option>
                                                                        </select>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td><label class=""><? echo lang('go_WebForm_'); ?></label> </td>
                                                                <td><input type="text" name="web_form_address" id="wbfrmadd_edit" size="50" maxlength="1055"></td>
                                                        </tr>
                                                        <tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
                                                        <tr>
                                                                <td colspan="2" align="center"> <br><label class=""><? echo lang('go_TransferConfNumberOverride'); ?></label> </td>
                                                        </tr>
                                                        <tr>
                                                                <td colspan="2">
                                                                <label class=""><? echo lang('go_Number'); ?> 1:</label> <input type="text" name="xferconf_a_number" id="xfer1" size="20" maxlength="50">
                                                                <label class=""><? echo lang('go_Number'); ?> 4:</label> <input type="text" name="xferconf_d_number" id="xfer4" size="20" maxlength="50">
                                                                <br>
                                                                <label class=""><? echo lang('go_Number'); ?> 2:</label> <input type="text" name="xferconf_b_number" id="xfer2" size="20" maxlength="50">
								<label class=""><? echo lang('go_Number'); ?> 5:</label> <input type="text" name="xferconf_e_number" id="xfer5" size="20" maxlength="50">
                                                                <br>
                                                                <label class=""><? echo lang('go_Number'); ?> 3:</label> <input type="text" name="xferconf_c_number" id="xfer3" size="20" maxlength="50">
                                                                </td>
                                                        </tr>
                                                        <tr><td colspan="2">&nbsp;</td></tr>
                                                        <tr><td colspan="2">&nbsp;</td></tr>
                                                        <tr>
                                                                <td colspan="2" align="center"><b><?php
                                                                echo "<a id=\"clickadvanceplus\" style=\"cursor: pointer;\" onclick=\"$('#statusid').css('display', 'block'); $('#clickadvanceplus').css('display', 'none'); $('#clickadvanceminus').css('display', 'block');  \" title=\"".lang('go_Clicktoviewreports')."\">[ + ] ".lang('go_STATUSESWITHINTHISLIST')." </a><a id=\"clickadvanceminus\" style=\"cursor: pointer; display: none;\" onclick=\"$('#statusid').css('display', 'none'); $('#clickadvanceplus').css('display', 'block'); $('#clickadvanceminus').css('display', 'none');\" title=\"".lang('go_Clicktoviewreports')."\">[ - ] ".lang('go_STATUSESWITHINTHISLIST')."</a>";
?></b></td>
                                                        </tr>
                                                        <tr>
                                                                <td colspan="2" align="center">
                                                                        <div id="stats"></div>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td align="center" colspan="2"><br>
                                                                <input type="button" name="editSUBMIT" class="button" style="cursor:pointer;border:0px;color:#7A9E22;" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">
<!--                                                            <input type="submit" name="editSUBMIT" value="MODIFY">                                                          -->
                                                                </td>

                                                        </tr>
                                                        <tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
                                        </table>
                                </form>
                                </center>
                                </div>
                                <!-- end edit -->



	<div id="tabs-1">

				<!-- LISTs TAB -->
				<div id="showlist" style="display: block;">
				
				<form name="showlistview" id="showlistview">
				<input type="hidden" name="showval" id="showval">
				
				<table id="listtableresult" class="tablesorter" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%;margin-top:3px;" > 
					<thead>
					<tr align="left" class="nowrap">
						<th class="thheader" style="padding-bottom:-1px;">&nbsp;&nbsp;<b><? echo lang('go_LISTID'); ?></b> </th>
						<th title="<? echo lang('go_NAMETooltip'); ?>"  colspan="" class="thheader" style="padding-bottom:-1px;"><b><? echo lang('go_NAME'); ?></b> </th>
						<th class="thheader" align="left" style="padding-bottom:-1px;"><b><? echo lang('go_STATUS'); ?></b> </th>
						<th class="thheader" style="padding-bottom:-1px;"><b><? echo lang('go_LASTCALLDATE'); ?></b> </th>
						<th title="<? echo lang('go_LEADSCOUNTTooltip'); ?>"  class="thheader" style="padding-bottom:-1px;"><b><? echo lang('go_LEADSCOUNT'); ?></b> </th>
						<th class="thheader" style="padding-bottom:-1px;"><b><? echo lang('go_CAMPAIGN'); ?></b> </th>
						<th colspan="3" class="thheader" style="width:7%;white-space: nowrap;padding-bottom:-1px;" align="right">
						<span class="toolTip" title="<? echo lang('go_ActionColumnTooltip'); ?>" style="cursor:pointer;" id="selectAction">&nbsp;<? echo lang('go_ACTION'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span>



</th>
						<th align="center" width="26px" style="padding-bottom:-1px;">
									<!--<input id="sellist" value="<?=$ingroupInfo->group_id;?>" type="checkbox">-->	
									<input type="checkbox" id="selectAll" />							 
						</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if($permissions->list_read == "N"){
                                                echo("<tr class='tr2'><td colspan='9'>".lang('go_Youdonthavepermissiontoviewthisrecords')."</td></tr>");
                                                $countthis = 0;
                                                $justpermission = true;
                                        }	
                  
                  if($countthis > 0){
                  								
					   foreach($lists as $listsInfo){
					?>   		         	
							 <tr align="left" class="tr<?php echo alternator('1', '2') ?>">
								 <td align="left" style="padding-bottom:-1px;">&nbsp;
								 <!--<div class="rightdiv toolTip" title="MODIFY <?=$listsInfo->list_id?>">-->
								<!-- <a id="listidlink" class="activator"  onClick="postval('<? echo $listsInfo->list_id; ?>');"><? echo $listsInfo->list_id; ?></a>-->
								<a id="listidlink" class="leftDiv toolTip" style="cursor:pointer;"  title="<? echo lang('go_MODIFY'); ?> <?=$listsInfo->list_id?>"  onClick="postval('<? echo $listsInfo->list_id; ?>');"><? echo $listsInfo->list_id; ?></a>
						
								</td>
								
								 <td colspan="" style="padding-bottom:-1px;">
								 								 
								 <?
								 	
									if($listsInfo->list_name == "") {
										echo "&nbsp;";
									} else {
										echo ucwords(strtolower($listsInfo->list_name));
									}
								 ?>
								 </td>
								 <td align="left" style="padding-bottom:-1px;">
								 <?php
								 	if($listsInfo->active=="Y") {
								 		echo "<b><font color=green>ACTIVE</font></b>";
								 	} else {
								 		echo "<b><font color=red>INACTIVE</font></b>";	
								 	}
								 	
								 ?>
								 </td>
								 <td align="left" style="padding-bottom:-1px;">	
								<?
								 		#echo $listsInfo->list_lastcalldate.'&nbsp;'; 
										echo str_replace("-","&#150;",$listsInfo->list_lastcalldate)."&nbsp;";
								?>
								</td>
				
								 <td align="left" style="padding-bottom:-1px;"><font color="RED"><b><? echo $listsInfo->tally; ?></b></font></td>
								 <td align="left" style="padding-bottom:-1px;"><? echo $listsInfo->campaign_id."&nbsp;"; ?></td>
  								 <td align="right" style="padding-bottom:-1px;">
  								
<img src="<?=$base?>img/edit.png" onclick="postval('<? echo $listsInfo->list_id; ?>');"  class="rightdiv toolTip" style="cursor:pointer;width:14px; padding: 3px;" title="<? echo lang('go_MODIFY'); ?> <?=$listsInfo->list_id?>"  />
								
  								 </td>
  								 <td align="left" style="padding-bottom:-1px;">
								 <?php
									if($listsInfo->list_id == 998 || $listsInfo->list_id == 999 || $listsInfo->list_id == 101) {
								 ?>
								 
  								 <div class="rightdiv toolTip" title="<? echo lang('go_Cannotdelete'); ?> <?=$listsInfo->list_id?>" style="padding:3px;">
						 			 <img src="<?=$base?>img/delete_grayed.png" style="cursor:pointer;width:12px;"  /> 
						 		 </div>
								<?php
									} else {
								?>
  								 <div class="rightdiv toolTip" title="<? echo lang('go_DELETE'); ?> <?=$listsInfo->list_id?>" style="padding:3px;">
						 			 <img src="<?=$base?>img/delete.png" onclick="deletepost('<? echo $listsInfo->list_id; ?>');" style="cursor:pointer;width:12px;"  /> 
						 		 </div>
								
								<?php
								}
								?>
  								 <div class="rightdiv toolTip" title="<? echo lang('go_Download'); ?> <?=$listsInfo->list_id?>" style="padding:3px;">
						 			 <a href="<?=$base?>index.php/go_list/download/<?=$listsInfo->list_id?>"><img src="<?=$base?>img/download.png" style="cursor:pointer;width:12px;"  /> </a>
						 		 </div>
								 </td>
  								 <td align="center" style="padding-bottom:-1px;">
  								<div class="rightdiv toolTip" title="<? echo lang('go_VIEWINFOFORLIST'); ?> <?=$listsInfo->list_id?>" style="padding: 3px;">
									<img style="cursor:pointer;width:12px;" src="<?=$base?>img/status_display_i.png" onclick="viewpost('<? echo $listsInfo->list_id; ?>');" style="cursor:pointer;width:14px;">
									</div>
								 </td>
								 <td align="center" width="26px" style="margin-top:-1px;padding-bottom:-1px;">
								 <?php
									if($listsInfo->list_id == 998 || $listsInfo->list_id == 999 || $listsInfo->list_id == 101) {
								 ?>
									<input type="checkbox" id="cannotdelete[]" disabled/>
								<?php
									} else {
								?>
									<input type="checkbox" id="delselectlist[]" value="<?=$listsInfo->list_id;?>" />
								<?php
								}
								?>
								 </td>
							 </tr>
							<?php
							$i++;
							}
							} else {
								if(!$justpermission){
	
							echo "<td colspan=\"7\" align=\"center\" style=\"background-color: #EFEFEF;\"><font color=\"red\"><b>".lang('go_Norecordsfound')." !</b></font></td>";

								}
							}
							
							?>
							
			</tbody>
				</table><br>
				<?php
				if (strlen($pagelinks["lists"]) > 0 || !is_null($pagelinks["lists"])) {
					echo "<div style='float:left;'>".$pagelinks["lists"]."</div>";
				}
			        echo "<div style='float:right;'>".$pageinfo["lists"]."</div>";
				?>
				<table id="listloadinggif" class="" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%;" > 
					<tr><td colspan="7" align="center"><div id="loadingslist"></div></td></tr>				
				</table>
				</form>
				</div>
				<!-- end view -->
				
				<!-- end add -->
				
				
				<!-- edit -->
				<!-- <div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="box" id="box">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closeme();"></a>
	
				<form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
					<input type="hidden" name="editlist" value="editlist">
					<input type="hidden" name="editval" id="editval">
					<input type="hidden" name="showvaledit" id="showvaledit" value="">
		
					
					<div id="listid_edit" align="left" class="title-header"> </div>
					<div align="left">					
					<table width="100%">
						<tr><td align="left"><div id="cdates"></div></td><td align="right"><div id="lcdates"></div></td></tr>
					</table>
					
					
					</div>
					
					<table class="tableedit">
							<tr>
								<td><br><label class="modify-value">Name:</label><div id="simula"></div> </td>
								<td><input type="text" name="list_name" id="listname_edit" size="30" maxlength="30"></td>
							</tr>
							<tr>
								<td><label class="modify-value">Description:</label></td>
								<td><input type="text" name="list_description" id="listdesc_edit" size="30" maxlength="255"></td>
							</tr>
							<tr>
								<td><label class="modify-value">Campaign:</label></td>
								<td>
									<select size="1" name="campaign_id" id="campid_edit">
										<option>--- Select Campaign ---</option>
										<?php
   		               			/*	foreach($campaigns as $campaignInfo){
												$cid = $campaignInfo->campaign_id;
												$cname = $campaignInfo->campaign_name;
                        					echo '<option value="'.$cid.'">'.$cname.'</option>';
										} */
										?>
									</select>
								</td>
							</tr>
							
							<tr>
								<td><label class="modify-value">Reset Times:</label> </td>
								<td><input type="text" name="reset_time" id="restime_edit" size="30" maxlength="100"></td>
							</tr>
							<tr>
								<td><label class="modify-value">Reset Lead-Called-Status:</label></td>
								<td>
									<select size="1" name="reset_list" id="reslist_edit">
										<option value="N">N</option>
										<option value="Y">Y</option>
									</select> 
								&nbsp;&nbsp;&nbsp;
								 <label class="modify-value">Active:</label>
									<select size="1" name="active" id="act_edit">
										<option value="Y">Y</option>
										<option value="N">N</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><label class="modify-value">Agent Script Override:</label> </td>
								<td>
									<select size="1" name="agent_script_override" id="agcscp_edit">
										<?php
											 if($eagent_script_override!=null) {
										?>
											 <option value="<?=$eagent_script_override?>"><?=$eagent_script_override?></option>										 
										<?											 
											 } else {
										?>
											 <option selected value=""> - </option>
										<?php	 	
											 }
										?>
										<option value="">NONE - INACTIVE</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><label class="modify-value"><label class="modify-value">Campaign CID Override:</label> </td>
								<td><input type="text" name="campaign_cid_override" id="campcidover_edit" size="20" maxlength="20"></td>
							</tr>
							<tr>
								<td><label class="modify-value"><label class="modify-value">Drop Inbound Group Override:</label> </td>
								<td>
									<select size="1" name="drop_inbound_group_override" id="drpinbovr_edit">
									<?php
										if($edrop_inbound_group_override!=null) {
									?>
										<option value="<?=$edrop_inbound_group_override?>"><?=$edrop_inbound_group_override?></option>
									<?
										}
									?>
										<option value="">NONE</option>
									</select>			
								</td>
							</tr>
							<tr>
								<td><label class="modify-value">Web Form:</label> </td>
								<td><input type="text" name="web_form_address" id="wbfrmadd_edit" size="50" maxlength="1055"></td>
							</tr>
							<tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
							<tr>
								<td colspan="2" align="center"> <br><label class="modify-value">Transfer-Conf Number Override</label> </td>
							</tr>
							<tr>
								<td colspan="2">
								<label class="modify-value">Number 1:</label> <input type="text" name="xferconf_a_number" id="xfer1" size="20" maxlength="50">
								<label class="modify-value">Number 4:</label> <input type="text" name="xferconf_d_number" id="xfer4" size="20" maxlength="50">
								<br>
								<label class="modify-value">Number 2:</label> <input type="text" name="xferconf_b_number" id="xfer2" size="20" maxlength="50">
								<label class="modify-value">Number 5:</label> <input type="text" name="xferconf_e_number" id="xfer5" size="20" maxlength="50">
								<br>
								<label class="modify-value">Number 3:</label> <input type="text" name="xferconf_c_number" id="xfer3" size="20" maxlength="50">
								</td>
							</tr>
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr>
								<td colspan="2" align="center"><b><?php
?></b></td>
							</tr>			
							<tr>
								<td colspan="2" align="center">
									<label id="stats"></label>
								</td>
							</tr>			
							<tr>
								<td align="center" colspan="2"><br>
								<input type="button" name="editSUBMIT" class="button" style="cursor:pointer;border:0px;color:#7A9E22;" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">
								</td>
								
							</tr>
							<tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
					</table>				
				</form>
				</center>
				</div>-->	
				<!-- end edit -->
				
			    <!-- view edit -->
			    <div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxviewlist" id="boxviewlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeview();"></a>
 				
 				<table summary="" class="listinfo">
					<tr>
					<td class="listinfolabel">
						<b><? echo lang('go_ListID_'); ?>: </b>	
					</td>
					<td>
						<div id="viewlistid" align="left"> </div>
					</td>
					</tr>
					<tr>
					<td class="listinfolabel">
						<b><? echo lang('go_Description_'); ?>: </b>				
					</td>
					<td>
						<div id="viewlistdesc" align="left"> </div>
					</td>
					</tr>
					<tr>					
					<td class="listinfolabel">
						<b><? echo lang('go_Status_'); ?>: </b>					
					</td>
					<td>
						<div id="viewliststatus" align="left"> </div>
					</td>					
					</tr>
					<tr>
					<td class="listinfolabel">
						<b><? echo lang('go_Lastcalldate'); ?>: </b>					
					</td>
					<td>
						<div id="viewlistcalldate" align="left"> </div>
					</td>
					
					</tr>
                                        <tr>
                                            <td colspan="2" class="listdownload" ><a id="download"><? echo lang('go_Download'); ?></a></td>
                                        </tr>
				</table>			
	 				
			    </center>
			    </div>
			    <!-- end view edit -->						
				
				
				
				
	</div> <!-- end LISTs -->
	
	
	
	
	
	
	
	
	<!-- CUSTOM FIELDS -->
	<div id="tabs-2">
	<div id="showlist" style="display: block;">
	
			<table id="cumstomtable" class="tablesorter" width="100%" class="" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; width:100%; margin-top: 3px;" > 
				<thead>
				<tr align="left" class="nowrap">
					<th class="thheader">&nbsp;&nbsp;<b><? echo lang('go_LISTID'); ?></b> </th>
					<th class="thheader"><b><? echo lang('go_DESCRIPTION'); ?></b></th>
					<th class="thheader"><b><? echo lang('go_STATUS'); ?></b> </th>
					<th class="thheader"><b><? echo lang('go_CAMPAIGNASSIGNED'); ?></b> </th>
					<th class="thheader"><b><? echo lang('go_CUSTOMFIELDS'); ?></b> </th>
					<th class="thheader"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> </th>
					
					<th colspan="3" class="thheader" style="width:8%;" align="right">
                                            <span style="cursor:pointer;" id="customselectAction">&nbsp;<? echo lang('go_MODIFY'); ?>&nbsp;</span>
					</th>
                                        <th align="center" width="35px">
                                           <input type="checkbox" id="customselectallf" disabled/>
                                        </th>
				</tr>
				</thead>
				<?php
					if (strlen($clist) > 0)
					{
						echo $clist;
					} else {
						 echo "<tr><td colspan=\"7\" align=\"center\" style=\"background-color: #EFEFEF;\"><font color=\"red\"><b>No custom fields created.</b></font></td></tr>";

#						echo "<tr><td colspan='6' style='font-weight:bold;color:#f00;text-align:center;padding-top:10px;'>No custom fields created.</td></tr>";
					}
				?>
			</table>
			<?php
			if (strlen($pagelinks["custom"]) > 0 || !is_null($pagelinks["custom"])) {
				echo "<div style='float:left;padding-top:10px;'>".$pagelinks["custom"]."</div>";
			}
			echo "<div style='float:right;padding-top:10px;'>".$pageinfo["custom"]."</div>";
			?>
		</div>
	
	</div><!-- end tab2 -->



	<!-- tab3 -->
	<div id="tabs-3">
				<!-- upload leads -->
				<div id="uploadlist" style="display: block;" align="left">
				<form action="go_list" name="uploadform" id="uploadform" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
				<input type="hidden" name="leadsload" id="leadsloadok" value="ok">
				<input type="hidden" name="tabvalsel" id="tabvalsel" value="<?=$tabvalsel?>">
				<input type="hidden" name="leadfile_name" id="leadfile_name" value="<?=$leadfile_name?>">
				<!--<b>Load Leads</b>
				<table class="tableedit" width="100%">
					<tr><td colspan="2">&nbsp;&nbsp;</td></tr>
				</table>
				-->
				<center>
				<table class="tablenodouble" width="50%">
				<?php
						$permissions = $this->commonhelper->getPermissions("loadleads",$this->session->userdata("user_group"));
						if($permissions->loadleads_read == "N"){
                                                   echo("<tr><td colspan='9'>".lang('go_Youdonthavepermissiontoviewthisrecords')."</td></tr>");
                                                   $countthis = 0;
                                                   $justpermission = true;
						   exit;
                                                }

					if($fields==null) {
				?>	
			
					<tr class="hideThisOne">
						<td colspan="2">&nbsp;&nbsp;</td>
					</tr>
		  			<tr class="hideThisOne">
						<td align="right"><label class="modify-value"><? echo lang('go_Leadsfile'); ?>:<label></td>
						<td><input title="<? echo lang('go_LeadsfileTooltip'); ?>"  type="file" name="leadfile" id="leadfile" value="<?php echo $leadfile ?>">
						<div class="progress">
                            <div class="bar"></div >
                            <div class="percent">0%</div >
                        </div>
                        <div id="customsd"></div>
					</td>
		  			</tr>
					<tr class="hideThisOne">
						<td align="right"><label class="modify-value"><? echo lang('go_ListID_'); ?></label></td>
						<td>
							<select title="<? echo lang('go_ListIDTooltips'); ?>."  name="list_id_override">
								<?php
									foreach($listIDs as $listsInfo){
											$load_list_id = $listsInfo->list_id;
											$load_list_name = $listsInfo->list_name;
											echo '<option value="'.$load_list_id.'">'.$load_list_id.'---'.$load_list_name.'</option>';	 
									}
								?>
							</select>
						</td>
					</tr>
					<tr class="hideThisOne">
						<td align="right"><label class="modify-value"><? echo lang('go_PhoneCode'); ?>: </label></td>
						<td>
								<select name="phone_code_override" title="<? echo lang('go_PhoneCodeTooltip'); ?>">
                        	<option value=''><? echo lang('go_LoadfromLeadFile'); ?></option>
                        	<?php
						//echo '<option value="1" selected>1---USA</option>';
                        		foreach($phonedoces as $listcodes) {
 									$selected = '';
									$country_code = $listcodes->country_code;
                        			$country = $listcodes->country;
									if ($country=="USA")
										$selected='selected="selected"';
                        			echo '<option value="'.$country_code.'" '.$selected.'>'.$country_code.'---'.$country.'</option>';
								}
                        	?>
                        </select><br>
			 <font size="1" color="red">*<? echo lang('go_IfyouselectLoadfromLeadFilesbesuretocheckyourphonecodefromyourfile'); ?>.</font>
                        </td>
                </tr>
                <!--<tr>
						<td><B><font face="arial, helvetica" size="2">File layout to use:</font></B></td>
						<td><font face="arial, helvetica" size="2"><input type="radio" name="file_layout" value="standard">Standard Format&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name="file_layout" value="custom" checked>Custom layout</td>
                </tr>-->
                <tr class="hideThisOne">
						<td align="right"><label class="modify-value"><? echo lang('go_DuplicateCheck'); ?>: </label></td>
						<td>
							<select size="1" name="dupcheck" title="<? echo lang('go_DuplicateCheckTooltip'); ?>">
								<option value="NONE"><? echo lang('go_NODUPLICATECHECK'); ?></option>
								<option value="DUPLIST"><? echo lang('go_CHECKFORDUPLICATESBYPHONEINLISTID'); ?>*</option>
								<option value="DUPCAMP"><? echo lang('go_CHECKFORDUPLICATESBYPHONEINALLCAMPAIGNLISTS'); ?></option>
								<!-- <option value="DUPSYS">CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM</option>
								<option value="DUPTITLEALTPHONELIST">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN LIST ID</option>
								<option value="DUPTITLEALTPHONESYS">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN ENTIRE SYSTEM</option>
 -->
							</select>
						</td>
		  			 </tr>
		  			 <tr class="hideThisOne">
		  			 <td align="right"><label class="modify-value"><? echo lang('go_TimeZone'); ?>: </label></td>
						<td>
							<select size="1" name="postalgmt" title="<? echo lang('go_TimeZoneTooltip'); ?>">
								<option value="AREA" selected><? echo lang('go_COUNTRYCODEANDAREACODEONLY'); ?></option>
								<option value="POSTAL"><? echo lang('go_POSTALCODEFIRST'); ?></option>
								<option value="TZCODE"><? echo lang('go_OWNERTIMEZONECODEFIRST'); ?></option>
							</select>
						</td>
					 </tr>
					 <tr class="hideThisOne"><td colspan="2">&nbsp;&nbsp;</td></tr>
					 <tr class="hideThisOne">
					 	<td colspan="2">
					 		<center>
					 			<input type="submit" value="<? echo lang('go_UPLOADLEADS'); ?>" name="submit_file" id="submit_file" style="cursor:pointer;" onclick="return checkmes();">
					 			<!--<input type="button" onClick="javascript:document.location='go_list/#tabs-3'" value="START OVER" name='reload_page'>-->
					 		</center>
					 	</td>
					 	
					 </tr>
					 <?php
					}
					 ?>
					 </form>
					 <?php
					 if($fields!=null) {
					 ?>
					<form action="go_list" name="uploadform2" id="uploadform2" onSubmit="ParseFileName()" enctype="multipart/form-data">
					<input type="hidden" name="leadsload" value="okfinal">
					<input type="hidden" name="lead_file" id="lead_file" value="<?=$lead_file?>">
					<input type="hidden" name="leadfile" id="leadfile" value="<?=$leadfile?>">
					<input type="hidden" name="list_id_override" value="<?=$list_id_override?>">
					<input type="hidden" name="phone_code_override" value="<?=$phone_code_override?>">
					<input type="hidden" name="dupcheck" value="<?=$dupcheck?>">
					<input type="hidden" name="leadfile_name" id="leadfile_name" value="<?=$leadfile_name?>">
					<input type="hidden" name="superfinal" id="superfinal">
					

					 <tr class="hideThisOne">
					 	
					 	<td colspan="2" align="center">
					 			
					 			<!--<br><br><br><br>-->
					 			<table>
					 			<tr bgcolor="#efefef">
					 			<td align="center" colspan="2"><b><? echo lang('go_Processing'); ?> <?=$delim_name ?> <? echo lang('go_files'); ?>...<br>

<? echo lang('go_LISTIDFORTHISFILE'); ?>: <?=$list_id_override?><br>

<? echo lang('go_COUNTRYCODEFORTHISFILE'); ?>: <?=$phone_code_override?></b><br><br><br></td>
					 			</tr>
					 			<?php	
					 			
									//$noview = array("security_phrase","date_of_birth","gender","country_code","phone_code","owner","rank","address3","address2","title","source_id","vendor_lead_code","lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");
									$noview = array("phone_code","lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");					 				
									$hiddenfields = array("security_phrase","date_of_birth","gender","country_code","vendor_lead_code","title","owner","rank","address3","address2","source_id");
									//$noview = array("security_phrase","alt_phone","date_of_birth","gender","country_code","phone_code","owner","rank","address3","address2","title","source_id","vendor_lead_code","lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");					 				
					 				
					 				foreach ($fields as $field) {
					 					
					 					if(in_array("$field", $noview)) {
											echo "";					 						
					 					} else {
											if (in_array("$field", $hiddenfields))
											{
												$hiddenclass = 'class="advancedFields"';
											} else {
												$hiddenclass = '';
											}
										
											echo "  <tr bgcolor=#efefef $hiddenclass>\r\n";
											echo "    <td align=right><b><font class=standard>".strtoupper(eregi_replace("_", " ", $field)).": </font></b></td>\r\n";
											echo "    <td align=left><select name='".$field."_field'>\r\n";
											echo "     <option value='-1'>(none)</option>\r\n";

											for ($j=0; $j<count($fieldrow); $j++) {
												eregi_replace("\"", "", $fieldrow[$j]);
												echo "     <option value='$j'>\"$fieldrow[$j]\"</option>\r\n";
											}
									
											echo "  </select></td>\r\n";
											echo "  </tr>\r\n";
					
										}
									} // end for
								
					 			?>
								<tr>
									<td colspan="2" style="padding-top:20px;text-align:center;"><span id="advancedFieldLink">[ + <? echo lang('go_SHOWADVANCEFIELDS'); ?> ]</span></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:15px;text-align:center;white-space:nowrap;font-size:10px;color:red;">*<? echo lang('go_Customfieldswillshowhereifyouhaveenableditonthelistidyouprovided'); ?>.</td>
								</tr>
					 		</table>
					 	</td>
					 </tr>
					 <tr class="hideLoading">
						<td colspan="4" align="center">	
							<div id="loadings"></div>					 
					 	</td>
					 </tr>
					 <tr class="showResults" style="display:none;">
						<td colspan="4" align="center">	
							<div id="show_result"></div>
					 	</td>
					 </tr>
					 
					 <tr>
				    	 <td class="hideThisOne" colspan="4" align="center">
				    		<br><br><br>
				    		<input type="submit" name="OK_to_process" value="OK TO PROCESS" onclick="uploadimg();" style="cursor: pointer;"><br />
				    		<!--<input type="button" onClick="javascript:document.location='go_list/#tabs-3'" value="BACK" name="reload_page">-->
				    		<input type="checkbox" id="show_results" /> <span style="color: red; font-size: 10px;"><? echo lang('go_Checkthisboxifyouwanttoshowtheresult'); ?></span>
						</td>
					 <?php
					 	}
					 ?>
					 </tr>
					 </form>
		  		</table>
				<br />
		  		</center>
				</div>
	</div><!-- end tab3 -->

	       <!-- COPY CUSTOM FIELDS -->
        <div id="tabs-4">
        <div id="copylist" style="display: none;">
			<form name="copyfields" id="copyfields" method="POST">
                        <table id="cumstomtablec" class="tablesorter" cellspacing="0" cellpadding="0" border="0" style="margin-left:auto; margin-right:auto; ">
                                <thead>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="center" colspan="2">&nbsp;&nbsp;</td>
					</tr>
                                	<tr align="center" class="nowrap">
                                        	<td class="thheader" align="right"><b><? echo lang('go_FROM'); ?>: &nbsp;&nbsp;&nbsp;&nbsp; </b></td>
						<td>	
						<select id="dropsource_list_id" name="dropsource_list_id">
						<?php
			  				foreach($dropcopylist as $dropcopylistInfo){
                                  				$copy_list_id = $dropcopylistInfo->list_id;
                                  				$copy_list_name = $dropcopylistInfo->list_name;
                                  				echo '<option value="'.$copy_list_id.'">'.$copy_list_id.'---'.$copy_list_name.'</option>';
                          				}
						?>
						</select>
						</td>
                                	</tr>
                                	<tr align="center" class="nowrap">
                                        	<td class="thheader" align="right"><b><? echo lang('go_TO'); ?>: &nbsp;&nbsp;&nbsp;&nbsp; </b></td>
						<td>	
						<select id="dropcopy_list_id" name="dropcopy_list_id">
						<?php
			  				foreach($dropcopylist as $dropcopylistInfo){
                                  				$copy_list_id = $dropcopylistInfo->list_id;
                                  				$copy_list_name = $dropcopylistInfo->list_name;
                                  				echo '<option value="'.$copy_list_id.'">'.$copy_list_id.'---'.$copy_list_name.'</option>';
                          				}
						?>
						</select>
						</td>
                                	</tr>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="right"><b><? echo lang('go_OPTION'); ?>: &nbsp;&nbsp;&nbsp;&nbsp; </b></td>
						<td align="left">
						<select id="copy_option" name="copy_option">
							<option value="APPEND">APPEND</option>
							<option value="UPDATE">UPDATE</option>
							<option value="REPLACE">REPLACE</option>
						</select>	
						</td>	
					</tr>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="center" colspan="2">&nbsp;&nbsp;</td>
					</tr>
		                        <tr align="center" class="nowrap">
                                        	<td class="thheader" align="center" colspan="2"><input type="submit" name="COPY_SUBMIT" id="COPY_SUBMIT" value="SUBMIT"></td>
					</tr>
                                </thead>
                                <tbody>
                                </tbody>
                        </table>
			</form>
                </div>

        </div><!-- end tab4 -->
		
		
	<!-- DNC NUMBERS -->
        <div id="tabs-5">
			<br style="font-size:8px;" />
			<div class="table_dnc" style="margin-top:-15px;">
				<div id="dnc_placeholder">

				<br />
				<p style="text-align:center;font-weight:bold;color:#f00;"><? echo lang('go_Typethenumberatthetoprightsearchbox'); ?>.</p>
				</div>
			</div>
		
			<!-- Overlay1 -->
			<div id="overlayDNC" style="display:none;"></div>
			<div id="boxDNC">
			<a id="closeboxDNC" class="toolTip" title="<? echo lang('go_CLOSE'); ?>"></a>
			<div id="overlayContentDNC"></div>
			</div>

			<!-- Action Menu -->
			<div id='go_dnc_menu' class='go_dnc_menu'>
			<ul>
			<li class="go_dnc_submenu" title="<? echo lang('go_DeleteSelected'); ?>" id="delete"><? echo lang('go_DeleteSelected'); ?></li>
			</ul>
			</div>
        </div>
	<!-- end tab5 -->
	
	<div id="tabs-6">
		
	</div>
		
		
	<!-- Lead Search -->
        <div id="tabs-7">
			<br style="font-size:8px;" />
			<div class="table_lead_search" style="margin-top:-15px;">
				<div id="lead_search_placeholder">
					<br />
					<table border=0 cellpadding=0 cellspacing=0 style="width:100%;margin-left:auto;margin-right:auto;">
						<thead>
							<tr style="text-align: left">
								<th>&nbsp;<? echo lang('go_LEADID'); ?></th>
								<th>&nbsp;<? echo lang('go_LISTID'); ?></th>
								<th>&nbsp;<? echo lang('go_PHONE'); ?></th>
								<th>&nbsp;<? echo lang('go_FULLNAME'); ?></th>
								<th>&nbsp;<? echo lang('go_LASTCALLDATE'); ?></th>
								<th>&nbsp;<? echo lang('go_STATUS'); ?></th>
								<th>&nbsp;<? echo lang('go_LASTAGENT'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr class="tr2">
								<td colspan=8 style="font-weight:bold;color:#F00;font-style:italic;">&nbsp;<? echo lang('go_Norecordsfound'); ?>.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<input type="hidden" id="search_count" value="0" />
		
			<!-- Overlay1 -->
			<div id="overlaySearch" style="display:none;"></div>
			<div id="boxSearch">
				<a id="closeboxSearch" class="toolTip" title="<? echo lang('go_CLOSE'); ?>"></a>
				<div id="overlayContentSearch">
					<table style="margin-left:auto;margin-right:auto;width:100%;">
						<tr>
							<td colspan=2 style="font-weight:bold;text-align:center;font-size:16px;"><? echo lang('go_LeadSearchOptions'); ?></td>
						</tr>
						<tr>
							<td colspan=2 style="font-weight:bold;text-align:center;font-size:10px;">&nbsp;</td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_Phone_'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('phone',null,'id="search_phone" maxlength="11" size="12" class="basicSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;width:40%;"><? echo lang('go_SearchAltPhone'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_dropdown('alt_phone',array('N'=>'No','Y'=>'Yes'),null,'class="advanceSearch"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;"><? echo lang('go_FirstName'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('first_name',null,'id="search_first_name" maxlength="30" class="basicSearch"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;"><? echo lang('go_LastName'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('last_name',null,'id="search_last_name" maxlength="30" class="basicSearch"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;" class="adv"><span style="color:red">*</span> <? echo lang('go_LastCallDate'); ?>:&nbsp;</td>
							<td style="white-space:nowrap;" class="adv">
								<div style="display:table-cell;">
									&nbsp;
								</div>
								<div id="widgetField" style="display:table-cell;">
									<a href="javascript:void(0);" id="daterange" style="float:right; position: static;">Select date range</a>
									<div style="margin-top:2px;" id="widgetDate"><span name="selected_from_date" id="selected_from_date" class="advanceSearch"><? echo date('Y-m-d'); ?></span> to <span name="selected_to_date" id="selected_to_date" class="advanceSearch"><? echo date('Y-m-d'); ?></span></div>
								</div>
								<div style="display:table-cell;">
									<input type="checkbox" id="searchByDate" /> <small style="color:#F00">(<? echo lang('go_searchwdate'); ?>)</small>
 								</div>
							</td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_LeadID'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('lead_id',null,'maxlength="10" size="10" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_Disposition'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_dropdown('status',$dispos,null,'style="width:250px" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_ListID_') ?>&nbsp;</td>
							<td>&nbsp;<?=form_input('list_id',null,'maxlength="15" size="15" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_LastAgent'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('user',null,'maxlength="20" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_Address_'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('address',null,'maxlength="100" size="35" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_City_'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('city',null,'maxlength="50" size="25" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_State_'); ?>&nbsp;</td>
							<td>&nbsp;<?=form_input('state',null,'maxlength="2" size="3" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_Email_'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('email',null,'maxlength="70" size="30" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td style="text-align:right;"><? echo lang('go_Comments'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_input('comments',null,'maxlength="100" size="40" class="advanceSearch"') ?></td>
						</tr>
						<tr class="adv">
							<td colspan=2 style="text-align:left;font-size:10px;color:red;line-height:18px;padding-left:10px;">* <? echo lang('go_Leadsearchbydaterangeislimitedto60daysonly'); ?>.</td>
						</tr>
						<tr>
							<td colspan=2 style="font-weight:bold;text-align:center;font-size:10px;">&nbsp;</td>
						</tr>
						<tr>
							<td colspan=2 style="text-align:right;"><span id="showAdvance" class="buttons"><? echo lang('go_Advance'); ?></span> | <span id="submitSearch" class="buttons"><? echo lang('go_Search'); ?></span></td>
						</tr>
					</table>
				</div>
			</div>
			
			<div style="top:170px;left:14%;right:14%;margin-left:auto;margin-right:auto;" id="widgetCalendarLeadInfo"></div>
			
			<!-- Lead Info Overlay -->
			<div id="overlayLeadInfo" style="display:none;"></div>
			<div id="boxLeadInfo">
				<a id="closeboxLeadInfo" class="toolTip" title="<? echo lang('go_CLOSE'); ?>"></a>
				<div id="overlayLoadingLeadInfo"><center><br /><img src="<? echo $base; ?>img/goloading.gif" /><br /><br /></center></div>
				<div id="overlayContentLeadInfo">
					<table style="margin-left:auto;margin-right:auto;width:100%;">
						<tr>
							<td colspan=2 style="font-weight:bold;text-align:center;font-size:16px;"><? echo lang('go_LeadInformation'); ?></td>
						</tr>
						<tr>
							<td colspan=2 style="font-weight:bold;text-align:center;font-size:10px;">&nbsp;</td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"> <? echo lang('go_LeadID'); ?>:&nbsp;</td>
							<td style="width:60%;line-height:20px;">&nbsp;<span id="leadinfo_lead_id"></span></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_ListID_'); ?>:&nbsp;</td>
							<td style="width:60%;line-height:20px;">&nbsp;<span id="leadinfo_list_id"></span></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;">Fronter:&nbsp;</td>
							<td style="width:60%;line-height:20px;">&nbsp;<span id="leadinfo_user"></span></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_FirstName'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('first_name',null,'id="leadinfo_first_name" maxlength="30" size="25" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_LastName'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('last_name',null,'id="leadinfo_last_name" maxlength="30" size="25" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_Address_'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('address1',null,'id="leadinfo_address1" maxlength="100" size="50" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_City_'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('city',null,'id="leadinfo_city" maxlength="50" size="30" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_State_'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('state',null,'id="leadinfo_state" maxlength="2" size="3" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_Zip'); ?>&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('postal_code',null,'id="leadinfo_postal_code" maxlength="10" size="12" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_PhoneCode'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('phone_code',null,'id="leadinfo_phone_code" maxlength="10" size="10" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_PhoneNumber'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('phone_number',null,'id="leadinfo_phone_number" maxlength="18" size="20" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_AltPhone'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('alt_phone',null,'id="leadinfo_alt_phone" maxlength="12" size="15" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_Email_'); ?>:&nbsp;</td>
							<td style="width:60%;">&nbsp;<?=form_input('email',null,'id="leadinfo_email" maxlength="70" size="30" class="basicLeadInfo"') ?></td>
						</tr>
						<tr>
							<td style="text-align:right;width:40%;"><? echo lang('go_Comments'); ?>:&nbsp;</td>
							<td style="width:60%;white-space:nowrap;">&nbsp;<?=form_textarea(array('name'=>'comments','value'=>null,'id'=>"leadinfo_comments",'cols'=>"50",'rows'=>"5",'style'=>"resize:none;",'class'=>"basicLeadInfo")) ?></td>
						</tr>
						<tr class="advLeadInfo">
							<td style="text-align:right;"><? echo lang('go_Disposition'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_dropdown('status',$dispos,null,'id="leadinfo_status" style="width:250px" class="advanceLeadInfo"') ?></td>
						</tr>
						<tr class="advLeadInfo">
							<td style="text-align:right;"><? echo lang('go_ModifyVicidialLogs'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_checkbox(array('id'=>'modify_logs','value'=>'1','name'=>'modify_logs','checked'=>true,'class'=>"advanceLeadInfo"))?></td>
						</tr>
						<tr class="advLeadInfo">
							<td style="text-align:right;"><? echo $ModifyAgentLogs; ?>:&nbsp;</td>
							<td>&nbsp;<?=form_checkbox(array('id'=>'modify_agent_logs','value'=>'1','name'=>'modify_agent_logs','checked'=>true,'class'=>"advanceLeadInfo"))?></td>
						</tr>
						<tr class="advLeadInfo">
							<td style="text-align:right;"><? echo lang('go_ModifyCloserLogs'); ?>::&nbsp;</td>
							<td>&nbsp;<?=form_checkbox(array('id'=>'modify_closer_logs','value'=>'1','name'=>'modify_closer_logs','checked'=>false,'class'=>"advanceLeadInfo"))?></td>
						</tr>
						<tr style="display:none">
							<td style="text-align:right;"><? echo lang('go_AddCloserLogRecord'); ?>:&nbsp;</td>
							<td>&nbsp;<?=form_checkbox(array('id'=>'add_closer_record','value'=>'1','name'=>'add_closer_record','checked'=>false,'class'=>"advanceLeadInfo"))?></td>
						</tr>
						<tr>
							<td colspan=2 style="font-weight:bold;text-align:center;font-size:10px;">&nbsp;</td>
						</tr>
						<tr>
							<td colspan=2 style="text-align:right;"><span id="showAdvanceLeadInfo" class="buttons"><? echo lang('go_Advance'); ?></span> | <span id="submitLeadInfo" class="buttons"><? echo lang('go_Submit'); ?></span></td>
						</tr>
					</table>
					
					<!-- S t a r t -->
					<br class="clear"/>
					<div class="collapse-anchor"><a id="log-collapse"><? echo lang('go_OtherInfo'); ?> [+]</a></div>
					<div id="collapsible" class="invi-elem"> 
						<div class="corner-all innerbox-tbl" id="calls-to-this-lead">
							<div class="user-tbl">
								<div class='innerbox-title'><strong><? echo lang('go_CallstothisLead'); ?></strong></div>
								<div class="user-hdr">
									<div class="user-tbl-cols"><? echo lang('go_DateTime');?></div>
									<div class="user-tbl-cols user-tbl-cols-centered"><? echo lang('go_Length'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_Status_'); ?></div>
									<div class="user-tbl-cols">TSR</div>
									<div class="user-tbl-cols"><? echo lang('go_Campaign_'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_List'); ?></div>
									<div class="user-tbl-cols">Lead</div>
									<div class="user-tbl-cols"><? echo lang('go_HangupReason'); ?></div>
									<div class="user-tbl-cols">Phone</div>
									<br class="clear"/>
								</div>
								<div class="user-tbl-container">&nbsp;</div>
							</div>
						</div><br class="clear"/>
						<br class="clear"/>
						<div class="corner-all innerbox-tbl" id="closer-records">
							<div class="user-tbl">
								<div class='innerbox-title'><strong><? echo lang('go_CloserRecordsforthisLead'); ?></strong></div>
								<div class="user-hdr">
									<div class="user-tbl-cols"><? echo lang('go_DateTime');?></div>
									<div class="user-tbl-cols user-tbl-cols-centered"><? echo lang('go_Length'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_Status_'); ?></div>
									<div class="user-tbl-cols">TSR</div>
									<div class="user-tbl-cols"><? echo lang('go_Campaign_'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_List'); ?></div>
									<div class="user-tbl-cols">Lead</div>
									<div class="user-tbl-cols"><? echo lang('go_Wait'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_HangupReason'); ?></div>
									<br class="clear"/>
								</div>
								<div class="user-tbl-container">&nbsp;</div>
							</div>
						</div><br class="clear"/>
						<br class="clear"/>
						<div class="corner-all innerbox-tbl" id="agent-log">
							<div class="user-tbl">
								<div class='innerbox-title'><strong><? echo lang('go_AgentLogRecordsforthisLead'); ?></strong></div>
								<div class="user-hdr">
									<div class="user-tbl-cols " style="width:18%"><? echo lang('go_DateTime');?></div>
									<div class="user-tbl-cols user-normalcols"><? echo lang('go_Campaign_'); ?></div>
									<div class="user-tbl-cols user-normalcols">TSR</div>
									<div class="user-tbl-cols user-smallcols"><? echo lang('go_Pause'); ?></div>
									<div class="user-tbl-cols user-smallcols"><? echo lang('go_Wait'); ?></div>
									<div class="user-tbl-cols user-smallcols"><? echo lang('go_Talk'); ?></div>
									<div class="user-tbl-cols user-smallcols">Dispo</div>
									<div class="user-tbl-cols user-smallcols"><? echo lang('go_Status_'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_Group'); ?></div>
									<div class="user-tbl-cols">Sub</div> 
									<br class="clear"/>
								</div>
								<div class="user-tbl-container">&nbsp;</div>
							</div>
						</div><br class="clear"/>
						<br class="clear"/>
						<div class="corner-all innerbox-tbl" id="recording">
							<div class="user-tbl">
								<div class='innerbox-title'><strong><? echo lang('go_RecordingsforthisLead'); ?></strong></div>
								<div class="user-hdr">
									<div class="user-tbl-cols">Lead</div>
									<div class="user-tbl-cols"><? echo lang('go_DateTime');?></div>
									<div class="user-tbl-cols user-tbl-cols-centered"><? echo lang('go_Seconds'); ?></div>
									<div class="user-tbl-cols">RecId</div>
									<div class="user-tbl-cols"><? echo lang('go_Filename'); ?></div>
									<div class="user-tbl-cols"><? echo lang('go_Location'); ?></div>
									<div class="user-tbl-cols">TSR</div>
									<br class="clear"/>
								</div>
								<div class="user-tbl-container">&nbsp;</div>
							</div>
						</div><br class="clear"/>
						<br class="clear"/>
					</div>
					<!-- E n d -->
					<br class="clear"/><br class="clear"/>
				</div>
			</div>

			<!-- Recordings Overlay -->
			<div id="overlayRecordings" style="display:none;"></div>
			<div id="boxRecordings">
				<a id="closeboxRecordings" class="toolTip" title="<? echo lang('go_CLOSE'); ?>"></a>
				<div id="overlayLoadingRecordings"><center><br /><img src="<? echo $base; ?>img/goloading.gif" /><br /><br /></center></div>
				<div id="overlayContentRecordings">
					<div style="font-size:20px;width:100%;text-align:center;color:#000;font-weight:bold;" id='rec_lead_id'></div>
					<br />
					<div id="recording_output"></div>
					<!-- E n d -->
					<br class="clear"/><br class="clear"/>
				</div>
			</div>

			<!-- Action Menu -->
			<div id='go_lead_search_menu' class='go_lead_search_menu'>
			<ul>
			<li class="go_lead_search_submenu" title="<? echo lang('go_DeleteSelected'); ?>" id="delete"><? echo lang('go_DeleteSelected'); ?></li>
			</ul>
			</div>
        </div>
	<!-- end tab7 -->

				

									<div style="display: none;" class="demo-description">
										<p><? echo lang('go_Clicktabstoswapbetweencontentthatisbrokenintologicalsections');  ?>.</p>
									</div><!-- End demo-description -->							
				
                            <div class="container">
                               <div class="clear"></div>
                            </div>
                            						<div id='go_action_menu' class='go_action_menu'>
<ul>
<li class="go_action_submenu" title="<? echo lang('go_ActivateSelected'); ?>" id="activate"><? echo lang('go_ActivateSelected'); ?></li>
<li class="go_action_submenu" title="<? echo lang('go_DeactivatedSelected'); ?>" id="deactivate"><? echo lang('go_DeactivatedSelected'); ?></li>
<li class="go_action_submenu" title="<? echo lang('go_DeleteSelected'); ?>" id="delete"><? echo lang('go_DeleteSelected'); ?></li>
</ul>
</div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</div>
</div> <!-- wpwrap -->
</div>
<!-- end body -->
