<?php 
if($this->session->flashdata('error'))
{ ?>
	<div class="error">
		<?php echo $this->session->flashdata('error');?>
	</div>
<?php 
}elseif($this->session->flashdata('msg'))
{ ?>
	<div class="msg">
		<?php echo $this->session->flashdata('msg');?>
	</div>
<?php 
} ?>

<div class="row">
	<div class="col-xl-12">
		<?php 
		if($keys===FALSE && !empty($lang)){ ?>
			<div class="files">
				<div><?php echo $this->lang->line('language_first_time_info');?></div>
				<?php echo form_open(site_url('/language/update_all_keys'));?>
					<div>
						<input type="hidden" name="filename" value="<?php echo $file;?>" />
						<input type="hidden" name="language" value="<?php echo $language;?>" /><br/>
						<input type="submit" name="update" value="<?php echo $this->lang->line('language_yes_label');?>" />
					</div>
				</form>
				<p class="error"><?php echo $this->lang->line('language_first_time_warning');?></p>
			</div>
		<?php 
		}
		if(isset($extra_keys) && !empty($extra_keys))
		{ ?>
			<div class="files">
				<div>
				<p><?php echo $this->lang->line('language_keys_db_warning');?></p>
					<?php echo form_open(site_url('/language/add_extra_keys'));?>
						<div>
							<input type="hidden" name="filename" value="<?php echo $file;?>" />
							<input type="hidden" name="language" value="<?php echo $language;?>" />
							<input type="submit" name="add_keys" value="<?php echo $this->lang->line('language_add_all_keys');?>" />
						</div>
					</form>
					<p class="error"><?php echo $this->lang->line('language_keys_file_warning');?></p>
					<a href="#" onclick="$('#extra_keys').toggle();"><?php echo $this->lang->line('language_show_keys');?></a>
				</div>
				<div id="extra_keys" style="display:none;">
					<ul>
						<?php foreach($extra_keys as $k){ ?>
							<li>
								<?php echo $k;?> = "<?php echo $lang[$k];?>"
								<?php echo form_open(site_url('/language/add_one_key'));?>
									<div>
										<input type="hidden" name="filename" value="<?php echo $file;?>" />
										<input type="hidden" name="language" value="<?php echo $language;?>" />
										<input type="hidden" name="key" value="<?php echo $k;?>" />
										<input type="submit" name="add_key" value="<?php echo $this->lang->line('language_add_this_key');?>" />
									</div>
								</form>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		<?php 
		} ?>
		<div class="files">
			<?php if($this->config->item('comments')==1) { ?><a href="#" onclick="$('.comments').toggle();"><?php echo $this->lang->line('language_sh_comments');?></a><br/><?php } ?>
				<?php echo form_open(site_url('/language/save_language_file'));?>
				<div id="results"></div>
				<?php if(isset($keys)&&!empty($keys)) {?>
					<table class="table">
					<?php 
					foreach($keys as $key) :?>
						<tr>
							<td><i class="icon-database-remove delete_key" id="key_<?php echo $key;?>"></i></td>
							<td>
								
									<label class="left"><?php echo $key;?> <?php if(is_array($lang)&&!array_key_exists($key,$lang)){ ?><small>(NEW!)</small><?php }?> : </label>
									<?php if(isset($pattern) && array_key_exists($key,$pattern)){
										echo '<p class="pattern clear">'.$pattern[$key].'</p>';
									}?>
							</td>
							<td>
									<input type="text" value="<?php echo (is_array($lang) && array_key_exists($key,$lang)) ? htmlspecialchars(stripslashes($lang[$key])):'';?>" name="<?php echo $key;?>" size="89" class="form-control"/>
									<?php if(isset($comments)){ ?>
										<div class="comments"<?php echo ($this->config->item('comments_show')==1) ? '' : ' style="display:none;"';?>>
											<label class="left">Comment:</label><input class="comment left" type="text" name="comment_<?php echo $key;?>" size="50"  class="form-control" value="<?php echo (is_array($comments) && array_key_exists($key,$comments)) ? $comments[$key] : ''; ?>" />
										</div>
									<?php } ?>
									<div class="clear"></div>
								
									</td>
									</tr>
					<?php endforeach; ?>
									</table>
				<?php } ?>
				<div id="extra-lang"></div>
				<div class="clear"></div>
				<br/>
				<input type="hidden" name="filename" value="<?php echo $file;?>" />
				<input type="hidden" name="language" value="<?php echo $language;?>" />
				<input type="button" name="add_new_key" id="add_new_key" value="<?php echo $this->lang->line('language_add_new_key');?>" />
				<input type="submit" name="change" value="<?php echo $this->lang->line('language_save_changes');?>sss"/>
			</form>
		</div>
	</div>
</div>

<script>
	$(function(){
		$(".delete_key").click(function(){
			$(this).closest("tr").remove();
		})

	})
</script>