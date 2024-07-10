<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="<?= file_url( $this->template->_params['preference']['image_favicon'] ); ?>" />
	<title><?= $this->template->_params['preference']['nama_kantor'] . ' - ' . $this->template->title; ?></title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
		type="text/css">

	<?= $this->template->stylesheet; ?>
	<?= $this->template->stylesheet_end; ?>
	<?= $this->template->javascript; ?>
	<?= $this->template->javascript_end; ?>
</head>

<body class="navbar-top <?= ( $this->template->_params['left_sidebar_mini'] ) ? 'sidebar-xs' : '' ?>">
	<?php echo $this->template->widget( "Header", array( 'title' => 'Project name', 'params' => $this->template->_params ) ); ?>
	<div class="page-content">
		<?php echo $this->template->widget( "Left_Sidebar", array( 'title' => 'SideBar', 'params' => $this->template->_params ) ); ?>
		<?php echo $this->template->second_sidebar; ?>
		<div class="content-wrapper">
			<?php echo $this->template->header_content; ?>
			<div class="content">
				<?php echo $this->template->widget( "Tombol_Aksi", array( 'title' => 'Tombol', 'params' => $this->template->_params ) ); ?>
				<?php echo $this->template->content; ?>
			</div>
			<?php echo $this->template->widget( "Footer", array( 'title' => 'Footer', 'params' => $this->template->_params ) ); ?>
		</div>
		<?php echo $this->template->right_sidebar; ?>
	</div>
	<!-- Clickable menu -->
	<!-- <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right" data-fab-toggle="hover" data-fab-state="closed">
			<li>
				<a class="fab-menu-btn btn bg-indigo-400 btn-float rounded-round btn-icon legitRipple">
					<i class="fab-icon-open icon-cog52"></i>
					<i class="fab-icon-close icon-cross2"></i>
				</a>

				<ul class="fab-menu-inner">
					<li>
						<div data-fab-label="Support/Tiket">
							<a href="<?= base_url( 'support' ); ?>" class="btn btn-light rounded-round btn-icon btn-float">
								<i class="icon-cog2"></i>
							</a>
						</div>
					</li>
					<li>
						<div data-fab-label="Full Screen">
							<a href="#" class="btn btn-light rounded-round btn-icon btn-float  full_screen">
								<i class="icon-screen-full"></i>
							</a>
						</div>
					</li>
					<li>
						<div data-fab-label="Lock Screen">
							<a href="<?= base_url( 'lock-screen' ); ?>" class="btn bg-light rounded-round btn-icon btn-float">
							<i class="icon-lock"></i>
							</a>
						</div>
					</li>
					<li>
						<div data-fab-label="Close Aplication">
							<a href="<?= base_url( 'auth/logout' ); ?>" class="btn bg-light rounded-round btn-icon btn-float">
							<i class="icon-enter3"></i>
							</a>
						</div>
					</li>
				</ul>
			</li>
		</ul> -->
	<!-- /clickable menu -->

	<script>

		var base_url = "<?php echo base_url(); ?>";
		var mode_aksi = "<?php echo _MODE_; ?>";
		var modul_name = "<?php echo _MODULE_NAME_; ?>";
		var csrf_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
		var csrf_cookie_name = '<?php echo $this->config->item( 'csrf_cookie_name' ); ?>';
		var csrf_hash = '<?php echo $this->security->get_csrf_hash(); ?>';
		var Globals = <?php echo json_encode( array(
		'sLengthMenu'   => _l( 'msg_data_table_sLengthMenu' ),
		'sZeroRecords'  => _l( 'msg_data_table_sZeroRecords' ),
		'sInfo'         => _l( 'msg_data_table_sInfo' ),
		'sInfoEmpty'    => _l( 'msg_data_table_sInfoEmpty' ),
		'sInfoFiltered' => _l( 'msg_data_table_sInfoFiltered' ),
		'sSearch'       => _l( 'msg_data_table_sSearch' ),
		'sFirst'        => _l( 'msg_data_table_sFirst' ),
		'sPrevious'     => _l( 'msg_data_table_sPrevious' ),
		'sNext'         => _l( 'msg_data_table_sNext' ),
		'sLast'         => _l( 'msg_data_table_sLast' ),
		'cboSelect'     => _l( 'msg_cbo_select' ),
		'hapus'         => _l( 'msg_confirm_delete' ),
		'nil_combo'     => 100,
		) ); ?>;
		$(function () {
			var object = {};
			object[csrf_token_name] = csrf_hash;

			$.ajaxSetup({
				data: object
			});
			$(document).ajaxComplete(function () {
				object[csrf_token_name] = csrf_hash;
				$('input[name="' + csrf_token_name + '"]').val(csrf_hash);
				$.ajaxSetup({
					data: object
				});
			});

			$('form').submit(function () {
				object[csrf_token_name] = csrf_hash;
				$('input[name="' + csrf_token_name + '"]').val(csrf_hash);
				return true;
			});
		});

		$('.summernote-code').summernote({
			height: 400,   //set editable area's height
			codemirror: { // codemirror options
				theme: 'monokai'
			}
		});
	</script>
</body>

</html>