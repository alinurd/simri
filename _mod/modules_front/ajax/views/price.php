<br/><br/>
<div class="table-responsive">
<strong>List Price - <?=$info['product'];?></strong><br/>
<table class="table table-bordered table-striped" cellspacing="0">
    <thead>
        <tr>
            <th>No.</th>
            <?php
            foreach ($info['m_input'] as $row):
                $romx = str_replace('-','_',$row); 
                ?>
            <th><?=lang('fld_dtl_'.$romx);?></th>
            <?php endforeach;?>
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
            ?>
            <td><?=$row[$romx];?></td>
            <?php endforeach;?>
            <td class="text-right"><?=number_format($row['mall_price']);?></td>
            <td class="text-center pointer text-primary order text-center" data-id="<?=$row['id'];?>"><i class="fa fa-cart-plus"></i></td>
    <?php endforeach;?>
    </tbody>
</table>