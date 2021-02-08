<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->load->model('Views_model');

		$data = array();
		$this->load->view('admin/_parts/header',$data);
	}

	public function index()
	{
		$data['page_views']['life_time_views'] = $this->Views_model->get_total();
		$data['page_views']['life_time_users'] = $this->Views_model->get_total(FALSE,TRUE);
		$this->load->view('admin/dashboard/index', $data);
		$this->load->view('admin/_parts/footer');
	}

	public function profile()
	{
		$data['response'] = NULL;
		if($this->input->post('submit') != NULL ){
			$postData = $this->input->post();
			$data['response'] = $this->Dashboard_model->update_profile($postData);
		}
		$data['edit_data'] = $this->Dashboard_model->get_profile();
		$this->load->view('admin/dashboard/profile', $data);
		$this->load->view('admin/_parts/footer');
	}

	public function logout()
	{
		ta_logout();
	}
}
