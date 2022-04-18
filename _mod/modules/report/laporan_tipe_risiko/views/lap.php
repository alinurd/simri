<?php
if (!$mode):?>
<a class="btn btn-primary" href="<?=base_url(_MODULE_NAME_.'/cetak');?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>
<br/>&nbsp;
<?php endif;?>
<style>
  .double-scroll {
        width: 100%;
    }
</style>
<div class="double-scroll">
    <table class="table table-bordered" border="1" >
        <thead>
            <tr class="text-center bg-primary-300">
                <th width="20%" rowspan="2">Aspek</th>
                <th width="20%" rowspan="2">Tipe Risiko</th>
                <th colspan="<?=count($dept);?>">Operational</th>
                <th rowspan="2">Total per Tipe</th>
            </tr>
            <tr class="text-center bg-primary-300">
                <?php
                foreach($dept as $row):?>
                <td><?=$row['owner_code'];;?></td>
                <?php endforeach;?>
            </tr>
        </thead>
        <tbody>
        <?php
        $no=0;
        // dumps($nilai);
        foreach ($risk as $key=>$row):?>
        <tr>
            <td rowspan="<?=count($row['child']);?>" STYLE="writing-mode: vertical-lr;-ms-writing-mode: tb-rl;"><?=$row['name'];?></td>
            <?php
            $noc=0;
            foreach ($row['child'] as $keyc=>$rowc):
                $totalH=0;
                if ($noc==0):
                    ++$noc;?>
                    <td><?=$rowc['data'];?>
                    <?php if (!$mode):?>
                        <br>
                        <a class="btn btn-primary btn-sm" href="<?=base_url(_MODULE_NAME_.'/cetak_register/'.$rowc['id'].'/'.$post['period'].'/'.$post['term']);?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>

                    <?php endif;?>
                    </td>
                    <?php
                    foreach($dept as $row):
                        $jml='';
                        $color='';
                        $color_text='#000000';
                        $nil=$rowc['id'].'-'.$row['id'];
                        if(array_key_exists($nil, $nilai)){
                            $jml=$nilai[$nil]['nil'];
                            $color=(isset($nilai[$nil]['warna']['color']))?$nilai[$nil]['warna']['color']:'';
                            $color_text=(isset($nilai[$nil]['warna']['color_text']))?$nilai[$nil]['warna']['color_text']:'#000000';
                        }
                        $totalH+=intval($jml);
                        $totalV[$row['id']]+=intval($jml);
                        if(intval($jml)>0){
                            ++$totalV2[$row['id']];
                        }
                        ?>
                        <td data-rowc="<?=$rowc['id']?>" data-row="<?=$row['id']?>" class="text-center <?=($color!="")?"pointer detail-peta":""?>" style="background-color:<?=$color;?>;color:<?=$color_text;?>;"><?=$jml;?></td>
                    <?php 
                    endforeach;?>
                    <td class="text-center bg-primary-300"><strong><?=(empty($totalH))?'':$totalH;?></strong></td>
                    </tr>
                <?php 
                else:?>
                    <tr><td><?=$rowc['data'];?>
                    <?php if (!$mode):?>
                        <br>
                        <a class="btn btn-primary btn-sm" href="<?=base_url(_MODULE_NAME_.'/cetak_register/'.$rowc['id'].'/'.$post['period'].'/'.$post['term']);?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>

                    <?php endif;?>
                    </td>
                    <?php
                    foreach($dept as $row):
                        $jml='';
                        $color='';
                        $color_text='#000000';
                        $nil=$rowc['id'].'-'.$row['id'];
                        if(array_key_exists($nil, $nilai)){
                            $jml=$nilai[$nil]['nil'];
                            $color=$nilai[$nil]['warna']['color'];
                            $color_text=$nilai[$nil]['warna']['color_text'];
                        }
                        $totalH+=intval($jml);
                        $totalV[$row['id']]+=intval($jml);
                        if(intval($jml)>0){
                            ++$totalV2[$row['id']];
                        }
                        ?>
                        <td data-rowc="<?=$rowc['id']?>" data-row="<?=$row['id']?>" class="text-center <?=($color!="")?"pointer detail-peta":""?>" style="background-color:<?=$color;?>;color:<?=$color_text;?>;"><?=$jml;?></td>
                    <?php 
                    endforeach;?>
                    <td class="text-center bg-primary-300"><strong><?=(empty($totalH))?'':$totalH;?></strong></td>
                    </tr>
                    <?php 
                endif;
            endforeach;
        endforeach;?>
        </tbody>
        <tfoot>
            <tr class="bg-primary-300">
                <th colspan="2">Total Per Departemen</th>
                <?php
                $g=0;
                foreach($dept as $row):
                    $g+=intval($totalV[$row['id']]);
                    ?>
                    <th class="text-center"><strong><?=(empty($totalV[$row['id']]))?'':$totalV[$row['id']];?></strong></th>
                <?php 
                endforeach;?>
                <th class="text-center"><strong><?=$g;?></strong></th>
            </tr>
            <tr class="bg-primary-300">
                <th colspan="2">Frekuensi Tipe Risiko Per Departemen</th>
                <?php
                $g=0;
                foreach($dept as $row):
                    $g+=intval($totalV2[$row['id']]);
                    ?>
                    <th class="text-center"><strong><?=(empty($totalV2[$row['id']]))?'':$totalV2[$row['id']];?></strong></th>
                <?php 
                endforeach;?>
                <th class="text-center"><strong><?=$g;?></strong></th>
            </tr>
        </tfoot>
    </table>
</div>