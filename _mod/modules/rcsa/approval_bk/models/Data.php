<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

    function get_email_creator($id)
	{
		$risk = $this->db->select('created_by, updated_by, owner_id')->from('il_rcsa')->where('id', $id)->get()->row();
		// if ($risk->created_by == NULL && $risk->updated_by==NULL) {
			$mail = $this->get_email_officer_by_owner($risk->owner_id);
			// $mailArr = [];
			// if(!empty($mail)){
			// 	foreach ($mail as $key => $value) {
			// 		$mailArr[] = $value->email;
			// 	}
			// }
			return $mail;
		// }
		// die();
		// if ($risk->created_by) {
		// 	$this->db->where('username', $risk->created_by);
		// }else{
		// 	$this->db->where('username', $risk->updated_by);
		// }
		
		// return  $this->db->select('email, real_name')->from('il_users')->get()->row();
	}

	function get_data_minggu($id)
	{
		$rows = $this->db->select('*')->where('id', $id)->get(_TBL_COMBO)->row();
		$tgl1 = date('Y-m-d');
		$tgl2 = date('Y-m-d');
		if ($rows) {
			$tgl1 = $rows->param_date;
			$tgl2 = $rows->param_date_after;
		}
		$rows = $this->db->select('*')->where('kelompok', 'minggu')->where('param_date>=', $tgl1)->where('param_date_after<=', $tgl2)->get(_TBL_COMBO)->result();
		$option[""] = _l('cbo_select');
		foreach ($rows as $row) {
			$option[$row->id] = $row->param_string . ' (' . date('d-m-Y', strtotime($row->param_date)) . ' s.d ' . date('d-m-Y', strtotime($row->param_date_after)) . ')';
		}

		return $option;
	}

	function get_email_role_admin_mr()
	{
		$query = $this->db->query("SELECT il_officer.officer_name, il_users.email FROM il_officer JOIN il_users ON il_users.staft_id = il_officer.id JOIN il_users_groups ON il_users_groups.user_id = il_users.id WHERE owner_no = 46 AND group_id = 1 ORDER BY officer_name ASC");

		$hasil = $query->result();

		return $hasil;
	}

	function get_email_officer_by_owner($owner)
	{
		$query = $this->db->query("SELECT distinct il_users.email, il_officer.officer_name FROM il_officer JOIN il_users ON il_users.staft_id = il_officer.id JOIN il_users_groups ON il_users_groups.user_id = il_users.id WHERE owner_no = ".$owner. " AND group_id = 2 ORDER BY officer_name ASC LIMIT 3");

		$hasil = $query->result();

		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */