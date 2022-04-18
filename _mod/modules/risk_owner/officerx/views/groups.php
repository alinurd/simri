	<table class="table" id="instlmt"><thead><tr>
	<th width="10%" style="text-align:center;">No.</th>
	<th>Group</th>
	<th width="10%" style="text-align:center;">Aksi</th>
	</tr></thead><tbody>
		
<?php
	$i=0;
	if($field) {
		foreach($field as $key=>$row)
		{ 
			$edit=form_hidden('id_edit[]',$row['id']);
			$cbo = form_dropdown('groups_id[]', $cbogroup, $row['group_no'],'class="form-control"');
			++$i;
			?>
			<tr>
				<td style="text-align:center;width:10%;"><?php echo $i.$edit;?></td>
				<td><?php echo $cbo;?></td>
				<td style="text-align:center;width:10%;"><a nilai="<?php echo $row['id'];?>" style="cursor:pointer;" onclick="remove_install(this,<?php echo $row['id'];?>)"><i class="fa fa-cut" title="menghapus data"></i></a></td>
			</tr>
		<?php 
		}
	}else{
		$cbo = form_dropdown('groups_id[]', $cbogroup,'','class="form-control"');
		$edit=form_hidden('id_edit[]','0');
		?>
			<tr>
				<td style="text-align:center;width:10%;">1. <?php echo $edit;?></td>
				<td><?php echo $cbo;?></td>
				<td style="text-align:center;width:10%;"><a nilai="0" style="cursor:pointer;" onclick="remove_install(this,0)"><i class="fa fa-cut" title="menghapus data"></i></a></td>
			</tr>
		<?php 
	}
	// $cbo = form_dropdown('groups_id[]', $cbogroup,'','class="form-control"');
	// $edit=form_hidden('id_edit[]','0');
	?>
	</tbody></table><center>
	<input id="add" class="btn btn-primary hide" type="button" onclick="add_install()" value="Add User Group" name="add">
	</center>
	
<script type="text/javascript">

	function add_install(){
		var cbo='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$cbo));?>';
		var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
		
		var theTable= document.getElementById("instlmt");
		var rl = theTable.tBodies[0].rows.length;
		
		if (theTable.rows[rl].cells[1].childNodes[0].value=="0"){
			alert("Groups Tidak boleh Kosong!");
		}else{
		
			var lastRow = theTable.tBodies[0].rows[rl];
			var tr = document.createElement("tr");
			
			if((rl-1)%2==0)
				tr.className="dn_block";
			else
				tr.className="dn_block_alt";
			
			var td1 =document.createElement("TD");
			td1.setAttribute("style","text-align:center;width:10%;");
			var td2 =document.createElement("TD");
			td2.setAttribute("align","left");
			var td3 =document.createElement("TD");
			td3.setAttribute("style","text-align:center;width:10%;");
			
			++rl;
			td1.innerHTML=rl+edit;
			td2.innerHTML=cbo;
			td3.innerHTML='<span nilai="0" style="cursor:pointer;" onclick="remove_install(this,0)"><i class="fa fa-cut" title="menghapus data" id="sip"></i></span>';
			
			tr.appendChild(td1);
			tr.appendChild(td2);
			tr.appendChild(td3);
			theTable.tBodies[0].insertBefore(tr , lastRow);
		}
	}
	
	function remove_install(t,iddel){
		if(confirm("Are you sure you want to permanently delete this transaction ?\nThis action cannot be undone")){
			var ri = t.parentNode.parentNode.rowIndex;
			$("#spinner-save-tepat").show();
			//form = $("#frm_data_dashbord").serialize();
			var form = {iddel:iddel}; 
			var url='<?php echo base_url("operator/del_groups");?>';
			$.ajax({
				type: "POST",
				url: url,
				data: form,
				success: function(msg){
					t.parentNode.parentNode.parentNode.deleteRow(ri-1);
					alert(msg + " record sukses dihapus");
				},
				failed: function(msg){
					alert("gagal");
				},
			});
		}
		return false;
	}
</script>