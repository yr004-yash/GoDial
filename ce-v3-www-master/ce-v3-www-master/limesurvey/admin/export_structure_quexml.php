<?php
/*
 * LimeSurvey
 * Copyright (C) 2007 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * $Id: export_structure_quexml.php 9671 2010-12-21 20:02:24Z c_schmitz $
 */


//Ensure script is not run directly, avoid path disclosure
include_once("login_check.php");



if (!isset($surveyid)) {$surveyid=returnglobal('sid');}

if (isset($surveyprintlang) && !empty($surveyprintlang))
	$quexmllang = $surveyprintlang;
	else
	$quexmllang=GetBaseLanguageFromSurveyID($surveyid);

$qlang = new limesurvey_lang($quexmllang);

if (!$surveyid)
{
	echo $htmlheader
		."<br />\n"
		."<table width='350' align='center' style='border: 1px solid #555555' cellpadding='1' cellspacing='0'>\n"
		."\t<tr bgcolor='#555555'><td colspan='2' height='4'><font size='1' face='verdana' color='white'><strong>"
		._EXPORTSURVEY."</strong></td></tr>\n"
		."\t<tr><td align='center'>\n"
		."$setfont<br /><strong><font color='red'>"
		._ERROR."</font></strong><br />\n"
		._ES_NOSID."<br />\n"
		."<br /><input type='submit' $btstyle value='"
		._GO_ADMIN."' onClick=\"window.open('$scriptname', '_top')\">\n"
		."\t</td></tr>\n"
		."</table>\n"
		."</body></html>\n";
	exit;
}





function cleanup($string)
{
	return trim(strip_tags(str_ireplace("<br />","\n",$string)));
}


function create_free($f,$len,$lab="")
{
	global $dom;
	$free = $dom->create_element("free");

	$format = $dom->create_element("format");
	$format->set_content(cleanup($f));

	$length = $dom->create_element("length");
	$length->set_content(cleanup($len));

	$label = $dom->create_element("label");
	$label->set_content(cleanup($lab));

	$free->append_child($format);
	$free->append_child($length);
	$free->append_child($label);


	return $free;
}


function fixed_array($array)
{
	global $dom;
	$fixed = $dom->create_element("fixed");

	foreach ($array as $key => $v)
	{
		$category = $dom->create_element("category");

		$label = $dom->create_element("label");
		$label->set_content(cleanup("$key"));

		$value= $dom->create_element("value");
		$value->set_content(cleanup("$v"));

		$category->append_child($label);
		$category->append_child($value);

		$fixed->append_child($category);
	}


	return $fixed;
}

/**
 * Calculate if this item should have a skipTo element attached to it
 * 
 * @param mixed $qid   
 * @param mixed $value 
 * 
 * @return bool|string Text of item to skip to otherwise false if nothing to skip to
 * @author Adam Zammit <adam.zammit@acspri.org.au>
 * @since  2010-10-28
 */
function skipto($qid,$value,$cfieldname = "")
{
	global $connect ;
	global $dbprefix ;
	global $surveyid ;
	global $qlang ;

	$zeros = $connect->qstr("0000000000");

	$Query = "SELECT q.*," . $connect->concat("RIGHT(" . $connect->concat($zeros,'g.gid') . ",10)","RIGHT(". $connect->concat($zeros,'q.question_order') .",10)") ." as globalorder
                  FROM {$dbprefix}questions as q, {$dbprefix}questions as q2, {$dbprefix}groups as g, {$dbprefix}groups as g2
                  WHERE q.parent_qid = 0 
                  AND q2.parent_qid = 0
                  AND q.sid=$surveyid
                  AND q2.sid=$surveyid
                  AND q2.qid = $qid       
                  AND g2.gid =q2.gid                      
                  AND g.gid = q.gid
                  AND " . $connect->concat("RIGHT(" . $connect->concat($zeros,'g.gid') . ",10)","RIGHT(". $connect->concat($zeros,'q.question_order') .",10)") ." > " . $connect->concat("RIGHT(" . $connect->concat($zeros,'g2.gid') . ",10)","RIGHT(". $connect->concat($zeros,'q2.question_order') .",10)") ."
                  ORDER BY globalorder";

	$QueryResult = db_execute_assoc($Query);
	
	$nextqid="";
	$nextorder="";

	$Row = $QueryResult->FetchRow();
	if ($Row)
	{
		$nextqid = $Row['qid'];
		$nextorder = $Row['globalorder'];
	}
	else
		return false;


	$Query = "SELECT q.*
		FROM {$dbprefix}questions as q
		JOIN {$dbprefix}groups as g ON (g.gid = q.gid)
		LEFT JOIN {$dbprefix}conditions as c ON (c.cqid = '$qid' AND c.qid = q.qid AND c.method LIKE '==' AND c.value NOT LIKE '$value' $cfieldname)
		WHERE q.sid = $surveyid
		AND q.parent_qid = 0
		AND " . $connect->concat("RIGHT(" . $connect->concat($zeros,'g.gid') . ",10)","RIGHT(". $connect->concat($zeros,'q.question_order') .",10)") ." >= $nextorder
		AND c.cqid IS NULL
		ORDER BY  " . $connect->concat("RIGHT(" . $connect->concat($zeros,'g.gid') . ",10)","RIGHT(". $connect->concat($zeros,'q.question_order') .",10)"); 


	$QueryResult = db_execute_assoc($Query);

	$Row = $QueryResult->FetchRow();
	if ($Row)
	{
		if ($nextqid == $Row['qid'])
			return false;
		else
			return $Row['title'];
	}
	else
		return $qlang->gT("End");

}



function create_fixed($qid,$rotate=false,$labels=true,$scale=0,$other=false,$varname="")
{
	global $dom;
	global $connect ;
	global $dbprefix ; 
	global $quexmllang;
	global $qlang;

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

	if ($labels)
		$Query = "SELECT * FROM {$dbprefix}labels WHERE lid = $labels  AND language='$quexmllang' ORDER BY sortorder ASC";
	else
		$Query = "SELECT code,answer as title,sortorder FROM {$dbprefix}answers WHERE qid = $qid AND scale_id = $scale  AND language='$quexmllang' ORDER BY sortorder ASC";

	$QueryResult = db_execute_assoc($Query);

	$fixed = $dom->create_element("fixed");

	$nextcode = "";

	while ($Row = $QueryResult->FetchRow())
	{
		$category = $dom->create_element("category");

		$label = $dom->create_element("label");
		$label->set_content(cleanup($Row['title']));

		$value= $dom->create_element("value");
		$value->set_content(cleanup($Row['code']));

		$category->append_child($label);
		$category->append_child($value);

		$st = skipto($qid,$Row['code']);
		if ($st !== false)
		{
			$skipto = $dom->create_element("skipTo");
			$skipto->set_content($st);
			$category->append_child($skipto);	
		}


		$fixed->append_child($category);
		$nextcode = $Row['code'];
	}

	if ($other)
	{
		$category = $dom->create_element("category");

		$label = $dom->create_element("label");
		$label->set_content(get_length($qid,"other_replace_text",$qlang->gT("Other")));

		$value= $dom->create_element("value");

		$value->set_content('-oth-');

		$category->append_child($label);
		$category->append_child($value);	    

		$contingentQuestion = $dom->create_element("contingentQuestion");
		$length = $dom->create_element("length");
		$text = $dom->create_element("text");

		$text->set_content(get_length($qid,"other_replace_text",$qlang->gT("Other")));
		$length->set_content(24);
		$contingentQuestion->append_child($text);
		$contingentQuestion->append_child($length);
		$contingentQuestion->set_attribute("varName",$varname . 'other');

		$category->append_child($contingentQuestion);

		$fixed->append_child($category);
	}

	if ($rotate) $fixed->set_attribute("rotate","true");

	return $fixed;
}

function get_length($qid,$attribute,$default)
{
	global $dom;
	global $dbprefix;
	global $connect ;
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

	$Query = "SELECT value FROM {$dbprefix}question_attributes WHERE qid = $qid AND attribute = '$attribute'";
	//$QueryResult = mysql_query($Query) or die ("ERROR: $QueryResult<br />".mysql_error());
	$QueryResult = db_execute_assoc($Query);

	$Row = $QueryResult->FetchRow();
	if ($Row && !empty($Row['value']))
		return $Row['value'];
	else
		return $default;

}


function create_multi(&$question,$qid,$varname,$scale_id = false,$free = false,$other = false)
{
	global $dom;
	global $dbprefix;
	global $connect ;
	global $quexmllang ;
	global $surveyid;
	global $qlang;

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

	$Query = "SELECT * FROM {$dbprefix}questions WHERE parent_qid = $qid  AND language='$quexmllang' ";
	if ($scale_id != false) $Query .= " AND scale_id = $scale_id ";
	$Query .= " ORDER BY question_order ASC";
	//$QueryResult = mysql_query($Query) or die ("ERROR: $QueryResult<br />".mysql_error());
	$QueryResult = db_execute_assoc($Query);

	$nextcode = "";

	while ($Row = $QueryResult->FetchRow())
	{
		$response = $dom->create_element("response");
		if ($free == false)
		{
			$fixed = $dom->create_element("fixed");
			$category = $dom->create_element("category");

			$label = $dom->create_element("label");
			$label->set_content(cleanup($Row['question']));

			$value= $dom->create_element("value");
			//$value->set_content(cleanup($Row['title']));
			$value->set_content("1");
			$nextcode = $Row['title'];

			$category->append_child($label);
			$category->append_child($value);

			$st = skipto($qid,'Y'," AND c.cfieldname LIKE '+$surveyid" . "X" . $Row['gid'] . "X" . $qid . $Row['title'] . "' ");
			if ($st !== false)
			{
				$skipto = $dom->create_element("skipTo");
				$skipto->set_content($st);
				$category->append_child($skipto);	
			}


			$fixed->append_child($category);

			$response->append_child($fixed);
		}
		else
			$response->append_child(create_free($free['f'],$free['len'],$Row['question']));

		$response->set_attribute("varName",$varname . cleanup($Row['title']));

		$question->append_child($response);
	}

	if ($other && $free==false)
	{
		$response = $dom->create_element("response");
		$fixed = $dom->create_element("fixed");
		$category = $dom->create_element("category");

		$label = $dom->create_element("label");
		$label->set_content(get_length($qid,"other_replace_text",$qlang->gT("Other")));

		$value= $dom->create_element("value");

		//Get next code
		if (is_numeric($nextcode))
			$nextcode++;
		else if (is_string($nextcode))
			$nextcode = chr(ord($nextcode) + 1);

		$value->set_content(1);

		$category->append_child($label);
		$category->append_child($value);	    

		$contingentQuestion = $dom->create_element("contingentQuestion");
		$length = $dom->create_element("length");
		$text = $dom->create_element("text");

		$text->set_content(get_length($qid,"other_replace_text",$qlang->gT("Other")));
		$length->set_content(24);
		$contingentQuestion->append_child($text);
		$contingentQuestion->append_child($length);
		$contingentQuestion->set_attribute("varName",$varname . 'other');

		$category->append_child($contingentQuestion);

		$fixed->append_child($category);
		$response->append_child($fixed);
		$response->set_attribute("varName",$varname . cleanup($nextcode));

		$question->append_child($response);
	}




	return;

}

function create_subQuestions(&$question,$qid,$varname,$use_answers = false)
{
	global $dom;
	global $dbprefix;
	global $connect ;
	global $quexmllang ;
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	if ($use_answers)
		$Query = "SELECT answer as question, code as title FROM {$dbprefix}answers WHERE qid = $qid  AND language='$quexmllang' ORDER BY sortorder ASC";
	else
		$Query = "SELECT * FROM {$dbprefix}questions WHERE parent_qid = $qid and scale_id = 0  AND language='$quexmllang' ORDER BY question_order ASC";
	$QueryResult = db_execute_assoc($Query);
	while ($Row = $QueryResult->FetchRow())
	{
		$subQuestion = $dom->create_element("subQuestion");
		$text = $dom->create_element("text");
		$text->set_content(cleanup($Row['question']));
		$subQuestion->append_child($text);
		$subQuestion->set_attribute("varName",$varname . cleanup($Row['title']));
		$question->append_child($subQuestion);
	}

	return;
}

global $dbprefix;
global $connect ;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$dom = domxml_new_doc("1.0");


//Title and survey id
$questionnaire = $dom->create_element("questionnaire");
$title = $dom->create_element("title");

$Query = "SELECT * FROM {$dbprefix}surveys,{$dbprefix}surveys_languagesettings WHERE sid=$surveyid and surveyls_survey_id=sid and surveyls_language='".$quexmllang."'";
$QueryResult = db_execute_assoc($Query);
$Row = $QueryResult->FetchRow();
$questionnaire->set_attribute("id", $Row['sid']);
$title->set_content(cleanup($Row['surveyls_title']));
$questionnaire->append_child($title);

//investigator and datacollector
$investigator = $dom->create_element("investigator");
$name = $dom->create_element("name");
$name = $dom->create_element("firstName");
$name = $dom->create_element("lastName");
$dataCollector = $dom->create_element("dataCollector");

$questionnaire->append_child($investigator);
$questionnaire->append_child($dataCollector);

//questionnaireInfo == welcome
if (!empty($Row['surveyls_welcometext']))
{
	$questionnaireInfo = $dom->create_element("questionnaireInfo");
	$position = $dom->create_element("position");
	$text = $dom->create_element("text");
	$administration = $dom->create_element("administration");
	$position->set_content("before");
	$text->set_content(cleanup($Row['surveyls_welcometext']));
	$administration->set_content("self");
	$questionnaireInfo->append_child($position);
	$questionnaireInfo->append_child($text);
	$questionnaireInfo->append_child($administration);
	$questionnaire->append_child($questionnaireInfo);
}

if (!empty($Row['surveyls_endtext']))
{
	$questionnaireInfo = $dom->create_element("questionnaireInfo");
	$position = $dom->create_element("position");
	$text = $dom->create_element("text");
	$administration = $dom->create_element("administration");
	$position->set_content("after");
	$text->set_content(cleanup($Row['surveyls_endtext']));
	$administration->set_content("self");
	$questionnaireInfo->append_child($position);
	$questionnaireInfo->append_child($text);
	$questionnaireInfo->append_child($administration);
	$questionnaire->append_child($questionnaireInfo);
}

//section == group


$Query = "SELECT * FROM {$dbprefix}groups WHERE sid=$surveyid AND language='$quexmllang' order by group_order ASC";
$QueryResult = db_execute_assoc($Query);

//for each section
while ($Row = $QueryResult->FetchRow())
{
	$gid = $Row['gid'];

	$section = $dom->create_element("section");

	if (!empty($Row['group_name']))
	{
		$sectionInfo = $dom->create_element("sectionInfo");
		$position = $dom->create_element("position");
		$text = $dom->create_element("text");
		$administration = $dom->create_element("administration");
		$position->set_content("title");
		$text->set_content(cleanup($Row['group_name']));
		$administration->set_content("self");
		$sectionInfo->append_child($position);
		$sectionInfo->append_child($text);
		$sectionInfo->append_child($administration);
		$section->append_child($sectionInfo);
	}


	if (!empty($Row['description']))
	{
		$sectionInfo = $dom->create_element("sectionInfo");	
		$position = $dom->create_element("position");
		$text = $dom->create_element("text");
		$administration = $dom->create_element("administration");
		$position->set_content("before");
		$text->set_content(cleanup($Row['description']));
		$administration->set_content("self");
		$sectionInfo->append_child($position);
		$sectionInfo->append_child($text);
		$sectionInfo->append_child($administration);
		$section->append_child($sectionInfo);
	}



	$section->set_attribute("id", $gid);

	//boilerplate questions convert to sectionInfo elements
	$Query = "SELECT * FROM {$dbprefix}questions WHERE sid=$surveyid AND gid = $gid AND type LIKE 'X'  AND language='$quexmllang' ORDER BY question_order ASC";
	$QR = db_execute_assoc($Query);
	while ($RowQ = $QR->FetchRow())
	{
		$sectionInfo = $dom->create_element("sectionInfo");
		$position = $dom->create_element("position");
		$text = $dom->create_element("text");
		$administration = $dom->create_element("administration");

		$position->set_content("before");
		$text->set_content(cleanup($RowQ['question']));
		$administration->set_content("self");
		$sectionInfo->append_child($position);
		$sectionInfo->append_child($text);
		$sectionInfo->append_child($administration);

		$section->append_child($sectionInfo);
	}



	//foreach question
	$Query = "SELECT * FROM {$dbprefix}questions WHERE sid=$surveyid AND gid = $gid AND parent_qid=0 AND language='$quexmllang' AND type NOT LIKE 'X' ORDER BY question_order ASC";
	$QR = db_execute_assoc($Query);
	while ($RowQ = $QR->FetchRow())
	{
		$question = $dom->create_element("question");
		$type = $RowQ['type'];
		$qid = $RowQ['qid'];

		$other = false;
		if ($RowQ['other'] == 'Y') $other = true;

		//create a new text element for each new line
		$questiontext = explode('<br />',$RowQ['question']);
		foreach ($questiontext as $qt)
		{
			$txt = cleanup($qt);
			if (!empty($txt))
			{
				$text = $dom->create_element("text");
				$text->set_content($txt);	
				$question->append_child($text);
			}
		}


		//directive
		if (!empty($RowQ['help']))
		{
			$directive = $dom->create_element("directive");
			$position = $dom->create_element("position");
			$position->set_content("during");
			$text = $dom->create_element("text");
			$text->set_content(cleanup($RowQ['help']));
			$administration = $dom->create_element("administration");
			$administration->set_content("self");

			$directive->append_child($position);
			$directive->append_child($text);
			$directive->append_child($administration);

			$question->append_child($directive);
		}

		$response = $dom->create_element("response");
		$sgq = $surveyid . "X" . $gid . "X" . $qid;
		$response->set_attribute("varName",$sgq);

		switch ($type)
		{
			case "X": //BOILERPLATE QUESTION - none should appear

				break;
			case "5": //5 POINT CHOICE radio-buttons
				$response->append_child(fixed_array(array("1" => 1,"2" => 2,"3" => 3,"4" => 4,"5" => 5)));
			$question->append_child($response);
			break;
			case "D": //DATE
				$response->append_child(create_free("date","8",""));
			$question->append_child($response);
			break;
			case "L": //LIST drop-down/radio-button list
				$response->append_child(create_fixed($qid,false,false,0,$other,$sgq));
			$question->append_child($response);
			break;
			case "!": //List - dropdown
				$response->append_child(create_fixed($qid,false,false,0,$other,$sgq));
			$question->append_child($response);
			break;
			case "O": //LIST WITH COMMENT drop-down/radio-button list + textarea
				$response->append_child(create_fixed($qid,false,false,0,$other,$sgq));
			$question->append_child($response);
			//no comment - this should be a separate question
			break;
			case "R": //RANKING STYLE
				create_subQuestions($question,$qid,$sgq,true);
			$Query = "SELECT COUNT(*) as sc FROM {$dbprefix}answers WHERE qid = $qid AND language='$quexmllang' ";
			$QRE = db_execute_assoc($Query);
			//$QRE = mysql_query($Query) or die ("ERROR: $QRE<br />".mysql_error());
			//$QROW = mysql_fetch_assoc($QRE);
			$QROW = $QRE->FetchRow();
			$response->append_child(create_free("integer",strlen($QROW['sc']),""));
			$question->append_child($response);
			break;
			case "M": //Multiple choice checkbox
				create_multi($question,$qid,$sgq,false,false,$other);
			break;
			case "P": //Multiple choice with comments checkbox + text
				//Not yet implemented
				create_multi($question,$qid,$sgq,false,false,$other);
			//no comments added
			break;
			case "Q": //MULTIPLE SHORT TEXT
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(create_free("text",get_length($qid,"maximum_chars","10"),""));
			$question->append_child($response);
			break;
			case "K": //MULTIPLE NUMERICAL
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(create_free("integer",get_length($qid,"maximum_chars","10"),""));
			$question->append_child($response);
			break;
			case "N": //NUMERICAL QUESTION TYPE
				$response->append_child(create_free("integer",get_length($qid,"maximum_chars","10"),get_length($qid,"prefix","")));
			$question->append_child($response);
			break;
			case "S": //SHORT FREE TEXT
				$response->append_child(create_free("text",get_length($qid,"maximum_chars","240"),get_length($qid,"prefix","")));
			$question->append_child($response);
			break;
			case "T": //LONG FREE TEXT
				$response->append_child(create_free("longtext",get_length($qid,"display_rows","40"),get_length($qid,"prefix","")));
			$question->append_child($response);
			break;
			case "U": //HUGE FREE TEXT
				$response->append_child(create_free("longtext",get_length($qid,"display_rows","80"),get_length($qid,"prefix","")));
			$question->append_child($response);
			break;
			case "Y": //YES/NO radio-buttons
				$response->append_child(fixed_array(array($qlang->gT("Yes") => 'Y',$qlang->gT("No") => 'N')));
			$question->append_child($response);
			break;
			case "G": //GENDER drop-down list
				$response->append_child(fixed_array(array($qlang->gT("Female") => 'F',$qlang->gT("Male") => 'M')));
			$question->append_child($response);
			break;
			case "A": //ARRAY (5 POINT CHOICE) radio-buttons
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(fixed_array(array("1" => 1,"2" => 2,"3" => 3,"4" => 4,"5" => 5)));
			$question->append_child($response);
			break;
			case "B": //ARRAY (10 POINT CHOICE) radio-buttons
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(fixed_array(array("1" => 1,"2" => 2,"3" => 3,"4" => 4,"5" => 5,"6" => 6,"7" => 7,"8" => 8,"9" => 9,"10" => 10)));
			$question->append_child($response);
			break;
			case "C": //ARRAY (YES/UNCERTAIN/NO) radio-buttons
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(fixed_array(array($qlang->gT("Yes") => 'Y',$qlang->gT("Uncertain") => 'U',$qlang->gT("No") => 'N')));
			$question->append_child($response);
			break;
			case "E": //ARRAY (Increase/Same/Decrease) radio-buttons
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(fixed_array(array($qlang->gT("Increase") => 'I',$qlang->gT("Same") => 'S',$qlang->gT("Decrease") => 'D')));
			$question->append_child($response);
			break;
			case "F": //ARRAY (Flexible) - Row Format
				//select subQuestions from answers table where QID
				create_subQuestions($question,$qid,$sgq);
			$response->append_child(create_fixed($qid,false,false,0,$other,$sgq));
			$question->append_child($response);
			//select fixed responses from
			break;
			case "H": //ARRAY (Flexible) - Column Format
				create_subQuestions($question,$RowQ['qid'],$sgq);
			$response->append_child(create_fixed($qid,true,false,0,$other,$sgq));
			$question->append_child($response);
			break;
			case "1": //Dualscale multi-flexi array
				//select subQuestions from answers table where QID
				create_subQuestions($question,$qid,$sgq);
			$response = $dom->create_element("response");
			$response->append_child(create_fixed($qid,false,false,0,$other,$sgq)); 
			$response2 = $dom->create_element("response");  
			$response2->set_attribute("varName",cleanup($sgq) . "_2");
			$response2->append_child(create_fixed($qid,false,false,1,$other,$sgq));   
			$question->append_child($response);
			$question->append_child($response2);  
			break;
			case ":": //multi-flexi array numbers
				create_subQuestions($question,$qid,$sgq);
			//get multiflexible_checkbox - if set then each box is a checkbox (single fixed response)
			$mcb  = get_length($qid,'multiflexible_checkbox',-1);
			if ($mcb != -1)
				create_multi($question,$qid,$sgq,1);
			else
			{
				//get multiflexible_max - if set then make boxes of max this width
				$mcm = strlen(get_length($qid,'multiflexible_max',1));
				create_multi($question,$qid,$sgq,1,array('f' => 'integer', 'len' => $mcm, 'lab' => ''));
			}
			break;
			case ";": //multi-flexi array text
				create_subQuestions($question,$qid,$sgq);
			//foreach question where scale_id = 1 this is a textbox
			create_multi($question,$qid,$sgq,1,array('f' => 'text', 'len' => 10, 'lab' => ''));
			break;
			case "^": //SLIDER CONTROL - not supported
				$response->append_child(fixed_array(array("NOT SUPPORTED:$type" => 1)));
			$question->append_child($response);
			break;
		} //End Switch




		$section->append_child($question);
	}


	$questionnaire->append_child($section);
}


$dom->append_child($questionnaire);

$quexml =  $dom->dump_mem(true,'UTF-8');

if (!(isset($noheader) && $noheader == true))
{
	$fn = "survey_{$surveyid}_{$quexmllang}.xml";
	header("Content-Type: text/xml");
	header("Content-Disposition: attachment; filename=$fn");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Pragma: public");                          // HTTP/1.0

	echo $quexml;	
	exit();
}
?>
