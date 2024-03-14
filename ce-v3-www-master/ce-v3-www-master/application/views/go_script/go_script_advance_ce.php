<?php
############################################################################################
####  Name:             go_script_advance_ce.php                                        ####
####  Type:             ci view (template for users)                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>

<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_script/go_script_ce.css">
<script type="text/javascript" src="<?=base_url()?>js/go_script/go_script_advance_ce.js"></script>
<script>
    $(".toolTip").tipTip();
</script>

<div class="script-advance-container">
    <div class="script-advance-tab-container">
         <div class="script-advance-tab corner-top script-advance-selected" id="vicidial">Vicidial</div>
         <div class="script-advance-tab corner-top" id="settings"><? echo lang('go_Scriptsettings'); ?></div>
         <div class="script-advance-tab corner-top" id="modify"><? echo lang('go_ModifyScript'); ?></div>
         <div class="script-advance-tab corner-top" id="add"><? echo lang('go_Addquestions'); ?></div>
         <br class="clear"/>
    </div>
    <div class="script-advance-panel">
        <?php
                   if($script[0]->active == "Y"){
                        $go_NoteExistingLimesurveyscriptscantbeeditedCreateanewoneifyouneedtoaddeditquestionslang = lang('go_NoteExistingLimesurveyscriptscantbeeditedCreateanewoneifyouneedtoaddeditquestions');
                        echo "<br/><span style='font-style:italic;color:red;font-size:10px;float:left;width:100%;'>$go_NoteExistingLimesurveyscriptscantbeeditedCreateanewoneifyouneedtoaddeditquestionslang</span><br/>";
                        $disable = "disabled";
                        $disabled = array('disabled'=>true);

                   } else {
              
                        $disable = "";
                        $disabled = array();
 
                   }
         ?>
         <div class="script-advance-left">
            <div class="script-vicidial-config">

                <br/>
                <div class="script-advance-label"><? echo lang('go_ScriptID_'); ?></div>
                <div class="script-advance-value"><?=$vicidial_script[0]->script_id.form_hidden('script_id',$vicidial_script[0]->script_id)?></div><br class="clear"/>
                <div class="script-advance-label"><? echo lang('go_ScriptName_'); ?></div>
                <div class="script-advance-value"><?=form_input("script_name",$vicidial_script[0]->script_name,"id='script_name' $disable")?></div><br/>
                <div class="script-advance-label"><? echo lang('go_ScriptComments_'); ?></div>
                <div class="script-advance-value"><?=form_input("script_comments",$vicidial_script[0]->script_comments,"id='script_comments' $disable")?></div><br/>
                <div class="script-advance-label"><? echo lang('go_Active_'); ?></div>
                <div class="script-advance-value"><?=form_dropdown("active",array('Y'=>'Yes','N'=>'No'),$vicidial_script[0]->active,"id='active' $disable")?></div><br/>
                <div class="script-advance-label">Script Text:</div>
                <div class="script-advance-value">
                     <div class="script_text">
                        <?php
                              echo form_dropdown(null,$fields,"$disable").(!empty($disable)?" &nbsp;Insert":"&nbsp;<a id='script-insert-field' onclick='updatetextarea(this)'>Insert</a><br class='clear'/>");
                              echo form_textarea(array_merge(array('id'=>"script_text",'cols'=>'50','rows'=>'10',
                                                                   'name'=>'script_text','value'=> htmlspecialchars_decode(  htmlentities($vicidial_script[0]->script_text))
                                                             ),$disabled ));
                        ?>
                     </div>
                </div>
            </div>
            <div class="script-advance-settings" >
               <br/>

               <div class="script-advance-label"><? echo lang('go_SurveyURL'); ?></div>
               <div class="script-advance-value"><a href="http://<?=$_SERVER['HTTP_HOST']?>/limesurvey/index.php?sid=<?=$script[0]->sid?>&lang=en">http://<?=$_SERVER['HTTP_HOST']?>/limesurvey/index.php?sid=<?=$script[0]->sid?>&lang=en</a></div><br class="clear"/>
               <div class="script-advance-label"><? echo lang('go_SurveyName'); ?></div>
               <div class="script-advance-value"><?=form_input('surveyls_title',$script[0]->surveyls_title,"id='surveyls_title' size='25' $disable")?></div><br/>
               <div class="script-advance-label"><? echo lang('go_SurveyDescription'); ?></div>
               <div class="script-advance-value">
                                                <?php
                                                     $attr = array_merge(array('name'=>'surveyls_description','value'=>$script[0]->surveyls_urldescription,'cols'=>'45', 'rows'=>'5'),$disabled);
                                                     echo form_textarea($attr)?></div><br/>
               <div class="script-advance-label"><? echo lang('go_WelcomeMessage'); ?></div>
               <div class="script-advance-value"><?php
                                                     $attr = array_merge(array('name'=>'welcom_message','value'=>$script[0]->surveyls_welcometext,'cols'=>'45', 'rows'=>'5'),$disabled);
                                                     echo form_textarea($attr)?></div><br/>
               <div class="script-advance-label"><? echo lang('go_EndMessage'); ?></div>
               <div class="script-advance-value"><?php
                                                     $attr = array_merge(array('name'=>'end_message','value'=>$script[0]->surveyls_endtext,'cols'=>'45', 'rows'=>'5'),$disabled);
                                                     echo form_textarea($attr)?></div><br/>
               <div class="script-advance-label"><? echo lang('go_Active_'); ?></div>
               <div class="script-advance-value"><?=form_dropdown('active',array('Y'=>'Yes','N'=>'No'),$script[0]->active)?></div><br/>
               <div class="script-advance-label"><? echo lang('go_BaseLanguage'); ?></div>
               <div class="script-advance-value"><?=$script[0]->surveyls_language.form_hidden(array("sid"=>$script[0]->sid,'script_id'=>$script[0]->script_id))?></div><br  class="clear"/>
               <div class="script-advance-label"><? echo lang('go_Administrator'); ?></div>
               <div class="script-advance-value"><?=form_input("admin",$script[0]->admin,"size='25' $disable")?></div><br/>
               <div class="script-advance-label"><? echo lang('go_AdminEmail'); ?></div>
               <div class="script-advance-value"><?=form_input('adminemail',$script[0]->adminemail,"size='25' $disable")?></div><br/>
               <div class="script-advance-label"><? echo lang('go_EndURL'); ?></div>
               <div class="script-advance-value"><?=form_input('surveyls_url',$script[0]->surveyls_url,"size='25' $disable")?></div><br/>
               <div class="script-advance-label"><? echo lang('go_EndURLDescription'); ?></div>
               <div class="script-advance-value"><?=form_input('surveyls_urldescription',$script[0]->surveyls_urldescription,"size='25' $disable")?></div><br/>
               <div class="script-advance-label"><? echo lang('go_DecimalSeparator'); ?></div>
               <div class="script-advance-value"><?=form_dropdown('surveyls_numberformat',$radixpoint,$script[0]->surveyls_numberformat,"$disable")?></div><br/>
            </div>
            <div class="script-advance-modify_question">
               <br/>
               <div class="script-advance-thead">
                   <div class="script-advance-th">ID</div>
                   <div class="script-advance-th"><? echo lang('go_TITLE'); ?></div>
                   <div class="script-advance-th"><? echo lang('go_QUESTION'); ?></div>
                   <div class="script-advance-th"><? echo lang('go_TYPE'); ?></div>
                   <div class="script-advance-th"><? echo lang('go_MANDATORY'); ?></div>
               </div>
               <?foreach($questions as $question){?>
                   <div id="<?=$question->qid?>" class="script-advance-row">
                       <div class="script-advance-col"><?=$question->qid?></div>
                       <div class="script-advance-col"><?=(empty($question->title)?"&nbsp;":$question->title)?></div>
                       <?preg_match("/[a-z0-9]/i",$question->question,$check)?>
                       <div class="script-advance-col toolTip" title="<?=(empty($question->question)?"&nbsp;":$question->question)?>" rel="<?=(empty($question->question)?"&nbsp;":$question->question)?>" ><?=(empty($check)?"&nbsp;":((strlen($question->question) < 15)?$question->question:substr($question->question,0,15)."..."))?></div>
                       <div class="script-advance-col"><?=$question->type?></div>
                       <div class="script-advance-col"><?=$question->mandatory?></div>
                   </div>
               <?}?>
            </div>
            <div class="script-advance-add_question">
                <br/>
                 <div class="script-advance-label"><? echo lang('go_Code'); ?></div>
                 <div class="script-advance-value"><?=form_input('title',null,'size="25" '.$disable)?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Question'); ?></div>
                 <div class="script-advance-value"><?=form_textarea(array_merge(array('name'=>'question','value'=>"",'cols'=>'35', 'rows'=>'5'),$disabled))?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Help'); ?></div>
                 <div class="script-advance-value"><?=form_textarea(array_merge(array('name'=>'help','value'=>"",'cols'=>'35', 'rows'=>'5'),$disabled))?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Type'); ?></div>
                 <div class="script-advance-value"><?="<select name='type' $disable>$type</select>"?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Mandatory'); ?></div>
                 <div class="script-advance-value"><?="Yes".form_radio(array_merge(array('name'=>'mandatory','value'=>'Y'),$disabled))."&nbsp;No".form_radio(array_merge(array('name'=>'mandatory','value'=>'N','checked'=>true),$disabled))?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Validation'); ?></div>
                 <div class="script-advance-value"><?=form_input('preg',null,'size="25"').form_hidden("sid",$script[0]->sid)?></div><br/>
            </div><br/>
         </div> <!--END Left-->
         <div class="script-advance-right">
             <div class="script-advance-settings">
                 <br/>
                 <div class="script-advance-label"><? echo lang('go_Format'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('format',array('S'=>'Question by Question','G'=>'Group by Group','A'=>'All in one'),$script[0]->format,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Template'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('template',$template,$script[0]->template,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_TemplatePreview'); ?></div>
                 <div class="script-advance-value"><?="<img src='https://".$_SERVER['HTTP_HOST']."/limesurvey/templates/$preview/preview.png'>"?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Showwelcomescreen'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('showwelcome',array('Y'=>'Yes','N'=>'No'),$script[0]->showwelcome,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Navigationdelayseconds'); ?></div>
                 <div class="script-advance-value"><?=form_input('navigationdelay',$script[0]->navigationdelay,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label">Show[&lt;&lt;Prev] button</div>
                 <div class="script-advance-value"><?=form_dropdown('allowprev',array('Y'=>'Yes','N'=>'No'),$script[0]->allowprev,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Showquestionindex_allowjumping'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('allowjumps',array('Y'=>'Yes','N'=>'No'),$script[0]->allowjumps,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Keyboardlessoperation'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('nonkeyboard',array('Y'=>'Yes','N'=>'No'),$script[0]->nokeyboard,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Showprogressbar'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('showprogress',array('Y'=>'Yes','N'=>'No'),$script[0]->showprogress,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Participantsmayprintanswers'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('printanswers',array('Y'=>'Yes','N'=>'No'),$script[0]->printanswers,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Publicstatistics'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('publicstatistics',array('Y'=>'Yes','N'=>'No'),$script[0]->publicstatistics,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Showgraphsinpublicstatistics'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('publicgraphs',array('Y'=>'Yes','N'=>'No'),$script[0]->publicgraphs,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_AutomaticallyloadURLwhensurveycomplete'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('autoredirect',array('Y'=>'Yes','N'=>'No'),$script[0]->autoredirect,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_ShowThereareXquestionsinthissurvey'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('showXquestions',array('Y'=>'Yes','N'=>'No'),$script[0]->showXquestions,"$disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Showgroupnameandorgroupdescription'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('showgroupinfo',array('B'=>'Show both','N'=>'Show group name only','D'=>'Show group description only','X'=>'Hide both'),$script[0]->showgroupinfo,"style='width:150px;' $disable")?></div><br class="clear"/>
                 <div class="script-advance-label"><? echo lang('go_Showquestionnumberandorcode'); ?></div>
                 <div class="script-advance-value"><?=form_dropdown('showqnumcode',array('B'=>'Show both','N'=>'Show group name only','D'=>'Show group description only','X'=>'Hide both'),$script[0]->showqnumcode,"style='width:150px;' $disable")?></div><br/>
             </div>
             <div class="script-advance-modify_question">
                 <br/>
                 <div class="script-advance-label"><? echo lang('go_Code'); ?></div>
                 <div class="script-advance-value"><?=form_input('title',null,'size="25"'.$disable)?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Question'); ?></div>
                 <div class="script-advance-value"><?=form_textarea(array_merge(array('name'=>'question','value'=>"",'cols'=>'35', 'rows'=>'5'),$disabled))?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Help'); ?></div>
                 <div class="script-advance-value"><?=form_textarea(array_merge(array('name'=>'help','value'=>"",'cols'=>'35', 'rows'=>'5'),$disabled))?></div><br/>
                 <div class="script-advance-label"><? echo lang('go_Type'); ?></div>
                 <div class="script-advance-value"><?="<select name='type'>$type</select>"?></div><br/>
                 <div class="script-advance-label"> <? echo lang('go_Mandatory'); ?></div>
                 <div class="script-advance-value"><?="Yes".form_radio(array_merge(array('name'=>'mandatory','value'=>'Y'),$disabled))."&nbsp;No".form_radio(array_merge(array('name'=>'mandatory','value'=>'N'),$disabled))?></div><br/>
                 <div class="script-advance-label"> <? echo lang('go_Validation'); ?></div>
                 <div class="script-advance-value"><?=form_input('preg',null,'size="25"').form_hidden('qid',null)?></div><br/>
             </div>
         </div> <!--END Right -->
         <br class="clear"/>
         <br class="clear"/>
         <br class="clear"/>
         <div class="script-advance-action"><?='<input type="button" value="Save" id="save-button" '.$disable.'/>'?></div>
         <br class="clear"/>
    </div>
</div>
