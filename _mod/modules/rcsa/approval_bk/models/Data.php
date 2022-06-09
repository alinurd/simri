<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

    function get_email_creator($id)
	{
		$risk = $this->db->select('created_by, updated_by')->from('il_rcsa')->where('id', $id)->get()->row();
		if ($risk->created_by) {
			$this->db->where('username', $risk->created_by);
		}else{
			$this->db->where('username', $risk->updated_by);
		}
		
		return  $this->db->select('email, real_name')->from('il_users')->get()->row();
	}

	function get_email_role_admin_mr()
	{
		$query = $this->db->query("SELECT il_officer.officer_name, il_users.email FROM il_officer JOIN il_users ON il_users.staft_id = il_officer.id JOIN il_users_groups ON il_users_groups.user_id = il_users.id WHERE owner_no = 46 AND group_id = 1 ORDER BY officer_name ASC");

		$hasil = $query->result();

		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */