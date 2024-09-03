<?php
if( $params['show_list_header'] ) : ?>
	<?php if( $params['box_list_header'] ) : ?>
		<div class="card card-body">
		<?php endif; ?>
		<?= $header; ?>
		<?php if( $params['box_list_header'] ) : ?>
		</div>
	<?php endif; ?>
<?php endif;

if( $params['modal_box_search'] ) : ?>
	<div class="card">
		<div class="card-body">
			search
		</div>
	</div>
<?php endif;

if( $params['box_content'] ) : ?>
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title"><?= $params['content_title']; ?></h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<?= $content; ?>
		</div>
		<div class="card-footer text-right text-muted">
			<?= ( isset( $info ) ) ? $info : ''; ?>
		</div>
	</div>
<?php else :
	echo $content; ?>
<?php endif; ?>

<?php
if( $params['show_list_footer'] ) : ?>
	<?php if( $params['box_list_header'] ) : ?>
		<div class="card card-body">
		<?php endif; ?>
		<?= $footer; ?>
		<?php if( $params['box_list_header'] ) : ?>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php
if( _MODE_ == 'add' || _MODE_ == 'edit' )
{
	echo form_close();
} ?>
