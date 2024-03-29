<?php
############################################################################################
####  Name:             go_colors_fresh.php                                             ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
####  Edited by:        GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>      ####
####  License:          AGPLv2                                                          ####
############################################################################################

header("Content-type: text/css");

if (file_exists("/etc/goautodial.conf")) {
	$conf_path = "/etc/goautodial.conf";
} elseif (file_exists("{$_SERVER['DOCUMENT_ROOT']}/goautodial.conf")) {
	$conf_path = "{$_SERVER['DOCUMENT_ROOT']}/goautodial.conf";
} else {
	die ("ERROR: 'goautodial.conf' file not found.");
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*|\[|\]/","",$DBCline);
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
        die ("ERROR: 'astguiclient.conf' file not found.");
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*/","",$DBCline);
                if (ereg("^VARDB_port", $DBCline))
                        {$VARDB_port = $DBCline;   $VARDB_port = preg_replace("/.*=/","",$VARDB_port);}
                }
        }


$golink=mysql_connect("$GOdbHostname:$VARDB_port", "$GOdbUsername", "$GOdbPassword");
if (!$golink)
        {
        die('MySQL connect ERROR: ' . mysql_error());
        }
mysql_select_db("$GOdbDatabase");

$stmt = "SELECT theme_color FROM go_server_settings";
$rslt = mysql_query($stmt, $golink);
$row=mysql_fetch_row($rslt);

$THEMECOLOR = '#'.$row[0];

?>

@charset "UTF-8";

html, body { margin:0; padding:0; font:12px; font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; }
html{background-color:#e5e5e5;}
/* html{background-color:#f9f9f9;} */
* html input,
* html .widget{border-color:#dfdfdf;}
textarea,
input[type="text"],
input[type="password"],
input[type="file"],
input[type="button"],
input[type="submit"],
input[type="reset"],
select{border-color:#dfdfdf;background-color:#fff;}
input[readonly]{background-color:#eee;}
body,
#wpbody, .form-table .pre{color:#333;}


/* WIDGETS TABLE BGCOLOR  FROM GRAY TO GREEN */
body>
.widget .widget-top,
.postbox h3,
.stuffbox h3
    {
        /* background:-moz-linear-gradient(bottom,#F7FFE9,#CAD9B1);
        background:-webkit-gradient(linear,left bottom,left top,from(#CAD9B1),to(#F7FFE9)); 
        border:#B8C6A1 .5px solid!important; color:#000; */
    }

.button,
.button-secondary,
.submit input,
input[type=button],
input[type=reset],
input[type=submit]
    {
        border-color:#bbb;
        color:#464646;
    }
    
.button:hover,
.button-secondary:hover,
.submit input:hover,
input[type=button]:hover,
input[type=button]:hover,
input[type=submit]:hover
    {
        color:#000;
        border-color:#666;
    }

a:hover {color: green;}
a:active,a:focus {color:#464646;}

#wphead #viewsite a:hover,
#adminmenu a:hover,
#adminmenu ul.wp-submenu a:hover,
#the-comment-list .comment a:hover,
#todaysstatus a:hover,
#media-upload a.del-link:hover,
div.dashboard-widget-submit input:hover,
.subsubsub a:hover,
.subsubsub a.current:hover,
.ui-tabs-nav a:hover,
.plugins .inactive a:hover,
#all-plugins-table .plugins .inactive a:hover,
#search-plugins-table .plugins .inactive a:hover
    {
        color:#000;
    }

/* MENU TEXTCOLOR */
a,
#adminmenu a,
#poststuff #edButtonPreview,
#poststuff #edButtonHTML,
#the-comment-list p.comment-author strong a,
#media-upload a.del-link,
#media-items a.delete,
.plugins a.delete,
.ui-tabs-nav a
    {
        color: #727272;
    }

#adminmenu #awaiting-mod,
#adminmenu .update-plugins,
#sidemenu a .update-plugins,
#todaysstatus .reallynow
    {
        background-color:#464646;
        color:#fff;
        -moz-box-shadow:#fff 0 -1px 0;
        -khtml-box-shadow:#fff 0 -1px 0;
        -webkit-box-shadow:#fff 0 -1px 0;
        box-shadow:#fff 0 -1px 0;
    }
    
#adminmenu li.current a #awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins
    {
        background-color:#464646;
        color:#fff;
        -moz-box-shadow:#fff 0 -1px 0;
        -khtml-box-shadow:#fff 0 -1px 0;
        -webkit-box-shadow:#fff 0 -1px 0;
        box-shadow:#fff 0 -1px 0;
    }


table.widefat span.delete a,
table.widefat span.trash a,
table.widefat span.spam a,
#dashboard_server_statistics .delete a,
#dashboard_server_statistics .trash a,
#dashboard_server_statistics .spam a
    {
        color:#bc0b0b;
    }
    
.widget,
#widget-list .widget-top,
.postbox,
#titlediv,
#poststuff .postarea,.stuffbox
    {
        border-color:#dfdfdf;
    }


/* WIDGETS TABLE TEXTCOLOR */
.widget,
.postbox
    {
        background-color:#fff;
    }
    

/*MAIN HEADER COLOR*/
.ui-sortable .postbox h3
    {
        color:#525252;
    }

/*MAIN HEADER HOVER COLOR*/
.widget .widget-top,.ui-sortable .postbox h3:hover
    {
        color: #4C7D24;
    } 

/* HEADER COLOR */

#wphead
    {
/*         border-bottom:#5c7a00 1px solid; */
        /*border-bottom:#000 1px solid;
        background:-moz-linear-gradient(bottom,#77a30a,#476000);
        background:-webkit-gradient(linear,left bottom,left top,from(#77a30a),to(#476000));
        box-shadow: 5px 5px 2px #BDBDBD;*/
        background: <?=$THEMECOLOR?>;
    }
/*
#wphead
    {
        border-bottom:#262626 1px solid;
        background:-moz-linear-gradient(bottom,#262626,#262626);
        background:-webkit-gradient(linear,left bottom,left top,from(#262626),to(#262626));
        box-shadow: none;
    }
*/
        
#wphead h1 a
    {
        color:#ffffff;
    }
    
#user_info, #clockbox
    {
        color:#ffffff;
    }
    
#user_info a:link,
#user_info a:visited
    {
        color:#ffffff;
        text-decoration:none;
    }
    
#user_info a:hover
    {
        color:#ccc;
        text-decoration:underline!important;
    }
    
#user_info a:active
    {
        color:#ccc!important;
    }
    
#site-title
    {
        font-weight: bold;
        font-style: italic !important;
    }

/* SCREEN OPTIONS COLOR */
#update-nag,
.update-nag
    {
        background:-moz-linear-gradient(bottom,#F7FFE9,#CAD9B1);
        background:-webkit-gradient(linear,left bottom,left top,from(#CAD9B1),to(#F7FFE9));
        border-color:#B8C6A1;color:#555;
    }

#screen-nag,
.screen-nag
    {
        background: transparent;
    }


/* FOOTER COLOR */
#footer
    {
         /*border-top:#5c7a00;
        background:-moz-linear-gradient(bottom,#476000,#77a30a);
        background:-webkit-gradient(linear,left bottom,left top,from(#5c7a00),to(#77a30a));
        position: relative; bottom: 2px;
        			background: #f6ffea;

        
       */
                   border:1px solid #cacdca;
			background: #f6ffea;
			/*box-shadow: #bfc5b4 2px 2px 2px;	*/



        color: #6a6363;
        
/*        height: 40px; width: 1280px;padding: 10px 0px 0px 5px; margin-bottom: 2px; 
  */      
    }
    
/*#footer a:link,
#footer a:visited
    {
        color:#39511c;
        text-decoration:none;
    }
    
#footer a:hover
    {
        color:#7c8e78;
        text-decoration:underline!important;
    }
    
#footer a:active
    {
        color:#ccc!important;
    }
    */

#adminmenu *
    {
        border-color:#D2D2D2;
    }
    
#adminmenu li.wp-menu-separator
    {
        background:transparent url(../img/menu-arrows.gif) no-repeat scroll left 5px;
    }
    
.folded #adminmenu li.wp-menu-separator
    {
        background:transparent url(../img/menu-arrows.gif) no-repeat scroll right -34px;
    }
    
#adminmenu li.wp-has-current-submenu.wp-menu-open .wp-menu-toggle,
#adminmenu li.wp-has-current-submenu:hover .wp-menu-toggle
    {
        background:transparent url(../img/menu-bits.gif?ver=20100610) no-repeat scroll left -207px;
    }
    
#adminmenu .wp-has-submenu:hover .wp-menu-toggle,
#adminmenu .wp-menu-open .wp-menu-toggle
    {
        background:transparent url(../img/menu-bits.gif?ver=20100610) no-repeat scroll left -109px;
    }

/* MENU SELECTED / CURRENT / OPEN */
#adminmenu a.current
    {
        color: #000;
    }

/* MENU SELECTED / CURRENT / OPEN */
#adminmenu li.wp-has-submenu.menu-top.wp-menu-open a
    {
        color: #000;
    }
    
#adminmenu a.menu-top
    {
        background:#f1f1f1 url(../img/menu-bits.gif?ver=20100610) repeat-x scroll left -379px;
    }
    
#adminmenu .wp-submenu a
    {
        background:#fff url(../img/menu-bits.gif?ver=20100610) no-repeat scroll 0 -310px;
    }
    
#adminmenu .wp-has-current-submenu ul li a
    {
        background:none;
    }

#adminmenu .wp-has-current-submenu ul li a.current
    {
        background:url(../img/menu.gif) top left no-repeat!important;
    }

.wp-has-current-submenu .wp-submenu
    {
        border-top:none!important;
    }
    
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu
    {
        border-bottom:#aaa 1px solid;
    }


/* MENU HEADER COLORING */
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
#adminmenu li.current a.menu-top{background:-moz-linear-gradient(bottom,#F7FFE9,#CAD9B1); background:-webkit-gradient(linear,left bottom,left top,from(#CAD9B1),to(#F7FFE9)); border:#D2D2D2 1px solid;color:#525252;}
#adminmenu li a:hover{color:#000!important;}
#adminmenu li.wp-has-current-submenu .wp-submenu,#adminmenu li.wp-has-current-submenu ul li a{}
#adminmenu li.wp-has-current-submenu ul li a{background:url(../img/menu-dark.gif) bottom left no-repeat!important;}
#adminmenu li.wp-has-current-submenu ul{border-bottom-color:#aaa;}
#adminmenu .wp-submenu .current a.current{background:transparent url(../img/menu-bits.gif?ver=20100610) no-repeat scroll 0 -289px;}

/* MENU HOVER BACKGROUND */
#adminmenu .wp-submenu a:hover{background-color:#DCEFC3!important;color:#333!important;}
#adminmenu .wp-submenu li.current,#adminmenu .wp-submenu li.current a,#adminmenu .wp-submenu li.current a:hover{color:#333;background-color:#f5f5f5;background-image:none;border-color:#D2D2D2;}
#adminmenu .wp-submenu ul{background-color:#fff;}
.folded #adminmenu li.menu-top,#adminmenu .wp-submenu .wp-submenu-head{background-color:#F1F1F1;}
.folded #adminmenu li.wp-has-current-submenu,.folded #adminmenu li.menu-top.current{background-color:#e6e6e6;}
#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head{background-color:#EAEAEA;border-color:#aaa;}
#adminmenu div.wp-submenu{background-color:transparent;}

/*** ICON DASHBOARD ***/
#adminmenu .menu-icon-dashboard div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -6px -22px; width: 34px; height: 34px;}    
#adminmenu .menu-icon-dashboard:hover div.wp-menu-image,
#adminmenu .menu-icon-dashboard.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-dashboard.current div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -6px 2px; width: 34px; height: 34px;}
    
/*** ICON DIALER ***/
#adminmenu .menu-icon-dialer div.wp-menu-image
{background:transparent url('../img/menu.png')  no-repeat -177px -22px; width: 34px; height: 34px;}
#adminmenu .menu-icon-dialer:hover div.wp-menu-image,
#adminmenu .menu-icon-dialer.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-dialer.current div.wp-menu-image
{background:transparent url('../img/menu.png')  no-repeat -177px 2px; width: 34px; height: 34px;}                                                           

/*** ICON REPORTS ***/
#adminmenu .menu-icon-reports div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -62px -22px; width: 34px; height: 34px;}
#adminmenu .menu-icon-reports:hover div.wp-menu-image,
#adminmenu .menu-icon-reports.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-reports.current div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -62px 2px; width: 34px; height: 34px;}
        
/*** ICON ADMIN ***/    
#adminmenu .menu-icon-admin div.wp-menu-image
{background:transparent url('../img/menu.png')  no-repeat -34px -22px; width: 34px; height: 34px;}
#adminmenu .menu-icon-admin:hover div.wp-menu-image,
#adminmenu .menu-icon-admin.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-admin.current div.wp-menu-image
{background:transparent url('../img/menu.png')  no-repeat -34px 2px; width: 34px; height: 34px;}
       
/*** ICON RECORDINGS ***/  
#adminmenu .menu-icon-recordings div.wp-menu-image
{background:transparent url('../img/menu.png')  no-repeat -93px -21px; width: 34px; height: 34px;}
#adminmenu .menu-icon-recordings:hover div.wp-menu-image,
#adminmenu .menu-icon-recordings.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-recordings.current div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -93px 3px;  width: 34px; height: 34px;}    
    
/*** ICON SUPPORT ***/    
#adminmenu .menu-icon-support div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -149px -23px; width: 34px; height: 34px;}
#adminmenu .menu-icon-support:hover div.wp-menu-image,
#adminmenu .menu-icon-support.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-support.current div.wp-menu-image
{background:transparent url('../img/menu.png') no-repeat -149px 1px; width: 34px; height: 34px;}
    
/*** ICON SETTINGS ***/    
#adminmenu .menu-icon-settings div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -130px -33px;}
#adminmenu .menu-icon-settings:hover div.wp-menu-image,
#adminmenu .menu-icon-settings.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-settings.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -130px -1px;}

/*** ICON DOC ***/    
#adminmenu .menu-icon-doc div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -163px -33px;}
#adminmenu .menu-icon-doc:hover div.wp-menu-image,
#adminmenu .menu-icon-doc.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-doc.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -163px -1px;}

/*** ICON AGENT ***/    
#adminmenu .menu-icon-agent div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -193px -33px;}
#adminmenu .menu-icon-agent:hover div.wp-menu-image,
#adminmenu .menu-icon-agent.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-agent.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -193px -1px;}

/*** ICON POST ***/    
#adminmenu .menu-icon-post div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -272px -33px;}
#adminmenu .menu-icon-post:hover div.wp-menu-image,
#adminmenu .menu-icon-post.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-post.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -272px -1px;}

/*** ICON PAGE ***/    
#adminmenu .menu-icon-page div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -151px -33px;}
#adminmenu .menu-icon-page:hover div.wp-menu-image,
#adminmenu .menu-icon-page.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-page.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -151px -1px;}

/*** ICON COMMENTS ***/    
#adminmenu .menu-icon-comments div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -31px -33px;}
#adminmenu .menu-icon-comments:hover div.wp-menu-image,
#adminmenu .menu-icon-comments.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-comments.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -31px -1px;}

/*** ICON APPEARANCE ***/ 
#adminmenu .menu-icon-appearance div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -1px -33px;}
#adminmenu .menu-icon-appearance:hover div.wp-menu-image,
#adminmenu .menu-icon-appearance.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-appearance.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -1px -1px;}

/*** ICON PLUGINS ***/    
#adminmenu .menu-icon-plugins div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -181px -33px;}
#adminmenu .menu-icon-plugins:hover div.wp-menu-image,
#adminmenu .menu-icon-plugins.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-plugins.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -181px -1px;}

/*** ICON USERS ***/ 
#adminmenu .menu-icon-users div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -301px -33px;}
#adminmenu .menu-icon-users:hover div.wp-menu-image,
#adminmenu .menu-icon-users.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-users.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -301px -1px;}

/*** ICON TOOLS ***/    
#adminmenu .menu-icon-tools div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -211px -33px;}
#adminmenu .menu-icon-tools:hover div.wp-menu-image,
#adminmenu .menu-icon-tools.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-tools.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -211px -1px;}

/*** ICON SETTINGS ***/    
#adminmenu .menu-icon-settings div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -130px -33px;}
#adminmenu .menu-icon-settings:hover div.wp-menu-image,
#adminmenu .menu-icon-settings.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-settings.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -130px -1px;}

/*** ICON SITE ***/    
#adminmenu .menu-icon-site div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -361px -33px;}
#adminmenu .menu-icon-site:hover div.wp-menu-image,
#adminmenu .menu-icon-site.wp-has-current-submenu div.wp-menu-image,
#adminmenu .menu-icon-site.current div.wp-menu-image
{background:transparent url('../img/menu.png?ver=20100531') no-repeat scroll -361px -1px;}

#screen-options-wrap,#contextual-help-wrap,#advanced-search-wrap{background-color:#f1f1f1;border-color:#dfdfdf;}
#screen-meta-links a.show-settings {color:#464646;}
#screen-meta-links a.show-settings:hover{color:#000;}
#quick-search-input {color: #8B8B8B; font-style: italic !important;}

.tdstats{color: transparent;}
.postbox:hover .handlediv{background:transparent url(../img/menu-bits.gif?ver=20100610) no-repeat scroll left -111px;}
.postbox:hover .actiondiv{background:transparent url(../img/reload.png) no-repeat scroll left 5px top 5px}
.postbox:hover .widgetconfig{/*background:transparent url(../img/settings-hover.png) no-repeat scroll left 5px top 5px;*/display:block;}
.postbox:hover .tdstats{color: #428e00;}
.closed .tdstats{color: #464646;}

#favorite-first{border-color:#c0c0c0;background:#f1f1f1;background:-moz-linear-gradient(bottom,#e7e7e7,#fff);background:-webkit-gradient(linear,left bottom,left top,from(#e7e7e7),to(#fff));}
#favorite-inside{border-color:#c0c0c0;background-color:#fff;}
#favorite-toggle{background:transparent url(../img/fav-arrow.gif?ver=20100531) no-repeat 0 -4px;}
#favorite-actions a{color:#464646;}
#favorite-actions a:hover{color:#000;}
#favorite-inside a:hover{text-decoration:underline;}

#screen-meta a.show-settings{background-color:transparent;text-shadow:rgba(255,255,255,0.7) 0 1px 0;}

#icon-dashboard
    {
    background:transparent url(../img/icons32.png) no-repeat -35px -22px; width: 44px; height: 36px;
    }
#icon-phone
    {
    background:transparent url(../img/icons32.png) no-repeat -149px -71px; width: 47px; height: 40px;
    }
#icon-user
    {
    background:transparent url(../img/icons32.png) no-repeat -388px -18px; width: 38px; height: 39px;
    }
#icon-team
    {
    background:transparent url(../img/icons32.png) no-repeat -1098px -19px; width: 35px; height: 41px;
    }
#icon-campaign
    {
    background:transparent url(../img/icons32.png) no-repeat -506px -72px; width: 46px; height: 40px;
    }
#icon-list
    {
    background:transparent url(../img/icons32.png) no-repeat -743px -72px; width: 47px; height: 40px;
    }
#icon-script
    {
    background:transparent url(../img/icons32.png) no-repeat -150px -132px; width: 52px; height: 39px;
    }
#icon-inbound
    {
    background:transparent url(../img/icons32.png) no-repeat -627px -72px; width: 47px; height: 39px;
    }
#icon-voicefile
    {
    background:transparent url(../img/icons32.png) no-repeat -687px -71px; width: 46px; height: 40px;
    }
#icon-repanalytic
    {
    background:transparent url(../img/icons32.png) no-repeat -226px -22px; width: 42px; height: 40px;
    }
#icon-callhistory
    {
    background:transparent url(../img/icons32.png) no-repeat -802px -71px; width: 45px; height: 45px;
    }
#icon-recording
    {
    background:transparent url(../img/icons32.png) no-repeat -860px -69px; width: 50px; height: 42px;
    }
#icon-support
    {
    background:transparent url(../img/icons32.png) no-repeat -949px -20px; width: 48px; height: 40px;
    }
#icon-search
    {
    background:transparent url(../img/icons32.png) no-repeat -33px -71px; width: 47px; height: 40px;
    }
#icon-ingroup
    {
    background:transparent url(../img/icons32.png) no-repeat -860px -69px; width: 50px; height: 42px;
    }
#icon-calltimes
    {
    background:transparent url(../img/icons32.png) no-repeat -96px -128px; width: 43px; height: 42px;
    }
#icon-carriers
    {
    background:transparent url(../img/icons32.png) no-repeat -446px -71px; width: 47px; height: 40px;
    }
#icon-settings
    {
    background:transparent url(../img/icons32.png) no-repeat -139px -22px; width: 34px; height: 34px;
    }
#icon-servers
    {
    background:transparent url(../img/icons32.png) no-repeat -210px -128px; width: 71px; height: 42px;
    }
#icon-usergroups
    {
    background:transparent url(../img/icons32.png) no-repeat -742px -71px; width: 48px; height: 40px;
    }
#icon-tenants
    {
    background:transparent url(../img/icons32.png) no-repeat -497px -134px; width: 47px; height: 40px;
    }
#icon-logs
    {
    background:transparent url(../img/icons32.png) no-repeat -387px -136px; width: 44px; height: 36px;
    }
a
/*
#icon-edit,#icon-post{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -552px -5px;}
#icon-record{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -370px -5px;}
#icon-upload{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -251px -5px;}
#icon-link-manager,#icon-link,#icon-link-category{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -190px -5px;}
#icon-edit-pages,#icon-page{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -312px -5px;}
#icon-edit-comments{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -72px -5px;}
#icon-themes{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -11px -5px;}
#icon-plugins{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -370px -5px;}
#icon-users,#icon-profile,#icon-user-edit{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -600px -5px;}
#icon-tools,#icon-admin{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -194px -5px;}
#icon-options-general{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -492px -5px;}
#icon-ms-admin{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -659px -5px;}
#icon-reports{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -3px -5px;}
#icon-support{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -72px -3px;}
#icon-systems{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -434px -3px;}
#icon-docs{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -310px -3px;}
#icon-agent{background:transparent url(../img/icons32.png?ver=20100531) no-repeat -600px -3px;}
*/

/* WIDGETS BODY TEXT HOVER PADDING */
#content { padding:10px 30px; }
a { color:#464646; text-decoration:none }
a:hover { text-decoration:underline }

/* STICKIES */
.ui-notify { width:350px; position:fixed; top:10px; right:10px; }
.ui-notify-message { padding:10px; margin-bottom:15px; -moz-border-radius:8px; -webkit-border-radius:8px; border-radius:8px }
.ui-notify-message h1 { font-size:14px; margin:0; padding:0 }
.ui-notify-message p { margin:3px 0; padding:0; line-height:18px }
.ui-notify-message a { color: #FFF; text-decoration: underline }
.ui-notify-message:last-child { margin-bottom:0 }
.ui-notify-message-style { background:#000; background:rgba(0,0,0,0.8); -moz-box-shadow: 0 0 6px #000; -webkit-box-shadow: 0 0 6px #000; box-shadow: 0 0 6px #000; }
.ui-notify-message-style h1 { color:#fff; font-weight:bold }
.ui-notify-message-style p { color:#fff }
.ui-notify-close { color:#fff; text-decoration:underline }
.ui-notify-click { cursor:pointer }
.ui-notify-cross { margin-top:-4px; float:right; cursor:pointer; text-decoration:none; font-size:12px; font-weight:bold; text-shadow:0 1px 1px #fff; padding:2px }
.ui-notify-cross:hover { color:#ffffab }
.ui-notify-cross:active { position:relative; top:1px }
