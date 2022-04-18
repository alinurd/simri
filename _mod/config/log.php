<?php
/**
 * CodeIgniter Log config
 *
 * @category   Applications
 * @package    CodeIgniter
 * @subpackage Config
 * @author     Bo-Yi Wu <appleboy.tw@gmail.com>
 * @license    BSD License
 * @link       http://blog.wu-boy.com/
 * @since      Version 1.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Database log table name
 */
$config['log_table_name'] = 'error_logs';
$config['log_acception'] = ['Warning']; //Warning Error
$config['log_acception_str'] = ['touch(): Unable to create file tmp/session', 'session_write_close']; //Warning