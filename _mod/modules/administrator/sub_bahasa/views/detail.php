<table class="table table-hover table-bordered table-striped detail" id="instlmt">
	<thead>
		<tr>
			<th width="5%">No.</th>
			<th>Key</th>
			<th>Content</th>
			<th width="5%" align="center">Action</th>
		</tr>
	</thead>
	<tbody>
<?php
	$i=0;
	foreach($field as $row)
	{
		$id=form_hidden('id_lang[]',$row->id);
		$key=form_input('key[]',$row->lang_key,' id="key" class="form-control"');
		$content=form_input('value[]',$row->value,' id="content" class="form-control"');
		$key_tmp=form_hidden('key_tnp[]',$row->lang_key);	
	?>
		<tr>
			<td width="5%" class="text-center"><?php echo ++$i . $id;?>.</td>
			<td width="35%"><?php echo $key_tmp . $key;?></td>
			<td><?php echo $content;?></td>
			<td  width="5%" class="text-center text-primary" onClick="remove_install(this,<?php echo $row->id;?>)" style="cursor:pointer;"><i class="fa fa-cut"></i></td>
		</tr>
	<?php
	}
	
	$id=form_hidden('id_lang[]',0);
	$key=form_input('key[]','',' id="key" class="form-control"');
	$content=form_input('value[]','',' id="content" class="form-control"');
	$key_tmp=form_hidden('key_tnp[]','');	
	?>
	</tbody>
</table>
<center>
	<input id="add" class="btn btn-primary" type="button" onclick="add_install()" value="Add Key Language" name="add">
	</center>
	
<script type="text/javascript">

	function add_install(){
		var key='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$key));?>';
		var content='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$content));?>';
		var key_tmp='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$key_tmp));?>';
		var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$id));?>';
		
		var theTable= document.getElementById("instlmt");
		var rl = theTable.tBodies[0].rows.length;
		
		var lastRow = theTable.tBodies[0].rows[rl];
		var tr = document.createElement("tr");
		
		if((rl-1)%2==0)
			tr.className="dn_block";
		else
			tr.className="dn_block_alt";
		
		var td1 =document.createElement("TD");
		td1.setAttribute("style","text-align:center;width:5%;");
		var td2 =document.createElement("TD");
		td2.setAttribute("align","left");
		var td3 =document.createElement("TD");
		td3.setAttribute("align","left");
		var td4 =document.createElement("TD");
		td4.setAttribute("style","text-align:center;width:10%;");
		
		++rl;
		td1.innerHTML=rl+edit;
		td2.innerHTML=key + key_tmp;
		td3.innerHTML=content;
		td4.innerHTML='<span nilai="0" style="cursor:pointer;" onclick="remove_install(this,0)"><i class="fa fa-cut" title="menghapus data" id="sip"></i></span>';
		
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		theTable.tBodies[0].insertBefore(tr , lastRow);
	}
	
	function remove_install(t,iddel){
		if(confirm("Are you sure you want to permanently delete this transaction ?\nThis action cannot be undone")){
			var ri = t.parentNode.parentNode.rowIndex;
			$("#spinner-save-tepat").show();
			//form = $("#frm_data_dashbord").serialize();
			if(iddel>0){
				var form = {iddel:iddel}; 
				var url='<?php echo base_url($this->uri->segment(1) . "/del_bahasa");?>';
				$.ajax({
					type: "POST",
					url: url,
					data: form,
					success: function(msg){
						if (msg>0){
							t.parentNode.parentNode.parentNode.deleteRow(ri-1);
							pesan_toastr(msg + " record sukses dihapus","success");
						}else{
							pesan_toastr(" gagal menghapus record","err");
						}
					},
					failed: function(msg){
						pesan_toastr(" gagal menghapus record","err");
					},
				});
			}else{
				t.parentNode.parentNode.parentNode.deleteRow(ri-1);
				pesan_toastr("record sukses dihapus","success");
			}
		}
		return false;
	}
	
	$("#module_id, #l_bahasa_no").change(function(){
		var mdl=$("#module_id").val().split('-#-');
		var bhs=$("#bahasa_no").val();
		if (mdl[1]=="#"){
			$("#cek_spiiner").hide();
			$("#cek_oke").show();
			$("#cek_oke").removeClass().addClass('fa fa-minus-circle text-danger');
			$("#l_module_name").val('');
		}else{
			cek_modul(mdl[1], bhs);
		}
	})
	
	function cek_modul(nmmodul, bahasa){
		$("#cek_spiiner").show();
		$("#cek_oke").hide();
		var data={'modul':nmmodul,'bahasa':bahasa};
		var url="<?php echo base_url("sub-bahasa/cek_bahasa");?>";
		$.ajax({
			type:"POST",
			url:url,
			data:data,
			success:function(msg){
				$("#cek_spiiner").hide();
				$("#cek_oke").show();
				$("#cek_oke").removeClass().addClass('fa fa-check-square-o text-primary');
				$("#l_module_name").val(nmmodul);
			},
			failed:function(msg){
				$("#cek_spiiner").hide();
				$("#cek_oke").show();
				$("#cek_oke").removeClass().addClass('fa fa-minus-circle text-danger');
				$("#l_module_name").val('');
			}
		})
	}
</script>