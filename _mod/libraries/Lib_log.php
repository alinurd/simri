<?php
/**
 * CodeIgniter Log Library
 *
 * @category   Applications
 * @package    CodeIgniter
 * @subpackage Libraries
 * @author     Bo-Yi Wu <appleboy.tw@gmail.com>
 * @license    BSD License
 * @link       http://blog.wu-boy.com/
 * @since      Version 1.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Lib_log
{
    /**
     * ci
     *
     * @param instance object
     */
    private $_ci;

    /**
     * log table name
     *
     * @param string
     */
    private $_log_table_name;
    private $__log_acception=[];
    private $__log_acception_str=[];

    public $levels = array(
        0             => 'Error',
        E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parsing Error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable error',
        E_DEPRECATED        => 'Runtime Notice',
        E_USER_DEPRECATED   => 'User Warning',
        1054                => 'Database Error'
    );

    /**
     * constructor
     *
     */
    public function __construct()
    {
        $this->_ci =& get_instance();
        set_error_handler(array($this, 'error_handler'));
        set_exception_handler(array($this, 'exception_handler'));
        // Load database driver
        $this->_ci->load->database();
        // Load config file
        $this->_ci->load->config('log');
        $this->_log_table_name = ($this->_ci->config->item('log_table_name')) ? $this->_ci->config->item('log_table_name') : 'logs';
        $this->_log_acception = ($this->_ci->config->item('log_acception')) ? $this->_ci->config->item('log_acception') : [];
        $this->_log_acception_str = ($this->_ci->config->item('log_acception_str')) ? $this->_ci->config->item('log_acception_str') : [];
    }

    /**
     * PHP Error Handler
     *
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return void
     */
    public function error_handler($severity, $message, $filepath, $line)
    {
        $user_id='';
        if ($this->_ci->ion_auth->logged_in()){
            $user_id = $this->_ci->ion_auth->get_user_name();
        }

        $errtype=isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;
        if (!in_array($errtype,$this->_log_acception)){
            $data = array(
                'errno' => $severity,
                'errtype' => $errtype,
                'errstr' => $message,
                'user_id' => $user_id,
                'errfile' => $filepath,
                'errline' => $line,
                'user_agent' => $this->_ci->input->user_agent(),
                'ip_address' => $this->_ci->input->ip_address(),
                'time' => date('Y-m-d H:i:s')
            );

            $this->_ci->db->insert($this->_log_table_name, $data);
        }
    }

    /**
     * PHP Error Handler
     *
     * @param   object
     * @return void
     */
    public function exception_handler($exception)
    {
        $user_id='';
        if ($this->_ci->ion_auth->logged_in()){
            $user_id = $this->_ci->ion_auth->get_user_name();
        }
        $cd = $this->_ci->db->error();
        if ($cd['code']>0){
            $code=$cd['code'];
            $message=$exception->getMessage().'\n'.$cd['message'];
        }else{
            $code=$exception->getCode();
            $message = $exception->getMessage();
        }

        $errtype=isset($this->levels[$exception->getCode()]) ? $this->levels[$exception->getCode()] : $exception->getCode();
        if (!in_array($errtype,$this->_log_acception)){

            $data = array(
                'errno' => $code,
                'errtype' => $errtype,
                'errstr' => $message,
                'user_id' => $user_id,
                'errfile' => $exception->getFile(),
                'errline' => $exception->getLine(),
                'user_agent' => $this->_ci->input->user_agent(),
                'ip_address' => $this->_ci->input->ip_address(),
                'time' => date('Y-m-d H:i:s')
            );

            $this->_ci->db->insert($this->_log_table_name, $data);
        }
    }
}

/* End of file Lib_log.php */
