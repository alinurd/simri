<table class="table table-hover table-striped " id='datatable-staft'>
    <thead>
        <tr>
            <th width='5%' class='text-center'>No.</th>
            <th width='10%' class='text-center'>NIP</th>
            <th class='text-center'>Staft Name</th>
            <th width='15%' class='text-center'>Title</th>
            <th class="text-center" width='8%'>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no=0;
            foreach($rows as $row):?>
                <tr>
                    <td><?=++$no;?></td>
                    <td><?=$row->nip;?></td>
                    <td><?=$row->name;?></td>
                    <td><?=$row->title;?></td>
                    <td class="text-center"><span class="pilih-staft pointer" data-kel="<?=$kel;?>" data-nama="<?=$row->name;?>" data-title="<?=$row->title;?>" data-id="<?=$row->id;?>">Pilih</span></td>
                </tr>
        <?php endforeach;?>
    </tbody>
</table>

<script>
    var DatatableAdvanced = function() {
        var _componentDatatableAdvanced = function() {
            if (!$().DataTable) {
                console.warn('Warning - datatables.min.js is not loaded.');
                return;
            }

            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 100,
                    targets: [ 5 ]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });

            // DOM positioning
            $('.datatable-dom-position').DataTable({
                dom: '<"datatable-header length-left"lp><"datatable-scroll"t><"datatable-footer info-right"fi>',
            });

            // Highlighting rows and columns on mouseover
            var lastIdx = null;
            var table = $('#datatable-staft').DataTable({
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                columnDefs: [ 
                    {
                        targets: [0,1],
                        orderable: false
                    }
                ],
                order: [[ 2, "asc" ]],
                processing: false,
                bServerSide: false,
            });

            $('#datatable-staft tbody').on('mouseover', 'td', function() {
                var colIdx = table.cell(this).index().column;

                if (colIdx !== lastIdx) {
                    $(table.cells().nodes()).removeClass('active');
                    $(table.column(colIdx).nodes()).addClass('active');
                }
            }).on('mouseleave', function() {
                $(table.cells().nodes()).removeClass('active');
            });
        };

        // Select2 for length menu styling
        var _componentSelect2 = function() {
            if (!$().select2) {
                console.warn('Warning - select2.min.js is not loaded.');
                return;
            }

            // Initialize
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                dropdownAutoWidth: true,
                width: 'auto'
            });
        };

        return {
            init: function() {
                _componentDatatableAdvanced();
                _componentSelect2();
            }
        }
    }();


    // Initialize module
    // ------------------------------

    $(document).ready(function() {
        DatatableAdvanced.init();
    });
</script>