<?php
base_path();

class Views_model extends CI_Model
{
	protected $TABLE_PAGE_VIEWS = 'page_views';
	protected $TABLE_CHANNELS = 'channels';
	protected $TABLE_SATELLITES = 'satellites';
	private $COL_view_id = 'view_id';
	private $COL_user_ip = 'user_ip';
	private $COL_content_type = 'content_type';
	private $COL_content_url = 'content_url';
	private $COL_satellite_id = 'satellite_id';
	private $COL_channel_id = 'channel_id';
	private $COL_time = 'time';



	public function new_page_view($content_type, $content_url, $satellite_id, $channel_id){
		$new = array(
			$this->COL_user_ip => get_client_ip(),
			$this->COL_content_type => $content_type,
			$this->COL_content_url => $content_url,
			$this->COL_satellite_id => $satellite_id,
			$this->COL_channel_id => $channel_id
		);
		$this->db->insert($this->TABLE_PAGE_VIEWS, $new);
		$insertId = $this->db->insert_id();
		if (isset($insertId)){
			if ($satellite_id != 0){
				$page_views = $this->get_views_count($satellite_id, $this->COL_satellite_id, $this->TABLE_SATELLITES);
				$update = array(
					"views" => $page_views+1,
				);
				$this->db->where($this->COL_satellite_id, $satellite_id);
				$this->db->update($this->TABLE_SATELLITES, $update);
			}
			if ($channel_id != 0){
				$page_views = $this->get_views_count($channel_id, $this->COL_channel_id, $this->TABLE_CHANNELS);
				$update = array(
					"views" => $page_views+1,
				);
				$this->db->where($this->COL_channel_id, $channel_id);
				$this->db->update($this->TABLE_CHANNELS, $update);
			}
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function get_total($timed = NULL, $distinct = NULL){
		if ($distinct==TRUE){
			$this->db->select("count(DISTINCT(user_ip)) as view_count");
		}else{
			$this->db->select("count(view_id) as view_count");
		}
		$this->db->from($this->TABLE_PAGE_VIEWS);
		$query = $this->db->get();
		$array = $query->row_array();
		return $array['view_count'];
	}
	private function get_views_count($item_id, $item, $table){
		$this->db->select('views');
		$this->db->where($item, $item_id);
		$query = $this->db->get($table);
		$array = $query->row_array();
		return $array['views'];
	}

	public function get_most_searched($item_type, $limit){
		if ($item_type == SATELLITE){
			$this->db->select("satellite_id, count(satellite_id) as item_count");
			$this->db->from($this->TABLE_PAGE_VIEWS);
			$this->db->group_by($this->COL_satellite_id);
			$this->db->where_not_in($this->COL_satellite_id, array('0'));
		}elseif($item_type == CHANNEL){
			$this->db->select("channel_id, count(channel_id) as item_count");
			$this->db->from($this->TABLE_PAGE_VIEWS);
			$this->db->group_by($this->COL_channel_id);
			$this->db->where_not_in($this->COL_channel_id, array('0'));
		}
		$this->db->order_by('item_count', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		if ($item_type == SATELLITE){
			return array_column($query->result_array(), $this->COL_satellite_id);
		}elseif($item_type == CHANNEL){
			return array_column($query->result_array(), $this->COL_channel_id);
		}
	}


}
