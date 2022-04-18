
<?php 	echo $content;?>
<script>
$(function(){
	$('.toggleCheckBoxes').click(function(){

		var nil=false;
		var td=$(this);
		var sts=td.data('sts');
		if (sts==0){
			td.attr('data-sts',1);
			nil = true;
		}else{
			td.attr('data-sts',0);
		}
		$(td).removeData();
		
		var tr = td.parents('tr');
		tr.find('td').find("input [type=checkbox]").attr('checked', nil);
	})
})
</script>