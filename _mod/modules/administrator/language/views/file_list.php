
<table class="table table-hover table-striped dataTable no-footer">
	<thead>
		<tr>
			<th>File</th>
			<th>Delete</th>
	</tr>
	</thead>
	<tbody>
		<?php 
		foreach($files as $f):
			$namax =str_replace('_lang.php','',$f);
			$nama = str_replace('_',' ',ucwords(strtolower($namax)));
			?>
			<tr>
				<td><?php echo $nama;?></a></td>
				<td width="15%" class="text-center">
					<a href ="<?=base_url('/language/lang_file/'.$sel_dir.'/'.$namax);?>" title="Edit language"><i class="icon-database-edit2 "></i></a> &nbsp;&nbsp;&nbsp;
					<a href ="<?=base_url('/language/delete_language_file/'.$sel_dir.'/'.$f);?>" title="delete language"><i class="icon-database-remove text-danger "></i></a>
			</td>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
$(document).ready(function(){
	$('.button_del').click(function(){
		var answer = confirm('<?php echo $this->lang->line('language_confirm_lang_delete');?>');
		return answer; // answer is a boolean
	});
	});
</script>
