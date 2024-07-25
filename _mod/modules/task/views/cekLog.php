<div class='table-responsive'>
<h3>Log peringatan</h3>
<table class="table table-hover" id="tbl_list_mitigasi">
    <thead>
        <tr>
            <th>No</th>
            <th>ref id</th>
            <th>subject</th>
             <th>message</th>
            <th>created_at</th>
            <th>to</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        foreach ($log as $q): 
      
        ?>
            <tr>
            <td><?= $no++?></td>
            <td><?=$q['ref_id'];?></td>
            <td><?=$q['subject'];?></td>
            <td><?=$q['message'];?></td>
             <td>created_at</td>
            <td>to</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>