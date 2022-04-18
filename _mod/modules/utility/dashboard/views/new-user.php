<table class="table table-bordered table-hover datatable-highlight dataTable no-footer">
    <thead>
        <tr>
            <th Width="5%">No.</th>
            <th>Name</th>
            <th Width="20%">Email</th>
            <th Width="20%">Date</th>
            <th Width="8%">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        foreach($new_user as $row):?>
        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['real_name'];?></td>
            <td><?=$row['email'];?></td>
            <td><small><?=time_ago($row['created_at']);?></small></td>
            <td class="text-center pointer"><a href="<?=base_url("operator/edit/".$row['id']);?>"><i class="fa fa-search"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>