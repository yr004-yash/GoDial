<?php
########################################################################################################
####  Name:             	index.php   	                        	    	    	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2014 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Jerico James Milo	                                            ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
$firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
$safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? true : false;
$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;

$myurl = "https://".$_SERVER['SERVER_NAME']."/login/ieview.php";

if(!$firefox && !$chrome) {
	header("Location: $myurl") ;
} 


$version = file_get_contents('../version.txt');


if (file_exists("/etc/goautodial.conf")) {
	$conf_path = "/etc/goautodial.conf";
} elseif (file_exists("{$_SERVER['DOCUMENT_ROOT']}/goautodial.conf")) {
	$conf_path = "{$_SERVER['DOCUMENT_ROOT']}/goautodial.conf";
} else {
	die ($lang['go_conf_file_not_found']);
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*|\[|\]/","",$DBCline);
                if (ereg("^VARSERVTYPE", $DBCline))
                        {$VARSERVTYPE = $DBCline;   $VARSERVTYPE = preg_replace("/.*=/","",$VARSERVTYPE);}
                if (ereg("goautodialdbhostname", $DBCline))
                        {$GOdbHostname = $DBCline;   $GOdbHostname = preg_replace("/.*=/","",$GOdbHostname);}
                if (ereg("goautodialdbusername", $DBCline))
                        {$GOdbUsername = $DBCline;   $GOdbUsername = preg_replace("/.*=/","",$GOdbUsername);}
                if (ereg("goautodialdbpassword", $DBCline))
                        {$GOdbPassword = $DBCline;   $GOdbPassword = preg_replace("/.*=/","",$GOdbPassword);}
                if (ereg("goautodialdbdatabase", $DBCline))
                        {$GOdbDatabase = $DBCline;   $GOdbDatabase = preg_replace("/.*=/","",$GOdbDatabase);}
                }
        }

if (file_exists("/etc/astguiclient.conf")) {
	$conf_path = "/etc/astguiclient.conf";
} elseif (file_exists("{$_SERVER['DOCUMENT_ROOT']}/astguiclient.conf")) {
	$conf_path = "{$_SERVER['DOCUMENT_ROOT']}/astguiclient.conf";
} else {
	die ($lang['go_ast_file_not_found']);
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*/","",$DBCline);
                if (ereg("^PATHlogs", $DBCline))
                        {$PATHlogs = $DBCline;   $PATHlogs = preg_replace("/.*=/","",$PATHlogs);}
                if (ereg("^PATHweb", $DBCline))
                        {$WeBServeRRooT = $DBCline;   $WeBServeRRooT = preg_replace("/.*=/","",$WeBServeRRooT);}
                if (ereg("^VARserver_ip", $DBCline))
                        {$WEBserver_ip = $DBCline;   $WEBserver_ip = preg_replace("/.*=/","",$WEBserver_ip);}
                if (ereg("^VARDB_server", $DBCline))
                        {$VARDB_server = $DBCline;   $VARDB_server = preg_replace("/.*=/","",$VARDB_server);}
                if (ereg("^VARDB_database", $DBCline))
                        {$VARDB_database = $DBCline;   $VARDB_database = preg_replace("/.*=/","",$VARDB_database);}
                if (ereg("^VARDB_user", $DBCline))
                        {$VARDB_user = $DBCline;   $VARDB_user = preg_replace("/.*=/","",$VARDB_user);}
                if (ereg("^VARDB_pass", $DBCline))
                        {$VARDB_pass = $DBCline;   $VARDB_pass = preg_replace("/.*=/","",$VARDB_pass);}
                if (ereg("^VARDB_port", $DBCline))
                        {$VARDB_port = $DBCline;   $VARDB_port = preg_replace("/.*=/","",$VARDB_port);}
                }
        }

$link=mysql_connect("$VARDB_server:$VARDB_port", "$VARDB_user", "$VARDB_pass");
if (!$link)
        {
	die($lang['go_mysql_connect_error'] . mysql_error());
        }
mysql_select_db("$VARDB_database");

$golink=mysql_connect("$GOdbHostname:$VARDB_port", "$GOdbUsername", "$GOdbPassword");
if (!$golink)
        {
	die($lang['go_mysql_connect_error'] . mysql_error());
        }


if (isset($_GET["forgotpass"])) {
        $forgotpass = $_GET["forgotpass"];
} elseif (isset($_POST["forgotpass"])) {
        $forgotpass=$_POST["forgotpass"];
}

if (isset($_GET["verifymail"])) {
        $verifymail = $_GET["verifymail"];
} elseif (isset($_POST["verifymail"])) {
        $verifymail=$_POST["verifymail"];
}

if (isset($_GET["emailadd"])) {
        $emailadd = $_GET["emailadd"];
} elseif (isset($_POST["emailadd"])) {
        $emailadd=$_POST["emailadd"];
}

if (isset($_GET["f_accnt_web"])) {
        $f_accnt_web = $_GET["f_accnt_web"];
} elseif (isset($_POST["f_accnt_web"])) {
        $f_accnt_web=$_POST["f_accnt_web"];
}

if (isset($_GET["f_emailadd"])) {
        $f_emailadd = $_GET["f_emailadd"];
} elseif (isset($_POST["f_emailadd"])) {
        $f_emailadd=$_POST["f_emailadd"];
}

if (isset($_GET["f_reset_pass"])) {
        $f_reset_pass = $_GET["f_reset_pass"];
} elseif (isset($_POST["f_reset_pass"])) {
        $f_reset_pass=$_POST["f_reset_pass"];
}

if (isset($_GET["f_rtype_pass"])) {
        $f_rtype_pass = $_GET["f_rtype_pass"];
} elseif (isset($_POST["f_rtype_pass"])) {
        $f_rtype_pass=$_POST["f_rtype_pass"];
}

if (isset($_GET["lang"])) {
        $sgo_language = $_GET["lang"];
} elseif (isset($_POST["lang"])) {
        $sgo_language=$_POST["lang"];
}


$stmt = "SELECT company_name,company_logo,login_button FROM `$GOdbDatabase`.`go_server_settings`";
$rslt = mysql_query($stmt, $golink);
$row=mysql_fetch_row($rslt);
$COMPANYNAME = $row[0];
$COMPANYLOGO = $row[1];
$LOGINBUTTON = $row[2];

if($_SERVER['HTTPS']!='on')
{
?>
<script language="javascript">
window.location = "https://"+window.location.host+"/login/"
</script>
<?php
}

//multi_lang
$query_defLang = "SELECT default_language FROM system_settings;";
$rsltdefLang = mysql_query($query_defLang, $link);
$cnt_defLang = mysql_num_rows($rsltdefLang);

        if($cnt_defLang > 0) {
                $rowDeflang=mysql_fetch_row($rsltdefLang);
                $def_lang = $rowDeflang[0];
        }

$querylang = "SELECT lang,name FROM `$GOdbDatabase`.go_language;";
$rsltlang=mysql_query($querylang, $golink);
$cnt_lang = mysql_num_rows($rsltlang);

        if ($cnt_lang > 0) {
                $l = 0;
                $LangLink = '';
                while ($cnt_lang > $l) {
                        $rowlang=mysql_fetch_row($rsltlang);
                        $lowerlang = strtolower($rowlang[1]);
                        $LangLink .= "<div id=\"flag-$rowlang[0]\" class=\"flags\" ><a href=\"?lang=$lowerlang\" style=\"display: block; font-size: 10px;\"  title=\"$rowlang[1]\">&nbsp; </a></div>";
                $l++;
                }
        }

if(strlen($sgo_language) > 0) {
$_SESSION['xsgo_language'] = $sgo_language;
$vgo_language = $_SESSION['xsgo_language'];
$lang_info = "?lang={$sgo_language}";

$settings_path = "{$_SERVER['DOCUMENT_ROOT']}/application/language/$sgo_language/login_lang.php";
include($settings_path);
} else {
$vgo_language = "english";
$settings_path = "{$_SERVER['DOCUMENT_ROOT']}/application/language/$vgo_language/login_lang.php";
include($settings_path);
}


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

$curPage = curPageURL();
$loginURL = "https://".$_SERVER['SERVER_NAME']."/login/{$lang_info}";

if($curPage != $loginURL) {
  header("Location: $loginURL"); /* Redirect browser */
  exit();
}

?>

<html>
<head>
<title><? echo $COMPANYNAME; ?> - <? echo $lang['go_empowering_generation']; ?></title>
<link rel="shortcut icon" href="../img/gologoico.ico" />
<meta http-equiv="Content-Type"sdf content="text/html; charset=utf-8">

<!--<link rel="stylesheet" type="text/css" href="css/style.css">-->
<link rel="stylesheet" type="text/css" href="../css/go_select_language.php" />
<link rel="stylesheet" type="text/css" media="screen" href="css/style.php">

<script type="text/javascript">
</script>

<script src="https://<? echo $_SERVER['HTTP_HOST']; ?>/js/jquery.main.js" type="text/javascript"></script>
<script src="https://<? echo $_SERVER['HTTP_HOST']; ?>/js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>
<!-- <script type="text/javascript" src="jquery-1.2.6.min.js"></script> -->

<script type='text/javascript'>
$(document).ready(function(){
	
	$("#form_login").submit(function(event) {
		
    	    var name = $("input#user_name").val();  
    	    var pass = $("input#user_pass").val();
    	
    	    if(name=="" || name==null) {
    		alert("<? echo $lang['go_pls_enter_username']; ?>");
    		$("#user_name").focus();
    		return false;	
    	    }
    	
    	    if(pass=="" || pass==null) {
    		alert("<? echo $lang['go_pls_enter_pass']; ?>");
    		$("#user_pass").focus();
    		return false;	
    	    }
    	
    	
		dataString = $("#form_login").serialize();
		
		$.ajax({
  			type: "POST",  
  			url: "https://<?=$_SERVER['SERVER_NAME'];?>/index.php/go_login/validate_credentials",  
  			data:  dataString,
    		
    		success: function(data){
      		
	   			if (data=="Authenticated"){
					$('#messageid').css("color","green");
					$('#messageid').text('<? echo $lang['go_redirecting'] ?>').fadeIn('fast');
					window.location = "https://<?=$_SERVER['SERVER_NAME'];?>/dashboard";
	   			} else {
	   				$('#messageid').text(data).fadeIn('fast').fadeOut(3000);
				}
			}

		});     

            //return false;
	    event.preventDefault();

        });

    $("#signup").click(function(){

        $('#signupOverlay').fadeIn('fast');
        $('#signupBox').css({'width': '770px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
        $('#signupBox').animate({
        top: "70px",
        left: "14%",
        right: "14%"
        }, 500);
        $('#signupoverlayContent').fadeOut("slow").load('../index.php/go_carriers_ce/sippywelcome').fadeIn("slow");

    });

        $('#signupClosebox').click(function()
        {
                $('#signupBox').animate({'top':'-2550px'},500);
                $('#signupOverlay').fadeOut('slow');
                $("#box").empty();
        });

    });
 


</script>
<style>
#signup{cursor:pointer;}
#statusOverlay,#fileOverlay,#hopperOverlay{
        background:transparent url(https://<?=$_SERVER['SERVER_NAME']?>/img/overlay.png) repeat top left;
        position:fixed;
        top:0px;
        bottom:0px;
        left:0px;
        right:0px;
        z-index:102;
}

#signupOverlay{
        background:transparent url(https://<?=$_SERVER['SERVER_NAME'] ?>/img/overlay.png) repeat top left;
        position:fixed;
        top:0px;
        bottom:0px;
        left:0px;
        right:0px;
        z-index:102;
}


#box{
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

#signupBox{
        position:absolute;
        top:-2550px;
        left:30%;
        right:30%;
        background-color: #FFF;
        color:#7F7F7F;
        padding:20px;

        -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
        z-index:103;
}

#signupClosebox{
        float:right;
        width:26px;
        height:26px;
        background:transparent url(https://<?=$_SERVER['SERVER_NAME']; ?>/img/cancel.png) repeat top left;
        margin-top:-30px;
        margin-right:-30px;
        cursor:pointer;
}
</style>
</head>
<body class="bodybgback">

	<div class="bodyheader" style="line-height:16px;">
		 <span style="margin-left: 1%;">
			<a href="http://goautodial.com" title="GOautodial" taget="_new">
				<img src="smalllogo.png" border="0" style="padding-top:2px">
			</a>
			&nbsp;&nbsp;
		 </span>
		 <span style="margin-right: 1%; font-size: 13px; margin-top: 8px; float: right;">
				<a href="http://<?=$_SERVER['SERVER_NAME']?>/agent/" class="go_menu_list" style="color:#FFFFFF;text-decoration:none;"><b><? echo strtoupper($lang['go_agent_login']); ?></b></a>
		 </span>
	</div>
                 <span style="margin-left: 1%;padding-top:5px;float:left;">
		<? echo $LangLink; ?>
		</span>
<br><br><br><br>
<center>
<?
if($forgotpass==1) {

?>

<form name="forgot" id="forgot" method="POST" action="<?=$_SERVER['PHP_SELF']?>?forgotpass=1" class="curvebox">
<input type="hidden" name="verifymail" id="verifymail" value="1">
	<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>
					<table width="400px" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 20px; padding-right:0px;">
									<img src="<?=$COMPANYLOGO ?>" width="150px" >
								</td>
							</tr>
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
                                                        <tr>
                                                                <!-- <td align="center" style="padding-left:35px;"> -->
                                                                <td align="center">
                                                                        <font color="black"><? echo $lang['go_account_number']; ?></a>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                                <td align="center">
                                                                        <input class="form_input_button" type="text" title="<? echo $lang['go_account_number']; ?>" name="f_accnt_web" id="f_accnt_web" size="30">
                                                                </td>
                                                        </tr>
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="center">
									<input src="portal-forgot-button.png" title="<? echo  $lang['go_send_email']; ?>" style="vertical-align: middle;width:130px;height:40px;" type="image">
								</td>
							</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

</form>

<?php

if($verifymail=="1") {

     function getAccountInfo($type,$value){
          switch ($type)
          {
               case "i_account":
                    $vtype = "int";
                    break;

               case "username":
                    $vtype = "string";
                    break;
          }
          include '/var/www/html/sippysignup/html.php';
          include '/var/www/html/sippysignup/xmlrpc/xmlrpc.inc';

          $params = array(new xmlrpcval(array($type => new xmlrpcval($value, $vtype)), "struct"));
          $msg = new xmlrpcmsg('getAccountInfo', $params);
          $_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
          $_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));

          $cli->setSSLVerifyPeer(false);

          $r = $cli->send($msg, 12);

          if ($r->faultCode()) {
               if ($r->faultCode() != 400) {
                    error_log("Fault. Code: " . $r->faultCode() . ", Reason: " . $r->faultString());
               }
          }
	
          return $r->value();
     }

	$auth_accnt = $f_accnt_web;
     	$struct = getAccountInfo("username",$auth_accnt);

        if($struct=="0") {
		echo "<div class=\"ce\">";
		echo "<font color=\"red\" size=\"4\">{$lang['go_the_account_does_not_exist'] }</font>";
                echo "</div>";
	} else {

		$emailval = $struct->structmem('email');
        	$sippyemail = $emailval->getval();

		$queryv_user = "SELECT user,pass FROM vicidial_users WHERE user LIKE '%$auth_accnt%'";
		$rslt=mysql_query($queryv_user, $link);
		$qm_conf_ct = mysql_num_rows($rslt);
		if ($qm_conf_ct > 0) {
			$row=mysql_fetch_row($rslt);
			$goautouser = $row[0];
			$goautopass = $row[1];
		}

                $queryv_user2 = "SELECT username,web_password FROM justgovoip_sippy_info WHERE username LIKE '%$auth_accnt%'";
                $rslt2=mysql_query($queryv_user2, $link);
                $qm_conf_ct2 = mysql_num_rows($rslt2);
                if ($qm_conf_ct2 > 0) {
                        $row2=mysql_fetch_row($rslt2);
                        $goautouser2 = $row[0];
                        $goautopass2 = $row[1];
                }

	
		#$tologinsippy = "https://dal.justgovoip.com/accounts.php";
		$tologin = "https://".$_SERVER['SERVER_NAME']."/login/";
                $to = "$sippyemail";
                $headers = "{$lang["go_from"]}: noreply@justgocloud.com";
                $subject = "{$lang["go_justgocloud_admin_pass_ret"]}";

                $message = "{$lang["go_pls_keep_this_email"] }:
		
		{$lang["go_account_number"]}: $auth_accnt
	        
		{$lang["go_justgocloud_admin_url"] }: $tologin	
		{$lang["go_web_admin_username"] }: $goautouser
		{$lang["go_admin_pass"] }: $goautopass

		";
		
		mail($to,$subject,$message,$headers);
		echo "<div class=\"ce\">";
		echo "<font color=\"#428e00\" size=\"4\">{$lang["go_pass_sent"]} $sippyemail</font>";
		echo "</div>";		
	}



}



} else {
?>
<div id="signupOverlay" style="display:none;"></div>
<div id="signupBox">
<a id="signupClosebox" class="toolTip" title="<? echo $lang["go_close"];?>"></a>
<div id="signupoverlayContent"></div>
</div>
<form name="form_login" id="form_login" method="POST" action="https://<?=$_SERVER['SERVER_NAME']?>/index.php" class="curvebox">
<input type="hidden" name="ua_language" id="ua_language" value="<? echo $vgo_language; ?>">
	<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
        		<td valign="middle">
					<center>
						<table width="400px" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 20px; padding-right:0px;">
									<img src="<?=$COMPANYLOGO?>" width="150px" >
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td align="left" style="padding-left:35px;">
									<font color="black"><? echo $lang["go_username"]; ?>:</font>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input class="form_input_button" type="text" title="<? echo $lang["go_your_username"]; ?>" name="user_name" id="user_name" size="30">
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td align="left" style="padding-left:35px;">
									<font color="black"><? echo $lang["go_password"];  ?>:</font>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input class="form_input_button" type="password" title="<? echo $lang["go_your_pass"]; ?>" name="user_pass" id="user_pass" size="30">
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr> 
								<td align="center">
									<input src="<?=$LOGINBUTTON?>" title="<? echo $lang["go_login"]; ?>" style="vertical-align: middle;width:130px;height:40px;" type="image">
								</td>
							</tr>
							<tr><td style="font-size:5px;">&nbsp;</td></tr>
							<tr>
								<td align="center">&nbsp;<span id='messageid' style="color: red;"></span>&nbsp;</td>							
							</tr>
							</table>
					</center>
				</td>
			</tr>
    	</tbody>
    </table><br><br>
 
</form>
<div class="ce">
<br>
 <a class="ce" href='../credits'>GoAdmin &reg; <?echo $version;?></a> 

</div>
<?php
}
?>
</center>

 <div class='footer'>
   <table width="100%" >
				<tr><td align="center" style="color: #6a6363; font-size: 10px;"><? echo $lang["go_gad_ce"]; ?> <a href='../gplv2'>GPLv2</a>. <? echo $lang["go_ce1"]; ?></td></tr>
				<tr><td align="center" style="color: #6a6363; font-size: 10px;"><a href='http://goautodial.org'>GoAutoDial CE</a> &reg;, <a href='http://goautodial.com'>GoAutoDial</a> &reg;, <a href='http://justgocloud.com'>JustGoCloud</a> &reg; <? echo $lang["go_and"]; ?> <a href='http://justgovoip.com'>JustGoVoIP</a> &reg; <? echo $lang["go_registered_trademark"]; ?></td></tr>
            			<tr><td align="center" style="color: #6a6363; font-size: 10px;">&copy; <? echo $lang["go_gad_alr"]; ?></td></tr>
    </table>
   
</div>
</body>


</html>
