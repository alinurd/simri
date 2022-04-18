<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	public function get_data_monitoring() {
        date_default_timezone_set('Asia/Jakarta');
        $data = array(
            'id',
            'ip_address',
            'timestamp',
            'data'
        );
        $this->db->select($data);
        $query = $this->db->get($this->tbl_ci_sessions);
        $no = 1;
        foreach ($query->result() as $row)
        {   
            $session_data = $row->data;
			$return_data = array();
            $offset = 0;
            while ($offset < strlen($session_data)) {
                if (!strstr(substr($session_data, $offset), "|")) {
                    throw new Exception("invalid data, remaining: " . substr($session_data, $offset));
                }
                $pos = strpos($session_data, "|", $offset);
                $num = $pos - $offset;
                $varname = substr($session_data, $offset, $num);
                $offset += $num + 1;
                $data = unserialize(substr($session_data, $offset));
                $return_data[$varname] = $data;
                $offset += strlen(serialize($data));
            }
            if(!empty($return_data['user_info'])){
                echo "<tr>";
                echo "<td style=\"text-align: right\"><div id=\"dv_ip_$no\">".date("d-m-Y H:i:s",$return_data['__ci_last_regenerate'])."</div></td>";
                echo "<td style=\"text-align: left\">".$return_data['user_info']['nama_lengkap']."</td>";
                echo "<td style=\"text-align: center\"><div id=\"dv_$no\">".$row->ip_address."</div></td>";
                echo "<td style=\"text-align: left\">".$return_data['info_agen']['browser']."</td>";
                echo "<td style=\"text-align: left\">".$return_data['info_agen']['platform']."</td>";
                echo "<td style=\"text-align: center\">"
                    . " <button class=\"btn btn-sm btn-danger\" onclick=\"logout_user('".$row->id."','".$return_data['namapengguna']."');\">Logout</button></td>";
                echo "</tr>"; 
                $no++;
            }
        }
    }
	
	function logout_user() {
        $this->db->delete($this->tbl_ci_sessions, array('id' => $this->input->post('id')));
        return true;        
    }
}

/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */