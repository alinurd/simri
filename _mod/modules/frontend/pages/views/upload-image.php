<table class="table" id="tbl_product">
    <thead>
        <tr>
            <th width="8%">No</th>
            <th width="10%">Picture</th>
            <th>Title</th>
            <th>Description</th>
            <th width="6%">Default</th>
            <th width="6%">Sticky</th>
            <th width="6%">Active</th>
            <th width="5%">Action</th>
        <tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        if (is_array($rows) || is_object($rows))
        {
        foreach($rows as $key=>$row){
            $icon = '<i class="icon icon-square-up pointer text-success up" title="Naik"></i> &nbsp;
            <i class="icon icon-square-down pointer text-warning down" title="Turun"></i>';
            if ($key==0){
                $icon = '<i class="icon icon-square-up pointer text-success up" title="Naik" style="display: none;"></i> &nbsp;
                <i class="icon icon-square-down pointer text-warning down" title="Turun"></i>';
            }elseif ($key==(count($rows)-1)){
                $icon = '<i class="icon icon-square-up pointer text-success up" title="Naik"></i> &nbsp;
                <i class="icon icon-square-down pointer text-warning down" title="Turun" style="display: none;"></i>';
            }
            $img='';
            $name = form_hidden(['upload_name_tmp[]'=>$row['name'], 'type_gallery_tmp[]'=>$row['type']]);
            $title = form_input('upload_title_tmp[]', $row['title'], 'class="form-control" style="width:100%;"');
            $note = form_input('upload_note_tmp[]', $row['note'], 'class="form-control" style="width:100%;"');
            $sticky = form_dropdown('upload_sticky_tmp[]', ['No', 'Yes'], $row['sticky'], 'style="width:100%;"');
            $default = form_dropdown('upload_default_tmp[]', ['No', 'Yes'], $row['default'], 'style="width:100%;"');
            $active = form_dropdown('upload_active_tmp[]', ['No', 'Yes'], $row['active'], 'style="width:100%;"');
            if ($row['type']==0){
                $name_video = form_hidden(['text_video_tmp[]'=>$row['name']]);
                if (!empty($row['name'])){
                    $img = '<img src="'.file_url($row['name']).'" width="100">'.$name_video;
                }
            }else{
                $name_video = form_input('text_video_tmp[]', $row['name'], 'class="form-control" style="width:100%;"');
                $img = '<iframe width="220" height="115" src="'.$row['name'].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                $img .='<br/>'.$name_video;
            }
            ?>
            <tr>
                <td class="text-center"><?=$icon;?></td>
                <td><?=$name.$img;?></td>
                <td><?=$title;?></td>
                <td><?=$note;?></td>
                <td><?=$default;?></td>
                <td><?=$sticky;?></td>
                <td><?=$active;?></td>
                <td><i class="icon-database-remove text-primary pointer delete-gallery"></i></td>
            </tr>
        <?php
        }
        } ?>
    </tbody>
</table>
<span class="btn btn-info float-right pointer" id="add_product"> Add Image Product </span>
<br/>