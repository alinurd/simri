$(function(){
    $(document).on('click','#add_kriteria', function(){
        var row = $("#tbl_term > tbody");
        row.append('<tr><td>'+edit+'</td><td>'+kode+'</td><td>'+kriteria+'</td><td class="text-center"><span class="text-primary" nilai="0" style="cursor:pointer;" onclick="remove_install(this,0)"><i class="fa fa-cut" title="menghapus data"></i></span></td></tr>');
    });
});