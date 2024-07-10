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
					if( ! empty( $data['kriteria'][$key] ) )
					{
						$this->crud->crud_table( _TBL_COMBO );
						$this->crud->crud_field( 'pid', $newid, 'int' );
						$this->crud->crud_field( 'data', $data['kriteria'][$key] );
						$this->crud->crud_field( 'urut', $data['kode'][$key], 'int' );
						$this->crud->crud_field( 'kelompok', 'kriteria-like' );
						$this->crud->crud_field( 'urut', ++$no );

						if( intval( $data['edit_id'][$key] ) > 0 )
						{
							$this->crud->crud_where( [ 'field' => 'id', 'value' => $row, 'op' => '=' ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->crud_type( 'edit' );
						}
						else
						{
							$title = create_unique_slug( $data['kriteria'][$key], _TBL_COMBO );
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
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
