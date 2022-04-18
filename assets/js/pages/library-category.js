$(function(){
    $(document).on('click','.icon-square-up, .icon-square-down', function(){
		var row = $(this).parents("tr:first");
		$(".icon-square-up,.icon-square-down").show();
		if ($(this).is(".icon-square-up")) {
			row.insertBefore(row.prev());
		} else {
			row.insertAfter(row.next());
		}
		// $("tbody tr:last .down").hide();
		$(this).closest('table').find('tbody tr:last').find('.icon-square-down').hide();
		$(this).closest('table').find('tbody tr:first').find('.icon-square-up').hide();
    });

    $(document).on('click','#add_risk', function(){
        var row = $("#risk_type > tbody");
        row.append('<tr><td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td><td>'+edit+'</td><td>'+risk_type+'</td><td class="text-center"><i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data harga "></i> | <span class="text-primary" nilai="0" style="cursor:pointer;" onclick="remove_install(this,0)"><i class="fa fa-cut" title="menghapus data"></i></span></td></tr>');
    });
});