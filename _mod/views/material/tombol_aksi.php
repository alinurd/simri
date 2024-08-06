<?php
if( ! $params['modal_box_search'] && $params['_mode_'] == 'list' ) : ?>
	<div class="card" id="box_search" style="display:none;">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Search </h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<?php echo form_open( $this->uri->uri_string, array( 'id' => 'form_input_search', 'class' => 'form-horizontal', 'role' => 'form"' ) ); ?>
			<div class="modal-body">
				<?php
				echo form_hidden( [ 'sts_query' => '1' ] );
				foreach( $field['fields'] as $row ) :
					if( $row['search'] ) :
						?>
						<div class="form-group row">
							<label class="col-form-label col-sm-3"><?= $row['title']; ?></label>
							<div class="col-sm-9">
								<?= $row['box']; ?>
							</div>
						</div>
					<?php endif; endforeach; ?>
			</div>

			<div class="modal-footer">
				<button type="submit" class="btn bg-primary">Search</button>
			</div>
			</form>
		</div>
	</div>
<?php endif;

if( $params['_mode_'] == 'add' || $params['_mode_'] == 'edit' )
{
	$hidden['l_save'] = 1;
	foreach( $params['fields'] as $row )
	{
		if( isset( $row['hidden'] ) )
			$hidden[$row['field']] = $row['hidden'];
	}
	echo form_open_multipart( $this->uri->uri_string, array( 'id' => 'form_input', 'class' => 'form-horizontal' ), $hidden );
} ?>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-xl-12">
				<?php
				if( $params['_mode_'] !== 'list' )
				{
					$rows = $params['button']['input'];
				}
				else
				{
					$rows = $params['button']['list'];
				}
				foreach( $rows as $key => $row ) :
					$tag  = ( isset( $row['tag'] ) ) ? $row['tag'] : 'button';
					$type = ( isset( $row['type'] ) ) ? $row['type'] : 'button';

					$float = '';
					if( isset( $row['align'] ) )
					{
						if( $row['align'] !== 'left' )
						{
							$float = 'float-' . $row['align'];
						}
					}
					$btn_label = ' btn-labeled btn-labeled-left ';
					$label     = ( ! empty( $row['label'] ) ) ? $row['label'] : "Export";
					if( $this->agent->is_mobile() )
					{
						$btn_label = '';
						$label     = '';
					}

					if( $key == 'print' && is_array( $row ) ) : ?>
						<div class="btn-group">
							<button type="button"
								class="btn bg-green <?= $btn_label; ?> <?= $float . ' ' . ( isset( $row['round'] ) ? $row['round'] : '' ); ?> dropdown-toggle"
								data-toggle="dropdown"><b><i class="icon-database-export"></b></i> <?= $label; ?></button>
							<div class="dropdown-menu dropdown-menu-right">
								<?php
								foreach( $row['detail'] as $prt ) :
									?>
									<a type="<?= $type; ?>" id="<?= $prt['id']; ?>"
										name="<?= ( isset( $prt['name'] ) ) ? $prt['name'] : $prt['id']; ?>"
										class="<?= ( isset( $prt['class'] ) ) ? $prt['class'] : ' '; ?> dropdown-item"
										href="<?= $prt['url']; ?>" style="margin-right:5px;" <?= ( isset( $prt['attr'] ) ) ? $prt['attr'] : ''; ?> target="_blank">
										<b><i class="<?= $prt['icon']; ?>"></i></b> &nbsp;&nbsp; <?= $prt['label']; ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php else :
						if( $tag == 'a' ) :

							?>
							<a type="<?= $type; ?>" id="<?= $row['id']; ?>"
								name="<?= ( isset( $row['name'] ) ) ? $row['name'] : $row['id']; ?>"
								class="btn <?= $row['color']; ?>  <?= $btn_label; ?> <?= $float . ' ' . $row['round']; ?> <?= ( isset( $row['class'] ) ) ? $row['class'] : ' button-action'; ?>"
								href="<?= $row['url']; ?>" style="margin-right:5px;" <?= ( isset( $row['attr'] ) ) ? $row['attr'] : ''; ?>>
								<b><i class="<?= $row['icon']; ?>"></i></b> <?= $label; ?>
							</a>
						<?php else : ?>
							<button type="<?= $type; ?>" id="<?= $row['id']; ?>"
								name="<?= ( isset( $row['name'] ) ) ? $row['name'] : $row['id']; ?>"
								class="btn <?= $row['color']; ?>  <?= $btn_label; ?> <?= $float . ' ' . $row['round']; ?> <?= ( isset( $row['class'] ) ) ? $row['class'] : ' button-action'; ?>"
								data-url="<?= $row['url']; ?>" style="margin-right:5px;" <?= ( isset( $row['attr'] ) ) ? $row['attr'] : ''; ?> value="<?= ( isset( $row['value'] ) ) ? $row['value'] : 'x'; ?>">
								<b><i class="<?= $row['icon']; ?>"></i></b> <?= $label; ?>
							</button>
						<?php endif;
					endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>