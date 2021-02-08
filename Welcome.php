<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Public_Controller {
	public function __construct()
	{
		parent::__construct();
		/*
		 * Common Data
		 */
		$data['sidebar_satellite_list'] = $this->Satellite_model->get_most_searched();

		$this->load->vars($data);
		/*
		 * End of Common Data
		 */
	}
	public function index()
	{
		$this->output->cache(10);
		//START - SEO
		$data['seo'] = seo(
			WEBSITE_NAME.' - Transponder Search - Satellites & Channels List',
			'Telephent is a transponder search website. Telephent provides satellites and channels list. 
			Telephent also provides channel\'s specifications like channel frequency, system, Symbol Rate, FEC, group, and type.',
			WEBSITE_NAME.' - Transponder Search - Satellites & Channels List'
		);
		//END - SEO
		//START - CREATE PAGE VIEW
		$this->Views_model->new_page_view(
			'home_page',
			current_url(),
			0,
			0
		);
		//END - CREATE PAGE VIEW

		$data['channel_list_search'] = $this->Channel_model->get_all(1);
		$data['channel_list'] = $this->Channel_model->get_most_searched();
		$data['satellite_list'] = $this->Satellite_model->get_most_searched();

		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/home', $data);
		$this->load->view('front/_parts/footer');
	}
	public function satellites(){
		$this->output->cache(60);
		//START - SEO
		$data['seo'] = seo(
			'Satellites - '.WEBSITE_NAME,
			'',
			'Satellites'
		);
		//END - SEO
		//START - CREATE PAGE VIEW
		$this->Views_model->new_page_view(
			'satellite_list',
			current_url(),
			0,
			0
		);
		//END - CREATE PAGE VIEW

		$data['satellite_list'] = $this->Satellite_model->get_all(1);

		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/satellites', $data);
		$this->load->view('front/_parts/footer');
	}
	public function satellite($satellite_id = 1, $slug = NULL, $paged = NULL, $page = 1){
		$this->output->cache(15);
		$satellite = $this->Satellite_model->get_one($satellite_id);
		$data['satellite_id'] = $satellite_id;

		$items_count = $this->Item_model->count_all_search(CHANNEL,$satellite_id);

		$data['page']['base_url'] = base_url()."satellite/{$satellite_id}/{$slug}/page/";
		$data['page']["per_page"] = PAGINATION_PER_PAGE;
		$data['page']["current_page"] = $page;
		$data['page']["next_page"] = $page + 1;
		$data['page']["prev_page"] = $page - 1;
		$data['page']["total_pages"] = $items_count/PAGINATION_PER_PAGE;
		$data['page']["total_pages_round"] = round($data['page']["total_pages"]);
		if ($data['page']["total_pages"] > $data['page']["total_pages_round"]){
			$data['page']["total_pages"] = $data['page']["total_pages_round"] + 1;
		}else{
			$data['page']["total_pages"] = $data['page']["total_pages_round"];
		}
		if ($page != 1){
			$pagination_title = " &raquo; Page {$page} of {$data['page']["total_pages"]}";
		}else{
			$pagination_title = "";
		}

		//START - SEO
		$data['seo'] = seo(
			$satellite['satellite_name'].' - Channel List - '.WEBSITE_NAME.$pagination_title,
			'',
			$satellite['satellite_name']." - Channel List"
		);
		//END - SEO
		//START - CREATE PAGE VIEW
		$this->Views_model->new_page_view(
			'channel_list2',
			current_url(),
			$satellite_id,
			0
		);
		//END - CREATE PAGE VIEW

		$data['channel_list'] = $this->Item_model->search(CHANNEL,$satellite_id, ($page*30)-29);
		$data['channel_list_search'] = $this->Item_model->search(CHANNEL,$satellite_id);
		$data['count'] = count($data['channel_list_search']) - 1;

		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/channels2', $data);
		$this->load->view('front/_parts/footer');
	}
	public function channels($paged = NULL, $page = 1){
		$this->output->cache(10);

		$items_count = $this->Channel_model->count_all();

		$data['page']['base_url'] = base_url()."channels/page/";
		$data['page']["per_page"] = PAGINATION_PER_PAGE;
		$data['page']["current_page"] = $page;
		$data['page']["next_page"] = $page + 1;
		$data['page']["prev_page"] = $page - 1;
		$data['page']["total_pages"] = $items_count/PAGINATION_PER_PAGE;
		$data['page']["total_pages_round"] = round($data['page']["total_pages"]);
		if ($data['page']["total_pages"] > $data['page']["total_pages_round"]){
			$data['page']["total_pages"] = $data['page']["total_pages_round"] + 1;
		}else{
			$data['page']["total_pages"] = $data['page']["total_pages_round"];
		}
		if ($page != 1){
			$pagination_title = " &raquo; Page {$page} of {$data['page']["total_pages"]}";
		}else{
			$pagination_title = "";
		}

		//START - SEO
		$data['seo'] = seo(
			'Channels - '.WEBSITE_NAME.$pagination_title,
			'',
			"Channels"
		);
		//END - SEO
		//START - CREATE PAGE VIEW
		$this->Views_model->new_page_view(
			'channel_list',
			current_url(),
			0,
			0
		);
		//END - CREATE PAGE VIEW

		$data['channel_list'] = $this->Channel_model->get_all(1, ($page*30)-29);
		$data['channel_list_search'] = $this->Channel_model->get_all(1);
		$data['count'] = $items_count;

		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/channels', $data);
		$this->load->view('front/_parts/footer');
	}

	public function channel($channel_id, $slug, $details = NULL, $satellite = NULL, $satellite_id = NULL){
		$this->output->cache(15);

		if ($details!=NULL){
			$data['item'] = $this->Item_model->get_one($channel_id,$satellite_id);
			//START - SEO
			$data['seo'] = seo(
				$data['item']['channel_name'].' - '.$data['item']['satellite_name'].' - Frequency Details - '.WEBSITE_NAME,
				$data['item']['channel_name'].' - '.$data['item']['satellite_name']
				.' channel & satellite frequency, system, Symbol Rate, FEC, group, type and other details.',
				$data['item']['channel_name'].' - '.$data['item']['satellite_name']." - Details"
			);
			//END - SEO

			//START - CREATE PAGE VIEW
			$this->Views_model->new_page_view(
				'item_single',
				current_url(),
				$satellite_id,
				$channel_id
			);
			//END - CREATE PAGE VIEW

			$this->load->view('front/_parts/header', $data);
			$this->load->view('front/single', $data);
			$this->load->view('front/_parts/footer');
		}else{
			$data['channel'] = $this->Channel_model->get_one($channel_id);

			//START - SEO
			$data['seo'] = seo(
				$data['channel']['channel_name'].' - Satellite List - '.WEBSITE_NAME,
				'',
				$data['channel']['channel_name']." - Satellite List"
			);
			//END - SEO

			//START - CREATE PAGE VIEW
			$this->Views_model->new_page_view(
				'satellite_list2',
				current_url(),
				0,
				$channel_id
			);
			//END - CREATE PAGE VIEW

			$data['satellite_list'] = $this->Item_model->search(SATELLITE,$channel_id);
			$this->load->view('front/_parts/header', $data);
			$this->load->view('front/satellites2', $data);
			$this->load->view('front/_parts/footer');
		}
	}

	public function search(){
		if (isset($_GET['satellite']) AND isset($_GET['frequency'])){

			//START - SEO
			$data['seo'] = seo(
				'Search results for frequency "'.$_GET['frequency'].'" Channels - '.WEBSITE_NAME,
				'Here is the search result for the channels with frequency '.$_GET['frequency'].' .',
				'Search Results for Frequency "'.$_GET['frequency'].'"'
			);
			//END - SEO
			//START - CREATE PAGE VIEW
			$this->Views_model->new_page_view(
				'search_result',
				current_url(),
				$_GET['satellite'],
				0
			);
			//END - CREATE PAGE VIEW

			$data['satellite_id'] = $_GET['satellite'];
			$data['channel_list_search'] = $data['channel_list'] = $this->Item_model->search_item($_GET['frequency'],$_GET['satellite']);
			$data['count'] = count($data['channel_list']);
			$this->load->view('front/_parts/header', $data);
			$this->load->view('front/channels2', $data);
			$this->load->view('front/_parts/footer');
		}
	}

	/*
	 * Static Pages
	 */
	public function privacy(){
		$this->output->cache(10000);

		//START - SEO
		$data['seo'] = seo(
			'Privacy Policy - '.WEBSITE_NAME,
			'Privacy Policy - '.WEBSITE_NAME.'.',
			'Privacy Policy'
		);
		//END - SEO
		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/static/privacy', $data);
		$this->load->view('front/_parts/footer');
	}
	public function about(){
		$this->output->cache(10000);

		//START - SEO
		$data['seo'] = seo(
			'About - '.WEBSITE_NAME,
			'Telephent is a transponder search website. Telephent provides satellites and channels list.',
			'About'
		);
		//END - SEO
		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/static/about', $data);
		$this->load->view('front/_parts/footer');
	}
	public function disclaimer(){
		$this->output->cache(10000);

		//START - SEO
		$data['seo'] = seo(
			'Disclaimer - '.WEBSITE_NAME,
			'If you require any more information or have any questions about our site\'s 
			disclaimer, please feel free to contact us by email at telephent@gmail.com.',
			'Disclaimer'
		);
		//END - SEO
		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/static/disclaimer', $data);
		$this->load->view('front/_parts/footer');
	}
	public function contact(){
		$this->output->cache(10000);

		//START - SEO
		$data['seo'] = seo(
			'Contact - '.WEBSITE_NAME,
			'',
			'Contact'
		);
		//END - SEO
		$this->load->view('front/_parts/header', $data);
		$this->load->view('front/static/contact', $data);
		$this->load->view('front/_parts/footer');
	}
}
