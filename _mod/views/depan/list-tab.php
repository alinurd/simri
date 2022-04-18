<ul class="nav nav-tabs nav-justified nav-tabs-bottom">
    <?php
    $first=true;
    foreach($data as $key=>$row):
        $active='';
        if ($first){
            $active = 'active show';
        }
        $first=false;
    ?>
    <li class="nav-item"><a href="#tab_<?=$key;?>" class="nav-link <?=$active;?>" data-toggle="tab"><?=$row['title'];?></a></li>
    <?php endforeach;?>
</ul>

<div class="tab-content">
    <?php
        $first=true;
        foreach($data as $key=>$row):
            $active='';
            if ($first){
                $active = 'active show';
            }
            $first=false;
        ?>
        <div class="tab-pane fade  <?=$active;?>" id="tab_<?=$key;?>">
            <?=$row['content'];?>
        </div>
        <?php endforeach;?>
</div>