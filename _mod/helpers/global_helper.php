<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );




if( ! function_exists( 'is_model_exist' ) )
{
	function is_model_exist( $model )
	{
		$ci       =& get_instance();
		$load_arr = (array) $ci->load;
		$mod_arr  = array();
		foreach( $load_arr as $key => $value )
		{
			if( substr( trim( $key ), 2, 50 ) == "_ci_model_paths" )
			{
				$mod_arr = $value;
				break;
			}
		}

		$nama = 'models/' . ucfirst( $model ) . '.php';
		foreach( $mod_arr as $path )
		{
			if( file_exists( $path . $nama ) )
				return TRUE;
		}
		return FALSE;
	}
}

if( ! function_exists( '_tree' ) )
{
	function _tree( array $elements, $parentId = 0 )
	{
		$branch = array();
		foreach( $elements as $element )
		{
			if( $element['slug'] == $parentId )
			{
				$children = _tree( $elements, $element['id'] );
				if( $children )
				{
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}
		return $branch;
	}
}

if( ! function_exists( 'dumps' ) )
{
	function dumps( $expression, $return = FALSE, $die = FALSE )
	{
		ob_start();
		$content = ob_get_contents();
		ob_end_clean();

		if( $return )
		{
			return $content;
		}
		else
		{
			if( isset( $_SERVER['argc'] ) && isset( $_SERVER['argv'] ) )//from cli
				echo $content;
			else
			{
				echo '<pre class="doi_dump">';
				echo htmlentities( $content );
				echo '</pre>';
			}
			if( $die )
				die( 'dumps' );
		}
	}
}

if( ! function_exists( 'getUserIP' ) )
{
	function getUserIP()
	{
		if( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
		{
			if( strpos( $_SERVER['HTTP_X_FORWARDED_FOR'], ',' ) > 0 )
			{
				$addr = explode( ",", $_SERVER['HTTP_X_FORWARDED_FOR'] );
				return trim( $addr[0] );
			}
			else
			{
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		else
		{
			return $_SERVER['REMOTE_ADDR'];
		}
	}
}

if( ! function_exists( 'create_unique_slug' ) )
{
	function create_unique_slug( $string, $table, $field = 'uri_title' )
	{
		$CI             =& get_instance();
		$string         = trim( $string );
		$slug           = url_title( $string );
		$slug           = strtolower( $slug );
		$i              = 0;
		$params         = [];
		$params[$field] = $slug;
		if( $CI->input->post( 'id' ) )
		{
			$params['id !='] = $CI->input->post( 'id_edit' );
		}

		while( $CI->db->where( $params )->get( $table )->num_rows() )
		{
			if( ! preg_match( '/-{1}[0-9]+$/', $slug ) )
			{
				$slug .= '-' . ++$i;
			}
			else
			{
				$slug = preg_replace( '/[0-9]+$/', ++$i, $slug );
			}
			$params[$field] = $slug;
		}

		return $slug;
	}
}


if( ! function_exists( 'base_front_url' ) )
{
	/**
	 * Base URL
	 *
	 * Create a local URL based on your basepath.
	 * Segments can be passed in as a string or an array, same as site_url
	 * or a URL to a file can be passed in, e.g. to an image file.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function base_front_url( $uri = '', $protocol = NULL )
	{
		$CI =& get_instance();
		if( ! empty( $uri ) )
		{
			$uri = '/' . $uri;
		}
		return $CI->config->item( 'frontend_url' ) . $uri;
	}
}

/**
 *  Check if input string is a valid YouTube URL
 *  and try to extract the YouTube Video ID from it.
 *  @author  Stephan Schmitz <eyecatchup@gmail.com>
 *  @param   $url   string   The string that shall be checked.
 *  @return  mixed           Returns YouTube Video ID, or (boolean) false.
 */
if( ! function_exists( 'parse_yturl' ) )
{
	function parse_yturl( $url )
	{
		$pattern = '#^(?:https?://)?';    # Optional URL scheme. Either http or https.
		$pattern .= '(?:www\.)?';         #  Optional www subdomain.
		$pattern .= '(?:';                #  Group host alternatives:
		$pattern .= 'youtu\.be/';       #    Either youtu.be,
		$pattern .= '|youtube\.com';    #    or youtube.com
		$pattern .= '(?:';              #    Group path alternatives:
		$pattern .= '/embed/';        #      Either /embed/,
		$pattern .= '|/v/';           #      or /v/,
		$pattern .= '|/watch\?v=';    #      or /watch?v=,    
		$pattern .= '|/watch\?.+&v='; #      or /watch?other_param&v=
		$pattern .= ')';                #    End path alternatives.
		$pattern .= ')';                  #  End host alternatives.
		$pattern .= '([\w-]{11})';        # 11 characters (Length of Youtube video ids).
		$pattern .= '(?:.+)?$#x';         # Optional other ending URL parameters.
		preg_match( $pattern, $url, $matches );
		return ( isset( $matches[1] ) ) ? $matches[1] : FALSE;
	}
}

if( ! function_exists( 'split_words' ) )
{
	function split_words( $string, $nb_caracs )
	{
		$final_string = "";
		$string       = strip_tags( html_entity_decode( $string ) );
		if( strlen( $string ) <= $nb_caracs )
		{
			$final_string = $string;
		}
		else
		{
			$final_string = "";
			$words        = explode( " ", $string );
			$no           = 0;
			foreach( $words as $value )
			{
				if( $no < $nb_caracs )
				{
					if( ! empty( $final_string ) ) $final_string .= " ";
					$final_string .= $value;
					++$no;
				}
				else
				{
					break;
				}
			}
		}
		return $final_string;
	}
}

if( ! function_exists( 'token' ) )
{
	function token( $value = 40, $tipe = "angkahuruf", $prefix = '', $id = '', $table = '' )
	{
		$ci =& get_instance();

		if( empty( $tipe ) )
			$keyspace = '1234567890abcdefghijklmnopqrstuvwxyz!@#$%ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		elseif( $tipe == 'angka' )
			$keyspace = '1234567890';
		elseif( $tipe == 'huruf' )
			$keyspace = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		elseif( $tipe == 'angkahuruf_upper' )
			$keyspace = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		elseif( $tipe == "angkahuruf" )
			$keyspace = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		// $keyspace = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		if( empty( $table ) )
		{
			$rows = $ci->db->select( 'token' )->get( 'il_api_keys' )->result_array();
		}
		else
		{
			$rows = $ci->db->select( 'forgotten_password_code as token' )->where( 'forgotten_password_code<>', NULL )->where( 'forgotten_password_code<>', '' )->get( $table )->result_array();
		}
		$code = [];
		foreach( $rows as $row )
		{
			$code[$row['token']] = $row['token'];
		}

		do
		{
			$str = $prefix;
			$max = mb_strlen( $keyspace, '8bit' ) - 1;
			for( $i = 0; $i < $value; ++$i )
			{
				$str .= $keyspace[random_int( 0, $max )];
			}
			$str .= $id;
		} while( array_key_exists( $str, $code ) );
		return $str;
	}
}

if( ! function_exists( '_h' ) )
{
	function _h( $line, $label = '', $log_errors = TRUE, $popup = TRUE )
	{
		$CI = &get_instance();

		$_line = @sprintf( $CI->lang->line( trim( $line ), $log_errors ), $label );

		$help = '';
		if( $_line != '' )
		{
			if( preg_match( '/"/', $_line ) && ! is_html( $_line ) )
			{
				$_line = html_escape( $_line );
			}

			$help = ForceUTF8\Encoding::toUTF8( $_line );
		}

		$span_help = '';
		if( $CI->data_config->get_Preference( 'help_tool' ) )
		{
			if( $CI->data_config->get_Preference( 'help_popup' ) )
			{
				if( ! empty( $help ) )
				{
					$span_help = '&nbsp;&nbsp;&nbsp;<span class="float-right pointer" data-popup="tooltip" data-html="true" title="' . $help . '"><i class="icon-help text-info"></i></span>';
				}
			}
		}

		if( ! $popup )
		{
			$span_help = '<span class="text-warning"><small>' . $help . '</small></span>';
		}

		return $span_help;
	}
}

if( ! function_exists( '_l' ) )
{
	function _l( $line, $label = '', $log_errors = TRUE )
	{
		$CI = &get_instance();

		if( is_array( $label ) && count( $label ) > 0 )
		{
			$_line = vsprintf( $CI->lang->line( trim( $line ), $log_errors ), $label );
		}
		else
		{
			$_line = @sprintf( $CI->lang->line( trim( $line ), $log_errors ), $label );
		}

		if( $_line != '' )
		{
			if( preg_match( '/"/', $_line ) && ! is_html( $_line ) )
			{
				$_line = html_escape( $_line );
			}

			return ForceUTF8\Encoding::toUTF8( $_line );
		}

		if( mb_strpos( $line, '_db_' ) !== FALSE )
		{
			return 'db_translate_not_found';
		}

		return ForceUTF8\Encoding::toUTF8( $line );
	}
}

if( ! function_exists( 'time_ago' ) )
{
	function time_ago( $tgl, $unit = 6 )
	{
		$CI =& get_instance();
		$CI->lang->load( 'date' );

		$str = array();
		// $xx=date('Y-m-d H:i:s') ;
		$waktu_tujuan   = strtotime( $tgl );
		$waktu_sekarang = mktime( date( "H" ), date( "i" ), date( "s" ), date( "m" ), date( "d" ), date( "Y" ) );
		// echo $tgl . ' ' . $xx . "<br>";
		// echo $waktu_sekarang . ' ' . $waktu_tujuan . "<br>";
		// die();

		//hitung selisih kedua waktu
		$selisih_waktu = $waktu_sekarang - $waktu_tujuan;

		//Untuk menghitung jumlah dalam satuan hari:
		$jumlah_tahun = floor( $selisih_waktu / 31557600 );
		if( $jumlah_tahun > 0 )
		{
			$str[] = $jumlah_tahun . ' ' . $CI->lang->line( $jumlah_tahun > 1 ? 'date_years' : 'date_year' );
		}

		//Untuk menghitung jumlah dalam satuan hari:
		$sisa         = $selisih_waktu % 31557600;
		$jumlah_bulan = floor( $sisa / 2629743 );
		if( $jumlah_bulan > 0 )
		{
			$str[] = $jumlah_bulan . ' ' . $CI->lang->line( $jumlah_bulan > 1 ? 'date_months' : 'date_month' );
		}

		//Untuk menghitung jumlah dalam satuan hari:
		$sisa          = $selisih_waktu % 2629743;
		$jumlah_minggu = floor( $sisa / 604800 );
		if( $jumlah_minggu > 0 )
		{
			$str[] = $jumlah_minggu . ' ' . $CI->lang->line( $jumlah_minggu > 1 ? 'date_weeks' : 'date_week' );
		}

		//Untuk menghitung jumlah dalam satuan hari:
		$sisa        = $selisih_waktu % 604800;
		$jumlah_hari = floor( $sisa / 86400 );
		if( $jumlah_hari > 0 )
		{
			$str[] = $jumlah_hari . ' ' . $CI->lang->line( $jumlah_hari > 1 ? 'date_days' : 'date_day' );
		}

		//Untuk menghitung jumlah dalam satuan jam:
		$sisa       = $selisih_waktu % 86400;
		$jumlah_jam = floor( $sisa / 3600 );
		if( $jumlah_jam > 0 )
		{
			$str[] = $jumlah_jam . ' ' . $CI->lang->line( $jumlah_jam > 1 ? 'date_hours' : 'date_hour' );
		}

		//Untuk menghitung jumlah dalam satuan menit:
		$sisa         = $sisa % 3600;
		$jumlah_menit = floor( $sisa / 60 );
		if( $jumlah_menit > 0 )
		{
			$str[] = $jumlah_menit . ' ' . $CI->lang->line( $jumlah_menit > 1 ? 'date_minutes' : 'date_minute' );
		}
		// die(implode(', ', $str));
		$result = $CI->lang->line( "date_a_few" );
		if( count( $str ) > 0 )
			$result = $str[0] . $CI->lang->line( "date_ago" );
		;

		return $result;
	}
}

if( ! function_exists( 'isValidEmail' ) )
{
	function isValidEmail( $email )
	{
		$regex = "/(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}/";
		if( preg_match( $regex, $email, $match ) )
		{
			return $match[0];
		}
		return FALSE;
	}
}

if( ! function_exists( 'upload_image_new' ) )
{
	function upload_image_new( $data = array(), $multi = FALSE, $no = 0 )
	{
		// Doi::dump($data);

		if( ! array_key_exists( 'path', $data ) )
			$data['path'] = 'staft';
		if( ! array_key_exists( 'nm_random', $data ) )
			$data['nm_random'] = TRUE;
		if( ! array_key_exists( 'type', $data ) )
			$data['type'] = 'gif|jpg|png';
		if( ! array_key_exists( 'thumb', $data ) )
			$data['thumb'] = TRUE;
		if( ! array_key_exists( 'size', $data ) )
			$data['size'] = 10000;
		if( ! array_key_exists( 'sub_path', $data ) )
			$data['sub_path'] = '';
		if( ! array_key_exists( 'file_name', $data ) )
			$data['file_name'] = $_FILES[$data['nm_file']]['name'];
		// $data['file_name']=url_title(strtolower($_FILES[$data['nm_file']]['name']));
		// Doi::dump($_FILES[$data['nm_file']]);
		switch( $data['path'] )
		{
			case 'staft':
				$path = staft_path_relative();
				break;
			case 'slide':
				$path = slide_path_relative();
				break;
			case 'news':
				$path = news_path_relative();
				break;
			case 'regulasi':
				$path = regulasi_path_relative();
				break;
			case 'import':
				$path = import_path_relative();
				break;
			case 'export':
				$path = export_path_relative();
				break;
			case 'events':
				$path = events_path_relative();
				break;
			case 'rcsa':
				$path = file_path_relative( 'rcsa' );
				break;
			case 'loss':
				$path = file_path_relative( 'loss' );
				break;
			case 'upload':
				$path = file_path_relative();
				break;
			default:
				$path = file_path_relative();
				break;
		}
		if( defined( '_CABANG_NO_' ) )
		{
			$fld = strtolower( _CABANG_NO_ . '-' . url_title( _CABANG_KODE_ ) );
			// echo $fld;
			if( ! is_dir( $path . '/' . $fld ) )
			{
				mkdir( $path . '/' . $fld, 0777, TRUE );
			}
			$path .= '/' . $fld;
		}
		// die("cabang nya : "._CABANG_NO_." namanya :"._CABANG_NAMA_);

		$ci                    =& get_instance();
		$config['upload_path'] = $path;

		$config['allowed_types'] = $data['type'];
		$config['max_size']      = $data['size'];
		$config['overwrite']     = FALSE;
		$config['encryp_name']   = FALSE;
		$config['remove_space']  = TRUE;
		// if (array_key_exists('file_name', $data)){
		// $config['file_name']=$data['file_name'];
		// }else{
		// $config['file_name']=$_FILES[$data['nm_file']]['name'];
		// }
		// die();

		$data['file_name'] = preg_replace( '/(.*)\\.[^\\.]*/', '$1', $data['file_name'] );
		if( $data['nm_random'] )
			$config['file_name'] = md5( $data['file_name'] . time() );
		else
			$config['file_name'] = url_title( strtolower( basename( $data['file_name'] ) ) );

		if( ! is_dir( $config['upload_path'] ) )
		{
			Doi::dump( $config['upload_path'] );
			return FALSE;
		}
		// Doi::dump($config);
		// Doi::dump("nonya ".$no);
		// Doi::dump(" - nonya ".$multi);
		if( ( $multi && $no == 0 ) || ! $multi )
		{
			$ci->load->library( 'upload', $config );
		}
		else
		{
			$ci->upload->initialize( $config, TRUE );
		}
		if( ! $ci->upload->do_upload( $data['nm_file'] ) )
		{
			$error = $ci->upload->display_errors();
			// Doi::dump($data);
			// Doi::dump($config);
			// Doi::dump($_FILES['userfile']);
			// die($error);
			$msg = $error;
			$ci->session->set_userdata( array( 'result_proses_error' => $msg ) );
			$sql['message']       = 'upload image gagal ' . $_FILES[$data['nm_file']]['name'];
			$sql['priority']      = 3;
			$sql['priority_name'] = 'Biasa';
			$sql['type']          = 'image';
			$sql['jml']           = 1;
			$sql['old_data']      = "";
			$sql['new_data']      = "";
			$id                   = 0;
			return FALSE;
		}
		else
		{
			$result = $ci->upload->data();
		}
		if( $data['thumb'] )
		{
			create_thumb( $result['file_name'], 160, $path );
			create_thumb( $result['file_name'], 60, $path );
		}
		// if (defined('_CABANG_NO_')){
		// $result['file_name'] = $fld . "/". $result['file_name'];
		// }
		return $result;
	}
}

if( ! function_exists( 'checkPassword' ) )
{
	function checkPassword( $pwd, &$errors )
	{
		$CI          =& get_instance();
		$errors_init = $errors;

		$preference = $CI->db->select( '*' )->get( _TBL_PREFERENCE )->result_array();
		$p          = [];
		foreach( $preference as $key => $pref )
		{
			$p[$pref['uri_title']] = $pref['value'];
		}

		if( strlen( $pwd ) < intval( $p['pass_min'] ) || strlen( $pwd ) > intval( $p['pass_max'] ) )
		{
			$errors[] = sprintf( lang( 'msg_pass_min' ), $p['pass_min'], $p['pass_max'] );
		}

		// if (strlen($pwd) > intval($p['pass_max'])) {
		// 	$errors[] = str_replace('[n]',$p['pass_max'],lang('msg_pass_max'));
		// }

		if( intval( $p['pass_number'] ) == 1 )
		{
			if( ! preg_match( "#[0-9]+#", $pwd ) )
			{
				$errors[] = lang( 'msg_pass_number' );
			}
		}

		if( intval( $p['pass_letter'] ) == 1 )
		{
			if( ! preg_match( "#[a-zA-Z]+#", $pwd ) )
			{
				$errors[] = lang( 'msg_pass_letter' );
			}
		}

		if( intval( $p['pass_upper'] ) == 1 )
		{
			if( ! preg_match( "#[A-Z]+#", $pwd ) )
			{
				$errors[] = lang( 'msg_pass_upper' );
			}
		}

		if( intval( $p['pass_lower'] ) == 1 )
		{
			if( ! preg_match( "#[a-z]+#", $pwd ) )
			{
				$errors[] = lang( 'msg_pass_lower' );
			}
		}

		if( intval( $p['pass_symbol'] ) == 1 )
		{
			if( ! preg_match( "#\W+#", $pwd ) )
			{
				$errors[] = lang( 'msg_pass_symbol' );
			}
		}
		return $errors;
	}
}
if( ! function_exists( 'form_textarea' ) )
{
	/**
	 * Textarea field
	 *
	 * @param	mixed	$data
	 * @param	string	$value
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_textarea( $data = '', $value = '', $extra = '', $info = TRUE, $params = [] )
	{
		$defaults = array(
		 'name' => is_array( $data ) ? '' : $data,
		 'cols' => '40',
		 'rows' => '10',
		);

		$param = array(
		'size' => 1000,
		'isi'  => 0,
		'no'   => 0,
		);
		$param = array_merge( $param, $params );

		if( ! is_array( $data ) or ! isset( $data['value'] ) )
		{
			$val = $value;
		}
		else
		{
			$val = $data['value'];
			unset( $data['value'] ); // textareas don't use the value attribute
		}
		$result  = '<textarea ' . _parse_form_attributes( $data, $defaults ) . _attributes_to_string( $extra ) . '>' . html_escape( $val ) . "</textarea>\n";
		$content = '';
		if( $info )
		{
			$jmlhuruf = intval( $param['size'] ) - intval( strlen( $param['isi'] ) );
			$left     = 'id_sisa_' . $param['no'];

			$content .= '<br/><span class="text-warning">' . lang( 'msg_chr_left' ) . ' </span><span style="display:inline-block;height:20px;"><small><input id="' . $left . '" type="hidden" align="right" class="form-control" style="text-align:right;width:60px;" disabled="" name="f1_11_char_left" value="' . $jmlhuruf . '" size="5">' . lang( 'btn_chr_left' ) . '<span id="span_' . $left . '"  align="right" class="badge badge-primary " name="f1_11_char_left" style="font-size: 100%;">' . $jmlhuruf . '</span></small></span>';
		}
		return $result . $content;
	}
}

if( ! function_exists( 'format_list' ) )
{
	function format_list( $str_arr, $format = ", " )
	{
		$arr    = explode( $format, $str_arr );
		$format = '<ol><li>' . implode( '</li><li>', $arr ) . '</li></ol>';
		return $format;
	}
}


if( ! function_exists( 'get_owner' ) )
{
	function get_owner( $str = '' )
	{
		$ci   =& get_instance();
		$str  = explode( ',', $str );
		$rows = $ci->db->where_in( 'id', $str )->get( 'il_owner' )->result_array();
		$x    = [];
		foreach( $rows as $row )
		{
			$x[] = $row['owner_name'];
		}
		$hasil = implode( '#', $x );
		$hasil = format_list( $hasil, "#" );
		return $hasil;
	}
}



