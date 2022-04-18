<div class="language">
    <?php if(isset($dir)&&!empty($dir)){ ?>
        
        <table class="table table-bordered">
            <thead>
                <th width="4%">No.</th>
                <th>Language</th>
                <th width="8%">Files</th>
                <th width="8%">Action</th>
            </thead>
            <tbody>
            <?php 
            $no=0;
            foreach($dir as $d): ?>
                <tr>
                    <td><?=++$no;?></td>
                    <td><?=$d['dir'];?></td>
                    <td><?=$d['count'];?></td>
                    <td>
                        <?php echo form_open(site_url('/language/delete_language'));?>
                        <a href="<?php echo site_url('/language/lang_list/'.$d['dir']);?>"><i class="icon-clipboard3"></i></a> 
                        <input type="hidden" name="language" value="<?php echo $d['dir'];?>" />
                        <i type="submit" class="icon-database-remove button_del"></i>
                    </form>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
	<?php } ?>
	<p><a href="#" id="new_lang"><?php echo $this->lang->line('language_create_lang');?></a></p>
	<div id="new_lang_form">
		<?php echo form_open(site_url('/language/create_new_language'));?>
			<div>
				<label><?php echo $this->lang->line('language_new_lang_info');?></label>
				<input type="text" name="language" />
				<input type="submit" name="create" value="<?php echo $this->lang->line('language_create_label');?>" />
			</div>
		</form>
	</div>
</div>
		<script>

$(document).ready(function(){
    $("#add_new_key").click(function() { ///add click action for button
        var n = $('.ex_key').length+1; ///get lenght of new keys and values
        var m = $('.ex_val').length+1;
        //<![CDATA[
        $('<div class="row"><label><?php echo $this->lang->line('language_key');?> : </label><input class="ex_key" type="text" name="new_key_'+n+'" size="30" /><br/><label><?php echo $this->lang->line('language_translation');?>:</label><input class="ex_val" type="text" name="new_value_'+m+'" size="89" /><br/><div class="clear"></div></div>').appendTo('#extra-lang'); ///add input for new key
        //]]>
    });
    $('#create_file_form').hide();
    $("#new_file").click(function() { ///add click action for button
        $('#create_file_form').toggle();
    });
    $('#new_lang_form').hide();
    $("#new_lang").click(function() { ///add click action for button
        $('#new_lang_form').toggle();
    });
    $('.button_del').click(function(){
        var answer = confirm('<?php echo $this->lang->line('language_confirm_lang_delete');?>');
        return answer; // answer is a boolean
    });
});

</script>
