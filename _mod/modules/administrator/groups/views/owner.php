<script>
	function _toggleCheckBoxes(td){
		if(td){
			if(td.checked){
				td.checked = false;
			}else{
				td.checked = true;
			}
		
			var tr = td.parentNode;
			if(tr && tr.cells){
				var l = tr.cells.length - 1;
				for(var i = 1 ; i <= l ; i++){
					var tmpTd = tr.cells[i];
					var checkbox = tmpTd.childNodes[1];
					if(checkbox){
						checkbox.checked = td.checked;
					}
				}
			}
		}
	}
</script>
<?php
	$read_checked1 = '';
	$hide="";
	if (count($groups)>0){
		if (is_array($groups[0]['param'])){
			if (array_key_exists('all',$groups[0]['param'])){
				if ($groups[0]['param']['all']==1){
					$read_checked1 = 'checked';
					$hide="hide";
				}
			}
		}
	}
?>
	<div class="form-group">
		<label>
		<input type="hidden" name="owner_all" value="0">
		<input id="owner_all" class="pointer" type="checkbox" name="owner_all" value="1" <?php echo $read_checked1;?>> &nbsp;&nbsp;<?php echo lang('msg_field_all_unit') . get_help('msg_help_all_unit');?>
		</label>
	</div>
	<table class="table table-striped table-hover <?php echo $hide;?>" width="100%" id="tbl_owner">
		<thead>
			<tr>
				<th width="10%" ><?php echo lang('msg_field_check');?></th>
				<th><?php echo lang('msg_field_risk_unit');?></th>
			</tr>
		</thead>
	<tbody>
<?php
	$i=0;
	foreach($owner as $row)
	{	
		
		if($hide=='hide'){
			$read_checked = 'checked';
		}else{
			$read_checked = '';
			if (count($groups)>0){
				if (is_array($groups[0]['param'])){
					foreach($groups[0]['param']['owner'] as $key=>$gr)
					{
						if ($gr==$row['id']){
							$read_checked = 'checked';
							break;
						}
					}
				}
			}
		}
		?>
		<tr>
			<td  style="cursor:pointer;text-align:left;">
				<strong>
					<input class="pointer" type="checkbox" name="owner_no[]" value="<?php echo $row['id'];?>" <?php echo $read_checked;?>>
				</strong>
			</td>
			<td>
				<?php echo $row['name'];?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody></table>

<script>
	$("#owner_all").click(function(){
		if($(this).is(":checked"))
			$("#tbl_owner").addClass('hide');
		else
			$("#tbl_owner").removeClass('hide');
	})
</script>	