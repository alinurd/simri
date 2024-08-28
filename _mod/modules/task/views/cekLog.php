<div class='table-responsive'>
<h3>Log peringatan</h3>
<table class="table table-hover" id="tbl_list_mitigasi">
    <thead>
    <tr class="text-center">
    <th>No</th>
            <!-- <th>ref id</th> -->
            <th>Recipient</th>
            <th>subject</th>
             <th>message</th>
            <th> Sent At </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        foreach ($log as $q): 
      
        ?>
            <tr class="text-center">
            <td><?= $no++?></td>
            <td><?=$q['to'];?></td> 
            <!-- <td><?=$q['ref_id'];?></td> -->
            <td><?=$q['subject'];?></td>
            <td><?=$q['ket'];?></td>
            <td><?=$q['created_at'];?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>