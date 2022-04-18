<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logdata {
	private $ci;
	private $agent;
	public $user;
	public $type;
		/**
	 * message (uses lang file)
	 *
	 * @var string
	 */
	protected $messages;
	protected $log;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 */
	protected $errors;

	/**
	 * error start delimiter
	 *
	 * @var string
	 */
	protected $error_start_delimiter;

	/**
	 * error end delimiter
	 *
	 * @var string
	 */
	protected $error_end_delimiter;


	function __construct()
	{
        $this->ci =& get_instance();
        $this->messages    = [];
		$this->errors      = [];
        $this->log      = [];
        $this->ci =& get_instance();

        $this->agent = (array) $this->ci->agent;
        $this->user = $this->ci->session->userdata('data_user');
	}

    public function set_log($log, $value)
	{
        if (is_array($value)){
			$val='';
            foreach ($value as $key=>$row){
                $val .= $key.'='.$row. ' \n ';
            }
        }else{
            $val=$value;
        }
		$this->log[$log][] = $val;

		return $log;
    }
    
    public function save_log()
	{
		$this->ci->crud->crud_table(_TBL_LOG);
        $this->ci->crud->crud_type('add');
        $this->ci->crud->crud_field('ip', $this->ci->input->ip_address());
        $this->ci->crud->crud_field('user_no', $this->user['id']);
        $this->ci->crud->crud_field('user_name', $this->user['username']);
        $this->ci->crud->crud_field('module', str_replace('_','-',$this->ci->router->fetch_module()));
        $this->ci->crud->crud_field('agent', $this->agent['agent']);
        $this->ci->crud->crud_field('referer', $this->ci->input->server('REQUEST_URI'));
        $this->ci->crud->crud_field('type', $this->type);
        $message='';
        foreach($this->log as $keys=>$logs){
        	foreach($logs as $key=>$log){
            	$message .=$keys.' '.($key+1). ' : '.$log.'\n';
        	}
        }
        $this->ci->crud->crud_field('message', $message);
        $this->ci->crud->process_crud();
        $this->clear_log();
		return $log;
    }

    public function clear_log()
	{
		$this->log = [];

		return TRUE;
    }
    
	public function set_message($message, $replace=false)
	{
        if ($replace)
            $this->messages = [];
            
		$this->messages[] = $message;
		return $message;
	}

	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return string
	 * @author Ben Edmunds
	 */
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$_output .= $message.'<br/>' ;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @param bool $langify
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 */
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = [];
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}

	/**
	 * clear_messages
	 *
	 * Clear messages
	 *
	 * @return true
	 * @author Ben Edmunds
	 */
	public function clear_messages()
	{
		$this->messages = [];

		return TRUE;
	}

	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @param string $error The error to set
	 *
	 * @return string The given error
	 * @author Ben Edmunds
	 */
	public function set_error($error, $replace=false)
	{
        if ($replace)
            $this->errors = [];
            
            $this->errors[] = $error;
        return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return string
	 * @author Ben Edmunds
	 */
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$_output .= $error.' <br/>';
		}

		return $_output;
	}

	/**
	 * errors as array
	 *
	 * Get the error messages as an array
	 *
	 * @param bool $langify
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 */
	public function errors_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = [];
			foreach ($this->errors as $error)
			{
				$_output[] = $error;
			}
			return $_output;
		}
		else
		{
			return $this->errors;
		}
	}

	/**
	 * clear_errors
	 *
	 * Clear Errors
	 *
	 * @return true
	 * @author Ben Edmunds
	 */
	public function clear_errors()
	{
		$this->errors = [];

		return TRUE;
	}
}

/* End of file Authentication.php */
/* Location: ./application/libraries/Authentication.php */