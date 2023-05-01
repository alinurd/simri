<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Alur Approval</h6>
                <div class="header-elements">
                    <span class="label"><?=(!empty($mode))?'<span class="badge bg-blue-400"> '.$mode_text.' </span>':'';?></span>
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="height:300px;">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>Level</th>
                            <th>Owner</th>
                            <th>TMRD</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($alur as $key=>$row):
                            if ($key>0):?>
                            <?php
                                $level = $row['level'];
                                if ($row['level'] == 'Manager') {
                                    $level = 'VP';
                                }elseif($row['level'] == 'Kepala Departemen'){
                                    $level = 'SVP';
                                }
                            ?>
                            <tr>
                                <td><?=$level;?></td>
                                <td><?=$row['owner'];?></td>
                                <td><?=$row['staft'];?></td></tr>
                            <?php endif;endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Log/Histori Approval</h6>
                <div class="header-elements">
                    <span class="label"><?=(!empty($mode))?'<span class="badge bg-blue-400"> '.$mode_text.' </span>':'';?></span>
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="height:300px;overflow-y:scroll;">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Judul</th>
                            <th>Catatan</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($histori as $key=>$row):?>
                            <tr><td><?=$row['tanggal'];?></td><td><?=$row['pengirim'];?></td><td><?=$row['keterangan'];?></td><td><?=$row['note'];?></td></tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>