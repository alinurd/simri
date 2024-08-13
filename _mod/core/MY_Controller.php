<?php ( defined( 'BASEPATH' ) ) or exit( 'No direct script access allowed' );

/* load the MX_Router class */
//require APPPATH."third_party/MX/Controller.php";
// owner tri untoro (tri.untoro@gmail.com)

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MY_Controller extends MX_Controller
{

	private $_template_ = 'template';
	private $_template_front = 'login';
	public $_js_ = [];
	public $_css_ = [];
	public $_data_user_ = [];
	protected $configuration = [];
	protected $config_list = [];
	protected $sts_open_tab = FALSE;
	protected $sts_open_coloums = FALSE;
	protected $sts_open_coloums_all = FALSE;
	protected $jml_tabs = 0;
	protected $jml_coloums = 0;
	protected $jml_coloums_all = 0;
	protected $tmp_data = array();
	protected $data_fields = array();
	protected $_button = array();
	protected $can = array();
	protected $canTmp = array();
	protected $post = array();
	protected $_STS_INSERT = TRUE;
	protected $_STS_UPDATE = TRUE;
	protected $i_left = 0;
	protected $tbl_simpan = '';
	protected $multi_lang_value = [];
	protected $param_meta_value = [];
	protected $free_modul = [];

	public function __construct()
	{
		parent::__construct();

		$this->remap_default = array( 'add' => '__insert', 'edit' => '__update', 'delete' => '__delete', 'delete_all' => '__delete_all', 'export' => '__export_all', 'view' => '__update', 'print' => '__print' );

		$lock_screen = $this->session->userdata( 'lock_screen' );
		if( $lock_screen && $this->router->fetch_module() !== 'auth' )
		{
			header( 'location:' . base_url( 'auth/lock-screen' ) );
		}

		if( ! $this->ion_auth->logged_in() && $this->router->fetch_module() !== 'auth' )
		{
			$redirect_to            = urlencode( current_url() );
			$redirect['last_visit'] = $redirect_to;
			$this->session->set_userdata( $redirect );
			header( 'location:' . base_url( 'login' ) );
			exit();
		}

		$this->_data_user_ = $this->session->userdata( 'data_user' );

		$this->load->model( 'auth/ion_auth_crud', 'crud' );
		$this->config->load( 'configuration', TRUE );
		$this->can                            = [ 'read' => TRUE, 'insert' => FALSE, 'update' => FALSE, 'delete' => FALSE, 'print' => FALSE ];
		$this->configuration                  = $this->config->item( 'default_config', 'configuration' );
		$live_config                          = $this->data_config->get_Preference( '', 1 );
		$this->configuration                  = array_merge( $this->configuration, $live_config );
		$this->config_list                    = $this->config->item( 'default_list', 'configuration' );
		$this->configuration['content_title'] = ( ! empty( $this->lang->line( _MODULE_NAME_REAL_ . '_title' ) ) ) ? $this->lang->line( _MODULE_NAME_REAL_ . '_title' ) : ucwords( str_replace( '-', ' ', _MODULE_NAME_ ) ) . ' ' . ucwords( _MODE_ );
		$this->configuration['preference']    = $this->data_config->get_Preference();
		$this->configuration['user']          = $this->_data_user_;
		$this->configuration['free_module']   = $this->config->item( 'free_module', 'configuration' );
		$this->configuration['_mode_']        = $this->_mode_;
		$this->preference                     = $this->configuration['preference'];
		// if ($this->_is_data_exist){
		// 	$this->data->_set_params($this->configuration);
		// }
		$user_id = 0;
		if( $this->configuration['user'] )
		{
			$user_id = $this->configuration['user']['id'];
		}
		define( '_USER_ID_', $user_id );
		$this->css();
		$this->js();

		$this->initialize();

		if( $this->configuration['themes_mode'] !== 'default' )
		{
			$this->_template_ = $this->configuration['themes_mode'];
		}

		// if (isset($this->configuration['user']['modul'])){
		// 	$this->_template_='depan';
		// }
		if( isset( $_GET['cs'] ) )
		{
			unset( $_POST );
			$search['_' . $this->modul_name . '_search_'] = array();
			$this->session->set_userdata( $search );
			$this->post = [];
		}
		else
		{
			if( isset( $_POST['sts_query'] ) )
			{
				$search['_' . $this->modul_name . '_search_'] = $this->input->post();
				$this->session->set_userdata( $search );
				$this->post = $this->input->post();
			}
			else
			{
				$this->post = $this->session->userdata( '_' . $this->modul_name . '_search_' );
			}
		}

		$jml = 0;
		if( $x = $this->session->userdata( '_' . $this->modul_name . '_search_' ) )
		{
			foreach( $this->session->userdata( '_' . $this->modul_name . '_search_' ) as $key => $qs )
			{
				if( $key !== 'sts_query' )
				{
					if( ! empty( $qs ) )
					{
						++$jml;
					}
				}
			}
		}
		// $this->breadcrumbs->push('Home', '/');
		$this->breadcrumbs->push( ucwords( str_replace( '-', ' ', _MODULE_NAME_ ) ), _MODULE_NAME_ );

		if( empty( $this->post ) )
			$this->post = array();
	}

	function get_template()
	{
		return $this->_template_;
	}

	function set_template_( $tmp )
	{
		$this->_template_ = $tmp;
	}

	protected function initialize()
	{
		$this->tmp_data['tabs']        = array();
		$this->tmp_data['coloums']     = array();
		$this->tmp_data['coloums_all'] = array();

		if( method_exists( $this->router->fetch_class(), 'init' ) )
		{
			$configuration = (array) $this->init();
			$this->register_configuration( $configuration['configuration'] );
		}

		return $this;
	}

	function setPrivilege( $mode, $value = TRUE )
	{
		$this->canTmp[$mode] = $value;
	}

	function register_button( $mode = 'list', $idEdit = 0 )
	{
		if( $mode == 'list' )
		{
			if( $this->can['insert'] )
			{
				$this->_button['list']['insert'] = [
				 'label' => lang( 'btn_insert' ),
				 'color' => 'bg-primary',
				 'id'    => 'btn_new',
				 'tag'   => 'a',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-database-add',
				 'url'   => base_url( _MODULE_NAME_ . '/add/' ),
				 'align' => 'left',
				];
			}

			if( $this->can['update'] )
			{
				$this->_button['one']['update'] = [
				 'label' => $this->lang->line( 'btn_update' ),
				 'id'    => 'btn_edit_one',
				 'class' => 'text-success',
				 'icon'  => 'icon-database-edit2 ',
				 'url'   => base_url( _MODULE_NAME_ . '/edit/' ),
				];
			}

			if( $this->can['delete'] )
			{
				$this->_button['list']['delete'] = [
				 'label' => $this->lang->line( 'btn_delete' ),
				 'color' => 'bg-danger',
				 'id'    => 'btn_delete',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-database-remove',
				 'url'   => base_url( _MODULE_NAME_ . '/delete-all/' ),
				 'class' => 'disabled',
				 'align' => 'left',
				];

				$this->_button['one']['delete'] = [
				 'label' => $this->lang->line( 'btn_delete' ),
				 'id'    => 'btn_delete_one',
				 'icon'  => 'icon-database-remove',
				 'class' => 'delete text-danger',
				 'url'   => base_url( _MODULE_NAME_ . '/delete/' ),
				];
			}
			if( $this->can['print'] )
			{
				$this->_button['list']['print'] = [
				 'label' => $this->lang->line( 'btn_export' ),
				 'color' => 'bg-green',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-file-excel ',
				 'align' => 'left',
				];

				$this->_button['list']['print']['detail']['excel'] = [
				 'label' => $this->lang->line( 'btn_export_excel' ),
				 'color' => 'bg-green',
				 'id'    => 'btn_export_excel',
				 'tag'   => 'a',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-file-excel ',
				 'url'   => base_url( _MODULE_NAME_ . '/export/excel' ),
				 'align' => 'left',
				];

				$this->_button['list']['print']['detail']['pdf'] = [
				 'label' => $this->lang->line( 'btn_export_pdf' ),
				 'color' => 'bg-green',
				 'id'    => 'btn_export_pdf',
				 'tag'   => 'a',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-file-pdf',
				 'url'   => base_url( _MODULE_NAME_ . '/export/pdf' ),
				 'align' => 'left',
				];

				$this->_button['one']['print'] = [
				 'label' => $this->lang->line( 'btn_print' ),
				 'id'    => 'btn_export',
				 'icon'  => 'icon-printer',
				 'class' => 'text-primary',
				 'attr'  => [ 'target' => '_blank' ],
				 'url'   => base_url( _MODULE_NAME_ . '/print/' ),
				];
			}
			if( $this->configuration['modal_box_search'] )
			{
				$this->_button['list']['search'] = [
				 'label' => $this->lang->line( 'btn_search' ),
				 'color' => 'bg-info',
				 'id'    => 'btn_search',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-search4',
				 'url'   => base_url( _MODULE_NAME_ . '/search/' ),
				 'attr'  => ' data-toggle="modal" data-target="#modal_form_search" ',
				 'align' => 'right',
				];
			}
			else
			{
				$this->_button['list']['search'] = [
				 'label' => $this->lang->line( 'btn_search' ),
				 'color' => 'bg-info',
				 'id'    => 'btn_search_card',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-search4',
				 'url'   => base_url( _MODULE_NAME_ . '/search/' ),
				 'align' => 'right',
				];
			}


			$this->_button['one']['view'] = [
			 'label' => $this->lang->line( 'btn_view' ),
			 'id'    => 'btn_view',
			 'icon'  => 'icon-clipboard3',
			 'url'   => base_url( _MODULE_NAME_ . '/view/' ),
			];

			if( method_exists( $this->router->fetch_class(), 'optionalButton' ) )
			{
				$this->_button['list'] = $this->optionalButton( $this->_button['list'], $mode );
			}
		}
		elseif( $mode == 'add' || $mode == 'edit' )
		{
			if( $this->can['insert'] || $this->can['update'] )
			{
				$this->_button['input']['save'] = [
				 'label' => $this->lang->line( 'btn_save' ),
				 'color' => 'bg-info',
				 'id'    => 'btn_save',
				 'name'  => 'Save',
				 'value' => 'Simpan',
				 'type'  => 'submit',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-floppy-disk',
				 'url'   => base_url( _MODULE_NAME_ . '/save/' ),
				];
			}

			if( $this->can['insert'] || $this->can['update'] )
			{
				$this->_button['input']['save_quit'] = [
				 'label' => $this->lang->line( 'btn_save_quit' ),
				 'color' => 'bg-success',
				 'id'    => 'btn_save_quit',
				 'name'  => 'Save_Quit',
				 'value' => 'Simpan_Quit',
				 'type'  => 'submit',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-floppy-disk',
				 'url'   => base_url( _MODULE_NAME_ . '/save-quit/' ),
				];
			}

			if( $this->can['insert'] )
			{
				$this->_button['input']['insert'] = [
				 'label' => $this->lang->line( 'btn_insert' ),
				 'color' => 'bg-primary',
				 'id'    => 'btn_new',
				 'align' => 'right',
				 'tag'   => 'a',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-database-add',
				 'url'   => base_url( _MODULE_NAME_ . '/add/' ),
				];
			}

			if( $this->can['delete'] && $mode == 'edit' )
			{
				$this->_button['input']['delete'] = [
				 'label' => $this->lang->line( 'btn_delete' ),
				 'color' => 'bg-danger',
				 'id'    => 'btn_delete',
				 'tag'   => 'a',
				 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
				 'icon'  => 'icon-database-remove',
				 'class' => 'delete',
				 'url'   => base_url( _MODULE_NAME_ . '/delete/' . $idEdit ),
				 'align' => 'left',
				];
			}

			$this->_button['input']['back'] = [
			 'label' => $this->lang->line( 'btn_back' ),
			 'color' => 'bg-slate',
			 'id'    => 'btn_back',
			 'tag'   => 'a',
			 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
			 'icon'  => 'icon-exit',
			 'url'   => base_url( _MODULE_NAME_ ),
			];
			if( method_exists( $this->router->fetch_class(), 'optionalButton' ) )
			{
				$this->_button['input'] = $this->optionalButton( $this->_button['input'], $mode );
			}
		}
	}

	private function register_configuration( $configuration )
	{
		if( is_array( $configuration ) )
		{
			$this->configuration = array_merge( $this->configuration, $configuration );
		}
		return $this;
	}

	public function _remap( $method, $arguments )
	{
		$sts        = TRUE;
		$method_cek = str_replace( '_', '-', $this->router->fetch_module() );
		if( _USER_ID_ )
		{
			$this->can = $this->ion_auth->privilege( $method_cek, $this->configuration['user'] );
		}

		$this->can = array_merge( $this->can, $this->canTmp );

		if( ! $this->can['read'] && $this->router->fetch_module() !== 'errorpage' && ! in_array( $this->router->fetch_module(), $this->configuration["free_module"] ) && ( ! $this->ion_auth->is_admin() ) )
		{
			header( 'location:' . base_url( 'access-denied' ) );
			exit();
		}
		elseif( array_key_exists( $method, $this->remap_default ) )
		{
			$method = $this->remap_default[$method];
		}
		elseif( $method == 'index' )
		{
			if( ! method_exists( $this->router->fetch_class(), 'index' ) )
			{
				$method = 'index_default';
				if( ! method_exists( $this->router->fetch_class(), $method ) )
				{
					$sts = FALSE;
				}
			}
			else
			{
				$this->register_button( 'list' );
				$this->configuration['button'] = $this->_button;
			}
		}
		elseif( ! method_exists( $this->router->fetch_class(), $method ) )
		{
			$sts = FALSE;
		}

		if( $sts )
		{
			call_user_func_array( [ $this, $method ], $arguments );
		}
		else
		{
			header( 'location:' . base_url( 'errorpage' ) );
		}
	}

	function templateFront()
	{
		$this->template->title->add( 'Welcomes!' );
		$this->template->title = 'Selamat Datang';
		if( method_exists( $this->router->fetch_class(), 'contentTitle' ) )
			$this->template->contentTitle = $this->contentTitle();

		$this->template->set_template( $this->_template_front );
		$this->template->_params = $this->configuration;
		$this->template->stylesheet->add( $this->_css_ );
		$this->template->javascript->add( $this->_js_ );
		$this->template->header_front->view( $this->_template_front . '/header' );
		$this->template->footer_front->view( $this->_template_front . '/footer' );
	}

	function templateDepan()
	{
		$this->template->title->add( 'Welcomes!' );
		$this->template->title = 'Selamat Datang';
		if( method_exists( $this->router->fetch_class(), 'contentTitle' ) )
			$this->template->contentTitle = $this->contentTitle();

		$this->template->set_template( 'depan' );
		$this->template->_params = $this->configuration;
		$this->template->stylesheet->add( $this->_css_ );
		$this->template->javascript->add( $this->_js_ );
		$this->template->header_front->view( $this->_template_front . '/header' );
		$this->template->footer_front->view( $this->_template_front . '/footer' );
	}

	function set_template()
	{
		$this->template->title->add( 'Welcomes!' );
		$this->template->title = 'Selamat Datang';
		$this->template->set_template( $this->_template_ );
		$this->template->stylesheet->add( $this->_css_ );
		$this->template->javascript->add( $this->_js_ );
		if( $this->configuration['show_header_content'] )
		{
			$breadcrumbs = $this->breadcrumbs->show();
			$this->template->header_content->view( $this->_template_ . '/header_content', array( 'breadcrumbs' => $breadcrumbs, 'param' => $this->configuration ) );
		}
		$data_second = [];
		if( method_exists( $this->router->fetch_class(), 'secondSidebarData' ) )
		{
			$data_second = $this->secondSidebarData();
		}

		if( $this->configuration['show_second_sidebar'] )
		{
			$this->template->second_sidebar->view( $this->_template_ . '/second_sidebar', array( 'title' => 'Hello, world!', 'params' => $this->configuration, 'data' => $data_second ) );
		}

		if( $this->configuration['show_right_sidebar'] )
		{
			$this->template->right_sidebar->view( $this->_template_ . '/right_sidebar', array( 'title' => 'Hello, world!', 'params' => $this->configuration, 'data' => $data_second ) );
		}
	}

	function default_display( $param = [] )
	{
		$this->template->set_template( $this->_template_ );
		$this->template->stylesheet->add( $this->_css_ );
		$this->template->javascript->add( $this->_js_ );
		$info   = '';
		$footer = '';
		$header = '';
		if( array_key_exists( 'info', $param ) )
		{
			$info = $param['info'];
		}
		if( array_key_exists( 'footer', $param ) )
		{
			$footer = $param['footer'];
		}
		if( array_key_exists( 'header', $param ) )
		{
			$header = $param['header'];
		}
		if( array_key_exists( 'configuration', $param ) )
		{
			$this->register_configuration( $param['configuration'] );
		}
		$this->template->content->view( $this->_template_ . '/content', [ 'content' => $param['content'], 'params' => $this->configuration, 'header' => $header, 'footer' => $footer, 'info' => $info ] );

		$this->template->_params = $this->configuration;
		$this->template->publish();
	}

	function index_default()
	{
		$this->register_button( 'list' );
		$this->configuration['button'] = $this->_button;
		$content                       = $this->_template_ . '/list';
		if( isset( $this->configuration['monitoring'] ) )
		{
			$content = $this->_template_ . '/list-monitoring';
		}
		if( ! $this->input->is_ajax_request() && array_key_exists( 'fields', $this->tmp_data ) )
		{
			$this->set_search_box( $this->post );
		}
		if( method_exists( $this->router->fetch_class(), 'content' ) )
		{
			$content = $this->content();
		}
		else
		{
			$this->breadcrumbs->push( 'List', 'list' );
			$arr_tmp           = $this->tmp_data;
			$arr_tmp['params'] = $this->configuration;
			$content           = $this->load->view( $content, $arr_tmp, TRUE );
		}
		$header = '';
		$footer = '';
		if( method_exists( $this->router->fetch_class(), 'setContentHeader' ) )
		{
			$header = $this->setContentHeader( _MODE_ );
		}
		if( method_exists( $this->router->fetch_class(), 'setContentFooter' ) )
		{
			$footer = $this->setContentFooter( _MODE_ );
		}

		$this->set_template();

		if( $this->configuration['tab_list'] )
		{
			$isi[] = [ 'title' => $this->configuration['tab_title'], 'content' => $content ];
			if( method_exists( $this->router->fetch_class(), 'contentTab' ) )
			{
				$tab = $this->contentTab();
				// dump($tab);die();
				if( is_array( $tab ) )
				{
					foreach( $tab as $row )
					{
						$isi[] = [ 'title' => $row['title'], 'content' => $row['content'] ];
					}
				}
			}
			$content = $this->load->view( $this->_template_ . '/list-tab', [ 'data' => $isi ], TRUE );
		}
		$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'field' => $this->tmp_data, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer ] );

		$this->template->_params = $this->configuration;
		$this->template->publish();
	}

	function set_Tbl_Master( $tbl, $db = "", $prefix = TRUE )
	{
		if( empty( $db ) )
		{
			$db = $this->db->database;
		}

		$tbl = str_replace( $this->db->dbprefix ?? '', '', $tbl );

		if( $prefix )
		{
			$this->tbl_master = $this->db->dbprefix( $tbl );
		}
		else
		{
			$this->tbl_master = $tbl;
		}
		$this->tmp_data['table'] = $tbl;
		$this->set_Attr_Table( 'size', '100%' );
		$this->tbl_simpan = $this->tbl_master;
	}

	function set_Attr_Table( $attr, $nilai, $type = "style" )
	{
		$this->tmp_data['attrTable'][$attr] = $nilai;
	}

	function set_Tbl_Master_Child( $tbl, $id )
	{
		$tbl                      = str_replace( $this->db->dbprefix ?? '', '', $tbl );
		$this->tbl_master_child[] = [ 'id' => $id, 'tbl' => $this->db->dbprefix( $tbl ) ];
	}

	function set_Table( $tbl )
	{
		$tblx         = str_replace( $this->db->dbprefix ?? '', '', $tbl );
		$tbl_x        = 'tbl_' . $tblx;
		$this->$tbl_x = $this->db->dbprefix( $tblx );
	}

	function set_Field_Primary( string $tbl, $field, $info = TRUE )
	{
		$this->tmp_data['primary'] = array( 'tbl' => $tbl, 'id' => $field, 'info' => $info );
	}

	function set_Close_Setting()
	{
		$this->data_fields['master'] = $this->tmp_data;
	}

	function set_Join_Table( $data = array() )
	{
		$arr_tmp['master'] = 1;
		$arr_tmp['pk']     = $data['pk'];
		if( array_key_exists( 'id_pk', $data ) )
			$arr_tmp['id_pk'] = $data['id_pk'];
		if( array_key_exists( 'sp', $data ) )
			$arr_tmp['sp'] = $data['sp'];
		if( array_key_exists( 'id_sp', $data ) )
			$arr_tmp['id_sp'] = $data['id_sp'];
		if( array_key_exists( 'type', $data ) )
			$arr_tmp['type'] = $data['type'];

		$this->tmp_data['m_tbl'][] = $arr_tmp;
	}

	function set_Sort_Table( $nmtbl, $field, $type = 'asc' )
	{
		$this->tmp_data['sort'][] = array( 'tbl' => $nmtbl, 'id' => $field, 'type' => $type );
	}

	function set_Group_Table( $nmtbl, $field )
	{
		$this->tmp_data['group'][] = array( 'tbl' => $nmtbl, 'id' => $field );
	}

	function set_Where_Table( $param = [] )
	{
		if( ! array_key_exists( 'tbl', $param ) )
		{
			$param['tbl'] = $this->tmp_data['primary']['tbl'];
		}
		if( ! array_key_exists( 'op', $param ) )
		{
			$param['op'] = '=';
		}
		$this->tmp_data['where'][] = array( 'tbl' => $param['tbl'], 'id' => $param['field'], 'op' => $param['op'], 'value' => $param['value'] );
	}

	function set_Free_Where_Table( $field, $source, $stsCek = TRUE )
	{
		if( $stsCek )
		{
			if( isset( $this->post['q_' . $field] ) )
			{
				if( ! empty( $this->post['q_' . $field] ) )
				{
					$this->tmp_data['free_where'][] = array( 'source' => $source );
				}
			}
		}
		else
		{
			$this->tmp_data['free_where'][] = array( 'source' => $source );
		}
	}

	function set_Table_List( $nmtbl, $field, $title = '', $size = 0, $align = 'left', $print = TRUE )
	{

		if( empty( $title ) )
		{
			if( defined( strtoupper( '_' . $nmtbl . '_' . $field . '_' ) ) )
			{
				$title = constant( strtoupper( '_' . $nmtbl . '_' . $field . '_' ) );
			}
			else
			{
				$title = ucwords( $field );
			}
		}

		$this->tmp_data['title'][] = array( $nmtbl, $field, $title, $size, $align, $print );
	}

	function _DONT_UPDATE()
	{
		$this->_STS_UPDATE = FALSE;
	}

	function _DONT_INSERT()
	{
		$this->_STS_INSERT = FALSE;
	}

	function set_Open_Tab( $title, $icon = "list" )
	{
		$this->sts_open_tab                      = TRUE;
		$this->jml_tabs                          = count( $this->tmp_data['tabs'] );
		$this->tmp_data['tabs'][$this->jml_tabs] = array( 'title' => $title, 'id' => 'tab-0' . count( $this->tmp_data['tabs'] ), 'icon' => $icon );
	}

	function set_Close_Tab()
	{
		$this->sts_open_tab = FALSE;
	}

	function set_Open_Coloums( $title = "" )
	{
		$jml = 0;
		if( $this->tmp_data['tabs'] )
		{
			if( array_key_exists( 'cols', $this->tmp_data['tabs'][$this->jml_tabs] ) )
				$jml = count( $this->tmp_data['tabs'][$this->jml_tabs]['cols'] );
		}

		$this->sts_open_coloums                        = TRUE;
		$this->jml_coloums                             = count( $this->tmp_data['coloums'] );
		$this->tmp_data['coloums'][$this->jml_coloums] = array( 'title' => $title, 'id' => 'col-0' . $jml );
	}

	function set_Close_Coloums()
	{
		$this->sts_open_coloums = FALSE;
	}

	function set_Open_Coloums_All( $title = "" )
	{
		$this->sts_open_coloums_all                            = TRUE;
		$this->jml_coloums_all                                 = count( $this->tmp_data['coloums_all'] );
		$this->tmp_data['coloums_all'][$this->jml_coloums_all] = array( 'title' => $title, 'id' => 'col-0' . count( $this->tmp_data['tabs'][$this->jml_coloums_all]['cols_all'] ) );
	}

	function set_Close_Coloums_All()
	{
		$this->sts_open_coloums_all = FALSE;
	}

	function addBid( $data = [] )
	{
		$this->tmp_data['fields'][$data['field']] = array_merge( $this->tmp_data['fields'][$data['field']], $data );
	}

	function set_Save_Table( $tbl )
	{
		$this->tbl_simpan = $tbl;
	}

	function addField( $data = array() )
	{
		$config_list = $this->config->item( 'default_list', 'configuration' );
		$tbl         = $this->tbl_master;
		if( array_key_exists( 'tbl', $data ) )
			$tbl = $data['tbl'];
		$data['nmtbl'] = $tbl;

		$field = $data['field'];
		$title = lang( 'fld_' . $data['field'] );
		if( empty( $title ) )
		{
			if( ! array_key_exists( 'title', $data ) )
			{
				$title = ucwords( str_replace( '_', ' ', $data['field'] ) );
			}
			else
			{
				$title = $data['title'];
			}
		}
		define( strtoupper( '_' . $tbl . '_' . $field . '_' ), $title );
		$data['title'] = $title;

		if( array_key_exists( 'values', $data ) )
		{
			if( ! array_key_exists( 'input', $data ) )
			{
				$data['input'] = 'combo';
			}
			if( ! array_key_exists( 'type', $data ) )
			{
				$data['type'] = 'int';
			}
		}

		if( ! array_key_exists( 'input', $data ) )
		{
			$data['input'] = 'text';
		}

		if( ( $data['input'] == 'boolean' || $data['input'] == 'bool:switch' ) && ! array_key_exists( 'default', $data ) )
		{
			$data['default'] = 1;
		}
		elseif( $data['input'] == 'updown' && ! array_key_exists( 'size', $data ) )
		{
			if( $this->agent->is_mobile() )
			{
				$data['size'] = 100;
			}
			else
			{
				$data['size'] = 20;
			}
		}
		elseif( $data['input'] == 'multitext' && ! array_key_exists( 'size', $data ) )
		{
			$data['size'] = 500;
		}

		$show = TRUE;
		if( array_key_exists( 'show', $data ) )
			$show = $data['show'];

		$save = FALSE;
		if( $show )
			$save = TRUE;

		if( array_key_exists( 'save', $data ) )
			$save = $data['save'];
		$data['save'] = $save;

		// if (array_key_exists('multiselect',$data) && !array_key_exists('default',$data)){
		// 	$data['default']=-1;
		// }

		$placeholder = $this->lang->line( 'ph_' . $field ) ? $this->lang->line( 'ph_' . $field ) : '';
		if( empty( $placeholder ) )
		{
			$placeholder = $data['title'];
			if( array_key_exists( 'placeholder', $data ) )
				$placeholder = $data['placeholder'];
		}
		$data['placeholder'] = $placeholder;

		$this->tmp_data['fields'][$field] = array_merge( $config_list, $data );

		if( $this->sts_open_tab )
		{
			$this->tmp_data['tabs'][$this->jml_tabs]['field'][] = $field;
			if( $this->sts_open_coloums )
			{
				$this->tmp_data['tabs'][$this->jml_tabs]['cols'][$this->jml_coloums][] = $field;
			}

			if( $this->sts_open_coloums_all )
			{
				$this->tmp_data['tabs'][$this->jml_tabs]['cols_all'][$this->jml_coloums_all][] = $field;
			}
		}
	}

	function set_search_box( $value = [] )
	{
		$search = [];
		foreach( $this->tmp_data['fields'] as &$row )
		{
			$isi = '';
			if( $value )
			{
				if( array_key_exists( $row['field'], $value ) )
					$isi = $value[$row['field']];
			}

			if( $row['search'] )
			{
				$x = '';
				if( $row['search'] )
				{

					$mdl = 'inputBox_' . strtoupper( $row['field'] );
					if( method_exists( $this->router->fetch_class(), $mdl ) )
					{
						$x = $this->$mdl( 'edit', $row, $value, $isi );
					}

					if( $isi )
					{

						if( array_key_exists( 'values', $row ) )
						{
							$method_name = 'searchBox_' . strtoupper( $row['field'] );

							if( method_exists( $this->router->fetch_class(), $method_name ) )
							{
								$row['values'] = $this->$method_name( $value, $isi );
							}

							if( array_key_exists( $isi, $row['values'] ) )
							{

								$search[] = [ 'field' => $row['title'], 'value' => $row['values'][$isi] ];
							}
							else
							{
								$search[] = [ 'field' => $row['title'], 'value' => $isi ];
							}
						}
						else
						{
							$search[] = [ 'field' => $row['title'], 'value' => $isi ];
						}
					}
				}

				if( $x )
				{
					$row['box'] = $x;
				}
				else
				{
					$row['box'] = $this->set_box_input( $row, $isi, TRUE );
				}
			}
		}

		unset( $row );
		$this->tmp_data['search'] = $search;
	}


	function set_input_box( $value = [], $mode = 'add' )
	{
		$search = [];
		foreach( $this->tmp_data['fields'] as &$row )
		{
			$isi = '';
			if( $value )
			{
				if( array_key_exists( $row['field'], $value ) )
				{
					if( $row['json'] && ! is_array( $value[$row['field']] ) )
					{
						$isi = json_decode( $value[$row['field']], TRUE );
					}
					elseif( $row['multiselect'] && ! is_array( $value[$row['field']] ) )
					{
						$isi = explode( ',', $value[$row['field']] );
					}
					else
					{
						$isi = $value[$row['field']];
					}
				}
			}
			if( $isi == '' )
			{
				if( array_key_exists( 'default', $row ) && $mode == 'add' )
					$isi = $row['default'];
			}
			$draw         = TRUE;
			$getKeyStatus = ( ! empty( $value ) ) ? array_key_exists( $row['field'], $value ) : FALSE;
			if( $row['type'] !== 'free' && ! $getKeyStatus && $mode == 'edit' )
			{
				$draw = FALSE;
			}
			$row['isi'] = $isi;
			if( empty( $row['inputBox'] ) )
			{
				$method_name = 'inputBox_' . strtoupper( $row['field'] );
				if( $row['show'] && $draw )
				{
					if( method_exists( $this->router->fetch_class(), $method_name ) )
					{
						$x          = $this->$method_name( $mode, $row, $value, $isi );
						$row['box'] = $x;
					}
					else
					{
						$row['box'] = $this->set_box_input( $row, $isi );
					}
				}
				else
				{
					if( method_exists( $this->router->fetch_class(), $method_name ) )
					{
						$x          = $this->$method_name( $mode, $row, $value, $isi );
						$row['box'] = $x;
					}
				}
			}
			else
			{
				$method_name = $row['inputBox'];
				$x           = call_user_func_array( $method_name, [ $mode, $row, $value, $isi ] );
				// $x=$this->$method_name($mode, $row, $value, $isi);
				$row['box'] = $x;
			}
		}
		unset( $row );
	}

	function set_box_input( $row, $isi = "", $search = FALSE )
	{
		$required        = "";
		$class_upper     = "";
		$class_lower     = "";
		$mode            = 'a';
		$error           = "";
		$placeholder     = '';
		$disabled        = '';
		$align           = 'text-left';
		$readOnly        = '';
		$content         = '';
		$width           = "20%";
		$autofocus       = '';
		$nopass          = 0;
		$feedBack        = '';
		$feedBackContent = '';
		$start_prepend   = '';
		$prepend         = '';
		$end_prepend     = '';
		$append          = '';
		$size            = '';

		$type = $row['input'];
		switch( $type )
		{
			case 'int':
			case 'integer':
			case 'intdot':
			case 'integerdot':
			case 'float':
				$align = 'text-right';
				break;
			default:
				$align = 'text-left';
				break;
		}

		if( $row['textup'] )
		{
			$class_upper = ' text-uppercase ';
		}

		if( $row['textlo'] )
		{
			$class_lower = ' text-lower ';
		}

		if( $row['readonly'] && ! $search )
		{
			$readOnly = ' readonly="readonly" ';
		}

		if( array_key_exists( 'align', $row ) )
		{
			$align = 'text-' . $row['align'];
		}

		if( array_key_exists( 'alias', $row ) )
		{
			$label = $row['alias'];
		}
		else
		{
			$label = $row['field'];
		}

		if( ! empty( $row['prepend'] ) || ! empty( $row['append'] ) )
		{
			$size          = $row['size'] . '%';
			$start_prepend = '<div class="input-group" style="width:' . $size . ';">';
			if( ! empty( $row['prepend'] ) )
			{
				$prepend .= '<span class="input-group-prepend pointer" id="prepend_' . $row['field'] . '">
					<span class="input-group-text">' . $row['prepend'] . '</span>
				</span>';
			}
			if( ! empty( $row['append'] ) )
			{
				$append .= '<span class="input-group-append pointer" id="append_' . $row['field'] . '">
				<span class="input-group-text">' . $row['append'] . '</span>
				</span>';
			}
			$end_prepend = '</div>';
		}

		if( $row['bidFeedBack'] )
		{
			$feedBack        = '<div class="form-group form-group-feedback form-group-feedback-' . $row['bidFeedBackAlign'] . '">';
			$feedBackContent = '<div class="form-control-feedback">' . $row['bidFeedBackContent'] . '</div></div>';
		}

		if( $this->configuration['placeholder_tool'] )
		{
			if( ! empty( $row['placeholder'] ) )
				$placeholder = 'placeholder="' . $row['placeholder'] . '"';
		}

		if( $row['required'] && ! $search )
			$required = 'required="required"';

		if( $row['disabled'] && ! $search )
		{
			$disabled = 'disabled';
		}

		if( _MODE_ == 'view' )
		{
			$disabled = ' disabled ';
		}

		$content = "";

		$width = '';
		if( empty( $size ) )
		{
			$size = $row['size'] . '%';
			if( $row['size'] == 100 || ! empty( $feedBack ) )
			{
				$size = '100%';
			}
			$width = 'width:' . $size . ' !important;';
		}
		switch( $type )
		{
			case 'plaintext':
				$content = '<div class="form-control-plaintext">' . $isi . '</div>';
				break;
			case 'string':
			case 'text':

				$content = form_input( $label, $isi, " size='" . $row['size'] . "' maxlength='" . $row['max'] . "'  class='form-control $error $align $class_upper $class_lower' $disabled $required $readOnly $placeholder id=$label $autofocus style='$width' " );
				if( ! empty( $disabled ) )
				{
					$content .= form_hidden( $label, $isi );
				}
				break;
			case 'tag':
				$content = form_input( $label, $isi, " size=$row[size] class='form-control tokenfield $error $align' $required $disabled $placeholder id=$label $autofocus " );
				if( ! empty( $disabled ) )
				{
					$content .= form_hidden( $label, $isi );
				}
				break;
			case 'multitext':
				$jmlhuruf = intval( $row['size'] ) - intval( strlen( $isi ) );

				++$this->i_left;
				$left = 'id_sisa_' . $this->i_left;
				$size = "100%";
				$isi = nl2br( stripslashes( $isi ) );

				if( intval( $row['size'] ) > 0 )
				{
					$content = form_textarea( $label, $isi, " id='$label' maxlength='$row[size]' size=$row[size] $disabled $placeholder $readOnly class='form-control $error $align' rows='12' cols='5' style='overflow: hidden; width: $size !important; height: 200px;'  onblur='_maxLength(this , \"$left\")' onkeyup='_maxLength(this , \"$left\")' data-role='tagsinput' $autofocus ", TRUE, [ 'size' => $jmlhuruf, 'isi' => intval( strlen( $isi ) ), 'no' => $this->i_left ] );
					// $content .='<br/><span class="text-warning">'.lang('msg_chr_left').' </span><span style="display:inline-block;height:20px;"><small><input id="'.$left.'" type="hidden" align="right" class="form-control" style="text-align:right;width:60px;" disabled="" name="f1_11_char_left" value="'.$jmlhuruf.'" size="5">'.lang('btn_chr_left').'<span id="span_'.$left.'"  align="right" class="badge badge-primary " name="f1_11_char_left">'.$jmlhuruf.'</small></span></span>';
				}
				else
				{
					$content = form_textarea( $label, $isi, " id='$label' maxlength='10000' size=$row[size] $disabled class='form-control $error $align' rows='2' cols='5' style='overflow: hidden; width: $size !important; height: 104px;' " );
				}
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );

				break;
			case 'html':
				$content = form_textarea( $label, html_entity_decode( $isi ), " id='$label' class='trumbowyg'" );
				// $content ='<div class="summernote">';
				// $content .=$isi;
				// $content .='</div>';

				break;
			case 'pass':
				$id_pass = 'password' . ++$nopass;
				$result_pass = "result" . $nopass;
				$content = form_password( $label, '', " size=$row[size] $disabled $required class='form-control $error $align' autocomplete='new-password' id='$id_pass' $autofocus  style='$width'  " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, '' );
				break;
			case 'int':
			case 'integer':
				$size = $row['size'] . '%';
				if( $row['size'] == 100 || ! empty( $feedBack ) )
				{
					$size = '100%';
				}

				$content = form_input( $label, $isi, " class='form-control angka $error $align'  maxlength='" . $row['max'] . "' $disabled $required size=$row[size] id=$label $readOnly $autofocus style='width: $size' " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'float':
				$isi = number_format( floatval( $isi ) );
				if( empty( $isi ) )
					$isi = 0;

				$size = $row['size'] . '%';
				if( $row['size'] == 100 || ! empty( $feedBack ) )
				{
					$size = '100%';
				}

				$content = form_input( $label, $isi, " class='form-control rupiah $error $align' $disabled $required size=$row[size] style='width: $size'  id=$label $readOnly $autofocus " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'intdot':
			case 'integerdot':
				$content = form_input( $label, $isi, " class='form-control numericdot $error $align' $required $disabled size=$row[size]  id=$label $readOnly $autofocus " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'updown':
				if( empty( $isi ) )
				{
					$isi = '';
				}
				$size = 'width:' . $row['size'] . '% !important;';
				$sizes = $row['size'];
				if( $this->agent->is_mobile() )
				{
					$sizes = 100;
					$size  = '';
				}
				//$content= form_input(array('type'=>'number','name'=>$label),$isi," $disabled $required class='form-control touchspin-postfix $error $align' $readOnly size=$row[size] style='width:$row[size]% !important;' id=$label $autofocus ");
				$content = '<div class="input-group" style="width:' . $sizes . '% !important;">
					<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();">
						-
					</button>';

				$content .= form_input( array( 'type' => 'number', 'name' => $label ), $isi, " $disabled $required class='form-control touchspin-postfix $error text-center' $readOnly max='" . $row['maxrange'] . "' min='" . $row['minrange'] . "' step='" . $row['steprange'] . "' style='" . $size . "' id=$label $autofocus " );

				$content .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();">
						+
					</button>
					</div>';
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'color':
				$content = form_input( array( 'type' => 'color', 'name' => $label ), $isi, " $disabled class='$error form-control' size=$row[size] style='height:30px;width:80px;background-color:$isi;' id=$label $autofocus " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'range':
				if( ! $isi )
					$isi = 1;
				$content = form_input( array( 'type' => 'range', 'name' => $label ), $isi, " class='$error form-control' id='" . $label . "'  min='" . $row['minrange'] . "' max='" . $row['maxrange'] . "' step='" . $row['steprange'] . "' oninput='ageOutputId.value = $label.value' " );
				$content .= '<output name="ageOutputName" id="ageOutputId">' . $isi . '</output>';
				break;
			case 'boolean':
				$content = form_dropdown( $label, array( '' => '-', '0' => lang( 'cbo_no' ), '1' => lang( 'cbo_yes' ) ), $isi, "id=$label $disabled $required style='height:30px;width:100px' class='form-control' $autofocus " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'bool:switch':
				$content = "";
				$check = FALSE;
				if( $isi )
				{
					$check = TRUE;
				}

				$content .= form_hidden( $label, 0 );
				$content .= '<div class="form-check form-check-switchery form-check-inline form-check-switchery-double">
				<label class="form-check-label">';
				$content .= lang( 'cbo_no' );
				$content .= form_checkbox( $label, 1, $check, 'id="' . $label . '" class="pointer form-switchery-primary" ' );
				$content .= lang( 'cbo_yes' );
				$content .= '</label></div>';
				break;
			case 'boolean:string':
				$content = form_dropdown( $label, array( '' => '-', 'N' => lang( 'msg_cbo_no' ), 'Y' => lang( 'msg_cbo_yes' ) ), $isi, "id=$label $disabled style='height:30px;width:100px' class='form-control' $autofocus " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'combox':
				// $size=$row['size'].'px';
				$size = 'auto';
				if( $row['size'] == 100 )
				{
					$size = '100%';
				}
				elseif( $row['size'] == 0 )
				{
					$size = 'auto';
				}
				$content = '<div id="loadingmessage" class="waitting" style="display: none;"></div>';
				$content .= form_dropdown( $label, $row['values'], $isi, "id=$label $disabled $required class='$error form-control' style='width:$size  !important;'  $autofocus $readOnly " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;

			case 'combo':
				$multi = '';
				// $size = $row['size'] . 'px'[]
				$size = $row['size'] * 5;
				$size .= 'px';
				if( $row['multiselect'] )
				{
					$size  = '100%';
					$multi = ' multiple="multiple" ';
					$label = $label . '[]';
					if( ! is_array( $isi ) )
						$isi = explode( ',', $isi );
				}
				elseif( $row['size'] == 100 )
				{
					$size = '100%';
				}
				$content = form_dropdown( $label, $row['values'], $isi, "id=$label $disabled class='$error form-control select' style='width:$size;'  $autofocus $multi " );
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'date':
				$size = 'auto';
				if( $row['size'] == 100 )
				{
					$size = '100%';
				}
				elseif( $row['size'] == 0 )
				{
					$size = 'auto';
				}

				$tgl = date( 'd-m-Y' );
				if( ! empty( $isi ) )
				{
					if( $isi == '01-01-1970' )
						$isi = date( 'd F, Y' );
					else
						$isi = date( 'd F, Y', strtotime( $isi ) );
				}
				$content = '<div class="input-group"   style="width:' . $row['size'] . '%;">
							<span class="input-group-prepend">
								<span class="input-group-text"><i class="icon-calendar3"></i></span>
							</span>';
				$content .= form_input( $label, $isi, " id=$label class='form-control $error pickadate' $disabled $required  $readOnly $autofocus " );
				$content .= '</div>';
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;

			case 'time':
				$size = 'auto';
				if( $row['size'] == 100 )
				{
					$size = '100%';
				}
				elseif( $row['size'] == 0 )
				{
					$size = 'auto';
				}

				$tgl = date( 'd-m-Y H:i' );
				if( ! empty( $isi ) )
					$tgl = date( 'd-m-Y H:i', strtotime( $isi ) );

				$content = '<div class="input-group"><span class="input-group-prepend">
							<span class="input-group-text"><i class="icon-alarm"></i></span>
						</span>';

				$content .= form_input( $label, $isi, " id=$label size=$row[size] class='form-control $error pickatime' $disabled $required style='width:$size px;'  $readOnly $autofocus " );
				$content .= '</div>';
				if( ! empty( $disabled ) )
					$content .= form_hidden( $label, $isi );
				break;
			case 'upload':
				$content = '';
				// $o = '<img id="img_' . $label . '" style="margin-top:10px;"  width="' . $row['size_pic'] . '" src="" alt="image"/>';
				$o = '<img id="img_' . $label . '" style="margin-top:10px;"  width="' . $row['size_pic'] . '" src="' . setImageDefault( "" ) . '" alt="image"/>';
				$oo = "";

				if( ! empty( $isi ) )
				{
					$kel = 'image';
					if( array_key_exists( 'path', $row ) )
					{
						$pt      = explode( '/', $row['path'] );
						$path    = $pt[0] . '_path_relative';
						$info    = pathinfo( $path( $isi ) );
						$info_ci = get_file_info( $path( $isi ) );
						$url     = $pt[0] . '_url';
						$url     = $url( $isi );
						$kel     = 'file'; //$row['path'];
					}
					else
					{
						$info    = pathinfo( img_path_relative( $isi ) );
						$info_ci = get_file_info( img_path_relative( $isi ) );
						$url     = img_url( $isi );
					}
					/**
					 * @change default image if image not exist
					 */
					if( ! empty( setImageDefault( $path( $isi ) ) ) )
					{
						$url = setImageDefault( $path( $isi ) );
						$isi = $url;
					}

					/** end */
					if( strtolower( $info['extension'] ) == "jpg" || strtolower( $info['extension'] ) == "png" || strtolower( $info['extension'] ) == "jpeg" || strtolower( $info['extension'] ) == "gif" || strtolower( $info['extension'] ) == "bmp" )
					{
						$o = '<img id="img_' . $label . '"  src="' . $url . '" alt="image" style="margin-top:10px;" class="detail-img pointer" width="' . $row['size_pic'] . '"  data-file="' . $isi . '" data-path="' . $pt[0] . '" />';
					}
					$nmFunc = $kel . '_path_relative';
					$size   = 0;
					if( $info_ci )
					{
						if( $info_ci['size'] > 2000 )
							$size = number_format( $info_ci['size'] / 1024 ) . ' kb';
						else
							$size = $info_ci['size'] . ' byte';
					}
					$oo = '<br/><span class="well"><span data-url="' . base_url( 'ajax/download_preview/' ) . '" data-target="' . $kel . '" data-file="' . $isi . '" class="preview_file pointer text-primary">' . $isi . '</span></span><br/><span style="padding-left:19px;">Size : ' . $size . '</span><br/>&nbsp;<br/>';
				}
				$content = $o . $oo;
				$content .= '<br><small>note :<br/>Image type : [' . $row['file_type'] . ']<br/>Max Image Size : ' . number_format( floatval( $row['file_size'] ) / 1024, 2 ) . ' Mb</small><br/><div class="upload-btn-wrapper">
				<button class="btn">Upload a file</button>';
				$content .= form_upload( $label, '', 'class="pointer" onchange="showMyImage(this,\'img_' . $label . '\')"' );
				$content .= form_hidden( [ $label . '_tmp' => $isi ] );
				$content .= '</div>';
				break;
			case 'radio':
				$br = 'form-check-inline';
				if( array_key_exists( 'vertical', $row ) )
					if( $row['vertical'] )
						$br = '';
				$content = "";
				foreach( $row['combo'] as $key => $cbo )
				{
					$check = FALSE;
					if( $isi == $key )
					{
						$check = TRUE;
					}
					$content .= '<div class="form-check  ' . $br . '">
					<label class="form-check-label">';
					$content .= form_radio( $label, $key, $check, 'id="' . $label . '_' . $key . '"  class="form-check-primary" ' );
					$content .= form_label( $cbo . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $label . '_' . $key, [ 'class' => 'pointer' ] );
					$content .= '</label></div>';
				}
				break;
			case 'check':
				$br = 'form-check-inline';
				if( array_key_exists( 'vertical', $row ) )
					if( $row['vertical'] )
						$br = '';

				$content = "";
				if( empty( $isi ) )
					$isi = '';

				$isi = explode( ",", $isi );

				foreach( $row['combo'] as $key => $cbo )
				{
					$check = FALSE;
					if( in_array( $key, $isi ) )
					{
						$check = TRUE;
					}
					$content .= '<div class="form-check ' . $br . '">
					<label class="form-check-label">';
					$content .= form_checkbox( $label . '[]', $key, $check, 'id="' . $label . '_' . $key . '" class="form-check-primary" ' );
					$content .= form_label( $cbo . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $label . '_' . $key, [ 'class' => 'pointer' ] );
					$content .= '</label></div>';
				}
				break;
			case 'switch':
				$content = "";
				$isi = TRUE;
				$check = FALSE;
				if( $isi )
				{
					$check = TRUE;
				}

				$content .= '<div class="form-check form-check-switchery form-check-inline form-check-right">
				<label class="form-check-label">';
				$content .= form_checkbox( $label, $isi, $check, 'id="' . $label . '" class="pointer form-switchery-primary" ' );
				$content .= 'Test saja';
				$content .= '</label></div>';

				break;
		}

		// $contents['content'] = $content;
		// $contents['feedBack'] = $feedBack;
		// $contents['feedBackContent'] = $feedBackContent;
		return $start_prepend . $prepend . $feedBack . $content . $feedBackContent . $append . $end_prepend;
	}

	function __insert()
	{

		$this->breadcrumbs->push( 'Add', 'add' );
		$manualSave = FALSE;
		$data       = $this->input->post();
		$this->set_input_box( $data, 'add' );
		$save = '';
		if( $data )
			$save = $data['l_save'];
		$header = '';
		$footer = '';
		$this->register_button( 'add' );
		$this->configuration['button'] = $this->_button;
		$this->configuration['fields'] = $this->tmp_data['fields'];

		if( method_exists( $this->router->fetch_class(), 'setContentHeader' ) )
		{
			$header = $this->setContentHeader( _MODE_ );
		}
		if( method_exists( $this->router->fetch_class(), 'setContentFooter' ) )
		{
			$footer = $this->setContentFooter( _MODE_ );
		}
		$this->configuration['_mode_'] = 'add';
		if( ! empty( $save ) )
		{
			if( isset( $data['Save'] ) )
				$mode = 'Stay';
			else
				$mode = 'Back';
			$sts_form_validation = $this->cek_form_validation( $this->tmp_data['fields'] );

			if( $sts_form_validation )
			{
				if( method_exists( $this->router->fetch_class(), 'checkBeforeSave' ) )
				{
					$sts_form_validation = $this->checkBeforeSave( $this->input->post(), '', 'add' );
				}
			}

			if( $sts_form_validation == FALSE )
			{
				$this->session->set_flashdata( "message", "" );
				$this->session->set_flashdata( "message_crud", "" );
				$this->session->set_flashdata( "message_crud_error", "" );
				$this->session->set_flashdata( 'message_crud_error', $this->logdata->errors_array() );
				$this->template->_params = $this->configuration;
				$this->set_template();
				$content       = $this->_template_ . '/input';
				$tmp           = $this->tmp_data;
				$tmp['params'] = $this->configuration;
				if( method_exists( $this->router->fetch_class(), 'inputContent' ) )
				{
					$content = $this->inputContent( 'add', $tmp );
				}
				else
				{
					$content = $this->load->view( $content, $tmp, TRUE );
				}
				$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer ] );

				$this->template->publish();
			}
			else
			{
				$this->tmp_data['data'] = $this->input->post();

				foreach( $this->tmp_data['fields'] as $field )
				{
					if( array_key_exists( 'show', $field ) )
					{
						if( $field['show'] || $field['save'] )
						{
							if( array_key_exists( 'alias', $field ) )
							{
								$label = $field['alias'];
							}
							else
							{
								$label = $field['field'];
							}
							$method_name = 'insertValue_' . strtoupper( $field['field'] );

							if( method_exists( $this->router->fetch_class(), $method_name ) )
							{
								$this->tmp_data['data'][$label] = $this->$method_name( $this->tmp_data['data'][$label], $this->tmp_data['data'], '' );
							}
						}
					}
				}
				$this->db->trans_begin();
				if( method_exists( $this->router->fetch_class(), 'manualSave' ) )
				{
					$id_new     = $this->manualSave( $this->tmp_data, 'add' );
					$manualSave = TRUE;
				}
				else
				{
					if( $this->_STS_INSERT )
					{
						$this->crud->crud_table( $this->tbl_simpan );
						$this->crud->crud_type( 'add' );
						foreach( $this->tmp_data['fields'] as $row )
						{
							if( array_key_exists( 'show', $row ) )
							{
								if( ( ( $row['show'] && $row['save'] ) || ( ! $row['show'] && $row['save'] ) ) && $row['type'] !== 'free' )
								{
									$label = $row['field'];
									if( array_key_exists( 'alias', $row ) )
									{
										$label = $row['alias'];
									}
									$value = '##nodata##';
									if( array_key_exists( $label, $this->tmp_data['data'] ) )
									{
										$value = $this->tmp_data['data'][$label];
										if( is_array( $this->tmp_data['data'][$label] ) )
										{
											if( $row['json'] )
											{
												$value = json_encode( $this->tmp_data['data'][$label] );
											}
											else
											{
												$value = implode( ',', $this->tmp_data['data'][$label] );
											}
											$this->tmp_data['data'][$label . '_array'] = $this->tmp_data['data'][$label];
										}
									}
									if( $row['input'] == 'upload' )
									{
										if( ! empty( $_FILES[$row['field']]['name'] ) )
										{
											$value                          = $this->save_file( $row, $_FILES[$row['field']] );
											$this->tmp_data['data'][$label] = $value;
										}
										else
										{
											$this->tmp_data['data'][$label] = '';
										}
									}
									if( $row['textup'] )
									{
										$value = strtoupper( $value );
									}
									elseif( $row['textlo'] )
									{
										$value = strtolower( $value );
									}
									if( $value !== '##nodata##' )
										$this->crud->crud_field( $row['field'], $value, $row['type'] );
								}
							}
						}
						if( $this->tmp_data['primary']['info'] )
						{
							$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
						}
						$this->crud->process_crud();
						$id_new = $this->crud->last_id();
					}
					else
					{
						$id_new = 1;
					}
				}

				$id = TRUE;
				if( $id_new > 0 )
				{
					if( method_exists( $this->router->fetch_class(), 'afterSave' ) )
					{
						$id = $this->afterSave( $id_new, $this->tmp_data['data'], [], 'add' );

						if( ! $id )
						{
							$this->db->trans_rollback();
							$this->logdata->type = 0;
							throw new Exception( 'Error: ' . $this->logdata->errors(), E_USER_ERROR );
							$id = FALSE;
						}
						else
						{
							$this->db->trans_commit();
							$this->logdata->save_log();
							$this->session->set_flashdata( 'message_crud', '1 Data berhasil ditambahkan' );
						}
					}
					else
					{
						$this->db->trans_commit();
						$this->logdata->save_log();
						$this->session->set_flashdata( 'message_crud', '1 Data berhasil ditambahkan' );
					}
				}
				else
				{
					$this->db->trans_rollback();
					throw new Exception( 'Error: ' . $this->logdata->errors(), E_USER_ERROR );
					$id = FALSE;
				}

				if( $id_new && $id )
				{
					unset( $_POST );
					$method_name = 'redirectUrl';
					if( $mode == 'Stay' )
					{
						if( method_exists( $this->router->fetch_class(), $method_name ) )
						{
							$url = $this->$method_name( base_url( $this->modul_name . '/edit/' . $id_new ), $id_new );
						}
						else
						{
							$url = base_url( $this->modul_name . '/edit/' . $id_new );
						}
					}
					else
					{
						switch( $this->modul_name )
						{
							case 'risk-context':
								$url = base_url( $this->modul_name . "/identifikasi-risiko/" . $id_new );
								break;

							default:
								$url = base_url( $this->modul_name );
								break;
						}
					}
					header( 'location:' . $url );
				}
				else
				{
					$this->template->_params = $this->configuration;
					$this->set_template();
					$content       = $this->_template_ . '/input';
					$tmp           = $this->tmp_data;
					$tmp['params'] = $this->configuration;
					if( method_exists( $this->router->fetch_class(), 'inputContent' ) )
					{
						$content = $this->inputContent( 'add', $tmp );
					}
					else
					{
						$content = $this->load->view( $content, $tmp, TRUE );
					}
					$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer ] );

					$this->template->publish();
				}
			}
		}
		else
		{
			$this->template->_params = $this->configuration;
			$this->set_template();
			$content       = $this->_template_ . '/input';
			$tmp           = $this->tmp_data;
			$tmp['params'] = $this->configuration;
			if( method_exists( $this->router->fetch_class(), 'inputContent' ) )
			{
				$content = $this->inputContent( 'add', $tmp );
			}
			else
			{
				$content = $this->load->view( $content, $tmp, TRUE );
			}
			$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer, 'info' => 'info add saja' ] );

			$this->template->publish();
		}
	}

	function setInfoFooter( $data = array() )
	{
		$content = '<span style="color:red;">*) Wajib diisi </span>';
		if( $this->tmp_data['primary']['info'] )
		{
			if( $data )
			{
				$content .= '<span class="pull-right">' . $this->lang->line( 'create_by' ) . $data['created_by'] . ' | ' . $this->lang->line( 'create_stamp' ) . $data['created_at'] . ' | ' . $this->lang->line( 'update_by' ) . $data['updated_by'] . ' | ' . $this->lang->line( 'update_stamp' ) . $data['updated_at'] . '</span>';
			}
		}
		return $content;
	}

	function save_file( $row = [], $data = [] )
	{
		$pt   = explode( '/', $row['path'] );
		$path = $pt[0] . '_path_relative';

		$this->load->library( 'image' );
		$this->image->set_Param( 'nm_file', $row['field'] );
		$this->image->set_Param( 'file_name', $data['name'] );
		$this->image->set_Param( 'path', $path( $pt[1] ) );
		$this->image->set_Param( 'thumb', $row['file_thumb'] );
		$this->image->set_Param( 'type', $row['file_type'] );
		$this->image->set_Param( 'size', $row['file_size'] );
		$this->image->set_Param( 'nm_random', $row['file_random'] );

		if( ! empty( $row['multi'] ) )
		{
			$this->image->set_Param( 'multi', $row['multi'] );
			$this->image->set_Param( 'image_no', $row['image_no'] );
		}

		$this->image->upload();
		return $pt[1] . '/' . $this->image->result( 'file_name' );
	}

	function __update( $id = '' )
	{


		$this->breadcrumbs->push( 'Edit', 'edit' );
		$manualSave = FALSE;
		$idEdit     = ( $id == '' ) ? $this->uri->segment( 3 ) : $id;
		if( $idEdit == '' )
		{
			header( 'location:' . base_url( $this->module_name ) );
		}

		$post = $this->input->post();

		$save = '';
		if( $post )
		{
			$save = $post['l_save'];
		}
		$this->configuration['_mode_'] = $this->_mode_;
		$data                          = $post;
		if( ! $data )
		{
			if( method_exists( $this->router->fetch_class(), 'inputManualData' ) )
			{
				$data = $this->inputManualData();
			}
			else
			{
				$this->crud->cParamsModul = $this->tmp_data;
				$this->crud->cId          = $idEdit;
				$this->crud->getQuery();
				$data = $this->crud->getOneData();
			}

			$old_data = $data;
		}
		else
		{
			if( method_exists( $this->router->fetch_class(), 'inputManualData' ) )
			{
				$old_data = $this->inputManualData();
			}
			else
			{
				$this->crud->cParamsModul = $this->tmp_data;
				$this->crud->cId          = $idEdit;
				$this->crud->getQuery();
				$old_data = $this->crud->getOneData();
			}
		}

		$infoFooter = $this->setInfoFooter( $old_data );
		$this->logdata->set_log( 'old_data', $old_data );
		$this->data_fields['data'] = $data;
		$this->set_input_box( $data, 'edit' );
		$header = '';
		$footer = '';
		$this->register_button( 'edit', $idEdit );
		$this->configuration['button'] = $this->_button;
		$this->configuration['fields'] = $this->tmp_data['fields'];
		$this->template->_params       = $this->configuration;

		if( method_exists( $this->router->fetch_class(), 'setContentHeader' ) )
		{
			$header = $this->setContentHeader( _MODE_ );
		}
		if( method_exists( $this->router->fetch_class(), 'setContentFooter' ) )
		{
			$footer = $this->setContentFooter( _MODE_ );
		}

		if( ! empty( $save ) )
		{
			if( isset( $data['Save'] ) )
				$mode = 'Stay';
			else
				$mode = 'Back';
			$sts_form_validation = $this->cek_form_validation( $this->tmp_data['fields'] );

			if( $sts_form_validation )
			{
				if( method_exists( $this->router->fetch_class(), 'checkBeforeSave' ) )
				{
					$sts_form_validation = $this->checkBeforeSave( $this->input->post(), $old_data, 'edit' );
				}
			}

			if( $sts_form_validation == FALSE )
			{
				$this->session->set_flashdata( 'message_crud_error', $this->logdata->errors_array() );
				$this->set_template();
				$content       = $this->_template_ . '/input';
				$tmp           = $this->tmp_data;
				$tmp['params'] = $this->configuration;
				if( method_exists( $this->router->fetch_class(), 'inputContent' ) )
				{
					$content = $this->inputContent( 'edit', $tmp );
				}
				else
				{
					$content = $this->load->view( $content, $tmp, TRUE );
				}
				$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer ] );

				$this->template->publish();
			}
			else
			{
				$this->tmp_data['data'] = $this->input->post();

				foreach( $this->tmp_data['fields'] as $field )
				{
					if( array_key_exists( 'show', $field ) )
					{
						if( $field['show'] || $field['save'] )
						{
							if( array_key_exists( 'alias', $field ) )
							{
								$label = $field['alias'];
							}
							else
							{
								$label = $field['field'];
							}
							$method_name = 'updateValue_' . strtoupper( $field['field'] );

							if( method_exists( $this->router->fetch_class(), $method_name ) )
							{
								$this->tmp_data['data'][$label] = $this->$method_name( $this->tmp_data['data'][$label], $this->tmp_data['data'], $old_data );
							}
						}
					}
				}
				$this->db->trans_begin();
				if( method_exists( $this->router->fetch_class(), 'manualSave' ) )
				{
					$id_new     = $this->manualSave( $idEdit, $this->tmp_data, $old_data, 'edit' );
					$manualSave = TRUE;
				}
				else
				{

					if( $this->_STS_UPDATE )
					{
						$this->crud->crud_table( $this->tbl_simpan );
						$this->crud->crud_type( 'edit' );
						foreach( $this->tmp_data['fields'] as $row )
						{
							if( array_key_exists( 'show', $row ) )
							{
								if( ( ( $row['show'] && $row['save'] ) || ( ! $row['show'] && $row['save'] ) ) && $row['type'] !== 'free' )
								{
									$label = $row['field'];
									if( array_key_exists( 'alias', $row ) )
									{
										$label = $row['alias'];
									}
									$value = '##nodata##';
									if( array_key_exists( $label, $this->tmp_data['data'] ) )
									{
										$value = $this->tmp_data['data'][$label];
										if( is_array( $this->tmp_data['data'][$label] ) )
										{
											if( $row['json'] )
											{
												$value = json_encode( $this->tmp_data['data'][$label] );
											}
											else
											{
												$value = implode( ',', $this->tmp_data['data'][$label] );
											}

											$this->tmp_data['data'][$label . '_array'] = $this->tmp_data['data'][$label];
											$this->tmp_data['data'][$label]            = $value;
										}
									}
									if( $row['input'] == 'upload' )
									{
										if( ! empty( $_FILES[$row['field']]['name'] ) )
										{
											$value                          = $this->save_file( $row, $_FILES[$row['field']] );
											$this->tmp_data['data'][$label] = $value;
										}
										else
										{
											$this->tmp_data['data'][$label] = '';
										}
									}
									if( $row['textup'] )
									{
										$value = strtoupper( $value );
									}
									elseif( $row['textlo'] )
									{
										$value = strtolower( $value );
									}
									if( $value !== '##nodata##' )
										$this->crud->crud_field( $row['field'], $value, $row['type'] );
								}
							}
						}
						if( $this->tmp_data['primary']['info'] )
						{

							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->crud_field( 'updated_at', date( "Y-m-d H:i:s" ) );
						}
						$this->crud->crud_where( [ 'field' => $this->tmp_data['primary']['id'], 'value' => $idEdit, 'op' => '=' ] );
						$this->crud->process_crud();
						$id_new = $idEdit;
					}
					else
					{
						$id_new = 1;
					}
				}

				$id = TRUE;
				if( $id_new > 0 )
				{
					if( method_exists( $this->router->fetch_class(), 'afterSave' ) )
					{
						$id = $this->afterSave( $id_new, $this->tmp_data['data'], $old_data, 'edit' );
						if( ! $id )
						{
							$this->db->trans_rollback();
							$this->logdata->type = 0;
							throw new Exception( 'Error: ' . $this->logdata->errors(), E_USER_ERROR );
							$id = FALSE;
						}
						else
						{
							$this->db->trans_commit();
							$this->logdata->save_log();
						}
					}
					else
					{
						$this->db->trans_commit();
						$this->logdata->save_log();
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->logdata->type = 0;
					throw new Exception( 'Error: ' . $this->logdata->errors(), E_USER_ERROR );
					$id = FALSE;
				}

				if( $id_new && $id )
				{
					unset( $_POST );
					$method_name = 'redirectUrl';
					if( $manualSave )
					{
						$url = base_url( $this->modul_name );
					}
					else
					{
						if( $mode == 'Stay' )
						{
							if( method_exists( $this->router->fetch_class(), $method_name ) )
							{
								$url = $this->$method_name( base_url( $this->modul_name . '/edit/' . $idEdit ), $idEdit );
							}
							else
							{
								$url = base_url( $this->modul_name . '/edit/' . $idEdit );
							}
						}
						else
						{
							switch( $this->modul_name )
							{
								case 'risk-context':
									$url = base_url( $this->modul_name . "/identifikasi-risiko/" . $idEdit );
									break;

								default:
									$url = base_url( $this->modul_name );
									break;
							}
						}
					}
					$this->session->set_flashdata( 'message_crud', 'Data berhasil diupdate' );
					header( 'location:' . $url );
				}
				else
				{
					$this->session->set_flashdata( 'message_crud_error', $this->logdata->errors_array() );
					$this->set_template();
					$content       = $this->_template_ . '/input';
					$tmp           = $this->tmp_data;
					$tmp['params'] = $this->configuration;
					if( method_exists( $this->router->fetch_class(), 'inputContent' ) )
					{
						$content = $this->content( 'add', $tmp );
					}
					else
					{
						$content = $this->load->view( $content, $tmp, TRUE );
					}
					$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer ] );

					$this->template->publish();
				}
			}
		}
		else
		{
			$this->template->_params = $this->configuration;
			$this->set_template();
			$content       = $this->_template_ . '/input';
			$tmp           = $this->tmp_data;
			$tmp['params'] = $this->configuration;
			if( $data )
			{
				if( method_exists( $this->router->fetch_class(), 'inputContent' ) )
				{
					$content = $this->inputContent( 'add', $tmp );
				}
				else
				{
					$content = $this->load->view( $content, $tmp, TRUE );
				}
			}
			else
			{
				$content = $this->load->view( 'template/nodata', [], TRUE );
			}

			$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration, 'header' => $header, 'footer' => $footer, 'info' => $infoFooter ] );

			$this->template->publish();
		}
	}

	function __delete()
	{
		$idx  = intval( $this->uri->segment( 3 ) );
		$id[] = $idx;
		$this->proses_delete( $id );
		header( 'location:' . base_url( $this->modul_name ) );
	}

	function __delete_all()
	{
		$id = $this->input->post( 'id' );
		$this->proses_delete( $id );
		header( 'Content-type: application/json' );
		echo json_encode( [ 'status' => TRUE ] );
	}

	function del_Child()
	{
		$result    = array( 'sts' => 1, "ket" => "Sukses Delete Data" );
		$tbl       = $this->input->post( 'tbl' );
		$id        = $this->input->post( 'iddel' );
		$nm_method = "afterSubDelete";

		$status = TRUE;
		$this->db->trans_begin();
		$this->crud->crud_table( $tbl );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'op' => 'in', 'field' => 'id', 'value' => $id ] );
		$this->crud->process_crud();
		if( method_exists( $this->router->fetch_class(), $nm_method ) )
		{
			$status = $this->$nm_method( $id );
		}
		if( $status )
		{
			$this->db->trans_commit();
			$this->logdata->save_log();
		}
		else
		{
			$this->db->trans_rollback();
			$this->logdata->set_message( 'data gagal dihapus !!', TRUE );
			$result = array( 'sts' => 0, "ket" => "gagal proses" );
		}

		$this->session->set_flashdata( 'message_crud', $this->logdata->messages() );
		$this->logdata->clear_log();
		$this->logdata->clear_messages();
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function proses_delete( $id = [] )
	{
		$status = TRUE;
		$this->db->trans_begin();
		$this->crud->crud_table( $this->tbl_simpan );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'op' => 'in', 'field' => $this->tmp_data['primary']['id'], 'value' => $id ] );
		$this->crud->process_crud();
		if( method_exists( $this->router->fetch_class(), 'afterDelete' ) )
		{
			$status = $this->afterDelete( $id );
		}
		if( $status )
		{
			$this->db->trans_commit();
			$this->logdata->save_log();
		}
		else
		{
			$this->db->trans_rollback();
			$this->logdata->set_message( 'data gagal dihapus !!', TRUE );
		}

		$this->session->set_flashdata( 'message_crud', $this->logdata->messages() );
		$this->logdata->clear_log();
		$this->logdata->clear_messages();
	}

	function list_data()
	{
		$this->register_button( 'list' );
		$get                      = $this->input->get();
		$this->crud->cParamsData  = $get;
		$this->crud->cParamsModul = $this->tmp_data;

		if( method_exists( $this->router->fetch_class(), 'searchBox_VALUE' ) )
		{
			$this->post = $this->searchBox_VALUE( $this->post );
		}

		$this->crud->cPost = $this->post;
		$this->crud->getQuery();
		$rows                  = $this->crud->getAllData();
		$this->crud->iStsLimit = FALSE;
		$this->crud->getQuery();
		$countRowNoLimit = $this->crud->getCountData();
		$countRow        = $this->crud->getCountAllData();
		$datas           = [];
		$no              = 0;
		if( ! method_exists( $this->router->fetch_class(), 'printAction' ) )
		{
			unset( $this->_button['one']['print'] );
		}

		if( method_exists( $this->router->fetch_class(), 'customData' ) )
		{
			$rows = $this->customData( $rows );
		}

		$id     = $this->tmp_data['primary']['id'];
		$arr_id = array();
		foreach( $rows as $row )
		{
			if( isset( $row[$id] ) )
				$arr_id[] = $row[$id];
		}

		if( method_exists( $this->router->fetch_class(), 'MASTER_DATA_LIST' ) )
		{
			$this->MASTER_DATA_LIST( $arr_id, $rows );
		}
		$config = $this->configuration;
		foreach( $rows as $row )
		{
			$btn_one = $this->_button['one'];
			if( method_exists( $this->router->fetch_class(), 'optionalPersonalButton' ) )
			{
				$btn_one = $this->optionalPersonalButton( $this->_button['one'], $row );
			}

			$data = [];
			if( isset( $btn_one['delete'] ) )
			{
				$data[] = '<div class="text-center"><input type="checkbox" class="form-check-input pointer text-center" name="chk_list[]" style="padding:0;margin:0;" value="' . $row[$id] . '"/></div>';
			}
			else
			{
				$data[] = '';
			}
			$data[] = '<div class="text-center">' . ++$no . '</div>';
			foreach( $this->tmp_data['title'] as $tit )
			{
				$mod = 'listBox_' . strtoupper( $tit[1] );
				$isi = '';
				if( $this->tmp_data['fields'][$tit[1]]['type'] !== 'free' )
					$isi = $row[$tit[1]];
				if( method_exists( $this->router->fetch_class(), $mod ) )
				{
					$isi = $this->$mod( $this->tmp_data['fields'][$tit[1]], $row, $isi );
				}
				else
				{
					$x = '';
					$y = '';
					switch( $this->tmp_data['fields'][$tit[1]]['input'] )
					{
						case 'boolean':
						case 'bool:switch':
							$x = function ($fields, $tit, $isi)
							{
								$content = '<span class="badge badge-danger">' . $this->lang->line( 'cbo_no' ) . '</span>';
								if( $fields['list'] == 'publish' )
								{
									$content = '<span class="badge badge-danger">' . $this->lang->line( 'cbo_nopublish' ) . '</span>';
								}
								if( $isi == 1 )
								{
									$content = '<span class="badge badge-primary"> ' . $this->lang->line( 'cbo_yes' ) . ' </span>';
									if( $fields['list'] == 'publish' )
									{
										$content = '<span class="badge badge-primary"> ' . $this->lang->line( 'cbo_publish' ) . ' </span>';
									}
								}
								return $content;
							};
							break;
						case 'combo':
							$x = function ($fields, $tit, $isi)
							{
								if( array_key_exists( $isi, $fields['values'] ) )
								{
									$isi = str_replace( '&nbsp;', '', $fields['values'][$isi] );
									$isi = str_replace( $this->lang->line( 'cbo_select_parent' ) ?? '', '', $isi );
									$isi = str_replace( $this->lang->line( 'cbo_select' ) ?? '', '', $isi );
								}
								return $isi;
							};
							break;

						case 'upload':
							$y = function ($fields, $tit, $isi)
							{
								$pt     = explode( '/', $fields['path'] );
								$result = '';
								if( ! empty( $isi ) )
								{
									$class = [ 'class' => 'rounded-circle detail-img pointer', 'data-file' => $isi, 'data-path' => $pt[0] ];

									$thumb = '';
									if( $fields['file_thumb'] )
									{
										$thumb = 'tiny';
									}
									else
									{
										$class['width'] = '50';
									}
									$result = img( $isi, $pt[0], $class, $thumb );
								}
								return $result;
							};
							break;
					}
					if( $x )
					{
						$isi_arrs = ( ! is_null( $isi ) ) ? explode( ',', $isi ) : "";
						$isi_tmp  = [];
						if( is_array( $isi_arrs ) )
						{
							foreach( $isi_arrs as $isi_arr )
							{
								$isi_tmp[] = call_user_func_array( $x, [ $this->tmp_data['fields'][$tit[1]], $tit, $isi_arr ] );
							}
						}

						$isi = implode( ', ', $isi_tmp );
					}
					if( $y )
					{
						$isi = call_user_func_array( $y, [ $this->tmp_data['fields'][$tit[1]], $tit, $isi ] );
					}
				}
				if( $tit[4] !== 'left' )
					$isi = '<div class="text-' . $tit[4] . '">' . $isi . '</div>';
				$data[] = $isi;
			}

			$action = [];
			if( $config['type_action_button'] == 'drop' )
			{
				foreach( $btn_one as $btn )
				{
					$attr = '';
					if( array_key_exists( 'attr', $btn ) )
					{
						if( is_array( $btn['attr'] ) )
						{
							foreach( $btn['attr'] as $keAt => $at )
							{
								$attr .= $keAt . '="' . $at . '" ';
							}
						}
						else
						{
							$attr .= $btn['attr'];
						}
					}

					if( isset( $btn['id'] ) )
					{
						$attr .= 'id="' . $btn['id'] . '"';
					}

					$type = 'a';
					$url  = '';
					if( array_key_exists( 'type', $btn ) )
					{
						$type = $btn['type'];
					}
					if( array_key_exists( 'url', $btn ) )
					{
						$url = 'href="' . $btn['url'] . $row[$id] . '"';
					}
					$class    = ( array_key_exists( 'class', $btn ) ) ? $btn['class'] : '';
					$action[] = '<' . $type . ' class="dropdown-item ' . $class . '" ' . $url . ' ' . $attr . '><i class="' . $btn['icon'] . '" ></i> ' . $btn['label'] . '</' . $type . '>';
				}
				$data[] = '
					<div class="text-center">
						<div class="list-icons">
							<div class="dropdown">
								<a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
									<i class="icon-menu9"></i>
								</a>

								<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(22px, 19px, 0px);">' .
				 implode( '', $action )
				 . '</div>
							</div>
						</div>
					</div>
				';
			}
			else
			{
				foreach( $btn_one as $btn )
				{
					$class    = ( array_key_exists( 'class', $btn ) ) ? $btn['class'] : '';
					$action[] = '<a class="' . $class . '"  href="' . $btn['url'] . $row[$id] . '"><i class="' . $btn['icon'] . '" data-popup="tooltip" data-html="true" data-title="' . $btn['label'] . '" title="' . $btn['label'] . '"></i></a>';
				}
				$data[] = implode( ' | ', $action );
			}
			$datas[] = $data;
		}
		$data = [
		 "draw"            => intval( @$_GET['draw'] ),
		 "recordsTotal"    => $countRow,
		 "recordsFiltered" => $countRowNoLimit,
		 "data"            => $datas,
		];
		header( 'Content-type: application/json' );
		echo json_encode( $data );
	}

	public static function stringFromColumnIndex( $columnIndex )
	{
		static $indexCache = [];
		if( ! isset( $indexCache[$columnIndex] ) )
		{
			$indexValue = $columnIndex;
			$base26     = NULL;
			do
			{
				$characterValue = ( $indexValue % 26 ) ?: 26;
				$indexValue     = ( $indexValue - $characterValue ) / 26;
				$base26         = chr( $characterValue + 64 ) . ( $base26 ?: '' );
			} while( $indexValue > 0 );
			$indexCache[$columnIndex] = $base26;
		}
		return $indexCache[$columnIndex];
	}

	function __export_all( $mode = 'excel' )
	{
		$rows  = $this->session->userdata( 'list_data_all' );
		$title = $this->tmp_data['title'];
		if( method_exists( $this->router->fetch_class(), 'printAllAction' ) )
		{
			$this->printAllAction( $rows, $title, $mode );
		}
		else
		{
			if( $mode == 'excel' )
			{
				$spreadsheet = new Spreadsheet();

				// Set document properties
				$spreadsheet->getProperties()->setCreator( 'Andoyo - Java Web Media' )
				 ->setLastModifiedBy( 'Andoyo - Java Web Medi' )
				 ->setTitle( 'Office 2007 XLSX Test Document' )
				 ->setSubject( 'Office 2007 XLSX Test Document' )
				 ->setDescription( 'Test document for Office 2007 XLSX, generated using PHP classes.' )
				 ->setKeywords( 'office 2007 openxml php' )
				 ->setCategory( 'Test result file' );

				// Add some data
				$spreadsheet->setActiveSheetIndex( 0 );
				$sheet = $spreadsheet->setActiveSheetIndex( 0 );
				$i     = 1;
				$col   = 0;
				foreach( $title as $tit )
				{
					if( $this->tmp_data['fields'][$tit[1]]['type'] !== 'free' && $tit[5] )
					{
						$sheet->setCellValue( $this->stringFromColumnIndex( ++$col ) . $i, $tit[2] );
					}
				}

				// Miscellaneous glyphs, UTF-8
				$i  = 2;
				$no = 0;
				foreach( $rows as $row )
				{
					$col = 0;
					foreach( $title as $tit )
					{
						if( $this->tmp_data['fields'][$tit[1]]['type'] !== 'free' && $tit[5] )
						{
							if( $this->tmp_data['fields'][$tit[1]]['input'] == 'combo' )
							{
								$x   = function ($fields, $tit, $isi)
								{
									if( array_key_exists( $isi, $fields['values'] ) )
									{
										$isi = str_replace( '&nbsp;', '', $fields['values'][$isi] );
										$isi = str_replace( $this->lang->line( 'cbo_select_parent' ) ?? '', '', $isi );
										$isi = str_replace( $this->lang->line( 'cbo_select' ) ?? '', '', $isi );
									}
									return $isi;
								};
								$isi = call_user_func_array( $x, [ $this->tmp_data['fields'][$tit[1]], $tit, $row[$tit[1]] ] );
							}
							else
							{
								$isi = $row[$tit[1]];
							}
							$sheet->setCellValue( $this->stringFromColumnIndex( ++$col ) . $i, $isi );
						}
					}
					$i++;
				}

				// Rename worksheet
				$spreadsheet->getActiveSheet()->setTitle( 'Report Excel ' . date( 'd-m-Y H' ) );

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				// Redirect output to a client’s web browser (Xlsx)
				header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
				header( 'Content-Disposition: attachment;filename="Report Excel.xlsx"' );
				header( 'Cache-Control: max-age=0' );
				// If you're serving to IE 9, then the following may be needed
				header( 'Cache-Control: max-age=1' );

				// If you're serving to IE over SSL, then the following may be needed
				header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
				header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); // always modified
				header( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
				header( 'Pragma: public' ); // HTTP/1.0

				$writer = IOFactory::createWriter( $spreadsheet, 'Xlsx' );
				$writer->save( 'php://output' );
			}
			else
			{
				$this->load->library( 'pdf' );

				$html = '<div class="page page-first">
					<table width="100%" class="header"><tr>';
				$html .= '<td>' . $this->preference['nama_kantor'] . '</td></tr>';
				$html .= '<tr><td>' . $this->preference['alamat_kantor'] . '</td></tr>';
				$html .= '<tr><td>Telp: ' . $this->preference['telp_kantor'] . ' email: ' . $this->preference['email_kantor'] . '</td></tr></table><br/><br/><br/>';

				$html .= '<table border="1" cellPadding="2" cellspacing="0" width="100%">';
				$html .= '<thead>';
				$html .= '<tr><th>No</th>';
				foreach( $title as $tit )
				{
					if( $this->tmp_data['fields'][$tit[1]]['type'] !== 'free' && $tit[5] )
					{
						$html .= '<th>' . $tit[2] . '</th>';
					}
				}
				$html .= '</tr></thead>';
				$html .= '<tbody>';
				$no   = 0;
				foreach( $rows as $row )
				{
					$html .= '<tr><td>' . ++$no . '</td>';
					foreach( $title as $tit )
					{
						if( $this->tmp_data['fields'][$tit[1]]['type'] !== 'free' && $tit[5] )
						{
							if( $this->tmp_data['fields'][$tit[1]]['input'] == 'combo' )
							{
								$x   = function ($fields, $tit, $isi)
								{
									if( array_key_exists( $isi, $fields['values'] ) )
									{
										$isi = str_replace( '&nbsp;', '', $fields['values'][$isi] );
										$isi = str_replace( $this->lang->line( 'cbo_select_parent' ) ?? '', '', $isi );
										$isi = str_replace( $this->lang->line( 'cbo_select' ) ?? '', '', $isi );
									}
									return $isi;
								};
								$isi = call_user_func_array( $x, [ $this->tmp_data['fields'][$tit[1]], $tit, $row[$tit[1]] ] );
							}
							else
							{
								$isi = $row[$tit[1]];
							}
							$html .= '<td>' . $isi . '</td>';
						}
					}
					$html .= '</tr>';
				}
				$html .= '</tbody>';
				$html .= '</table>';
				$this->pdf->setPaper( 'A4', 'potrait' );
				$this->pdf->filename = "laporan-" . _MODULE_NAME_ . ".pdf";
				$this->pdf->load( $html );
			}
		}
		exit;
	}

	function __print( $id = 0 )
	{
		if( method_exists( $this->router->fetch_class(), 'printAction' ) && $id > 0 )
		{
			$data  = $this->db->where( 'id', $id )->get( $this->tbl_master )->row_array();
			$title = $this->tmp_data['title'];
			$this->printAction( $data, $title, $id );
		}
		else
		{
			header( 'location:' . base_url( _MODULE_NAME_ ) );
		}
	}

	function cek_form_validation( $data = array() )
	{
		$valid = FALSE;
		$rules = array( 'required', 'matches', 'is_unique', 'min_length', 'max_length', 'exact_length', 'greater_than', 'less_than', 'alpha', 'alpha_numeric', 'alpha_dash', 'numeric', 'integer', 'decimal', 'is_natural', 'is_natural_no_zero', 'valid_email', 'valid_emails', 'valid_ip', 'valid_base64', 'xss_clean', 'prep_for_form', 'prep_url', 'strip_image_tags', 'encode_php_tags' );

		foreach( $rules as $rule )
		{
			$lang = lang( "form_validation_" . $rule );
			if( ! empty( $lang ) )
			{
				$this->form_validation->set_message( $rule, $lang );
			}
		}

		foreach( $data as $key => $row )
		{
			$ada = FALSE;
			if( array_key_exists( 'show', $row ) )
			{
				if( $row['show'] )
				{
					if( array_key_exists( 'required', $row ) )
					{
						$msg_title = lang( 'fld_' . $row['field'] );
						if( empty( $msg_title ) )
						{
							$msg_title = $row['title'];
						}

						if( array_key_exists( 'alias', $row ) )
							$label = $row['alias'];
						else
							$label = $row['field'];

						if( $row['required'] )
						{
							if( array_key_exists( 'rule', $row ) )
							{
								$rule = 'required';
								if( isset( $row['rule'] ) )
								{
									$rule = $row['rule'];
								}
							}
							else
							{
								$rule = 'required';
							}

							$valid = TRUE;
							$ada   = TRUE;
						}
						elseif( array_key_exists( 'rule', $row ) )
						{

							$rule  = $row['rule'];
							$valid = TRUE;
							$ada   = TRUE;
						}
						if( $ada )
						{
							$this->form_validation->set_rules( $label, $msg_title, $rule );
						}
						$ada  = FALSE;
						$rule = '';
					}
				}
			}
		}

		if( $valid )
		{
			$proses = $this->form_validation->run();
			return $proses;
		}
		else
		{
			return TRUE;
		}
	}

	function get_combo_parent_dept( $filter = TRUE )
	{
		$data                = $this->get_parent_data_dept( $filter );
		$this->output_parent = array( '' => $this->lang->line( 'cbo_select_parent' ) );
		foreach( $data as $row )
		{
			$this->buildItem_parent_dept( $row );
		}
		return $this->output_parent;
	}

	function buildItem_parent_dept( $ad, $level = 0 )
	{

		$space = str_repeat( '&nbsp;', $level * 6 );
		$code  = '';
		if( ! empty( $ad['code'] ) )
		{
			$code = ' - ' . $ad['code'];
		}
		$this->output_parent[$ad['id']] = $space . $ad['title'] . $code;
		if( array_key_exists( 'children', $ad ) )
		{
			++$level;
			foreach( $ad['children'] as $row )
			{
				if( $level > 3 && $this->modul_name == "risk-context" )
				{
					continue;
				}
				$this->buildItem_parent_dept( $row, $level );
			}
		}
		$level = 0;
	}

	function get_parent_data_dept( $filter )
	{
		$parent = 0;
		if( $filter )
		{
			if( $this->_data_user_['group']['param']['privilege_owner'] >= 2 )
			{
				if( $this->_data_user_['owner'] )
				{
					$this->db->where_in( 'id', $this->_data_user_['owner'] );
					$parent = $this->_data_user_['owner_pid'];
				}
			}
		}

		$this->db->select( '*' );
		$this->db->from( _TBL_OWNER );
		$this->db->order_by( 'urut' );
		$query = $this->db->get();
		$rows  = $query->result_array();
		$input = [];
		foreach( $rows as $row )
		{
			$input[] = array( "id" => $row['id'], "title" => $row['owner_name'], "code" => $row['owner_code'], "slug" => $row['pid'], "urut" => $row['urut'], "active" => $row['active'] );
		}

		$result = _tree( $input, $parent );
		return $result;
	}

	function get_data_dept()
	{
		$this->db->select( '*' );
		$this->db->from( _TBL_OWNER );
		$this->db->order_by( 'urut' );
		$query = $this->db->get();
		$rows  = $query->result_array();
		$input = [];
		foreach( $rows as $row )
		{
			$input[$row['id']] = array( "id" => $row['id'], "title" => $row['owner_name'], "code" => $row['owner_code'], "slug" => $row['pid'], "urut" => $row['urut'], "active" => $row['active'] );
		}
		return $input;
	}

	function set_Map( $sts = TRUE )
	{
		if( $sts )
		{
			$this->load->library( 'googlemaps' );
			$this->addField( [ 'field' => 'lat', 'size' => 30, 'readonly' => TRUE ] );
			$this->addField( [ 'field' => 'lng', 'size' => 30, 'readonly' => TRUE ] );
			$this->addField( [ 'field' => 'peta', 'type' => 'free', 'input' => 'free', 'mode' => 'a', 'size' => 20 ] );
		}
	}

	function inputBox_PETA( $mode, $field, $row, $value )
	{
		$result = $this->data_peta( $row );
		return $result;
	}

	function data_peta( $data = array() )
	{
		$ada            = FALSE;
		$config['zoom'] = '5';
		if( count( $data ) == 0 )
		{
			$config['center'] = '-1.362176,117.817383';
		}
		elseif( floatval( $data['lat'] ) == 0 || floatval( $data['lng'] ) == 0 )
		{
			$config['center'] = '-1.362176,117.817383';
		}
		else
		{
			$ada              = TRUE;
			$config['center'] = $data['lat'] . ',' . $data['lng'];
			$config['zoom']   = '13';
		}

		$config['sensor']                      = TRUE;
		$config['apiKey']                      = 'AIzaSyD-9SGqwku28lZF3C9wwwaD0JK2pUXPVwo';
		$config['disableMapTypeControl']       = TRUE;
		$config['disableNavigationControl']    = TRUE;
		$config['disableScaleControl']         = TRUE;
		$config['disableStreetViewControl']    = TRUE;
		$config['places']                      = TRUE;
		$config['placesAutocompleteInputID']   = 'map_tag';
		$config['placesAutocompleteBoundsMap'] = TRUE; // set results biased towards the maps viewport
		$config['placesAutocompleteOnChange']  = "var place = placesAutocomplete.getPlace();
             if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
             } else {
                 map.setCenter(place.geometry.location);
                 map.setZoom(17);
             }";
		$config['onclick']                     = 'clearOverlays();createMarker_map({ map: map, position:event.latLng });get_position_map(event.latLng.lat() , event.latLng.lng(),event.name);';
		$this->googlemaps->initialize( $config );

		if( $ada )
		{
			$marker             = array();
			$marker['position'] = $data['lat'] . ',' . $data['lng'];
			$this->googlemaps->add_marker( $marker );
		}
		$data['map'] = $this->googlemaps->create_map();

		$data['view_map'] = $this->load->view( 'map', $data, TRUE );
		return $data['view_map'];
	}

	function listBox_MAP( $field, $rows, $value )
	{
		if( ! empty( $rows['lat'] ) )
			$value = '<a href="https://www.google.com/maps/?q=' . $rows['lat'] . ',' . $rows['lng'] . '" target="_blank"><i class="icon-map4 pointer detail-mapx" data-lat="' . $rows['lat'] . '" data-lng="' . $rows['lng'] . '"></i></a>';
		return $value;
	}

	function listBox_CREATED_AT( $fields, $rows, $value )
	{
		$content = '';
		if( ! empty( $value ) )
		{
			$content = time_ago( $value );
			$value   = date( 'l, d M Y H:i:s', strtotime( $value ) );
		}
		$content = '<span data-toggle="tooltip" title="' . $value . '" data-original-title="' . $value . '"> ' . $content . '</span>';
		return $content;
	}

	function css( $mode = [] )
	{
		$path = '';
		if( $this->configuration['themes_mode'] !== 'default' )
		{
			$path = $this->configuration['themes_mode'] . '/';
		}
		$this->_css_[] = 'icons/icomoon/styles.css';
		$this->_css_[] = 'icons/fontawesome/styles.min.css';
		$this->_css_[] = 'bootstrap.min.css';
		$this->_css_[] = $path . 'bootstrap_limitless.min.css';
		$this->_css_[] = $path . 'layout.min.css';
		$this->_css_[] = $path . 'components.min.css';
		$this->_css_[] = 'colors.min.css';
		// $this->_css_[] = 'datatable/datatables.min.css';
		// $this->_css_[] = 'datatable/dataTables.bootstrap4.min.css';
		// $this->_css_[] = 'datatable/fixedColumns.bootstrap4.min.css';
		// $this->_css_[] = 'datatable/fixedHeader.bootstrap4.min.css';
		// $this->_css_[] = 'datatable/buttons.bootstrap4.min.css';
		// $this->_css_[] = 'datatable/responsive.bootstrap4.min.css';
		$this->_css_[] = 'summernote.css';
		$this->_css_[] = 'plugins/editors/trumbowyg/ui/trumbowyg.min.css';
		$this->_css_[] = 'basic.min.css';
		$this->_css_[] = 'style.css?v=2';
	}

	function js( $mode = [] )
	{
		$this->_js_[] = 'main/jquery.min.js';
		$this->_js_[] = 'main/bootstrap.bundle.min.js';
		$this->_js_[] = 'plugins/loaders/blockui.min.js';
		// $this->_js_[] = 'plugins/visualization/d3/d3.min.js';
		// $this->_js_[] = 'plugins/visualization/d3/d3_tooltip.js';
		$this->_js_[] = 'plugins/forms/styling/switchery.min.js';
		$this->_js_[] = 'plugins/forms/selects/bootstrap_multiselect.js';
		$this->_js_[] = 'plugins/ui/moment/moment.min.js';
		// $this->_js_[] = 'plugins/pickers/daterangepicker.js';
		$this->_js_[] = 'plugins/extensions/jquery_ui/interactions.min.js';
		$this->_js_[] = 'plugins/forms/tags/tagsinput.min.js';
		$this->_js_[] = 'plugins/forms/tags/tokenfield.min.js';
		$this->_js_[] = 'plugins/forms/styling/uniform.min.js';
		$this->_js_[] = 'plugins/forms/styling/switch.min.js';
		$this->_js_[] = 'plugins/forms/selects/select2.min.js';
		// $this->_js_[] = 'plugins/pickers/anytime.min.js';


		$this->_js_[] = 'plugins/ui/moment/moment.min.js';
		$this->_js_[] = 'plugins/pickers/pickadate/picker.js';
		$this->_js_[] = 'plugins/pickers/pickadate/picker.date.js';
		$this->_js_[] = 'plugins/pickers/pickadate/picker.time.js';
		$this->_js_[] = 'plugins/pickers/pickadate/legacy.js';
		$this->_js_[] = 'plugins/notifications/jgrowl.min.js';
		$this->_js_[] = 'plugins/notifications/noty.min.js';
		$this->_js_[] = 'plugins/editors/summernote/summernote.min.js';
		$this->_js_[] = 'plugins/editors/trumbowyg/trumbowyg.min.js';
		$this->_js_[] = 'plugins/editors/trumbowyg/plugins/pasteembed/trumbowyg.pasteembed.js';

		$this->_js_[] = 'plugins/datatable/datatables.min.js';
		$this->_js_[] = 'plugins/datatable/fixed_columns.min.js';
		$this->_js_[] = 'plugins/datatable/fixed_header.min.js';
		$this->_js_[] = 'plugins/datatable/responsive.min.js';
		$this->_js_[] = 'plugins/echarts/echarts.min.js';
		$this->_js_[] = 'plugins/dropzone/min/dropzone.min.js';
		$this->_js_[] = 'plugins/forms/validation/validate_1_19_5.min.js';
		$this->_js_[] = 'jquery.doubleScroll.js';
		$this->_js_[] = 'jquery.number.min.js';
		$this->_js_[] = 'js.cookie.js';
		$this->_js_[] = 'app.js';
		$this->_js_[] = 'custom.js';

		$arr_js = [ $this->modul_name, 'bersama' ];
		foreach( $arr_js as $js )
		{
			if( file_exists( FCPATH . 'assets/js/pages/' . $js . ".js" ) )
			{
				$this->_js_[] = 'pages/' . $js . '.js';//?ver=51
			}
		}
	}

	function _set_Where_Owner( $param = [] )
	{
		if( ! isset( $param['tbl'] ) )
		{
			$tbl = $this->tbl_master;
		}
		else
		{
			$tbl = $param['tbl'];
		}
		if( ! isset( $param['field'] ) )
		{
			$field = 'owner_id';
		}
		else
		{
			$field = $param['field'];
		}

		$op = FALSE;
		if( $this->_data_user_['group']['param']['privilege_owner'] >= 2 )
		{
			$op = TRUE;
		}
		if( $op )
		{
			$this->set_Where_Table( [ 'tbl' => $tbl, 'field' => $field, 'op' => 'in', 'value' => $this->_data_user_['owner'] ] );
		}
	}

	function switchLang( $language = "" )
	{
		$language = ( $language != "" ) ? $language : "english";
		$this->session->set_userdata( 'site_lang', $language );
		redirect( $_SERVER['HTTP_REFERER'] );
	}

	function _multi_language( $fields = [], $target = 'param_lang' )
	{
		$this->multi_lang_value = $fields;
		$lang                   = $this->db->where( 'is_default', 0 )->get( _TBL_BAHASA )->result();
		$this->set_Open_Tab( 'Other Language' );
		$this->addField( [ 'field' => $target, 'show' => FALSE, 'save' => TRUE ] );
		foreach( $lang as $lg )
		{
			foreach( $fields as $key => $row )
			{
				$tmp             = $this->tmp_data['fields'][$row];
				$tmp['field']    = $row . '_' . $lg->key;
				$tmp['title']    = $this->tmp_data['fields'][$row]['title'];
				$tmp['type']     = 'free';
				$tmp['search']   = FALSE;
				$tmp['required'] = FALSE;
				$isi             = '';
				$tmp['inputBox'] = function ($mode, $field, $row, $value)
				{
					$isi = [];
					if( $row )
					{
						$isi = json_decode( $row['param_lang'], TRUE );
					}
					$nm  = explode( '_', $field['field'] );
					$nm1 = $nm[count( $nm ) - 1];
					$nms = [];
					for( $x = 0; $x < count( $nm ) - 1; ++$x )
					{
						$nms[] = $nm[$x];
					}
					$nm2 = implode( '_', $nms );
					if( is_array( $isi ) )
					{
						if( array_key_exists( $nm1, $isi ) )
						{
							if( array_key_exists( $nm2, $isi[$nm1] ) )
							{
								$isi = $isi[$nm1][$nm2];
							}
							else
							{
								$isi = '';
							}
						}
						else
						{
							$isi = '';
						}
					}
					else
					{
						$isi = '';
					}
					$content = $this->set_box_input( $field, $isi );
					return $content;
				};

				if( $key == 0 )
				{
					$tmp['line']      = TRUE;
					$tmp['line-text'] = $lg->title;
					$tmp['line-icon'] = 'icon-image';
				}
				$this->addField( $tmp );
			}
		}
		$this->set_Close_Tab();
	}

	function _meta_seo( $target = 'param_meta' )
	{
		$cboRobot = $this->crud->combo_value( [ 'index' => 'index', 'follow' => 'follow', 'Noindex' => 'Noindex', 'Nofollow' => 'Nofollow', 'Noarchive' => 'Noarchive', 'Nosnippet' => 'Nosnippet', 'None' => 'None', 'NOODP' => 'NOODP', 'NOYDIR' => 'NOYDIR' ] )->noSelect()->result_combo();
		$this->set_Open_Tab( 'Meta SEO' );
		$this->addField( [ 'field' => $target, 'show' => FALSE, 'save' => TRUE ] );
		$this->addField( [ 'field' => 'meta_description', 'type' => 'free', 'default' => $this->configuration['meta_description'], 'input' => 'multitext', 'size' => 300, 'line' => TRUE, 'line-text' => 'General Meta', 'line-icon' => 'icon-users' ] );
		$this->addField( [ 'field' => 'meta_keywords', 'type' => 'free', 'default' => $this->configuration['meta_keywords'], 'max' => 250 ] );
		$this->addField( [ 'field' => 'meta_author', 'type' => 'free', 'default' => $this->configuration['meta_author'], 'max' => 250 ] );
		$this->addField( [ 'field' => 'meta_canonical', 'type' => 'free', 'default' => base_url(), 'max' => 250 ] );
		$this->addField( [ 'field' => 'meta_robots', 'type' => 'free', 'input' => 'combo', 'values' => $cboRobot, 'multiselect' => TRUE ] );
		$this->addField( [ 'field' => 'meta_sosmed_title', 'type' => 'free', 'max' => 250, 'line' => TRUE, 'line-text' => 'Sosial Media Meta', 'line-icon' => 'icon-users' ] );
		$this->addField( [ 'field' => 'meta_sosmed_description', 'default' => $this->configuration['meta_description'], 'type' => 'free', 'input' => 'multitext', 'max' => 250 ] );
		$this->addField( [ 'field' => 'meta_sosmed_image', 'type' => 'free', 'max' => 250 ] );
		$this->addField( [ 'field' => 'meta_sosmed_type', 'type' => 'free', 'max' => 100 ] );
		$this->addField( [ 'field' => 'meta_sosmed_url', 'default' => base_url(), 'type' => 'free', 'max' => 250 ] );
		$this->set_Close_Tab();
	}

	function insertValue_PARAM_LANG( $value, $rows, $old )
	{
		$return = $this->save_langdata( $value, $rows, $old );
		return $return;
	}

	function updateValue_PARAM_LANG( $value, $rows, $old )
	{
		$return = $this->save_langdata( $value, $rows, $old );
		return $return;
	}

	function save_langdata( $value, $rows, $old )
	{
		$lang       = $this->db->where( 'is_default', 0 )->get( _TBL_BAHASA )->result();
		$lang_value = [];
		foreach( $lang as $lg )
		{
			foreach( $this->multi_lang_value as $row )
			{
				$lang_value[$lg->key][$row] = $rows[$row . '_' . $lg->key];
			}
		}
		$return = json_encode( $lang_value );
		return $return;
	}

	function insertValue_PARAM_META( $value, $rows, $old )
	{
		$return = $this->save_metadata( $value, $rows, $old );
		return $return;
	}

	function updateValue_PARAM_META( $value, $rows, $old )
	{
		$return = $this->save_metadata( $value, $rows, $old );
		return $return;
	}

	function save_metadata( $value, $rows, $old )
	{
		$content['meta_description']        = $rows['meta_description'];
		$content['meta_keywords']           = $rows['meta_keywords'];
		$content['meta_author']             = $rows['meta_author'];
		$content['meta_robots']             = implode( ',', $rows['meta_robots'] );
		$content['meta_canonical']          = $rows['meta_canonical'];
		$content['meta_sosmed_title']       = $rows['meta_sosmed_title'];
		$content['meta_sosmed_description'] = $rows['meta_sosmed_description'];
		$content['meta_sosmed_image']       = $rows['meta_sosmed_image'];
		$content['meta_sosmed_type']        = $rows['meta_sosmed_type'];
		$content['meta_sosmed_url']         = $rows['meta_sosmed_url'];
		$return                             = json_encode( $content );
		return $return;
	}

	function inputBox_PARAM_META( $mode, $field, $rows, $value )
	{
		if( $rows )
			$this->param_meta_value = json_decode( $rows['param_meta'], TRUE );
		else
			$this->param_meta_value = [ 'meta_description' => '', 'meta_keywords' => '', 'meta_author' => '', 'meta_sosmed_type' => '', 'meta_robots' => '', 'meta_canonical' => '', 'meta_sosmed_title' => '', 'meta_sosmed_url' => '', 'meta_sosmed_description' => '', 'meta_sosmed_image' => '' ];
	}

	function inputBox_META_DESCRIPTION( $mode, $field, $row, $value )
	{
		if( empty( $this->param_meta_value['meta_description'] ) )
			$value = $this->configuration['meta_description'];
		else
			$value = $this->param_meta_value['meta_description'];
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_KEYWORDS( $mode, $field, $row, $value )
	{
		if( empty( $this->param_meta_value['meta_keywords'] ) )
			$value = $this->configuration['meta_keywords'];
		else
			$value = $this->param_meta_value['meta_keywords'];
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_AUTHOR( $mode, $field, $row, $value )
	{
		if( empty( $this->param_meta_value['meta_author'] ) )
			$value = $this->configuration['meta_author'];
		else
			$value = $this->param_meta_value['meta_author'];
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_CANONICAL( $mode, $field, $row, $value )
	{
		if( $this->param_meta_value )
		{
			$value = $this->param_meta_value['meta_canonical'];
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_ROBOTS( $mode, $field, $row, $value )
	{
		if( ! is_array( $value ) )
		{
			if( empty( $this->param_meta_value['meta_robots'] ) )
				$value = explode( ',', $this->configuration['meta_robots'] );
			else
				$value = explode( ',', $this->param_meta_value['meta_robots'] );
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_SOSMED_TITLE( $mode, $field, $row, $value )
	{
		if( $this->param_meta_value )
		{
			$value = $this->param_meta_value['meta_sosmed_title'];
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_SOSMED_DESCRIPTION( $mode, $field, $row, $value )
	{
		if( $this->param_meta_value )
		{
			$value = $this->param_meta_value['meta_sosmed_description'];
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_SOSMED_IMAGE( $mode, $field, $row, $value )
	{
		if( $this->param_meta_value )
		{
			$value = $this->param_meta_value['meta_sosmed_image'];
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_SOSMED_TYPE( $mode, $field, $row, $value )
	{
		if( $this->param_meta_value )
		{
			$value = $this->param_meta_value['meta_sosmed_type'];
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_META_SOSMED_URL( $mode, $field, $row, $value )
	{
		if( $this->param_meta_value )
		{
			$value = $this->param_meta_value['meta_sosmed_url'];
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}


	function getListMonitoring( $b, $rows, $value )
	{
		$bln=intval($b);
 		$tgl = 01;
		$cekFinal = $this->data->cek_mitigasi_final($rows['id'], $bln);
		$monthly = $this->data->getMonthlyMonitoring($rows['id'], $bln);
		$monthlyBefore = $this->data->getMonthlyMonitoring($rows['id'], $bln - 1);
		$period = $this->db->where('period_id', $rows['period_id'])->where('bulan_int', $bln)->get("il_view_minggu")->row_array();

 		$blnnow = date('mY');
		$tglnow = date('d');
		$thnRcsa = substr($period['period'], 0, 4);
		$dateRcsa = new DateTime($thnRcsa . '-' . $bln . '-' . $tgl);
		$hariIni = new DateTime();
		$title = 'Update Monitoring ' . date('M', mktime(0, 0, 0, $bln, 10));
		if(!$cekFinal && isset($monthly)){
			$title = 'update aktifitas belum lengkap';
		}

		if (isset($monthlyBefore)) {
			if (intval($blnnow) > (1 * 100 + intval($period['period']))   and $hariIni  > $dateRcsa && $cekFinal) {
				if (isset($monthly)) {
					$result = '<a class="propose" href="' . base_url('progress-mitigasi' . '/update/' . $rows['id'] . '/' . $bln) . '"><span class="btn" style="padding:4px 8px;width:100%;background-color:' . $monthly['color'] . ';color:' . $monthly['color_text'] . ';" title="'.$title.'">' . $monthly['level_color'] . ' </span></a>';
				} else {
					$result = '<a class="propose" href="' . base_url('progress-mitigasi' . '/update/' . $rows['id'] . '/' . $bln) . '"><span class="btn" style="padding:4px 8px;width:100%;;" title="'.$title.'"><i class="fa fa-pencil" aria-hidden="true"></i> </span></a>';
				}
			}
			else
			{
				$result = '<span class="text-danger"><i class="fa fa-times" aria-hidden="true"></i></span>';
			}
		}
		else
		{
			if( $bln == 01 )
			{
				if( isset( $monthly ) )
				{
					$result = '<a class="propose" href="' . base_url( 'progress-mitigasi' . '/update/' . $rows['id'] . '/' . $bln ) . '"><span class="btn" style="padding:4px 8px;width:100%;background-color:' . $monthly['color'] . ';color:' . $monthly['color_text'] . ';" title="' . $title . '">' . $monthly['level_color'] . ' </span></a>';
				}
				else
				{
					$result = '<a class="propose" href="' . base_url( 'progress-mitigasi' . '/update/' . $rows['id'] . '/' . $bln ) . '"><span class="btn" style="padding:4px 8px;width:100%;;" title="' . $title . '"><i class="fa fa-pencil" aria-hidden="true"></i> </span></a>';
				}
			}
			else
			{
				$result = '<span class="text-danger"><i class="fa fa-times" aria-hidden="true"></i></span>';
			}
		}
		return $result;
	}
}
