<table class="table">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th>Category</th>
            <th>Product</th>
            <th>Date</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Note</th>
            <th>Price</th>
            <th width="19%">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no=0;
    foreach ($rows as $row):?>
    <tr>
        <td><?=++$no;?></td>
        <td><?=$row['kelompok'];?></td>
        <td><?=$row['product'];?></td>
        <td><?=$row['tgl'];?></td>
        <td><?=$row['name'];?></td>
        <td><?=$row['phone'];?></td>
        <td><?=$row['email'];?></td>
        <td><?=$row['note'];?></td>
        <td><?=$row['product_price'];?></td>
        <td class="text-center  text-primary pointer" title="proses">&nbsp;</td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>