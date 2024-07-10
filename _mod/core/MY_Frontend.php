<?php ( defined( 'BASEPATH' ) ) or exit( 'No direct script access allowed' );

/* load the MX_Router class */
//require APPPATH."third_party/MX/Controller.php";

class MY_Frontend extends MX_Controller
{

	private $_template_ = 'template_front';
	public $_js_ = [];
	public $_css_ = [];
	public $_meta_ = [];
	public $_data_user_ = [];
	protected $configuration = [];
	protected $can = array();
	protected $post = array();

	public function __construct()
	{
		parent::__construct();
		$this->_data_user_ = $this->session->userdata( 'data_user' );
		$this->config->load( 'configuration', TRUE );
		$this->can                            = [ 'read' => TRUE, 'insert' => FALSE, 'update' => FALSE, 'delete' => FALSE, 'print' => FALSE ];
		$this->configuration                  = $this->config->item( 'default_config', 'configuration' );
		$live_config                          = $this->data_config->get_Preference( '', 1 );
		$this->configuration                  = array_merge( $this->configuration, $live_config );
		$this->configuration['content_title'] = ( ! empty( $this->lang->line( 'lang_' . _MODULE_NAME_REAL_ . '_title' ) ) ) ? $this->lang->line( 'lang_' . _MODULE_NAME_REAL_ . '_title' ) : ucwords( str_replace( '-', ' ', _MODULE_NAME_ ) ) . ' ' . ucwords( _MODE_ );
		$this->configuration['preference']    = $this->data_config->get_Preference();
		$this->configuration['user']          = $this->_data_user_;
		$this->configuration['_mode_']        = $this->_mode_;
		$this->preference                     = $this->configuration['preference'];
		$this->template->title->add( $this->configuration['preference']['nama_kantor'] );

		$this->_meta_ = [];
		foreach( $this->configuration as $key => $met )
		{
			if( substr( $key, 0, 4 ) == 'meta' )
			{
				$this->_meta_['meta'][substr( $key, 5, 100 )] = $met;
			}
			if( substr( $key, 0, 4 ) == 'link' )
			{
				$this->_meta_['link'][substr( $key, 5, 100 )] = $met;
			}
		}

		$user_id = 0;
		if( $this->configuration['user'] )
			$user_id = $this->configuration['user']['id'];

		define( '_USER_ID_', $user_id );

		$this->initialize();
		$this->_template_ .= '/' . $this->configuration['front_themes_mode'];

		$this->css();
		$this->js();

	}

	public function __get( $class )
	{
		return CI::$APP->$class;
	}

	protected function initialize()
	{
		if( method_exists( $this->router->fetch_class(), 'init' ) )
		{
			$configuration = (array) $this->init();
			// dumps($configuration);
			if( is_array( $configuration['configuration'] ) )
			{
				$this->configuration = array_merge( $this->configuration, $configuration['configuration'] );
			}
			// dumps($this->configuration);die();
		}
		return $this;
	}

	public function _remap( $method, $arguments )
	{
		$sts        = TRUE;
		$method_cek = str_replace( '_', '-', $this->router->fetch_module() );

		// !$this->configuration['user']['is_admin']
		if( ! $this->can['read'] && $this->router->fetch_module() !== 'errorpage' && ( ! $this->ion_auth->is_admin() ) )
		{
			header( 'location:' . base_url( 'access-denied' ) );
			exit();
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

	function index_default()
	{
		$content = $this->_template_ . '/default';

		if( method_exists( $this->router->fetch_class(), 'content' ) )
		{
			$content = $this->content();
		}
		else
		{
			$content = $this->load->view( $content, [], TRUE );
		}
		$header = '';
		$footer = '';

		$this->template->title->set_default( $this->configuration['preference']['nama_kantor'] );
		$this->template->set_template( $this->_template_ );
		$this->template->stylesheet->add( $this->_css_ );
		$this->template->javascript->add( $this->_js_ );
		$this->template->meta->add( $this->_setMeta_() );

		$this->template->content->view( $this->_template_ . '/content', [ 'content' => $content, 'params' => $this->configuration ] );

		$this->template->_params = $this->configuration;
		$this->template->publish();
	}

	function default_display( $param = [] )
	{
		$this->template->title->set_default( $this->configuration['preference']['nama_kantor'] );
		$this->template->set_template( $this->_template_ );
		$this->template->stylesheet->add( $this->_css_ );
		$this->template->javascript->add( $this->_js_ );
		$this->template->meta->add( $this->_setMeta_() );

		if( array_key_exists( 'configuration', $param ) )
			$this->register_configuration( $param['configuration'] );

		$this->template->content->view( $this->_template_ . '/content', [ 'content' => $param['content'], 'params' => $this->configuration, 'header' => '', 'footer' => '' ] );

		$this->template->_params = $this->configuration;
		$this->template->publish();
	}

	function _setMeta_()
	{
		$rows = $this->config->item( 'meta', 'configuration' );
		$meta = [];
		if( $rows )
		{
			foreach( $rows as $row )
			{
				if( $row['show'] )
				{
					$meta[$row['type']][$row['name']] = $row['content'];
				}
			}
		}
		if( array_key_exists( 'meta', $meta ) && array_key_exists( 'meta', $this->_meta_ ) )
		{
			$meta['meta'] = array_merge( $meta['meta'], $this->_meta_['meta'] );
		}
		if( array_key_exists( 'link', $meta ) && array_key_exists( 'link', $this->_meta_ ) )
		{
			$meta['link'] = array_merge( $meta['link'], $this->_meta_['link'] );
		}
		// dumps($meta);die();
		return $meta;
	}

	function _set_meta( $name, $value = '', $type = 'meta' )
	{
		$arrMeta = [ 'meta_description' => 'description', 'meta_keywords' => 'keywords', 'meta_author' => 'author', 'meta_robots' => 'robots', 'meta_sosmed_title' => 'og:title', 'meta_sosmed_description' => 'og:description', 'meta_sosmed_image' => 'og:image', 'meta_sosmed_type' => 'og:type', 'meta_sosmed_url' => 'og:url' ];
		if( is_array( $name ) )
		{
			// dumps($name);die();
			foreach( $name as $key => $row )
			{
				if( array_key_exists( $key, $arrMeta ) )
				{
					$isi = $row;
					if( $key == 'meta_sosmed_url' )
					{
						$isi = $this->uri->uri_string;
					}
					$this->_meta_['meta'][$arrMeta[$key]] = $isi;
				}
			}
		}
		else
		{
			$this->_meta_[$type][$name] = $value;
		}
	}

	function _set_title( $name )
	{
		$this->template->title->add( $name );
	}

	function css( $mode = [] )
	{
		$path = '';
		if( $this->configuration['themes_mode'] !== 'default' )
		{
			$path = $this->configuration['themes_mode'] . '/';
		}

		$this->_css_[] = 'bootstrap.min.css';
		$this->_css_[] = 'mega-menu/mega_menu.css';
		$this->_css_[] = 'font-awesome.min.css';
		$this->_css_[] = 'themify-icons.css';
		$this->_css_[] = 'owl-carousel/owl.carousel.css';
		$this->_css_[] = 'magnific-popup/magnific-popup.css';
		// $this->_css_[] = 'revolution/css/settings.css';
		$this->_css_[] = 'scrollbar/jquery.mCustomScrollbar.css';
		$this->_css_[] = 'fancybox/jquery.fancybox.css?v=2.1.5';
		$this->_css_[] = 'style.css';
		$this->_css_[] = 'responsive.css';
	}


	function js( $mode = [] )
	{
		$this->_js_[] = 'jquery.min.js';
		$this->_js_[] = 'popper.min.js';
		$this->_js_[] = 'bootstrap.min.js';
		$this->_js_[] = 'mega-menu/mega_menu.js';
		$this->_js_[] = 'owl-carousel/owl.carousel.min.js';
		$this->_js_[] = 'jquery.appear.js';
		$this->_js_[] = 'simple.money.format.js';
		$this->_js_[] = 'counter/jquery.countTo.js';
		$this->_js_[] = 'magnific-popup/jquery.magnific-popup.min.js';
		$this->_js_[] = 'fancybox/jquery.mousewheel.pack.js?v=3.1.3';
		$this->_js_[] = 'fancybox/jquery.fancybox.pack.js?v=2.1.5';
		// $this->_js_[] = 'revolution/js/jquery.themepunch.tools.min.js';
		// $this->_js_[] = 'revolution/js/jquery.themepunch.revolution.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.actions.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.carousel.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.kenburn.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.layeranimation.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.migration.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.navigation.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.parallax.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.slideanims.min.js';
		// $this->_js_[] = 'revolution/js/extensions/revolution.extension.video.min.js';
		$this->_js_[] = 'scrollbar/jquery.mCustomScrollbar.concat.min.js';
		$this->_js_[] = 'loaders/blockui.min.js';
		$this->_js_[] = 'custom.js';

		$arr_js = [ $this->modul_name, 'bersama' ];
		foreach( $arr_js as $js )
		{
			if( file_exists( FCPATH . 'assets/js/pages/' . $js . ".js" ) )
			{
				$this->_js_[] = 'pages/' . $js . '.js';
			}
		}
	}

	function switchLang( $language = "" )
	{
		$language = ( $language != "" ) ? $language : "english";
		$this->session->set_userdata( 'site_lang', $language );
		redirect( $_SERVER['HTTP_REFERER'] );
	}
}
