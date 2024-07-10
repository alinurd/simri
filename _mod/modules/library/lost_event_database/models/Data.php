<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{

	var $nm_tbl = '';
	var $nm_tbl_user = '';
	var $_prefix = '';
	var $_modules = '';
	public function __construct()
	{
		parent::__construct();
		$this->nm_tbl      = "group_user";
		$this->nm_tbl_user = "users";
		$this->_modules    = $this->router->fetch_module();
	}


	function get_group( $iduser = 0 )
	{
		$this->db->select( '*' );
		$this->db->from( $this->_prefix . 'group_user' );
		$this->db->where( 'user_no', $iduser );

		$query           = $this->db->get();
		$result['field'] = $query->result_array();
		return $result;
	}

	function get_img_file_name( $id )
	{
		$query = $this->db->select( '*' )
		  ->where( 'id', $id )
		  ->get( $this->nm_tbl_user );
		$rows  = $query->result();
		$nm    = '';
		foreach( $rows as $row )
		{
			$nm = $row->photo;
		}
		return $nm;
	}

	function save_group( $newid = 0, $data = array(), $img = "" )
	{
		$now = new DateTime();
		$tgl = $now->format( 'Y-m-d H:i:s' );

		if( ! empty( $img['file_name'] ) )
		{
			$upi['photo'] = $img['file_name'];
			$this->db->where( 'id', $newid );
			$this->db->update( $this->nm_tbl_user, $upi );
		}

		if( isset( $data['id_edit'] ) )
		{
			if( count( $data['id_edit'] ) > 0 )
			{
				foreach( $data['id_edit'] as $key => $row )
				{
					$upd['group_no'] = $data['groups_id'][$key];
					;
					$upd['user_no'] = $newid;

					if( intval( $data['id_edit'][$key] ) > 0 )
					{
						$upd['update_date'] = $tgl;
						$upd['update_user'] = $this->authentication->get_info_user( 'username' );
						$this->db->where( 'id', $data['id_edit'][$key] );
						$this->db->update( $this->nm_tbl, $upd );
						$type = "update";
					}
					else
					{
						$upd['create_user'] = $this->authentication->get_info_user( 'username' );
						$this->db->insert( $this->nm_tbl, $upd );
						$type = "insert";
					}
					if( $this->db->_error_message() )
					{
						$msg                  = "Gagal memproses data<br>" . $this->db->_error_message();
						$sql['message']       = $this->db->last_query();
						$sql['priority']      = 1;
						$sql['priority_name'] = 'Gawat';
						$sql['type']          = $type;
						$this->crud->save_log( $sql );
						$id = 0;
						return FALSE;
					}
					else
					{
						return TRUE;
					}
				}
			}
		}
		else
		{
			return TRUE;
		}
	}

	function delete_data( $id )
	{
		$this->db->where_in( 'id', $id );
		$this->db->delete( $this->nm_tbl );
		$jml = $this->db->affected_rows();
		return $jml;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
