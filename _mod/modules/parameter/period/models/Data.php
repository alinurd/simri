<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function save_detail( $newid = 0, $data = array(), $img = "" )
	{
		if( isset( $data['edit_id'] ) )
		{
			if( count( $data['edit_id'] ) > 0 )
			{
				$no = 0;
				foreach( $data['edit_id'] as $key => $row )
				{
					if( ! empty( $data['term'][$key] ) )
					{
						$this->crud->crud_table( _TBL_COMBO );
						$this->crud->crud_field( 'pid', $newid, 'int' );
						$this->crud->crud_field( 'data', $data['term'][$key] );
						$this->crud->crud_field( 'param_date', $data['param_date'][( $key * 2 ) + 1], 'date' );
						$this->crud->crud_field( 'param_date_after', $data['param_date_after'][( $key * 2 ) + 1], 'date' );
						$this->crud->crud_field( 'kelompok', 'term' );
						$this->crud->crud_field( 'urut', ++$no );

						if( intval( $data['edit_id'][$key] ) > 0 )
						{
							$this->crud->crud_where( [ 'field' => 'id', 'value' => $row, 'op' => '=' ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->crud_type( 'edit' );
						}
						else
						{
							$title = create_unique_slug( $data['term'][$key], _TBL_COMBO );
							$this->crud->crud_field( 'uri_title', $title );
							$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
							$this->crud->crud_type( 'add' );
						}
						$this->crud->process_crud();
					}
				}
			}
		}
		if( isset( $data['edit_id_2'] ) )
		{
			if( count( $data['edit_id_2'] ) > 0 )
			{

				$no = 0;
				foreach( $data['edit_id_2'] as $key => $row )
				{
					if( ! empty( $data['bulan'][$key] ) && ! empty( $data['minggu'][$key] ) )
					{
						$monthName = date( "F", mktime( 0, 0, 0, $data['bulan'][$key], 10 ) );
						$this->crud->crud_table( _TBL_COMBO );
						$this->crud->crud_field( 'pid', $newid, 'int' );
						$this->crud->crud_field( 'data', $data['minggu'][$key] );
						$this->crud->crud_field( 'param_int', $data['bulan'][$key] );
						$this->crud->crud_field( 'param_string', $monthName );
						$this->crud->crud_field( 'param_date', $data['param_date_2'][( $key * 2 ) + 1], 'date' );
						$this->crud->crud_field( 'param_date_after', $data['param_date_after_2'][( $key * 2 ) + 1], 'date' );
						$this->crud->crud_field( 'kelompok', 'minggu' );
						$this->crud->crud_field( 'urut', ++$no );

						if( intval( $data['edit_id_2'][$key] ) > 0 )
						{
							$this->crud->crud_where( [ 'field' => 'id', 'value' => $row, 'op' => '=' ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->crud_type( 'edit' );
						}
						else
						{
							$title = create_unique_slug( $data['bulan'][$key] . '-' . $data['minggu'][$key], _TBL_COMBO );
							$this->crud->crud_field( 'uri_title', $title );
							$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
							$this->crud->crud_type( 'add' );
						}
						$this->crud->process_crud();
					}
				}
			}
		}
		return TRUE;
	}

	function delete_data( $id )
	{
		$this->db->where( 'id', $id );
		$this->db->delete( _TBL_GROUP_USER );
		$jml = $this->db->affected_rows();
		// die($this->db->last_query());
		$hasil['sts'] = 0;
		$hasil['ket'] = 'Gagal Mengahapus';

		if( $jml > 0 )
		{
			$hasil['sts'] = $jml;
			$hasil['ket'] = 'data berhasil dihapus';
		}
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
