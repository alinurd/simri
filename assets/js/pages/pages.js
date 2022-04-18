var noImg=0;
var noCoverImg=0;
$(function(){
    $(document).on('click','.up, .down', function(){
		var row = $(this).parents("tr:first");
		$(".up,.down").show();
		if ($(this).is(".up")) {
			row.insertBefore(row.prev());
		} else {
			row.insertAfter(row.next());
		}
		// $("tbody tr:last .down").hide();
		$(this).closest('table').find('tbody tr:last').find('.down').hide();
		$(this).closest('table').find('tbody tr:first').find('.up').hide();
    });

    $(document).on('click','#add_product', function(){
        ++noImg;
        var img='<div class="pil_picture"><img id="img_photo_'+noImg+'" style="margin-top:10px;"  width="100" src="" alt="image"/><br/><div class="upload-btn-wrapper"> <button class="btn">Upload a file</button><input type="file" name="upload_image[]" class="pointer" onchange="showMyImage(this,\'img_photo_'+noImg+'\')"></div></div>';
        var pil_image ='<select name="type_gallery[]" class="video"><option value="0" selected="selected">Image</option><option value="1" >Video</option></select>';
        var text_video ='<input type="text" name="text_video[]" value="" class="form-control text_video d-none">';
        var title ='<input type="text" name="upload_title[]" value="" class="form-control">';
        var note ='<input type="text" name="upload_note[]" value="" class="form-control">';
        var pil ='<select name="upload_default[]"><option value="0">No</option><option value="1" selected="selected">Yes</option></select>';
        var sticky ='<select name="upload_sticky[]"><option value="0">No</option><option value="1" selected="selected">Yes</option></select>';
        var sts ='<select name="upload_active[]"><option value="0">No</option><option value="1" selected="selected">Yes</option></select>';

		var row = $("#tbl_product > tbody");
        row.append('<tr><td class="text-center"></td><td>'+img+text_video+'<br/>'+pil_image+'</td><td>'+title+'</td><td>'+note+'</td><td>'+pil+'</td><td>'+sticky+'</td><td>'+sts+'</td><td class="text-center"><span class="text-primary" nilai="0" ><i class="icon-database-remove text-primary pointer delete-gallery" title="menghapus data" id="sip"></i></span></td></tr>');
    });

    $(document).on('change','.video', function(){
        console.log($(this).val());
        if ($(this).val()==1){
            $(this).closest('td').find('.text_video').removeClass('d-none');
            $(this).closest('td').find('.pil_picture').addClass('d-none');
        }else{
            $(this).closest('td').find('.text_video').addClass('d-none');
            $(this).closest('td').find('.pil_picture').removeClass('d-none');
        }
    });

    $(document).on('click','.delete-gallery', function(){
        if(confirm("Are you sure you want to permanently delete this transaction ?\nThis action cannot be undone")){
            $(this).closest('td').closest('tr').remove();
        }
    });

});