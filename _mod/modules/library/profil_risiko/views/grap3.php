    <div class="row">
        <?php
        $r[] = ['value' => $data['110'], 'name' => 'Sebelum Due Date (110%)', 'type_chat' => $data['110%']];
        $r[] = ['value' => $data['100'], 'name' => 'On Schedule (100%)', 'type_chat' => $data['100%']];
        $r[] = ['value' => $data['90'], 'name' => 'Delay 1 month (90%)', 'type_chat' => $data['90%']];
        $r[] = ['value' => $data['75'], 'name' => 'Delay &#62; 1 month (75%)', 'type_chat' => $data['75%']];
        $r[] = ['value' => $data['0'], 'name' => 'Tidak terlaksana (0%)', 'type_chat' => $data['0%']];

        $warna = ['#23890f', '#1460d1', '#7e57c2', '#ff0000', '#009688'];

        ?>



        <?php
        $resultD = [];
        $resultD[] = ['value' => $data['110'], 'name' => 'Sebelum Due Date (110%)', 'type_chat' => 2, 'param_id' => 4];
        $resultD[] = ['value' => $data['100'], 'name' => 'On Schedule (100%)', 'type_chat' => 2, 'param_id' => 3];
        $resultD[] = ['value' => $data['90'], 'name' => 'Delay 1 month  (90%)', 'type_chat' => 2, 'param_id' => 2];
        $resultD[] = ['value' => $data['75'], 'name' => 'Delay &#62; 1 month(75%)', 'type_chat' => 2, 'param_id' => 1];
        $resultD[] = ['value' => $data['0'], 'name' => 'Tidak terlaksana(0%)', 'type_chat' => 2, 'param_id' => 0];


        $x['data'] = $resultD;
        $x['title'] = [
            'text' => 'Tingkat Pelaksanaan and Efektivitas Mitigasi Risiko',
            'subtext' => 'Last updated: ' . date('M d, Y, H:i'),
            'left' => 'center',
            'textStyle' => [
                'fontSize' => 17,
                'fontWeight' => 500
            ],
            'subtextStyle' => [
                'fontSize' => 12
            ]
        ];
        $x['warna'] = ['#23890f', '#1460d1', '#7e57c2', '#ff0000', '#009688'];
        $hasil = json_encode($x);

        ?>
        <div class="col-md-7">
            <div class="chart has-fixed-height" style="width: 500px; margin:auto" id="pie_basic_2"></div>

        </div>


        <div class="col-md-4">
            <?php foreach ($warna as $key => $value) : ?>
                <span class=" badge text-white " style=" background: <?= $value ?>;margin-bottom: 15px;font-size:20px">
                    <?= $r[$key]['name'] ?> : <?= $r[$key]['value']; ?> [ <?= $r[$key]['type_chat']; ?>% ]
                </span>
            <?php endforeach; ?>

        </div>
    </div>
    <script>
        var data = <?= $hasil; ?>;
        grafik_pie(data, 'pie_basic_2');
    </script>