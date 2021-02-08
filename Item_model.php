<?php
base_path();

class Item_model extends CI_Model
{
	protected $TABLE_ITEM = 'items';
	protected $TABLE_CHANNEL = 'channels';
	protected $TABLE_SATELLITE = 'satellites';

	private $COL_channel_id = 'channel_id';
	private $COL_channel_name = 'channel_name';
	private $COL_satellite_id = 'satellite_id';
	private $COL_satellite_name = 'satellite_name';

	private $COL_col_frequency = 'col_frequency';
	private $COL_col_system = 'col_system';
	private $COL_col_sr = 'col_sr';
	private $COL_col_fec = 'col_fec';
	private $COL_col_group = 'col_group';
	private $COL_col_type = 'col_type';


	//ADD or UPDATE
	public function update($data){
		$new = array(
			$this->COL_channel_id => $data[$this->COL_channel_id],
			$this->COL_satellite_id => $data[$this->COL_satellite_id],
			$this->COL_col_frequency => $data[$this->COL_col_frequency],
			$this->COL_col_system => $data[$this->COL_col_system],
			$this->COL_col_sr => $data[$this->COL_col_sr],
			$this->COL_col_fec => $data[$this->COL_col_fec],
			$this->COL_col_group => $data[$this->COL_col_group],
			$this->COL_col_type => $data[$this->COL_col_type],
		);
		$this->db->replace($this->TABLE_ITEM, $new);
		return TRUE;
	}

	//GET SINGLE ITEM
	public function get_one($channel_id, $satellite_id){
		$this->db->select('*');
		$where = array(
			'items.channel_id' => $channel_id,
			'items.satellite_id' => $satellite_id
		);
		$this->db->from($this->TABLE_ITEM);
		$this->db->join($this->TABLE_CHANNEL,'items.channel_id = channels.channel_id');
		$this->db->join($this->TABLE_SATELLITE,'items.satellite_id = satellites.satellite_id');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

	//GET ALL ITEMS
	public function get_all(){
		$this->db->select('*');
		$this->db->from($this->TABLE_ITEM);
		$this->db->join($this->TABLE_CHANNEL,'items.channel_id = channels.channel_id');
		$this->db->join($this->TABLE_SATELLITE,'items.satellite_id = satellites.satellite_id');
		$query = $this->db->get();
		return $query->result_array();
	}

	//SEARCH ITEMS
	public function search($item, $id, $start = NULL)
	{
		if ($item == SATELLITE){
			return $this->get_all_by_channel($id, $start);
		}elseif ($item == CHANNEL){
			return $this->get_all_by_satellite($id, $start);
		}else{
			return array("status" => FALSE);
		}
	}

	public function count_all_search($item, $id) {
		$this->db->from($this->TABLE_ITEM);
		if ($item == SATELLITE){
			$this->db->join($this->TABLE_SATELLITE,'items.satellite_id = satellites.satellite_id');
			$this->db->where('items.channel_id', $id);
		}elseif ($item == CHANNEL){
			$this->db->join($this->TABLE_CHANNEL,'items.channel_id = channels.channel_id');
			$this->db->where('items.satellite_id', $id);
		}
		return $this->db->get()->num_rows();
	}

	//GET ALL BY CHANNEL
	public function get_all_by_channel($channel_id, $start = NULL)
	{
		$this->db->select("items.satellite_id, satellite_name, items.channel_id");
		$this->db->from($this->TABLE_ITEM);
		//$this->db->join($this->TABLE_CHANNEL,'items.channel_id = channels.channel_id');
		$this->db->join($this->TABLE_SATELLITE,'items.satellite_id = satellites.satellite_id');
		$this->db->where('items.channel_id', $channel_id);
		if ($start!=NULL){
			$this->db->limit(PAGINATION_PER_PAGE, $start);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	//GET ALL BY SATELLITE
	public function get_all_by_satellite($satellite_id, $start = NULL)
	{
		$this->db->select("items.channel_id, channel_name, items.satellite_id");
		$this->db->from($this->TABLE_ITEM);
		$this->db->join($this->TABLE_CHANNEL,'items.channel_id = channels.channel_id');
		//$this->db->join($this->TABLE_SATELLITE,'items.satellite_id = satellites.satellite_id');
		$this->db->where('items.satellite_id', $satellite_id);
		if ($start!=NULL){
			$this->db->limit(PAGINATION_PER_PAGE, $start);
		}
		$this->db->order_by($this->COL_channel_name, 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function search_item($search_value, $satellite_id){
		$this->db->select("items.channel_id, channel_name, items.satellite_id");
		$this->db->from($this->TABLE_ITEM);
		$this->db->join($this->TABLE_CHANNEL,'items.channel_id = channels.channel_id');
		$this->db->where('items.satellite_id', $satellite_id);
		$this->db->like($this->COL_col_frequency, $search_value, 'both');
		$this->db->order_by($this->COL_channel_name, 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
	//DELETE ITEM
	public function delete($channel_id, $satellite_id){
		$where = array(
			$this->COL_channel_id => $channel_id,
			$this->COL_satellite_id => $satellite_id
		);
		$this->db->where($where);
		return $this->db->delete($this->TABLE_ITEM);
	}


}
