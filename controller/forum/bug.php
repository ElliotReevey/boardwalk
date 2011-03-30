<?php

	class Bug extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');			
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
			
			$this->load->model('forum');
			$data = $this->forum->topics(4);
			$data['messages'] = $this->session->displaydata('messages');
			$this->load->view("forum/topics",$data);
			
		}

		function view($id){
		
			$this->load->model('forum');
			$data = $this->forum->posts($id,'bug');
			$data['messages'] = $this->session->displaydata('messages');
			$this->load->view("forum/posts",$data);
			
		}
		
		function create(){
		
			$this->load->model('forum');
			$data = $this->forum->create(4);
			$this->load->view("forum/create",$data);
		
		}
		
		function newpost($topicid){
		
			$this->load->model('forum');
			$data = $this->forum->newpost($topicid);
			
		}

		function like($topicid) {
		
			$this->load->model('forum');
			$data = $this->forum->like($topicid,'bug');
			if($data){
				$this->session->set_displaydata('messages',$data);
				$site_url = $this->core->get_config_item('base_url');
				header("Location: ".$site_url."forum/bug/");
			}	
		}		

		function topiclike($topicid) {
			$this->load->model('forum');
			$data = $this->forum->like($topicid,'bug');
			if($data){
				$this->session->set_displaydata('messages',$data);
				$site_url = $this->core->get_config_item('base_url');
				header("Location: ".$site_url."forum/bug/view/".$topicid."/");
			}
		}

		function postlike($postid) {
		
			$this->load->model('forum');
			$data = $this->forum->postlike($postid,'bug');
		
		}

	}