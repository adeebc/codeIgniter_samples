<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Satellite extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$data = array();
		$this->load->model('Channel_model');
		$this->load->model('Satellite_model');
		$this->load->view('admin/_parts/header',$data);
	}

	public function index(){
		if (isset($_GET['delete'])){
			$data['delete_message'] = $this->Satellite_model->delete($_GET['delete']);
		}
		$data['list_all'] = $this->Satellite_model->get_all(1);
		$this->load->view('admin/satellite/index', $data);
		$this->load->view('admin/_parts/footer');
	}

	public function add(){
		$data['response'] = NULL;
		if($this->input->post('submit') != NULL ){
			$postData = $this->input->post();
			$data['response'] = $this->Satellite_model->add($postData);
		}
		$this->load->view('admin/satellite/add', $data);
		$this->load->view('admin/_parts/footer');
	}

	public function edit($ID = NULL){
		$data['response'] = NULL;
		if($this->input->post('submit') != NULL ){
			$postData = $this->input->post();
			$data['response'] = $this->Satellite_model->update($postData, $ID);
		}
		$data['edit_data'] = $this->Satellite_model->get_one($ID);
		$this->load->view('admin/satellite/update', $data);
		$this->load->view('admin/_parts/footer');
	}

}
