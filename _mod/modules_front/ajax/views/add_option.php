<?php
echo $hide;
if (isset($hide2))
	echo $hide2;
	
foreach ($content as $row){
	?>		
	<div class="form-group clearfix">
		<label class="col-sm-3 control-label">
			<?=$row['label'];?>		
		</label>
		<div class="col-sm-9">
			<div class="input-group"  style="width:100%;">
			<?=$row['isi']; ?>
			</div>
		</div>
	</div>
<?php } ?>

