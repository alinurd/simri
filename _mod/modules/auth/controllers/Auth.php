<?php defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends MY_Controller
{
	public $datas = [];

	public function __construct()
	{

		parent::__construct();
		$this->templateFront();
		$this->form_validation->set_error_delimiters( $this->config->item( 'error_start_delimiter', 'ion_auth' ), $this->config->item( 'error_end_delimiter', 'ion_auth' ) );

		$this->lang->load( 'auth' );
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */

	public function index()
	{
		if( ! $this->ion_auth->logged_in() )
		{
			// redirect them to the login page
			redirect( 'login', 'refresh' );
		}
		else if( ! $this->ion_auth->is_admin() ) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			show_error( 'You must be an administrator to view this page.' );
		}
		else
		{
			$this->datas['title'] = $this->lang->line( 'index_heading' );

			// set the flash data error message if there is one
			$this->datas['message'] = ( validation_errors() ) ? validation_errors() : $this->session->flashdata( 'message' );

			//list the users
			$this->datas['users'] = $this->ion_auth->users()->result();

			//USAGE NOTE - you can do more complicated queries like this
			//$this->datas['users'] = $this->ion_auth->where('field', 'value')->users()->result();

			foreach( $this->datas['users'] as $k => $user )
			{
				$this->datas['users'][$k]->groups = $this->ion_auth->get_users_groups( $user->id )->result();
			}

			$this->_render_page( 'index', $this->datas );
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{

		if( $this->ion_auth->logged_in() )
		{
			redirect( 'dashboard' );
		}
		$this->datas['title'] = $this->lang->line( 'login_heading' );

		// validate form input
		$this->form_validation->set_rules( 'identity', str_replace( ':', '', $this->lang->line( 'login_identity_label' ) ), 'required' );
		$this->form_validation->set_rules( 'password', str_replace( ':', '', $this->lang->line( 'login_password_label' ) ), 'required' );

		if( $this->form_validation->run() === TRUE )
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post( 'remember' );

			$indetityCheck = $this->ion_auth->identity_check( $this->input->post( 'identity' ) );
			if( ! $indetityCheck )
			{
				$this->session->set_flashdata( 'message', "Incorrect Login" );
				redirect( 'login', 'refresh' );
			}

			$cekExpiredPassword = $this->checkExpiredPassword( $this->input->post() );
			if( ! $cekExpiredPassword )
			{
				$this->session->set_flashdata( 'message', "Password Sudah Expired, Harap Perbarui / Reset Password anda " );
				redirect( "login", "refresh" );
			}

			if( $this->ion_auth->login( $this->input->post( 'identity' ), $this->input->post( 'password' ), $remember ) )
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
				// $redirect = $this->session->userdata('last_visit');
				$redirect = base_url();
				header( 'location:' . urldecode( $redirect ) );
				// redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
				redirect( 'login', 'refresh' ); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->datas['message'] = ( validation_errors() ) ? validation_errors() : $this->session->flashdata( 'message' );

			$this->datas['identity'] = [
			 'name'  => 'identity',
			 'class' => 'form-control',
			 'id'    => 'identity',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'identity' ),
			];

			$this->datas['password'] = [
			 'name'  => 'password',
			 'class' => 'form-control',
			 'id'    => 'password',
			 'type'  => 'password',
			];

			$this->_render_page( 'login', $this->datas, TRUE );
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->datas['title'] = "Logout";

		// log the user out
		$this->db->update( 'users', [ 'session_id' => NULL, 'last_past_date' => time() ], [ $this->config->item( 'identity', 'ion_auth' ) => $this->session->userdata( 'identity' ) ] );

		$user = $this->session->userdata( 'data_user' );
		$this->logdata->set_log( 'sql', 'logout' );
		$this->logdata->type = 2;
		$this->logdata->user = [ 'id' => $user['id'], 'username' => $user['username'] ];
		$this->logdata->save_log();

		$this->ion_auth->logout();
		// redirect them to the login page
		$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
		redirect( 'login', 'refresh' );
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules( 'old', $this->lang->line( 'change_password_validation_old_password_label' ), 'required' );
		$this->form_validation->set_rules( 'new', $this->lang->line( 'change_password_validation_new_password_label' ), 'required|min_length[' . $this->config->item( 'min_password_length', 'ion_auth' ) . ']|matches[new_confirm]' );
		$this->form_validation->set_rules( 'new_confirm', $this->lang->line( 'change_password_validation_new_password_confirm_label' ), 'required' );

		if( ! $this->ion_auth->logged_in() )
		{
			redirect( 'login', 'refresh' );
		}

		$user = $this->ion_auth->user()->row();

		if( $this->form_validation->run() === FALSE )
		{
			// display the form
			// set the flash data error message if there is one
			$this->datas['message'] = ( validation_errors() ) ? validation_errors() : $this->session->flashdata( 'message' );

			$this->datas['min_password_length']  = $this->config->item( 'min_password_length', 'ion_auth' );
			$this->datas['old_password']         = [
			 'name' => 'old',
			 'id'   => 'old',
			 'type' => 'password',
			];
			$this->datas['new_password']         = [
			 'name'    => 'new',
			 'id'      => 'new',
			 'type'    => 'password',
			 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
			];
			$this->datas['new_password_confirm'] = [
			 'name'    => 'new_confirm',
			 'id'      => 'new_confirm',
			 'type'    => 'password',
			 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
			];
			$this->datas['user_id']              = [
			 'name'  => 'user_id',
			 'id'    => 'user_id',
			 'type'  => 'hidden',
			 'value' => $user->id,
			];

			// render
			$this->_render_page( 'change_password', $this->datas );
		}
		else
		{
			$identity = $this->session->userdata( 'identity' );

			$change = $this->ion_auth->change_password( $identity, $this->input->post( 'old' ), $this->input->post( 'new' ) );

			if( $change )
			{
				//if the password was successfully changed
				$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
				redirect( 'change-password', 'refresh' );
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		$this->datas['title'] = $this->lang->line( 'forgot_password_heading' );

		// setting validation rules by checking whether identity is username or email
		if( $this->config->item( 'identity', 'ion_auth' ) != 'email' )
		{
			$this->form_validation->set_rules( 'identity', $this->lang->line( 'forgot_password_identity_label' ), 'required' );
		}
		else
		{
			$this->form_validation->set_rules( 'identity', $this->lang->line( 'forgot_password_validation_email_label' ), 'required|valid_email' );
		}


		if( $this->form_validation->run() === FALSE )
		{

			$this->datas['type'] = $this->config->item( 'identity', 'ion_auth' );
			// setup the input
			$this->datas['identity'] = [
			 'name' => 'identity',
			 'id'   => 'identity',
			];

			if( $this->config->item( 'identity', 'ion_auth' ) != 'email' )
			{
				$this->datas['identity_label'] = $this->lang->line( 'forgot_password_identity_label' );
			}
			else
			{
				$this->datas['identity_label'] = $this->lang->line( 'forgot_password_email_identity_label' );
			}
			// set any errors and display the form
			$this->datas['message'] = ( validation_errors() ) ? validation_errors() : $this->session->flashdata( 'message' );

			$this->_render_page( 'forgot_password', $this->datas, TRUE );
		}
		else
		{
			$identity_column = $this->config->item( 'identity_email', 'ion_auth' );
			$identity        = $this->ion_auth->where( $identity_column, $this->input->post( 'identity' ) )->users()->row();

			if( empty( $identity ) )
			{

				if( $this->config->item( 'identity_email', 'ion_auth' ) != 'email' )
				{
					$this->ion_auth->set_error( 'forgot_password_identity_not_found' );
				}
				else
				{
					$this->ion_auth->set_error( 'forgot_password_email_not_found' );
				}

				$this->session->set_flashdata( 'message', $this->ion_auth->errors() );

				redirect( "recovery-password", 'refresh' );
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password( $identity->{$this->config->item( 'identity_email', 'ion_auth' )} );
			if( $forgotten )
			{
				$params = [
				 '[[RealName]]' => $identity->real_name,
				 '[[UserName]]' => $identity->username,
				//  '[[Link]]'     => '<a href="' . base_url( 'auth/reset-password/' . $forgotten ) . '">' . base_url( 'auth/reset-password/' . $forgotten ) . '</a>'
				 '[[Code]]'     => $forgotten,
				];
				$datas  = [
				 'recipient' => $this->input->post( 'identity' ),
				 'cc'        => '',
				 'bcc'       => $this->preference['email_admin'],
				];
				if( $this->preference['send_notif'] == 1 )
				{
					$this->load->library( 'outbox' );
					$this->outbox->setTemplate( 'TMP02' )->setParams( $params )->setDatas( $datas )->send();
				}

				// $this->session->set_flashdata( 'message', "Please Enter The code that we have sent to your email below" );
				redirect( "code-confirmation", 'refresh' ); //we should display a confirmation page here instead of the login page
			}
			else
			{

				redirect( "recovery-password", 'refresh' );
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */

	public function reset_password( $code = NULL )
	{
		unset( $_SESSION["message"] );
		$code = $this->input->post( "code_confirmation" ) ?? NULL;

		if( ! $code )
		{
			show_404();
		}

		$this->datas['title'] = $this->lang->line( 'reset_password_heading' );
		$user                 = $this->ion_auth_model->get_user_by_forgotten_password_code( $code );
		// $user = $this->ion_auth->forgotten_password_check($code);
		if( $user )
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules( 'new', $this->lang->line( 'reset_password_validation_new_password_label' ), 'required|min_length[' . $this->config->item( 'min_password_length', 'ion_auth' ) . ']|matches[new_confirm]' );
			$this->form_validation->set_rules( 'new_confirm', $this->lang->line( 'reset_password_validation_new_password_confirm_label' ), 'required' );

			if( $this->form_validation->run() === FALSE )
			{
				// display the form

				// set the flash data error message if there is one
				$this->datas['message'] = ( validation_errors() ) ? validation_errors() : $this->session->flashdata( 'message' );

				$this->datas['min_password_length']  = $this->config->item( 'min_password_length', 'ion_auth' );
				$this->datas['new_password']         = [
				 'name'    => 'new',
				 'id'      => 'new',
				 'type'    => 'password',
				 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
				];
				$this->datas['new_password_confirm'] = [
				 'name'    => 'new_confirm',
				 'id'      => 'new_confirm',
				 'type'    => 'password',
				 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
				];
				$this->datas['user_id']              = [
				 'name'      => 'user_id',
				 'id'        => 'user_id',
				 'type'      => 'hidden',
				 'value'     => $user['id'],
				 'real_name' => $user['real_name'],
				];
				$this->datas['csrf']                 = $this->_get_csrf_nonce();
				$this->datas['code']                 = $code;
				// render
				$this->_render_page( 'reset_password', $this->datas );
			}
			else
			{
				$identity = $user->{$this->config->item( 'identity', 'ion_auth' )};

				// do we have a valid request?
				if( $this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post( 'user_id' ) )
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code( $identity );

					show_error( $this->lang->line( 'error_csrf' ) );
				}
				else
				{

					// finally change the password
					$change = $this->ion_auth->reset_password( $identity, $this->input->post( 'new' ) );

					if( $change )
					{
						die( "masuk" );
						// if the password was successfully changed
						$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
						redirect( "login", 'refresh' );
					}
					else
					{
						$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
						redirect( 'reset_password/' . $code, 'refresh' );
					}
				}
			}
		}
		else
		{

			// redirect( "forgot-password", 'refresh' );
			// $uriRef = explode( "/", $_SERVER["HTTP_REFERER"] );
			// if( in_array( "code-confirmation", $uriRef ) )
			// {
			// 	// if the code is invalid then send them back to the forgot password page
			// 	$this->session->set_flashdata( 'message', "Code Confirmation Tidak Valid" );
			// 	$urlRedirect = "code-confirmation";

			// }
			// else
			// {
			// 	// if the code is invalid then send them back to the forgot password page
			// 	$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
			// 	$urlRedirect = "forgot-password";

			// }
			// $this->session->set_flashdata( 'message', $this->ion_auth->errors() );
			$this->session->set_flashdata( 'message', "Code Confirmation Tidak Valid" );
			$urlRedirect = "code-confirmation";
			redirect( $urlRedirect, "refresh" );
		}
	}

	public function proses_reset_password()
	{
		$user_id     = $this->input->post( 'user_id' );
		$new         = $this->input->post( 'new' );
		$new_confirm = $this->input->post( 'new_confirm' );
		$code        = $this->input->post( 'code' );
		$errors      = [];
		$no          = 0;
		if( $new !== $new_confirm )
		{
			$this->logdata->set_error( "Password tidak sama" );
			++$no;
		}
		else
		{
			checkPassword( $new, $errors );
		}
		if( count( $errors ) > 0 )
		{
			$user = $this->ion_auth_model->get_user_by_forgotten_password_code( $code );

			$this->datas['message']              = $errors;
			$this->datas['min_password_length']  = $this->config->item( 'min_password_length', 'ion_auth' );
			$this->datas['new_password']         = [
			 'name'    => 'new',
			 'id'      => 'new',
			 'type'    => 'password',
			 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
			];
			$this->datas['new_password_confirm'] = [
			 'name'    => 'new_confirm',
			 'id'      => 'new_confirm',
			 'type'    => 'password',
			 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
			];
			$this->datas['user_id']              = [
			 'name'      => 'user_id',
			 'id'        => 'user_id',
			 'type'      => 'hidden',
			 'value'     => $user['id'],
			 'real_name' => $user['real_name'],
			];
			$this->datas['csrf']                 = $this->_get_csrf_nonce();
			$this->datas['code']                 = $code;

			$this->_render_page( 'reset_password', $this->datas );
		}
		else
		{

			$this->crud->crud_table( _TBL_USERS );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $user_id ] );
			$this->crud->crud_where( [ 'field' => 'forgotten_password_selector', 'value' => $code ] );
			$this->crud->crud_field( 'password', $this->ion_auth_model->hash_password( $new ) );
			$this->crud->process_crud();

			$getUser      = $this->ion_auth->user( $user_id )->row();
			$getPref      = $this->db->get_where( _TBL_PREFERENCE, [ "uri_title" => "password_expr" ] )->row_array();
			$userDate     = date( "Y-m-d", strtotime( $getUser->updated_at ) );
			$setExpiredAt = date( "Y-m-d", strtotime( "+{$getPref["value"]} days", strtotime( $userDate ) ) );
			$dataUpdt     = [
			 "expired_at" => $setExpiredAt,
			 "sts_update" => 1,
			];
			$this->db->update( _TBL_USERS, $dataUpdt, [ "id" => $user_id ] );

			redirect( "login", 'refresh' );
		}
	}

	public function reset_password_prosess( $code = NULL )
	{
		die( "masuk" );

		if( ! $code )
		{
			show_404();
		}

		$this->datas['title'] = $this->lang->line( 'reset_password_heading' );
		$user                 = $this->ion_auth_model->get_user_by_forgotten_password_code( $code );
		// $user = $this->ion_auth->forgotten_password_check($code);
		if( $user )
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules( 'new', $this->lang->line( 'reset_password_validation_new_password_label' ), 'required|min_length[' . $this->config->item( 'min_password_length', 'ion_auth' ) . ']|matches[new_confirm]' );
			$this->form_validation->set_rules( 'new_confirm', $this->lang->line( 'reset_password_validation_new_password_confirm_label' ), 'required' );

			if( $this->form_validation->run() === FALSE )
			{
				// display the form

				// set the flash data error message if there is one
				$this->datas['message'] = ( validation_errors() ) ? validation_errors() : $this->session->flashdata( 'message' );

				$this->datas['min_password_length']  = $this->config->item( 'min_password_length', 'ion_auth' );
				$this->datas['new_password']         = [
				 'name'    => 'new',
				 'id'      => 'new',
				 'type'    => 'password',
				 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
				];
				$this->datas['new_password_confirm'] = [
				 'name'    => 'new_confirm',
				 'id'      => 'new_confirm',
				 'type'    => 'password',
				 'pattern' => '^.{' . $this->datas['min_password_length'] . '}.*$',
				];
				$this->datas['user_id']              = [
				 'name'      => 'user_id',
				 'id'        => 'user_id',
				 'type'      => 'hidden',
				 'value'     => $user['id'],
				 'real_name' => $user['real_name'],
				];
				$this->datas['csrf']                 = $this->_get_csrf_nonce();
				$this->datas['code']                 = $code;

				// render
				$this->_render_page( 'reset_password', $this->datas );
			}
			else
			{
				$identity = $user->{$this->config->item( 'identity', 'ion_auth' )};

				// do we have a valid request?
				if( $this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post( 'user_id' ) )
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code( $identity );

					show_error( $this->lang->line( 'error_csrf' ) );
				}
				else
				{
					// finally change the password
					$change = $this->ion_auth->reset_password( $identity, $this->input->post( 'new' ) );

					if( $change )
					{
						// if the password was successfully changed
						$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
						redirect( "login", 'refresh' );
					}
					else
					{
						$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
						redirect( 'reset_password/' . $code, 'refresh' );
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
			redirect( "recovery-password", 'refresh' );
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate( $id, $code = FALSE )
	{
		$activation = FALSE;

		if( $code !== FALSE )
		{
			$activation = $this->ion_auth->activate( $id, $code );
		}
		else if( $this->ion_auth->is_admin() )
		{
			$activation = $this->ion_auth->activate( $id );
		}

		if( $activation )
		{
			// redirect them to the auth page
			$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
			redirect( "auth", 'refresh' );
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
			redirect( "recovery-password", 'refresh' );
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate( $id = NULL )
	{
		if( ! $this->ion_auth->logged_in() || ! $this->ion_auth->is_admin() )
		{
			// redirect them to the home page because they must be an administrator to view this
			show_error( 'You must be an administrator to view this page.' );
		}

		$id = (int) $id;

		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'confirm', $this->lang->line( 'deactivate_validation_confirm_label' ), 'required' );
		$this->form_validation->set_rules( 'id', $this->lang->line( 'deactivate_validation_user_id_label' ), 'required|alpha_numeric' );

		if( $this->form_validation->run() === FALSE )
		{
			// insert csrf check
			$this->datas['csrf'] = $this->_get_csrf_nonce();
			$this->datas['user'] = $this->ion_auth->user( $id )->row();

			$this->_render_page( 'deactivate_user', $this->datas );
		}
		else
		{
			// do we really want to deactivate?
			if( $this->input->post( 'confirm' ) == 'yes' )
			{
				// do we have a valid request?
				if( $this->_valid_csrf_nonce() === FALSE || $id != $this->input->post( 'id' ) )
				{
					show_error( $this->lang->line( 'error_csrf' ) );
				}

				// do we have the right userlevel?
				if( $this->ion_auth->logged_in() && $this->ion_auth->is_admin() )
				{
					$this->ion_auth->deactivate( $id );
				}
			}

			// redirect them back to the auth page
			redirect( 'auth', 'refresh' );
		}
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->datas['title'] = $this->lang->line( 'create_user_heading' );

		if( ! $this->ion_auth->logged_in() || ! $this->ion_auth->is_admin() )
		{
			redirect( 'auth', 'refresh' );
		}

		$tables                         = $this->config->item( 'tables', 'ion_auth' );
		$identity_column                = $this->config->item( 'identity', 'ion_auth' );
		$this->datas['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules( 'first_name', $this->lang->line( 'create_user_validation_fname_label' ), 'trim|required' );
		$this->form_validation->set_rules( 'last_name', $this->lang->line( 'create_user_validation_lname_label' ), 'trim|required' );
		if( $identity_column !== 'email' )
		{
			$this->form_validation->set_rules( 'identity', $this->lang->line( 'create_user_validation_identity_label' ), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']' );
			$this->form_validation->set_rules( 'email', $this->lang->line( 'create_user_validation_email_label' ), 'trim|required|valid_email' );
		}
		else
		{
			$this->form_validation->set_rules( 'email', $this->lang->line( 'create_user_validation_email_label' ), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]' );
		}
		$this->form_validation->set_rules( 'phone', $this->lang->line( 'create_user_validation_phone_label' ), 'trim' );
		$this->form_validation->set_rules( 'company', $this->lang->line( 'create_user_validation_company_label' ), 'trim' );
		$this->form_validation->set_rules( 'password', $this->lang->line( 'create_user_validation_password_label' ), 'required|min_length[' . $this->config->item( 'min_password_length', 'ion_auth' ) . ']|matches[password_confirm]' );
		$this->form_validation->set_rules( 'password_confirm', $this->lang->line( 'create_user_validation_password_confirm_label' ), 'required' );

		if( $this->form_validation->run() === TRUE )
		{
			$email    = strtolower( $this->input->post( 'email' ) );
			$identity = ( $identity_column === 'email' ) ? $email : $this->input->post( 'identity' );
			$password = $this->input->post( 'password' );

			$additional_data = [
			 'first_name' => $this->input->post( 'first_name' ),
			 'last_name'  => $this->input->post( 'last_name' ),
			 'company'    => $this->input->post( 'company' ),
			 'phone'      => $this->input->post( 'phone' ),
			];
		}
		if( $this->form_validation->run() === TRUE && $this->ion_auth->register( $identity, $password, $email, $additional_data ) )
		{
			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
			redirect( "auth", 'refresh' );
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->datas['message'] = ( validation_errors() ? validation_errors() : ( $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata( 'message' ) ) );

			$this->datas['first_name']       = [
			 'name'  => 'first_name',
			 'id'    => 'first_name',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'first_name' ),
			];
			$this->datas['last_name']        = [
			 'name'  => 'last_name',
			 'id'    => 'last_name',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'last_name' ),
			];
			$this->datas['identity']         = [
			 'name'  => 'identity',
			 'id'    => 'identity',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'identity' ),
			];
			$this->datas['email']            = [
			 'name'  => 'email',
			 'id'    => 'email',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'email' ),
			];
			$this->datas['company']          = [
			 'name'  => 'company',
			 'id'    => 'company',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'company' ),
			];
			$this->datas['phone']            = [
			 'name'  => 'phone',
			 'id'    => 'phone',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'phone' ),
			];
			$this->datas['password']         = [
			 'name'  => 'password',
			 'id'    => 'password',
			 'type'  => 'password',
			 'value' => $this->form_validation->set_value( 'password' ),
			];
			$this->datas['password_confirm'] = [
			 'name'  => 'password_confirm',
			 'id'    => 'password_confirm',
			 'type'  => 'password',
			 'value' => $this->form_validation->set_value( 'password_confirm' ),
			];

			$this->_render_page( 'create_user', $this->datas );
		}
	}
	/**
	 * Redirect a user checking if is admin
	 */
	public function redirectUser()
	{
		if( $this->ion_auth->is_admin() )
		{
			redirect( 'auth', 'refresh' );
		}
		redirect( '/', 'refresh' );
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user( $id )
	{
		$this->datas['title'] = $this->lang->line( 'edit_user_heading' );

		if( ! $this->ion_auth->logged_in() || ( ! $this->ion_auth->is_admin() && ! ( $this->ion_auth->user()->row()->id == $id ) ) )
		{
			redirect( 'auth', 'refresh' );
		}

		$user          = $this->ion_auth->user( $id )->row();
		$groups        = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups( $id )->result();

		//USAGE NOTE - you can do more complicated queries like this
		//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();


		// validate form input
		$this->form_validation->set_rules( 'first_name', $this->lang->line( 'edit_user_validation_fname_label' ), 'trim|required' );
		$this->form_validation->set_rules( 'last_name', $this->lang->line( 'edit_user_validation_lname_label' ), 'trim|required' );
		$this->form_validation->set_rules( 'phone', $this->lang->line( 'edit_user_validation_phone_label' ), 'trim' );
		$this->form_validation->set_rules( 'company', $this->lang->line( 'edit_user_validation_company_label' ), 'trim' );

		if( isset( $_POST ) && ! empty( $_POST ) )
		{
			// do we have a valid request?
			if( $this->_valid_csrf_nonce() === FALSE || $id != $this->input->post( 'id' ) )
			{
				show_error( $this->lang->line( 'error_csrf' ) );
			}

			// update the password if it was posted
			if( $this->input->post( 'password' ) )
			{
				$this->form_validation->set_rules( 'password', $this->lang->line( 'edit_user_validation_password_label' ), 'required|min_length[' . $this->config->item( 'min_password_length', 'ion_auth' ) . ']|matches[password_confirm]' );
				$this->form_validation->set_rules( 'password_confirm', $this->lang->line( 'edit_user_validation_password_confirm_label' ), 'required' );
			}

			if( $this->form_validation->run() === TRUE )
			{
				$data = [
				 'first_name' => $this->input->post( 'first_name' ),
				 'last_name'  => $this->input->post( 'last_name' ),
				 'company'    => $this->input->post( 'company' ),
				 'phone'      => $this->input->post( 'phone' ),
				];

				// update the password if it was posted
				if( $this->input->post( 'password' ) )
				{
					$data['password'] = $this->input->post( 'password' );
				}

				// Only allow updating groups if user is admin
				if( $this->ion_auth->is_admin() )
				{
					// Update the groups user belongs to
					$this->ion_auth->remove_from_group( '', $id );

					$groupData = $this->input->post( 'groups' );
					if( isset( $groupData ) && ! empty( $groupData ) )
					{
						foreach( $groupData as $grp )
						{
							$this->ion_auth->add_to_group( $grp, $id );
						}
					}
				}

				// check to see if we are updating the user
				if( $this->ion_auth->update( $user->id, $data ) )
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
					$this->redirectUser();
				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
					$this->redirectUser();
				}
			}
		}

		// display the edit user form
		$this->datas['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->datas['message'] = ( validation_errors() ? validation_errors() : ( $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata( 'message' ) ) );

		// pass the user to the view
		$this->datas['user']          = $user;
		$this->datas['groups']        = $groups;
		$this->datas['currentGroups'] = $currentGroups;

		$this->datas['first_name']       = [
		 'name'  => 'first_name',
		 'id'    => 'first_name',
		 'type'  => 'text',
		 'value' => $this->form_validation->set_value( 'first_name', $user->first_name ),
		];
		$this->datas['last_name']        = [
		 'name'  => 'last_name',
		 'id'    => 'last_name',
		 'type'  => 'text',
		 'value' => $this->form_validation->set_value( 'last_name', $user->last_name ),
		];
		$this->datas['company']          = [
		 'name'  => 'company',
		 'id'    => 'company',
		 'type'  => 'text',
		 'value' => $this->form_validation->set_value( 'company', $user->company ),
		];
		$this->datas['phone']            = [
		 'name'  => 'phone',
		 'id'    => 'phone',
		 'type'  => 'text',
		 'value' => $this->form_validation->set_value( 'phone', $user->phone ),
		];
		$this->datas['password']         = [
		 'name' => 'password',
		 'id'   => 'password',
		 'type' => 'password',
		];
		$this->datas['password_confirm'] = [
		 'name' => 'password_confirm',
		 'id'   => 'password_confirm',
		 'type' => 'password',
		];

		$this->_render_page( 'edit_user', $this->datas );
	}

	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->datas['title'] = $this->lang->line( 'create_group_title' );

		if( ! $this->ion_auth->logged_in() || ! $this->ion_auth->is_admin() )
		{
			redirect( 'auth', 'refresh' );
		}

		// validate form input
		$this->form_validation->set_rules( 'group_name', $this->lang->line( 'create_group_validation_name_label' ), 'trim|required|alpha_dash' );

		if( $this->form_validation->run() === TRUE )
		{
			$new_group_id = $this->ion_auth->create_group( $this->input->post( 'group_name' ), $this->input->post( 'description' ) );
			if( $new_group_id )
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata( 'message', $this->ion_auth->messages() );
				redirect( "auth", 'refresh' );
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->datas['message'] = ( validation_errors() ? validation_errors() : ( $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata( 'message' ) ) );

			$this->datas['group_name']  = [
			 'name'  => 'group_name',
			 'id'    => 'group_name',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'group_name' ),
			];
			$this->datas['description'] = [
			 'name'  => 'description',
			 'id'    => 'description',
			 'type'  => 'text',
			 'value' => $this->form_validation->set_value( 'description' ),
			];

			$this->_render_page( 'create_group', $this->datas );
		}
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group( $id )
	{
		// bail if no group id given
		if( ! $id || empty( $id ) )
		{
			redirect( 'auth', 'refresh' );
		}

		$this->datas['title'] = $this->lang->line( 'edit_group_title' );

		if( ! $this->ion_auth->logged_in() || ! $this->ion_auth->is_admin() )
		{
			redirect( 'auth', 'refresh' );
		}

		$group = $this->ion_auth->group( $id )->row();

		// validate form input
		$this->form_validation->set_rules( 'group_name', $this->lang->line( 'edit_group_validation_name_label' ), 'trim|required|alpha_dash' );

		if( isset( $_POST ) && ! empty( $_POST ) )
		{
			if( $this->form_validation->run() === TRUE )
			{
				$group_update = $this->ion_auth->update_group( $id, $_POST['group_name'], array(
				 'description' => $_POST['group_description'],
				) );

				if( $group_update )
				{
					$this->session->set_flashdata( 'message', $this->lang->line( 'edit_group_saved' ) );
				}
				else
				{
					$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
				}
				redirect( "auth", 'refresh' );
			}
		}

		// set the flash data error message if there is one
		$this->datas['message'] = ( validation_errors() ? validation_errors() : ( $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata( 'message' ) ) );

		// pass the user to the view
		$this->datas['group'] = $group;

		$this->datas['group_name'] = [
		 'name'  => 'group_name',
		 'id'    => 'group_name',
		 'type'  => 'text',
		 'value' => $this->form_validation->set_value( 'group_name', $group->name ),
		];
		if( $this->config->item( 'admin_group', 'ion_auth' ) === $group->name )
		{
			$this->datas['group_name']['readonly'] = 'readonly';
		}

		$this->datas['group_description'] = [
		 'name'  => 'group_description',
		 'id'    => 'group_description',
		 'type'  => 'text',
		 'value' => $this->form_validation->set_value( 'group_description', $group->description ),
		];

		$this->_render_page( 'edit_group', $this->datas );
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper( 'string' );
		$key   = random_string( 'alnum', 8 );
		$value = random_string( 'alnum', 20 );
		$this->session->set_flashdata( 'csrfkey', $key );
		$this->session->set_flashdata( 'csrfvalue', $value );

		return [ $key => $value ];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post( $this->session->flashdata( 'csrfkey' ) );
		if( $csrfkey && $csrfkey === $this->session->flashdata( 'csrfvalue' ) )
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page( $view, $data = NULL, $returnhtml = FALSE ) //I think this makes more sense
	{


		$viewdata = ( empty( $data ) ) ? $this->datas : $data;

		$view_html = $this->load->view( $view, $viewdata, $returnhtml );
		$this->template->content->add( $view_html );
		$this->template->publish();

		// // This will return html on 3rd argument being true
		// if ($returnhtml)
		// {
		// 	return $view_html;
		// }
	}

	function lock_screen()
	{
		$sts = $this->session->userdata( 'lock_screen' );
		if( ! $sts )
		{
			$this->session->set_userdata( [ 'lock_screen' => TRUE ] );
			$this->session->set_userdata( [ 'last_visit' => urlencode( current_url() ) ] );
			$this->session->set_userdata( [ 'lock_time' => date( 'd M Y H:i:s' ) ] );
			$data['waktu'] = date( 'd M Y H:i:s' );
		}
		else
		{
			$this->session->userdata( 'lock_time' );
			$data['waktu'] = $this->session->userdata( 'lock_time' );
		}
		$this->load->view( 'lock', $data );
	}

	function unlock()
	{
		if( $this->ion_auth->unlock( $this->input->post( 'sandi' ) ) )
		{
			$this->session->set_userdata( [ 'lock_screen' => FALSE ] );
			$url = urldecode( $this->session->userdata( 'last_visit' ) );
			header( 'location:' . base_url() );
		}
		else
		{
			$this->session->set_flashdata( 'message', $this->ion_auth->errors() );
			redirect( 'auth/lock-screen', 'refresh' );
		}
	}


	function checkExpiredPassword( $postData )
	{

		$getUser = $this->db->get_where( _TBL_USERS, [ "username" => $postData["identity"] ] )->row_array();
		$getPref = $this->db->get_where( _TBL_PREFERENCE, [ "uri_title" => "password_expr" ] )->row_array();

		if( $this->ion_auth->in_group( 1, $getUser["id"] ) )
		{
			return TRUE;
		}
		elseif( ! $this->ion_auth->in_group( 1, $getUser["id"] ) && ! $getUser["sts_update"] || ! $getUser["expired_at"] )
		{
			return FALSE;
		}


		/**
		 * @tidak terpakai
		 */
		$userDate        = date( "Y-m-d", strtotime( $getUser["updated_at"] ) );
		$userExpiredDate = date( "Y-m-d", strtotime( $getUser["expired_at"] ) );
		if( empty( $userExpiredDate ) )
		{
			$setExpiredAt = date( "Y-m-d", strtotime( "+{$getPref["value"]} days", strtotime( $userDate ) ) );
			$dataUpdt     = [
			 "expired_at" => $setExpiredAt,
			 "sts_update" => 1,
			];
			$this->db->update( _TBL_USERS, $dataUpdt, [ "id" => $getUser["id"] ] );
			$userExpiredDate = date( "Y-m-d", strtotime( $setExpiredAt ) );
		}
		/**end */

		return ( strtotime( $userDate ) > strtotime( $userExpiredDate ) ) ? FALSE : TRUE;

	}

	function code_confirmation()
	{
		$this->_render_page( 'code_confirmation', $this->datas, TRUE );
	}
}
