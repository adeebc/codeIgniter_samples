<?php
base_path();

class Dashboard_model extends CI_Model
{
	protected $TABLE_LOGIN = 'login';

	private $COL_login_id = 'login_id';
	private $COL_login_email = 'login_email';
	private $COL_login_password = 'login_password';

	public function update_profile($data)
	{
		$update = array(
			$this->COL_login_email => $data[$this->COL_login_email],
			$this->COL_login_password => md5($data[$this->COL_login_password])
		);

		$this->db->where($this->COL_login_id, 1);
		$updateID = $this->db->update($this->TABLE_LOGIN, $update);

		if (isset($updateID)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function get_profile()
	{
		$this->db->select('*');
		$this->db->where($this->COL_login_id, 1);
		$query = $this->db->get($this->TABLE_LOGIN);
		return $query->row_array();
	}

	public function login($data)
	{
		$this->db->select('count(*) as allcount');
		$this->db->where($this->COL_login_email, $data[$this->COL_login_email]);
		$q = $this->db->get($this->TABLE_LOGIN);
		$result = $q->result_array();
		if($result[0]['allcount'] != 0){
			$this->db->select('*');
			$this->db->where($this->COL_login_email,$data[$this->COL_login_email]);
			$q = $this->db->get($this->TABLE_LOGIN);
			$result = $q->row_array();
			if($result[$this->COL_login_password] == md5($data[$this->COL_login_password])){
				$response = TRUE;
			}else{
				$response = "Password does not match";
			}
		}else{
			$response = "Email does not exist";
		}
		return $response;
	}



}
