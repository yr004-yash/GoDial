<?php
############################################################################################
####  Name:             go_campaign_view.php                                            ####
####  Type: 		    ci views                                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script>
$(function()
{
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delPhone[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delPhone[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	var toggleAction = $('#go_action_menu').css('display');
	$('#selectAction').click(function()
	{
		if (toggleAction == 'none')
		{
			var position = $(this).offset();
			$('#go_action_menu').css('left',position.left-68);
			$('#go_action_menu').css('top',position.top+16);
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

	$('li.go_action_submenu').click(function () {
		var selectedPhones = [];
		$('input:checkbox[id="delPhone[]"]:checked').each(function()
		{
			selectedPhones.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedPhones.length<1)
		{
			alert('<? echo $this->lang->line("go_pls_sel_ext"); ?>');
		}
		else
		{
			var s = '';
			if (selectedPhones.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('<? echo $this->lang->line("go_del_con_sel_ext"); ?>'+s+'?');
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_phones_ce/go_update_phone_list/'+action+'/'+selectedPhones+'/');
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_phones_ce/go_update_phone_list/'+action+'/'+selectedPhones+'/');
			}
		}
	});

	if (<?php echo count($phones); ?> > 0 || $('#search_list').val().length > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({headers: { 2: { sorter: "ipAddress" }, 6: { sorter: false}, 8: { sorter: false}, 9: {sorter: false} }});
	}
	else
	{
		addNewPhones();
	}
});
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
                        <th>&nbsp;<? echo strtoupper($this->lang->line("go_exten_only")); ?></th>
                        <th>&nbsp;<? echo strtoupper($this->lang->line("go_protocol")); ?></th>
                        <?php
                        if ($this->session->userdata('user_group') == "ADMIN") {
                        ?>
                        <th>&nbsp;<? echo strtoupper($this->lang->line("go_server")); ?></th>
                        <th>&nbsp;<? echo strtoupper($this->lang->line("go_dial_plan")); ?></th>
                        <?php
                        }
                        ?>
                        <th>&nbsp;<? echo strtoupper($this->lang->line("go_status")); ?></th>
                        <th>&nbsp;<? echo strtoupper($this->lang->line("go_name")); ?></th>
                        <th colspan="2">&nbsp;<? echo $this->lang->line("go_vmail"); ?></th>
                        <th style="<?=$hideThis?>">&nbsp;<? echo strtoupper($this->lang->line("go_group")); ?></th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;<? echo strtoupper($this->lang->line("go_action")); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
if (count($phones) > 0) {
	$x = 0;
	foreach ($phones as $list)
	{		
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
                $status = ($list->active=="Y") ? "{$this->lang->line('go_active')}" : "{$this->lang->line('go_inactive')}";
                $acolor = ($status=="{$this->lang->line('go_active')}") ? "green" : "#F00";
                $ugroup = ($list->user_group=="---{$this->lang->line('go_all')}---") ? "{$this->lang->line('go_all_user_groups')}" : $list->user_group;
		
		echo "<tr style='background-color:$bgcolor;'>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->extension}')\">{$list->extension}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->protocol}</td>";
		if ($this->session->userdata('user_group') == "{$this->lang->line('go_admin')}") {
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->server_ip}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->dialplan_number}</td>";
		}
		echo "<td style='border-top:#D0D0D0 dashed 1px;display:none;'>&nbsp;{$list->voicemail_id}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;color:$acolor;font-weight:bold;width:10%;'>&nbsp;$status</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->fullname}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->messages}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->old_messages}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;width:6%;'>&nbsp;$ugroup&nbsp;&nbsp;&nbsp;</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->extension}')\" style='cursor:pointer;' class='toolTip' title='{$this->lang->line("go_modify_phone")}<br />{$list->extension}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span onclick=\"delPhone('{$list->extension}')\" style='cursor:pointer;' class='toolTip' title='{$this->lang->line("go_del_phone")}<br />{$list->extension}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delPhone[]' value='{$list->extension}' /></td>\n";
		echo "</tr>";
	}
} else {
	echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"13\">{$this->lang->line('go_no_records_found')}</td></tr>\n";
}
?>
	</tbody>
</table>
<div style="padding-top:5px;text-align: right;"><span style="float: left"><?=$pagelinks['links'] ?></span><?=$pagelinks['info'] ?></div>
