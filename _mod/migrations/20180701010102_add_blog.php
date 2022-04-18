<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_blog extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 *
	 */
	public function up()
	{
			$this->dbforge->add_field(array(
					'blog_id' => array(
							'type' => 'INT',
							'constraint' => 5,
							'unsigned' => TRUE,
							'auto_increment' => TRUE
					),
					'blog_title' => array(
							'type' => 'VARCHAR',
							'constraint' => '100',
					),
					'blog_description' => array(
							'type' => 'TEXT',
							'null' => TRUE,
					),
			));
			$this->dbforge->add_key('blog_id', TRUE);
			$this->dbforge->create_table('blog');
	}

	public function down()
	{
			$this->dbforge->drop_table('blog');
	}
}