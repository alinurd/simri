<div class="x_content">
	<menu id="nestable-menu" class="pull-right">
		<button type="button" data-action="expand-all">Expand All</button>
		<button type="button" data-action="collapse-all">Collapse All</button>
	</menu>
	<table class="table">
		<tr>
			<td>
			<textarea id="nestable-output" name="nestable-output" class="d-none"><?=$source_tree;?></textarea>
			<div class="dd" id="nestable">
				<ol class="dd-list">
					<?php echo $tree;?>
				</ol>
			</div>
			</td>
		</tr>
	</table>
</div>
<?php echo form_close();?>


<script>
	$(function(){
		$(".edit_modul").click(function(){
			var id = $(this).attr('data-id');
			$("#mdl_"+id).toggle();
		})
		
		$(".title_modul").keyup(function(){
			$(this).closest(".dd3-content").find(".judul").html($(this).val());
		})
		$(".icon_modul").change(function(){
			$(this).closest(".dd3-content").find("i").removeClass().addClass($(this).val());
		})
		    // output initial serialised data
		var updateOutput = function(e)
		{
			// console.log(e);
			var list   = e.length ? e : $(e.target),
				output = list.data('output');
			if (window.JSON) {
				if( typeof output != 'undefined' || output != null ){
					output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
				}
			} else {
				output.val('JSON browser support required for this demo.');
			}
		};
		$('#nestable').nestable({
			group: 1
		})
		.on('change', updateOutput);
		
		$('#nestable-menu').on('click', function(e)
		{
			var target = $(e.target),
				action = target.data('action');
			
			if (action === 'expand-all') {
				$('.dd').nestable('expandAll');
			}
			if (action === 'collapse-all') {
				$('.dd').nestable('collapseAll');
			}
		});
		updateOutput($('#nestable').data('output', $('#nestable-output')));

		$('#btn_save_modul').click(function(event) {
			var x=$(this);
			looding('light',x.parent().parent());
			$.ajax({
				type:'post',
				url:x.data('url'),
				data:{data:$('#nestable-output').val()},
				dataType: "json",
				success:function(result){
					stopLooding(x.parent().parent());
					location.reload();
				},
				error:function(msg){
					stopLooding(x.parent().parent());
				},
				complate:function(){
				}
			})
		});
	})
</script>