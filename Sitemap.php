<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Sitemap extends Public_Controller {


	public function __construct()
	{
		parent::__construct();

	}
	public function index()
	{
		$this->load->helper('xml');
		$data['items'] = $this->Item_model->get_all();
		$data['items_count'] = count($data['items']) + 3;
		$this->load->view('front/sitemap', $data);
	}
}
