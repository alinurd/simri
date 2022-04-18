<table class="table table-striped table-hover" width="100%" id="tbl_owner">
	<tr>
		<td width="10%">
			<label><input type="hidden" name="sts_disc"><input type="checkbox" name="sts_disc" id="sts_disc"> Can input Discount</label>
		</td>
	</tr>
	<tr>
		<td width="10%" >
			<label><input type="hidden" name="sts_price"><input type="checkbox" name="sts_price" id="sts_price"> Can Change Price</label>
		</td>
	</tr>
</table>

<script>
	$("#owner_all").click(function(){
		if($(this).is(":checked"))
			$("#tbl_owner").addClass('hide');
		else
			$("#tbl_owner").removeClass('hide');
	})
</script>	