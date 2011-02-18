<?php

	class Search extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
			
			$this->load->view("world/search");

		}
		
		function player(){
		
			if(isset($_POST['playerSearch'])) {
				$search = $_POST['searchName'];
				$status = $_POST['accStatus'];
				$activity = $_POST['accActivity'];
				
				if($this->validation->userName($search)) {
					if($this->validation->is_numeric($status)) {
						if($this->validation->is_numeric($activity)) {
							
							$this->load->model('searchresults');
							$data['results'] = $this->searchresults->search_perform($search,"username","users",$status,$activity);
							$data['search_term'] = $search;
							$data['search_type'] = "Player Search";
							$data['result_type'] = "users";
							$data['site_url'] = $this->core->get_config_item('base_url');
							$this->load->view("world/searchresults",$data);
			
						} else {
							$data['fail'] = "Invalid activity, try again.";
						}
					} else {
						$data['fail'] = "Invalid status, try again.";
					}
				} else {
					$data['fail'] = "The search term you entered was invalid.";
				}
				
				if(isset($data['fail'])) {
					$this->load->view("world/search",$data);
				}
				
			} else {
				$this->load->view("world/search");
			}
			
		}

		function crew(){
		
			if(isset($_POST['crewSearch'])) {
				$search = $_POST['searchCrew'];
			
				if($this->validation->userName($search)) {
					$check = $this->db->query("SELECT * FROM crews WHERE crewname = '$search'");
					if($check){
					
						$this->load->model('searchresults');
						$data['results'] = $this->searchresults->search_perform($search,"crewname","crews");
						$data['search_term'] = $search;
						$data['search_type'] = "Crew Search";
						$data['result_type'] = "crews";
						$data['site_url'] = $this->core->get_config_item('base_url');
						$this->load->view("world/searchresults",$data);
					
					} else {
						$data['fail'] = "The crewname you entered does not exist.";
					}			
				} else {
					$data['fail'] = "The crewname you entered was invalid.";
				}
			}
		
		}
						
		
	}