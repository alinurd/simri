<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ion_auth_config extends MX_Model
{
	protected $tipe=0;
	public function __construct()
	{

	}


	public function set_Preference($tipe=0)
	{
		// if ($tipe>-1)
		$this->tipe=$tipe;
		$this->db->where('tipe', $this->tipe);
		$preference = $this->db
			->select('*')
			->get('preference');

		$prefs=$preference->result_array();
		$p=array();
		foreach($prefs as $key=>$pref){
			$p[$pref['uri_title']]=$pref['value'];
		}
		$arr_pref['preference-'.$this->tipe]=$p;
		$this->session->set_userdata($arr_pref);
    }

    public function get_Preference($info='', $tipe=0)
	{
		$this->tipe=$tipe;
		$arr = $this->session->userdata('preference-'.$tipe);
		if(!$arr)
			$this->set_Preference();
			
		$arr = $this->session->userdata('preference-'.$tipe);
		if (!empty($info)){
			$result='Undefine';
			if (is_array($arr)){
				if (array_key_exists($info, $arr))
					$result=$arr[$info];
			}
			return (string) $result;
		}else{
			return (array) $arr;
		}
	}
}