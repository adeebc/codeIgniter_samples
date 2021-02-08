<?php
base_path();

class Satellite_model extends CI_Model
{
	protected $TABLE_SATELLITE = 'satellites';
	private $COL_satellite_id = 'satellite_id';
	private $COL_satellite_name = 'satellite_name';
	private $COL_views = 'views';
	private $COL_status = 'status';



	public function add($data){
		$new = array(
			$this->COL_satellite_name => $data[$this->COL_satellite_name],
			$this->COL_status => 1
		);
		$this->db->insert($this->TABLE_SATELLITE, $new);

		$insertId = $this->db->insert_id();
		if (isset($insertId)){
			return $insertId;
		}else{
			return 0;
		}
	}

	public function update($data, $id){
		$update = array(
			$this->COL_satellite_name => $data[$this->COL_satellite_name]
		);
		if (isset($data[$this->COL_status])){
			$update[$this->COL_status] = $data[$this->COL_status];
		}else{
			$update[$this->COL_status] = 0;
		}
		$this->db->where($this->COL_satellite_id, $id);
		$updateID = $this->db->update($this->TABLE_SATELLITE, $update);
		if (isset($updateID)){
			return $updateID;
		}else{
			return 0;
		}
	}

	public function get_one($id){
		$this->db->select('*');
		$this->db->where($this->COL_satellite_id, $id);
		$query = $this->db->get($this->TABLE_SATELLITE);
		return $query->row_array();
	}

	public function get_all($status){
		$this->db->select('*');
		$this->db->order_by($this->COL_satellite_id, 'ASC');
		$this->db->where($this->COL_status, $status);
		$query = $this->db->get($this->TABLE_SATELLITE);
		return $query->result_array();
	}

	public function get_most_searched(){
		$this->db->select('*');
		$this->db->from($this->TABLE_SATELLITE);
		$this->db->order_by('views', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function delete($id){
		$this->db->where($this->COL_satellite_id, $id);
		return $this->db->delete($this->TABLE_SATELLITE);
	}


}
