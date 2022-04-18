<?php
$dont=['laminated_price','spanram_price','price_sheet'];
?>
<br/><br/>
<div class="table-responsive">
<strong>Price List - <?=$info['product'];?></strong><br/>
<table class="table table-bordered table-striped" cellspacing="0">
    <thead>
        <tr>
            <th>No.</th>
            <?php
            foreach ($info['m_input'] as $row):
                $romx = str_replace('-','_',$row); 
                $label = $this->lang->line('fld_dtl_'.$romx.'_'.$info['id']);
                if (empty($label)){
                    $label = lang('fld_dtl_'.$romx);
                }
                if (!in_array($romx,$dont)):
                ?>
            <th><?=$label;?></th>
                <?php endif;endforeach;?>
            <th><?=lang('fld_dtl_price');?></th>
            <th><?=lang('fld_dtl_action');?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no=0;
        foreach($field as $row):
        $edit_id = form_hidden('edit_id[]', $row['id']);
        ?>
        <tr>
            <td><?=++$no.$edit_id;?>
            <?php
            foreach ($info['m_input'] as $rom):
                $romx = str_replace('-','_',$rom); 
                $cbo='cbo_'.$romx.'_id';
                $img='image_'.$romx;
                
                if (!in_array($romx,$dont)):
                    if (!empty($row[$img])){
                        $img=file_url($row[$img]);
                        $class='pointer text-primary';
                        $title='title=" Klik untuk melihat image "';
                        $view='<a class="fancybox"  data-fancybox-group="gallery-combo"  href="'.$img.'">'.$row[$romx].'</a>';
                    }else{
                        $img='';
                        $view=$row[$romx];
                        $class='';
                        $title='';
                    }
            ?>
            <td class="info-ima <?=$class;?>" <?=$title;?> data-image="<?=$img;?>" data-nama="<?=$row[$romx];?>"><?=$view;?></td>
                <?php endif;endforeach;?>
            <td class="text-right"><?=number_format($row['mall_price']);?></td>
            <td class="text-center pointer text-primary order text-center" data-id="<?=$row['id'];?>"><a href="<?=base_url('product/'.$info['uri_title'].'/order/'.$row['id']);?>"><i class="fa fa-cart-plus"></i></a></td>
    <?php endforeach;?>
    </tbody>
</table>