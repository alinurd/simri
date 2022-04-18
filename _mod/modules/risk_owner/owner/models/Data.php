<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}
	
	function get_data_posisi_menu(){
		$level=$this->db->where('kelompok', 'level-approval')->where('active', 1)->get(_TBL_COMBO)->result_array();
		$arrLevel=[];
		foreach($level as $row){
			$arrLevel[$row['id']]=$row;
		}

		$this->db->select('*');
		$this->db->from(_TBL_OFFICER);
		$this->db->where('sts_owner', 1);
		$this->db->order_by('owner_no');
		$query=$this->db->get();
		$rows=$query->result_array();
		$arr_photo=array();
		foreach($rows as $pht){
			$arr_photo[$pht['owner_no']]=$pht;
		}
		
		$this->level=array();
		$this->db->select('*');
		$this->db->from(_TBL_OWNER);
		$this->db->order_by('no');
		$query=$this->db->get();
		$rows=$query->result_array();
		foreach($rows as $row){
			$tel = $row['owner_name'];
			$photo="";
			$name="";
			if (array_key_exists($row['id'], $arr_photo)){
				$photo=$arr_photo[$row['id']]['photo'];
				$name=$arr_photo[$row['id']]['officer_name'];
			}
			$level = explode(',',$row['level_approval']);
			$sts_approval='';
			if($level){
				foreach($level as $ad){
					$id=intval($ad);
					if (array_key_exists($id, $arrLevel)){
						$sts_approval .= ' &nbsp;<span class="label pointer" title=" Level : '.$arrLevel[$id]['data'].' " style="background-color:'.$arrLevel[$id]['param_string'].';color:#ffffff; padding:5px 15px;"> <i class="fa fa-key" ttle=" Masuk dalam pelaporan "></i> '.$arrLevel[$id]['kode'].' </span>';
					}
				}
			}
			$input[] = array("id" => $row['id'], "level_approval" => $row['level_approval'], "approval" => $sts_approval, "kode_approval" => '**', "warna_approval" => 'color', "code" => $row['owner_code'], "title" => $tel, "slug" => $row['pid'], "photo" => $photo, "name" => $name, "status" => $row['active'], "act" => '');
		}
		
		$result = _tree($input);
		// Doi::dump($result);die();
		return $result;
	}
	
	function simpan_data($data){
		$output_data = stripslashes($data['data']);
		$rows = json_decode($output_data);
		$type='update';
		$n = 0;
		foreach($rows as $row) { 
			$n++; 
			$n1 = 0;
			$update_id = $row->id;
			
			$this->crud->crud_table(_TBL_OWNER);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('pid', 0, 'int');
			$this->crud->crud_field('urut', $n, 'int');
			$this->crud->crud_field('level', 0, 'int');
			$this->crud->crud_where(['field'=>'id', 'value'=>$row->id]);
			$this->crud->process_crud();

			if(!empty($row->children)){
			foreach ($row->children as $vchild){ 
				$n1++; 
				$n2 = 0;
				
				$this->crud->crud_table(_TBL_OWNER);
				$this->crud->crud_type('edit');
				$this->crud->crud_field('pid', $row->id, 'int');
				$this->crud->crud_field('urut', $n1, 'int');
				$this->crud->crud_field('level', 1, 'int');
				$this->crud->crud_where(['field'=>'id', 'value'=>$vchild->id]);
				$this->crud->process_crud();

				if(!empty($vchild->children)){
				foreach ($vchild->children as $vchild1){ 
					$n2++; 
					$n3 = 0;

					$this->crud->crud_table(_TBL_OWNER);
					$this->crud->crud_type('edit');
					$this->crud->crud_field('pid', $vchild->id, 'int');
					$this->crud->crud_field('urut', $n2, 'int');
					$this->crud->crud_field('level', 2, 'int');
					$this->crud->crud_where(['field'=>'id', 'value'=>$vchild1->id]);
					$this->crud->process_crud();

					if(!empty($vchild1->children)){
					foreach ($vchild1->children as $vchild2){ 
						$n3++; 
						$n4 = 0;

						$this->crud->crud_table(_TBL_OWNER);
						$this->crud->crud_type('edit');
						$this->crud->crud_field('pid', $vchild1->id, 'int');
						$this->crud->crud_field('urut', $n3, 'int');
						$this->crud->crud_field('level', 3, 'int');
						$this->crud->crud_where(['field'=>'id', 'value'=>$vchild2->id]);
						$this->crud->process_crud();

						if(!empty($vchild2->children)){
						foreach ($vchild2->children as $vchild3){ 
							$n4++;
							$n5=0;

							$this->crud->crud_table(_TBL_OWNER);
							$this->crud->crud_type('edit');
							$this->crud->crud_field('pid', $vchild2->id, 'int');
							$this->crud->crud_field('urut', $n4, 'int');
							$this->crud->crud_field('level', 4, 'int');
							$this->crud->crud_where(['field'=>'id', 'value'=>$vchild3->id]);
							$this->crud->process_crud();

							if(!empty($vchild3->children)){
							foreach ($vchild3->children as $vchild4){ 
								$n5++;
								$n6=0;

								$this->crud->crud_table(_TBL_OWNER);
								$this->crud->crud_type('edit');
								$this->crud->crud_field('pid', $vchild3->id, 'int');
								$this->crud->crud_field('urut', $n5, 'int');
								$this->crud->crud_field('level', 5, 'int');
								$this->crud->crud_where(['field'=>'id', 'value'=>$vchild4->id]);
								$this->crud->process_crud();

							}
							}
						}
						}
					}
					}
				}
				}
			}
			}
		}

		return TRUE ;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */