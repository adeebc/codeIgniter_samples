<?php
base_path();

class Channel_model extends CI_Model
{
	protected $TABLE_CHANNEL = 'channels';
	private $COL_channel_id = 'channel_id';
	private $COL_channel_name = 'channel_name';
	private $COL_views = 'views';
	private $COL_status = 'status';



	public function add($data){
		$new = array(
			$this->COL_channel_name => $data[$this->COL_channel_name],
			$this->COL_status => 1
		);
		$this->db->insert($this->TABLE_CHANNEL, $new);

		$insertId = $this->db->insert_id();
		if (isset($insertId)){
			return $insertId;
		}else{
			return 0;
		}
	}

	public function update($data, $id){
		$update = array(
			$this->COL_channel_name => $data[$this->COL_channel_name]
		);
		if (isset($data[$this->COL_status])){
			$update[$this->COL_status] = $data[$this->COL_status];
		}else{
			$update[$this->COL_status] = 0;
		}
		$this->db->where($this->COL_channel_id, $id);
		$updateID = $this->db->update($this->TABLE_CHANNEL, $update);
		if (isset($updateID)){
			return $updateID;
		}else{
			return 0;
		}
	}

	public function get_one($id){
		$this->db->select('*');
		$this->db->where($this->COL_channel_id, $id);
		$query = $this->db->get($this->TABLE_CHANNEL);
		return $query->row_array();
	}

	public function get_all($status, $start = NULL){
		$this->db->select('*');
		$this->db->where($this->COL_status, $status);
		$this->db->order_by($this->COL_channel_name, 'ASC');
		if ($start!=NULL){
			$this->db->limit(PAGINATION_PER_PAGE, $start);
		}
		$query = $this->db->get($this->TABLE_CHANNEL);
		return $query->result_array();
	}

	public function get_most_searched(){
		$this->db->select('*');
		$this->db->from($this->TABLE_CHANNEL);
		$this->db->order_by('views', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function count_all() {
		$this->db->from($this->TABLE_CHANNEL);
		return $this->db->get()->num_rows();
	}

	public function delete($id){
		$this->db->where($this->COL_channel_id, $id);
		return $this->db->delete($this->TABLE_CHANNEL);
	}


}
